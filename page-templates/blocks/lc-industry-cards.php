<?php
/**
 * Block template for LC Industry Cards.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

/* get ids of pages with parent 'industries' */

$parent_page = get_page_by_path( 'industries' );

$child_args = array(
    'post_parent' => $parent_page->ID,
    'post_type'   => 'page',
    'post_status' => 'publish'
);

$children = get_children( $child_args );

$background = get_field( 'background' );
$bgcolour   = $background ? $background : 'white';

?>
<section class="lc-industry-cards py-5 bg--<?= esc_attr( $bgcolour ); ?>">
	<div class="container">
		<h2 class="text-center"><?= esc_html( get_field( 'title' ) ); ?></h2>
		<div class="text-center mb-4"><?= wp_kses_post( get_field( 'content' ) ); ?></div>
		<div class="splide" id="industryCardsSplide">
			<div class="splide__track">
				<ul class="splide__list">
					<?php
					foreach ( $children as $child ) {
						$industry_image = get_the_post_thumbnail( $child->ID, 'large', array( 'class' => 'lc-industry-card__image' ) );
						$industry_link  = get_permalink( $child->ID );
						$industry_title = get_the_title( $child->ID );
						?>
					<li class="splide__slide">
						<a class="lc-industry-card" href="<?= esc_url( $industry_link ) ?>'">
							<?= $industry_image; ?>
							<div class="lc-industry-card__overlay"></div>
							<h3 class="lc-industry-card__title"><?= esc_html( $industry_title ) ?></h3>
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
        new Splide('#industryCardsSplide', {
            type   : 'loop',
            perPage: 4,
            gap    : '1.5rem',
			autoplay: true,
			pagination: false,
            breakpoints: {
                1200: { perPage: 3 },
                768: { perPage: 2 },
                576: { perPage: 1 }
            }
        }).mount();
    }
});
</script>