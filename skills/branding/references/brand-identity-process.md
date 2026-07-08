# Proces wymyślania marki fikcyjnej

Ten reference służy do tworzenia brandu, gdy nie istnieje realna marka do odtworzenia. Nie zastępuje researchu GMB dla prawdziwych firm; działa tylko wtedy, gdy projekt jest demo, portfolio albo świadomym fikcyjnym rebrandem.

## 1. Research branży
Zbierz aktualne, lekkie źródła:
- 3-5 realnych marek z branży: nazwy, oferta, układ słów, ton CTA.
- 2-3 źródła trendowe z bieżącego lub najbliższego sezonu: logo, typografia, palety, opakowania, retail/service design.
- 1-2 lokalnych konkurentów, jeśli demo ma lokalizację.

Syntetyzuj tylko wzorce, nie kopiuj cudzych nazw ani znaków. Zapisz `sources` w `brand.json` albo `brand-spec.md`.

## 2. Nazwa
Wybierz jedną finalną nazwę. Sprawdź, czy brzmi naturalnie w języku rynku i czy da się ją użyć w domenie, nagłówku hero, faviconie i CTA.

Typowe struktury:
- `Nazwisko + Barbershop / Barber Co. / Grooming Co.` — dobre dla rzemiosła, zaufania, lokalności.
- `The + rzeczownik + Barber` — dobre dla marek z klimatem klubu, pracowni albo miejsca spotkań.
- Jedno mocne słowo — dobre dla premium, lifestyle i prostego zapamiętania.
- `Supply Co. / Grooming Goods / Studio` — dobre, gdy strona ma pokazywać także produkty, merch albo gift cards.

Unikaj nazw tak generycznych, że brzmią jak placeholder: `Elite Barber`, `Premium Cuts`, `Best Grooming`.

## 3. Tagline
Tagline ma doprecyzować obietnicę, nie powtarzać nazwy. Dobre hasło ma 3-8 słów i mówi o efekcie lub doświadczeniu: precyzja, rytuał, pewność, szybkość, lokalny charakter.

## 4. Paleta
Zbuduj paletę rolami, nie tylko kolorami:
- `primary` — kolor akcji i rozpoznawalny akcent marki.
- `dark` — tło hero, stopka, kontrast dla premium.
- `light` — tło sekcji, karty, obszary cennika.
- `accent` — mały detal: linie, hover, badge, ikony.
- `muted` — opcjonalny neutral do ramek i drugiego planu.

Obowiązkowo dopisz pary kontrastowe dla przycisków. Przykład: `#F5EFE3` na `#17201C`, `#17201C` na `#D9A441`. Nie zakładaj, że „złoty na czarnym” zawsze działa; sprawdź kontrast dla realnych hexów.

## 5. Typografia
Dla większości demo wystarczy para Google Fonts:
- display/headings: serif, slab serif albo mocny grotesk, który daje charakter.
- body/UI: czysty sans z dobrą czytelnością w cenniku, formularzu i na mobile.

Dobieraj kontrast ról. Nie używaj dwóch fontów, które są prawie takie same, ani dwóch krzykliwych display fontów naraz.

## 6. Logo concept
Wybierz typ znaku z `logo-concepts.md`. Dla WP demo najpraktyczniejszy jest zestaw:
- prosty raster mark square na favicon/social/avatar,
- tekstowy wordmark złożony realnym fontem w builderze,
- opcjonalny badge/emblem jako grafika dekoracyjna w hero albo stopce.

Nie obiecuj, że AI image_gen wygeneruje finalne wektorowe logo. To koncept rastrowy; finalny wektor wymaga osobnego odtworzenia w narzędziu wektorowym.

## 7. Voice/tone
Opisz styl copy krótko:
- tempo zdań: krótkie, pewne, usługowe;
- słownictwo: konkretne usługi, efekt po wizycie, rytuał, fach;
- granice: bez pustych superlatyw, bez agresywnego macho, bez żartów kosztem klienta.

## 8. Handoff
Przed buildem pokaż userowi gotowy brand i zapisz artefakt. Potem Faza 3 Design używa go jako wejścia do `design-spec.json`, a decyzja stacku nadal wynika ze `STACK-DECISION-GUIDE.md`.

## Worked example: demo barbershop

