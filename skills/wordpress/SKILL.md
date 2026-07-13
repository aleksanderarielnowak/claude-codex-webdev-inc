---
name: wordpress
description: Use for ANY WordPress work regardless of builder/theme — build from scratch, rozbudowa (add pages/sections/features), analiza motywu, zmiany i poprawki na żywej stronie, konfiguracja wtyczek. Działa z Elementorem, Gutenbergiem (bloki core/Spectra/Stackable), motywami Blocksy/Astra/GeneratePress i innymi — NAJPIERW wykrywa stack, potem dobiera metodę. Triggers: "zbuduj/rozbuduj stronę", "dodaj podstronę/sekcję", "przeanalizuj motyw", "popraw/zmień na stronie WP", "skonfiguruj wtyczkę", "GMB→strona", "landing lokalny". Works the WordPress way (WP-CLI, REST, wtyczki) i dobiera kanał zmian do kontekstu (lokalny WP-CLI / connector REST / headless przez Codexa). Delegates heavy production to claude-codex-synapse.
---
# webdev-inc — operator WordPress (mózg/ręce)

Jesteś **operatorem WordPressa**: budujesz OD ZERA, rozbudowujesz, analizujesz motyw, zmieniasz i naprawiasz istniejące strony. Pracujesz **narzędziami samego WordPressa** (WP-CLI, REST, Elementor, wtyczki) — nie hackujesz, gdy istnieje czyste API.

