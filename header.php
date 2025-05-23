<?php
/**
 * The header for the theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package lc-saialupack2025
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
session_start();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta
        charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() ); ?>/fonts/manrope-v15-latin-regular.woff2"
        as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() ); ?>/fonts/manrope-v15-latin-600.woff2"
        as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() ); ?>/fonts/manrope-v15-latin-700.woff2"
        as="font" type="font/woff2" crossorigin="anonymous">
    <?php
	if ( ! is_user_logged_in() ) {
    	if ( get_field( 'ga_property', 'options' ) ) {
        	?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async
        src="https://www.googletagmanager.com/gtag/js?id=<?= esc_attr( get_field( 'ga_property', 'options' ) ); ?>">
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config',
            '<?= esc_attr( get_field( 'ga_property', 'options' ) ); ?>'
            );
    </script>
        	<?php
    	}
    	if ( get_field( 'gtm_property', 'options' ) ) {
        	?>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer',
            '<?= esc_attr( get_field( 'gtm_property', 'options' ) ); ?>'
            );
    </script>
    <!-- End Google Tag Manager -->
        	<?php
    	}
	}
	if ( get_field( 'google_site_verification', 'options' ) ) {
		echo '<meta name="google-site-verification" content="' . esc_attr( get_field( 'google_site_verification', 'options' ) ) . '" />';
	}
	if ( get_field( 'bing_site_verification', 'options' ) ) {
		echo '<meta name="msvalidate.01" content="' . esc_attr( get_field( 'bing_site_verification', 'options' ) ) . '" />';
	}

	wp_head();

    if ( is_front_page() ) {
        /*
        ?>
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "HomeAndConstructionBusiness",
    "name": "Valewood Bathrooms",
    "url": "https://www.valewoodbathrooms.co.uk/",
    "logo": "https://www.valewoodbathrooms.co.uk/wp-content/theme/lc-saialupack2025/img/valewood-logo.jpg",
    "description": "Valewood Bathrooms offers bespoke bathroom renovation, design, and installation services across West Sussex, tailored to your space and needs.",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Shelley's Farm, Valewood Lane",
        "addressLocality": "Barns Green",
        "addressRegion": "West Sussex",
        "postalCode": "RH13 0QJ",
        "addressCountry": "GB"
    },
    "telephone": "+44 7581 858426",
    "email": "hello@valewoodbathrooms.co.uk",
    "sameAs": [
        "https://www.facebook.com/valewoodbathrooms",
        "https://www.google.com/maps?cid=ChIJZfEPPzbBdUgRQy5bcknd9XQ"
    ],
    "openingHoursSpecification": [
        {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday"
        ],
        "opens": "08:00",
        "closes": "18:00"
        }
    ],
    "areaServed": [
        { "@type": "Place", "name": "Horsham" },
        { "@type": "Place", "name": "Southwater" },
        { "@type": "Place", "name": "Billingshurst" },
        { "@type": "Place", "name": "Pulborough" },
        { "@type": "Place", "name": "Storrington" },
        { "@type": "Place", "name": "West Chiltington" },
        { "@type": "Place", "name": "Barns Green" },
        { "@type": "Place", "name": "West Sussex" }
    ],
    "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Bathroom Services",
        "itemListElement": [
        {
            "@type": "Offer",
            "itemOffered": {
            "@type": "Service",
            "name": "Bathroom Design",
            "description": "Tailored bathroom design services to help you create a stylish and functional space."
            }
        },
        {
            "@type": "Offer",
            "itemOffered": {
            "@type": "Service",
            "name": "Bathroom Renovation",
            "description": "Full and partial bathroom renovation projects, from retiling to layout changes and modernisation."
            }
        },
        {
            "@type": "Offer",
            "itemOffered": {
            "@type": "Service",
            "name": "Bathroom Installation",
            "description": "Expert installation of bathrooms including plumbing, fixtures, tiling, and finishing."
            }
        }
        ]
    }
}
</script>
        <?php
        */
    }
    ?>
</head>
<body <?php body_class(); ?>
    <?php understrap_body_attributes(); ?>>
    <?php
	do_action( 'wp_body_open' );
	if ( ! is_user_logged_in() ) {
    	if ( get_field( 'gtm_property', 'options' ) ) {
        	?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe
        src="https://www.googletagmanager.com/ns.html?id=<?= esc_attr( get_field( 'gtm_property', 'options' ) ); ?>"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    		<?php
		}
	}
	?>
<header id="wrapper-navbar" class="fixed-top">
        <nav id="main-nav" class="navbar navbar-expand-lg pb-0" aria-labelledby="main-nav-label">
            <div class="container-xl">
                <div class="d-flex justify-content-between w-100 w-lg-auto align-items-center">
                    <!-- Logo -->
                    <a href="/" class="logo"></a>

                    <!-- Mobile Menu Toggle -->
                    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <i class="fal fa-bars"></i>
                    </button>
                </div>

                <!-- Navbar Content (Now inside the same container-xl) -->
                <div id="navbarContent" class="collapse navbar-collapse">
                    <div class="w-100 d-flex flex-column justify-content-lg-between align-items-lg-center row-gap-2">
                        <!-- Contact Details (Hidden on Mobile) -->
                        <div class="contact-info d-none d-lg-flex gap-3 w-100 justify-content-end">
                            <span><i class="fal fa-phone-alt"></i> <?= do_shortcode( '[contact_phone]' ); ?></span>
                            <span><i class="fal fa-envelope"></i> <?= do_shortcode( '[contact_email]' ); ?></span>
                        </div>

                        <!-- Navigation -->
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location'  => 'primary_nav',
                                'container'       => false,
                                'menu_class'      => 'navbar-nav w-100 justify-content-end flex-wrap align-items-lg-center',
                                'fallback_cb'     => '',
                                'depth'           => 3,
                                'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
