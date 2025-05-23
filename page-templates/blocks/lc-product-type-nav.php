<?php
/**
 * Block template for LC Product Type Nav.
 *
 * @package lc-saialupack2025
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$product_types = get_terms(
	array(
		'taxonomy'   => 'product_type',
		'hide_empty' => false,
	)
);

if ( ! empty( $product_types ) && ! is_wp_error( $product_types ) ) {
	?>
	<section class="lc-product-type py-5">
		<div class="container">
			<div class="row g-4">
				<?php
				$d = 0;
				foreach ( $product_types as $product_type ) {
					?>
				<div class="col-lg-3 col-md-6" data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>">
					<a class="lc-product-type__card" href="<?= esc_url( get_term_link( $product_type ) ); ?>">
						<?php
						if ( ! empty( get_field( 'image', $product_type ) ) ) {
							echo wp_get_attachment_image(
								get_field(
									'image',
									$product_type
								),
								'large',
								false,
								array(
									'class' => 'lc-product-type__image',
								)
							);
						}
						?>
						<div class="lc-product-type__content">
							<h3 class="lc-product-type__title">
								<?= esc_html( $product_type->name ); ?>
							</h3>
							<p class="lc-product-type__description">
								<?php
								if ( ! empty( $product_type->description ) ) {
									echo wp_kses_post( $product_type->description );
								}
								?>
							</p>
							<div class="lc-product-type__link">
								View Products
							</div>
						</div>
					</a>
				</div>
					<?php
					$d += 100;
				}
				?>
			</div>
		</div>
	</section>
	<?php
} else {
	?>
	<div class="lc-product-type-nav">
		<p><?php esc_html_e( 'No product types found.', 'lc-saialupack2025' ); ?></p>
	</div>
	<?php
}