### Research snapshot
Źródła użyte jako punkty odniesienia:
- Fellow Barber używa prostej, produktowo-usługowej struktury: barbershopy, lokacje, barberzy, sklep i kategorie groomingowe; komunikuje „elevated barbershops” i produkty pielęgnacyjne.
- Murdock London łączy barbershop z produktami hair/beard/shave/cologne, lokacjami i językiem eksperckim.
- Ruffians pokazuje premium doświadczenie salonowe, produkty, gift cards, barbershopy i kampanie z doświadczeniem typu „complimentary Old Fashioned”.
- Trendy logo na 2026 przesuwają się w stronę responsywnych systemów, cieplejszych form, taktylności i mniejszej zależności od jednego statycznego znaku.
- Trendy typograficzne na 2026 premiują powrót serifu, „mutant heritage”, ekspresję i wyraźną hierarchię, ale z czytelnością w digitalu.

Wniosek dla fikcyjnego barbershopu: nie iść w sam czarno-złoty kliszowy badge. Lepszy kierunek to „heritage, ale odświeżony”: ciemna zieleń zamiast czerni, ciepły krem, mosiężny akcent, mocny serif display i bardzo czytelny sans.

### Brand spec
```json
{
  "name": "Brass & Bristle",
  "tagline": "Precyzyjne cięcie. Spokojny rytuał.",
  "palette": {
    "dark": "#17201C",
    "primary": "#7A2E2A",
    "light": "#F5EFE3",
    "accent": "#D9A441",
    "muted": "#8B8578",
    "button_contrast_pairs": [
      { "background": "#17201C", "text": "#F5EFE3", "use": "CTA na jasnych sekcjach i photo-hero overlay" },
      { "background": "#F5EFE3", "text": "#17201C", "use": "CTA outline/secondary na ciemnych sekcjach" },
      { "background": "#D9A441", "text": "#17201C", "use": "mały akcent CTA, badge, hover; nie używać z białym tekstem" }
    ]
  },
  "typography": {
    "display": "Fraunces",
    "body": "Inter",
    "notes": "Fraunces daje heritage i charakter bez ciężkiego vintage plakatu; Inter utrzymuje czytelność usług, cennika i formularzy."
  },
  "logo": {
    "type": "combination mark + responsive monogram",
    "concept": "Monogram B&B wpisany w prosty okrąg z cienką linią mosiężną; drobny motyw szczotki/brzytwy jako negatywna przestrzeń, bez skomplikowanych detali. Wordmark składany fontem Fraunces/Inter w builderze, nie wypalony w rastrze.",
    "asset_prompt": "Clean premium barbershop monogram logo concept, letters B&B, simple circular brass line, deep green background, subtle razor and brush negative space, flat vector-like look, centered square composition, no mockup, no 3D, no tiny text"
  },
  "voice": "Mów spokojnie, konkretnie i fachowo. Copy ma brzmieć jak do klienta, który chce wyjść pewny wyglądu: krótkie zdania, nazwy usług, efekt po wizycie, bez napompowanego macho i bez pustych obietnic premium.",
  "rationale": [
    "Nazwa łączy materiał premium (brass) z rzemiosłem groomingowym (bristle), więc działa jako marka salonu i potencjalnej linii produktów.",
    "Paleta odchodzi od czerni na rzecz głębokiej zieleni i kremu, ale zostawia mosiężny akcent znany z barber/grooming estetyki.",
    "Fraunces + Inter łączy ekspresyjny serif heritage z czytelnym digital UI.",
    "Responsive monogram działa jako favicon, avatar i znak na zdjęciach, a wordmark pozostaje ostry i edytowalny jako tekst."
  ]
}
```

### Jak użyć w designie WP
W Fazie 3 Design traktuj `Brass & Bristle` jako gotowy brand input:
- hero: ciemna zieleń/photo overlay, tekst kremowy, CTA `#F5EFE3` na `#17201C` albo odwrotnie zależnie od tła;
- sekcje usług: jasne tło, ciemny tekst, burgund jako nagłówki/linie;
- logo raster: tylko jako znak/monogram; tekst marki składać natywnie w builderze fontami z handoffu;
- sekcje, przyciski, karty i tła budować wyłącznie natywnymi widgetami/elementami wybranego stacku.

### Źródła przykładu
- https://www.fellowbarber.com/
- https://www.murdocklondon.com/
- https://ruffians.co.uk/
- https://www.creativebloq.com/design/logos-icons/these-logo-design-trends-will-define-2026
- https://www.creativebloq.com/design/fonts-typography/breaking-rules-and-bringing-joy-top-typography-trends-for-2026
- https://www.creativebloq.com/typography/20-perfect-font-pairings-3132120
