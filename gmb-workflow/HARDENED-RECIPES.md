# HARDENED RECIPES — twarde recepty z boju (v2, po przebiegu Kärcher Koszalin 2026-06-17)

> Każda pozycja to realny bloker/bug z pierwszego pełnego przebiegu, z gotowym rozwiązaniem do skopiowania.
> Cel: następny projekt z ~2 h debugowania → **~30–40 min wypełniania placeholderów**, przy WYŻSZEJ jakości.
> Złota zasada przebiegu: **Codex AUTORUJE pliki na dysk, Claude APLIKUJE przez wp-cli i weryfikuje.** (Powód niżej.)

---

## 0. PRE-FLIGHT (zrób ZANIM zaczniesz budować — oszczędza godziny)
1. **Codex sandbox NIE odpala php/wp** na Windows+Local — sprawdź 1 linią (patrz §1) i z góry przyjmij podział author/apply.
2. **Scheme = HTTPS** — Local serwuje `.local` po SSL. Ustaw siteurl/home na `https://` PRZED buildem (patrz §3), inaczej zdjęcia po http = mixed-content (puste w przeglądarce usera).
3. **Font Awesome = wersja 5** w Elementorze (nie 6!) — używaj nazw FA5 (patrz §6).
4. **WP_DEBUG** — Local ma display_errors ON; wyłącz eksperyment SVG-ikon + ustaw debug (patrz §4), inaczej PHP notice'y wchodzą w treść strony.
5. **wp-cli na Local headless wymaga własnego php.ini + shim DB_HOST** (patrz §2) — bez tego `wp` w ogóle nie ruszy.

---

## 1. Codex sandbox nie wykonuje binarek spoza workspace (Windows+Local)
**Objaw:** `cmd /c wp.cmd ...` w `codex exec` → `Program 'php.exe' failed to run: Odmowa dostępu` (Access denied), nawet przez cmd.exe.
**Wniosek:** Codex NIE uruchomi WP-CLI ani php na tej maszynie. Build, który „się udał" raz, to loteria.
**Probe (1 linia, rób na starcie):**
```
echo 'cmd /c "<ABS>\wp.cmd" option get blogname → zwróć {wp_ok,blogname}' | codex exec --skip-git-repo-check -s workspace-write -c sandbox_workspace_write.network_access=true -C "<DIR>" -
```
**REGUŁA — podział author/apply:**
- **Codex** autoruje treść/`_elementor_data`/copy do plików na dysk, z PLACEHOLDERAMI na wartości, których jeszcze nie ma (ID zdjęć, shortcode CF7, mapa).
- **Claude** (poza sandboxem) APLIKUJE: import zdjęć, podstawia placeholdery, zapisuje meta przez wp-cli. To „deterministyczne CLI", dozwolone dla Claude.

---

