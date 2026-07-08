# STACK DECISION GUIDE - wybor stosu WP per klient

Ten dokument jest miejscem, w ktorym Claude podejmuje autonomiczna decyzje o stosie WordPress dla konkretnego projektu z pipeline'u GMB/dowolne zrodlo -> strona WP. Claude nie pyta za kazdym razem o zgode na wybor buildera, motywu albo pluginu funkcyjnego, ale zawsze podaje uzytkownikowi wybrany stos, sygnaly z briefu i krotkie uzasadnienie. Twarda zasada obowiazuje niezaleznie od wybranego stosu: sekcje, przyciski, kontenery, karty, tła i uklady buduj przez natywny edytor/builder albo dedykowany widget/plugin; nie tworz gotowych sekcji przez recznie wklejany HTML/CSS jako skrot.

## 1. Tabela decyzyjna

| Sygnal z briefu klienta | Rekomendowany stos | Dlaczego | Fallback, jesli nie pasuje |
|---|---|---|---|
| Brak silnego sygnalu specjalistycznego, lokalna firma uslugowa, potrzebna szybka edycja przez klienta | Elementor + Hello Elementor + HFE + Happy Addons / Essential Addons + CF7 | To aktualny domyslny tor repo: sprawdzony, szybki, edytowalny i zgodny z `NATIVE-ELEMENTOR-BUILD.md` | Bricks, jesli priorytetem jest lzejszy output i dluzsza eksploatacja |
| Klient chce bardzo szybka, czysta, skalowalna strone i akceptuje mniejsza powszechnosc buildera niz Elementor | Bricks Builder | Bricks jest builderem-motywem z wbudowanym WooCommerce Builder, Query Loop, Menu Builder, Popup Builder, warunkami i interakcjami; oficjalnie pozycjonuje sie na wydajnosc i skalowalnosc | Elementor default, jesli klient lub zespol juz pracuje w Elementorze |
| Klient ma istniejaca strone w Divi albo ekosystem Elegant Themes | Divi 5 / Elegant Themes | Divi ma duzy ekosystem, marketplace, AI/Cloud/VIP w planach Pro; Divi 5 usuwa shortcode'y na rzecz formatu blokowego i nowego frameworka | Elementor default albo Bricks przy przebudowie od zera |
| Potrzebna latwa alternatywa dla Elementora, mniej dodatkowych pluginow i licencja na wiele stron | Breakdance | Breakdance reklamuje prosty visual builder, wiele funkcji w core, WooCommerce, formularze, popupy, dynamic data i licencje unlimited | Elementor default, jesli potrzebne sa najpopularniejsze addony i najwieksza baza wiedzy |
| Klient techniczny, agencja, SaaS, zaawansowane dynamiczne layouty, ACF, kontrola kodu | Oxygen Builder | Oxygen jest developer-first, daje duza kontrole i lifetime/unlimited licensing, ale wymaga mocniejszego know-how | Bricks, jesli nadal chcesz wydajnosc, ale latwiejsza obsluge wizualna |
| Prosta strona informacyjna, blog, portfolio, strona ekspercka, nacisk na native WordPress i minimalny narzut | Native WordPress Site Editor + block theme (np. Twenty Twenty-Five) | Site Editor edytuje header, footer, szablony, wzorce i style przez bloki; zero dodatkowego buildera | GeneratePress + GenerateBlocks, jesli potrzebujesz wiecej kontroli blokowej |
| Strona lokalna z naciskiem na performance, dostepnosc i dluga stabilnosc | GeneratePress Premium + GenerateBlocks Pro | GeneratePress komunikuje speed/stability/accessibility; GenerateBlocks dodaje lekki block-based builder, wzorce i global styles | Blocksy albo Kadence, jesli startery i gotowe UX sa wazniejsze niz minimalizm |
| Szybki build na gotowych starterach, WooCommerce, header/footer, ladny efekt bez ciezkiego buildera | Blocksy + Blocksy Companion | Blocksy ma starter sites, header/footer builder, WooCommerce, Gutenberg-ready i rozbudowany Companion | Kadence, jesli klient chce wiekszy pakiet design/security/commerce w jednym ekosystemie |
| Klient chce blokowy ekosystem z gotowymi szablonami, header/footer, sklepem i opcjonalnymi dodatkami security/membership | Kadence Theme + Kadence Blocks | Kadence oferuje theme + blocks + starter templates; wyzsze plany dodaja Shop Kit, security, backups i memberships | GeneratePress, jesli priorytetem jest lzejsza baza |
| Sklep, katalog produktow, platnosci, dostawa, warianty, subskrypcje | WooCommerce + dodatki Woo dobrane do modelu sprzedazy | WooCommerce jest open-source commerce platform dla WordPress; dodatki Woo obsluguja platnosci, dodatki produktowe i subskrypcje | Prosty katalog bez koszyka: blokowy katalog/oferta + formularz zapytania |
| Rezerwacje wizyt, grafiki pracownikow, salony beauty, medyczne, coaching, serwis | Amelia albo Bookly | Oba sa systemami appointment booking; Amelia celuje w branze uslugowe i eventy, Bookly ma free/pro/business/ultimate | WP Simple Booking Calendar dla samej dostepnosci terminow bez pelnego procesu wizyty |
| Wynajem domkow, apartamentow, sprzetu, pola campingowe, prosta dostepnosc terminow | WP Simple Booking Calendar | Lekki kalendarz dostepnosci z iCalendar import/export i prostym cennikiem rocznym | Amelia/Bookly, jesli potrzebne sa platnosci, pracownicy i formularz rezerwacji |
| Kursy, szkolenia, certyfikaty, e-learning, szkolenia B2B | LearnDash, Tutor LMS albo LifterLMS | To dedykowane LMS-y z builderami kursow, quizami, platnosciami, membership/subscriptions i raportowaniem w wyzszych planach | WooCommerce + produkty cyfrowe tylko dla prostych materialow bez LMS |
| Agencja nieruchomosci, listingi, mapa, agenci, CRM, zapytania o lokale | Estatik albo Houzez | Estatik to plugin/theme real estate, Houzez to pelny motyw real estate z CRM, builderami i listingami | Elementor/Bricks + custom CPT tylko dla malego portfolio kilku realizacji |
| Restauracja, kawiarnia, bar, menu online, QR, zamowienia z odbiorem | Five Star Restaurant Menu / Food Ordering + opcjonalnie WooCommerce | Dedykowany plugin daje menu, ceny, zdjecia, schema, QR i w Ultimate zamowienia z platnosciami | Prosta karta: natywne bloki/Elementor cards bez systemu zamowien |
| Firma uslugowa potrzebuje kalkulatora wyceny: remonty, sprzatanie, transport, eventy, konfiguratory | Cost Calculator Builder albo Formidable Forms | Dedykowane kalkulatory/formularze maja logike warunkowa, platnosci, PDF/quote i zbieranie leadow | CF7/WPForms tylko dla prostego formularza bez kalkulacji |

