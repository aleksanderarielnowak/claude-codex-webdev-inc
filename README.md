# claude-codex-webdev-inc

Wtyczka workflow (zalążek): **wizytówka Google (GMB) biznesu bez strony → gotowa, jakościowa lokalna strona WordPress**, w modelu Synapse (Claude = mózg, Codex = ręce).

> Status: **seed / dokumentacja workflow**. Komponenty wykonawcze (golden-build `sections/*.json`, skrypt QA Playwright, site-szablon Local, skill `web-design-trends`) dochodzą przy działającym site + tokenach Codexa. Patrz `gmb-workflow/README.md` → TODO.

## Start
1. Przeczytaj **`GMB-TO-SITE-WORKFLOW.md`** (master prompt — pełny protokół).
2. **`gmb-workflow/START-HERE.md`** — jak odpalić przebieg (Twój 1 krok w Local).
3. Specy per faza: **`gmb-workflow/phase-specs.md`**.

## Zawartość
- `GMB-TO-SITE-WORKFLOW.md` — master prompt (zasady, 5 faz, stack, design, przyspieszenie, design-system, czas)
- `gmb-workflow/` — pakiet operacyjny: README (indeks), START-HERE, phase-specs, business-type-playbooks, section-library, qa-checklist, wp-base-blueprint.json

## Filozofia
Claude planuje/weryfikuje (~25%), Codex wykonuje (~75%). Jakość: weryfikacja per-etap · `ready=true` ≠ poprawność wizualna · specy nazywają narzędzie i zabraniają hacków. Szybkość: blueprint/clone + biblioteka sekcji + build równoległy + zdjęcia w tle + QA headless. Cel: ~30-45 min/projekt przy wyższej jakości.

## Powiązane
Bazuje na pluginie `claude-codex-synapse` (protokół mózg/ręce) + oficjalnych skillach WordPress.

---
🤖 Generated with [Claude Code](https://claude.com/claude-code)
