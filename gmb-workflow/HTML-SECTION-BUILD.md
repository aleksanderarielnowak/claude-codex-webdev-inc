# HTML-section build — deterministyczny build Elementora

> Warstwa „quality + efficiency": strona nadal jest stroną Elementor, ale każda podstrona składa się z kilku widgetów HTML. Układ, kontrast i responsywność trzyma jeden namespace'owany CSS child-theme.

## Kiedy używać
| Podejście | Używaj gdy | Plusy | Ryzyka |
|---|---|---|---|
| **HTML-section build** | Potrzebujesz szybko zbudować lokalną stronę usługową, masz powtarzalne sekcje i chcesz pełnej kontroli CSS | deterministyczne `_elementor_data`, brak `postprocess-fixes`, mały JSON, zero driftu widgetów, łatwe diffy | klient nie edytuje każdej karty „po elementorowemu"; edycja sekcji = HTML/CSS |
| **Natywne widgety Elementora** | Klient ma samodzielnie przebudowywać sekcje w panelu lub potrzebujesz gotowych integracji widgetów | edycja wizualna, integracje Elementora | duży JSON, drift ustawień, częste fixy full-width/kontrast/mobile |

W praktyce: dla pipeline'u GMB → lokalna strona WP używaj **HTML-section build** jako domyślnego sposobu produkcji. Natywne widgety zostaw dla formularzy, menu, nietypowych integracji albo miejsc, gdzie edycja przez klienta jest ważniejsza niż deterministyczny build. Reguły ops z `HARDENED-RECIPES.md` nadal obowiązują: Codex autoruje pliki, Claude aplikuje WP-CLI.

## 3 helpery
Neutralny prefiks startera to `site_` / `site-`. W projekcie podmień go na prefiks klienta.

```php
<?php
if (!function_exists('site_uid')) {
    function site_uid() {
        return substr(str_replace('-', '', wp_generate_uuid4()), 0, 7);
    }
}

if (!function_exists('site_html_widget')) {
    function site_html_widget($html) {
        return [
            'id' => site_uid(),
            'elType' => 'widget',
            'widgetType' => 'html',
            'settings' => ['html' => $html],
            'elements' => [],
        ];
    }
}

if (!function_exists('site_section')) {
    function site_section($html) {
        return [
            'id' => site_uid(),
            'elType' => 'section',
            'settings' => ['layout' => 'full_width', 'gap' => 'no'],
            'elements' => [[
                'id' => site_uid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [site_html_widget($html)],
            ]],
        ];
    }
}
```

## Skeleton podstrony
Minimalny build: heredoc HTML → `array_map()` → 5 meta-operacji Elementora.

```php
<?php
$page_id = 123;
$asset = trailingslashit(get_stylesheet_directory_uri()) . 'assets/img/';

$hero = <<<HTML
<section class="site-sec site-hero">
  <div class="site-wrap site-grid">
    <div>
      <span class="site-badge">{{BADGE}}</span>
      <h1>{{H1}}</h1>
      <p class="site-lead">{{LEAD}}</p>
      <div class="site-actions">
        <a class="site-btn" href="{{CTA_URL}}">{{CTA_LABEL}}</a>
        <a class="site-btn site-btn--ghost" href="/kontakt/">Kontakt</a>
      </div>
    </div>
    <figure class="site-figure">
      <img src="{$asset}hero.svg" alt="" loading="eager">
    </figure>
  </div>
</section>
HTML;

$data = array_map('site_section', [$hero]);

update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($data)));
update_post_meta($page_id, '_elementor_edit_mode', 'builder');
update_post_meta($page_id, '_elementor_template_type', 'wp-page');
update_post_meta($page_id, '_wp_page_template', 'elementor_header_footer');
delete_post_meta($page_id, '_elementor_css');
```

## Katalog wzorców w base CSS
| Wzorzec | Klasy | Użycie |
|---|---|---|
| Hero ilustracyjny | `site-sec site-hero`, `site-grid`, `site-figure` | strona główna, bez zdjęcia w tle |
| Photo hero | `site-photo-hero`, `site-photo-hero__content` | podstrony, zdjęcie + `linear-gradient(...), url(...)` |
| Statystyki | `site-stats`, `site-counter` | liczby, lata, zasięg, proces |
| About + checklist | `site-grid`, `site-checklist`, `site-photo-frame` | sekcja „o nas", argumenty jakości |
| Karty 3/4 | `site-cards`, `site-cards--3`, `site-card`, `site-card__media` | usługi, kategorie, atuty |
| Proces | `site-timeline`, `site-step` | 3 kroki współpracy |
| News / CTA | `site-card site-card--green`, `site-btn--light` | aktualności, przejście do kontaktu |
| Kontakt | `site-contact`, `site-contact-list`, `site-form`, `site-map` | dane, formularz, iframe mapy |
| Galeria / kafelki | `site-gallery-grid`, `site-gallery-tile`, `site-tile` | realizacje, kategorie zdjęciowe |
| Warianty sekcji | `site-sec--dark`, `site-sec--paper`, `site-sec--cream` | rytm strony i kontrast |

## Reguła kontrastu guzików
Kontrast wynika z **tła guzika**, nie z tła sekcji.

| Wariant | Użycie | Reguła |
|---|---|---|
| `site-btn` | podstawowy CTA na jasnym tle | ciemne tło, jasny tekst |
| `site-btn--ghost` | drugorzędny CTA na jasnym tle | obrys, tekst w kolorze marki |
| `site-btn--light` | CTA na ciemnej sekcji | jasne tło, ciemny tekst |
| `site-btn--on-photo` | ghost na zdjęciu | jasny obrys i jasny tekst, tylko przy overlayu |

Nie zostawiaj domyślnego guzika na zdjęciu bez overlayu. Photo hero zawsze buduj jako:

```html
<section class="site-photo-hero" style="background-image: linear-gradient(90deg, rgba(0,0,0,.72), rgba(0,0,0,.38)), url('{{HERO_IMG_URL}}');">
```

## Startery
Startery są w `assets/child-theme/`:

| Plik | Co robi |
|---|---|
| `functions.php` | enqueue fontów + parent/child style, tytuł archiwum, link „Czytaj więcej" |
| `set-kit.php` | programowy Elementor Global Kit; ustaw paletę marki i fonty |
| `build-page.example.php` | przykład home + pętla podstron ofertowych na helperach `site_*` |
| `dynamic-footer.php` | generyczny footer child-theme z placeholderami NAP |
| `base-theme.css` | pełny system komponentów `site-*` |

Kolejność: podmień prefix `site` → ustaw paletę/fonty → wypełnij placeholdery → Claude aplikuje WP-CLI → HTTP/visual QA.
