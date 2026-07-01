<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/native-lib.php';

/*
 * Przykład buildera natywnej strony Elementora.
 * Uruchom przez: wp eval-file build-<slug>-native.php
 * W projekcie podmień: $page_id, treści, linki, media id+url i klasy hero.
 */

$page_id = 123;

$media = [
    'hero' => ['id' => 101, 'url' => 'https://example.local/wp-content/uploads/hero.jpg'],
    'card_1' => ['id' => 102, 'url' => 'https://example.local/wp-content/uploads/card-1.jpg'],
    'card_2' => ['id' => 103, 'url' => 'https://example.local/wp-content/uploads/card-2.jpg'],
    'card_3' => ['id' => 104, 'url' => 'https://example.local/wp-content/uploads/card-3.jpg'],
    'gallery_1' => ['id' => 105, 'url' => 'https://example.local/wp-content/uploads/gallery-1.jpg'],
    'gallery_2' => ['id' => 106, 'url' => 'https://example.local/wp-content/uploads/gallery-2.jpg'],
    'gallery_3' => ['id' => 107, 'url' => 'https://example.local/wp-content/uploads/gallery-3.jpg'],
];

$dark_text = ['text_color' => '#4b5555'];
$white_text = ['text_color' => '#ffffff'];

$card_settings = [
    'background_background' => 'classic',
    'background_color' => '#ffffff',
    'border_border' => 'solid',
    'border_width' => fm_native_dims(1),
    'border_color' => '#e5e8e8',
    'border_radius' => fm_native_dims(8),
    '_padding' => fm_native_dims(28),
    'padding' => fm_native_dims(28),
    'space_between_widgets' => ['unit' => 'px', 'size' => 18],
];

$data = [];

// Hero: tło ustaw klasą w native.css, np. .fm-hero--home.
$data[] = fm_native_section([
    fm_native_column([
        fm_native_heading('LOKALNA FIRMA USŁUGOWA', 'h6', fm_native_class_settings('fm-eyebrow')),
        fm_native_heading('Krótki, konkretny nagłówek oferty', 'h1', fm_native_class_settings('fm-title', [
            'title_color' => '#ffffff',
        ])),
        fm_native_text('Lead wyjaśniający wartość oferty, obszar działania i najważniejszy powód kontaktu.', fm_native_class_settings('fm-sub', $white_text)),
        fm_native_inner_section([
            fm_native_column([
                fm_native_button('Poproś o wycenę', '/kontakt/', fm_native_class_settings('fm-btn')),
            ], 50),
            fm_native_column([
                fm_native_button('Zobacz ofertę', '/oferta/', fm_native_class_settings('fm-btn fm-btn--light', [
                    'button_background_color' => '#ffffff',
                    'button_text_color' => '#242d2d',
                    'button_background_hover_color' => '#f0f2f2',
                    'hover_color' => '#242d2d',
                ])),
            ], 50),
        ], fm_native_class_settings('fm-actions')),
    ], 100, fm_native_class_settings('fm-hero__content')),
], fm_native_class_settings('fm-sec fm-sec--dark fm-hero fm-hero--home', [
    'padding' => fm_native_dims(128, 24, 128, 24),
]));

$data[] = fm_native_header_section('OFERTA', 'Trzy najmocniejsze powody wyboru', 'Sekcja kart oparta o natywne widgety image, heading i text-editor.');

$cards = [
    ['card_1', 'Usługa pierwsza', 'Krótki opis korzyści, zakresu i sytuacji, w której ta usługa jest najlepszym wyborem.'],
    ['card_2', 'Usługa druga', 'Opis drugiego filaru oferty. Zwięźle, konkretnie, bez lania wody.'],
    ['card_3', 'Usługa trzecia', 'Opis trzeciego filaru oferty z akcentem na dowód jakości albo wygodę klienta.'],
];
$columns = [];
foreach ($cards as $card) {
    $columns[] = fm_native_column([
        fm_native_image(fm_native_media($media, $card[0]), $card[1], fm_native_class_settings('fm-card__image')),
        fm_native_heading($card[1], 'h3', fm_native_class_settings('fm-card__title')),
        fm_native_text($card[2], fm_native_class_settings('fm-card__text', $dark_text)),
    ], 33, array_merge($card_settings, fm_native_class_settings('fm-card fm-card--service')));
}
$data[] = fm_native_section($columns, fm_native_class_settings('fm-sec fm-sec--light', [
    'padding' => fm_native_dims(28, 24, 90, 24),
]));

