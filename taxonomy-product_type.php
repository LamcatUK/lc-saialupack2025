<?php
/**
 * Template for displaying product type taxonomy archive.
 *
 * @package lc-saialupack2025
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
$current_term = get_queried_object();

$strapline = get_the_archive_description();
$strapline = preg_replace( '/^<p>(.*)<\/p>$/s', '$1', $strapline );
?>
<main id="main">
<section class="hero">
	<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<?=
				wp_get_attachment_image(
					get_field( 'hero_image', $current_term ),
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
	<?php
	if ( have_posts() ) {
		?>
	<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="productGrid">

		<?php
		while ( have_posts() ) {
			the_post();
			$sku       = get_the_title();
			$pname     = get_field( 'product_name');
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
				<div class="card-title"><?= esc_html( $pname ); ?> <?= esc_html( $sku ); ?></div>
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
					<ul class="list-unstyled mb-0 fs-300">
						<?php
						if ( $capacity ) {
							?>
						<li><strong>Capacity:</strong> <?= esc_html( $capacity ); ?>ml</li>
							<?php
						}
						if ( $depth ) {
							?>
						<li><strong>Depth:</strong> <?= esc_html( $depth ); ?>mm</li>
							<?php
						}
						if ( $top_out_a ) {
							?>
						<li><strong>Top Out:</strong> <?= esc_html( $top_out_a . ' x ' . $top_out_b ); ?>mm</li>
							<?php
						}
						if ( $top_in_a ) {
							?>
						<li><strong>Top In:</strong> <?= esc_html( $top_in_a . ' x ' . $top_in_b ); ?>mm</li>
							<?php
						}
						if ( $base_a ) {
							?>
						<li><strong>Base:</strong> <?= esc_html( $base_a . ' x ' . $base_b ); ?>mm</li>
							<?php
						}
						?>
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
<?php

if ( have_rows( 'faqs', $current_term ) ) {
	?>
<section class="has-grey-100-background-color">
	<div class="container py-5">
		<h2>Frequently asked questions</h2>
		<div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">
	<?php
	while ( have_rows( 'faqs', $current_term ) ) {
		the_row();
		$question = get_sub_field( 'question' );
		$answer   = get_sub_field( 'answer' );
		?>
		<div class="schema-faq-section" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
          	<div class="schema-faq-question" itemprop="name"><strong><?php the_sub_field( 'question' ); ?></strong></div>
          	<div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
            	<div class="schema-faq-answer" itemprop="text"><?php the_sub_field( 'answer' ); ?></div>
          	</div>
        </div>
		<?php
	}
	?>
		</div>
	</div>
</section>
	<?php
}

get_template_part( 'page-templates/blocks/lc-cta' );
?>
</main>
<?php
get_footer();