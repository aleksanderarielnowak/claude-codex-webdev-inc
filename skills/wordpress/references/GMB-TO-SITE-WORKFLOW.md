# WORKFLOW: GMB → strona WordPress (dowolne źródło → strona WP, master prompt)

> Samowystarczalny protokół dla pipeline "dowolne źródło o firmie → gotowa lokalna strona WP" (GMB zostaje jednym z wariantów wejścia).
> Rola: **Claude = mózg (planuje, dyryguje, weryfikuje). Codex = ręce (fetch, obrazy, build, WP-CLI).**
> Cel: najwyższa jakość strony przy minimalnym koszcie tokenów Claude. Podział pracy **~75% Codex / 25% Claude**.
> 🔧 **Twarde recepty z boju (CZYTAJ NAJPIERW przy buildzie):** `gmb-workflow/HARDENED-RECIPES.md` + gotowce `gmb-workflow/assets/`. To największy skok prędkości (z ~2 h debugu → ~30–40 min).

---

## 0. ZASADY NADRZĘDNE (obowiązują w każdym przebiegu)

1. **Research/fetch ZAWSZE przez Codexa, z dowolnego źródła + samodzielne wyszukiwanie w otwartej sieci.** Surowy HTML/SERP/Maps/social/rejestry nigdy nie wchodzą do kontekstu Claude — Codex trzyma na dysku, zwraca zwięzły JSON + ścieżki artefaktów.
2. **Kod/build pisze Codex; Claude planuje i weryfikuje.** Claude sam tylko: drobne edycje tekstu/config (<3 linie), CLI read-only, analiza.
3. **NIE ufaj `ready=true` na słowo dla rzeczy WIZUALNYCH/ARCHITEKTURALNYCH.** Pola JSON Codexa są wiarygodne dla faktów plikowych (ścieżki, bajty, `node --check`), ale Codex wielokrotnie raportował `ok=true` przy realnych defektach (brak menu w headerze, biała karta edytora, obrazy 404, treść `opacity:0`). To trzeba zobaczyć.
4. **Weryfikacja PO KAŻDYM ETAPIE, nie tylko na końcu.** Fundament osobno → strony osobno. Łapanie regresji wcześnie jest tańsze niż build 6 stron na zepsutym fundamencie.
5. **Specyfikacja nazywa WŁAŚCIWE narzędzie i ZABRANIA hacków.** Codex pod luźnym specem improwizuje kruche obejścia (np. serwowanie przez `php -S`, wstrzykiwanie headera do `_elementor_data`). Spec ma jawnie mówić "użyj X (np. HFE / Site Shell Local), NIE rób Y (php -S / hack CSS)".
6. **Zdjęcia AI = najdłuższy proces → generuj PIERWSZE, w tle** (`codex exec`/`codex-bg`), równolegle z provisioningiem i brandingiem. Nic nie ma czekać na obrazy.
7. **UTF-8 wszędzie** (polskie znaki). Pliki dla Codexa/WP zapisuj jawnie UTF-8; weryfikuj brak mojibake.
8. **Fallback Claude-only:** gdy Codex niedostępny (brak tokenów/MCP) — Claude robi sam tylko lekkie rzeczy (WP-CLI read, drobne edycje, planowanie); ciężki build/obrazy czekają.

---

## 1. PIPELINE — 5 faz

### Faza 0 — INTAKE (Claude)
INPUT: **jedno lub więcej dowolnych źródeł**:
- URL istniejącej strony WWW (redesign/odświeżenie albo baza pod nową stronę).
- Profil social media (Facebook / Instagram / LinkedIn / TikTok / inne).
- Wizytówka Google (GMB) — screen LUB link.
- Wpis/numer KRS, NIP albo REGON.
- Sama nazwa firmy + lokalizacja → Codex sam wyszukuje wszystkie informacje w otwartej sieci.
- Screen → Claude vision 1× (logo, nazwa, branża, kolory, ton).
- URL/link/social/KRS/NIP/REGON/sama nazwa → Codex fetch (Faza 1 obejmuje intake i normalizację).
- **Decyzja build-mode:** „Tryb edytowalności: NATYWNY Elementor (klient sam edytuje) czy HTML-section (premium/szybko, edycja przez kod)?" Pytaj na starcie, bo to determinuje architekturę buildu. Domyślnie **NATYWNY Elementor**, jeśli klient ma sam zarządzać stroną.
- Artefakt: `brief.json` (marka, branża, NAP, atrybuty, sugerowane kolory, ton).

