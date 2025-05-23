<?php
/**
 * Block template for LC Contact.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . esc_attr( $block['anchor'] ) . '"';
}


?>
<div class="container py-5" <?= $anchor; ?> data-aos="fade-up">
	<div class="row g-5">
		<div class="col-md-4">
			<h2 class="h4 text--primary-500 fw-700 section-heading--start">Register for Updates</h2>
			<p class="lead"> Sign up to get notified when new episodes drop. No spam, just stories worth savouring.</p>
		</div>
		<div class="col-md-8">
			<?= do_shortcode( '[contact-form-7 id="073fdfe"]' ); ?>
		</div>
	</div>
</div>