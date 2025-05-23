<?php
/**
 * lc-taxonomies.php
 *
 * Registers custom taxonomies for the lc-saialupack2025 WordPress theme.
 *
 * @package lc-saialupack2025
 */

function lc_register_taxonomies() {
	register_taxonomy(
		'product_category',
		'product',
		array(
			'label'             => 'Product Categories',
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
		)
	);

	register_taxonomy(
		'product_type',
		'product',
		array(
			'label'             => 'Product Types',
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'      => array( 'with_front' => false ),
		)
	);

	register_taxonomy(
		'edge_type',
		'product',
		array(
			'label'             => 'Edge Types',
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'lc_register_taxonomies' );
