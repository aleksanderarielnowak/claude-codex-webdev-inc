---
description: Dołóż sekcję z biblioteki do istniejącej podstrony Elementora (rozbudowa) wg skilla wordpress.
argument-hint: <strona + jaka sekcja, np. "Home: opinie klientów">
---
Dodaj sekcję do istniejącej strony wg skilla `wordpress` (gałąź „rozbudowa / zmiany").

CEL: $ARGUMENTS

Kroki:
1. **Zidentyfikuj realnie wyrenderowaną stronę** i jej `_elementor_data` (po `data-*-id` / ścieżce assetu, NIE po nazwie z listy admina — stale duplikaty marnują rundy). Zrób snapshot.
2. **Wybierz szablon** z `skills/wordpress/references/gmb-workflow/section-library.md`; dobierz tor (NATYWNY/HTML) zgodny z istniejącą stroną.
3. **Codex AUTORUJE** sekcję (placeholdery `__IMG_*__` itd.), **Claude APLIKUJE** (postprocess + apply). Kanał: WP-CLI/REST (patrz `skills/wordpress/references/wp-change-channels.md`). **Zmiana w plikach motywu/snippet → NAJPIERW poinformuj usera i poczekaj na zgodę.**
4. **Weryfikuj:** HTTP + screenshot samej sekcji. Wstrzyknij guardraile z `LEARNINGS.md`.
