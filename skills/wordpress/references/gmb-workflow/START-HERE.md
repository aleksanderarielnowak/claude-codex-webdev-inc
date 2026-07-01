# Uruchomienie pipeline'u: dowolne źródło → strona WP

Przebieg wykonujesz **autonomicznie** (mózg/ręce). Jedyny krok, którego nie zrobisz sam: **utworzenie site'a w Local** — Local nie ma CLI do tworzenia site'ów. Poproś o niego użytkownika, resztę prowadź sam.

## Krok użytkownika (poproś o niego na starcie, ~1-2 min)
Poinstruuj użytkownika krótko i poczekaj na dane:
- **Opcja A (najszybsza, gdy istnieje szablon):** w Local prawy klik na site-szablonie z gotowym stosem → **Clone** → nazwa `klient-xyz`. Stos (Hello Elementor + wtyczki + Kit) już jest (~30 s).
- **Opcja B (pierwszy raz / bez szablonu):** Local → **+ Create a new site** → nazwa → Environment **Preferred** (nginx + PHP 8.2+ + MySQL), WordPress najnowszy → ustaw login admina → **Start site**. (Zrób z tego potem szablon do clone'a.)

Odbierz od użytkownika: **nazwę site'a + URL `.local` + login admina.** Dalej operujesz przez Site Shell (Local → site → „Open site shell").

## Przebieg (mózg = Claude, ręce = Codex)
1. **Research autonomiczny** — deleguj fetch do Codexa (`phase-specs.md` Faza 1) → `brief.json`, `research.json`, `needs_user` (tylko krytyczne braki) + dobór playbooku branżowego.
2. **Środowisko** — Codex weryfikuje/dopina stos przez Site Shell (Faza 2); clone z szablonu → tylko weryfikacja. WP-CLI odpala Claude (patrz `HARDENED-RECIPES.md` §1–§2).
3. **Zdjęcia AI** — uruchom generację Codexa **w tle od razu** (batch); reszta leci równolegle.
4. **Design** — zaplanuj strukturę pod TYP biznesu (playbook) + branding. Dla kierunku wizualnego użyj skilla `web-design-trends`.
5. **Build** — Codex wypełnia sekcje z `section-library.md` (równolegle per strona); wybór toru: NATYWNY Elementor / HTML-section.
6. **SEO** — Yoast (Faza 4C).
7. **QA** — Codex headless wg `qa-checklist.md`; oglądaj kontaktówkę + finalny przegląd estetyczny, nie poluj bugów wzrokiem.
8. **Handoff** — przekaż dane logowania + listę podstron; site edytowalny w Local.

## Zasady trzymające jakość
Fetch zawsze przez Codexa · weryfikuj po KAŻDYM etapie · `ready=true` ≠ poprawność wizualna · spec nazywa narzędzie i zabrania hacków · NIGDY `php -S` jako hosting · header/footer tylko przez HFE.

## Wymagania wstępne (sprawdź przed startem)
- Codex z tokenami + MCP wstały (`/codex-setup`, jeśli rozłączony).
- Local uruchomiony, site wystartowany.
- INPUT: jedno lub więcej źródeł — URL strony WWW (redesign/baza), profil social, wizytówka GMB (screen/link), KRS/NIP/REGON, albo sama nazwa + lokalizacja (Codex wyszukuje w otwartej sieci).
