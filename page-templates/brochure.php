<?php
/**
 * Template Name: Brochure
 */

get_header( 'brochure' ); ?>
<style>
	.brochure-front {
		text-align: center;
		margin-bottom: 20px;
	}
	.brochure-intro {
		margin-bottom: 20px;
	}
	.brochure-product-type {
		margin-bottom: 40px;
	}
	.brochure-products {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 20px;
	}
	.brochure-product {
		border: 1px solid #ddd;
		border-radius: 1rem;
		padding: 15px;
	}

	.brochure-product-image {
		text-align: center;
		margin-bottom: 10px;
		img {
			height: 100px;
			width: auto;
		}
	}

	.brochure-product-specs {
		display: grid;
		grid-template-columns: auto 15px auto 30px;
		align-items: center;
	}
	.gcols-2 {
		grid-column: 3 / 4;
	}

	@media print {
		/* Page setup with running headers and footers */
		@page {
			margin: 2cm;
			size: A4;

			@top-left {
				content: url("<?= esc_url( get_stylesheet_directory_uri() . '/img/sai-logo.svg' ); ?>");
				width: 41mm;
				height: 10mm;
				object-fit: contain;
				margin-top: 10mm;
				margin-bottom: 8.5mm;
			}
			@bottom-left {
				content: "📞 <?= esc_html( get_field( 'contact_phone', 'option' ) ); ?>";
				font-size: 14px;
				font-weight: bold;
				white-space: nowrap;
				background-color: var(--col-green-900);
				color: white;
				padding: 5mm 10mm;
			}
			@bottom-center {
				content: "✉️ <?= esc_html( get_field( 'contact_email', 'option' ) ); ?>";
				font-size: 14px;
				font-weight: bold;
				white-space: nowrap;
				background-color: var(--col-green-900);
				color: white;
				padding: 5mm 10mm;
			}
			@bottom-right {
				content: "🌐 www.saialupack.com";
				font-size: 14px;
				font-weight: bold;
				white-space: nowrap;
				background-color: var(--col-green-900);
				color: white;
				padding: 5mm 10mm;
			}
			/* Create a continuous green background across the footer */
			@bottom {
				margin: 0;
				background-color: var(--col-green-900);
				height: 15mm;
			}
		}

		/* First page (cover) - no header */
		@page :first {
			@top-left {
				content: none;
			}
			@top-center {
				content: none;
			}
		}

		/* Ensure background colors and images are printed */
		* {
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		/* Force page breaks after sections */
		section {
			page-break-after: always;
			break-after: page;
		}

		/* Prevent page breaks inside products */
		.brochure-product {
			page-break-inside: avoid;
			break-inside: avoid;
			width: 59mm !important;
			font-size: 7px;
			font-weight: bold;
		}

		.brochure-product {
			.card {
			border: 1px solid rgba( 0 0 0 / 0.175);
			border-radius: 16px;
		}

		/* Set product image height */
		.brochure-product-image {
			height: 20mm;
			img {
				height: 100%;
				width: auto;
				object-fit: contain;
			}
		}

		/* Ensure grid layout works well in print */
		.brochure-products {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 5mm;
			counter-reset: product-counter;
		}

		/* Force page break after every 12 products */
		.brochure-product {
			counter-increment: product-counter;
		}
		.brochure-product:nth-child(12n) {
			page-break-after: always;
			break-after: page;
		}

		/* Remove unnecessary margins for print */
		.brochure-product-type {
			margin-bottom: 0;
		}

		/* Ensure all text in product cards is bold and correct size */
		.brochure-product * {
			font-size: 8px !important;
			font-weight: bold !important;
		}

		.brochure-product-specs {
			display: grid;
			grid-template-columns: auto 5mm auto 5mm;
			align-items: center;
		}

		.brochure-product-specs-label,
		.brochure-product-specs-colon,
		.brochure-product-specs-spec,
		.brochure-product-specs-icon {
			margin-bottom: 1mm;
		}
		.brochure-product-specs-icon img {
			width: 4.5mm;
			height: 2.9mm;
			object-fit: contain;
		}

		.brochure-back {
			font-size: 12px;
		}
	}
</style>
	<?php
	// get terms of product_type
    $product_types = get_terms( array(
        'taxonomy'   => 'product_type',
        'hide_empty' => true,
    ) );
	// foreach product type output products
	if ( ! empty( $product_types ) && ! is_wp_error( $product_types ) ) {
		foreach ( $product_types as $product_type ) {
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
			);
			$products = new WP_Query( $args );
			if ( $products->have_posts() ) {
				echo '<section class="brochure-product-type">';
				echo '<h2>' . esc_html( $product_type->name ) . '</h2>';
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
					if ( has_post_thumbnail() ) {
						echo '<div class="brochure-product-image">' . get_the_post_thumbnail( get_the_ID(), 'medium' ) . '</div>';
					}
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
				}
				echo '</div>'; // .brochure-products
				echo '</section>'; // .brochure-product-type
			}
			wp_reset_postdata();
		}
	}
    ?>

<?php get_footer( 'brochure' ); ?>