## 2. Buildery stron

### Bricks Builder

**Opis:** Bricks to visual site builder w formie motywu dla self-hosted WordPress. Oficjalna strona opisuje go jako narzedzie do wydajnych, skalowalnych i mocno konfigurowalnych stron; w core ma m.in. Query Loop Builder, Menu Builder, Popup Builder, WooCommerce Builder, forms, dynamic data, conditions i interactions.

**Cena:** platny. Oficjalny cennik: Starter 79 USD/rok (1 site), Business 149 USD/rok (3 sites), Agency 249 USD/rok (unlimited), Ultimate Lifetime 599 USD jednorazowo. Lokalne i stagingowe instalacje nie licza sie do limitu licencji.

**Kiedy wybrac:** gdy klient ceni performance, SEO, dluga utrzymywalnosc, bardziej semantyczna strukture niz typowy ciezki builder, dynamiczne listingi, custom loops, WooCommerce bez doklejania wielu dodatkow, albo gdy projekt ma rosnac.

**Kiedy nie:** gdy priorytetem jest maksymalna powszechnosc wsrod nietechnicznych klientow i gotowych tutoriali; gdy klient juz ma zespol pracujacy w Elementorze; gdy build ma byc ultra-szybki na obecnych helperach repo.

**Regula natywna:** uzywaj elementow Bricks: Section, Container, Block, Div, Button, Form, Query Loop, WooCommerce elements, Map, Tabs, Accordion. Bricks ma element HTML i custom code, ale w tym workflow nie sluza one do skladania sekcji, ukladow, przyciskow ani tła.

**Zrodla:** https://bricksbuilder.io/ , https://bricksbuilder.io/pricing/ , https://academy.bricksbuilder.io/developer/guides/converter/

### Divi 5 / Elegant Themes

**Opis:** Divi to motyw i builder od Elegant Themes. Divi 5 wedlug oficjalnej strony zostal przebudowany od zera, usuwa shortcode'y na rzecz szybszego, bardziej elastycznego systemu, zapisuje content w block-based format i ma nowy API dla modulow. Ekosystem obejmuje Divi Marketplace, Divi AI, Divi Cloud, Divi VIP, Divi Teams i Divi Dash.

**Cena:** platny, unlimited websites. Oficjalny cennik: Divi 89 USD/rok, Divi Lifetime 249 USD jednorazowo, Divi Pro 277 USD/rok, Divi Lifetime + Pro 297 USD dzisiaj + 212 USD/rok za Pro Services.

