<?php
/**
 * Block template for LC Popular Products.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

$background = get_field( 'background' );
$bgcolour   = $background ? $background : 'white';

$products = get_field( 'products' );
if ( ! $products || ! is_array( $products ) || empty( $products ) ) {
	return;
}

?>
<section class="lc-product-cards py-5 bg--<?= esc_attr( $bgcolour ); ?>">
	<div class="container">
		<h2 class="text-center"><?= esc_html( get_field( 'title' ) ); ?></h2>
		<div class="text-center mb-4"><?= wp_kses_post( get_field( 'content' ) ); ?></div>
		<div class="splide" id="productCardsSplide">
			<div class="splide__track">
				<ul class="splide__list">
					<?php
					foreach ( $products as $product ) {
						$sku       = get_field( 'sku', $product );
						$capacity  = get_field( 'capacity', $product );
						$depth     = get_field( 'depth', $product );
						$top_out_a = get_field( 'top_out_a', $product );
						$top_out_b = get_field( 'top_out_b', $product );
						$top_in_a  = get_field( 'top_in_a', $product );
						$top_in_b  = get_field( 'top_in_b', $product );
						$base_a    = get_field( 'base_a', $product );
						$base_b    = get_field( 'base_b', $product );

						$category_terms = wp_get_post_terms( $product, 'product_category', array( 'fields' => 'slugs' ) );
						$edge_terms     = wp_get_post_terms( $product, 'edge_type', array( 'fields' => 'slugs' ) );
						?>
					<li class="splide__slide product-card">
						<a class="card h-100" href="<?= esc_url( get_permalink( $product) ); ?>">
							<?php
							if ( has_post_thumbnail( $product ) ) {
								?>
							<img src="<?= esc_url( get_the_post_thumbnail_url( $product, 'medium' ) ); ?>" class="card-img-top" alt="<?= esc_attr( $sku ); ?>">
								<?php
							} else {
								?>
							<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/default-product.png' ); ?>" class="card-img-top" alt="<?php esc_attr( $sku ); ?>">
								<?php
							}
							?>
							<div class="card-body">
								<div class="card-title"><?= esc_html( $sku ); ?></div>
								<ul class="list-unstyled mb-0 fs-300">
									<li><strong>Capacity:</strong> <?= esc_html( $capacity ); ?>ml</li>
									<li><strong>Depth:</strong> <?= esc_html( $depth ); ?>mm</li>
									<li><strong>Top Out:</strong> <?= esc_html( $top_out_a . ' x ' . $top_out_b ); ?>mm</li>
									<li><strong>Top In:</strong> <?= esc_html( $top_in_a . ' x ' . $top_in_b ); ?>mm</li>
									<li><strong>Base:</strong> <?= esc_html( $base_a . ' x ' . $base_b ); ?>mm</li>
								</ul>
							</div>
						</a>
					</li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.Splide) {
        new Splide('#productCardsSplide', {
            type   : 'loop',
            perPage: 4,
			perMove: 1,
			cloneStatus: true,
            gap    : '1.5rem',
			autoplay: true,
			pagination: false,
			interval: 3000,
			breakpoints: {
                1200: { perPage: 3 },
                768: { perPage: 2 },
                576: { perPage: 1 }
            }
        }).mount();
    }
});
</script>