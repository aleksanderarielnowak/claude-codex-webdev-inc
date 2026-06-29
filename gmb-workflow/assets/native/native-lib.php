<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('fm_native_uid')) {
    function fm_native_uid() {
        $hex = '0123456789abcdef';
        $id = '';

        for ($i = 0; $i < 7; $i++) {
            $id .= $hex[wp_rand(0, 15)];
        }

        return $id;
    }
}

if (!function_exists('fm_native_dims')) {
    function fm_native_dims($top, $right = null, $bottom = null, $left = null, $unit = 'px') {
        if ($right === null) {
            $right = $top;
        }
        if ($bottom === null) {
            $bottom = $top;
        }
        if ($left === null) {
            $left = $right;
        }

        return [
            'unit' => $unit,
            'top' => $top,
            'right' => $right,
            'bottom' => $bottom,
            'left' => $left,
            'isLinked' => ($top === $right && $top === $bottom && $top === $left),
        ];
    }
}

if (!function_exists('fm_native_class_settings')) {
    function fm_native_class_settings($classes, $settings = []) {
        $classes = trim((string) $classes);

        foreach (['css_classes', '_css_classes'] as $key) {
            $current = isset($settings[$key]) ? trim((string) $settings[$key]) : '';
            $settings[$key] = trim($current . ' ' . $classes);
        }

        return $settings;
    }
}

if (!function_exists('fm_native_media')) {
    function fm_native_media($media, $key) {
        if (!isset($media[$key]) || !is_array($media[$key])) {
            return ['id' => 0, 'url' => ''];
        }

        return [
            'id' => isset($media[$key]['id']) ? (int) $media[$key]['id'] : 0,
            'url' => isset($media[$key]['url']) ? esc_url_raw($media[$key]['url']) : '',
            'source' => 'library',
            'alt' => '',
            'size' => '',
        ];
    }
}

if (!function_exists('fm_native_icon')) {
    function fm_native_icon($icon) {
        return [
            'value' => 'fas ' . $icon,
            'library' => 'fa-solid',
        ];
    }
}

if (!function_exists('fm_native_widget')) {
    function fm_native_widget($type, $settings) {
        return [
            'id' => fm_native_uid(),
            'elType' => 'widget',
            'widgetType' => $type,
            'settings' => $settings,
            'elements' => [],
        ];
    }
}

if (!function_exists('fm_native_heading')) {
    function fm_native_heading($title, $size = 'h2', $settings = []) {
        return fm_native_widget('heading', array_merge([
            'title' => $title,
            'header_size' => $size,
        ], $settings));
    }
}

if (!function_exists('fm_native_text')) {
    function fm_native_text($editor, $settings = []) {
        return fm_native_widget('text-editor', array_merge([
            'editor' => $editor,
        ], $settings));
    }
}

if (!function_exists('fm_native_button')) {
    function fm_native_button($text, $url, $settings = []) {
        return fm_native_widget('button', array_merge([
            'text' => $text,
            'link' => [
                'url' => $url,
                'is_external' => '',
                'nofollow' => '',
            ],
            'size' => 'md',
            'button_background_color' => '#ff7f00',
            'button_text_color' => '#ffffff',
            'hover_color' => '#ffffff',
            'button_background_hover_color' => '#e67200',
            'border_radius' => fm_native_dims(4),
            'text_padding' => fm_native_dims(15, 26, 15, 26),
        ], $settings));
    }
}

if (!function_exists('fm_native_image')) {
    function fm_native_image($image, $alt = '', $settings = []) {
        return fm_native_widget('image', array_merge([
            'image' => $image,
            'image_size' => 'full',
            'caption_source' => 'none',
            '_wp_attachment_image_alt' => $alt,
        ], $settings));
    }
}

if (!function_exists('fm_native_icon_box')) {
    function fm_native_icon_box($icon, $title, $description, $settings = []) {
        return fm_native_widget('icon-box', array_merge([
            'selected_icon' => fm_native_icon($icon),
            'title_text' => $title,
            'description_text' => $description,
            'position' => 'top',
            'primary_color' => '#ff7f00',
            'title_color' => '#242d2d',
            'description_color' => '#4b5555',
            'icon_size' => ['unit' => 'px', 'size' => 42],
            'title_bottom_space' => ['unit' => 'px', 'size' => 12],
        ], $settings));
    }
}

