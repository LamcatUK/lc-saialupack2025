<?php
/**
 * Template Name: Brochure Index
 *
 * @package lc-saialupack2025
 */

// phpcs:disable

defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) {
	wp_redirect( home_url() );
	exit;
}
?><style>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;700&display=swap');
body {
	margin: 0;
	padding: 0;
	font-family: 'Manrope', Arial, Helvetica, sans-serif;
	font-size: 9pt;
}
table.brochure-index {
	width: 100%;
	border-collapse: collapse;
	margin-top: 20px;
}
table.brochure-index th, table.brochure-index td {
	padding: 4px 8px;
	border-bottom: 1px solid #eee;
	text-align: left;
	font-size: 9pt;
	font-family: 'Manrope', Arial, Helvetica, sans-serif;
	font-weight: 400;
}
table.brochure-index th {
	font-weight: 700;
}
table.brochure-index td.sku {
	font-weight: 700;
}
</style>
<?php
$args = array(
	'post_type'      => 'product',
	'posts_per_page' => -1,
	'orderby'        => 'title',
	'order'          => 'ASC',
);
$products = new WP_Query( $args );
if ( $products->have_posts() ) {
	echo '<table class="brochure-index">';
	echo '<tr><th>SKU</th><th>Page</th><th>Product Type</th><th>Product Category</th></tr>';
	while ( $products->have_posts() ) {
		$products->the_post();
		$sku = get_the_title();
		$product_type_terms = get_the_terms( get_the_ID(), 'product_type' );
		$product_type = $product_type_terms && ! is_wp_error( $product_type_terms ) ? esc_html( $product_type_terms[0]->name ) : '';
		$product_cat_terms = get_the_terms( get_the_ID(), 'product_category' );
		$product_cat = $product_cat_terms && ! is_wp_error( $product_cat_terms ) ? esc_html( $product_cat_terms[0]->name ) : '';
		echo '<tr>';
		echo '<td class="sku">' . esc_html( $sku ) . '</td>';
		echo '<td></td>';
		echo '<td>' . $product_type . '</td>';
		echo '<td>' . $product_cat . '</td>';
		echo '</tr>';
	}
	echo '</table>';
	wp_reset_postdata();
}

// phpcs:enable
?>
