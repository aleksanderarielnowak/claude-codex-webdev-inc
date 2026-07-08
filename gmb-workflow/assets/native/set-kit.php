<?php
if (!defined('ABSPATH')) {
    exit;
}

$kit_id = (int) get_option('elementor_active_kit');

if (!$kit_id) {
    if (defined('WP_CLI')) {
        WP_CLI::error('Brak aktywnego Elementor Kit.');
    }
    return;
}

// Ustaw paletę marki projektu. Wartości poniżej są neutralnym starterem.
$settings = [
    'system_colors' => [
        ['_id' => 'primary', 'title' => 'Primary', 'color' => '#2e5132'],
        ['_id' => 'secondary', 'title' => 'Secondary', 'color' => '#6b4f3a'],
        ['_id' => 'text', 'title' => 'Text', 'color' => '#2a2a26'],
        ['_id' => 'accent', 'title' => 'Accent', 'color' => '#7d8c5c'],
    ],
    'custom_colors' => [
        ['_id' => 'site_accent', 'title' => 'Accent strong', 'color' => '#c2a14a'],
        ['_id' => 'site_surface', 'title' => 'Surface', 'color' => '#fffdf8'],
        ['_id' => 'site_soft', 'title' => 'Soft background', 'color' => '#f6f1e6'],
        ['_id' => 'site_muted', 'title' => 'Muted text', 'color' => '#6f6a5f'],
    ],
    'system_typography' => [
        [
            '_id' => 'primary',
            'title' => 'Primary',
            'typography_font_family' => 'Playfair Display',
            'typography_font_weight' => '700',
        ],
        [
            '_id' => 'secondary',
            'title' => 'Secondary',
            'typography_font_family' => 'Playfair Display',
            'typography_font_weight' => '800',
        ],
        [
            '_id' => 'text',
            'title' => 'Text',
            'typography_font_family' => 'Lato',
            'typography_font_weight' => '400',
        ],
        [
            '_id' => 'accent',
            'title' => 'Accent',
            'typography_font_family' => 'Lato',
            'typography_font_weight' => '600',
        ],
    ],
    'body_typography_typography' => 'custom',
    'body_typography_font_family' => 'Lato',
    'body_typography_font_weight' => '400',
    'container_width' => [
        'unit' => 'px',
        'size' => 1200,
        'sizes' => [],
    ],
];

update_post_meta($kit_id, '_elementor_page_settings', $settings);
delete_post_meta($kit_id, '_elementor_css');

if (defined('WP_CLI')) {
    WP_CLI::success('Ustawiono Elementor Kit dla natywnego startera.');
}