$data[] = fm_native_header_section('REALIZACJE', 'Galeria prac', 'Galerie i obrazy muszą wskazywać media z biblioteki WP: id + url.');

// Galeria w zwykłej sekcji, nie w inner-section.
$gallery = [];
foreach (['gallery_1', 'gallery_2', 'gallery_3'] as $index => $key) {
    $title = 'Realizacja ' . ($index + 1);
    $gallery[] = fm_native_column([
        fm_native_image(fm_native_media($media, $key), $title, fm_native_class_settings('fm-tile__image')),
        fm_native_heading($title, 'h3', fm_native_class_settings('fm-tile__cap')),
    ], 33, fm_native_class_settings('fm-tile'));
}
$data[] = fm_native_section($gallery, fm_native_class_settings('fm-sec fm-sec--light fm-gallery', [
    'gap' => 'no',
    'padding' => fm_native_dims(28, 24, 90, 24),
]));

$pricing = [
    ['Start', 'od 000 zł', ['Zakres podstawowy', 'Termin do ustalenia', 'Wycena po rozmowie'], false],
    ['Standard', 'od 000 zł', ['Najczęściej wybierany zakres', 'Opieka nad procesem', 'Czytelny harmonogram'], true],
    ['Premium', 'od 000 zł', ['Rozszerzony zakres', 'Priorytetowy termin', 'Najwięcej personalizacji'], false],
];
$price_columns = [];
foreach ($pricing as $package) {
    $widgets = [];
    if ($package[3]) {
        $widgets[] = fm_native_heading('Najczęściej wybierany', 'h6', fm_native_class_settings('fm-price__badge'));
    }
    $widgets[] = fm_native_heading($package[0], 'h3', fm_native_class_settings('fm-price__name'));
    $widgets[] = fm_native_heading($package[1], 'h3', fm_native_class_settings('fm-price__value'));
    $widgets[] = fm_native_icon_list($package[2], fm_native_class_settings('fm-price__list'));
    $widgets[] = fm_native_button('Zapytaj o wycenę', '/kontakt/', fm_native_class_settings('fm-btn'));

    $price_columns[] = fm_native_column($widgets, 33, array_merge($card_settings, fm_native_class_settings($package[3] ? 'fm-card fm-price fm-price--featured' : 'fm-card fm-price')));
}
$data[] = fm_native_section($price_columns, fm_native_class_settings('fm-sec fm-sec--paper fm-pricing', [
    'padding' => fm_native_dims(90, 24, 90, 24),
]));

$data[] = fm_native_section([
    fm_native_column([
        fm_native_heading('Gotowy na rozmowę?', 'h2', fm_native_class_settings('fm-title', [
            'align' => 'center',
            'title_color' => '#ffffff',
        ])),
        fm_native_text('CTA końcowe z jasną obietnicą następnego kroku.', fm_native_class_settings('fm-sub', array_merge($white_text, [
            'align' => 'center',
        ]))),
        fm_native_button('Przejdź do kontaktu', '/kontakt/', fm_native_class_settings('fm-btn fm-btn--light', [
            'align' => 'center',
            'button_background_color' => '#ffffff',
            'button_text_color' => '#242d2d',
            'button_background_hover_color' => '#f0f2f2',
            'hover_color' => '#242d2d',
        ])),
    ], 100, fm_native_class_settings('fm-cta__inner')),
], fm_native_class_settings('fm-sec fm-sec--dark fm-cta', [
    'padding' => fm_native_dims(82, 24, 82, 24),
]));

update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($data, JSON_UNESCAPED_UNICODE)));
update_post_meta($page_id, '_elementor_edit_mode', 'builder');
update_post_meta($page_id, '_elementor_template_type', 'wp-page');
update_post_meta($page_id, '_wp_page_template', 'elementor_header_footer');
delete_post_meta($page_id, '_elementor_css');

if (class_exists('\\Elementor\\Plugin') && isset(\Elementor\Plugin::$instance->files_manager)) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
}
