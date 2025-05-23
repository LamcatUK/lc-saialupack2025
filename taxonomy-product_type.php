<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
$term = get_queried_object();
?>
<main id="main">
<section class="hero">
	<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<?=
				wp_get_attachment_image(
					get_field( 'hero_image', $term ),
					'full',
					false,
					array(
						'class' => 'd-block w-100 h-100 object-fit-cover',
						'style' => 'object-position: center;',
					),
				);
				?>
			</div>
		</div>
	</div>
	<div class="hero__overlay"></div>
	<div class="hero__content d-flex align-items-center">
		<div class="container">
			<div class="row">
				<div class="col-md-6 text-white">
					<?php
					$d = 0;
					?>
					<h1 data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>"><div class="hero__title"><?= single_term_title(); ?></div>
					<?php
					$d += 100;
					if ( $strapline ) {
						?>
					<p data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>" class="hero__strapline"><?= esc_html( $strapline ); ?></p></h1>
						<?php
						$d += 100;
					}
					?>
					<div class="hero__buttons d-flex flex-wrap gap-2">
						<span data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>">
							<a class="button button-outline hero__button mt-3"
								href="/contact-us/"
								target="_self'">
								Contact Us
							</a>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
	<div class="container pb-5">
		<div class="yoast-breadcrumbs">
		<?php
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		}
		?>
		</div>
    <div><?php the_archive_description(); ?></div>
	<?php
	if ( have_posts() ) {
		?>
	<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="productGrid">

		<?php
		while ( have_posts() ) {
			the_post();
			$sku       = get_field( 'sku' );
			$capacity  = get_field( 'capacity' );
			$depth     = get_field( 'depth' );
			$top_out_a = get_field( 'top_out_a' );
			$top_out_b = get_field( 'top_out_b' );
			$top_in_a  = get_field( 'top_in_a' );
			$top_in_b  = get_field( 'top_in_b' );
			$base_a    = get_field( 'base_a' );
			$base_b    = get_field( 'base_b' );
			?>
		<div class="col product-card">
			<a class="card h-100" href="<?php the_permalink(); ?>">
				<?php
				if ( has_post_thumbnail() ) {
					?>
				<img src="<?php the_post_thumbnail_url( 'medium' ); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
					<?php
				} else {
					?>
				<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/default-product.png' ); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
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
		</div>
			<?php
		}
		?>
	</div>
		<?php
	} else {
		?>
	<p>No products found for this type.</p>
		<?php
	}
	?>
	</div>
</main>
<?php get_footer(); ?>
