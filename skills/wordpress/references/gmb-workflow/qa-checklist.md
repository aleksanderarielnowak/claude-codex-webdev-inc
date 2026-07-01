# QA headless — automatyczna bramka jakości (Codex uruchamia, Claude czyta pass/fail)

> Cel: przenieść łapanie bugów z drogich oczu Claude na **deterministyczny skrypt headless** (Playwright/Puppeteer).
> Codex odpala go na żywym site po każdym etapie, zwraca JSON pass/fail. Claude patrzy na screeny tylko dla oceny **estetycznej** + finalny przegląd — nie poluje na bugi wzrokiem.

## Jak używać
Codex: dla każdej strony z listy uruchom headless (1440×900, `networkidle`, scroll do dołu z krokami), wykonaj asercje, zwróć:
```json
{ "ready": true|false,
  "pages": [{ "url":"...", "checks": { "<nazwa>": true|false }, "fails":["..."] }],
  "contact_sheet": ".codex/artifacts/contact-sheet.png",
  "issues": [] }
```
`ready=true` tylko gdy WSZYSTKIE asercje przeszły na WSZYSTKICH stronach.

## Asercje — architektura/serwer (łapią błąd „php -S"/bialy ekran)
- **A1 HTTP 200** — strona i `/wp-admin/` zwracają 200 (admin może 302→login).
- **A2 Edytor działa (nie biała karta)** — otwórz `/wp-admin/post.php?post=<ID>&action=elementor`; po `networkidle` w DOM jest `#elementor-editor-wrapper`/panel (`.elementor-panel`) ORAZ brak nieskończonego `#elementor-loading`. (Biała karta = jednowątkowy serwer → ten check ją wykrywa.)
- **A3 Współbieżność obrazów** — strona z galerią ładuje wszystkie `<img>` (patrz V3) bez 404 przy równoległym żądaniu.

## Asercje — layout/wizual (łapią boxed/opacity/kontrast/header)
- **V1 Header z menu** — w `header`/szablonie HFE są linki nawigacji (≥5 pozycji) + CTA „Rezerwacja". (Łapie „header bez menu".)
- **V2 Full-width** — sekcje hero/atuty mają `getBoundingClientRect().width` ≈ szerokość okna (±2px), nie ~1140 boxed. (Łapie boxed marginesy.)
- **V3 Obrazy widoczne** — każdy istotny `<img>`: `naturalWidth>0 && complete===true`; brak broken src/404. (Łapie „wysypane zdjęcia".)
- **V4 Brak trwałego opacity:0** — po przescrollowaniu sekcja-po-sekcji żaden widget treści nie ma `getComputedStyle(el).opacity === '0'` ani `visibility:hidden`. (Łapie „wyblakłe menu".)
- **V5 Hero ma tło-zdjęcie** — sekcja hero ma `background-image` z URL zwracającym 200 (nie samo ciemne tło).
- **V6 Kontrast** — dla kluczowych bloków tekstu (stopka, pozycje menu) policz kontrast tekst/tło ≥ 4.5:1 (WCAG AA). (Łapie „nieczytelną stopkę".)
- **V7 CF7 renderuje** — na `/kontakt/` jest `.wpcf7 form` z polami (nie goły shortcode `[contact-form-7...]` w treści).
- **V8 Ikony nie-emoji** — w sekcji atutów są realne ikony (`svg`/`i.eicon`/`<img>` ikony), brak gołych znaków emoji jako „ikon".

## Asercje — SEO (Yoast)
- **S1** każda strona ma unikalny `<title>` i `<meta name="description">`.
- **S2** jest `og:image` (PNG/JPG, nie SVG) i `application/ld+json` (schema).
- **S3** istnieje `/sitemap_index.xml` (Yoast) → 200.

## Kontaktówka (oszczędza tokeny Claude)
Codex skleja miniatury 6 stron w 1 obraz `contact-sheet.png` (siatka 2×3, podpisy URL). Claude robi 1 odczyt vision = szybki skan; pełny screen tylko dla strony, która wygląda podejrzanie.

## Reduced-motion
Jeden przebieg z emulacją `prefers-reduced-motion: reduce` — V4 musi nadal przejść (treść widoczna bez animacji).

## Asercje v2 — realne bugi z przebiegu Kärcher (2026-06-17)
> WAŻNE: część z tych checków rób przez **HTTP/grep po stronie Claude**, bo headless Codexa MYLI (patrz `HARDENED-RECIPES.md` §13: `ignoreHTTPSErrors` ukrywa mixed-content; Google Maps puste headless; boolean Codexa bywa fałszywy).
- **X1 Brak mixed-content** — w HTML strony żaden `wp-content/uploads` URL nie jest `http://` (wszystkie `https://`). Łapie puste zdjęcia u usera (Codex headless tego NIE wykryje).
- **X2 Ikony renderują** — icon-boxy mają realne glify FA5 (nie puste); zero nazw FA6/`eicon-` nieistniejących. (Sprawdź też brak `Trying to access array offset` w body = eksperyment SVG-ikon + WP_DEBUG.)
- **X3 Kafelki równe** — kontenery-siatki mają `id^="kwg"` i `display:grid` (równe kolumny + wysokości); brak 2+1/krzywego wrapu.
- **X4 Przyciski na żółtym bannerze widoczne** — kontrast ≥4.5:1 (ciemne tło `background_color`, nie żółty-na-żółtym).
- **X5 Jedna mapa** — na /kontakt/ dokładnie 1 widget mapy (Codex potrafi dodać natywny `google_maps` + shortcode).
- **X6 CTA bez numerów** — żadna etykieta przycisku nie zawiera cyfr telefonu („Zadzwoń teraz", nie „Zadzwoń 880…").
- **X7 Aktualności zaprojektowane** — /aktualnosci/ ma banner + kafelki `.kw-akt-card`, NIE goły „Archives"; `page_for_posts=0`.
- **X8 Mobile (viewport 390px)** — hamburger brandowy (nie domyślny/różowy), menu czytelne, CTA pełnoszerokie, h1≈30px, gridy 1-kol; weryfikuj zrzutem + klik toggle.
- **X9 Brak pustych widgetów `<style>`** — zabezpieczenia w globalnym Additional CSS, nie per-page HTML.
