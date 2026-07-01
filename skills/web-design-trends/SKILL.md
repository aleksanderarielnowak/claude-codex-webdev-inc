---
name: web-design-trends
description: Use PRZED projektowaniem/odświeżaniem wyglądu strony — gdy trzeba wybrać kierunek designu (layout hero, typografia, paleta, komponenty, mikrointerakcje) w oparciu o REALNĄ, BIEŻĄCĄ wiedzę o web-designie, a nie z pamięci. Triggery: "nowoczesny wygląd", "aktualne trendy", "odśwież design", "jak to teraz robią", "zaprojektuj sekcję hero", faza Design w pipeline budowy WP. Research deleguje do Codexa/WebSearch. Zasila fazę Design skilla `wordpress`.
---
# web-design-trends — projektuj z bieżącej wiedzy, nie z pamięci

Zanim zaprojektujesz layout/wygląd, **zbierz aktualne wzorce** (bieżący rok) dla danej branży i typu strony. Model kosztu ten sam co reszta: **research deleguj, syntezę trzymaj zwięzłą** — nie wciągaj surowego HTML/galerii do kontekstu.

## Kiedy
- Faza **Design** pipeline'u budowy (przed `references/gmb-workflow/section-library.md`).
- **Redesign / odświeżenie** istniejącej strony.
- Gdy user prosi o „nowoczesny / aktualny" wygląd albo o konkretną sekcję (hero, cennik, opinie).

## Jak (deleguj research, syntetyzuj krótko)
1. **Deleguj do Codexa** (`/codex`, sieć `-c sandbox_workspace_write.network_access=true`) albo użyj WebSearch: zbierz aktualne wzorce dla `<branża> + <typ strony>` — kierunki layoutu hero, typografii, palety, komponentów kart, mikrointerakcji; 2–3 realne referencje; wzorce **przestarzałe do unikania**.
2. **Wymóg na wynik = zwięzły design-brief JSON**, nie zrzut stron: `{ direction, palette, typography, hero_layout, components[], microinteractions[], references[], avoid[] , year, sources[] }`. Codex trzyma surowiznę na dysku, zwraca kompakt.
3. **Zsyntetyzuj** i podaj jako wejście do fazy Design skilla `wordpress` (`../wordpress/SKILL.md`).

## Guardraile (dopasuj trend do realiów projektu)
- **Aktualność z dowodem** — podaj rok i źródło; odrzuć „ponadczasowe" ogólniki bez pokrycia.
- **Dopasowanie do branży** — sprawdź z playbookiem `../wordpress/references/gmb-workflow/business-type-playbooks.md`; trend ma służyć konwersji, nie być ozdobą.
- **Edytowalność** — nie proponuj wzorców, które łamią zasadę „mid-SEOwiec sam to zmodyfikuje" (skomplikowane custom-buildy bez natywnych widgetów).
- **Wydajność / dostępność** — unikaj kosztownych efektów łamiących render/WCAG (np. ciężki `backdrop-filter`, kontrast <4.5, animacje bez `reduced-motion`) — patrz `../../LEARNINGS.md`.

## Wynik
Design-brief zasilający tor buildu (NATYWNY/HTML) w skillu `wordpress`. To „bieżąca wiedza webdev", na której webdev-inc buduje — zamiast projektować z głowy.
