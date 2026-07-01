# claude-codex-webdev-inc

Wtyczka Claude Code: **operator WordPress/Elementor** — budowa stron od zera, rozbudowa, analiza motywu, zmiany i poprawki. Pracuje narzędziami samego WordPressa (WP-CLI, REST, Elementor, wtyczki), a dla rzeczy dostępnych wyłącznie przez GUI prowadzi **wp-admin headless przez Codexa**. Model Synapse: Claude = mózg (spec + weryfikacja), Codex = ręce (produkcja).

## Instalacja
```
/plugin marketplace add aleksanderarielnowak/alek-claude-plugins
/plugin install claude-codex-webdev-inc@alek-claude-plugins
```
Rekomendowany towarzysz: **claude-codex-synapse** (protokół mózg/ręce, delegacja przez `/codex`). webdev-inc konsumuje ten protokół, gdy jest zainstalowany; bez niego działa inline.

## Jak działa
Skill **`wordpress`** odpala się sam na zadaniach WP (build / rozbudowa / analiza / poprawki). To router: klasyfikuje zadanie i wczytuje właściwy reference dopiero wtedy, gdy jest potrzebny (progressive disclosure — tanio dla kontekstu).

- `skills/wordpress/SKILL.md` — protokół: router zadań, kanały zmian, dwa tory buildu, zasady jakości, guardraile.
- `skills/wordpress/references/wp-change-channels.md` — Kanał 1 (WP-CLI/REST, domyślny) vs Kanał 2 (headless wp-admin, fallback GUI-only) + **reguła zgody na pliki motywu**.
- `skills/wordpress/references/GMB-TO-SITE-WORKFLOW.md` — master prompt pipeline'u „źródło o firmie → gotowa strona".
- `skills/wordpress/references/gmb-workflow/` — pakiet operacyjny: `phase-specs`, `business-type-playbooks`, `section-library`, `NATIVE-ELEMENTOR-BUILD`, `HTML-SECTION-BUILD`, `HARDENED-RECIPES`, `qa-checklist`, `wp-base-blueprint.json`.
- `skills/wordpress/references/gmb-workflow/assets/` — gotowce wykonawcze: `native/` (helpery `fm_native_*`), `child-theme/` (helpery `site_*`), `postprocess-fixes.js`, `apply-pages.php`, `base-additions.css`, `polish/`.
- `skills/web-design-trends/SKILL.md` — projektuj z BIEŻĄCEJ wiedzy o web-designie (research delegowany), nie z pamięci; zasila fazę Design.
- `LEARNINGS.md` — guardraile WP (bugi z boju, wstrzykiwane do specu Codexa).

## Komendy (jawne wejścia)
- `/claude-codex-webdev-inc:build <źródło o firmie>` — pełny pipeline budowy od zera.
- `/claude-codex-webdev-inc:section <strona + sekcja>` — dołóż sekcję z biblioteki (rozbudowa).
- `/claude-codex-webdev-inc:qa <URL/site>` — QA headless (asercje architektura/wizual/SEO).

Na co dzień nie musisz ich wołać — skill `wordpress` odpala się sam na zadaniach WP; komendy to skróty do konkretnych operacji.

## Kanały zmian (kluczowa zasada)
**REST/WP-CLI first** (deterministyczne, odwracalne, „w bazie", autonomiczne). **Headless wp-admin dopiero dla rzeczy GUI-only** (blob ustawień wtyczki, operacje w edytorze Elementora bez API). Zmiany w **plikach motywu / snippety** — webdev-inc **najpierw informuje** co i gdzie chce umieścić, dopiero po potwierdzeniu działa.

## Dwa tory buildu
- **NATYWNY Elementor** — gdy klient sam edytuje w panelu (natywne widgety, `native.css`).
- **HTML-section** — gdy priorytet to szybkość/premium/pełna kontrola CSS (sekcje HTML, child-theme).

Zawsze: strona ma być edytowalna przez mid-poziom SEOwca; struktura udokumentowana w handoffie.

## Filozofia
Weryfikacja per-etap · `ready=true` ≠ poprawność wizualna · specy nazywają narzędzie i zabraniają hacków · pracuj po WordPressowemu (API wtyczek/Elementora, nie hacki w DB).

## Powiązane
`claude-codex-synapse` (protokół mózg/ręce) + oficjalne skille WordPress.

---
🤖 Generated with [Claude Code](https://claude.com/claude-code)
