<?php
/**
 * Footer template for the Valewood Bathrooms theme.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="footer pt-5">
    <div class="container">
        <div class="row gx-4 g-lg-2 g-xxl-5">
            <div class="col-xl-3 text-center mb-3 pe-3">
                <img src="<?= esc_url( get_stylesheet_directory_uri() ); ?>/img/sai-logo--wo.svg" alt="Sai Alupack Ltd" class="footer__logo">
            </div>
            <div class="col-sm-6 col-xl-3">
                <?=
				wp_nav_menu(
                    array(
                        'theme_location'  => 'footer_menu1',
                    	'container_class' => 'footer__menu',
                    )
                );
                ?>
            </div>
            <div class="col-sm-6 col-xl-3">
                <?=
				wp_nav_menu(
					array(
						'theme_location'  => 'footer_menu2',
						'container_class' => 'footer__menu',
					)
				);
				?>
            </div>
            <div class="col-sm-6 col-xl-3">
                <ul class="fa-ul">
                    <li class="mb-2">
                        <span class="fa-li">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <?= get_field( 'contact_address', 'options' ); ?>
                    </li>
                    <li class="mb-2">
                        <span class="fa-li">
                            <i class="fas fa-phone"></i>
                        </span>
                        <a href="tel:<?= esc_html( get_field( 'contact_phone', 'options' ) ); ?>"><?= esc_html( get_field( 'contact_phone', 'options' ) ); ?></a>
                    </li>
                    <li>
                        <span class="fa-li">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <a href="mailto:<?= esc_html( antispambot( get_field( 'contact_email', 'options' ) ) ); ?>"><?= esc_html( antispambot( get_field( 'contact_email', 'options' ) ) ); ?></a>
                </ul>
            </div>
        </div>

        <div class="row gx-2 colophon py-3">
            <div class="col-lg-7 text-center text-lg-start">
                &copy; <?= esc_html( gmdate( 'Y' ) ); ?> Sai Alupack Ltd. Registered in England, no. 14328453.
            </div>
            <div class="col-lg-5 text-end d-flex gap-2 justify-content-center justify-content-lg-end flex-wrap">
                <a href="/privacy-policy/">Privacy &amp; Cookies</a> |
                <span>Site by <a href="https://www.lamcat.co.uk/" rel="noopener" target="_blank" class="lc" title="Lamcat Design & Consulting">Lamcat</a></span>
            </div>
        </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>