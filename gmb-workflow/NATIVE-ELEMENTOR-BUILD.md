# NATYWNY ELEMENTOR build — edytowalny tor Elementora

> Drugi tor buildu obok `HTML-SECTION-BUILD.md`. Strona nadal powstaje deterministycznie przez `_elementor_data`, ale z natywnych widgetów Elementora, więc klient może później edytować nagłówki, teksty, przyciski, obrazy, karty i galerie w panelu.

## Filozofia
| Tor | Kiedy | Plusy | Koszt |
|---|---|---|---|
| **NATYWNY Elementor** | Klient chce sam edytować stronę wizualnie w Elementorze | edycja w panelu, natywne widgety, mniej „kodu w kartach" | większy JSON, więcej pułapek, wymaga `native.css` |
| **HTML-section** | Liczy się premium wygląd, szybkość i pełna kontrola kodem | deterministyczny layout, mniejszy drift, szybkie diffy | edycja treści głównie przez HTML/CSS |

Domyślnie wybieraj **NATYWNY Elementor**, jeśli klient ma sam zarządzać treściami i układem. Wybieraj **HTML-section**, jeśli priorytetem jest szybki, bardzo kontrolowany build, a edycja przez klienta nie jest krytyczna.

## Stack
| Element | Rola |
|---|---|
| Hello Elementor + child theme | lekka baza, enqueue CSS/JS i fontów |
| Elementor | natywne widgety i edytor |
| Header Footer Elementor (HFE) | header/footer bez hackowania stron |
| Happy Addons / Essential Addons | dodatkowe widgety, gdy realnie potrzebne |
| Contact Form 7 | formularze kontaktowe |

## Wzorzec pracy
| Krok | Kto | Co powstaje |
|---|---|---|
| 1 | Codex | `build-<slug>-native.php` z tablicą natywnych widgetów |
| 2 | Codex | wspólny `native-lib.php` + `native.css` z klasami `fm-*` |
| 3 | Claude | import zdjęć do biblioteki mediów i przygotowanie `id + url` |
| 4 | Claude | `wp eval-file build-<slug>-native.php` |
| 5 | Claude | `wp elementor flush-css` / clear cache + zrzut weryfikacyjny |

Builder zapisuje:

```php
update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($data, JSON_UNESCAPED_UNICODE)));
update_post_meta($page_id, '_elementor_edit_mode', 'builder');
update_post_meta($page_id, '_elementor_template_type', 'wp-page');
update_post_meta($page_id, '_wp_page_template', 'elementor_header_footer');
delete_post_meta($page_id, '_elementor_css');
```

## Pułapki krytyczne
| Pułapka | Objaw | Reguła |
|---|---|---|
| Media JSON z BOM | `json_decode()` zwraca `null`, znikają wszystkie zdjęcia | PowerShell zapisuj bez BOM: `[IO.File]::WriteAllText($path, $json, [Text.UTF8Encoding]::new($false))` |
| Tło sekcji z `_elementor_data` | Elementor nie emituje tła hero mimo ustawień | hero ustawiaj klasą CSS: `.fm-hero--<slug>` w `native.css` |
| Galeria w inner-section | widgety image potrafią znikać | galerie dawaj w zwykłych sekcjach, nie w `inner-section` |
| Obrazy z URL motywu | puste obrazki / brak edycji w panelu | `image` i `gallery` muszą używać mediów z biblioteki WP: `id + url` |
| Ikony FA6 | puste ikony w Elementorze | używaj Font Awesome 5: `fas ...`, `library => fa-solid` |
| Brak dedykowanego CSS | layout wygląda jak surowy Elementor | `native.css` mapuje styl na `.elementor-widget-*`, karty, galerie, cenniki i CTA |
| Stary CSS Elementora | zmiany nie widać po apply | po buildzie: usuń `_elementor_css`, flush CSS i clear cache |
| Niski viewport headless | reveal nie odpala, sekcje wyglądają na wyblakłe | weryfikuj wysokim oknem, tak aby cała strona mieściła się w viewport albo scrolluj sekcja po sekcji |

## Animacje i ruch
- Reveal: bazowo `opacity:1`; animacja dopiero po klasie `html.fm-anim`.
- Klasę `fm-anim` wstrzykuj wcześnie w `<head>`, żeby nie było flasha między stanem statycznym i animowanym.
- `prefers-reduced-motion: reduce` ma wyłączać przejścia i zostawiać treść widoczną.
- Hovery: krótkie przejścia z `cubic-bezier`, `will-change` tylko na elementach faktycznie animowanych.

Minimalny snippet:

```html
<script>document.documentElement.classList.add('fm-anim');</script>
```

## Assety
| Plik | Użycie |
|---|---|
| `assets/native/native-lib.php` | helpery `fm_native_*` |
| `assets/native/native.css` | design-system `fm-*` |
| `assets/native/build-page.native.example.php` | przykład buildera |
