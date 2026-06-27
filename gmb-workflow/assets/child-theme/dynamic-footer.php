<?php
/**
 * Generic child theme footer override.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<footer id="site-footer" class="site-footer" role="contentinfo">
    <div class="site-footer__inner">
        <div class="site-footer__brand">
            <img class="site-footer__logo" src="<?php echo esc_url(trailingslashit(get_stylesheet_directory_uri()) . 'assets/img/logo.png'); ?>" alt="{{BRAND}} logo" width="64" height="64">
            <h2>{{BRAND}}</h2>
            <p>{{FOOTER_DESCRIPTION}}</p>
            <span class="site-footer__badge">{{FOOTER_BADGE}}</span>
        </div>

        <nav class="site-footer__nav" aria-label="Szybkie linki">
            <h3>Szybkie linki</h3>
            <ul>
                <li><a href="/o-nas/">O nas</a></li>
                <li><a href="/oferta/">Oferta</a></li>
                <li><a href="/realizacje/">Realizacje</a></li>
                <li><a href="/aktualnosci/">Aktualności</a></li>
                <li><a href="/kontakt/">Kontakt</a></li>
            </ul>
        </nav>

        <div class="site-footer__contact">
            <h3>Kontakt</h3>
            <p>{{ADDR}}</p>
            <p><a href="tel:{{PHONE_TEL}}">{{PHONE}}</a><br><a href="mailto:{{EMAIL}}">{{EMAIL}}</a></p>
        </div>

        <div class="site-footer__hours">
            <h3>Godziny</h3>
            <p>{{HOURS}}</p>
        </div>
    </div>

    <div class="site-footer__bottom">
        <p>&copy; <?php echo esc_html(date('Y')); ?> {{BRAND}} - Wszelkie prawa zastrzeżone.</p>
    </div>
</footer>