**Kiedy wybrac:** gdy klient juz ma Divi, kupiona licencje Elegant Themes, layouty z Divi Marketplace, albo potrzebuje szybkiej pracy w ekosystemie Divi/AI/Cloud. Dobry wybor przy migracji Divi 4 -> Divi 5 zamiast przepisywania calej strony.

**Kiedy nie:** gdy projekt jest nowy i performance/lekki output sa wazniejsze niz ekosystem; gdy trzeba uniknac lock-in do jednego buildera; gdy klient oczekuje natywnego edytora WP.

**Regula natywna:** buduj przez sekcje, rows, modules, Theme Builder i oficjalne moduły Divi. Nie uzywaj Code module do budowania gotowych sekcji lub layoutow.

**Zrodla:** https://www.elegantthemes.com/divi-5/ , https://www.elegantthemes.com/join/ , https://www.elegantthemes.com/marketplace/

### Breakdance

**Opis:** Breakdance to visual builder dla WordPress. Oficjalnie pozycjonuje sie jako prosty i szybki builder z pelnym zestawem funkcji: 145 elements, design library, WooCommerce integration, form builder, popup builder, dynamic data, class manager, conditions i import/export settings.

**Cena:** platny; oficjalna strona podaje Pro unlimited sites 199.99 USD/rok oraz Pro + AI Bundle 249.99 USD/rok jako oferta bundle. Strona deklaruje 60-day money-back guarantee i lock-in ceny odnowienia.

**Kiedy wybrac:** gdy potrzebujesz alternatywy dla Elementora z wieksza liczba funkcji w core, mniej dodatkow i prosty visual workflow; szczegolnie dla stron lokalnych z formularzami, popupami i WooCommerce.

**Kiedy nie:** gdy klient wymaga bardzo dojrzalego, najwiekszego ekosystemu dodatkow; gdy projekt zalezy od istniejacych assetow Elementora; gdy w zespole nie ma doswiadczenia z Breakdance.

**Regula natywna:** korzystaj z elementow Breakdance, formularzy, popupow, WooCommerce i dynamic data. Nie skladaj sekcji w recznym HTML/CSS.

**Zrodla:** https://breakdance.com/ , https://breakdance.com/pricing/

### Oxygen Builder

**Opis:** Oxygen to visual builder dla profesjonalistow/developerow. Oficjalna strona opisuje go jako developer-first visual builder z pelna kreatywna kontrola, wysokim performance, ACF integration i pelnym theme builderem. Oxygen 6 i Oxygen Classic sa rozrozniane na stronie producenta.

**Cena:** platny lifetime/unlimited. Oficjalny cennik pokazuje Basic 129 USD lifetime, WooCommerce 149 USD lifetime oraz Lifetime Bundle 199.50 USD lifetime w ofercie launchowej; licencja obejmuje unlimited installations i projekty klienckie.

**Kiedy wybrac:** gdy klient lub opiekun strony jest techniczny, gdy build wymaga ACF, dynamicznych szablonow, kontroli struktury i wydajnosci, albo gdy strona bedzie rozwijana jak produkt.

**Kiedy nie:** dla zwyklych lokalnych firm, ktore chca samodzielnie klikac proste zmiany; dla projektow, w ktorych opieka po wdrozeniu ma byc latwa dla nietechnicznego klienta; dla ultra-szybkiego defaultowego workflow repo.

**Regula natywna:** uzywaj elementow i komponentow Oxygen, repeaterow, conditions, WooCommerce integration i UI buildera. Kod custom moze wspierac logike, ale nie zastepuje natywnych sekcji, kart, przyciskow ani tła.

**Zrodla:** https://oxygenbuilder.com/ , https://oxygenbuilder.com/pricing/

## 3. Motywy blokowe, FSE i lekki Gutenberg

### Blocksy + Blocksy Companion

**Opis:** Blocksy to darmowy motyw Gutenberg/WooCommerce-ready z ekosystemem Companion, starter sites, header/footer builderem i rozbudowanymi modulami Pro. Oficjalny cennik wymienia m.in. pro starter sites, Shop extra, Post types extra i white label.

**Cena:** freemium. Blocksy Pro: Personal 69 USD/rok lub 199 USD lifetime, Business 99 USD/rok lub 299 USD lifetime, Agency 149 USD/rok lub 499 USD lifetime.

**Kiedy wybrac:** gdy potrzebujesz szybkiego, lekkiego motywu z dobrym customizerem, gotowymi starterami, WooCommerce i przyjazna edycja bez ciezkiego page buildera.

