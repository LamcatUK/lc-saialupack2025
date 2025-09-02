<?php
/**
 * File: lc-taxonomies.php
 * Description: Registers custom taxonomies for the theme.
 * Theme: Sai Alupack 2025
 *
 * @package lc-saialupack2025
 */

/**
 * Registers custom ACF blocks for the theme.
 *
 * This function is used to define and register Advanced Custom Fields (ACF) blocks
 * that can be used within the WordPress block editor. Each block can have its own
 * settings, templates, and styles.
 *
 * @return void
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
			'label'               => 'Product Types',
			'hierarchical'        => true,
			'show_ui'             => true,
			'show_admin_column'   => true,
			'show_in_rest'        => true,
			'show_in_graphql'     => true,
			'graphql_single_name' => 'ProductType',
			'graphql_plural_name' => 'ProductTypes',
			'rewrite'             => array(
				'slug'       => 'type',
				'with_front' => false,
			),
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

	register_taxonomy(
		'usage',
		'product',
		array(
			'label'             => 'Usage Category',
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
		)
	);
}

add_action( 'init', 'lc_register_taxonomies' );