### Faza 1 — RESEARCH (Codex FETCH)
- Tryb: **autonomiczny max**. Codex zbiera ile się da z otwartej sieci, dociąga brakujące dane z wielu źródeł i normalizuje wszystko do `brief.json` / `research.json`.
- KRS/NIP/REGON: nazwa rejestrowa, adres, PKD/branża, zarząd/forma prawna.
- Google Maps/GMB: NAP, godziny, ocena+liczba opinii, destylat opinii (atuty/słabości/tematy), zdjęcia.
- Social: ton marki, oferta, zdjęcia, aktywność.
- SERP: konkurencja (3-5), keywords (brand/lokalne/ofertowe/informacyjne).
- Jeśli istnieje strona WWW: zaciągnij obecną treść, branding i strukturę; ustal czy robimy redesign/odświeżenie istniejącej strony czy nową stronę.
- Luki wypełnij rozsądnymi założeniami branżowymi. Eskaluj do usera tylko krytyczne braki (np. telefon, godziny otwarcia) jako lista `needs_user` w `research.json`; drobiazgi nie blokują przebiegu.
- **NOWE — research TYPU biznesu:** jakie TYPY podstron są standardem/konwersyjne dla tej branży. Przykłady:
  - gastro/restauracja → ładne **menu jedzeniowe na stronie głównej**, galeria dań, rezerwacja, dowóz/na wynos
  - firma budowlana/wykonawcza → **Realizacje/Projekty** (portfolio z foto przed/po), Usługi, Wycena
  - beauty/salon → Cennik usług, Galeria metamorfoz, Rezerwacja online, Zespół
  - prawnik/usługi B2B → Specjalizacje, Case studies, Zespół, Kontakt z formularzem
  - e-com lokalny → Produkty/Kategorie, Dostawa, Opinie
- Artefakt: `research.json` (+ `needs_user`, `business-type-playbook` = rekomendowane typy podstron + sekcje pod tę branżę).

