# Faza „SZLIFOWANIE" — runda dopieszczenia gotowej strony

Opcjonalna runda PO akceptacji bazowej strony (Faza 4). Cel: podnieść „wow" bez przebudowy treści. Zamawiana hasłem: *więcej grafik na podstronach, animowane sekcje, countupy, więcej hoverów i guzików*.

Sprawdzona na przebiegu **OSK Jan** (nauka jazdy, 2026-06-17). Pliki wzorcowe: `assets/polish/`.

## Co dostarcza
- **Grafiki na podstronach** — hero każdej podstrony dostaje zdjęcie (reużyj zdjęcia AI z galerii) + ciemny overlay marki, przez body class `page-id-N`.
- **Animacje reveal** — fade+slide przy wejściu w viewport (IntersectionObserver), staggered delay, respekt `prefers-reduced-motion`.
- **Countupy** — pas liczników (zdawalność %, lata, klienci, ocena) animowany 0→target.
- **Bogatsze hovery** — karty się unoszą z akcentową krawędzią, kafelki galerii zoom, cennik skala+ramka, animowane podkreślenia menu/stopki.
- **Więcej guzików** — warianty `--lg`, `--arrow` (ruchoma strzałka), mocniejszy `:focus-visible`.

## Realizacja (Codex pisze, Claude aplikuje)
2 nowe pliki + edycja `functions.php` + 1 builder. Wzorce w `assets/polish/`:

| Plik | Co to |
|---|---|
| `polish.css` | warstwa NAD bazowym CSS: `.jan-reveal`/`.is-visible`, hovery kart/kafelków/cennika, warianty `.jan-btn--lg/--arrow`, `.jan-stats`/`.jan-stat__num`, hero podstron `body.page-id-N .jan-hero{...url(../img/photos/X.jpg)...}` (ŚCIEŻKI WZGLĘDNE z pliku CSS) |
| `polish.js` | vanilla, DOMContentLoaded, idempotentny: IntersectionObserver dodaje `.is-visible` (+ auto-oznacza `.jan-card/.jan-stat/.jan-steps>*/.jan-section h2`); countup `.jan-stat__num[data-target]` z `data-suffix`, obsługa dziesiętnych, reduced-motion → wartość końcowa |
| `build-stats.php` | IDEMPOTENTNY builder (skip jeśli marker `jan-stats-section`): dekoduje `_elementor_data`, wstawia sekcję container+widget `html` z 4× `.jan-stat .jan-reveal`, waliduje `json_decode!==null`, zapis `wp_slash(json_encode())` |

**`functions.php`:** dograj enqueue obu plików z `filemtime()` jako wersją (cache-bust), nie psuj istniejących.

## Adaptacja per projekt
- Prefiks klas `jan-` → zamień na prefiks projektu (lub zostaw, jeśli child theme to dziedziczy).
- Mapowanie `page-id-N` → realne ID podstron + dobór zdjęć hero.
- Wartości liczników + etykiety pod branżę; ZAWSZE disclaimer „*dane przykładowe" przy placeholderach.
- Trzymać [[rule-button-contrast]]: żółty/akcentowy guzik = ciemny tekst, niezależnie od tła sekcji.

## Aplikacja (Claude)
1. `php -l` na PHP, `node --check` na JS.
2. `wp eval-file _build/build-stats.php` (na wybranych stronach: home + 1-2 kluczowe).
3. `wp elementor flush-css`.

## Weryfikacja zrzutem (Edge/Chromium headless)
- `--virtual-time-budget=2500` + WYSOKIE `--window-size` (np. `1280x3200`) — inaczej IntersectionObserver nie odpali dla treści pod foldem (reveal zostaje `opacity:0` = wygląda „pusto").
- Countup łapie się ZWYKLE w pół animacji (~40% targetu) — to NORMALNE, potwierdza że liczy. Final widać w realnej przeglądarce.
