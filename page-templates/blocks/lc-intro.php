<?php
/**
 * Block template for LC Intro.
 *
 * @package lc-valewood2025
 */

defined( 'ABSPATH' ) || exit;

$button = get_field( 'button' );
$image  = get_field( 'image' );
$center = $image ? 'text-start' : 'text-center';
?>
<section class="section-intro">
	<div class="container py-5 <?= esc_attr( $center ); ?>" data-aos="fade-up">
		<?php
		if ( $image ) {
			?>
   		<h2 class="text--plum"><?= esc_html( get_field( 'title' ) ); ?></h2>
		<div class="row g-5">
			<div class="col-md-7">
				<p class="lead fs-500 text--dark <?= esc_attr( $center ); ?>"><?= esc_html( get_field( 'lead' ) ); ?></p>
				<p class="fs-400 text--grey-400"><?= esc_html( get_field( 'content' ) ); ?></p>
				<?php
				if ( $button ) {
					?>
					<a href="<?= esc_url( $button['url'] ); ?>" class="button button-outline mt-3"><?= esc_html( $button['title'] ); ?></a>
					<?php
				}
				?>
			</div>
			<div class="col-md-5">
				<?= wp_get_attachment_image( get_field( 'image' ), 'large', false, array( 'class' => 'img-fluid' ) ); ?>
			</div>
		</div>
			<?php
		} else {
			?>
		<h2 class="text--plum"><?= esc_html( get_field( 'title' ) ); ?></h2>
		<p class="lead fs-500 text--dark w-constrained mx-auto"><?= esc_html( get_field( 'lead' ) ); ?></p>
		<p class="fs-400 text--grey-400 w-constrained mx-auto"><?= wp_kses_post( get_field( 'content' ) ); ?></p>	
			<?php
			if ( $button ) {
				?>
		<a href="<?= esc_url( $button['url'] ); ?>" class="button button-outline mt-3"><?= esc_html( $button['title'] ); ?></a>
				<?php
			}
		}
		?>
	</div>
</section>