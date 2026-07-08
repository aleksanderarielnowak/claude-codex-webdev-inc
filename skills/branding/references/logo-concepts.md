# Logo concepts dla marek fikcyjnych

Ten plik pomaga wybrać typ logo i przygotować realistyczny brief do AI image_gen. Logo concept jest częścią brandingu, ale nie jest skrótem do budowy sekcji: w WordPress sekcje, przyciski, kontenery i tła nadal powstają przez natywne elementy buildera albo dedykowany widget/plugin.

## Katalog stylów

### Wordmark
Logo oparte głównie na nazwie marki.

Używaj, gdy:
- nazwa jest krótka i charakterystyczna;
- marka ma wyglądać nowocześnie, premium albo editorial;
- tekst musi być ostry, czytelny i łatwy do zmiany.

Handoff: składaj wordmark prawdziwym Google Fontem w builderze albo pliku projektowym. Nie polegaj na AI rasterze do małych liter i precyzyjnej typografii.

### Lettermark / monogram
Znak z inicjałów, np. `B&B`, `PM`, `RC`.

Używaj, gdy:
- nazwa jest dłuższa;
- potrzebny jest favicon, avatar social, znak na zdjęciach i mały detal w headerze;
- brand ma mieć bardziej premium albo klubowy charakter.

Dla barbershopów monogram jest praktyczny, bo działa na szyldzie, ręczniku, naklejce, gift card i faviconie.

### Combination mark
Znak + wordmark, używane razem lub osobno.

Używaj, gdy:
- strona potrzebuje pełnego headera, ale też małego znaku responsywnego;
- marka ma łączyć usługę lokalną z produktami, merch albo rezerwacją online;
- potrzebny jest najbardziej elastyczny zestaw dla WP.

To domyślny wybór dla demo biznesu: raster mark jako asset, wordmark jako edytowalny tekst.

### Emblem / badge
Nazwa i symbol zamknięte w kształcie: koło, tarcza, pieczęć, szyld.

Używaj, gdy:
- branża korzysta z rzemiosła, tradycji, klubowości albo lokalu z atmosferą;
- logo ma wyglądać dobrze jako naklejka, szyld, stamp, grafika w hero;
- marka ma mieć „heritage” bez udawania starej firmy.

Uwaga: badge łatwo robi się zbyt szczegółowy. Do małych rozmiarów przygotuj uproszczony monogram bez mikrotypografii.

### Symbol / pictorial mark
Prosty znak obrazkowy bez nazwy.

Używaj, gdy:
- symbol wynika z mocnego konceptu marki;
- nazwa będzie blisko znaku w layoutach;
- potrzebujesz mocnego, małego akcentu.

Dla barbershopów unikaj pierwszego skojarzenia, jeśli jest zbyt dosłowne: skrzyżowane nożyczki, brzytwa, wąsy i barber pole są czytelne, ale szybko wyglądają generycznie. Lepiej użyć ich subtelnie: negatywna przestrzeń, linia, detal, pattern.

## Jak briefować AI image_gen

Cel: wygenerować **czysty raster concept**, który da się użyć jako favicon/social mark/hero graphic albo jako referencję do późniejszej wektoryzacji.

Dobry brief zawiera:
- typ znaku: monogram, emblem, combination mark, symbol;
- branżę i pozycjonowanie: premium, lokalny craft, nowoczesny grooming, szybki urban barbershop;
- kształt i kompozycję: square, centered, simple silhouette, thick strokes, high contrast;
- paletę z hexami albo opisem ról: deep green, warm cream, brass accent;
- ograniczenia: no mockup, no photorealistic scene, no tiny text, no complex ornaments, no 3D unless explicitly needed.

Przykład:
```text
Clean premium barbershop monogram logo concept, letters B&B, simple circular brass line, deep green background, warm cream mark, subtle razor and brush negative space, flat vector-like look, centered square composition, thick readable strokes, no mockup, no 3D, no tiny text.
```

## Raster vs vector — uczciwa granica

Wbudowane `image_gen` Codexa tworzy obraz rastrowy. Może wygenerować dobry **koncept** logo, ikonę, avatar, grafikę hero albo favicon export, ale nie zastępuje skalowalnego logo wektorowego.

Pragmatyczny workflow:
1. Wygeneruj prosty square mark na transparentnym albo jednolitym tle.
2. Używaj go w WP jako mały znak, favicon, social avatar albo dekoracyjny badge.
3. Wordmark i tekst marki składaj prawdziwym fontem Google w natywnym builderze, żeby litery były ostre, dostępne i edytowalne.
4. Jeśli projekt ma iść do realnej marki, odtwórz finalny znak jako SVG/vector w narzędziu projektowym i dopiero wtedy traktuj go jako logo produkcyjne.

## Kontrola jakości logo concept
- Czy znak działa w 32-48 px jako favicon?
- Czy ma wersję na jasne i ciemne tło?
- Czy nie zawiera drobnego tekstu wypalonego w rastrze?
- Czy nie kopiuje dosłownie znanych marek ani stockowego emblematu?
- Czy da się opisać jednym zdaniem, co symbol znaczy?
- Czy pasuje do palety i fontów z `brand.json`, zamiast być osobnym stylem?
