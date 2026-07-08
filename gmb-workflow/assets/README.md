# assets/ — reużywalne komponenty buildu (z przebiegu Kärcher 2026-06-17)

Gotowce, które skracają build z ~2 h debugu do ~30–40 min. Pełny kontekst: `../HARDENED-RECIPES.md`.

| Plik | Co robi | Kto uruchamia |
|---|---|---|
| `mu-kw-aktualnosci.php` | mu-plugin: shortcode `[kw_aktualnosci]` = siatka kafelków wpisów (strona Aktualności jako design, nie archiwum). Wgraj do `wp-content/mu-plugins/`. | Claude |
| `native/` | NATYWNY Elementor: `native-lib.php`, `native.css`, `build-page.native.example.php`, `set-kit.php` dla edytowalnych stron na natywnych widgetach. | Codex autoruje, Claude aplikuje |
| `polish/polish.css` | Warstwa „szlifowanie": animacje reveal, hovery (karty/kafelki/cennik), warianty guzików `--lg/--arrow`, countupy `.jan-stats`, hero podstron `page-id-N`. Patrz `../phase-szlifowanie.md`. | Claude (enqueue) |
| `polish/polish.js` | Vanilla JS: IntersectionObserver reveal + countup `[data-target]/[data-suffix]`, reduced-motion. | Claude (enqueue) |
| `polish/build-stats.php` | Idempotentny builder sekcji liczników do `_elementor_data`. `wp eval-file`. | Claude |

**Kolejność:** Codex autoruje `build-<slug>-native.php` na `assets/native/native-lib.php` i `assets/native/native.css` → Claude aplikuje `wp eval-file` → `wp elementor flush-css` / clear cache → weryfikacja w edytorze i na froncie.

**Dlaczego Claude aplikuje, nie Codex:** sandbox Codexa na Windows+Local NIE wykonuje `php.exe`/`wp.cmd` (Access denied). Patrz `../HARDENED-RECIPES.md` §1. wp-cli bootstrap (php-cli.ini + wp.cmd + wp-cli-db.php) w §2.
