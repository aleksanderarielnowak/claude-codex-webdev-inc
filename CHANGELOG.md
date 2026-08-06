# Changelog

Format oparty na [Keep a Changelog](https://keepachangelog.com/pl/1.1.0/).
Wersje wcześniejsze niż 0.8.0 nie mają wpisów — historia w logu commitów.

## [0.8.0] — 2026-08-06

Wydanie o jednej rzeczy: **jak buduje się stronę**. Poprzednie wersje mówiły,
czego nie robić. Ta mówi, co robić i w jakiej kolejności.

### Dodane

- **`references/METODA-BUDOWY-SEKCJAMI.md`** — metoda nadrzędna, stojąca ponad
  wyborem stacku, buildera i motywu. Obowiązuje przy budowie od zera, rozbudowie
  i poprawkach. Gdy inny dokument w repo sugeruje coś innego, wygrywa ten.
  - **Przebieg:** plan designu i lista sekcji w kolejności → budowa sekcja po
    sekcji do końca listy → weryfikacja po każdej sekcji, nie na końcu.
  - **Każda sekcja = własny blok/kontener buildera** — samodzielna jednostka,
    którą można kliknąć, przesunąć, zduplikować, wyłączyć.
  - **Drabina wykonania:** sam builder → builder + dedykowana wtyczka → blok HTML
    dla fragmentu → blok HTML na całą sekcję. Zejście niżej wymaga uzasadnienia
    w handoffie.
  - **Braki buildera** nadrabiane motywem potomnym, wtyczkami i dodatkowym CSS —
    jako uzupełnienie komponentów, nigdy ich zamiennik.
  - **Jeden właściciel stylu na właściwość** — albo builder, albo arkusz.
  - **Bramki zaliczenia sprawdzalne maszynowo:** nieudokumentowany blok HTML,
    sekcja której nie da się kliknąć, reguły układu sekcji w CSS motywu
    potomnego, brak zrzutu — każde z osobna oznacza zadanie niezaliczone.
  - **Antywzorce** opisane na realnych przypadkach, nie w teorii.

### Zmienione

- `skills/wordpress/SKILL.md` — metoda wpięta jako sekcja otwierająca, przed
  routerem zadań; dopisana do listy reference jako czytana *przed* budową,
  a nie „gdy faza tego wymaga".
- `commands/build.md` — krok 1 wczytuje metodę; Faza 4 przepisana z budowy
  całej strony na budowę sekcja po sekcji z weryfikacją po każdej.
- `commands/section.md` — HTML przestał być równorzędnym „torem" do wyboru
  obok natywnego; jest szczeblem awaryjnym drabiny.
- Twarda zasada o stosie doprecyzowana: ręczny HTML jest **dozwolony** jako
  szczebel 3–4 drabiny, gdy builder i wtyczki realnie nie sięgają, i wtedy
  wymaga udokumentowania. Zakazany pozostaje jako skrót i domyślny tryb pracy.

### Kontekst

Zmiana wynika z trzeciego nawrotu tego samego błędu w praktyce: strony powstawały
jako jeden blok surowego HTML plus obszerny arkusz stylów. Front działał, ale
w edytorze nie było czego kliknąć — strona była nieedytowalna dla osoby, która
ma ją potem utrzymywać.

Poprzedni zapis reguły („nigdy ręczny HTML") był jednocześnie za słaby i za ostry:
nie mówił, jak budować, a jednocześnie zakazywał HTML-a tam, gdzie bywa jedynym
wyjściem (mapy, osadzenia zewnętrzne, nietypowe interakcje).
