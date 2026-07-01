# Kanały połączenia i nanoszenia zmian w WordPressie

webdev-inc rozmawia z sajtem przez **abstrakcyjny kanał połączenia** — konkretna implementacja jest **pluggable** (wzorzec tool-agnostyczny). Dzięki temu zostawiamy furtkę: dziś używamy WP-CLI / connectora / headless, jutro podepniemy **własny connector**, nie zmieniając logiki skilla. webdev-inc **wykrywa i zapisuje**, które kanały są dostępne per sajt (patrz „Rejestr per-projekt"), i dobiera najtańszy sprawny.

## Rejestr typów kanałów
| id | transport | zdolności | kiedy | status |
|---|---|---|---|---|
| `local-wpcli` | Claude uruchamia `wp.cmd` lokalnie | pełne WP-CLI, DB, pliki | lokalny dev (Local by Flywheel) | ✅ dostępny |
| `ssh-wpcli` | WP-CLI po SSH na serwerze | pełne WP-CLI, DB, pliki motywu/pluginów | żywy sajt z dostępem SSH | ✅ gdy są dane SSH |
| `rest-connector` | most REST przez wtyczkę na sajcie (np. connector `wordpress-terminal`, albo Code Snippets REST) | posts/pages/meta, `_elementor_data`, ACF, `wp_options`, cache-flush; NIE dowolne pliki | żywy sajt z zainstalowanym connectorem | ⚙️ gdy zainstalowany |
| `headless-codex` | Playwright przez Codexa (Synapse), steruje wp-admin/builderem | wszystko, co człowiek w przeglądarce (GUI-only, edytor Elementora, dowolny builder) | brak connectora/SSH albo rzecz wyłącznie GUI | ✅ uniwersalny fallback |
| `own-connector` | **NASZA** wtyczka WP: akcje REST + headless combo | docelowo: REST-owe akcje domenowe + własny headless-runner | gdy zbudujemy własny connector | 🔜 zarezerwowany |

**Zasada wyboru (tiered, od najtańszego sprawnego):**
1. lokalny dev → `local-wpcli`;
2. żywy sajt: `rest-connector` (jeśli jest) → `ssh-wpcli` (jeśli SSH) → w ostateczności `headless-codex`;
3. rzecz **GUI-only** (blob ustawień wtyczki bez klucza, operacja w edytorze buildera bez API) → `headless-codex` niezależnie od reszty;
4. gdy powstanie `own-connector` → staje się preferowanym dla żywych sajtów (łączy REST + headless w jednym, audytowalnym kanale).

> `rest-connector` i `headless-codex` są **komplementarne**: pierwszy jest tani i deterministyczny, drugi robi to, czego żaden REST nie dosięgnie. Nie wybieramy jednego na stałe — webdev-inc decyduje per zadanie.

## Rejestr per-projekt (zapisuj dostępne opcje)
Po pierwszym kontakcie z sajtem zapisz, co jest dostępne, żeby nie odkrywać tego od nowa — w `.codex/state-cache.json` albo `wp-project.json` (szablon: `references/wp-project.template.json`):
```json
{
  "site": "example.com",
  "env": "live",
  "channels_available": ["rest-connector", "ssh-wpcli", "headless-codex"],
  "channel_default": "rest-connector",
  "stack": { "theme": "Blocksy", "builder": "gutenberg+stackable", "content_store": "post_content-blocks" },
  "notes": "connector wordpress-terminal wpięty; SSH przez hosting X; cache: LiteSpeed"
}
```
Aktualizuj przy zmianie stacku/dostępów. To fundament pod przyszły własny connector — kanały są danymi, nie hardcodem.

## Model author→apply (dla kanałów programistycznych)
`local-wpcli` / `ssh-wpcli` / `rest-connector`:
- **Codex AUTORUJE** pliki na dysk (PHP do `wp eval-file`, JSON `_elementor_data`, block-markup, configi) z placeholderami dla wartości powstających po akcji (`__IMG_<slug>__`, `__CF7_SHORTCODE__`, `__MAP_SHORTCODE__`). Self-check: poprawny JSON/PHP.
- **Claude APLIKUJE** wybranym kanałem: import assetów → ID → podstawienie → zapis. Na Windows/Local sandbox Codexa nie odpala php.exe/wp.cmd — apply robi Claude.
- **Zawsze**: snapshot → zmiana → weryfikacja (HTTP/grep tanio, screenshot dla UI). Trzymaj `rollback`.

## headless-codex (fallback, przez Codexa)
Tylko dla rzeczy **wyłącznie GUI**. Codex prowadzi Chromium (Playwright) z zapisaną sesją (`storageState` w `.codex/.session/`); Claude weryfikuje screenshotem/HTTP (uwaga: `ignoreHTTPSErrors` maskuje mixed-content; bot-blocked embedy renderują pusto). Na **cudzych/żywych sajtach klientów** logowanie do panelu przez przeglądarkę stosuj ostrożnie (brak audit-logu, ryzyko cudzych sesji) — preferuj `rest-connector`/`ssh-wpcli`, jeśli są.

## ⚠️ Reguła zgody na pliki motywu
Zmiany „w bazie" (`wp_options`, meta, `_elementor_data`, block-markup w `post_content`) rób autonomicznie wybranym kanałem. Ale **snippet / pliki / motyw** (`functions.php`, `style.css` child-theme, mu-plugin, edytor plików motywu) — **NAJPIERW poinformuj usera** co, gdzie i po co chcesz umieścić/zmienić; poczekaj na potwierdzenie. Pliki motywu edytuje się przez `ssh-wpcli` (albo docelowo `own-connector`); są trwalsze i łatwiej nimi popsuć niż wpis w bazie → świadoma zgoda = bezpiecznik.
