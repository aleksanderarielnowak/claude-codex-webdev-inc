# Wzorce operacyjne pracy na WordPressie

Bezpieczne, powtarzalne wzorce operowania na sajtach WP — niezależne od kanału połączenia (patrz `wp-change-channels.md`). To wiedza „jak nie zepsuć i jak móc cofnąć", którą webdev-inc stosuje przy każdej zmianie na istniejącym sajcie.

> **Pochodzenie i licencja.** Wzorce zainspirowane architekturą `wordpress-terminal` (Karol Samson, netin.pro) — przeniesione jako **konwencje/wzorce**, nie kopia kodu (tamten kod jest proprietary). Wiedzę dev/review WP (bloki, motywy, Woo, security…) czerpiemy ze skilli `wp-*` (Jorge Rosal, **MIT**) — patrz sekcja „Wiedza dev/review". Własny connector (`own-connector`) zbudujemy od zera wg tych wzorców.

## 1. Snapshot → zmiana → weryfikacja → (rollback)
Każda zmiana destrukcyjna: **najpierw snapshot stanu sprzed**, potem zmiana, potem weryfikacja. Trzymaj możliwość cofnięcia.
- Snapshot = pełna kopia stanu obiektu PRZED (nie diff), z walidacją że to poprawny JSON zanim zapiszesz.
- Układ: `snapshots/<timestamp ISO>/<typ>/<id>.json` — typy: `pages`, `posts`, `elementor`, `acf`, `blocks`, `meta`, `menu-items`, `options`, `theme-files`.
- `rollback` przywraca z snapshotu: całą ostatnią operację / konkretny timestamp / pojedynczy item. Per-typ handler; błąd na itemie nie blokuje reszty.
- Domyślnie snapshot ZAWSZE; `--no-snapshot` tylko na wyraźne życzenie (ostrzeż: nieodwracalne).

## 2. Audit log (JSONL)
Każda operacja zapisu → jedna linia do `logs/audit.jsonl`:
```json
{"ts":"2026-07-01T15:24:11Z","action":"page.update","target":"42","status":"ok","extra":{"title":"..."}}
```
Pola: `action` (`page.update`, `elementor.set`, `theme.edit`, `plugin.activate`…), `target` (id/slug/`-`), `status` (`ok`/`fail`/`skip`), `extra` (JSON). Daje ścieżkę audytu „co, kiedy, z jakim skutkiem".

## 3. Profil stacku (zasila Krok 0 i `wp-project.json`)
Wykryj i zapisz stack raz, potem czytaj z pliku:
- `wp_version`, `php_version`, `theme` (name/stylesheet/template/is_child), `builders[]`, `primary_builder`, `plugins` (seo/forms/cache/security/elementor/acf/spectra…), `content_store`.
- Źródło: connector `/info` (najlepsze) → fallback core REST (`/wp/v2/themes?status=active`, `/wp/v2/plugins`) → `mixed`.
- Zapisz do `wp-project.json` (szablon `wp-project.template.json`); pokaż userowi jedną linijkę: „Stack: Blocksy + Gutenberg+Stackable + Yoast · cache: LiteSpeed".

## 4. Dry-run
Dla operacji zapisu wspieraj `--dry-run`: pokaż payload/efekt, nie wysyłaj. Domyślnie przy nieoczywistych bulkach.

## 5. Bulk z CSV — izolacja błędów
Operacje masowe: parsuj CSV wiersz-po-wierszu, wołaj per-item; **błąd na jednym itemie nie przerywa batcha**; zbierz errlog na końcu (co przeszło, co nie i dlaczego).

## 6. Bezpieczeństwo poświadczeń
- Sekrety (Application Password, klucz API connectora, klucz SSH) w plikach `0600`, **nigdy w argv** ani w repo (`.gitignore`: dane per-projekt, sekrety).
- Own-connector (docelowo): klucz API jako hash (SHA256) + porównanie stałoczasowe (`hash_equals`), prefiks tokena, namespace REST stały (zmiana = breaking).
- Uwaga na Gutenberg: przy zapisie `post_content` z blokami (`<!-- wp:* -->`) pilnuj, by filtr `kses` nie wyciął markupu bloków (własny connector musi to obsłużyć świadomie).

## 7. Routing kanału (connector-first z fallbackiem)
Wybieraj kanał wg rejestru (`wp-change-channels.md`), z jawną możliwością wymuszenia (`--via-rest` / wskazanie kanału). Sprawdzaj wersję connectora zanim użyjesz nowszej operacji; brak → fallback niższego kanału.

## 8. Ścieżki plików wejściowych
Konwencja `@relative/path` → rozwiń do workspace projektu (`content/<typ>/relative/path`), auto-mkdir, **odrzucaj path traversal** (`..`, ścieżki absolutne poza workspace).

## Wiedza dev/review WP (skille `wp-*`, MIT — Jorge Rosal)
Gdy zadanie dotyka kodu/architektury WP, **korzystaj z tych skilli** (są aktywne w środowisku przez `wordpress-terminal`; NIE powielaj ich treści). Mapowanie temat → skill:
- bezpieczeństwo kodu → `wp-security-review` · wydajność → `wp-performance-review`
- plugin → `wp-plugin-development` · motyw/theme.json/FSE → `wp-theme-development` · bloki Gutenberg → `wp-block-development`
- WooCommerce → `wp-woocommerce-dev` · REST API → `wp-rest-api-development` · ACF/model treści → `wp-acf-and-content-modeling`
- panel admin → `wp-admin-ui-development` · headless/WPGraphQL → `wp-headless-and-wpgraphql`
- testy → `wp-test-strategy` · PHPStan → `wp-phpstan-review` · CI/CD/release → `wp-ci-cd-and-release-engineering`
- migracje/upgrade → `wp-migration-upgrade-review` · dostępność → `wp-accessibility-review`
- WordPress Playground → `wp-playground-development` · audyt/onboarding sajtu → `wp-site-audit-and-onboarding` · WP-CLI/ops → `wp-wpcli-and-ops`

> Gdyby webdev-inc miał działać bez `wordpress-terminal`, te skille (MIT) trzeba **vendorować z atrybucją** (`THIRD-PARTY-NOTICES.md`) — na razie odwołujemy się do już zainstalowanych.
