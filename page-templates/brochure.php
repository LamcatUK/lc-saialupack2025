<?php
/**
 * Template Name: Brochure
 */

defined ( 'ABSPATH' ) || exit;

// if not logged in, redirect to home.
if ( ! is_user_logged_in() ) {
	wp_redirect( home_url() );
	exit;
}

?><style>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;700&display=swap');
body {
	margin: 0;
	padding: 0;
	font-family: 'Manrope', Arial, Helvetica, sans-serif;
}
.brochure-products {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 10mm;
	width: 100%;
	page-break-after: always;
}
.brochure-product {
	/* border removed for Illustrator */
	padding: 6mm 5mm 2mm 5mm;
	box-sizing: border-box;
	page-break-inside: avoid;
	break-inside: avoid;
	font-size: 8pt !important;
	font-family: 'Manrope', Arial, Helvetica, sans-serif;
	font-weight: 400;
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: stretch;
	line-height: 1.1;
}
/* Always reserve space for image section, even if no image */
.brochure-product-image {
	text-align: center;
	margin-bottom: 4mm;
	height: 18mm;
	display: flex;
	align-items: center;
	justify-content: center;
}
.brochure-product-image img {
	max-height: 18mm;
	width: auto;
	object-fit: contain;
	display: block;
	margin: 0 auto;
}
.brochure-product-sku,
.brochure-product-title {
	font-size: 8pt;
	font-family: 'Manrope', Arial, Helvetica, sans-serif;
	font-weight: 700;
	margin-bottom: 2mm;
}
.brochure-product-specs {
	display: grid;
	grid-template-columns: auto 5mm auto 5mm;
	align-items: center;
	font-size: 8pt;
	font-family: 'Manrope', Arial, Helvetica, sans-serif;
	font-weight: 400;
}
.brochure-product-specs-label,
.brochure-product-specs-colon,
.brochure-product-specs-spec,
.brochure-product-specs-icon {
	margin-bottom: 1mm;
	font-family: 'Manrope', Arial, Helvetica, sans-serif;
	font-weight: 400;
}
.brochure-product-specs-icon img {
	width: 4.5mm;
	height: 2.9mm;
	object-fit: contain;
}
@media print {
	body {
		margin: 0;
		padding: 0;
		font-family: 'Manrope', Arial, Helvetica, sans-serif;
	}
	.brochure-products {
		gap: 5mm;
		page-break-after: always;
	}
	.brochure-product {
		font-size: 8pt !important;
		page-break-inside: avoid;
		break-inside: avoid;
		font-family: 'Manrope', Arial, Helvetica, sans-serif;
	}
	.brochure-product * {
		font-size: 8pt !important;
		font-family: 'Manrope', Arial, Helvetica, sans-serif;
	}
	.brochure-product-sku,
	.brochure-product-title {
		font-weight: 700;
	}
	.brochure-product-specs,
	.brochure-product-specs-label,
	.brochure-product-specs-colon,
	.brochure-product-specs-spec,
	.brochure-product-specs-icon {
		font-weight: 400;
	}
	.brochure-product:nth-child(12n) {
		page-break-after: always;
		break-after: page;
	}
}
</style>
<?php
// Get product types (taxonomy terms)
$product_types = get_terms( array(
	'taxonomy'   => 'product_type',
	'hide_empty' => true,
) );
if ( ! empty( $product_types ) && ! is_wp_error( $product_types ) ) {
	foreach ( $product_types as $product_type ) {
		// Get products for this type, ordered by SKU (post title)
		$args = array(
			'post_type'      => 'product',
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'term_id',
					'terms'    => $product_type->term_id,
				),
			),
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		);
		$products = new WP_Query( $args );
		if ( $products->have_posts() ) {
			echo '<section class="brochure-product-type">';
			$count = 0;
			echo '<div class="brochure-products">';
			while ( $products->have_posts() ) {
				$products->the_post();

				$top_in_a  = get_field( 'top_in_a', get_the_ID() );
				$top_in_b  = get_field( 'top_in_b', get_the_ID() );
				$top_out_a = get_field( 'top_out_a', get_the_ID() );
				$top_out_b = get_field( 'top_out_b', get_the_ID() );
				$base_a    = get_field( 'base_a', get_the_ID() );
				$base_b    = get_field( 'base_b', get_the_ID() );
				$depth     = get_field( 'depth', get_the_ID() );
				$capacity  = get_field( 'capacity', get_the_ID() );

			echo '<div class="brochure-product">';
			echo '<div class="brochure-product-image">';
			if ( has_post_thumbnail() ) {
				echo get_the_post_thumbnail( get_the_ID(), 'medium' );
			}
			echo '</div>';
			?>
				<div class="brochure-product-sku">SKU: <?= esc_html( get_the_title() ); ?></div>
				<div class="brochure-product-title"><?= esc_html( get_field( 'product_name' ) ); ?></div>
				<div class="brochure-product-specs">
					<?php
					if ( $top_out_a || $top_out_b ) {
						?>
					<div class="brochure-product-specs-label">Top Out</div>
					<div class="brochure-product-specs-colon">:</div>
					<div class="brochure-product-specs-spec"><?= esc_html( dimensions( $top_out_a, $top_out_b ) ); ?> mm</div>
					<div class="brochure-product-specs-icon"><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-top-out.svg' ); ?>"></div>
						<?php
					}
					if ( $top_in_a || $top_in_b ) {
						?>
					<div class="brochure-product-specs-label">Top In</div>
					<div class="brochure-product-specs-colon">:</div>
					<div class="brochure-product-specs-spec"><?= esc_html( dimensions( $top_in_a, $top_in_b ) ); ?> mm</div>
					<div class="brochure-product-specs-icon"><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-top-in.svg' ); ?>"></div>
						<?php
					}
					if ( $base_a || $base_b ) {
						?>
					<div class="brochure-product-specs-label">Base</div>
					<div class="brochure-product-specs-colon">:</div>
					<div class="brochure-product-specs-spec"><?= esc_html( dimensions( $base_a, $base_b ) ); ?> mm</div>
					<div class="brochure-product-specs-icon"><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-base.svg' ); ?>"></div>
						<?php
					}
					if ( $depth ) {
						?>
					<div class="brochure-product-specs-label">Depth</div>
					<div class="brochure-product-specs-colon">:</div>
					<div class="brochure-product-specs-spec"><?= esc_html( get_field( 'depth', get_the_ID() ) ); ?> mm</div>
					<div class="brochure-product-specs-icon"><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-depth.svg' ); ?>"></div>
						<?php
					}
					if ( $capacity ) {
						?>
					<div class="brochure-product-specs-label">Capacity</div>
					<div class="brochure-product-specs-colon">:</div>
					<div class="brochure-product-specs-spec"><?= esc_html( get_field( 'capacity', get_the_ID() ) ); ?> ml</div>
					<div class="brochure-product-specs-icon"><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-capacity.svg' ); ?>"></div>
						<?php
					}
					if ( get_field( 'package_size', get_the_ID() ) ) {
						?>
					<div class="brochure-product-specs-label">Package Size</div>
					<div class="brochure-product-specs-colon">:</div>
					<div class="brochure-product-specs-spec gcols-2"><?= esc_html( get_field( 'package_size', get_the_ID() ) ); ?></div>
						<?php
					}
					?>
				</div>
				<?php
				echo '</div>';
				$count++;
				if ( $count % 12 === 0 && $products->current_post + 1 < $products->post_count ) {
					echo '</div><div class="brochure-products" style="page-break-before: always;">';
				}
			}
			echo '</div>';
			echo '</section>';
		}
		wp_reset_postdata();
	}
}
?>
<?php get_footer( 'brochure' ); ?>