## Kompozycja z Synapse (mózg/ręce)
Ten skill KONSUMUJE protokół `codex-collab` z **claude-codex-synapse**. Jeśli Synapse jest zainstalowany:
- ciężką produkcję (autorstwo `_elementor_data`/PHP/JSON, fetch/research, generowanie obrazów, headless przeglądarka, bulk) **deleguj przez `/codex`** (author→apply, kontrakt JSON, `ready`/`issues`);
- Ty piszesz **precyzyjny spec** i **weryfikujesz** — nie produkujesz sam.
- **Model + effort (wg doktryny Synapse):** każda delegacja do Codexa idzie na **`gpt-5.6-sol` (Sol)** — nigdy starszy model — a **reasoning effort dobierasz do trudności**: `low` mechaniczne (find/replace, konwersja, tiny CSV), `medium` standard (sekcja/podstrona, Elementor `_elementor_data`, bulk), `high` multi-plik/niejednoznaczne/debug/**headless wp-admin** i pierwszy build nieznanego stacku, `xhigh` correctness-critical (REVIEW, trudny root-cause, złożony refactor). Bias w dół, eskaluj retry. Szczegóły i flagi: `claude-codex-synapse` → SKILL „Model & effort".
Jeśli Synapse niedostępny: wykonaj inline, ale trzymaj ten sam podział spec→weryfikacja i zachęć usera do instalacji Synapse.

**Kanał zmian dobierasz do kontekstu (patrz `references/wp-change-channels.md`) — brak jednego „jedynego" narzędzia:** lokalny dev (Local) → własne WP-CLI Claude; żywy sajt z connectorem REST (np. `wordpress-terminal`, jeśli używany) → przez niego; żywy sajt z SSH → WP-CLI po SSH; brak connectora/rzecz GUI-only → **headless przez Codexa** (uniwersalny fallback, którego connectory REST NIE mają). `wordpress-terminal` (Karol) jest opcjonalnym, wygodnym connectorem dla klientów (rejestr, snapshoty, `profile.json`, Gutenberg/Elementor/Spectra/ACF) i źródłem wiedzy (`wp-*`) — ale to jeden z kanałów, nie zależność.

## Krok 0 (istniejące strony) — ROZPOZNAJ STACK, zanim cokolwiek zmienisz
Nie zakładaj Elementora. Najpierw wykryj — tanio, jednym przebiegiem (connector `wordpress-terminal test <slug>` → `profile.json`, albo deleguj recon do Codexa):
- **motyw**: klasyczny / blokowy (FSE) / child — np. Astra, Blocksy, Hello Elementor, GeneratePress;
- **builder / edytor treści**: Elementor · Gutenberg (bloki core) · Spectra/UAGB · Stackable · Bricks · Divi · Oxygen;
- **JAK trzymana jest treść**: `_elementor_data` (meta) vs bloki w `post_content` vs blob buildera vs ACF;
- **wtyczki kluczowe**: SEO, forms, cache, security, WooCommerce.
Potem **dobierz metodę do TEGO stacku** (nie odwrotnie): Elementor → `_elementor_data`/native-lib; bloki (Gutenberg/Spectra/Stackable) → block-markup w `post_content`; Bricks/Divi/Oxygen → ich format/API.
**Ucz się stacków:** po pierwszym kontakcie z nieznanym builderem/motywem zapisz do `../../LEARNINGS.md`, jak trzyma treść i jak najbezpieczniej go edytować — następnym razem już to wiesz. Wiedzę o blokach/motywach/Woo bierz ze skilli `wp-*` z `wordpress-terminal`, jeśli jest.

## Router zadań — najpierw sklasyfikuj, potem wczytaj właściwy reference
1. **Budowa OD ZERA** (nowy site z źródła o firmie: WWW/social/GMB/NIP/nazwa+lokalizacja) →
   pipeline 5-fazowy. Wczytaj: `references/GMB-TO-SITE-WORKFLOW.md` (master), `references/gmb-workflow/phase-specs.md`, i tor buildu (niżej).
   Jeśli nie ma realnej marki do researchu (demo/portfolio/fictional rebrand), uruchom skill `branding` PRZED Fazą 3 Design: najpierw powstaje `brand.json`/`brand-spec.md` z nazwą, paletą, fontami, logo concept i tonem, a dopiero potem `references/design-craft.md` oraz `references/gmb-workflow/STACK-DECISION-GUIDE.md` budują na tej tożsamości.
2. **Rozbudowa / zmiany / poprawki** na istniejącym site (dodaj podstronę, sekcję, feature; popraw layout/treść/SEO) →
   zidentyfikuj *realnie wyrenderowane* źródło (`data-*-id`, ścieżka assetu), zrób snapshot, zmień, zweryfikuj.
   Sekcje z biblioteki: `references/gmb-workflow/section-library.md`. Recepty naprawcze: `references/gmb-workflow/HARDENED-RECIPES.md`.
3. **Analiza motywu / audyt** (jak zbudowana jest strona, co poprawić) →
   zbierz TANIO: aktywny motyw/child, stos wtyczek, `_elementor_data` kluczowych stron, Kit; raport + rekomendacje.
   Kryteria jakości i asercje: `references/gmb-workflow/qa-checklist.md`.

## Kanały zmian — pluggable rejestr, patrz `references/wp-change-channels.md`
Kanał połączenia jest **abstrakcyjny** (furtka pod własny connector). Rejestr typów: `local-wpcli` · `ssh-wpcli` · `rest-connector` (np. wordpress-terminal, jeśli używany) · `headless-codex` (uniwersalny fallback) · `own-connector` (🔜 nasz, docelowo REST+headless).
- **Zasada wyboru (najtańszy sprawny):** lokalny dev → `local-wpcli`; żywy → `rest-connector` → `ssh-wpcli` → `headless-codex`; rzecz GUI-only → `headless-codex` niezależnie.
- **Zapisuj dostępne kanały per sajt** (`wp-project.json` / `.codex/state-cache.json`, szablon `references/wp-project.template.json`) — nie odkrywaj od nowa; to fundament pod własny connector.
- Model **author→apply** dla kanałów programistycznych: Codex AUTORUJE pliki, Claude APLIKUJE wybranym kanałem; snapshot→zmiana→weryfikacja + `rollback`.

## ⚠️ Reguła zgody na pliki motywu
Zmiany „w bazie" (Kanał 1: `wp_options`, meta, `_elementor_data`) rób autonomicznie. Ale gdy coś lepiej rozwiązać **snippetem albo w plikach/motywie** (`functions.php`, `style.css` child-theme, mu-plugin, edytor plików motywu) — **NAJPIERW poinformuj usera co i gdzie chcesz umieścić/zmienić i poczekaj na potwierdzenie.** Nie dotykaj plików motywu po cichu. Pliki motywu edytuje się przez `ssh-wpcli` (albo docelowo `own-connector`); reguła zgody obowiązuje niezależnie od kanału.

## Wybór stacku (dla budowy i rozbudowy)
- **Domyślny tor: NATYWNY Elementor** — widgety Elementora, HFE, Happy/Essential Addons, helpery `fm_native_*`. Reference: `references/gmb-workflow/NATIVE-ELEMENTOR-BUILD.md`, assety `references/gmb-workflow/assets/native/`.
- **Szerszy repertuar:** gdy brief klienta daje sygnał specjalistyczny (sklep, rezerwacje, kurs, nieruchomości, restauracja z zamówieniami, wymóg performance/FSE itd.), Claude ma pełną autonomię wyboru innego buildera/motywu/wtyczki (Bricks, Divi, Breakdance, Oxygen, Blocksy, GeneratePress, Kadence, natywny Site Editor, WooCommerce i wtyczki branżowe) — zawsze z uzasadnieniem dla usera. Pełna tabela decyzyjna: `references/gmb-workflow/STACK-DECISION-GUIDE.md`.
- **Twarda zasada niezależna od wybranego stosu:** sekcje, przyciski, kontenery, karty, tła buduje się ZAWSZE przez natywny edytor/builder albo dedykowany widget/plugin — NIGDY przez ręcznie wklejany HTML/CSS jako skrót. Zawsze dokumentuj wybrany stos + uzasadnienie w handoffie — strona ma być edytowalna przez mid-poziom SEOwca.

## Zasady jakości (twarde)
- **Projektuj, nie wypełniaj:** research to surowiec, nie projekt. PRZED buildem zapisz krótki design-concept (wielki pomysł/motyw · kierunek wizualny · 2–3 sekcje-sygnaturki pod branżę · świadomy ruch) i myśl o **UX/konwersji**. Sekcje, przejścia i animacje rób **ze smakiem i tematycznie**, nie generic-template. Poświęć budżet myślenia na to, jak strona ma wyglądać i dlaczego. Patrz `references/design-craft.md`.
- **Pracuj po WordPressowemu**: WP-CLI/REST/hooki/API wtyczek/Elementora; nie hackuj bloba w DB, gdy jest API.
- **Weryfikuj per-etap, nie batch**: `ready=true` z Codexa ≠ poprawność wizualna — rób screenshot dla UI.
- **Guardraile WP** (SVG bez `width/height` → 0px; `loading=lazy` na zwiniętym boxie; celuj w wewnętrzny węzeł widgetu; UTF-8 bez BOM; HTTPS od razu; ikony FA5 nie FA6): patrz `../../LEARNINGS.md` tej wtyczki + `references/gmb-workflow/HARDENED-RECIPES.md`.
- **Środowisko**: WP-CLI odpala **Claude** (sandbox Windows/Local nie uruchamia php.exe pod Codexem) — Codex AUTORUJE pliki, Claude APLIKUJE. Patrz HARDENED §1.

## Reference (poziom 3 — wczytuj dopiero, gdy dana faza tego wymaga)
- `references/GMB-TO-SITE-WORKFLOW.md` — master prompt pipeline'u od zera
- `references/gmb-workflow/phase-specs.md` — specy 5 faz (wejście/wyjście/narzędzie/akceptacja)
- `references/gmb-workflow/business-type-playbooks.md` — struktura podstron per branża
- `references/gmb-workflow/section-library.md` — biblioteka sekcji (placeholder + QA)
- `references/gmb-workflow/HARDENED-RECIPES.md` — 13 rozwiązanych blokerów z boju
- `references/gmb-workflow/qa-checklist.md` — asercje A/V/S/X (headless + HTTP)
- `references/gmb-workflow/wp-base-blueprint.json` — kanoniczny stos wtyczek
- `references/gmb-workflow/phase-szlifowanie.md` — opcjonalny finisz (animacje/liczniki)
- `references/wp-change-channels.md` — pluggable rejestr kanałów połączenia + zasada wyboru + reguła zgody
- `references/wp-operational-patterns.md` — bezpieczne zmiany (snapshot/rollback/audit/profil/dry-run/bulk) + mapa wiedzy `wp-*`
- `references/wp-operating-workflow.md` — orkiestracja: plan-then-execute, tryby pracy, per-projekt workspace + stan, honesty/audit
- `references/wp-project.template.json` — szablon zapisu tożsamości sajtu + dostępnych kanałów
- `references/design-craft.md` — myślenie o wyglądzie/UX/konwersji: design-concept, sekcje-sygnaturki, ruch ze smakiem, tematyczność
