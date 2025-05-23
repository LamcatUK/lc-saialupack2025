<?php
/**
 * Block template for LC CTA.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

$cta_title   = get_field( 'cta_title', 'option' ) ? get_field( 'cta_title', 'option' ) : 'Ready to transform your bathroom?';
$cta_content = get_field( 'cta_content', 'option' ) ? get_field( 'cta_content', 'option' ) : 'We\'d love to help. Get in touch today for a free, no-pressure consultation.';
$cta_link    = get_field( 'cta_link', 'option' );

$phone = get_field( 'contact_phone', 'option' );
?>
<section class="section-cta-banner">
 	<div class="container" data-aos="fade-up">
		<div class="inner">
			<h2><?= esc_html( $cta_title ); ?></h2>
			<p><?= esc_html( $cta_content ); ?></p>
			<div class="mb-4">
				<a href="<?= esc_url( $cta_link['url'] ); ?>"
				target="<?= esc_attr( $cta_link['target'] ); ?>"
				class="button button-primary--light button--arrow"><?= esc_html( $cta_link['title'] ); ?></a>
			</div>
			<?= do_shortcode( '[contact_phone text="Call Us on ' . $phone . '" class="phone-link"]' ); ?>
		</div>
 	</div>
</section>
