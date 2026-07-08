# Design craft — myśl nad wyglądem, UX i konwersją (nie tylko wypełniaj research)

Research daje FAKTY (marka, branża, oferta, konkurencja). To surowiec, nie projekt. **Zanim zbudujesz — pomyśl, jak to ma wyglądać i dlaczego tak.** Poświęć realny budżet myślenia na kierunek wizualny, sekcje szyte na miarę, przejścia i animacje ze smakiem, oraz UX prowadzący do konwersji. Buduj tematycznie — strona ma „czuć" branżę i markę, nie być wypełnionym szablonem.

## 1. Najpierw koncept, potem build (design-thinking gate)
Przed Fazą 4 zapisz krótki **design-concept** (nie tylko `research.json`):
- **Wielki pomysł / motyw:** jedno zdanie, czym ta strona ma się wyróżniać i jakie uczucie budzić (np. „spokój i precyzja kliniki premium", „rzemieślnicza solidność warsztatu"). Wszystko dalej ma to wzmacniać.
- **Kierunek wizualny:** paleta z uzasadnieniem (nie „bo w logo"), typografia z charakterem (para nagłówek/tekst), rytm sekcji, gęstość, „oddech" (whitespace).
- **Sekcje bespoke, nie z półki:** dla TEJ branży zaprojektuj 2–3 sekcje-sygnaturki (np. dla stomatologa: interaktywny „przed/po", oś leczenia krok-po-kroku; dla restauracji: menu z aptekarską typografią + zdjęcia dań full-bleed). Reszta może być z biblioteki.
- **Ruch:** gdzie i JAKI (patrz §3). Zaprojektuj świadomie, nie „dodaj animacje wszędzie".

Ten koncept zasila tor buildu (NATYWNY/HTML) — dopiero teraz budujesz.

## 2. UX i konwersja (wpleć w każdą stronę)
- **Hierarchia i above-the-fold:** w pierwszym ekranie widać: kto to, co oferuje, dlaczego warto, i JEDNO główne CTA. Nie każ scrollować, by zrozumieć.
- **Jedno dominujące CTA na sekcję**, powtarzane w naturalnych momentach decyzji; kontrastowe, konkretne („Umów wizytę", nie „Więcej").
- **Redukcja tarcia:** krótkie formularze, widoczny telefon/NAP, mapa, godziny; zaufanie blisko CTA (opinie, certyfikaty, realizacje).
- **Skanowalność:** F/Z-pattern, nagłówki mówiące korzyść, listy zamiast ścian tekstu, ikony wspierające (nie dekor bez sensu).
- **Ścieżka konwersji:** hero → dowód/oferta → zaufanie → CTA. Każda podstrona ma jasny „następny krok".
- **Dostępność = UX:** kontrast ≥4.5, focus states, sensowne alty, klikalne cele ≥44px.

## 3. Ruch/animacje ze smakiem (nie fajerwerki)
- **Cel, nie ozdoba:** animacja ma kierować uwagę (reveal treści przy scrollu), dawać feedback (hover CTA/kart) albo budować markę (subtelny akcent w hero). Jeśli nie służy — usuń.
- **Wstrzemięźliwie:** 1 spójny język ruchu na stronie (czasy ~150–400ms, jeden easing). Nie mieszaj 5 efektów.
- **Reveal on scroll:** delikatny fade+slide (opacity 0→1, translateY ~12–20px), stagger 60–90ms w grupach. Baza `opacity:1` (treść czytelna, gdy JS/scroll nie zadziała).
- **Przejścia sekcji:** raczej płynne zmiany tła/rytmu i „oddech" niż ciężkie parallaxy.
- **Twarde guardraile (z LEARNINGS):** ZAWSZE `prefers-reduced-motion` fallback; **unikaj ciężkiego `backdrop-filter`/dużych `blur`** (dławi render, timeout QA); nie animuj tego, co psuje layout/CLS.

## 4. Tematyczność i spójność
- Dobór zdjęć, ikon, krojów i mikro-copy pod branżę i ton marki — brief stylu obrazów spójny (jedna estetyka, nie zlepek).
- Sekcje-sygnaturki powtarzają motyw przewodni; kolor akcentu i kształty (zaokrąglenia, linie) konsekwentne.
- Unikaj „generic corporate template" — jeśli tę sekcję można wkleić na dowolną inną stronę bez zmian, przemyśl ją.

## 5. Balans z resztą procesu
- **Jakość ≠ wolniej bez sensu:** myślenie idzie w KONCEPT (tanie, Claude) — masową produkcję nadal robi Codex (author→apply). Więcej myślenia z przodu, nie więcej rund z Codexem.
- **Edytowalność zostaje regułą:** bespoke sekcje w torze NATYWNYM buduj z natywnych widgetów, żeby mid-SEOwiec je potem ruszył (patrz `../SKILL.md`).
- Współpracuje z `web-design-trends` (aktualne wzorce) — trend jest INSPIRACJĄ do konceptu, nie gotowcem do skopiowania.
