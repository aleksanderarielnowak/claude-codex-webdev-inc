# ⚠️ PIERWSZA BUDOWA = TEST WALIDACYJNY (usuń ten plik po teście)

> **Ten plik jest tymczasowy.** Po przeprowadzeniu pierwszego pełnego przebiegu: `git rm FIRST-BUILD-TEST.md`, commit, push. Wnioski przenieś do `LEARNINGS.md`.

## Co to jest
Pierwszy pełny build strony **od zera jako spakowany plugin** `claude-codex-webdev-inc`. Wiedza w środku (fazy, tory buildu, HARDENED-RECIPES, design-craft) jest sprawdzona **ręcznie** (Kärcher / OSK Jan), ale **nie była jeszcze uruchamiana jako plugin**. Ten przebieg to walidacja: czy skill `wordpress` + referencje prowadzą przez cały proces gładko.

## Jak odpalić
- Wejście: wizytówka GMB / URL / social / NIP / nazwa+lokalizacja.
- `/claude-codex-webdev-inc:build <źródło>` albo po prostu opisz zadanie WP (skill `wordpress` wejdzie sam).
- Wymagania: Codex z tokenami + MCP (`/codex-setup` jeśli rozłączony), site w Local (user tworzy/klonuje — patrz `skills/wordpress/references/gmb-workflow/START-HERE.md`).

## Nastawienie (v0.7.0)
- **Design-concept PRZED buildem** (`skills/wordpress/references/design-craft.md`): wielki pomysł/motyw, kierunek wizualny, 2–3 sekcje-sygnaturki pod branżę, świadomy ruch, UX/konwersja. **Pokaż koncept userowi do akceptacji, potem produkcja.**
- **Projektuj, nie wypełniaj** — research to surowiec; sekcje/przejścia/animacje ze smakiem i tematycznie.

## Na co uważać / co złapać podczas testu (audyt przedtestowy)
1. **Bootstrap wp-cli na Local** (HARDENED §1–§2: `php-cli.ini` + `wp.cmd` + `wp-cli-db.php`) — najkruchszy krok. Zanotuj, ile zajął i co się sypało → kandydat na auto-generator.
2. **Golden extraction:** po udanym buildzie wyciągnij realne `sections/*.json` + `compositions/<branza>.json` z `_elementor_data` — następny projekt = wypełnianie, nie autorstwo. (Największa dźwignia.)
3. **Weryfikacja HTTP-first** (§13) + QA per-etap (`qa-checklist.md`), nie tylko na końcu. Nie ufaj headless Codexa dla mixed-content/mapy.
4. **Twarde recepty od startu:** author→apply, HTTPS od razu (§3), FA5 (§6), mapa = iframe (§9), Aktualności zaprojektowane (§11).
5. **Zapisuj wnioski:** WP-owe → `LEARNINGS.md` (ten plugin); komunikacja z Codexem → `LEARNINGS-CODEX-COMMS.md` w `claude-codex-synapse`.

## PO TEŚCIE — sprzątanie (obowiązkowe)
```
git rm FIRST-BUILD-TEST.md
git commit -m "Usuń notatkę testową po pierwszym buildzie; wnioski w LEARNINGS.md"
git push
```
+ przenieś złapane wnioski/regresje do `LEARNINGS.md` i (jeśli dotyczą speców) do odpowiednich referencji.
