// postprocess-fixes.js — deterministyczne fixy _elementor_data (uruchamia CLAUDE, nie Codex).
// Battle-tested w przebiegu Kärcher 2026-06-17. Patrz HARDENED-RECIPES.md §6–§12.
// Użycie: ustaw CONFIG.dir + CONFIG.files, `node postprocess-fixes.js`, potem apply-pages.php.
const fs = require('fs');

const CONFIG = {
  dir: process.env.KW_DIR || './elementor/',          // folder z <slug>.json
  files: (process.env.KW_FILES || 'start,cennik,sprzet,jak-to-dziala,galeria,kontakt').split(','),
  primary: '#FFD800',   // żółć marki (banner)
  dark: '#1F1F1F',      // antracyt
  ctaText: 'Zadzwoń teraz',
  // mapowanie tytuł icon-box -> ikona FONT AWESOME 5 (Elementor 4.x ma FA5, NIE 6)
  icon(t){
    t = (t||'').toLowerCase();
    const m = [
      [/ocena|gwiazd|opini|\d\.\d/, 'fas fa-star'],
      [/sprzęt|sprzet|urządz|urzadz|maszyn|narzędz/, 'fas fa-tools'],
      [/chemia|proszek|środ|srod|płyn/, 'fas fa-flask'],
      [/instrukcj|dokument|pdf/, 'fas fa-file-alt'],
      [/adres|dojazd|mapa|lokaliz|odbiór w|odbior w|miasto/, 'fas fa-map-marker-alt'],
      [/rezerw|zadzwoń|zadzwon|telefon|kontakt|umów/, 'fas fa-phone'],
      [/odbierz|odbiór|odbior|zestaw|dostaw|paczk/, 'fas fa-box'],
      [/zwrot|zwracasz|oddaj/, 'fas fa-undo'],
      [/pierz|pranie|ekstrakc|woda|wod|plam|czyszcz/, 'fas fa-tint'],
      [/czas|godzin|zegar|szybk|od razu|termin/, 'fas fa-clock'],
      [/uwag|ostroż|ostroz|warning|delikat/, 'fas fa-exclamation-triangle'],
      [/większ|wieksz|wydajn|moc|dashboard|równ|rown/, 'fas fa-tachometer-alt'],
      [/dywan|wykładz|wykladz|podłog/, 'fas fa-layer-group'],
      [/kanap|narożnik|naroznik|fotel|mebl|sofa|tapicerk|krzes/, 'fas fa-couch'],
      [/materac|łóż|sypial/, 'fas fa-bed'],
      [/auto|samochod|pojazd/, 'fas fa-car'],
      [/cena|cennik|płatn|koszt|zł|pln/, 'fas fa-tag'],
      [/gwaran|jakość|pewn|zaufan/, 'fas fa-shield-alt'],
    ];
    for (const [re, ic] of m){ if (re.test(t)) return ic; }
    return 'fas fa-check-circle';
  },
};

const safe = a => Array.isArray(a) ? a : [];
let stats = { icons:0, sections:0, banners:0, maps_removed:0, html_removed:0, cta_cleaned:0, grids:0 };
let gridCounter = 0;

