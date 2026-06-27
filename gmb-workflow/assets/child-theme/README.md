# assets/child-theme — starter HTML-section build

Generyczne pliki do child-theme dla stron budowanych jako sekcje HTML w Elementorze. Prefix startera to `site`; w realnym projekcie podmień go na prefix klienta.

| Plik | Co to |
|---|---|
| `functions.php` | enqueue fontów, parent/child style, tytuł archiwum „Aktualności", link „Czytaj więcej" |
| `set-kit.php` | programowy Elementor Global Kit; przed użyciem ustaw paletę marki |
| `build-page.example.php` | przykład buildu home + pętla podstron ofertowych |
| `dynamic-footer.php` | footer child-theme z placeholderami NAP |
| `base-theme.css` | kompletny system komponentów `site-*` |

Kolejność: skopiuj pliki do child-theme → podmień `site` na prefix projektu → ustaw kolory/fonty w `set-kit.php` i `base-theme.css` → wypełnij placeholdery `{{...}}` → aplikuj przez WP-CLI poza sandboxem Codexa.

Pełna receptura: `../../HTML-SECTION-BUILD.md` (z katalogu `assets/`: `../HTML-SECTION-BUILD.md`).
