<?php
/**
 * The template for displaying all single products
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main">
	<div class="container pb-5">
		<section class="yoast-breadcrumbs pb-3">
		<?php
		echo '<a href="/">Home</a>';
		echo ' &gt; ';
		echo '<a href="/products/">Products</a>';

		$product_type_terms = get_the_terms( get_the_ID(), 'product_type' );
		if ( $product_type_terms && ! is_wp_error( $product_type_terms ) ) {
			echo ' &gt; ';
			$first_type = $product_type_terms[0];
			echo '<a href="' . esc_url( get_term_link( $first_type ) ) . '">' . esc_html( $first_type->name ) . '</a>';
		}

		echo ' &gt; ';
		$product_name = get_field( 'product_name' );
		if ( $product_name ) {
			echo esc_html( $product_name );
		} else {
			echo esc_html( get_the_title() );
		}
		?>
		</section>
		<div class="row">
			<div class="col-md-4 text-center my-auto">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) );
				}
				?>
			</div>
			<div class="col-md-7 offset-md-1">
				<h1 class="h2"><?= esc_html( get_field( 'product_name' ) ); ?></h1>
				<h2 class="h3">Product Information</h2>
				<?php
				$top_out_a = get_field( 'top_out_a' );
				$top_out_b = get_field( 'top_out_b' );
				$top_in_a  = get_field( 'top_in_a' );
				$top_in_b  = get_field( 'top_in_b' );
				$base_a    = get_field( 'base_a' );
				$base_b    = get_field( 'base_b' );
				$depth     = get_field( 'depth' );
				$capacity  = get_field( 'capacity' );
				$weight    = get_field( 'weight' );
				$sku       = get_the_title();
				?>
				<table class="table">
					<?php
					if ( $top_out_a ) {
						?>
					<tr>
						<th>Top Out</th>
						<td><?= esc_html( dimensions( $top_out_a, $top_out_b ) ); ?> mm</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-top-out.svg' ); ?>" alt="Top Out" class="img-fluid"></td>
					</tr>
						<?php
					}
					if ( $top_in_a ) {
						?>
					<tr>
						<th>Top In</th>
						<td><?= esc_html( dimensions( $top_in_a, $top_in_b ) ); ?> mm</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-top-in.svg' ); ?>" alt="Top In" class="img-fluid"></td>
					</tr>
						<?php
					}
					if ( $base_a ) {
						?>
					<tr>
						<th>Base </th>
						<td><?= esc_html( dimensions( $base_a, $base_b ) ); ?> mm</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-base.svg' ); ?>" alt="Base" class="img-fluid"></td>
					</tr>
						<?php
					}
					if ( $depth ) {
						?>
					<tr>
						<th>Depth</th>
						<td><?= esc_html( $depth ); ?> mm</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-depth.svg' ); ?>" alt="Depth" class="img-fluid"></td>
					</tr>
						<?php
					}
					if ( $capacity ) {
						?>
					<tr>
						<th>Capacity</th>
						<td><?= esc_html( $capacity ); ?> ml</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-capacity.svg' ); ?>" alt="Capacity" class="img-fluid"></td>
					</tr>
						<?php
					}
					if ( $weight ) {
						?>
					<tr>
						<th>Weight</th>
						<td><?= esc_html( $weight ); ?> g</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-weight.svg' ); ?>" alt="Weight" class="img-fluid"></td>
					</tr>
						<?php
					}
					?>
					<tr>
						<th>SKU:</th>
						<td colspan=2><?= esc_html( $sku ); ?></td>
					</tr>
					<?php
					$product_type_terms = get_the_terms( get_the_ID(), 'product_type' );

					if ( $product_type_terms && ! is_wp_error( $product_type_terms ) ) {
						?>
						<tr>
							<th>Product Type</th>
							<td colspan=2>
								<?php
								$product_type_names = wp_list_pluck( $product_type_terms, 'name' );

								$product_type_links = array_map(
									function ( $term ) {
										$term_link = get_term_link( $term, 'product_type' );
										if ( ! is_wp_error( $term_link ) ) {
											return '<a href="' . esc_url( $term_link ) . '">' . esc_html( $term ) . '</a>';
										}
										return esc_html( $term );
									},
									$product_type_names
								);

								echo wp_kses( implode( ', ', $product_type_links ), array( 'a' => array( 'href' => array() ) ) );
								?>
							</td>
						</tr>
						<?php
					}

					$edge_type_terms = get_the_terms( get_the_ID(), 'edge_type' );

					if ( $edge_type_terms && ! is_wp_error( $edge_type_terms ) ) {
						?>
						<tr>
							<th>Edge Type</th>
							<td colspan=2>
								<?php
								$edge_type_names = wp_list_pluck( $edge_type_terms, 'name' );
								echo esc_html( implode( ', ', $edge_type_names ) );
								?>
							</td>
						</tr>
						<?php
					}

					$product_category_terms = get_the_terms( get_the_ID(), 'product_category' );

					if ( $product_category_terms && ! is_wp_error( $product_category_terms ) ) {
						?>
						<tr>
							<th>Product Category</th>
							<td colspan=2>
								<?php
								$product_category_names = wp_list_pluck( $product_category_terms, 'name' );
								echo esc_html( implode( ', ', $product_category_names ) );
								?>
							</td>
						</tr>
						<?php
					}

					$usage_category_terms = get_the_terms( get_the_ID(), 'usage' );

					if ( $usage_category_terms && ! is_wp_error( $usage_category_terms ) ) {
						?>
						<tr>
							<th>Usage Category</th>
							<td colspan=2>
								<?php
								$usage_category_names = wp_list_pluck( $usage_category_terms, 'name' );
								echo esc_html( implode( ', ', $usage_category_names ) );
								?>
							</td>
						</tr>
						<?php
					}

					?>
				</table>
				<?php

				$samples_available = get_field( 'samples_available' );
				if ( is_array( $samples_available ) && in_array( 'Yes', $samples_available, true ) ) {
					?>
				<div class="my-4"><?= do_shortcode( '[lc_wishlist_button]' ); ?></div>
					<?php
				}
				?>
			</div>
			<?php
			if ( get_field( 'product_description' ) ) {
				echo '<div class="col-12 mb-4">';
				echo '<h2 class="h3">Product Description</h2>';
				echo wp_kses_post( get_field( 'product_description' ) );
				echo '</div>';
			}
			?>
		</div>
	</div>
	<?php
	get_template_part( 'page-templates/blocks/lc-cta' );
	?>
	<div class="container pt-5">
		<?php

		// Get related products based on shared categories or usage.
		$current_product_id = get_the_ID();
		$related_products   = array();

		// Get current product's categories and usage terms.
		$current_categories = get_the_terms( $current_product_id, 'product_category' );
		$current_usage      = get_the_terms( $current_product_id, 'usage' );

		$category_ids = array();
		$usage_ids    = array();

		if ( $current_categories && ! is_wp_error( $current_categories ) ) {
			$category_ids = wp_list_pluck( $current_categories, 'term_id' );
		}

		if ( $current_usage && ! is_wp_error( $current_usage ) ) {
			$usage_ids = wp_list_pluck( $current_usage, 'term_id' );
		}

		// Query for related products.
		if ( ! empty( $category_ids ) || ! empty( $usage_ids ) ) {
			$tax_query = array( 'relation' => 'OR' );

			if ( ! empty( $category_ids ) ) {
				$tax_query[] = array(
					'taxonomy' => 'product_category',
					'field'    => 'term_id',
					'terms'    => $category_ids,
				);
			}

			if ( ! empty( $usage_ids ) ) {
				$tax_query[] = array(
					'taxonomy' => 'usage',
					'field'    => 'term_id',
					'terms'    => $usage_ids,
				);
			}

			$related_query = new WP_Query(
				array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => 4,
					'orderby'        => 'rand',
					'post__not_in'   => array( $current_product_id ),
					'tax_query'      => $tax_query,
				)
			);

			if ( $related_query->have_posts() ) {
				?>
				<h3 class="mt-4">Related Products</h3>
				<div class="row related_products">
					<?php
					while ( $related_query->have_posts() ) {
						$related_query->the_post();
						$related_product_name = get_field( 'product_name' );
						$related_sku          = get_the_title();
						?>
						<div class="col-md-3 col-sm-6 mb-4">
							<a class="card h-100" href="<?= esc_url( get_permalink() ); ?>">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'medium', array( 'class' => 'card-img-top img-fluid' ) );
								} else {
									?>
									<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
										<span class="text-muted">No Image</span>
									</div>
									<?php
								}
								?>
								<div class="card-body d-flex flex-column">
									<h3 class="card-title fs-400"><?= esc_html( $related_product_name ? $related_product_name : $related_sku ); ?></h3>
									<p class="card-text small mb-1">SKU: <?= esc_html( $related_sku ); ?></p>
									<?php
									// Show all available specifications.
									$related_top_out_a = get_field( 'top_out_a' );
									$related_top_out_b = get_field( 'top_out_b' );
									$related_top_in_a  = get_field( 'top_in_a' );
									$related_top_in_b  = get_field( 'top_in_b' );
									$related_base_a    = get_field( 'base_a' );
									$related_base_b    = get_field( 'base_b' );
									$related_depth     = get_field( 'depth' );
									$related_capacity  = get_field( 'capacity' );

									if ( $related_top_out_a ) {
										echo '<p class="card-text small mb-1"><strong>Top Out:</strong> ' . esc_html( dimensions( $related_top_out_a, $related_top_out_b ) ) . ' mm</p>';
									}

									if ( $related_top_in_a ) {
										echo '<p class="card-text small mb-1"><strong>Top In:</strong> ' . esc_html( dimensions( $related_top_in_a, $related_top_in_b ) ) . ' mm</p>';
									}

									if ( $related_base_a ) {
										echo '<p class="card-text small mb-1"><strong>Base:</strong> ' . esc_html( dimensions( $related_base_a, $related_base_b ) ) . ' mm</p>';
									}

									if ( $related_depth ) {
										echo '<p class="card-text small mb-1"><strong>Depth:</strong> ' . esc_html( $related_depth ) . ' mm</p>';
									}

									if ( $related_capacity ) {
										echo '<p class="card-text small mb-1"><strong>Capacity:</strong> ' . esc_html( $related_capacity ) . ' ml</p>';
									}
									?>
								</div>
							</a>
						</div>
						<?php
					}
					wp_reset_postdata();
					?>
				</div>
				<?php
			}
		}
		?>
	</div>

</main>
<?php
get_footer();
