<?php
/**
 * File: lc-blocks.php
 * Description: Registers custom ACF blocks and modifies Gutenberg core blocks for the theme.
 * Author: Your Name
 * Theme: Valewood Bathrooms
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
function acf_blocks() {
    if ( function_exists( 'acf_register_block_type' ) ) {

		// INSERT NEW BLOCKS HERE.

        acf_register_block_type(
            array(
                'name'            => 'lc_child_pages',
                'title'           => __( 'LC Child Pages' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-child-pages.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_faqs',
                'title'           => __( 'LC FAQs' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-faqs.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_has_samples',
                'title'           => __( 'LC Has Samples' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-has-samples.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_brochure_cta',
                'title'           => __( 'LC Brochure CTA' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-brochure-cta.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_popular_products',
                'title'           => __( 'LC Popular Products' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-popular-products.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_industry_cards_no_slider',
                'title'           => __( 'LC Industry Cards (No Slider)' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-industry-cards-no-slider.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_industry_cards',
                'title'           => __( 'LC Industry Cards' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-industry-cards.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_wide_map',
                'title'           => __( 'LC Wide Map' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-wide-map.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_latest_news',
                'title'           => __( 'LC Latest News' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-latest-news.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_product_search',
                'title'           => __( 'LC Product Search' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-product-search.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_product_type_nav',
                'title'           => __( 'LC Product Type Nav' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-product-type-nav.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_hero',
                'title'           => __( 'LC Hero' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-hero.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_contact',
                'title'           => __( 'LC Contact' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-contact.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_text_image',
                'title'           => __( 'LC Text Image' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-text-image.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_cta',
                'title'           => __( 'LC CTA' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-cta.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

        acf_register_block_type(
            array(
                'name'            => 'lc_intro',
                'title'           => __( 'LC Intro' ),
                'category'        => 'layout',
                'icon'            => 'cover-image',
                'render_template' => 'page-templates/blocks/lc-intro.php',
                'mode'            => 'edit',
                'supports'        => array(
                    'mode'      => false,
                    'anchor'    => true,
                    'className' => true,
                    'align'     => true,
                ),
            )
        );

    }
}
add_action( 'acf/init', 'acf_blocks' );


/**
 * Modifies Gutenberg core block types.
 *
 * Adds a render callback to specific core blocks to wrap their content
 * in a custom container.
 *
 * @param array  $args Arguments for registering a block type.
 * @param string $name Name of the block type.
 * @return array Modified arguments for the block type.
 */
function core_image_block_type_args( $args, $name ) {
	if ( 'core/paragraph' === $name ) {
		$args['render_callback'] = 'modify_core_add_container';
    }
	if ( 'core/heading' === $name ) {
		$args['render_callback'] = 'modify_core_add_container';
    }
	if ( 'core/list' === $name ) {
		$args['render_callback'] = 'modify_core_add_container';
    }
	return $args;
}
add_filter( 'register_block_type_args', 'core_image_block_type_args', 10, 3 );

/**
 * Wraps the content of specific core blocks in a custom container.
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Block content.
 * @return string Modified block content wrapped in a container.
 */
function modify_core_add_container( $attributes, $content ) {
	if ( ! empty( $GLOBALS['skip_core_block_wrapping'] ) ) {
		return $content;
	}

	ob_start();
	?>
	<div class="container">
		<?php echo wp_kses_post( $content ); ?>
	</div>
	<?php
	return ob_get_clean();
}
