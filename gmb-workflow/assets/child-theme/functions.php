<?php
/**
 * Generic child theme assets for HTML-section builds.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'site-fonts',
        'https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&family=Playfair+Display:wght@600;700;800&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'hello-elementor',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme('hello-elementor')->get('Version')
    );

    wp_enqueue_style(
        'site-child',
        get_stylesheet_directory_uri() . '/style.css',
        ['hello-elementor', 'site-fonts'],
        wp_get_theme()->get('Version')
    );
});

add_filter('get_the_archive_title', function ($title) {
    if (is_home() || is_post_type_archive('post') || is_category() || is_tag() || is_date() || is_author()) {
        return 'Aktualności';
    }

    return $title;
});

add_filter('the_excerpt', function ($excerpt) {
    if (is_admin() || is_singular() || !in_the_loop()) {
        return $excerpt;
    }

    return $excerpt . '<p><a class="more-link" href="' . esc_url(get_permalink()) . '">Czytaj więcej</a></p>';
});
