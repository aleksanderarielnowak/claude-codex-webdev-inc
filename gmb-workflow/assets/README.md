# assets/ — reużywalne komponenty buildu (z przebiegu Kärcher 2026-06-17)

Gotowce, które skracają build z ~2 h debugu do ~30–40 min. Pełny kontekst: `../HARDENED-RECIPES.md`.

| Plik | Co robi | Kto uruchamia |
|---|---|---|
| `postprocess-fixes.js` | Deterministyczne fixy `_elementor_data`: ikony FA5, hero full-bleed, ciemne przyciski bannerów, dedupe map, równe siatki kart (`_element_id`), CTA bez numerów, responsywne paddingi. Ustaw `CONFIG.dir/files`, `node postprocess-fixes.js`. | Claude (przed apply) |
| `apply-pages.php` | Podstawia placeholdery (`__IMG_*__`, `__CF7_SHORTCODE__`, `__MAP_SHORTCODE__`) z `media.json` + shortcody, zapisuje `_elementor_data` jako string z `wp_slash`. `wp eval-file apply-pages.php`. | Claude |
| `base-additions.css` | Bazowy globalny CSS (siatki kart, CF7, kafelki Aktualności, mobile header/menu, zabezpieczenia). Wgraj przez `wp_update_custom_css_post`. Podmień kolory marki. | Claude |
| `mu-kw-aktualnosci.php` | mu-plugin: shortcode `[kw_aktualnosci]` = siatka kafelków wpisów (strona Aktualności jako design, nie archiwum). Wgraj do `wp-content/mu-plugins/`. | Claude |
| `native/` | Tor NATYWNY Elementor: `native-lib.php`, `native.css`, `build-page.native.example.php` dla edytowalnych stron na natywnych widgetach. | Codex autoruje, Claude aplikuje |
| `polish/polish.css` | Warstwa „szlifowanie": animacje reveal, hovery (karty/kafelki/cennik), warianty guzików `--lg/--arrow`, countupy `.jan-stats`, hero podstron `page-id-N`. Patrz `../phase-szlifowanie.md`. | Claude (enqueue) |
| `polish/polish.js` | Vanilla JS: IntersectionObserver reveal + countup `[data-target]/[data-suffix]`, reduced-motion. | Claude (enqueue) |
| `polish/build-stats.php` | Idempotentny builder sekcji liczników do `_elementor_data`. `wp eval-file`. | Claude |

**Kolejność HTML-section:** Codex autoruje `elementor/<slug>.json` z placeholderami → Claude: import zdjęć→`media.json` → `postprocess-fixes.js` → `apply-pages.php` → wgraj `base-additions.css` + mu-plugin → flush → weryfikacja (HTTP strukturalnie, potem wizualnie).

**Kolejność NATYWNY Elementor:** Codex autoruje `build-<slug>-native.php` na `assets/native/native-lib.php` i `assets/native/native.css` → Claude aplikuje `wp eval-file` → `wp elementor flush-css` / clear cache → weryfikacja w edytorze i na froncie.

**Dlaczego Claude aplikuje, nie Codex:** sandbox Codexa na Windows+Local NIE wykonuje `php.exe`/`wp.cmd` (Access denied). Patrz `../HARDENED-RECIPES.md` §1. wp-cli bootstrap (php-cli.ini + wp.cmd + wp-cli-db.php) w §2.
