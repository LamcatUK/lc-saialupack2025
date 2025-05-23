<?php
if (defined('WP_CLI') && WP_CLI) {
	WP_CLI::add_command('products import', function () {
		$csv_path = get_stylesheet_directory() . '/data-products.csv';

		if (!file_exists($csv_path)) {
			WP_CLI::error("CSV not found: $csv_path");
		}

		if (($handle = fopen($csv_path, 'r')) === false) {
			WP_CLI::error("Unable to open file: $csv_path");
		}

		$header = fgetcsv($handle);
		$count = 0;

		while (($row = fgetcsv($handle)) !== false) {
			$data = array_combine($header, $row);

			$post_id = wp_insert_post([
				'post_title'  => $data['sku'] ?: 'Unnamed Product',
				'post_type'   => 'product',
				'post_status' => 'publish',
			]);

			if (is_wp_error($post_id)) {
				WP_CLI::warning("Failed to insert post for SKU: {$data['sku']}");
				continue;
			}

			// Taxonomies
			if (!empty($data['category'])) {
				wp_set_object_terms($post_id, $data['category'], 'product_category');
			}
			if (!empty($data['edge'])) {
				wp_set_object_terms($post_id, $data['edge'], 'edge_type');
			}

			// ACF Fields
			update_field('sku', $data['sku'], $post_id);
			update_field('additional', $data['additional'], $post_id);
			update_field('lid', $data['lid'], $post_id);
			update_field('capacity', (float)$data['capacity'], $post_id);
			update_field('depth', (float)$data['depth'], $post_id);

			update_field('top_out_a', (float)$data['top_out_a'], $post_id);
			update_field('top_out_b', (float)$data['top_out_b'], $post_id);
			update_field('top_in_a', (float)$data['top_in_a'], $post_id);
			update_field('top_in_b', (float)$data['top_in_b'], $post_id);
			update_field('base_a', (float)$data['base_a'], $post_id);
			update_field('base_b', (float)$data['base_b'], $post_id);

			$count++;
			WP_CLI::log("Imported: {$data['sku']}");
		}

		fclose($handle);
		WP_CLI::success("Imported {$count} products.");
	});
}
