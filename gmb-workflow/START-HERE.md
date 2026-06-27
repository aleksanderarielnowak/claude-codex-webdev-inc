# START HERE — jak odpalić przebieg GMB/dowolne źródło → strona

> Jeden krok ręczny po Twojej stronie (Local nie ma CLI do tworzenia site'ów), reszta automatyczna.

## ✅ Twój 1 krok: site w Local (~1-2 min)
**Opcja A (najszybsza, gdy mamy szablon):** w Local kliknij prawym na site-szablonie z gotowym stosem → **Clone** → nazwij np. `klient-xyz`. Stos (Hello Elementor + wtyczki + Kit) już jest. ~30 s.

**Opcja B (pierwszy raz / bez szablonu):**
1. Local → **+ Create a new site** → nazwa (np. `klient-xyz`).
2. Environment: **Preferred** (nginx + PHP 8.2+ + MySQL). WordPress: najnowszy.
3. Ustaw login admina (zapamiętaj) → **Start site**.
4. (Raz) z tego zrób szablon do clone'a na przyszłość.

Po tym podaj mi: **nazwę site'a + URL `.local` + login admina**. Resztę robi Codex przez **Site Shell** (Local → site → „Open site shell" / „Site shell").

## ▶️ Co robię ja (mózg) + Codex (ręce)
1. **Research autonomiczny** (Codex fetch) — `phase-specs.md` Faza 1 → `brief.json`, `research.json`, `needs_user` dla krytycznych braków + dobór playbooku branżowego.
2. **Środowisko** — Codex sprawdza/dopina stos przez Site Shell (Faza 2). Jeśli clone z szablonu → tylko weryfikacja.
3. **Zdjęcia AI** — Codex startuje generację W TLE od razu (batch), reszta leci równolegle.
4. **Design** — ja planuję strukturę pod TYP biznesu (playbook) + branding.
5. **Build** — Codex wypełnia sekcje z `section-library.md` (równolegle per strona).
6. **SEO** — Yoast (Faza 4C).
7. **QA** — Codex headless wg `qa-checklist.md`; ja oglądam kontaktówkę + finalny przegląd estetyczny.
8. **Handoff** — dane logowania + lista podstron; site widoczny i edytowalny w Local.

## ⚠️ Zasady, które trzymają jakość
- Fetch zawsze Codex · weryfikacja po KAŻDYM etapie · `ready=true` ≠ poprawność wizualna · spec nazywa narzędzie i zabrania hacków · NIGDY `php -S` jako hosting · header/footer tylko przez HFE.

## 🔑 Wymagania
- Codex z tokenami + MCP wstały (`/codex-setup` jeśli rozłączony).
- Local uruchomiony, site wystartowany.
- INPUT: jedno lub więcej dowolnych źródeł:
  - URL istniejącej strony WWW (redesign/odświeżenie albo baza pod nową stronę).
  - Profil social media (Facebook / Instagram / LinkedIn / TikTok / inne).
  - Wizytówka Google (GMB) — screen lub link.
  - Wpis/numer KRS, NIP albo REGON.
  - Sama nazwa firmy + lokalizacja → Codex sam wyszukuje informacje w otwartej sieci.
