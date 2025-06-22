<?php
/**
 * Block template for LC Has Samples.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'samples_available',
            'value'   => 'Yes',
            'compare' => 'LIKE',
        ),
    ),
);

$query = new WP_Query( $args );
?>
<section class="has-samples">
	<div class="container pb-5">
	    <?php
		if ( $query->have_posts() ) {
			?>
			<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 lc-product-cards mb-4">
                <?php
				while ( $query->have_posts() ) {
					$query->the_post();
					$sku       = get_field( 'sku', get_the_ID() );
					$capacity  = get_field( 'capacity', get_the_ID() );
					$depth     = get_field( 'depth', get_the_ID() );
					$top_out_a = get_field( 'top_out_a', get_the_ID() );
					$top_out_b = get_field( 'top_out_b', get_the_ID() );
					$top_in_a  = get_field( 'top_in_a', get_the_ID() );
					$top_in_b  = get_field( 'top_in_b', get_the_ID() );
					$base_a    = get_field( 'base_a', get_the_ID() );
					$base_b    = get_field( 'base_b', get_the_ID() );
					?>
				<div class="col">
					<label>
						<div class="card h-100 product-card p-2">
							<input type="checkbox"
								class="wishlist-checkbox"
								data-add-to-wishlist
								data-id="<?php echo esc_attr( get_the_ID() ); ?>"
								id="wishlist-<?php the_ID(); ?>"
							>
							<?php
							if ( has_post_thumbnail( $product ) ) {
								?>
							<img src="<?= esc_url( get_the_post_thumbnail_url( $product, 'medium' ) ); ?>" class="card-img-top" alt="<?= esc_attr( $sku ); ?>">
								<?php
							} else {
								?>
							<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/default-product.png' ); ?>" class="card-img-top" alt="<?php esc_attr( $sku ); ?>">
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
						</div>
					</label>
				</div>
                	<?php
				}
				?>
            </div>
			<button type="button" id="requestSamples" class="button button-primary">Request Samples</button>
			<?php
			wp_reset_postdata();
		} else {
			?>
        <p>No products with samples available.</p>
    		<?php
		}
		?>
	</div>
</section>
<script>
document.addEventListener("DOMContentLoaded", function () {
	const btn = document.getElementById('requestSamples');
	const checkboxes = document.querySelectorAll('.wishlist-checkbox[data-add-to-wishlist]');

	function updateButtonState() {
		const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
		if (btn) btn.disabled = !anyChecked;

		checkboxes.forEach(cb => {
            const card = cb.closest('.product-card');
            if (card) {
                if (cb.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            }
        });
	}

	// Initial state
	updateButtonState();

	// Listen for changes
    checkboxes.forEach(cb => cb.addEventListener('change', updateButtonState));

    // Listen for wishlist sync event from wishlist.js
    document.addEventListener('wishlist-synced', updateButtonState);

	document.getElementById('requestSamples').addEventListener('click', function() {
		if (!this.disabled) {
			window.location.href = '/request-sample/';
		}
	});
});
</script>