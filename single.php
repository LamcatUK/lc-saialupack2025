<?php
/**
 * Template for displaying single posts.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;
get_header();

?>
<main id="main" class="blog">
	<?php
	$content = get_the_content();
	$blocks  = parse_blocks( $content );
	$sidebar = array();
	$after;
	?>
	<section class="breadcrumbs container-xl">
	<?php
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
	}
	?>
	</section>
	<?php

	$hero = get_field( 'show_hero_image' ) === 'Yes' ? true : ( get_field( 'show_hero_image' ) === 'No' ? false : true );

	$img = get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'blog__image' ) ) ? get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'blog__image' ) ) : '';
	?>
	<div class="container-xl">
		<div class="row g-4 pb-4">
			<div class="col-lg-9">
				<h1 class="blog__title"><?= esc_html( get_the_title() ); ?></h1>
				<?= $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="blog__meta">
				<?php
				$count = estimate_reading_time_in_minutes( get_the_content(), 200, true, true ) ?? null;
				if ( $count ) {
					echo wp_kses_post( $count );
				}
				?>
				</div>
				<?php
				echo apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				$prev = get_previous_post();
				$next = get_next_post();

				// Determine the correct Bootstrap class for alignment.
				if ( $prev && $next ) {
					$justify_class = 'justify-content-between'; // Both buttons → space them apart.
				} elseif ( $next ) {
					$justify_class = 'justify-content-end'; // Only Next → Align right.
				} else {
					$justify_class = 'justify-content-start'; // Only Previous → Align left.
				}
				?>

				<div class="post-navigation mt-4 d-flex <?= esc_attr( $justify_class ); ?>">
					<?php if ( $prev ) { ?>
						<a href="<?= esc_url( get_permalink( $prev ) ); ?>" class="button button--sm">← Previous</a>
					<?php } ?>
					<?php if ( $next ) { ?>
						<a href="<?= esc_url( get_permalink( $next ) ); ?>" class="button button--sm">Next →</a>
					<?php } ?>
				</div>

            </div>
            <div class="col-lg-3">
                <div class="sidebar">
					<?php
					$cats = get_the_category();
					$ids  = wp_list_pluck( $cats, 'term_id' );
					$r    = new WP_Query(
						array(
							'category__in'   => $ids,
							'posts_per_page' => 3,
							'post__not_in'   => array( get_the_ID() ),
						)
					);

					if ( $r->have_posts() ) {
						?>
					<div class="h5">Related News</div>
						<div class="related">
						<?php
						while ( $r->have_posts() ) {
							$r->the_post();
							?>
								<a class="related__card"
									href="<?= esc_url( get_the_permalink() ); ?>">
									<img src="<?= esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>"
										alt="" class="related__image">
									<div class="related__content">
										<h3 class="related__title">
											<?= esc_attr( get_the_title() ); ?>
										</h3>
									</div>
								</a>
							<?php
						}
						?>
					</div>
	    				<?php
					}
					?>
					<a href="/contact-us/" class="blog_cta">
						<img src="<?= esc_url( get_stylesheet_directory_uri() ); ?>/img/default-blog.jpg"
							alt="" class="blog_cta__image">
						<div class="blog_cta__content">
							<h3 class="blog_cta__title">
								Contact Us Today
							</h3>
						</div>
					</a>
				</div>
            </div>
        </div>
    </div>
</main>
<?php
get_footer();
?>