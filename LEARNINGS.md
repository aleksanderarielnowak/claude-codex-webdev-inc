# LEARNINGS — webdev-inc (guardraile WP)

Log domenowy: realne bugi i reguły z budowy/rozbudowy stron WordPress/Elementor. Uzupełnia twarde recepty z `skills/wordpress/references/gmb-workflow/HARDENED-RECIPES.md` (tam pełne, rozpisane blokery) — tu krótkie reguły „nie powiel tego", wstrzykiwane do specu Codexa w sekcji „Znane pułapki".

Format: `data · obserwacja · reguła/guardrail`. Zaimportowane z LEARNINGS Synapse (sesja WP 2026-06-14), by Synapse został domenowo-neutralny, a wiedza WP mieszkała tu.

## Guardraile buildu (Elementor / front)
- **2026-06-14 · `Array.prototype.slice.call(new Set(x))` zwraca `[]`** — slice nie działa na Set/iterowalnych; cały IntersectionObserver się nie podpiął, animacje martwe (złapane dopiero wizualnie). **GUARDRAIL:** zawsze `Array.from(new Set(x))`, nigdy `slice` na Set.
- **2026-06-14 · SVG bez atrybutów `width`/`height` (tylko `viewBox`) w `<img>` z `height:auto` zwija się do 0px** — niewidoczny w swobodnych kontenerach. **GUARDRAIL:** każdy SVG ma `width` i `height` w root (obok `viewBox`). Waliduj: `node -e "const s=require('fs').readFileSync(f,'utf8'); if(!s.includes('width=')) throw new Error('missing width')"`.
- **2026-06-14 · `loading="lazy"` na obrazie startującym ze zwiniętą wysokością lub poza viewportem** — lazy nigdy nie odpala, `complete=false` na zawsze. **GUARDRAIL:** grafiki dekoracyjne w boxach: `loading=eager`, nie lazy.
- **2026-06-14 · `backdrop-filter: blur()` na dużych elementach** — bardzo drogi dla kompozytora; `Page.captureScreenshot` timeoutował. **GUARDRAIL:** oszczędnie z `backdrop-filter`/dużymi `blur`; przy problemach z renderem — pierwszy podejrzany.
- **2026-06-14 · Widget nagłówka (Elementor/HFE) renderuje wewnętrzny węzeł łapiący globalny kolor** — klasa na wrapperze nie dosięga tekstu. **LEKCJA:** celuj w wewnętrzny element (`.elementor-widget-container`, `h1`), nie tylko wrapper.

## Guardraile środowiska / aplikacji
- **2026-06-14 · Codex zapisał pliki w złym katalogu** — brak precyzyjnego `-C` i niejednoznaczne ścieżki → równoległy folder. **GUARDRAIL:** zawsze `-C <EXACT_ABS_DIR>`; w specu: „twórz WYŁĄCZNIE w tych ścieżkach; nie twórz innych folderów".
- **2026-06-14 · Encoding: pliki czytane z powrotem do PHP/JSON muszą być UTF-8 bez BOM** — PowerShell 5.1 `Set-Content -Encoding utf8` pisze BOM → PHP `json_decode` zwraca null. **GUARDRAIL:** zapisuj przez `[IO.File]::WriteAllText($p,$s,(New-Object Text.UTF8Encoding($false)))` albo stripuj `^\xEF\xBB\xBF`.
- **2026-06-14 · WP-CLI pod sandboxem Codexa (Windows+Local) nie odpala php.exe/wp.cmd** („Odmowa dostępu"). **REGUŁA:** Codex AUTORUJE pliki, Claude APLIKUJE własnym WP-CLI (podział author→apply). Patrz HARDENED-RECIPES §1–§2.

## Guardraile weryfikacji (headless bywa mylący)
- **2026-06-14 · `ready=true` z Codexa ≠ poprawność wizualna** — raportował `ok=true` bez menu w headerze, białą kartę edytora, 404 obrazy, `opacity:0` treści. **REGUŁA:** dla UI zawsze screenshot per-etap; nie ufaj samemu polu JSON.
- **2026-06-14 · `ignoreHTTPSErrors:true` maskuje mixed-content** — asset po HTTP na stronie HTTPS ładuje się w Codexie, ale u usera jest blokowany (puste obrazy). **REGUŁA:** normalizuj schemat do https zanim uznasz „obrazy OK".
- **2026-06-14 · Konfiguracja wtyczek siedzi w `wp_options`, klikanie w GUI przez przeglądarkę jest drogie · REGUŁA:** dla zadań opartych o `wp_options` używaj mostu REST (Code Snippets) zamiast Chromium — patrz `skills/wordpress/references/wp-change-channels.md`.
