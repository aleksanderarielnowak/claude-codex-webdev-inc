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

## 5. Podział author/apply — natywny builder + zapis meta
Codex pisze `build-<slug>-native.php` na `assets/native/native-lib.php` z tokenami:
- zdjęcia: `__IMG_<slug>__` / `__IMGURL_<slug>__`
- CF7: `__CF7_SHORTCODE__`, mapa: `__MAP_SHORTCODE__`

Claude: `wp media import <png> --porcelain` → ID, `wp_get_attachment_url(ID)` → URL (https!), buduje `media.json`, podstawia tokeny i uruchamia `wp eval-file build-<slug>-native.php`. Builder zapisuje `update_post_meta($id,'_elementor_data', wp_slash(wp_json_encode($data, JSON_UNESCAPED_UNICODE)))`, `_elementor_edit_mode=builder`, `_elementor_template_type=wp-page`, `_wp_page_template=elementor_header_footer`; na końcu czyści CSS Elementora.
**BOM:** PowerShell 5.1 `Set-Content -Encoding utf8` dodaje BOM → PHP `json_decode` zwraca null. Zapisuj `[IO.File]::WriteAllText($p,$json,(New-Object Text.UTF8Encoding($false)))` albo strip BOM w PHP: `preg_replace('/^\xEF\xBB\xBF/','',$raw)`.

---

## 6. Reguły natywnego `_elementor_data`
- **Ikony → Font Awesome 5** (Elementor 4.x ma FA5, nie 6!). Nazwy FA5: `fa-tools, fa-map-marker-alt, fa-tint, fa-undo, fa-tachometer-alt, fa-exclamation-triangle, fa-check-circle, fa-file-alt`.
- **Hero full-bleed:** natywny kontener `content_width:full`, kontrolowany padding responsywny i tło przez klasę w `native.css`, jeśli Elementor nie emituje tła z meta.
- **Przyciski:** używaj natywnego widgetu button; tekst CTA bez numerów telefonu, link `tel:` zostaje.
- **Mapa:** preferuj widget WP Go Maps albo natywny shortcode/widget dodatku; unikaj duplikowania map na jednej stronie.
- **Responsywne paddingi:** dodaj `padding_mobile`/`padding_tablet` w builderze (hero 72/58, banner 62/46, content 48/48 na mobile).

---

## 7. Równe kafelki — buduj jako natywne kontenery
Karty buduj przez helpery `fm_native_*` i klasy `fm-*` z `assets/native/native.css`. Równe wysokości, odstępy i stackowanie mobile mają wynikać z natywnej struktury kontenerów oraz CSS projektu, a nie z późniejszych poprawek.

---

## 8. CSS projektu
Style projektu trzymaj w `assets/native/native.css` albo w globalnym CSS Elementora. Animacje muszą mieć bazowo `opacity:1`, `prefers-reduced-motion` i brak layout-shiftów.

---

## 9. Mapa = Google embed iframe bez klucza
WP Go Maps domyślnie silnik Google → `ApiProjectMapError` bez klucza. Najpewniej: iframe (renderuje się w przeglądarce usera; w headless Codexa NIE — bot — nie weryfikuj mapy zrzutem):
```
Użyj widgetu mapy z WP Go Maps / dodatku Elementora albo natywnego shortcode'u mapy, jeśli widget tego wymaga. Weryfikuj na froncie u usera, bo embedy map bywają puste w headless.
```

## 10. Header przez HFE i Nav Menu
Header buduj jako szablon HFE z logo, widgetem „Nav Menu" podpiętym do menu WP i natywnym CTA. Edycja menu WP ma propagować się do nagłówka bez ręcznej edycji szablonu.

## 11. Aktualności = ZAPROJEKTOWANE (standard), nie archiwum motywu
`page_for_posts` daje gołe „Archives" + surowe linki. Zamiast tego: mu-plugin z shortcode `[kw_aktualnosci]` (siatka kafelków wpisów) + strona Elementor (banner + shortcode), `page_for_posts=0`. Plik: `assets/mu-kw-aktualnosci.php`; style kafelków trzymaj w `assets/native/native.css` projektu.

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
