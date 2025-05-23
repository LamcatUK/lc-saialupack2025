<?php
/**
 * Template: archive-product.php
 * Description: All Products grid with filtering using ACF fields and taxonomies
 *
 * @package lc-saialupack2025
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$product_categories = get_terms(
	array(
		'taxonomy'   => 'product_category',
		'hide_empty' => true,
	)
);

$edge_types = get_terms(
	array(
		'taxonomy'   => 'edge_type',
		'hide_empty' => true,
	)
);

$fields = array( 'capacity', 'depth', 'top_out_a', 'top_out_b', 'top_in_a', 'top_in_b', 'base_a', 'base_b' );
$limits = array();

$product_ids = get_posts(
	array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	)
);

foreach ( $fields as $field ) {
	$field_values = array_map(
		function ( $post_id ) use ( $field ) {
			return (float) get_field( $field, $post_id );
		},
		$product_ids
	);

	$limits[ $field ] = array(
		'min' => min( $field_values ),
		'max' => max( $field_values ),
	);
}

$slider_fields = array(
	'capacity'  => 'Capacity (ml)',
	'depth'     => 'Depth (mm)',
	'top_out_a' => 'Top Out Length (mm)',
	'top_out_b' => 'Top Out Width (mm)',
	'top_in_a'  => 'Top In Length (mm)',
	'top_in_b'  => 'Top In Width (mm)',
	'base_a'    => 'Base Length (mm)',
	'base_b'    => 'Base Width (mm)',
);
?>
<main>
	<section class="hero">
	<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<?=
				wp_get_attachment_image(
					get_field( 'product_hero', 'option' ),
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
					<h1 data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>"><div class="hero__title">Products</div>
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
<div class="container my-5">
	<!-- Filters -->
	<div class="row g-3 mb-4 align-items-end">
		<div class="col-md-3">
			<label for="skuSearch" class="form-label">Search by SKU</label>
			<input type="text" id="skuSearch" class="form-control" placeholder="e.g. 1409pl">
		</div>
		<div class="col-md-3">
			<label for="categoryFilter" class="form-label">Category</label>
			<select id="categoryFilter" class="form-select">
				<option value="">All</option>
				<?php
				foreach ( $product_categories as $product_cat ) {
					?>
				<option value="<?= esc_attr( $product_cat->slug ); ?>"><?= esc_html( $product_cat->name ); ?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="col-md-3">
			<label for="edgeFilter" class="form-label">Edge Type</label>
			<select id="edgeFilter" class="form-select">
				<option value="">All</option>
				<?php
				foreach ( $edge_types as $edge ) {
					?>
				<option value="<?= esc_attr( $edge->slug ); ?>"><?= esc_html( $edge->name ); ?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="col-md-3 d-grid">
			<label class="form-label invisible">Reset</label>
			<button id="resetFilters" class="btn btn-secondary">Reset Filters</button>
		</div>
	</div>

	<!-- Sliders -->
<div class="accordion" id="filtersCollapse">
	<div class="accordion-item">
    	<div class="accordion-header">
      		<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="true" aria-controls="collapseFilters">
        	<strong>Product Filters</strong>
      		</button>
    	</div>
		<div id="collapseFilters" class="accordion-collapse collapse">
			<div class="accordion-body has-green-400-background-color">
				<div class="row gx-5 gy-3" id="sliderGroup">
					<?php
					foreach ( $slider_fields as $field => $label ) {
						$min = $limits[ $field ]['min'];
						$max = $limits[ $field ]['max'];
						?>
						<div class="col-md-6">
							<div class="row  border pt-5 ps-4 pe-5 pb-0 rounded has-white-background-color">
								<div class="col-md-4">
									<label for="<?= esc_attr( $field ); ?>Range" class="form-label fw-bold"><?= esc_html( $label ); ?></label>
								</div>
								<div class="col-md-8 d-flex align-items-center">
									<div class="range-inner w-100">
										<div id="<?= esc_attr( $field ); ?>Range" class="form-range-slider"></div>
										<div class="text-end small">
											Min: <span id="<?= esc_attr( $field ); ?>MinValue"><?= esc_html( $min ); ?></span>
											– Max: <span id="<?= esc_attr( $field ); ?>MaxValue"><?= esc_html( $max ); ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
    </div>
</div>

	<div class="mb-4">
	</div>

	<p id="productCount" class="fw-bold mb-3">&nbsp;</p>

<!-- Products Grid -->
	<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="productGrid">
		<?php
		while ( have_posts() ) {
			the_post();
			$sku       = get_field( 'sku' );
			$capacity  = get_field( 'capacity' );
			$depth     = get_field( 'depth' );
			$top_out_a = get_field( 'top_out_a' );
			$top_out_b = get_field( 'top_out_b' );
			$top_in_a  = get_field( 'top_in_a' );
			$top_in_b  = get_field( 'top_in_b' );
			$base_a    = get_field( 'base_a' );
			$base_b    = get_field( 'base_b' );

			$category_terms = wp_get_post_terms( get_the_ID(), 'product_category', array( 'fields' => 'slugs' ) );
			$edge_terms     = wp_get_post_terms( get_the_ID(), 'edge_type', array( 'fields' => 'slugs' ) );
			?>
			<div class="col product-card"
				data-sku="<?= esc_attr( $sku ); ?>"
				data-category="<?= esc_attr( implode( ',', $category_terms ) ); ?>"
				data-edge="<?= esc_attr( implode( ',', $edge_terms ) ); ?>"
				data-capacity="<?= esc_attr( $capacity ); ?>"
				data-depth="<?= esc_attr( $depth ); ?>"
				data-topout="<?= esc_attr( $top_out_a ); ?>"
				data-topout-width="<?= esc_attr( $top_out_b ); ?>"
				data-topin="<?= esc_attr( $top_in_a ); ?>"
				data-topin-width="<?= esc_attr( $top_in_b ); ?>"
				data-base="<?= esc_attr( $base_a ); ?>"
				data-base-width="<?= esc_attr( $base_b ); ?>">
				<a class="card h-100" href="<?= esc_url( get_permalink() ); ?>">
					<?php
					if ( has_post_thumbnail() ) {
						?>
					<img src="<?php the_post_thumbnail_url( 'medium' ); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
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
				</a>
			</div>
			<?php
		}
		?>
	</div>
</div>

<script>
const filterValues = {};

document.addEventListener('DOMContentLoaded', () => {

	const params = new URLSearchParams(window.location.search);
	document.getElementById('skuSearch').value = params.get('sku') || '';
	document.getElementById('categoryFilter').value = params.get('category') || '';
	document.getElementById('edgeFilter').value = params.get('edge') || '';
	document.getElementById('skuSearch').addEventListener('input', filterCards);
	document.getElementById('categoryFilter').addEventListener('change', filterCards);
	document.getElementById('edgeFilter').addEventListener('change', filterCards);
	document.getElementById('resetFilters').addEventListener('click', () => {
		document.getElementById('skuSearch').value = '';
		document.getElementById('categoryFilter').value = '';
		document.getElementById('edgeFilter').value = '';

		Object.entries(sliders).forEach(([key, config]) => {
			const slider = document.getElementById(config.id);
			if (slider && slider.noUiSlider) {
				slider.noUiSlider.set([config.min, config.max]);
			}
		});
	});
	const sliders = {
		<?php
		foreach ( $slider_fields as $field => $label ) {
			$min = $limits[ $field ]['min'];
			$max = $limits[ $field ]['max'];
			echo esc_js( $field ) . ': { id: \'' . esc_js( $field ) . 'Range\', min: ' . esc_js( $min ) . ', max: ' . esc_js( $max ) . " },\n";
		}
		?>
	};

	const totalSliders = Object.keys(sliders).length;
	let slidersReady = 0;

	Object.keys(sliders).forEach(key => {
		const sliderData = sliders[key];
		const slider = document.getElementById(sliderData.id);
		if (!slider) return;

		const minParam = parseFloat(params.get(`${key}_min`));
		const maxParam = parseFloat(params.get(`${key}_max`));
		const startMin = isNaN(minParam) ? sliderData.min : minParam;
		const startMax = isNaN(maxParam) ? sliderData.max : maxParam;

		noUiSlider.create(slider, {
			start: [startMin, startMax],
			connect: true,
			range: {
				min: sliderData.min,
				max: sliderData.max
			},
			step: 1,
			tooltips: true,
			format: {
				to: value => Math.round(value),
				from: value => Number(value)
			}
		});

		let firstUpdate = true;
		slider.noUiSlider.on('update', (values) => {
			document.getElementById(`${key}MinValue`).textContent = values[0];
			document.getElementById(`${key}MaxValue`).textContent = values[1];

			filterValues[key] = {
				min: parseFloat(values[0]),
				max: parseFloat(values[1])
			};

			if (firstUpdate) {
				firstUpdate = false;
				slidersReady++;
				if (slidersReady === Object.keys(sliders).length) {
					filterCards();
				}
			} else {
				filterCards();
			}
		});
	});
	});
// Ensure this is declared before use
function filterCards() {
	let visibleCount = 0;
	const skuQuery = document.getElementById('skuSearch').value.toLowerCase();
	const selectedCategory = document.getElementById('categoryFilter').value;
	const selectedEdge = document.getElementById('edgeFilter').value;

	const cards = document.querySelectorAll('.product-card');
	cards.forEach(card => {
		const sku = card.dataset.sku.toLowerCase();
		const category = card.dataset.category.split(',');
		const edge = card.dataset.edge.split(',');

		const matchesSku = sku.includes(skuQuery);
		const matchesCategory = !selectedCategory || category.includes(selectedCategory);
		const matchesEdge = !selectedEdge || edge.includes(selectedEdge);

		let matchesSliderRanges = true;
		for (const [field, range] of Object.entries(filterValues)) {
			const fieldToAttr = {
				capacity: 'capacity',
				depth: 'depth',
				top_out_a: 'topout',
				top_out_b: 'topout-width',
				top_in_a: 'topin',
				top_in_b: 'topin-width',
				base_a: 'base',
				base_b: 'base-width'
			};
			const datasetKey = fieldToAttr[field];
			const value = parseFloat(card.getAttribute(`data-${datasetKey}`) || 0);
			if (value < range.min || value > range.max) {
				matchesSliderRanges = false;
				break;
			}
		}

		const visible = matchesSku && matchesCategory && matchesEdge && matchesSliderRanges;
		card.style.display = visible ? '' : 'none';
		visibleCount += visible ? 1 : 0;
	});
	document.getElementById('productCount').textContent = `${visibleCount} product${visibleCount !== 1 ? 's' : ''} found`;
}
</script>

<?php get_footer(); ?>
