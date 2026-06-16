<?php
/**
 * apply-pages.php — wzorzec aplikatora (uruchamia CLAUDE: `wp eval-file apply-pages.php`).
 * Czyta elementor/<slug>.json (po postprocess-fixes.js), podstawia placeholdery z media.json
 * + shortcody, zapisuje _elementor_data jako STRING z wp_slash. Patrz HARDENED-RECIPES.md §5.
 *
 * Wymaga obok: media.json {slug:{id,url}}, oraz ustaw $BASE, $PAGES, $CF7, $MAP poniżej.
 */
$BASE  = 'C:\\...\\<projekt>';                 // folder roboczy z elementor/ i media.json
$PAGES = [                                      // post_id => plik
  // 9 => 'start.json', 10 => 'cennik.json', ...
];
$CF7 = '[contact-form-7 id="HASH" title="Rezerwacja terminu"]';
$MAP = '<iframe src="https://www.google.com/maps?q=ADRES&output=embed" style="border:0;width:100%;height:430px"></iframe>';

$mediaRaw = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents($BASE.'\\media.json')); // strip BOM (PowerShell 5.1!)
$media = json_decode($mediaRaw, true);
if (!is_array($media)) { echo json_encode(['fatal'=>'media.json: '.json_last_error_msg()]); return; }
$jstr = fn($s) => trim(json_encode($s, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), '"');

$result = [];
foreach ($PAGES as $id => $file) {
  $txt = file_get_contents($BASE.'\\elementor\\'.$file);
  foreach ($media as $slug => $info) {
    $txt = str_replace('"__IMG_'.$slug.'__"', (string)intval($info['id']), $txt); // ID liczbowe (bez cudzysłowów)
    $txt = str_replace('__IMGURL_'.$slug.'__', $jstr($info['url']), $txt);          // URL https!
  }
  $txt = str_replace('__CF7_SHORTCODE__', $jstr($CF7), $txt);
  $txt = str_replace('__MAP_SHORTCODE__', $jstr($MAP), $txt);

  if (json_decode($txt) === null) { $result[$id] = ['ok'=>false,'err'=>json_last_error_msg()]; continue; }
  $left = preg_match_all('/__[A-Z0-9_][A-Za-z0-9_-]*__/', $txt, $m) ? array_values(array_unique($m[0])) : [];

  update_post_meta($id, '_elementor_data', wp_slash($txt));   // Elementor trzyma STRING, nie tablicę
  update_post_meta($id, '_elementor_edit_mode', 'builder');
  update_post_meta($id, '_elementor_template_type', 'wp-page');
  update_post_meta($id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '4.1.3');
  update_post_meta($id, '_wp_page_template', 'elementor_header_footer');
  $result[$id] = ['ok'=>true, 'placeholdery_left'=>$left];
}
if (class_exists('\\Elementor\\Plugin')) { \Elementor\Plugin::$instance->files_manager->clear_cache(); }
echo json_encode(['pages'=>$result], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
