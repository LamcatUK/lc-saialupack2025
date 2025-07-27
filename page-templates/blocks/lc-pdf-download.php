<?php
/**
 * Block template for LC PDF Download.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

$classes = $block['className'] ?? 'py-5';

$pdf_id  = get_field( 'pdf_file' );
$pdf_url = $pdf_id ? wp_get_attachment_url( $pdf_id ) : '';
?>
<section class="pdf_download <?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="row g-5">
			<div class="col-md-8">
				<?= wp_kses_post( get_field( 'pdf_download_text' ) ); ?>
				<a href="<?= esc_url( $pdf_url ); ?>" target="_blank" class="button button-primary mt-4">Download PDF</a>
			</div>
			<div class="col-md-3 offset-md-1">
				<?php
				if ( wp_attachment_is( 'pdf', $pdf_id ) ) {
					$thumb_id = get_post_thumbnail_id( $pdf_id );
					if ( $thumb_id ) {
						?>
				<a href="<?= esc_url( $pdf_url ); ?>" target="_blank"><?= wp_get_attachment_image( $thumb_id, 'full', false, array( 'class' => 'img-fluid' ) ); ?></a>
						<?php
					} else {
						// Try to get PDF preview.
						$pdf_image = wp_get_attachment_image( $pdf_id, 'full', true, array( 'class' => 'img-fluid' ) );
						if ( $pdf_image ) {
							?>
				<a href="<?= esc_url( $pdf_url ); ?>" target="_blank"><?= wp_kses_post( $pdf_image ); ?></a>
							<?php
						}
					}
				}
                ?>
			</div>
		</div>
	</div>
</section>