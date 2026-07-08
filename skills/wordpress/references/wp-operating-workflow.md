# Workflow operowania na WordPressie (orkiestracja)

Jak webdev-inc prowadzi zadanie na istniejącym sajcie: od planu, przez tryb pracy, po persystencję i uczciwy audit. Uzupełnia `wp-operational-patterns.md` (bezpieczne zmiany, low-level) i `wp-change-channels.md` (kanały). Wzorce zaadaptowane z `seodyseusz` (netin.pro) — adaptacja, nie kopia.

## 1. Kanały jako config (pluggable)
Kanały połączenia to **dane, nie hardcode** — trzymaj wybór i dostępność w configu/rejestrze per projekt (`wp-project.json`, szablon `wp-project.template.json`; rejestr typów w `wp-change-channels.md`). Zmiana kanału = zmiana pola, nie przepisywanie logiki. To furtka pod przyszły `own-connector`.

## 2. Plan-then-execute (dla buildu i rozbudowy)
Zadania wieloetapowe prowadź planem, nie improwizacją:
1. **Zaplanuj fazy → punkty** (np. F1 środowisko · F2 fundament · F3 strony · F4 SEO · F5 QA), każdy punkt z jawnym „co i jakim kanałem/narzędziem".
2. **Pokaż plan i uzyskaj akceptację** (AskUserQuestion) zanim ruszysz produkcję.
3. **Wykonuj wg zatwierdzonego planu** — nie zmieniaj scope w locie; zmiana = nowa decyzja z userem.
4. **Notatka per punkt** (co zmieniono, jaki kanał, snapshot) = ścieżka audytu i baza pod rollback.

## 3. Tryby pracy (dobierz do ryzyka)
- **Hurtowo** (domyślne dla prostych): lecisz wszystkie punkty, raport na końcu — mały/standardowy build, ufasz procesowi.
- **Per-punkt**: zatrzymujesz się po każdym punkcie, czekasz na akceptację (Akceptuj/Zmień/Pomiń) — pierwszy build u klienta, sajt krytyczny, duża rozbudowa.
- **Hybryda**: low-risk hurtowo (np. treść sekcji), strategiczne per-punkt (np. zmiany w plikach motywu, deploy na prod).
Zapytaj o tryb w pre-flight, gdy zadanie jest nietrywialne.

## 4. Per-projekt workspace + stan
Trzymaj robotę per sajt/projekt, ze stanem do wznowienia:
```
<workspace>/<projekt>/
  INDEX.md              # przegląd: branża, kontakt, stack, cele
  wp-project.json       # tożsamość sajtu + dostępne kanały + stack (patrz template)
  snapshots/<ts>/<typ>/ # stany sprzed zmian (rollback)
  deliverables/         # output (build, audyt, raporty)
  input/                # dane od klienta / fallback manualny
  plan-state.json       # fazy/punkty + status (wznawianie sesji bez powtórek)
```
`plan-state.json`: `{task, started, fazy:[{id,name,punkty:[{id,status,notes_file}]}]}`. Przerwana sesja → czytaj stan → kontynuuj od `pending`.

## 5. Uczciwość i audit (twarde)
- **Metryki z dowodem**: każdy wynik (Lighthouse LCP/CLS, HTTP status, „obrazy OK") ma konkretne źródło + datę — nie estymuj „z głowy". Przy nieoczywistych wnioskach użyj REVIEW (Synapse) zamiast zgadywać.
- **Tool/kanał zawiódł → STOP + pytanie do usera**, nie cichy fallback (np. 5 ręcznych fetchy udających „pełny crawl/QA"). Zgłoś błąd i 2–3 propozycje.
- **Deploy/zmiana = snapshot przed i po** + wpis w `logs/audit.jsonl` (co, kanał, before→after). User widzi dokładnie, co się zmieniło i z jakim skutkiem.

## 6. Pre-action na żywym sajcie
Przed zmianą na LIVE potwierdź krótko: **kanał** (z rejestru) · **zakres** (pojedynczy/bulk/pliki) · **tryb** (live+snapshot / dry-run) · **builder** (z `profile`/`wp-project.json`). Dla plików motywu obowiązuje reguła zgody (patrz `wp-change-channels.md`).

## 7. Companion (na przyszłość)
Gdy powstanie własny `own-connector` (wtyczka WP + runner), wystaw go dwutorowo: slash-command dla usera + interfejs shell dla subagentów (subagent nie woła cudzego slash-commanda). Zdefiniuj pre-action protocol w jego dokach, by webdev-inc mógł go prowadzić spójnie.
