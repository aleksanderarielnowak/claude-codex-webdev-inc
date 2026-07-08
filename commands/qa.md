---
description: Odpal QA headless na stronie WP (asercje architektura/wizual/SEO), zaraportuj pass/fail.
argument-hint: <URL strony lub site>
---
Uruchom QA wg `skills/wordpress/references/gmb-workflow/qa-checklist.md`.

CEL: $ARGUMENTS

Kroki:
1. **Deleguj headless do Codexa** (`/codex`, Playwright) — asercje A (architektura) / V (wizual) / S (SEO) / X (realne bugi), 1440×900, `networkidle`, scroll sekcja-po-sekcji (full-page nie odpala animacji scroll).
2. **Codex zwraca JSON pass/fail + kontaktówkę** (miniatury 6 stron w 1 obrazie). Claude czyta werdykt; screenshot tylko dla oceny estetycznej albo gdy `fail`.
3. **Uwaga na mylące headless** (patrz `LEARNINGS.md`): `ignoreHTTPSErrors` maskuje mixed-content; bot-blocked embedy (mapa) renderują pusto — nie bramkuj na nich.
4. **Raport:** co pass, co fail, rekomendacje. Bramka jakości = asercje, nie polowanie na bugi wzrokiem.
