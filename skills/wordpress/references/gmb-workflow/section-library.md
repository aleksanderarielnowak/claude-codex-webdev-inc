# Biblioteka sekcji — przyspieszenie buildu bez utraty jakości

> Idea: zamiast budować `_elementor_data` od zera co projekt, trzymamy **przetestowane, sparametryzowane szablony sekcji**. Build = wypełnianie placeholderów (tekst, ID zdjęć, kolory), nie autorstwo.
> Efekt: build 6 stron z ~15 min → ~3-5 min, a jakość ROŚNIE (bugi full-width/opacity/kontrast/header już rozwiązane w szablonie i nie wracają).

## Skąd biorą się szablony
1. **Golden build** — raz budujemy wzorcowy site (Włoski Brzeg po naprawach to dobry kandydat), przechodzi pełne QA (qa-checklist.md).
2. **Ekstrakcja** — z bazy WP wyciągamy `_elementor_data` per sekcja (Codex: `wp post meta get <id> _elementor_data`), czyścimy do szablonu, zamieniamy konkretne wartości na `{{PLACEHOLDERY}}`.
3. Zapis do `sections/<typ>.json` + wpis w manifeście.
> Status: szablony populujemy z pierwszego POPRAWNEGO golden buildu (wymaga działającego site = Codex/WP). Tu definiujemy STRUKTURĘ i schemat.

## Katalog sekcji (typy)
| Plik | Sekcja | Placeholdery kluczowe |
|---|---|---|
| `hero.json` | Hero pełnoekranowy z tłem-zdjęciem+overlay+2 CTA | `{{HERO_IMG_ID}}`,`{{HERO_H1}}`,`{{HERO_SUB}}`,`{{CTA1}}`,`{{CTA2}}`,`{{OVERLAY}}` |
| `trust-bar.json` | Pasek zaufania (ocena/liczba opinii/badge) | `{{RATING}}`,`{{REVIEWS}}`,`{{BADGE_1..4}}` |
| `features-cards.json` | Atuty — 4 karty z ikonami | `{{ICON_n}}`,`{{TITLE_n}}`,`{{TEXT_n}}` |
| `showcase-cards.json` | Polecane (dania/usługi/produkty) ze zdjęciami | `{{IMG_ID_n}}`,`{{TITLE_n}}`,`{{DESC_n}}`,`{{PRICE_n?}}` |
| `about-teaser.json` | Teaser O nas (zdjęcie+tekst+link) | `{{IMG_ID}}`,`{{H2}}`,`{{BODY}}`,`{{LINK}}` |
| `reviews.json` | Opinie (sekcja akcentowa / karuzela) | `{{QUOTE_n}}`,`{{AUTHOR_n}}`,`{{RATING}}` |
| `gallery-grid.json` | Galeria masonry | `{{IMG_IDS[]}}` |
| `menu-list.json` | Lista pozycji (menu/cennik/usługi) z foto sekcji | `{{SECTION_IMG}}`,`{{ITEMS[]:{name,desc,price}}}` |
| `cta-map.json` | CTA + mapa Google (widget WP Go Maps) | `{{ADDRESS}}`,`{{HOURS}}`,`{{PHONE}}`,`{{MAP_COORDS}}`,`{{CTA}}` |
| `portfolio.json` | Realizacje przed/po (budowlana/auto/beauty) | `{{ITEMS[]:{before_id,after_id,title}}}` |
| `pricing.json` | Cennik/pakiety | `{{PLANS[]:{name,price,features[],cta}}}` |
| `contact-cf7.json` | Formularz CF7 + dane + mapa | `{{CF7_SHORTCODE}}`,`{{NAP}}`,`{{SOCIAL}}` |

## Schemat manifestu (`sections/manifest.json`)
```json
{
  "version": "1.0",
  "sections": [
    { "id": "hero", "file": "hero.json", "placeholders": ["HERO_IMG_ID","HERO_H1","HERO_SUB","CTA1","CTA2","OVERLAY"],
      "qa": ["V2 full-width","V4 no-opacity0","V5 hero-bg"], "industries": ["*"] }
  ]
}
```

## Reguły jakości wbudowane w KAŻDY szablon (raz, na zawsze)
- Sekcja = **Full Width/stretched**, treść w kontenerze ~1200px (V2).
- Animacje: bazowy `opacity:1` + `prefers-reduced-motion` fallback (V4).
- Hero: `background-image` + overlay (V5).
- Ikony = realne (eicon/SVG), nie emoji (V8).
- Kontrast tekst/tło ≥ 4.5:1, w tym stopka (V6).
- Nagłówki: kolor per-widget na wewn. elemencie (nie wrapper).

## Złożenie strony (composition recipes — per typ biznesu)
`compositions/<branza>.json` = kolejność sekcji per strona, zgodnie z business-type-playbooks.md. Przykład (gastro/home):
```json
{ "home": ["hero","trust-bar","features-cards","menu-list","about-teaser","reviews","gallery-grid","cta-map"] }
```
Build strony = wybierz kompozycję → dla każdej sekcji użyj helperów z `assets/native/native-lib.php` → podstaw placeholdery (copy z research + ID zdjęć z media.json) → zapisz `_elementor_data`. Codex robi to deterministycznie, równolegle per strona.

## Parametryzacja — źródła wartości
- Teksty/copy → `research.json` + brief + copy wygenerowane per sekcja.
- ID zdjęć → `media.json` (biblioteka mediów).
- Kolory/fonty → `design-spec.json` (Global Kit).
- NAP/godziny/mapa → `research.json`.
