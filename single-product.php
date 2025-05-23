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
	<div class="container">
		<h1><?= get_the_title(); ?></h1>
		<div class="row">
			<div class="col-md-6">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) );
				}
				?>
			</div>
			<div class="col-md-6">
				<h2>Product Information</h2>
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
				$sku       = get_field( 'sku' );
				?>
				<table class="table">
					<tr>
						<th>Top Out</th>
						<td><?= dimensions( $top_out_a, $top_out_b ); ?> mm</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-top-out.svg' ); ?>" alt="Top Out" class="img-fluid"></td>
					</tr>
					<tr>
						<th>Top In</th>
						<td><?= dimensions( $top_in_a, $top_in_b ); ?> mm</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-top-in.svg' ); ?>" alt="Top In" class="img-fluid"></td>
					</tr>
					<tr>
						<th>Base </th>
						<td><?= dimensions( $base_a, $base_b ); ?> mm</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-base.svg' ); ?>" alt="Base" class="img-fluid"></td>
					</tr>
					<tr>
						<th>Height/Depth</th>
						<td><?= esc_html( $depth ); ?> mm</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-depth.svg' ); ?>" alt="Depth" class="img-fluid"></td>
					</tr>
					<tr>
						<th>Capacity</th>
						<td><?= esc_html( $capacity ); ?> ml</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-capacity.svg' ); ?>" alt="Capacity" class="img-fluid"></td>
					</tr>
					<tr>
						<th>Weight</th>
						<td><?= esc_html( $weight ); ?> g</td>
						<td><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icon-weight.svg' ); ?>" alt="Weight" class="img-fluid"></td>
					</tr>
				</table>

				<div class="mb-2"><strong>SKU:</strong> <?= esc_html( $sku ); ?></div>
				<div class="mb-2"><strong>Product Type:</strong> <?= get_the_terms( get_the_ID(), 'product_type' ) ? implode( ', ', wp_list_pluck( get_the_terms( get_the_ID(), 'product_type' ), 'name' ) ) : ''; ?></div>
				<div class="mb-2"><strong>Edge Type:</strong> <?= get_the_terms( get_the_ID(), 'edge_type' ) ? implode( ', ', wp_list_pluck( get_the_terms( get_the_ID(), 'edge_type' ), 'name' ) ) : ''; ?></div>
				<div><strong>Category:</strong> <?= get_the_terms( get_the_ID(), 'product_category' ) ? implode( ', ', wp_list_pluck( get_the_terms( get_the_ID(), 'product_category' ), 'name' ) ) : ''; ?></div>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();