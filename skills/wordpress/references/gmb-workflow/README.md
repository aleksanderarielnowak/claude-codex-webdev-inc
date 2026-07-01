# gmb-workflow — pakiet workflow „GMB/dowolne źródło → strona WordPress"

Operacyjny zestaw dla pipeline'u: dowolne źródło o firmie (strona WWW, social, GMB, KRS/NIP/REGON albo sama nazwa + lokalizacja) → research w sieci → gotowa, jakościowa lokalna strona WP. Zalążek wtyczki `claude-codex-webdev-inc`.

## Pliki
| Plik | Co to | Kiedy |
|---|---|---|
| **`../GMB-TO-SITE-WORKFLOW.md`** | Master prompt — pełny protokół (zasady, 5 faz, stack, design, przyspieszenie, design-system) | czytaj najpierw |
| **`START-HERE.md`** | Jak odpalić przebieg + Twój 1 krok w Local | przed startem |
| **`phase-specs.md`** | Gotowe Task Specy per faza (Research/Środowisko/Fundament/Strony/SEO/QA) | podczas przebiegu |
| **`business-type-playbooks.md`** | Struktura podstron + design + SEO per branża | Faza 1+3 |
| **`section-library.md`** | Sparametryzowane szablony sekcji + kompozycje (przyspieszenie buildu) | Faza 4B |
| **`NATIVE-ELEMENTOR-BUILD.md`** | Receptura edytowalnego buildu: natywne widgety Elementora + `native.css` | Faza 4B, gdy klient ma sam edytować stronę |
| **`HTML-SECTION-BUILD.md`** | Receptura deterministycznego buildu: sekcje HTML w Elementorze + jeden CSS child-theme | Faza 4B, gdy liczy się szybkość i brak driftu |
| **`wp-base-blueprint.json`** | Bazowy stos wtyczek/motywu (blueprint/clone/WP-CLI) | Faza 2 |
| **`qa-checklist.md`** | Automatyczna bramka QA headless (asercje) | po każdym etapie |
| **`phase-szlifowanie.md`** | Opcjonalna runda dopieszczenia: grafiki podstron, animacje reveal, countupy, hovery, warianty guzików (wzorce w `assets/polish/`) | po akceptacji Fazy 4 |
| **`assets/native/`** | Startery NATYWNY Elementor: helpery `fm_native_*`, `native.css`, przykład buildera | przy buildzie edytowalnym |
| **`assets/child-theme/`** | Startery child-theme do HTML-section build: CSS komponentów, helpery, Kit, footer | przy starcie buildu stron |

## Filozofia
- **Claude = mózg** (plan, dyrygowanie, weryfikacja ~25%), **Codex = ręce** (fetch, obrazy, build ~75%).
- Jakość przez: weryfikacja per-etap · `ready=true` ≠ poprawność wizualna · specy nazywają narzędzie i zabraniają hacków · komponenty pre-verified.
- Szybkość przez: blueprint/clone (kasuje setup) · wybór toru NATYWNY Elementor albo HTML-section · biblioteka sekcji/buildów · build równoległy · zdjęcia w tle · QA headless zamiast oczu Claude.
- Cel: **~30-45 min/projekt przy wyższej jakości** po jednorazowej inwestycji w architekturę.

## Status / TODO (do populacji przy działającym site + tokenach Codexa)
- [ ] Golden build → ekstrakcja realnych szablonów `sections/*.json` + `compositions/*.json`
- [ ] Skrypt QA headless (Playwright) z asercji `qa-checklist.md`
- [ ] Site-szablon w Local do clone'a (stos z `wp-base-blueprint.json`)
- [ ] Skill `web-design-trends` (self-updating research designu — sekcja 7 master promptu)
- [ ] Spakowanie w plugin `claude-codex-webdev-inc`

## Powiązane
- Protokół mózg/ręce: plugin `claude-codex-synapse` (`skills/codex-collab/SKILL.md`, `LEARNINGS.md`).
- Oficjalne skille WP (`.claude/skills/`): `wp-wpcli-and-ops`, `wp-playground`, `blueprint`, `wp-rest-api` — warstwa ops.
