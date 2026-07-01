# gmb-workflow — pakiet pipeline'u „dowolne źródło → strona WordPress"

Operacyjne reference dla budowy strony OD ZERA: dowolne źródło o firmie (WWW, social, GMB, KRS/NIP/REGON albo sama nazwa + lokalizacja) → research → gotowa, jakościowa lokalna strona WP. Wczytywane przez skill `wordpress` w gałęzi „budowa od zera". Wczytuj konkretny plik dopiero, gdy dana faza go wymaga.

## Mapa plików (co → kiedy wczytać)
| Plik | Co to | Kiedy |
|---|---|---|
| `../GMB-TO-SITE-WORKFLOW.md` | Master protokół — zasady, 5 faz, stack, design, przyspieszenie | na start pipeline'u |
| `START-HERE.md` | Uruchomienie przebiegu + jedyny krok użytkownika (site w Local) | przed startem |
| `phase-specs.md` | Task Specy per faza (Research/Środowisko/Fundament/Strony/SEO/QA) | podczas przebiegu |
| `business-type-playbooks.md` | Struktura podstron + design + SEO per branża | Faza 1+3 |
| `section-library.md` | Sparametryzowane szablony sekcji + kompozycje | Faza 4B |
| `NATIVE-ELEMENTOR-BUILD.md` | Tor edytowalny: natywne widgety Elementora + `native.css` | Faza 4B, gdy klient sam edytuje |
| `HTML-SECTION-BUILD.md` | Tor deterministyczny: sekcje HTML + jeden CSS child-theme | Faza 4B, gdy liczy się szybkość/brak driftu |
| `HARDENED-RECIPES.md` | Twarde recepty z boju (WP-CLI bootstrap, HTTPS, ikony, mobile…) | CZYTAJ przy buildzie |
| `wp-base-blueprint.json` | Bazowy stos wtyczek/motywu (blueprint/clone/WP-CLI) | Faza 2 |
| `qa-checklist.md` | Bramka QA headless (asercje A/V/S/X) | po każdym etapie |
| `phase-szlifowanie.md` | Opcjonalny finisz: grafiki podstron, reveal, countupy, hovery | po akceptacji Fazy 4 |
| `assets/native/` | Startery toru NATYWNY: helpery `fm_native_*`, `native.css`, przykład buildera | build edytowalny |
| `assets/child-theme/` | Startery toru HTML-section: CSS komponentów, helpery, Kit, footer | start buildu stron |
| `assets/postprocess-fixes.js`, `apply-pages.php`, `base-additions.css` | Deterministyczne fixy + apply placeholderów + globalny CSS | Faza 4B (tor HTML) |

## Filozofia
- **Claude = mózg** (plan, dyrygowanie, weryfikacja), **Codex = ręce** (fetch, obrazy, build).
- Jakość: weryfikacja per-etap · `ready=true` ≠ poprawność wizualna · specy nazywają narzędzie i zabraniają hacków · komponenty pre-verified.
- Szybkość: blueprint/clone · wybór toru NATYWNY/HTML · biblioteka sekcji · build równoległy · zdjęcia w tle · QA headless zamiast oczu Claude.
- Cel: ~30–45 min/projekt przy wyższej jakości.

## Powiązania
- Protokół mózg/ręce: plugin `claude-codex-synapse` (skill `codex-collab`).
- Kanały zmian i wzorce operacyjne: `../wp-change-channels.md`, `../wp-operational-patterns.md`, `../wp-operating-workflow.md`.
- Wiedza dev/review WP (bloki/motywy/Woo/security…): skille `wp-*` (mapa temat→skill w `../wp-operational-patterns.md`).

## Do dopracowania (przy działającym site + tokenach Codexa)
- [ ] Golden build → ekstrakcja realnych szablonów `sections/*.json` + `compositions/*.json`.
- [ ] Skrypt QA headless (Playwright) z asercji `qa-checklist.md`.
- [ ] Site-szablon w Local do clone'a (stos z `wp-base-blueprint.json`).
