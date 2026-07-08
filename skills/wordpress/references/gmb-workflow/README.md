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
| `NATIVE-ELEMENTOR-BUILD.md` | Domyślny tor: natywne widgety Elementora + `native.css` | Faza 4B, stack domyślny |
| `STACK-DECISION-GUIDE.md` | Decyzja o builderze/motywie/pluginach per klient (Bricks/Divi/Breakdance/Oxygen, Blocksy/GeneratePress/Kadence/FSE, wtyczki branżowe) + uzasadnienie + twarda reguła natywnych widgetów | Faza 3, przed buildem |
| `HARDENED-RECIPES.md` | Twarde recepty z boju (WP-CLI bootstrap, HTTPS, ikony, mobile…) | CZYTAJ przy buildzie |
| `wp-base-blueprint.json` | Bazowy stos wtyczek/motywu (blueprint/clone/WP-CLI) | Faza 2 |
| `qa-checklist.md` | Bramka QA headless (asercje A/V/S/X) | po każdym etapie |
| `phase-szlifowanie.md` | Opcjonalny finisz: grafiki podstron, reveal, countupy, hovery | po akceptacji Fazy 4 |
| `assets/native/` | Startery domyślnego toru: helpery `fm_native_*`, `native.css`, przykład buildera, `set-kit.php` | build edytowalny |

## Filozofia
- **Claude = mózg** (plan, dyrygowanie, weryfikacja), **Codex = ręce** (fetch, obrazy, build).
- Jakość: weryfikacja per-etap · `ready=true` ≠ poprawność wizualna · specy nazywają narzędzie i zabraniają hacków · komponenty pre-verified.
- Szybkość: blueprint/clone · wybór stacku wg `STACK-DECISION-GUIDE.md` · biblioteka sekcji · build równoległy · zdjęcia w tle · QA headless zamiast oczu Claude.
- Cel: ~30–45 min/projekt przy wyższej jakości.

## Powiązania
- Protokół mózg/ręce: plugin `claude-codex-synapse` (skill `codex-collab`).
- Kanały zmian i wzorce operacyjne: `../wp-change-channels.md`, `../wp-operational-patterns.md`, `../wp-operating-workflow.md`.
- Wiedza dev/review WP (bloki/motywy/Woo/security…): skille `wp-*` (mapa temat→skill w `../wp-operational-patterns.md`).

## Do dopracowania (przy działającym site + tokenach Codexa)
- [ ] Golden build → ekstrakcja realnych szablonów `sections/*.json` + `compositions/*.json`.
- [ ] Skrypt QA headless (Playwright) z asercji `qa-checklist.md`.
- [ ] Site-szablon w Local do clone'a (stos z `wp-base-blueprint.json`).
