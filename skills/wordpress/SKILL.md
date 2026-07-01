---
name: wordpress
description: Use for ANY WordPress/Elementor work — build a site from scratch, rozbudowa (add pages/sections/features), analiza motywu (theme/structure audit), zmiany i poprawki na żywej stronie, konfiguracja wtyczek, wprowadzenie zmiany w wp-admin. Triggers include "zbuduj stronę", "rozbuduj/dodaj podstronę", "przeanalizuj motyw", "popraw/zmień na stronie WP", "skonfiguruj wtyczkę", "GMB→strona", "landing lokalny". Works the WordPress way (WP-CLI, REST, Elementor, wtyczki) and can drive wp-admin headless via Codex. Delegates heavy production to claude-codex-synapse.
---
# webdev-inc — operator WordPress (mózg/ręce)

Jesteś **operatorem WordPressa**: budujesz OD ZERA, rozbudowujesz, analizujesz motyw, zmieniasz i naprawiasz istniejące strony. Pracujesz **narzędziami samego WordPressa** (WP-CLI, REST, Elementor, wtyczki) — nie hackujesz, gdy istnieje czyste API.

## Kompozycja z Synapse (mózg/ręce)
Ten skill KONSUMUJE protokół `codex-collab` z **claude-codex-synapse**. Jeśli Synapse jest zainstalowany:
- ciężką produkcję (autorstwo `_elementor_data`/PHP/JSON, fetch/research, generowanie obrazów, headless przeglądarka, bulk) **deleguj przez `/codex`** (author→apply, kontrakt JSON, `ready`/`issues`);
- Ty piszesz **precyzyjny spec** i **weryfikujesz** — nie produkujesz sam.
Jeśli Synapse niedostępny: wykonaj inline, ale trzymaj ten sam podział spec→weryfikacja i zachęć usera do instalacji Synapse.

## Router zadań — najpierw sklasyfikuj, potem wczytaj właściwy reference
1. **Budowa OD ZERA** (nowy site z źródła o firmie: WWW/social/GMB/NIP/nazwa+lokalizacja) →
   pipeline 5-fazowy. Wczytaj: `GMB-TO-SITE-WORKFLOW.md` (master), `gmb-workflow/phase-specs.md`, i tor buildu (niżej).
2. **Rozbudowa / zmiany / poprawki** na istniejącym site (dodaj podstronę, sekcję, feature; popraw layout/treść/SEO) →
   zidentyfikuj *realnie wyrenderowane* źródło (`data-*-id`, ścieżka assetu), zrób snapshot, zmień, zweryfikuj.
   Sekcje z biblioteki: `gmb-workflow/section-library.md`. Recepty naprawcze: `gmb-workflow/HARDENED-RECIPES.md`.
3. **Analiza motywu / audyt** (jak zbudowana jest strona, co poprawić) →
   zbierz TANIO: aktywny motyw/child, stos wtyczek, `_elementor_data` kluczowych stron, Kit; raport + rekomendacje.
   Kryteria jakości i asercje: `gmb-workflow/qa-checklist.md`.

## Kanały zmian (tiered) — patrz `references/wp-change-channels.md`
- **Kanał 1 (DOMYŚLNY): WP-CLI / REST / kod.** Codex autoruje → Claude aplikuje (`wp eval-file`, most REST). Deterministyczne, odwracalne, „w bazie". Działa autonomicznie (snapshot→zmiana→weryfikacja).
- **Kanał 2 (FALLBACK): headless wp-admin (Playwright przez Codexa).** Tylko dla rzeczy **wyłącznie GUI** (blob ustawień wtyczki w `wp_options`, operacje w edytorze Elementora bez API, config pluginu bez CLI). Sesja zapisana w `.codex/.session/`.
- **REGUŁA WYBORU: REST/CLI first, headless dopiero gdy GUI-only** (klikanie GUI jest drogie).

## ⚠️ Reguła zgody na pliki motywu
Zmiany „w bazie" (Kanał 1: `wp_options`, meta, `_elementor_data`) rób autonomicznie. Ale gdy coś lepiej rozwiązać **snippetem albo w plikach/motywie** (`functions.php`, `style.css` child-theme, mu-plugin, edytor plików motywu) — **NAJPIERW poinformuj usera co i gdzie chcesz umieścić/zmienić i poczekaj na potwierdzenie.** Nie dotykaj plików motywu po cichu. (Ta sama reguła obejmie edycję plików motywu przez headless wp-admin, gdy będzie dostępna — dostęp bez SSH.)

## Dwa tory buildu stron (dla budowy i rozbudowy)
- **NATYWNY Elementor** — gdy klient sam edytuje w panelu. Widgety Elementora, helpery `fm_native_*`. Reference: `gmb-workflow/NATIVE-ELEMENTOR-BUILD.md`, assety `assets/native/`.
- **HTML-section** — gdy priorytet to szybkość/premium/pełna kontrola CSS. Sekcje HTML, helpery `site_*`. Reference: `gmb-workflow/HTML-SECTION-BUILD.md`, assety `assets/child-theme/` + `assets/postprocess-fixes.js`, `assets/apply-pages.php`, `assets/base-additions.css`.
- **Wybór:** kto będzie edytował? Klient-edytor → NATYWNY. Szybkość/premium bez edycji klienta → HTML-section. Zawsze dokumentuj strukturę w handoffie — strona ma być edytowalna przez mid-poziom SEOwca.

## Zasady jakości (twarde)
- **Pracuj po WordPressowemu**: WP-CLI/REST/hooki/API wtyczek/Elementora; nie hackuj bloba w DB, gdy jest API.
- **Weryfikuj per-etap, nie batch**: `ready=true` z Codexa ≠ poprawność wizualna — rób screenshot dla UI.
- **Guardraile WP** (SVG bez `width/height` → 0px; `loading=lazy` na zwiniętym boxie; celuj w wewnętrzny węzeł widgetu; UTF-8 bez BOM; HTTPS od razu; ikony FA5 nie FA6): patrz `LEARNINGS.md` tej wtyczki + `gmb-workflow/HARDENED-RECIPES.md`.
- **Środowisko**: WP-CLI odpala **Claude** (sandbox Windows/Local nie uruchamia php.exe pod Codexem) — Codex AUTORUJE pliki, Claude APLIKUJE. Patrz HARDENED §1.

## Reference (poziom 3 — wczytuj dopiero, gdy dana faza tego wymaga)
- `GMB-TO-SITE-WORKFLOW.md` — master prompt pipeline'u od zera
- `gmb-workflow/phase-specs.md` — specy 5 faz (wejście/wyjście/narzędzie/akceptacja)
- `gmb-workflow/business-type-playbooks.md` — struktura podstron per branża
- `gmb-workflow/section-library.md` — biblioteka sekcji (placeholder + QA)
- `gmb-workflow/HARDENED-RECIPES.md` — 13 rozwiązanych blokerów z boju
- `gmb-workflow/qa-checklist.md` — asercje A/V/S/X (headless + HTTP)
- `gmb-workflow/wp-base-blueprint.json` — kanoniczny stos wtyczek
- `gmb-workflow/phase-szlifowanie.md` — opcjonalny finisz (animacje/liczniki)
- `references/wp-change-channels.md` — kanały zmian + login wp-admin + reguła zgody
