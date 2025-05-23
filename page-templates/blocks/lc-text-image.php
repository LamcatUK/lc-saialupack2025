<?php
/**
 * Block template for LC Text Image.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

$split       = get_field( 'split' );
$order_field = get_field( 'order' );
$background  = get_field( 'background' );

$txtcolwidth = '50:50' === $split ? 'col-lg-6' : 'col-lg-8';
$imgcolwidth = '50:50' === $split ? 'col-lg-6' : 'col-lg-4';

$txtcol = 'Text/Image' === $order_field ? 'order-1 order-lg-1' : 'order-1 order-lg-2';
$imgcol = 'Text/Image' === $order_field ? 'order-2 order-lg-2' : 'order-2 order-lg-1';

$bgcolour = $background ? $background : 'white';

$image_id = get_field( 'image' );

if ( $image_id ) {
	$img = wp_get_attachment_image( $image_id, 'large', false, array( 'class' => 'text_image__img' ) );
} else {
	$img = '<img src="' . esc_url( get_stylesheet_directory_uri() . '/img/default-blog.jpg' ) . '" class="text_image__img" alt="Placeholder image">';
}

$anchor = isset( $block['anchor'] ) ? $block['anchor'] : '';
if ( $anchor ) {
	?>
	<a id="<?= esc_attr( $anchor ); ?>" class="anchor"></a>
 	<?php
}

$text_aos  = 'Text/Image' === $order_field ? 'fade-right' : 'fade-left';
$image_aos = 'Text/Image' === $order_field ? 'fade-left' : 'fade-right';

?>
<section class="text_image py-5 bg--<?= esc_attr( $bgcolour ); ?>">
	<div class="container-xl">
		<div class="row g-5">
			<div
				class="<?= esc_attr( trim( "$txtcolwidth $txtcol" ) ); ?> d-flex flex-column justify-content-center align-items-start"
				data-aos="<?= esc_attr( $text_aos ); ?>">
				<?php
				if ( get_field( 'title' ) ?? null ) {
					?>
				<h2 class="section-heading--start mb-2">
						<?= esc_html( get_field( 'title' ) ); ?>
				</h2>
				 	<?php
				}
				?>
				<div><?= wp_kses_post( get_field( 'content' ) ); ?></div>
				<?php
				if ( get_field( 'cta' ) ?? null ) {
					?>
					<a href="<?= esc_url( get_field( 'cta' )['url'] ); ?>"
						class="button button-primary align-self-start mt-3"
						target="<?= esc_attr( get_field( 'cta' )['target'] ); ?>">
						<?= esc_html( get_field( 'cta' )['title'] ); ?>
					</a>
					<?php
				}
				?>
			</div>
			<div
				class="<?= esc_attr( trim( "$imgcolwidth $imgcol" ) ); ?> text_image__image"
				data-aos="<?= esc_attr( $image_aos ); ?>">
				<?= $img; // phpcs:ignore ?>
			</div>
		</div>
	</div>
</section>