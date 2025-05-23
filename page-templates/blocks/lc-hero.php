<?php
/**
 * Block template for LC Hero.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

$images        = get_field( 'images' );
$heading       = get_field( 'heading' );
$strapline     = get_field( 'strapline' );
$button_link_1 = get_field( 'button_1' );
$button_link_2 = get_field( 'button_2' );

if ( ! $images ) {
	return;
}

$block_id = 'lc-hero-' . $block['id'];
?>
<section class="hero" id="<?php echo esc_attr( $block_id ); ?>">
	<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
		<div class="carousel-inner">
			<?php
			$active = 'active';
			foreach ( $images as $index => $image ) {
				?>
				<div class="carousel-item <?php echo $active; ?>">
					<?=
					wp_get_attachment_image(
						$image['ID'],
						'full',
						false,
						array(
							'class' => 'd-block w-100 h-100 object-fit-cover',
							'style' => 'object-position: center;',
						),
					);
					?>
				</div>
				<?php
				$active = '';
			}
			?>
		</div>
	</div>

	<div class="hero__overlay"></div>

	<div class="hero__content d-flex align-items-center">
		<div class="container">
			<div class="row">
				<div class="col-md-6 text-white">
					<?php
					$d = 0;
					if ( is_front_page() ) {
						echo '<img data-aos="fade" src="' . esc_url( get_stylesheet_directory_uri() . '/img/sai-logo--wo.svg' ) . '" alt="Sai Alupack Logo" class="hero__logo" />';
						$d += 100;
					}
					?>
					<h1 data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>"><div class="hero__title"><?= esc_html( $heading ); ?></div>
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
						<?php
						if ( $button_link_1 ) {
							?>
						<span data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>">
							<a class="button button-outline hero__button mt-3"
								href="<?= esc_url( $button_link_1['url'] ); ?>"
								target="<?= esc_attr( $button_link_1['target'] ?? '_self' ); ?>">
								<?= esc_html( $button_link_1['title'] ); ?>
							</a>
						</span>
							<?php
							$d += 100;
						}
						if ( $button_link_2 ) {
							?>
						<span data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>">
							<a class="button button-outline hero__button mt-3"
								href="<?= esc_url( $button_link_2['url'] ); ?>"
								target="<?= esc_attr( $button_link_2['target'] ?? '_self' ); ?>">
								<?= esc_html( $button_link_2['title'] ); ?>
							</a>
						</span>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
