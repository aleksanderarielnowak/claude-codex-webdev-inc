# METODA BUDOWY — SEKCJAMI. Reguła nadrzędna dla każdej budowy strony.

Ta metoda stoi ponad wyborem stacku, buildera i motywu. Obowiązuje przy budowie
od zera, przy rozbudowie i przy poprawkach. Jeśli jakikolwiek inny dokument w tej
wtyczce sugeruje coś innego — wygrywa ten.

## Zasada

**Strona powstaje SEKCJA PO SEKCJI, każda zbudowana narzędziami buildera.**

Nie powstaje jako wygenerowany plik markupu wrzucony do bazy. Nie powstaje jako
„cała strona naraz". Nie powstaje jako HTML ubrany w arkusz stylów.

## Przebieg

1. **Plan designu.** Zanim cokolwiek powstanie: koncepcja wizualna i **lista sekcji
   w kolejności** — co po czym idzie na stronie. Lista jest kontraktem, po niej
   sprawdzasz kompletność.
2. **Buduj po kolei, do końca.** Sekcja 1, weryfikacja, sekcja 2, weryfikacja…
   aż lista się wyczerpie. Nie przeskakuj, nie zostawiaj „do dokończenia potem".
3. **Każda sekcja = własny blok/kontener buildera.** Sekcja jest samodzielną
   jednostką, którą user może kliknąć, przesunąć, zduplikować, wyłączyć.
   Nigdy jedna wielka bryła na całą stronę.
4. **Następna sekcja.** I tak do końca planu.

## Drabina wykonania — schodzisz dopiero, gdy szczebel wyżej naprawdę nie działa

Kolejność jest wiążąca. Każde zejście niżej wymaga uzasadnienia w handoffie.

1. **Sam builder.** Natywne komponenty, ich ustawienia, ich system stylów
   (Customizer, global styles, ustawienia widgetu/bloku). To jest domyślna
   i preferowana droga. Ambicja: cała sekcja tutaj.
2. **Builder + wtyczka.** Brakuje komponentu (slider, formularz, opinie Google,
   kalkulator, filtr) → dokładasz dedykowaną wtyczkę i używasz JEJ komponentu.
3. **Blok HTML dla fragmentu.** Element, którego builder nie wyraża, a wtyczki
   nie ma sensu dokładać → ten **fragment** idzie w blok HTML. Reszta sekcji
   zostaje na komponentach.
4. **Blok HTML na całą sekcję.** Dopuszczalne, gdy sekcja jako całość jest poza
   zasięgiem buildera (mapa, osadzenie zewnętrzne, nietypowa interakcja).
   To jest wyjątek do udokumentowania, nie tryb pracy.

**Braki buildera nadrabiasz** zmianami w motywie potomnym, wtyczkami i dodatkowym
CSS — ale jako **uzupełnienie** tego, co zbudował builder, nigdy jako zamiennik.

## Kto jest właścicielem stylu

Dla każdej właściwości (odstęp, kolor, typografia, układ) **jeden właściciel**:
albo builder, albo arkusz. Nigdy obaj.

Domyślnie właścicielem jest builder. CSS wchodzi tylko tam, gdzie builder nie
sięga — i wtedy builder tej właściwości nie ustawia.

Podwójne właścicielstwo to najczęstsza przyczyna „front wygląda inaczej niż
zaplanowano": style inline z bloków biją arkusz tam, gdzie są, a arkusz wygrywa
przy `!important`. Powstaje hybryda, której nikt nie kontroluje.

## Bramki zaliczenia — sprawdzalne, nie deklaratywne

Zadanie jest niezaliczone, jeśli:

- strona zawiera blok HTML, którego **nie ma na liście udokumentowanych wyjątków**
  z uzasadnieniem;
- jakakolwiek sekcja nie jest samodzielnym blokiem/kontenerem, który user może
  kliknąć i edytować;
- arkusz motywu potomnego zawiera reguły **układu sekcji**, które powinny być
  ustawieniami komponentów;
- nie masz zrzutu potwierdzającego wygląd sekcji.

`ready: true` od Codexa obejmuje wyłącznie to, co dało się sprawdzić mechanicznie.
Zgodności wizualnej nie obejmuje nigdy. Zrzut robisz sam.

## Antywzorce — realne przypadki, nie teoria

**„Wygeneruję markup i wrzucę do bazy."** Najczęstszy nawrót. To, że wygenerowany
tekst nazywa się `core/columns` zamiast `<div>`, nic nie zmienia — nadal jest to
ręcznie sklejany markup, a nie budowanie komponentami. Sygnał ostrzegawczy: jeśli
piszesz sekcję jako tekst, robisz to źle.

**„Zrobię HTML, a wygląd załatwi arkusz."** Daje stronę działającą na froncie
i nieedytowalną w edytorze. User otwiera edytor i widzi ścianę tekstu zamiast
sekcji. Tak powstała Auto-Muza: trzy strony po jednym bloku `wp:html` i 1934
linie CSS w motywie potomnym.

**„Przepiszę HTML na bloki, ale zostawię CSS na wszelki wypadek."** Sprzeczne
polecenia dają hybrydę — bloki dokładają style inline, arkusz walczy o swoje,
front wychodzi inny mimo przejścia wszystkich kontroli automatycznych.
Przenosisz stylowanie do buildera → usuwasz odpowiadające reguły CSS.

**„Zbuduję całą stronę naraz i porównam na końcu."** Kiedy coś nie zagra, nie
wiadomo która sekcja zawiniła. Weryfikuj po każdej sekcji.