**Kiedy nie:** gdy projekt wymaga bardzo precyzyjnego, niestandardowego layoutu kazdej sekcji jak w Bricks/Oxygen; gdy klient juz ma zaakceptowany stack Elementor.

**Regula natywna:** buduj przez Gutenberg blocks, Blocksy Content Blocks, header/footer builder i kompatybilne bloki. Nie doklejaj gotowych sekcji przez reczny kod w widgetach.

**Zrodla:** https://creativethemes.com/blocksy/ , https://creativethemes.com/blocksy/pricing/

### GeneratePress + GenerateBlocks

**Opis:** GeneratePress to lekki motyw z naciskiem na speed, stability i accessibility. GenerateBlocks to maly zestaw lekkich blokow, ktory zamienia core editor w block-based builder z container, grid, headline, button, image, query, global styles, pattern library i pro blocks.

**Cena:** freemium. GP Premium 59 USD/rok, GenerateBlocks Pro 99 USD/rok, GeneratePress One 149 USD/rok; licencje mozna uzywac na projekty klienckie w limitach cennika.

**Kiedy wybrac:** gdy performance, dostepnosc, czysty output i dluga stabilnosc sa wazniejsze niz "wow" z gotowych addonow. Dobry dla ekspertow, blogow, B2B, prostych stron lokalnych i rozbudowy o wlasne patterny.

**Kiedy nie:** gdy klient oczekuje buildera typu drag-and-drop z duza liczba gotowych efektow; gdy potrzebujesz bardzo szybkiego, wizualnie bogatego buildu bez przygotowanej biblioteki blokow.

**Regula natywna:** korzystaj z core blocks, GenerateBlocks Container/Grid/Button/Image/Query, wzorcow i global styles. Nie wklejaj gotowych layoutow jako surowy kod.

**Zrodla:** https://generatepress.com/ , https://generatepress.com/premium/ , https://generatepress.com/blocks/ , https://generatepress.com/pricing/

### Kadence Theme + Kadence Blocks

**Opis:** Kadence to ekosystem theme + blocks + starter templates. Oficjalna strona opisuje global color/typography, header/footer builder, 200+ starter templates, a w wyzszych planach Shop Kit, security, backups i memberships.

**Cena:** freemium. Kadence Essentials 99 USD/rok, Pro 299 USD/rok, Elite 499 USD/rok.

**Kiedy wybrac:** gdy chcesz blokowy build z dobrymi starterami, header/footer, nawigacja, WooCommerce i opcjonalnym pakietem biznesowym w jednym ekosystemie.

**Kiedy nie:** gdy minimalizm i najnizszy narzut sa wazniejsze niz ekosystem; gdy potrzebujesz pelnej swobody visual buildera; gdy nie chcesz laczyc designu z dodatkami security/commerce jednego dostawcy.

**Regula natywna:** uzywaj Kadence Blocks, header/footer buildera, pattern hub i starter templates. Nie stosuj recznego kodu jako substytutu sekcji.

**Zrodla:** https://www.kadencewp.com/kadence-theme/ , https://www.kadencewp.com/pricing/

### Native WordPress Site Editor (FSE) + block themes

**Opis:** WordPress Site Editor pozwala projektowac caly site - header, footer, szablony, template parts, pages, styles i patterns - przez bloki. Dziala tylko z aktywnym block theme. Praktyczne motywy bazowe: Twenty Twenty-Four i Twenty Twenty-Five.

**Cena:** darmowe w WordPress core; motywy Twenty Twenty-Four/Five sa darmowe.

**Kiedy wybrac:** proste strony firmowe, blogi, portfolio, strony eksperckie, gdy klient chce jak najmniej pluginow i wystarcza mu edycja blokowa. Dobre, gdy brief nie wymaga zaawansowanego buildera ani specjalistycznych layoutow.

**Kiedy nie:** gdy potrzebujesz bardzo zlozonych animacji, zaawansowanych gridow, nietypowych komponentow, rozbudowanego sklepu albo client handoff ma byc w znanym builderze.

**Regula natywna:** buduj przez Site Editor, core blocks, theme blocks, patterns i template parts. Nie wklejaj gotowych sekcji jako recznie skladany kod.

**Zrodla:** https://wordpress.org/documentation/article/site-editor/ , https://wordpress.org/themes/twentytwentyfour/ , https://wordpress.org/themes/twentytwentyfive/

## 4. Pluginy specjalistyczne per funkcja biznesowa

### WooCommerce + kluczowe dodatki

**Opis:** WooCommerce to open-source commerce platform dla WordPress. Dodatki dobieraj do konkretnego modelu: WooPayments do platnosci, Product Add-Ons do personalizacji produktu, WooCommerce Subscriptions do platnosci cyklicznych.

