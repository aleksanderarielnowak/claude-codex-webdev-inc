<?php
/**
 * Plugin Name: KW Aktualności (shortcode kafelków wpisów)
 * Description: [kw_aktualnosci] — siatka kafelków najnowszych wpisów. Standard w workflow GMB→strona.
 * Wgraj do: <site>/app/public/wp-content/mu-plugins/kw-aktualnosci.php  (CSS .kw-akt-* w base-additions.css)
 */
if (!defined('ABSPATH')) exit;

add_shortcode('kw_aktualnosci', function($atts){
    $atts = shortcode_atts(['count' => 9], $atts, 'kw_aktualnosci');
    $q = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => intval($atts['count']),
        'ignore_sticky_posts' => true,
    ]);
    if (!$q->have_posts()) return '<p style="text-align:center;color:#777">Wkrótce pojawią się tu aktualności.</p>';

    $o = '<div class="kw-akt-grid">';
    while ($q->have_posts()){ $q->the_post();
        $url = esc_url(get_permalink());
        $date = esc_html(get_the_date('j.m.Y'));
        $excerpt = wp_trim_words(get_the_excerpt(), 22);
        $o .= '<article class="kw-akt-card">';
        $o .=   '<span class="kw-akt-tag">Aktualności</span>';
        $o .=   '<h3 class="kw-akt-title"><a href="'.$url.'">'.esc_html(get_the_title()).'</a></h3>';
        $o .=   '<span class="kw-akt-date">'.$date.'</span>';
        if ($excerpt) $o .= '<p class="kw-akt-exc">'.esc_html($excerpt).'</p>';
        $o .=   '<a class="kw-akt-more" href="'.$url.'">Czytaj więcej &rarr;</a>';
        $o .= '</article>';
    }
    $o .= '</div>';
    wp_reset_postdata();
    return $o;
});