if (!function_exists('fm_native_icon_list')) {
    function fm_native_icon_list($items, $settings = []) {
        $list = [];

        foreach ($items as $item) {
            $list[] = [
                '_id' => fm_native_uid(),
                'text' => $item,
                'selected_icon' => fm_native_icon('fa-check'),
            ];
        }

        return fm_native_widget('icon-list', array_merge([
            'icon_list' => $list,
            'icon_color' => '#ff7f00',
            'text_color' => '#242d2d',
            'space_between' => ['unit' => 'px', 'size' => 10],
            'icon_size' => ['unit' => 'px', 'size' => 16],
        ], $settings));
    }
}

if (!function_exists('fm_native_column')) {
    function fm_native_column($widgets, $size = 100, $settings = []) {
        return [
            'id' => fm_native_uid(),
            'elType' => 'column',
            'settings' => array_merge([
                '_column_size' => $size,
                '_inline_size' => null,
            ], $settings),
            'elements' => $widgets,
        ];
    }
}

if (!function_exists('fm_native_inner_section')) {
    function fm_native_inner_section($columns, $settings = []) {
        return [
            'id' => fm_native_uid(),
            'elType' => 'section',
            'isInner' => true,
            'settings' => array_merge([
                'layout' => 'full_width',
                'gap' => 'narrow',
                'padding' => fm_native_dims(0),
                'margin' => fm_native_dims(10, 0, 0, 0),
            ], $settings),
            'elements' => $columns,
        ];
    }
}

if (!function_exists('fm_native_section')) {
    function fm_native_section($columns, $settings = []) {
        return [
            'id' => fm_native_uid(),
            'elType' => 'section',
            'settings' => array_merge([
                'layout' => 'full_width',
                'stretch_section' => 'section-stretched',
                'gap' => 'default',
                'content_width' => ['unit' => 'px', 'size' => 1180],
                'padding' => fm_native_dims(90, 24, 90, 24),
            ], $settings),
            'elements' => $columns,
        ];
    }
}

if (!function_exists('fm_native_header_section')) {
    function fm_native_header_section($eyebrow, $title, $lead = '', $dark = false) {
        $widgets = [
            fm_native_heading($eyebrow, 'h6', fm_native_class_settings('fm-eyebrow', [
                'align' => 'center',
                'title_color' => '#ff7f00',
                'typography_typography' => 'custom',
                'typography_font_size' => ['unit' => 'px', 'size' => 14],
                'typography_font_weight' => '700',
                'typography_text_transform' => 'uppercase',
            ])),
            fm_native_heading($title, 'h2', fm_native_class_settings('fm-title', [
                'align' => 'center',
                'title_color' => $dark ? '#ffffff' : '#242d2d',
                'typography_typography' => 'custom',
                'typography_font_size' => ['unit' => 'px', 'size' => 42],
                'typography_font_weight' => '800',
            ])),
        ];

        if ($lead !== '') {
            $widgets[] = fm_native_text($lead, fm_native_class_settings('fm-sub', [
                'align' => 'center',
                'text_color' => $dark ? '#ffffff' : '#4b5555',
                'typography_typography' => 'custom',
                'typography_font_size' => ['unit' => 'px', 'size' => 18],
                '_margin' => fm_native_dims(0, 0, 0, 0),
            ]));
        }

        return fm_native_section([
            fm_native_column($widgets, 100, fm_native_class_settings('fm-head', [
                '_padding' => fm_native_dims(0, 14, 0, 14),
            ])),
        ], fm_native_class_settings('fm-sec fm-sec--head ' . ($dark ? 'fm-sec--dark' : 'fm-sec--light'), [
            'background_background' => 'classic',
            'background_color' => $dark ? '#242d2d' : '#ffffff',
            'padding' => fm_native_dims(90, 24, 28, 24),
        ]));
    }
}