**Cena:** WooCommerce core jest darmowy. WooPayments nie ma oplat setup/monthly, dziala pay-as-you-go od transakcji. Product Add-Ons: ok. 71 EUR/rok. WooCommerce Subscriptions: 279 USD/rok.

**Kiedy wybrac:** sklep z koszykiem, checkoutem, platnosciami, produktami fizycznymi/cyfrowymi, wariantami, abonamentami, dostawa, kuponami, integracjami marketplace.

**Kiedy nie:** prosty katalog uslug bez platnosci; oferta B2B, gdzie lepszy jest formularz zapytania; firma lokalna, ktora nie ma procesu obslugi zamowien.

**Regula natywna:** produkty, kategorie, cart/checkout i listingi buduj przez WooCommerce blocks, builder Woo danego stacku albo dedykowane widgety. Nie tworz recznych kart produktowych jako obejscia checkoutu.

**Zrodla:** https://woocommerce.com/ , https://woocommerce.com/products/woopayments/ , https://woocommerce.com/products/product-add-ons/ , https://woocommerce.com/products/woocommerce-subscriptions/

### Amelia

**Opis:** Amelia to booking plugin dla appointment booking, scheduling, eventow i branz uslugowych: beauty/spa, lekarze, joga, trenerzy, automotive, fotografowie, coaching, konsulting. Oficjalna strona wymienia m.in. automated notifications, group appointments, coupons, service extras, recurring appointments/events, deposit payments, packages, resources, cart, REST API, waiting list, WhatsApp, calendar sync i online meetings.

**Cena:** freemium. Free limited version jest dostepna na WordPress.org. Annual: Starter od 49 USD, Standard od 89 USD (regular 99), Pro od 149 USD (regular 199), Elite od 259 USD (regular 432). Lifetime: Standard od 299 USD, Pro od 449 USD, Elite od 799 USD.

**Kiedy wybrac:** wiele uslug, pracownicy, lokalizacje, grafik, zaliczki, pakiety, powiadomienia, integracje kalendarzy i spotkan online.

**Kiedy nie:** tylko statyczny kalendarz dostepnosci bez formularza i platnosci; bardzo proste "zadzwon i umow termin"; wynajem noclegow, gdzie wazniejszy jest iCal niz pracownicy/uslugi.

**Regula natywna:** osadzaj formularze i widoki Amelia shortcode/block/widgetem w natywnym builderze. Nie odtwarzaj formularza rezerwacji recznym kodem.

**Zrodla:** https://wpamelia.com/pricing/

### Bookly

**Opis:** Bookly to WordPress booking plugin z wersja free i platnymi planami. Oficjalny cennik wymienia unlimited appointments, customizable design, email/SMS notifications, local payments, booking list, staff/services, Google Calendar sync, online meetings, WooCommerce compatibility, Stripe, group booking, custom fields, recurring appointments, deposits, extras, packages i multi-location.

**Cena:** freemium. Free 0 USD. Pro ok. 49 USD/rok lub 129 USD one-time dla 1 site. Business ok. 149 USD/rok dla 1 site lub 349 USD one-time; wyzsze plany multi-site/agency.

**Kiedy wybrac:** wizyty uslugowe, wielu pracownikow, gabinety, salony, trenerzy, integracje kalendarzy, platnosci Stripe/WooCommerce i bardziej rozbudowane workflow niz prosty formularz.

**Kiedy nie:** gdy potrzebny tylko prosty kalendarz zajetosci; gdy klient nie chce zarzadzac grafikiem w WP; gdy proces rezerwacji jest zbyt nietypowy i wymaga dedykowanej aplikacji.

**Regula natywna:** uzywaj widokow Bookly i shortcode/widget/block w builderze. Nie tworz wlasnego formularza imitujacego Bookly bez integracji z jego baza rezerwacji.

**Zrodla:** https://www.booking-wp-plugin.com/pricing/

### WP Simple Booking Calendar

**Opis:** Lekki availability calendar dla WordPress. Oficjalna strona wymienia iCalendar import/export, bulk date editor, multiple calendar overview, custom legend, search widget, tooltips, user management i CSV export.

**Cena:** freemium. Premium: Personal 49 USD/rok (1 site), Business 79 USD/rok (5 sites), Developer 159 USD/rok (unlimited); automatyczne odnowienie roczne.

**Kiedy wybrac:** domki, apartamenty, wypozyczalnia sprzetu, pola kempingowe, dostepnosc zasobow, gdzie uzytkownik ma widziec wolne/zajete terminy.

**Kiedy nie:** appointment booking z pracownikami, platnosciami, uslugami i powiadomieniami; restauracyjne rezerwacje stolikow; zlozone hotele z channel managerem.