for (const f of CONFIG.files){
  const path = CONFIG.dir + f.trim() + '.json';
  let data = JSON.parse(fs.readFileSync(path, 'utf8').replace(/^﻿/, ''));

  // 0) usuń widgety HTML ze <style> (przenoszone do globalnego Additional CSS)
  const stripStyle = els => safe(els).filter(e => {
    const h = e.settings && e.settings.html;
    if (e.widgetType === 'html' && typeof h === 'string' && /<style|kw-animated/i.test(h)){ stats.html_removed++; return false; }
    if (e.elements) e.elements = stripStyle(e.elements);
    return true;
  });
  data = stripStyle(data);

  // 1) ikony FA5 + usuń duplikat natywnej mapy (zostaje shortcode)
  (function walk(els){ for (const e of safe(els)){
    if (e.widgetType === 'icon-box'){ e.settings.selected_icon = { value: CONFIG.icon(e.settings && e.settings.title_text), library:'fa-solid' }; delete e.settings.icon; stats.icons++; }
    if (e.elements) walk(e.elements);
  }})(data);
  const stripMaps = els => safe(els).filter(e => {
    if (e.widgetType === 'google_maps'){ stats.maps_removed++; return false; }
    if (e.elements) e.elements = stripMaps(e.elements);
    return true;
  });
  data = stripMaps(data);

  // 2) CTA bez numerów
  (function cta(els){ for (const e of safe(els)){
    if (e.widgetType === 'button' && typeof (e.settings||{}).text === 'string' && /zadzwo/i.test(e.settings.text) && /\d/.test(e.settings.text)){ e.settings.text = CONFIG.ctaText; stats.cta_cleaned++; }
    if (e.elements) cta(e.elements);
  }})(data);

  // 3) siatki kart -> _element_id (klasa na kontenerze się NIE renderuje!)
  (function grids(els){ for (const e of safe(els)){
    const kids = safe(e.elements); const ib = kids.filter(k => k.widgetType === 'icon-box').length;
    if (kids.length >= 2 && ib >= 2 && ib >= kids.length - 1){
      e.settings = e.settings || {};
      if (!String(e.settings._element_id||'').startsWith('kwg')){ e.settings._element_id = 'kwg'+kids.length+'-'+(++gridCounter); stats.grids++; }
      kids.forEach(k => { if (k.settings){ delete k.settings.width; delete k.settings._element_width; } });
    }
    grids(kids);
  }})(data);

  // 4) hero full-bleed + paddingi (+responsywne) + przyciski bannerów ciemne
  data.forEach((sec, idx) => {
    if (sec.elType !== 'container' && sec.elType !== 'section') return;
    sec.settings = sec.settings || {};
    const heroFirst = (f.trim() === CONFIG.files[0].trim() && idx === 0);
    const banner = (idx === 0 && !heroFirst) || sec.settings.background_color === CONFIG.primary;
    let top='80', bottom='80', mTop='48', mBot='48', tTop='64', tBot='64';
    if (heroFirst){ top='130'; bottom='118'; mTop='72'; mBot='58'; tTop='100'; tBot='90';
      delete sec.settings.width; sec.settings.content_width='boxed'; sec.settings.background_size='cover'; sec.settings.flex_justify_content='center';
      sec.settings.min_height = { unit:'px', size:640, sizes:[] };
    } else if (idx === 0){ top='104'; bottom='84'; mTop='62'; mBot='46'; tTop='84'; tBot='66'; }
    const cur = sec.settings.padding || {};
    sec.settings.padding        = { unit:'px', top, right:cur.right||'24', bottom, left:cur.left||'24', isLinked:false };
    sec.settings.padding_tablet = { unit:'px', top:tTop, right:cur.right||'24', bottom:tBot, left:cur.left||'24', isLinked:false };
    sec.settings.padding_mobile = { unit:'px', top:mTop, right:'18', bottom:mBot, left:'18', isLinked:false };
    if (sec.settings.background_color === CONFIG.primary){
      (function btns(els){ for (const e of safe(els)){
        if (e.widgetType === 'button'){
          e.settings.background_color = CONFIG.dark; e.settings.button_text_color = '#FFFFFF';
          e.settings.button_background_hover_color = '#FFFFFF'; e.settings.button_hover_color = CONFIG.dark;
          const cc = String(e.settings._css_classes||''); if (!cc.includes('kw-btn-dark')) e.settings._css_classes = (cc+' kw-btn-dark').trim();
          stats.banners++;
        }
        if (e.elements) btns(e.elements);
      }})(sec.elements);
    }
    stats.sections++;
  });

  fs.writeFileSync(path, JSON.stringify(data), 'utf8');
}
console.log(JSON.stringify(stats));
