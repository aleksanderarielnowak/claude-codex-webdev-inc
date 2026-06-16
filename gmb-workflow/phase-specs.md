# Gotowe specy per faza (Task Spec → Codex)

> Szablony do odpalenia. Claude wypełnia `{{...}}` per projekt i wysyła do Codexa. Każdy spec NAZYWA narzędzie i ZABRANIA hacków (anty-improwizacja). Każdy wymaga structured output `ready/issues`.

> ⚠️ **KOREKTY v2 (przebieg Kärcher 2026-06-17 — patrz `HARDENED-RECIPES.md`):**
> 1. **Fetch (Faza 1):** ZAWSZE `-c sandbox_workspace_write.network_access=true` na CLI + twarda tożsamość + zakaz reużycia cudzych folderów + nowy slug (bez tego sieć jest cicho blokowana → Codex podpina dane innego klienta i raportuje `ready:true`).
> 2. **Środowisko (Faza 2):** WP-CLI odpala **CLAUDE** (bootstrap §2), NIE Codex przez Site Shell — sandbox `codex exec` nie wykonuje php/wp. Od razu HTTPS (§3) + wyłącz eksperyment ikon/WP_DEBUG (§4).
> 3. **Strony (Faza 4B):** podział **author/apply** — Codex autoruje `elementor/<slug>.json` z placeholderami, Claude aplikuje (`assets/postprocess-fixes.js` → `assets/apply-pages.php`). `--output-schema` jest STRICT (luźny → 400); przy luźnym kształcie zdejmij flagę i wymuś JSON w prompcie.
> 4. **Zawsze:** Aktualności (zaprojektowane, nie archiwum) + 3 wpisy; CTA bez numerów; mobile dopracowany; mapa = Google embed iframe (bez klucza).

---

## FAZA 1 — RESEARCH (Codex fetch)
```
ROLA: ręce, fetch+recon. Wejście GMB: {{GMB_SCREEN_LUB_LINK}}.
ZADANIA: 1) Intake (marka, branża, NAP, atrybuty, kolory z logo, ton). 2) Potwierdź BRAK strony WWW. 3) Opinie: rating+liczba, destylat atuty/słabości/tematy (surowe na dysk). 4) Social, katalogi (pyszne/glovo/booksy/tripadvisor). 5) Konkurencja 3-5 (nazwa/ocena/strona?). 6) Keywords 15-20 (brand/lokalne/ofertowe/informacyjne). 7) TYP biznesu → dobierz playbook z business-type-playbooks.md (rekomendowane typy podstron + akcenty).
OUTPUT na dysk: ./{{SLUG}}/brief.json, research.json, raw/. UTF-8, null gdy nieznane (nie zgaduj).
ZWRÓĆ JSON: {ready, files, summary:{marka,branza,ma_strone_www,nap,rating,opinie,social,atuty,slabosci,konkurencja,keywords,business_type,rekomendowane_podstrony}, issues}
```

## FAZA 2 — ŚRODOWISKO (Codex, realny site Local)
> WYMÓG: site to PRAWDZIWY site Local (nginx+php-fpm+MySQL, wpis w sites.json). User utworzył/sklonował go ręcznie (patrz START-HERE.md). NIE używać `php -S`. NIE podrzucać niezarejestrowanego folderu.
```
ROLA: ręce. Site Local już istnieje: {{SITE_NAME}} ({{SITE_URL}}.local), Site Shell ma `wp`.
ZADANIA: 1) Potwierdź `wp core is-installed` i HTTP 200 przez nginx. 2) Stos (idempotentnie, jeśli nie z blueprintu): wp theme install hello-elementor --activate; wp plugin install elementor header-footer-elementor happy-elementor-addons essential-addons-for-elementor contact-form-7 wordpress-seo wp-smushit wp-google-maps --activate. 3) wp rewrite structure '/%postname%/'. 4) Global Kit (kolory {{PALETA}}, fonty Playfair+Inter). 5) 6 pustych stron {{SLUGI}}, Start=front.
ZWRÓĆ JSON: {ready, site_http, aktywne:{theme,wtyczki+wersje}, kit_ok, strony, issues}
```

## FAZA 4A — FUNDAMENT (header/footer przez HFE)
> WYMÓG: header/footer = szablony Elementora przez HFE (location header/footer). NIE wstrzykiwać do `_elementor_data` stron. NIE ukrywać legacy CSS hackiem.
```
ROLA: ręce. Zbuduj globalny header (HFE location=header): logo "{{BRAND}}" (Playfair), menu {{MENU_POZYCJE}}, CTA "{{CTA}}" (terakota {{C_PRIMARY}}) → /kontakt/, sticky, mobile hamburger. Footer (HFE location=footer): 3 kolumny {{NAP/SOCIAL/LINKI}}, © {{ROK}} {{BRAND}}, bez creditu WP.
SELF-VERIFY: headless screenshot home+1 podstrona; menu widoczne, nic nie wycieka.
ZWRÓĆ JSON: {ready, header_ok, footer_ok, screeny, issues}
```

## FAZA 4B — STRONY (z biblioteki sekcji, równolegle)
> WYMÓG: użyj szablonów z section-library.md (placeholdery), kompozycja z compositions/{{BRANZA}}.json. Sekcje full-width, animacje opacity:1+reduced-motion, ikony realne, kontrast. Zdjęcia z media.json.
```
ROLA: ręce. Dla każdej z 6 stron: wczytaj kompozycję → dla sekcji wczytaj szablon → wstrzyknij placeholdery (copy {{per-sekcja}}, ID zdjęć z media.json, kolory z Kit) → zapisz _elementor_data. Strony: {{LISTA}}. Kontakt: CF7 shortcode {{CF7}}. Mapa: widget WP Go Maps {{COORDS}}.
COPY: brandowane PL, nie lorem; ceny realne-placeholder.
RÓWNOLEGLE: per strona osobny scope.
ZWRÓĆ JSON: {ready, strony:[{url,http,full_width,hero_img,animacje,zdjecia_ok}], cf7_on_kontakt, issues}
```

## FAZA 4C — SEO (Yoast) + finisz
```
ROLA: ręce. Yoast per strona: focus keyword z research, SEO title + meta description (unikalne), OG image (PNG hero, nie SVG), schema (LocalBusiness/Restaurant/Service wg branży + FAQ gdzie jest), sitemap on. Smush: optymalizuj bibliotekę. Sprawdź sitemap_index.xml=200.
ZWRÓĆ JSON: {ready, per_strona:[{url,title,meta,focus_kw,schema}], sitemap_ok, obrazy_zoptymalizowane:n, issues}
```

## QA (po każdym etapie — patrz qa-checklist.md)
```
ROLA: ręce. Odpal headless QA wg qa-checklist.md na {{STRONY}}: asercje A1-A3 (architektura/biała karta/obrazy), V1-V8 (header/full-width/obrazy/opacity/hero/kontrast/CF7/ikony), S1-S3 (SEO). + kontaktówka 6 miniatur. + przebieg prefers-reduced-motion.
ZWRÓĆ JSON: {ready, pages:[{url,checks{},fails[]}], contact_sheet, issues}  (ready=true tylko gdy wszystko pass)
```
