<?php
/**
 * Block template for LC Child Pages.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

$parent_page = get_the_ID();

$child_args = array(
    'post_parent' => $parent_page,
    'post_type'   => 'page',
    'post_status' => 'publish',
);

$children = get_children( $child_args );

$background = get_field( 'background' );
$bgcolour   = $background ? $background : 'white';

?>
<section class="lc-industry-cards py-5 bg--<?= esc_attr( $bgcolour ); ?>">
	<div class="container">
		<h2 class="text-center"><?= esc_html( get_field( 'title' ) ); ?></h2>
		<div class="text-center mb-4"><?= wp_kses_post( get_field( 'content' ) ); ?></div>
		<div class="lc-industry-cards-row">
			<?php
			foreach ( $children as $child ) {
				$industry_image = get_the_post_thumbnail( $child->ID, 'large', array( 'class' => 'lc-industry-card__image' ) );
				$industry_link  = get_permalink( $child->ID );
				$industry_title = get_the_title( $child->ID );
				?>
			<a class="lc-industry-card" href="<?= esc_url( $industry_link ); ?>'">
				<?= wp_kses_post( $industry_image ); ?>
				<div class="lc-industry-card__overlay"></div>
				<h3 class="lc-industry-card__title"><?= esc_html( $industry_title ); ?></h3>
			</a>
				<?php
			}
			?>
		</div>
	</div>
</section>