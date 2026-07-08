# assets/native/ — starter toru NATYWNY ELEMENTOR

Reużywalny zestaw do budowania stron, które klient ma później edytować wizualnie w Elementorze.

| Plik | Co robi | Kiedy |
|---|---|---|
| `native-lib.php` | Helpery `fm_native_*` do składania `_elementor_data` z natywnych widgetów: heading, text, button, image, icon-box, icon-list, kolumny i sekcje | każdy builder `build-<slug>-native.php` |
| `native.css` | Design-system `fm-*` mapujący wygląd na strukturę Elementora (`.elementor-widget-*`) | enqueue w projekcie lub Global CSS Elementora |
| `build-page.native.example.php` | Krótki przykład: hero, karty, galeria, cennik, CTA + zapis meta Elementora | jako baza nowej podstrony |
| `set-kit.php` | Starter globalnego Elementor Kit: kolory, typografia, szerokość kontenera | przy fundamencie projektu |

## Kolejność użycia
1. Skopiuj `native-lib.php` i `native.css` do katalogu roboczego projektu albo wgraj CSS do globalnych ustawień Elementora.
2. Podmień paletę w `:root` i prefiks `fm_` / `fm-*`, jeśli projekt wymaga własnego namespace.
3. Wgraj obrazy do biblioteki mediów WP i używaj `id + url`, nie ścieżek z motywu.
4. Utwórz `build-<slug>-native.php` na bazie przykładu.
5. Claude aplikuje: `wp eval-file build-<slug>-native.php`, potem flush CSS Elementora i weryfikacja zrzutem.

Pełna receptura: `../../NATIVE-ELEMENTOR-BUILD.md`.
