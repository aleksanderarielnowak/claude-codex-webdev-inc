---
name: branding
description: Use gdy nie istnieje realna marka do researchu i trzeba autonomicznie wymyślić pełną tożsamość demo/fictional brand: nazwa, tagline, paleta, typografia, logo concept i tone of voice; triggery: projekt demo/portfolio od zera, fikcyjny rebrand, brak istniejącej marki, user prosi o wymyślenie brandu.
---
# branding — autonomiczne tworzenie fikcyjnej marki

Ten skill uruchamiaj, gdy Claude ma **wymyślić markę**, a nie odtworzyć istniejący brand z GMB/WWW/sociali. Branding jest wejściem do designu: najpierw powstaje tożsamość, potem `wordpress` dobiera stack i projektuje sekcje.

## Kiedy skill odpala
- Projekt demo/portfolio od zera bez prawdziwej firmy, logo, nazwy albo strony źródłowej.
- Fikcyjny rebrand klienta z recept typu `recipe-client-to-fictional-rebrand`.
- Brief mówi „wymyśl markę”, „stwórz brand”, „demo business”, „fictional business” albo podobnie.
- Research realnej marki zwrócił brak wiarygodnych danych brandowych, a user chce iść dalej bez doprecyzowania.

## Proces wysokiego poziomu
1. **Research branży** — sprawdź aktualne wzorce nazw, kolorów, typografii, logo i tonu dla tej branży; szczegóły: `references/brand-identity-process.md`.
2. **Nazwa i tagline** — wymyśl jedną finalną nazwę oraz krótkie hasło, bez ankiety opcji dla usera.
3. **Paleta** — przypisz hex do ról `primary`, `dark`, `light`, `accent` i zapisz pary bezpieczne kontrastowo dla CTA.
4. **Typografia** — dobierz parę Google Fonts z jasnymi rolami: display/headings i body/UI.
5. **Logo concept** — wybierz typ znaku i przygotuj brief dla assetu; katalog stylów: `references/logo-concepts.md`.
6. **Voice/tone** — opisz w 2-3 zdaniach styl copy, słownictwo i granice tonu.
7. **Handoff** — zapisz `brand.json` albo `brand-spec.md` w folderze projektu, zanim ruszy Faza 3 Design.

## Twarde ograniczenia
- **Budowa WP zostaje natywna:** sekcje, przyciski, kontenery, karty i tła muszą powstać przez natywny edytor/builder albo dedykowany widget/plugin; branding nie może sugerować ręcznie wklejanych sekcji HTML/CSS. Patrz `../wordpress/references/gmb-workflow/HARDENED-RECIPES.md`.
- **Kontrast CTA jest częścią palety:** każdy kolor przycisku musi mieć wskazaną parę tekst/tło bezpieczną kontrastowo dla wariantów light, dark i photo-hero. Nie przekazuj palety bez tej notatki.
- **Stack wybiera WordPress workflow:** `branding` tworzy tożsamość, ale nie decyduje o builderze/motywie/wtyczkach. Stack nadal wybiera `../wordpress/references/gmb-workflow/STACK-DECISION-GUIDE.md`.

## Kontrakt wyjścia
Przekaż do Fazy 3 Design krótki artefakt `brand.json` albo `brand-spec.md` z polami:
- `name` — finalna nazwa marki.
- `tagline` — jednozdaniowe hasło.
- `palette` — hex + role `primary`, `dark`, `light`, `accent`, opcjonalnie `muted`; dopisz `button_contrast_pairs` z parami tekst/tło.
- `typography` — nazwy Google Fonts, role i krótkie uzasadnienie.
- `logo` — typ znaku, opis konceptu, ograniczenia użycia oraz ścieżka do wygenerowanego assetu, jeśli powstał.
- `voice` — 2-3 zdania o tonie, rytmie copy i słowach, których używać/unikać.
- `rationale` — 3-5 punktów łączących decyzje z researchem branżowym.
- `sources` — URL-e lub dokumenty użyte w researchu trendów i przykładów.

## Autonomia
Claude wymyśla brand **w pełni autonomicznie**: nie pyta usera o wybór między wariantami, tylko bierze odpowiedzialność za finalną decyzję. Przed rozpoczęciem builda WP zawsze pokazuje userowi gotowy brand: nazwa, tagline, opis palety, typografia, logo concept i krótkie uzasadnienie. To ta sama postawa co przy autonomicznej decyzji stacku: decyzja jest samodzielna, ale jawnie zakomunikowana.