### Faza 2 — ŚRODOWISKO (Codex, ale na PRAWDZIWYM site Local)
> **KRYTYCZNE — nie powtórzyć błędu:** NIE serwować przez `php -S`/`router.php` i NIE podrzucać folderu do `Local Sites` bez rejestracji. Jednowątkowy `php -S` zabija edytor Elementora (biała karta) i ładowanie wielu obrazów (404 pod współbieżnością), a niezarejestrowany site nie pojawia się w aplikacji Local.
- **Site MUSI być prawdziwym site'em Local** (nginx + php-fpm + MySQL + domena `.local` + SSL), widocznym w aplikacji.
- Local nie ma publicznego CLI do tworzenia site'ów → **user tworzy pusty site w aplikacji Local** (nazwa, PHP 8.2+, najnowszy WP) — to ~1 min.
- **WP-CLI odpala CLAUDE, nie Codex** (KLUCZOWA korekta v2): sandbox `codex exec` na Windows+Local NIE wykonuje `php.exe`/`wp.cmd` („Odmowa dostępu") — patrz `gmb-workflow/HARDENED-RECIPES.md` §1. Claude buduje wrapper wp-cli (php-cli.ini + wp.cmd + wp-cli-db.php — §2) i nim provisionuje: motyw + wtyczki, permalinki `/%postname%/`.
- **Od razu HTTPS** (§3): `wp option update home/siteurl https://…` + `wp search-replace http→https --all-tables` — inaczej zdjęcia po http = mixed-content (puste u usera). **WP_DEBUG/eksperyment ikon** (§4): wyłącz `elementor_experiment-e_font_icon_svg`, ustaw WP_DEBUG_DISPLAY=false.
- Artefakt: `env.json` (site_url https `.local`, admin, ścieżki wp.cmd, mysqlPort, wersje).
- Skille WP do oparcia ops: `wp-wpcli-and-ops`, `wp-playground`, `blueprint`, `wp-rest-api`.

### Faza 3 — DESIGN (Claude planuje)
- **Struktura: 5-10 podstron GOTOWYCH (Claude decyduje ile) — MAKSYMALNIE jakościowe i UNIKALNE, dobrane pod TYP biznesu** (z Fazy 1). Mało podstron = każda dopracowana, nie generyczna. Typ biznesu MA przełożenie na design.
- **Strony ofertowe** wolno tworzyć jako **placeholder (pusty publish) wpięty w nawigację** — treść dojdzie później.
- **ZAWSZE „Aktualności" (blog) + 3 wpisy z samym tytułem jako placeholder**, wpięte w menu. Strona Aktualności = ZAPROJEKTOWANA (banner + kafelki wpisów przez mu-plugin `[kw_aktualnosci]`), `page_for_posts=0` — NIE gołe archiwum motywu. Patrz `HARDENED-RECIPES.md` §11.
- **CTA bez numerów telefonu** w etykietach („Zadzwoń teraz", nie „Zadzwoń 880…"); link `tel:` zostaje.
- **Mobile = standard** („ma być zajebiście"): brandowy hamburger, pełnoszerokie CTA, typografia/paddingi responsywne, gridy stackują (§12). Weryfikuj zrzutem 390px.
- Zawsze: strona Kontakt z formularzem (CF7) + mapa (Google embed iframe bez klucza — §9).
- Branding: paleta (z logo/branży), Google Fonts (np. serif display + sans), system sekcji.
- Artefakt: `design-spec.json` (paleta, fonty, per-strona sekcje, briefy zdjęć AI, guardrails).

### Faza 4 — WYKONANIE (podział AUTHOR/APPLY — patrz HARDENED-RECIPES §5)
> v2: Codex AUTORUJE pliki na dysk (z placeholderami `__IMG_*__`/`__CF7__`/`__MAP__`), CLAUDE APLIKUJE przez wp-cli + deterministyczny `postprocess-fixes.js` (Codex sandbox nie odpala php/wp). Weryfikacja per etap: NAJPIERW HTTP strukturalnie, wizualnie na końcu/batch.
**Etap A — Fundament:** Claude przez wp-cli: motyw + wtyczki + header/footer (HFE, NIE hack) + Kit + puste strony. → weryfikacja wizualna.
**Etap B — Strony:** wybierz tor z Fazy 0. HTML-section: Codex autoruje `elementor/<slug>.json` (batch 6 stron, copy PL, placeholdery) → Claude: import zdjęć→`media.json` → `postprocess-fixes.js` → `apply-pages.php` → `base-additions.css` + mu-plugin → flush. NATYWNY Elementor: Codex autoruje `build-<slug>-native.php` na `native-lib.php` + `native.css` → Claude aplikuje `wp eval-file` → flush CSS. → weryfikacja (HTTP + screeny).
**Etap C — SEO + finisz:** Yoast (tytuły/meta/schema/keywords), optymalizacja obrazów (Smush). → weryfikacja.

---

## 1A. DWA TORY BUDOWY

| Tor | Receptura | Wybierz gdy | Uwaga |
|---|---|---|---|
| **NATYWNY Elementor** | `gmb-workflow/NATIVE-ELEMENTOR-BUILD.md` + `gmb-workflow/assets/native/` | klient ma sam edytować sekcje, teksty, obrazy i karty w Elementorze | większy JSON, wymagany dedykowany `native.css` |
| **HTML-section** | `gmb-workflow/HTML-SECTION-BUILD.md` + `gmb-workflow/assets/child-theme/` | liczy się szybkość, premium wygląd i kontrola kodem | edycja sekcji głównie przez HTML/CSS |

Oba tory zostają w pakiecie. Decyzja z intake jest kontraktem dla całej Fazy 4B.

## 2. STACK WTYCZEK (instalacja z repo wordpress.org)

**Rdzeń:**
- **Hello Elementor** (motyw — lekki, czysty, standard pod Elementor)
- **Elementor** (builder)
- **Header Footer Elementor (HFE)** — header/footer jako szablony Elementora (Hello nie ma własnego buildera; NIGDY nie hackować przez `_elementor_data`)
- **Contact Form 7** — formularze

**"Wow"/bogactwo (research najpopularniejszych):**
- **Essential Addons for Elementor** — najpopularniejszy pakiet widgetów (karuzele, galerie, liczniki, cenniki, opinie, timeline) = od razu "więcej się dzieje"
- **Happy Addons** — dodatkowe widgety + animacje
- **WP Go Maps** lub **Integrate Google Maps** — interaktywny widget mapy (zamiast suchego embed)

**SEO + jakość:**
- **Yoast SEO** — tytuły/meta/OG/schema/sitemap + focus keywords z researchu (cel SEO)
- **Smush** lub **EWWW Image Optimizer** — kompresja/lazy obrazów (lżej, szybciej)

---

## 3. DESIGN — twarde wymogi (to były realne błędy)

1. **Sekcje pełnej szerokości** (Full Width/stretched); tła do krawędzi okna; treść w wewn. kontenerze ~1200px. Żadnych boxed marginesów.
2. **Hero z tłem-zdjęciem + ciemna nakładka** pod biały tekst; URL obrazu zwraca 200.
3. **Animacje wejścia z bezpiecznym fallbackiem:** bazowy `opacity:1` (animacja tylko go dograwa), `@media (prefers-reduced-motion: reduce)` pokazuje treść, no-JS fallback widoczny. ŻADEN element nie może zostać na `opacity:0` jeśli animacja nie odpali.
4. **Prawdziwe ikony, nie emoji.** Icon-boxy z ikonami (Elementor/Essential Addons).
5. **Dużo CTA + "dzieje się":** sticky CTA, liczniki (4.6/391 opinii), hover na kartach, pasek zaufania, sekcje naprzemienne kolorystycznie, opinie/karuzela.
6. **Kontrast obowiązkowo:** jasne tła → tekst ciemny; ciemne/zdjęcia → tekst jasny. **Stopka czytelna** (tekst na bordo wystarczająco jasny — to był błąd). Nagłówki w kolorach marki per-widget (celuj w wewnętrzny element, nie wrapper).
7. **Zdjęcia widoczne wszędzie** (atuty/dania/galeria/o-nas) — brak broken src/404; optymalizowane.

---

## 4. WERYFIKACJA (rola Claude, ~25%)

- Po **każdym etapie** screenshot (Chrome MCP lub headless Codexa) — nie batch na końcu dla buildów wizualnych.
- Dla treści ze scroll-animacjami: screeny **przescrollowane sekcja-po-sekcji**, nie jeden full-page (pełnostronicowy headless nie odpala scroll-animacji → fałszywe "wyblakłe").
- Sprawdź realnie: edytor stron działa (nie biała karta), obrazy się ładują, header/menu widoczne, full-width, kontrast, CF7 renderuje, mapa działa.
- Koszt Claude = oglądanie obrazów → patrz selektywnie (kluczowe strony), ale NIE pomijaj.

---

## 5. OUTPUT / HANDOFF

`handoff.json` + dla usera: URL `.local`, panel `/wp-admin`, login+hasło, lista podstron, użyte wtyczki, co dopracować (realne ceny/godziny do potwierdzenia z właścicielem). Site widoczny i edytowalny w aplikacji Local.

---

## 6. ARCHITEKTURA PRZYSPIESZENIA (jednorazowa inwestycja → szybciej na każdym kliencie)

> Cel: z ~1,5-2 h → **~30-45 min na projekt, przy WYŻSZEJ jakości** (mniej bugów, bo komponenty pre-verified). To jest sercem wtyczki — buduje się raz, amortyzuje na każdym kliencie.

**6.1 Bazowy blueprint stosu** — `gmb-workflow/wp-base-blueprint.json`. Kanoniczne źródło wtyczek/motywu (Hello Elementor + Elementor + HFE + Happy + Essential Addons + CF7 + Yoast + Smush + WP Go Maps). Użycie: (a) szybki test przez `wp-playground`, (b) **szablon site'a do clone'a w Local** (Local: Clone) → per projekt ~30 s zamiast instalacji, (c) tłumaczony na skrypt WP-CLI dla Site Shell. **Kasuje Fazę 2 + część 4A** (~13 min → ~1 min).

**6.2 Biblioteka sekcji** — `gmb-workflow/section-library.md`. Przetestowane, sparametryzowane szablony sekcji (`_elementor_data` z placeholderami). Build = wypełnianie placeholderów (copy + ID zdjęć + kolory), NIE autorstwo od zera. Reguły jakości (full-width, opacity:1+reduced-motion, kontrast, realne ikony) wbudowane RAZ w szablon → bugi nie wracają. **Build 6 stron ~15 min → ~3-5 min.** Kompozycje per branża (`compositions/<branza>.json`) wg playbooków.

**6.2A NATYWNY Elementor** — `gmb-workflow/NATIVE-ELEMENTOR-BUILD.md` + `gmb-workflow/assets/native/`. Ten tor używa natywnych widgetów Elementora (`heading/text/button/icon-box/icon-list/image/gallery/accordion`) i wspólnego `native.css`, gdy edytowalność dla klienta jest ważniejsza niż minimalny JSON.

**6.3 Równoległy build** — 6 podstron to niezależna treść → 6× `codex exec` w tle z osobnym scope. Faza 4B kurczy się do czasu najdłuższej pojedynczej strony.

**6.4 Strategia zdjęć (wąskie gardło)** — generacja PIERWSZA, w tle, **batch w jednym wywołaniu** (nie N cold-startów), opcjonalnie kilka równoległych jobów. **Selektywne AI:** custom tylko gdzie kluczowe (hero, sygnaturowe dania/realizacje); generyczne (tekstury/tła) z kuratorowanego packa per branża — mniej generacji, ta sama jakość.

**6.5 Automatyczne QA headless** — `gmb-workflow/qa-checklist.md`. Codex odpala deterministyczny skrypt (asercje architektura+wizual+SEO), zwraca pass/fail. **Claude patrzy na screeny tylko dla oceny estetycznej + finalny przegląd**, nie poluje na bugi wzrokiem. Bramka jakości zostaje, mój koszt spada. Bonus: **kontaktówka** (1 obraz = miniatury 6 stron) zamiast 6 pełnych odczytów vision.

**6.6 Playbooki branżowe** — `gmb-workflow/business-type-playbooks.md`. Mapowanie typ biznesu → typy podstron + akcenty designu + silosy SEO + briefy zdjęć. Zero zgadywania struktury per projekt.

---

## 7. DESIGN SYSTEM / WOW (warstwa kreatywna — żeby było wyróżniające, nie tylko „poprawne")

> Sekcja 3 naprawia BŁĘDY (sucho/emoji/opacity/kontrast). Ta sekcja dodaje warstwę „wow".

**7.1 Self-updating research trendów** — skill `web-design-trends`: Codex fetchuje aktualne wzorce (award-sites/trendy danego roku) **per branża**, destyluje do `design-spec` (layouty hero, mikro-interakcje, typografia, kolory). Cache w `.codex/design-cache.json`, odświeżany co N dni → design „sam się aktualizuje", nie wygląda generycznie-AI.

**7.2 Oficjalny `frontend-design`** — do generowania wyróżniających się frontów/sekcji (nie generic AI aesthetic). Podpinany przy projektowaniu sygnaturowych sekcji (hero, showcase).

**7.3 Moodboard do wspólnej oceny** — przed buildem Codex generuje moodboard (paleta+typografia+referencyjne layouty) → szybka akceptacja kierunku zanim ruszy build (tańsze niż poprawki po).

**7.4 Mikro-interakcje i ruch (z fallbackiem z sekcji 3.3)** — parallax subtelny na hero, hover-zoom na kartach, liczniki (counter-up), reveal sekwencyjny (stagger), sticky CTA, pasek logo/zaufania. Zawsze: `prefers-reduced-motion` + brak layout-shift + lazy poniżej foldu (eager na hero).

---

## 8. ZAKTUALIZOWANY CZAS

| Bez architektury przyspieszenia | Z architekturą (6) |
|---|---|
| ~1,5-2 h, cykle poprawek | **~30-45 min**, mniej poprawek, wyższa jakość |
Ścieżka krytyczna z optymalizacją: clone site (30s) → research → wypełnienie sekcji z biblioteki (równolegle) → QA headless → estetyczny przegląd. Zdjęcia w tle nie blokują.

## ARTEFAKTY WORKFLOW (folder `gmb-workflow/`)
- **`HARDENED-RECIPES.md`** — ⭐ twarde recepty z boju (wp-cli bootstrap, https, FA5, gridy, mapy, mobile, author/apply). CZYTAJ NAJPIERW.
- **`assets/`** — gotowce: `postprocess-fixes.js` (deterministyczne fixy), `apply-pages.php` (aplikator), `base-additions.css` (bazowy CSS), `mu-kw-aktualnosci.php` (shortcode Aktualności).
- **`NATIVE-ELEMENTOR-BUILD.md`** — tor natywnych widgetów Elementora dla stron edytowalnych przez klienta.
- **`assets/native/`** — helpery `fm_native_*`, `native.css` i przykład buildera natywnego.
- **`HTML-SECTION-BUILD.md`** — tor sekcji HTML w Elementorze, gdy priorytetem jest deterministyczny premium build.
- `wp-base-blueprint.json` — bazowy stos (blueprint/clone/WP-CLI)
- `section-library.md` — biblioteka sparametryzowanych sekcji + kompozycje
- `qa-checklist.md` — automatyczna bramka QA headless
- `business-type-playbooks.md` — struktura+design+SEO per branża