**Regula natywna:** osadzaj kalendarz blokiem/shortcodem w natywnym builderze. Nie rysuj kalendarza jako statycznej tabeli bez integracji.

**Zrodla:** https://www.wpsimplebookingcalendar.com/ , https://www.wpsimplebookingcalendar.com/pricing/ , https://wordpress.org/plugins/wp-simple-booking-calendar/

### LifterLMS

**Opis:** LifterLMS to LMS do kursow, czlonkostw, szkolen i sprzedazy edukacji. Core plugin jest darmowy; bundly dodaja ecommerce, CRM/email/forms, advanced quizzes, assignments, videos, certificates, cohorts, groups, social learning i inne addony.

**Cena:** freemium. Core free. Earth Bundle 199 USD/rok, Universe 299 USD/rok, Infinity 799 USD/rok wedlug oficjalnego cennika promocyjnego; odnowienia w pelnej cenie.

**Kiedy wybrac:** kursy i membershipy, gdy klient chce zaczac darmowym core, a potem dodawac platnosci i addony; projekty szkoleniowe, trenerzy, nonprofit, community.

**Kiedy nie:** pojedynczy plik PDF/video bez struktury kursu; klient chce gotowa platforme SaaS poza WP; potrzebna jest mocna analityka enterprise od startu.

**Regula natywna:** korzystaj z blokow/shortcode/widokow LifterLMS oraz motywu/stacku do layoutu. Nie buduj pseudo-kursu z recznych kart, jesli potrzebne sa lekcje, postep i platnosci.

**Zrodla:** https://lifterlms.com/pricing/

### Tutor LMS

**Opis:** Tutor LMS to LMS z course builderem, assignments, content drip, lessons/quizzes, live classes, AI Studio, bundles, native eCommerce, subscriptions, certificates, analytics i integracjami m.in. Zoom, Google Classroom/Meet, BuddyPress, PMPro, WooCommerce.

**Cena:** freemium. Core free. Annual: Individual ok. 139.30 USD/rok promocyjnie (regular 199), Business ok. 279.30 USD/rok (regular 399), Agency ok. 559.30 USD/rok (regular 799). Lifetime: Individual 499 USD, Business 999 USD, Agency 1999 USD.

**Kiedy wybrac:** creatorzy kursow, marketplace instruktorow, membership/community, subskrypcje kursow, potrzeba ladnego UX kursu i wielu funkcji w jednym LMS.

**Kiedy nie:** bardzo prosty landing z formularzem na szkolenie offline; enterprise LMS z wymaganiami SCORM/xAPI bez potwierdzenia; projekt bez budzetu na konfiguracje kursow.

**Regula natywna:** uzywaj widokow, blokow, shortcode i szablonow Tutor LMS. Layout otaczaj builderem, ale nie odtwarzaj recznie logiki kursow.

**Zrodla:** https://tutorlms.com/ , https://tutorlms.com/pricing/

### LearnDash

**Opis:** LearnDash to WordPress LMS z drag-and-drop course builderem, quiz builderem, memberships/subscriptions/bundles, Stripe/PayPal, video progression, focus mode, certificate builder, achievements, reporting/analytics w wyzszych planach i skalowaniem do team/enterprise.

**Cena:** platny. Essentials 259 USD/rok, Pro 399 USD/rok, Elite 599 USD/rok; bez oplat per student, unlimited courses/learners.

**Kiedy wybrac:** powazniejsze kursy i szkolenia B2B, certyfikacja, grupy/cohorty, raportowanie, sprzedaz do firm, wieksza baza uczniow.

**Kiedy nie:** gdy wystarczy prosty download lub webinar; gdy klient chce minimalny koszt i prosty darmowy start; gdy nie ma contentu kursowego i chce tylko formularz leadowy.

**Regula natywna:** uzywaj blokow/widokow LearnDash, MemberDash i oficjalnych integracji. Nie buduj recznych kart lekcji jako zamiennika LMS.

**Zrodla:** https://www.learndash.com/learndash-lms/ , https://www.liquidweb.com/software/learndash/

### Estatik

**Opis:** Estatik to plugin/theme real estate dla agentow, agencji i portali. Oficjalna strona wymienia frontend property submissions, subscriptions/payments, leads management, data manager, fields builder, buyer/agent accounts, interactive map search, compare properties, agents/agencies, mortgage calculator, MLS import przez RETS/RESO Web API w odpowiednim pakiecie i Elementor compatibility.

**Cena:** freemium. Estatik Simple jest darmowy. Estatik PRO: 89 USD. Estatik Premium z MLS import via RETS/RESO Web API: 649 USD. Strona podaje unlimited domains/sites oraz rozne okresy wsparcia zalezne od wersji.

