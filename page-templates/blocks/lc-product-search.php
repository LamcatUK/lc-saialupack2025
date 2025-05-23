<?php
/**
 * Block template for LC Product Search.
 *
 * @package lc-saialupack2025
 */
?>
<section class="product-search py-5">
	<img class="product-search__background" src="<?= esc_url( get_stylesheet_directory_uri() . '/img/product-search-bg.jpg' ); ?>" alt="Product Search Background">
	<div class="product-search__overlay"></div>
	<div class="container">
		<h2 class="text-center">Quick Product Locator</h2>
		<form action="/products/" method="get" class="quick-product-locator mb-4 p-4 rounded">
			<div class="row gx-5 gy-3 align-items-end">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-12">
							<label for="sku" class="form-label">Search by SKU</label>
							<input type="text" name="sku" id="sku" class="form-control" placeholder="e.g. 546rpl">
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-6">
							<label for="category" class="form-label">Category</label>
							<select name="category" id="category" class="form-select">
								<option value="">Any</option>
								<?php
								foreach ( get_terms(
									array(
										'taxonomy'   => 'product_category',
										'hide_empty' => true,
									)
								) as $product_cat ) {
									?>
									<option value="<?= esc_attr( $product_cat->slug ); ?>"><?= esc_html( $product_cat->name ); ?></option>
									<?php
								}
								?>
							</select>
						</div>
						<div class="col-md-6">
							<label for="edge" class="form-label">Edge Type</label>
							<select name="edge" id="edge" class="form-select">
								<option value="">Any</option>
								<?php
								foreach ( get_terms(
									array(
										'taxonomy'   => 'edge_type',
										'hide_empty' => true,
									)
								) as $edge ) {
									?>
									<option value="<?= esc_attr( $edge->slug ); ?>"><?= esc_html( $edge->name ); ?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<label class="form-label">Capacity (ml)</label>
					<div class="input-group">
						<input type="number" name="capacity_min" class="form-control" placeholder="Min">
						<span class="input-group-text">–</span>
						<input type="number" name="capacity_max" class="form-control" placeholder="Max">
					</div>
				</div>
				<div class="col-md-6">
					<label class="form-label">Top Width (mm)</label>
					<div class="input-group">
						<input type="number" name="top_out_b_min" class="form-control" placeholder="Min">
						<span class="input-group-text">–</span>
						<input type="number" name="top_out_b_max" class="form-control" placeholder="Max">
					</div>
				</div>
				<div class="col-md-12 text-center mt-4">
					<label class="form-label d-none">Search</label>
					<button type="submit" class="button">Find Products</button>
				</div>
			</div>
		</form>
	</div>
</section>
