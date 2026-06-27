<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('site_uid')) {
    function site_uid() {
        return substr(str_replace('-', '', wp_generate_uuid4()), 0, 7);
    }
}

if (!function_exists('site_html_widget')) {
    function site_html_widget($html) {
        return [
            'id' => site_uid(),
            'elType' => 'widget',
            'widgetType' => 'html',
            'settings' => ['html' => $html],
            'elements' => [],
        ];
    }
}

if (!function_exists('site_section')) {
    function site_section($html) {
        return [
            'id' => site_uid(),
            'elType' => 'section',
            'settings' => ['layout' => 'full_width', 'gap' => 'no'],
            'elements' => [[
                'id' => site_uid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [site_html_widget($html)],
            ]],
        ];
    }
}

function site_apply_elementor_page($page_id, array $sections) {
    $data = array_map('site_section', $sections);

    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($data)));
    update_post_meta($page_id, '_elementor_edit_mode', 'builder');
    update_post_meta($page_id, '_elementor_template_type', 'wp-page');
    update_post_meta($page_id, '_wp_page_template', 'elementor_header_footer');
    delete_post_meta($page_id, '_elementor_css');
}

$asset = trailingslashit(get_stylesheet_directory_uri()) . 'assets/img/';
$photos = $asset . 'photos/';

// HOME: skrót kompozycji hero + stats + karty + CTA.
$home_id = 123; // TODO: ID strony głównej.

$home_hero = <<<HTML
<section class="site-sec site-hero">
  <div class="site-wrap site-grid">
    <div>
      <span class="site-badge">{{HERO_BADGE}}</span>
      <h1>{{BRAND}} - {{HERO_H1}}</h1>
      <p class="site-lead">{{HERO_LEAD}}</p>
      <div class="site-actions">
        <a class="site-btn" href="{{PRIMARY_CTA_URL}}">{{PRIMARY_CTA_LABEL}}</a>
        <a class="site-btn site-btn--ghost" href="/kontakt/">Kontakt</a>
      </div>
    </div>
    <figure class="site-figure">
      <img src="{$asset}hero.svg" alt="{{HERO_IMAGE_ALT}}" loading="eager">
    </figure>
  </div>
</section>
HTML;

$home_stats = <<<HTML
<section class="site-sec site-sec--dark">
  <div class="site-wrap site-stats" aria-label="Najważniejsze informacje">
    <div class="site-counter"><strong>{{STAT_1}}</strong><span>{{STAT_1_LABEL}}</span></div>
    <div class="site-counter"><strong>{{STAT_2}}</strong><span>{{STAT_2_LABEL}}</span></div>
    <div class="site-counter"><strong>{{STAT_3}}</strong><span>{{STAT_3_LABEL}}</span></div>
  </div>
</section>
HTML;

$home_cards = <<<HTML
<section class="site-sec site-sec--cream">
  <div class="site-wrap">
    <div class="site-sec-head">
      <p class="site-eyebrow">{{SECTION_EYEBROW}}</p>
      <h2 class="site-title">{{SECTION_TITLE}}</h2>
      <p class="site-sub">{{SECTION_SUBTITLE}}</p>
    </div>
    <div class="site-cards site-cards--3">
      <article class="site-card"><div class="site-card__media"><img src="{$photos}service-1.jpg" alt="{{SERVICE_1_ALT}}" loading="lazy"></div><h3 class="site-card-title">{{SERVICE_1}}</h3><p>{{SERVICE_1_TEXT}}</p><a class="site-link" href="{{SERVICE_1_URL}}">Dowiedz się więcej</a></article>
      <article class="site-card"><div class="site-card__media"><img src="{$photos}service-2.jpg" alt="{{SERVICE_2_ALT}}" loading="lazy"></div><h3 class="site-card-title">{{SERVICE_2}}</h3><p>{{SERVICE_2_TEXT}}</p><a class="site-link" href="{{SERVICE_2_URL}}">Dowiedz się więcej</a></article>
      <article class="site-card"><div class="site-card__media"><img src="{$photos}service-3.jpg" alt="{{SERVICE_3_ALT}}" loading="lazy"></div><h3 class="site-card-title">{{SERVICE_3}}</h3><p>{{SERVICE_3_TEXT}}</p><a class="site-link" href="{{SERVICE_3_URL}}">Dowiedz się więcej</a></article>
    </div>
  </div>