**Kiedy wybrac:** agencja nieruchomosci potrzebuje listingow, map, agentow, leadow, pol nieruchomosci, wyszukiwarki i ewentualnie MLS import.

**Kiedy nie:** firma budowlana pokazujaca tylko kilka realizacji; prosta strona developera z kilkoma kartami inwestycji; projekt bez procesu dodawania/aktualizacji ofert.

**Regula natywna:** listingi, mapy, search i formularze kontaktowe nieruchomosci rob przez widoki/widgety Estatik i buildera. Nie tworz statycznego katalogu, jesli klient potrzebuje zarzadzac ofertami.

**Zrodla:** https://estatik.net/ , https://estatik.net/choose-your-version/

### Houzez

**Opis:** Houzez to specjalistyczny motyw real estate. Oficjalna strona wymienia page builder tools, Houzez Studio, Theme Builder, Header/Footer/Mega Menu/Search/Form/Grid Builder, Elementor Widgets, property detail pages, agents/agencies profiles, half map, listing templates, compare properties, CRM, lead forms, flexible maps, advanced search, custom fields, one-click demo import, multi-language i multi-currency.

**Cena:** platny. Oficjalny cennik: Single Site 79 USD one-time, Developer 349 USD one-time (5 websites), Agency 599 USD one-time (10 websites), z 1 rokiem updates/support.

**Kiedy wybrac:** klient jest typowa agencja nieruchomosci i chce gotowy portal/listingi/CRM zamiast skladania funkcji z wielu pluginow.

**Kiedy nie:** gdy potrzebujesz lekkiego, neutralnego motywu; gdy klient ma prosty katalog kilku lokali; gdy lock-in do motywu branżowego jest ryzykowny.

**Regula natywna:** uzywaj builderow i widgetow Houzez/Elementor dostarczanych z motywem. Nie odtwarzaj wyszukiwarki i kart ofert recznie.

**Zrodla:** https://houzez.co/ , https://houzez.co/pricing/ , https://themeforest.net/item/houzez-real-estate-wordpress-theme/15752549

### Five Star Restaurant Menu / Food Ordering

**Opis:** Plugin do menu restauracyjnego i zamowien. Wersja free daje responsywne menu, Gutenberg blocks/shortcodes, wiele menu i pozycji, zdjecia/ceny, schema, QR code. Premium dodaje layouty, custom fields, sort/filter/search, dietary icons, badges i style. Ultimate dodaje ordering, cart, PayPal/Stripe, SMS i appke managera.

**Cena:** freemium. Premium: Single Site 67 EUR, 5 sites 147 EUR, 10 sites 247 EUR w aktualnej promocji widocznej na stronie. Ultimate: Single Site 297 EUR w promocji; strona pokazuje tez ceny regularne.

**Kiedy wybrac:** restauracja, kawiarnia, bar, catering, menu online, menu QR, alergeny/diety, zamowienia z odbiorem/dostawa bez prowizji platform.

**Kiedy nie:** prosta wizytowka z kilkoma daniami; klient ma juz zewnetrzny system zamowien i nie chce zarzadzac zamowieniami w WP; potrzebny pelny POS/inventory.

**Regula natywna:** menu i ordering osadzaj blokiem/shortcodem pluginu w builderze. Nie skladaj menu jako statycznej tabeli, jesli klient ma aktualizowac pozycje, ceny i QR.

**Zrodla:** https://wordpress.org/plugins/food-and-drink-menu/ , https://www.fivestarplugins.com/plugins/five-star-restaurant-menu/

### Cost Calculator Builder

**Opis:** Plugin do kalkulatorow wyceny i estimation forms. Oficjalna strona wymienia unlimited forms, formula total, page builder compatibility, instant estimation, currency options, Pro templates, conditional system, reCaptcha, WooCommerce checkout, Stripe, PayPal, Razorpay, Contact Form 7, order form, file upload, PDF entries, discounts, validated form, sticky calculator, multi-step page breaker i AI formula generation.

**Cena:** freemium. Free na wordpress.org; Pro od 59 USD wedlug oficjalnej strony.

**Kiedy wybrac:** uslugi z kalkulacja ceny: remonty, sprzatanie, transport, ogrodnictwo, eventy, instalacje, konfiguratory pakietow, leady z wycena.

**Kiedy nie:** prosty formularz kontaktowy; cennik staly bez zmiennych; kalkulacje prawne/finansowe wymagajace walidacji eksperckiej.

**Regula natywna:** buduj kalkulator w UI pluginu i osadzaj natywnie. Nie koduj recznie kalkulatora w sekcji.

