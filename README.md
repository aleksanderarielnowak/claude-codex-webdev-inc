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

- `skills/wordpress/SKILL.md` — protokół: router zadań, kanały zmian, wybór stacku, zasady jakości, guardraile.
- `skills/wordpress/references/wp-change-channels.md` — Kanał 1 (WP-CLI/REST, domyślny) vs Kanał 2 (headless wp-admin, fallback GUI-only) + **reguła zgody na pliki motywu**.
- `skills/wordpress/references/GMB-TO-SITE-WORKFLOW.md` — master prompt pipeline'u „źródło o firmie → gotowa strona".
- `skills/wordpress/references/gmb-workflow/` — pakiet operacyjny: `phase-specs`, `business-type-playbooks`, `section-library`, `NATIVE-ELEMENTOR-BUILD`, `STACK-DECISION-GUIDE`, `HARDENED-RECIPES`, `qa-checklist`, `wp-base-blueprint.json`.
- `skills/wordpress/references/gmb-workflow/assets/` — gotowce wykonawcze: `native/` (helpery `fm_native_*`, `set-kit.php`), `polish/`.
- `skills/web-design-trends/SKILL.md` — projektuj z BIEŻĄCEJ wiedzy o web-designie (research delegowany), nie z pamięci; zasila fazę Design.
- `LEARNINGS.md` — guardraile WP (bugi z boju, wstrzykiwane do specu Codexa).

## Komendy (jawne wejścia)
- `/claude-codex-webdev-inc:build <źródło o firmie>` — pełny pipeline budowy od zera.
- `/claude-codex-webdev-inc:section <strona + sekcja>` — dołóż sekcję z biblioteki (rozbudowa).
- `/claude-codex-webdev-inc:qa <URL/site>` — QA headless (asercje architektura/wizual/SEO).

Na co dzień nie musisz ich wołać — skill `wordpress` odpala się sam na zadaniach WP; komendy to skróty do konkretnych operacji.

## Kanały zmian (kluczowa zasada)
**REST/WP-CLI first** (deterministyczne, odwracalne, „w bazie", autonomiczne). **Headless wp-admin dopiero dla rzeczy GUI-only** (blob ustawień wtyczki, operacje w edytorze Elementora bez API). Zmiany w **plikach motywu / snippety** — webdev-inc **najpierw informuje** co i gdzie chce umieścić, dopiero po potwierdzeniu działa.

## Wybór stacku
Domyślnie: **natywny Elementor** (Hello Elementor + Elementor + HFE + Happy/Essential Addons + CF7) — natywne widgety, `native.css`, zero ręcznego HTML/CSS na sekcje/przyciski/kontenery/tła. Gdy brief klienta daje silniejszy sygnał (sklep, rezerwacje, kurs, nieruchomości, restauracja z zamówieniami, wymóg wydajności/FSE itd.), Claude ma pełną autonomię wyboru innego buildera/motywu/wtyczki (Bricks, Divi, Breakdance, Oxygen, Blocksy, GeneratePress, Kadence, natywny Site Editor, WooCommerce i wtyczki branżowe) — patrz `skills/wordpress/references/gmb-workflow/STACK-DECISION-GUIDE.md`. Wybór zawsze z uzasadnieniem dla usera, nigdy bez niego, i zawsze pod tą samą twardą zasadą: sekcje/przyciski/kontenery/tła przez natywny edytor/widget, nigdy ręczny HTML/CSS.

Zawsze: strona ma być edytowalna przez mid-poziom SEOwca; struktura udokumentowana w handoffie.

## Filozofia
Weryfikacja per-etap · `ready=true` ≠ poprawność wizualna · specy nazywają narzędzie i zabraniają hacków · pracuj po WordPressowemu (API wtyczek/Elementora, nie hacki w DB).

## Powiązane
`claude-codex-synapse` (protokół mózg/ręce) + oficjalne skille WordPress.

---
🤖 Generated with [Claude Code](https://claude.com/claude-code)