</section>
HTML;

$home_cta = <<<HTML
<section class="site-sec site-sec--dark">
  <div class="site-wrap site-grid">
    <div>
      <p class="site-eyebrow">Kontakt</p>
      <h2 class="site-title">{{CTA_TITLE}}</h2>
      <p>{{ADDR}}<br>Tel. <a href="tel:{{PHONE_TEL}}">{{PHONE}}</a><br>E-mail: <a href="mailto:{{EMAIL}}">{{EMAIL}}</a></p>
    </div>
    <div class="site-actions">
      <a class="site-btn site-btn--light" href="/kontakt/">Dane kontaktowe</a>
      <a class="site-btn site-btn--ghost" href="{{MAP_URL}}" target="_blank" rel="noopener">Otwórz mapę</a>
    </div>
  </div>
</section>
HTML;

site_apply_elementor_page($home_id, [$home_hero, $home_stats, $home_cards, $home_cta]);

// PODSTRONY OFERTOWE: jeden wzorzec, wiele rekordów.
$offer_pages = [
    [
        'page_id' => 201,
        'slug' => 'usluga-1',
        'label' => '{{SERVICE_1}}',
        'title' => '{{SERVICE_1_H1}}',
        'lead' => '{{SERVICE_1_LEAD}}',
        'image' => $photos . 'service-1-hero.jpg',
    ],
    [
        'page_id' => 202,
        'slug' => 'usluga-2',
        'label' => '{{SERVICE_2}}',
        'title' => '{{SERVICE_2_H1}}',
        'lead' => '{{SERVICE_2_LEAD}}',
        'image' => $photos . 'service-2-hero.jpg',
    ],
];

foreach ($offer_pages as $page) {
    $hero = <<<HTML
<section class="site-photo-hero" style="background-image: linear-gradient(90deg, rgba(46,81,50,.88), rgba(46,81,50,.54), rgba(46,81,50,.22)), url('{$page['image']}');">
  <div class="site-wrap">
    <div class="site-photo-hero__content">
      <span class="site-badge">{$page['label']}</span>
      <h1>{$page['title']}</h1>
      <p>{$page['lead']}</p>
      <div class="site-actions">
        <a class="site-btn site-btn--light" href="tel:{{PHONE_TEL}}">Zadzwoń</a>
        <a class="site-btn site-btn--ghost site-btn--on-photo" href="/kontakt/">Zapytaj o ofertę</a>
      </div>
    </div>
  </div>
</section>
HTML;

    $details = <<<HTML
<section class="site-sec site-sec--paper">
  <div class="site-wrap site-grid site-grid--top">
    <div>
      <p class="site-eyebrow">Zakres</p>
      <h2 class="site-title">{{DETAILS_TITLE}}</h2>
      <p>{{DETAILS_TEXT}}</p>
      <ul class="site-checklist">
        <li>{{BENEFIT_1}}</li>
        <li>{{BENEFIT_2}}</li>
        <li>{{BENEFIT_3}}</li>
      </ul>
    </div>
    <div class="site-card site-card--green">
      <h3 class="site-card-title">Zapytaj o szczegóły</h3>
      <p>{{CTA_COPY}}</p>
      <a class="site-btn site-btn--light" href="/kontakt/">Kontakt</a>
    </div>
  </div>
</section>
HTML;

    site_apply_elementor_page((int) $page['page_id'], [$hero, $details]);
}

if (defined('WP_CLI')) {
    WP_CLI::success('Zbudowano przykładowe strony HTML-section.');
}
