---
description: Zbuduj stronę WordPress od zera ze źródła o firmie (WWW/social/GMB/NIP/nazwa+lokalizacja) — pełny pipeline 5-fazowy wg skilla wordpress.
argument-hint: <źródło o firmie: URL / nazwa + lokalizacja / NIP>
---
Uruchom pełny pipeline budowy strony wg skilla `wordpress` (gałąź „budowa od zera").

ŹRÓDŁO: $ARGUMENTS

Kroki:
1. **Wczytaj protokół:** `skills/wordpress/SKILL.md` + master `skills/wordpress/references/GMB-TO-SITE-WORKFLOW.md`. Wstrzykuj guardraile z `LEARNINGS.md` do KAŻDEGO specu Codexa (sekcja „Znane pułapki").
2. **Faza 1 Research** — deleguj fetch do Codexa (`/codex`, sieć `-c sandbox_workspace_write.network_access=true`) → brief.json + research.json + playbook (`skills/wordpress/references/gmb-workflow/business-type-playbooks.md`). Eskaluj tylko krytyczne `needs_user`.
3. **Faza 2 Środowisko** — WP-CLI odpala **Claude** (Codex nie uruchamia php.exe): stos wg `skills/wordpress/references/gmb-workflow/wp-base-blueprint.json`, permalinki, puste strony. HARDENED §1–§2.
4. **Wybór stacku:** domyślnie NATYWNY Elementor (`.../NATIVE-ELEMENTOR-BUILD.md`); przy silnym sygnale specjalistycznym z briefu wybierz inny builder/motyw/plugin wg `.../STACK-DECISION-GUIDE.md` (z uzasadnieniem dla usera).
5. **Faza 4 Build** — Codex AUTORUJE (placeholdery `__IMG_*__`), Claude APLIKUJE (biblioteka sekcji + postprocess-fixes + apply-pages). Weryfikuj **per-etap** screenshotem — `ready=true` ≠ poprawność wizualna.
6. **Faza 4C SEO** (Yoast/schema/sitemap) + **Faza 5 QA** (`skills/wordpress/references/gmb-workflow/qa-checklist.md`).
7. **Handoff:** login + lista podstron + co dopracować. Strona edytowalna przez mid-SEOwca.