## 2. WP-CLI na Local headless (Windows) — bootstrap (NAJWAŻNIEJSZY unblocker)
Gołe `php.exe` z `lightning-services` nie ma działającego `php.ini` (folder `conf/php` site'a to tylko szablony `.hbs`) → brak `mysqli`. A `DB_HOST='localhost'` w wp-config na Windows CLI rozwiązuje się błędnie (named-pipe/3306) → „Error establishing a database connection". Trzy pliki naprawiają wszystko:

**`php-cli.ini`** (włącz rozszerzenia; `display_errors=Off` = czysty stdout do parsowania JSON):
```ini
extension_dir = "C:\Users\<USER>\AppData\Roaming\Local\lightning-services\php-<VER>\bin\win64\ext"
extension=mysqli
extension=mbstring
extension=curl
extension=openssl
extension=gd
extension=exif
extension=intl
extension=zip
extension=fileinfo
extension=sodium
extension=gettext
memory_limit = 512M
default_charset = "UTF-8"
display_errors = Off
log_errors = Off
```
**`wp-cli-db.php`** (ładowane przez `--require` ZANIM wczyta się wp-config — pierwsza definicja `DB_HOST` wygrywa; `<PORT>` z `sites.json` → `services.mysql.ports`):
```php
<?php
if ( ! defined('DB_HOST') ) { define('DB_HOST', '127.0.0.1:<PORT>'); }
```
**`wp.cmd`** (wrapper; `<SITE>` = `C:\Users\<USER>\Local Sites\<slug>`):
```bat
@echo off
set "SITE=<SITE>"
set "TEMP=<WORKDIR>\tmp"
set "TMP=<WORKDIR>\tmp"
set "WP_CLI_DISABLE_AUTO_CHECK_UPDATE=1"
"...\lightning-services\php-<VER>\bin\win64\php.exe" -c "<WORKDIR>\php-cli.ini" "...\Local\resources\extraResources\bin\wp-cli\wp-cli.phar" --path="%SITE%\app\public" --require="<WORKDIR>\wp-cli-db.php" %*
```
Ścieżki site'a/portu wyłuskaj z `C:\Users\<USER>\AppData\Roaming\Local\sites.json` (po nazwie/domenie). Test: `wp.cmd option get blogname` → nazwa, polskie znaki OK.

---

## 3. Mixed content — HTTPS od razu
Strona chodzi po HTTPS, a WP generuje URL-e zdjęć po HTTP → przeglądarka usera blokuje (puste boxy). Codex Playwright z `ignoreHTTPSErrors` tego NIE wykrywa (u niego się ładują → fałszywe „obrazy OK").
```
wp option update home  https://<domena>.local
wp option update siteurl https://<domena>.local
wp search-replace 'http://<domena>.local' 'https://<domena>.local' --all-tables --report-changed-only
wp elementor flush-css ; wp cache flush
```
Rób to PRZED/PO imporcie mediów (URL-e w `_elementor_data` też muszą być https).

---

## 4. PHP notice'y w treści (Local debug + eksperyment Elementora)
Żółte „Warning: Trying to access array offset…" w sekcjach = eksperyment Elementora „Inline Font Icons (SVG)" (`e-icons.php`) + display_errors ON.
```
wp option update elementor_experiment-e_font_icon_svg inactive
wp config set WP_DEBUG true --raw
wp config set WP_DEBUG_DISPLAY false --raw   # WP zarządza display_errors tylko gdy WP_DEBUG=true
wp config set WP_DEBUG_LOG true --raw
wp elementor flush-css
```

---

## 5. Podział author/apply — konwencja placeholderów + zapis meta
Codex pisze `elementor/<slug>.json` (TABLICA `_elementor_data`) z tokenami:
- zdjęcia: `"id":"__IMG_<slug>__","url":"__IMGURL_<slug>__"`
- CF7: `"shortcode":"__CF7_SHORTCODE__"`, mapa: `"__MAP_SHORTCODE__"`

Claude: `wp media import <png> --porcelain` → ID, `wp_get_attachment_url(ID)` → URL (https!), buduje `media.json`, podstawia tokeny (uwaga: token ID `"__IMG_x__"` z cudzysłowami → liczba bez cudzysłowów; shortcody JSON-escapuj). Zapis: `update_post_meta($id,'_elementor_data', wp_slash($json_string))` (Elementor trzyma to jako STRING, NIE tablicę) + `_elementor_edit_mode=builder`, `_elementor_template_type=wp-page`, `_wp_page_template=elementor_header_footer`; na końcu `Plugin::$instance->files_manager->clear_cache()`.
**BOM:** PowerShell 5.1 `Set-Content -Encoding utf8` dodaje BOM → PHP `json_decode` zwraca null. Zapisuj `[IO.File]::WriteAllText($p,$json,(New-Object Text.UTF8Encoding($false)))` albo strip BOM w PHP: `preg_replace('/^\xEF\xBB\xBF/','',$raw)`.

Gotowy szablon aplikatora: `assets/apply-pages.php`. Biblioteka deterministycznych fixów: `assets/postprocess-fixes.js`.

---

## 6. Deterministyczne fixy `_elementor_data` (postprocess — taniej niż round-trip do Codexa)
Uruchamiane przez Claude na plikach `elementor/*.json` PRZED apply (`assets/postprocess-fixes.js`):
- **Ikony → Font Awesome 5** (Elementor 4.x ma FA5, nie 6!). Mapowanie po tytule; nazwy FA5: `fa-tools, fa-map-marker-alt, fa-tint, fa-undo, fa-tachometer-alt, fa-exclamation-triangle, fa-check-circle, fa-file-alt` (NIE `fa-screwdriver-wrench/location-dot/droplet/...` = FA6 → puste). Też martwe: nieistniejące `eicon-water/home/tools`.
- **Hero full-bleed:** kontener `content_width:full` ALE usuń sztywne `width:1200` (to ono boksuje obraz) + `background_size:cover`.
- **Przyciski na żółtym bannerze:** klucz tła to **`background_color`** (NIE `button_background_color` — taki klucz nie istnieje, działa tylko `button_text_color`, tło zostaje żółte z Kita = niewidoczne). Dla pewności klasa `kw-btn-dark` + CSS `!important`.
- **Dedupe mapy:** Codex potrafi dodać natywny widget `google_maps` ORAZ shortcode → usuń natywny, zostaw shortcode iframe.
- **Siatki kart równe:** patrz §7.
- **CTA bez numerów:** tekst buttona `/zadzwo.*\d/` → „Zadzwoń teraz" (link `tel:` zostaje).
- **Responsywne paddingi:** dodaj `padding_mobile`/`padding_tablet` (hero 72/58, banner 62/46, content 48/48 na mobile).

---

## 7. Równe kafelki — Elementor NIE renderuje `_css_classes` na KONTENERZE
Klasa na widgetcie się renderuje (`kw-animated` działa), na kontenerze NIE (padding/inne ustawienia kontenera działają, sama klasa nie trafia do markupu). Dlatego CSS-grid przez klasę kontenera nie zadziała.
**FIX:** użyj `_element_id` (CSS ID renderuje się dla kontenerów) i targetuj atrybutem:
```css
[id^="kwg2-"],[id^="kwg3-"],[id^="kwg4-"],[id^="kwg6-"]{display:grid !important;gap:24px;align-items:stretch}
[id^="kwg3-"]{grid-template-columns:repeat(3,1fr)} [id^="kwg4-"]{grid-template-columns:repeat(4,1fr)}
[id^="kwg6-"]{grid-template-columns:repeat(3,1fr)}
@media(max-width:1024px){[id^="kwg3-"],[id^="kwg4-"],[id^="kwg6-"]{grid-template-columns:repeat(2,1fr)}}
@media(max-width:767px){[id^="kwg2-"],[id^="kwg3-"],[id^="kwg4-"],[id^="kwg6-"]{grid-template-columns:1fr}}
[id^="kwg"]>*{height:100%} /* grid auto-równa wysokości wierszy */
```
postprocess oznacza kontener-siatkę (>=2 dzieci, większość icon-box) `_element_id="kwg<N>-<licznik>"`. (Alternatywa: budować od razu jako natywny grid container `container_type:grid`.)

---

## 8. CSS globalny zamiast per-page (Additional CSS)
Codex wstawiał per-stronę widget HTML z `<style>` (zabezpieczenie opacity/reduced-motion) → 6× duplikat + puste bloki w edytorze. Przenieś RAZ do Additional CSS: `wp_update_custom_css_post($css)`; usuń widgety HTML ze `<style>`. Pełny bazowy CSS (grid kart, CF7, mobile header/menu, kafelki Aktualności, zabezpieczenia): `assets/base-additions.css`.

---

## 9. Mapa = Google embed iframe bez klucza
WP Go Maps domyślnie silnik Google → `ApiProjectMapError` bez klucza. Najpewniej: iframe (renderuje się w przeglądarce usera; w headless Codexa NIE — bot — nie weryfikuj mapy zrzutem):
```
[shortcode/HTML]: <iframe src="https://www.google.com/maps?q=<ADRES URLENC>&output=embed" style="border:0;width:100%;height:430px"></iframe>
```

## 10. Header jako 1 widget HTML — nawigacja edytowana w HTML
Jeśli header HFE to pojedynczy widget HTML ze sztywnym `<ul>` (nie widget Nav Menu), pozycje menu dodajesz EDYTUJĄC ten HTML (post HFE) — edycja menu WP nic nie zmienia. **Lepszy wzorzec na przyszłość:** budować nagłówek z widgetem „Nav Menu" wpiętym w menu WP → nav-edyty propagują się same.

## 11. Aktualności = ZAPROJEKTOWANE (standard), nie archiwum motywu
`page_for_posts` daje gołe „Archives" + surowe linki. Zamiast tego: mu-plugin z shortcode `[kw_aktualnosci]` (siatka kafelków wpisów) + strona Elementor (żółty banner + shortcode), `page_for_posts=0`. Pliki: `assets/mu-kw-aktualnosci.php` + CSS `.kw-akt-*` w `assets/base-additions.css`.

## 12. Mobile = standard (zajebiście, nie afterthought)
- Brandowy hamburger (nie domyślny — potrafi wyjść różowy; nadpisz `!important`).
- Header mobilny: logo + hamburger w rzędzie, CTA `flex-basis:100%` pełnoszerokie pod spodem; dropdown rounded+shadow, `a{padding:15px 18px}`.
- Typografia <768px: h1 ~30px, h2 ~23px. Paddingi: `padding_mobile`. Gridy: 1 kol <768, 2 <1024.
- Weryfikuj zrzutem 390×844 (isMobile) + klik `.menu-toggle`.

---

## 13. Protokół weryfikacji (tańszy, mniej tur Claude)
1. **Strukturalnie HTTP najpierw** (tanio): `Invoke-WebRequest` + grep markerów (klasy, `kw-grid`, brak `__IMG_`, brak `Trying to access`, https w URL-ach zdjęć). Łapie 80% regresji bez vision.
2. **Wizualnie na końcu / dla estetyki** — nie po każdej zmianie. Batch fixów → jeden pass screenów.
3. **Weryfikuj JAK USER** — Codex headless z `ignoreHTTPSErrors` ukrywa mixed-content; bot-blokowane embedy (Google Maps) puste; boolean-detekcja Codexa bywa fałszywie negatywna → przy faktach wizualnych ufaj screenowi/HTTP, nie asercji Codexa.
4. **`.local` HTTPS:** `Invoke-WebRequest` w PS 5.1 wymaga `ServicePointManager.CertificatePolicy` (TrustAll) — self-signed.