**Zrodla:** https://stylemixthemes.com/cost-calculator-plugin/ , https://wordpress.org/plugins/cost-calculator-builder/

### WPForms / Formidable Forms jako formularze zaawansowane

**Opis:** WPForms nadaje sie do rozbudowanych formularzy leadowych, marketing automation, user registration, partial submissions i prostych platnosci. Formidable Forms jest mocniejsze dla formularzy-aplikacji, dynamic views, PDF, payments, conditional logic, repeaters, charts i kalkulacyjnych workflow.

**Cena:** WPForms freemium; Basic ok. 49.50 USD/rok promocyjnie, Plus 99.50 USD/rok promocyjnie. Formidable freemium; Basic 39.50 USD/rok promocyjnie, Plus 99.50 USD/rok promocyjnie; odnowienia wg pelnej ceny z cennika.

**Kiedy wybrac:** quote request, brief projektowy, lead routing, formularze wieloetapowe, uploady, rejestracje, PDF/invoice/proposal, gdy CF7 jest za prosty.

**Kiedy nie:** zwykly kontakt, gdzie CF7 wystarcza; pelny booking/sklep/LMS, gdzie formularz nie powinien udawac systemu biznesowego.

**Regula natywna:** formularze rob w UI pluginu i osadzaj blokiem/shortcodem/widgetem. Nie buduj formularzy recznym markupiem bez walidacji i zapisu.

**Zrodla:** https://wpforms.com/pricing/ , https://formidableforms.com/pricing/

## 5. Domyslny stack, gdy nie ma silnego sygnalu

Gdy research i brief nie wskazuja sklepu, rezerwacji, LMS, nieruchomosci, kalkulatora, menu restauracyjnego ani jasnego wymagania performance/FSE, domyslnym stackiem pozostaje:

| Element | Rola |
|---|---|
| Hello Elementor | lekka baza pod Elementor |
| Elementor | natywne widgety i edytor wizualny |
| Header Footer Elementor (HFE) | header/footer bez hackowania stron |
| Happy Addons / Essential Addons | dodatkowe widgety tylko tam, gdzie daja realny efekt |
| Contact Form 7 | standardowy formularz kontaktowy |
| Yoast SEO | tytuly/meta/schema/sitemap |
| Smush albo EWWW | optymalizacja obrazow |

Ten default jest nadal najbezpieczniejszy dla szybkich lokalnych stron, bo repo ma gotowa recepture `gmb-workflow/NATIVE-ELEMENTOR-BUILD.md` i assety `gmb-workflow/assets/native/`. Zmiana stacku musi wynikac z sygnalu biznesowego lub technicznego, nie z checi eksperymentowania.

## 6. Checklist Claude przed buildem

1. Czy klient sprzedaje produkty lub pobiera platnosci online? Jesli tak, rozważ WooCommerce i dodatki.
2. Czy klient umawia wizyty, pracownikow, zasoby albo terminy? Jesli tak, wybierz Amelia/Bookly/WP Simple Booking Calendar wedlug zlozonosci.
3. Czy klient sprzedaje kursy, szkolenia, certyfikaty lub membership? Jesli tak, wybierz LMS.
4. Czy branza ma specjalny model danych: nieruchomosci, restauracja/menu, kalkulator wyceny, katalog zasobow? Jesli tak, wybierz dedykowany plugin/motyw zamiast recznego udawania funkcji.
5. Czy najwazniejsze sa performance, dluga utrzymywalnosc i czysty output? Jesli tak, rozważ Bricks albo GeneratePress + GenerateBlocks.
6. Czy klient ma istniejacy stack/licencje/zespol w Elementorze, Divi, Bricks albo innym builderze? Nie migruj bez realnego powodu.
7. Czy klient bedzie sam edytowal strone? Wybierz narzedzie, ktore potrafi obsluzyc po wdrozeniu.
8. Czy prosty Site Editor i block theme wystarcza? Jesli tak, nie dokladaj ciezkiego buildera.
9. Czy wybrany plugin ma natywne bloki/widgety/shortcode do osadzenia? Jesli nie, nie wybieraj go jako glownego systemu dla tej funkcji.
10. Czy decyzja i uzasadnienie sa zapisane w `design-spec.json` / handoffie? Zawsze podaj stos, powody i alternatywe odrzucona.

## 7. Notatki researchowe

- Ceny sa snapshotem z oficjalnych stron pobranych w tej sesji i moga sie zmienic; przed zakupem licencji sprawdz checkout producenta.
- Jezeli klient wymaga konkretnej funkcji regulowanej prawnie lub finansowo (platnosci, subskrypcje, medycyna, szkolenia certyfikowane), potwierdz wymagania biznesowe i prawne poza tym przewodnikiem.
