# Kanały nanoszenia zmian w WordPressie

Jak webdev-inc wprowadza zmiany na stronie. Dwa kanały, wybierane wg kosztu i tego, czy coś da się zrobić „w bazie", czy tylko przez GUI.

## Kanał 1 — WP-CLI / REST / kod (DOMYŚLNY)
Deterministyczny, odwracalny, tani. Model **author→apply** (za `codex-collab`):
- **Codex AUTORUJE** pliki na dysk (PHP do `wp eval-file`, JSON `_elementor_data`, configi) z placeholderami dla wartości powstających dopiero po akcji Claude'a (`__IMG_<slug>__`, `__CF7_SHORTCODE__`, `__MAP_SHORTCODE__`). Self-check: pliki to poprawny JSON/PHP.
- **Claude APLIKUJE** własnym CLI: import assetów → ID → podstawienie placeholderów → zapis. To krok uprzywilejowany (na Windows/Local sandbox Codexa nie odpala php.exe/wp.cmd).
- **Zawsze**: snapshot → zmiana → weryfikacja (HTTP/grep tanio, screenshot dla UI). Codex zwraca `rollback`.
- **Ustawienia wtyczek**: jeśli siedzą w `wp_options`, użyj **mostu REST** (np. Code Snippets) zamiast klikania — `curl` w ms zamiast sesji przeglądarki.

## Kanał 2 — headless wp-admin (FALLBACK, przez Codexa)
Tylko dla rzeczy **wyłącznie przez GUI**: blob ustawień wtyczki bez czytelnego klucza w `wp_options`, operacje w edytorze Elementora bez API, config pluginu bez CLI/REST.
- Codex prowadzi Chromium (Playwright) z **zapisaną sesją** — `storageState` w `.codex/.session/`; loguj się do `/wp-login.php` raz, potem reużywaj.
- Claude weryfikuje screenshotem/HTTP (headless bywa mylący: `ignoreHTTPSErrors` maskuje mixed-content; bot-blocked embedy renderują pusto).
- Deleguj przez `/codex` z precyzyjnym specem: dokładny URL panelu, selektory/etykiety pól, wartości, kryteria „gotowe".

## Reguła wyboru kanału
> **REST/WP-CLI first. Headless wp-admin dopiero, gdy zmiana jest GUI-only.** Klikanie w GUI jest drogie i kruche — nie używaj go do tego, co REST/CLI załatwia deterministycznie.

## ⚠️ Reguła zgody na pliki motywu
Zmiany „w bazie" (`wp_options`, meta, `_elementor_data`) — autonomicznie. Ale **snippet / pliki / motyw** (`functions.php`, `style.css` child-theme, mu-plugin, edytor plików motywu) — **NAJPIERW poinformuj usera**: co, gdzie i po co chcesz umieścić/zmienić; poczekaj na potwierdzenie. Pliki motywu są trwalsze i łatwiej nimi popsuć niż wpis w bazie → świadoma zgoda = bezpiecznik.

Uwaga na przyszłość: gdy headless wp-admin będzie działać stabilnie, da też **dostęp do plików motywu bez SSH** (Wygląd → Edytor plików motywu / wtyczka file-manager). Ta sama reguła zgody wtedy obowiązuje.
