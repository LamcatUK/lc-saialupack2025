<?php
/**
 * Block template for LC Brochure CTA.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

$bg = get_field( 'background' );

$form_id = get_field( 'form_id' );
if ( ! $form_id ) {
	return;
}
?>
<section class="lc-brochure-cta py-5">
	<?= wp_get_attachment_image( $bg, 'full', false, array( 'class' => 'lc-brochure-cta__background' ) ); ?>
	<div class="lc-brochure-cta__overlay"></div>
	<div class="container" data-aos="fade">
		<div class="inner">
			<div class="row g-4 gy-md-0">
				<div class="col-md-8">
					<h2 class="mb-4 text-white"><?= esc_html( get_field( 'title' ) ); ?></h2>
					<div><?= wp_kses_post( get_field( 'content' ) ); ?></div>
				</div>
				<div class="col-md-4 my-auto text-center">
					<button type="button" class="button button-primary" data-bs-toggle="modal" data-bs-target="#brochureModal">
						<?= esc_html( get_field( 'button_label' ) ); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="modal fade" id="brochureModal" tabindex="-1" aria-labelledby="brochureModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
    	<div class="modal-content">
      		<div class="modal-header">
				<div class="modal-title fs-5" id="brochureModalLabel"><?= esc_html( get_field( 'modal_title' ) ); ?></div>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<div class="modal-body">
				<?php
				if ( get_field( 'form_intro' ) ) {
					?>
        		<p><?= esc_html( get_field( 'form_intro' ) ); ?></p>
					<?php
				}
				?>
				<?= do_shortcode( '[contact-form-7 id="' . $form_id . '" title="Request Brochure"]' ); ?>
      		</div>
		</div>
  	</div>
</div>


