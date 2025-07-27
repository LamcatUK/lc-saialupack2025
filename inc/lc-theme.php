<?php
/**
 * LC Theme Functions
 *
 * This file contains theme-specific functions and customizations for the Valewood Bathrooms theme.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

require_once LC_THEME_DIR . '/inc/lc-utility.php';
require_once LC_THEME_DIR . '/inc/lc-blocks.php';
require_once LC_THEME_DIR . '/inc/lc-news.php';
require_once LC_THEME_DIR . '/inc/lc-posttypes.php';
require_once LC_THEME_DIR . '/inc/lc-taxonomies.php';

/* require_once LC_THEME_DIR . '/inc/cli-import-products.php'; */


if ( ! defined( 'LC_COLOUR_PALETTE' ) ) {
	define(
        'LC_COLOUR_PALETTE',
        array(
            'black'     => '#0f0f0f',
            'white'     => '#ffffff',
            'green-100' => '#d3ebe5',
            'green-400' => '#45b8ac',
            'green-700' => '#277b6c',
            'green-900' => '#173f3d',
            'coral-400' => '#ff6b5e',
            'grey-100'  => '#efefef',
            'grey-400'  => '#d0d3d4',
        )
    );
}

// Remove unwanted SVG filter injection WP.
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

/**
 * Removes the comment-reply.min.js script from the footer.
 */
function remove_comment_reply_header_hook() {
    wp_deregister_script( 'comment-reply' );
}
add_action( 'init', 'remove_comment_reply_header_hook' );


/**
 * Removes the Comments menu from the WordPress admin dashboard.
 */
function remove_comments_menu() {
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'remove_comments_menu' );


/**
 * Removes specific page templates from the available templates list.
 *
 * @param array $page_templates The list of available page templates.
 * @return array The modified list of page templates.
 */
function child_theme_remove_page_template( $page_templates ) {
    unset( $page_templates['page-templates/blank.php'], $page_templates['page-templates/empty.php'], $page_templates['page-templates/left-sidebarpage.php'], $page_templates['page-templates/right-sidebarpage.php'], $page_templates['page-templates/both-sidebarspage.php'] );
    return $page_templates;
}
add_filter( 'theme_page_templates', 'child_theme_remove_page_template' );

/**
 * Removes support for specific post formats in the theme.
 */
function remove_understrap_post_formats() {
    remove_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
}
add_action( 'after_setup_theme', 'remove_understrap_post_formats', 11 );

if ( function_exists( 'acf_add_options_page' ) ) {
    acf_add_options_page(
        array(
            'page_title' => 'Site-Wide Settings',
            'menu_title' => 'Site-Wide Settings',
            'menu_slug'  => 'theme-general-settings',
            'capability' => 'edit_posts',
        )
    );
}

/**
 * Initializes theme widgets and menus.
 *
 * This function registers navigation menus, unregisters sidebars, and sets up theme support for custom colors and editor color palette.
 */
function widgets_init() {

    register_nav_menus(
		array(
			'primary_nav'  => __( 'Primary Nav', 'lc-saialupack2025' ),
			'footer_menu1' => __( 'Footer Nav 1', 'lc-saialupack2025' ),
			'footer_menu2' => __( 'Footer Nav 2', 'lc-saialupack2025' ),
			'footer_menu3' => __( 'Footer Nav 3', 'lc-saialupack2025' ),
	    )
	);

    unregister_sidebar( 'hero' );
    unregister_sidebar( 'herocanvas' );
    unregister_sidebar( 'statichero' );
    unregister_sidebar( 'left-sidebar' );
    unregister_sidebar( 'right-sidebar' );
    unregister_sidebar( 'footerfull' );
    unregister_nav_menu( 'primary' );

    add_theme_support( 'disable-custom-colors' );
}
add_action( 'widgets_init', 'widgets_init', 11 );

add_filter(
    'acf_editor_palette/colors',
    function () {
	    $acf_palette = array();

        foreach ( LC_COLOUR_PALETTE as $colour ) {
            $acf_palette[] = $colour['color'];
            $acf_palette[] = $colour['name'];
        }

    	return $acf_palette;
    }
);

remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );


/**
 * Registers a custom dashboard widget for the WordPress admin dashboard.
 *
 * This widget displays a custom message and contact link for Lamcat support.
 */
function register_lc_dashboard_widget() {
	wp_add_dashboard_widget(
		'lc_dashboard_widget',
        'Lamcat',
        'lc_dashboard_widget_display'
    );
}
add_action( 'wp_dashboard_setup', 'register_lc_dashboard_widget' );

/**
 * Displays the content of the custom Lamcat dashboard widget.
 *
 * This function outputs the HTML for the Lamcat dashboard widget,
 * including an image, a contact button, and a message for the user.
 */
function lc_dashboard_widget_display() {
	?>
    <div style="display: flex; align-items: center; justify-content: space-around;">
        <img style="width: 50%;"
            src="<?= esc_url( get_stylesheet_directory_uri() . '/img/lc-full.jpg' ); ?>">
        <a class="button button-primary" target="_blank" rel="noopener nofollow noreferrer"
            href="mailto:hello@lamcat.co.uk/">Contact</a>
    </div>
    <div>
        <p><strong>Thanks for choosing Lamcat!</strong></p>
        <hr>
        <p>Got a problem with your site, or want to make some changes & need us to take a look for you?</p>
        <p>Use the link above to get in touch and we'll get back to you ASAP.</p>
    </div>
	<?php
}


/**
 * Filters Yoast SEO breadcrumbs to remove 'resources' from the path.
 *
 * @param array $links The breadcrumb links.
 * @return array Modified breadcrumb links.
 */
function remove_resources_from_breadcrumbs( $links ) {
	return array_filter(
		$links,
		function ( $link ) {
			// Remove any breadcrumb item with 'resources' as the URL part or text.
			return ! (
				( isset( $link['url'] ) && false !== strpos( $link['url'], '/resources/' ) ) ||
				( isset( $link['text'] ) && 'resources' === strtolower( $link['text'] ) )
			);
		}
	);
}
add_filter( 'wpseo_breadcrumb_links', 'remove_resources_from_breadcrumbs' );

// phpcs:disable

// add_filter('wpseo_breadcrumb_links', function( $links ) {
//     global $post;
//     if ( is_singular( 'post' ) ) {
//         $t = get_the_category($post->ID);
//         $breadcrumb[] = array(
//             'url' => '/guides/',
//             'text' => 'Guides',
//         );

//         array_splice( $links, 1, -2, $breadcrumb );
//     }
//     return $links;
// }
// );
// remove discussion metabox
// function cc_gutenberg_register_files()
// {
//     // script file
//     wp_register_script(
//         'cc-block-script',
//         get_stylesheet_directory_uri() . '/js/block-script.js', // adjust the path to the JS file
//         array('wp-blocks', 'wp-edit-post')
//     );
//     // register block editor script
//     register_block_type('cc/ma-block-files', array(
//         'editor_script' => 'cc-block-script'
//     ));
// }
// add_action('init', 'cc_gutenberg_register_files');
// phpcs:enable

/**
 * Filters the excerpt content.
 *
 * This function ensures that the excerpt content is returned as is
 * when in the admin area or when the post ID is not available.
 *
 * @param string $post_excerpt The post excerpt.
 * @return string The filtered post excerpt.
 */
function understrap_all_excerpts_get_more_link( $post_excerpt ) {
    if ( is_admin() || ! get_the_ID() ) {
        return $post_excerpt;
    }
    return $post_excerpt;
}

/**
 * Removes shortcodes from the content in search results.
 *
 * This function ensures that shortcodes are stripped from the content
 * when displaying search results.
 *
 * @param string $content The content to filter.
 * @return string The filtered content without shortcodes.
 */
function wpdocs_remove_shortcode_from_index( $content ) {
	if ( is_search() ) {
		$content = strip_shortcodes( $content );
    }
    return $content;
}
add_filter( 'the_content', 'wpdocs_remove_shortcode_from_index' );

/**
 * Enqueues theme styles and scripts.
 *
 * This function registers and enqueues external styles and scripts
 * such as AOS animations and Splide.js for the theme.
 */
function lc_theme_enqueue() {
    $the_theme = wp_get_theme();
    wp_enqueue_style( 'aos-style', 'https://unpkg.com/aos@2.3.1/dist/aos.css', array(), '2.3.1', 'all' );
    wp_enqueue_script( 'aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '2.3.1', true );

    wp_enqueue_style( 'splide-css', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css', array(), '4.1.3' );
    wp_enqueue_script( 'splide-js', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js', array(), '4.1.3', true );
    wp_enqueue_style( 'lightbox-stylesheet', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'lightbox-scripts', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', array(), $the_theme->get( 'Version' ), true );
    wp_enqueue_script( 'masonry-scripts', 'https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js', array(), $the_theme->get( 'Version' ), true );
    wp_enqueue_script( 'imagesloaded-scripts', 'https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js', array(), $the_theme->get( 'Version' ), true );

	// phpcs:disable
    // wp_deregister_script( 'jquery' );
	// wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.3.min.js', array(), null, true);
    // wp_enqueue_style('lightbox-stylesheet', get_stylesheet_directory_uri() . '/css/lightbox.min.css', array(), $the_theme->get('Version'));
    // wp_enqueue_script('lightbox-scripts', get_stylesheet_directory_uri() . '/js/lightbox.min.js', array(), $the_theme->get('Version'), true);
    // wp_enqueue_script('lightbox-scripts', get_stylesheet_directory_uri() . '/js/lightbox-plus-jquery.min.js', array(), $the_theme->get('Version'), true);
    // wp_enqueue_script('parallax', get_stylesheet_directory_uri() . '/js/parallax.min.js', array('jquery'), null, true);
	// phpcs:enable
}
add_action( 'wp_enqueue_scripts', 'lc_theme_enqueue' );


add_theme_support( 'disable-theme-editor' );

add_action(
	'admin_init',
	function () {
		if ( current_theme_supports( 'disable-theme-editor' ) ) {
			define( 'DISALLOW_FILE_EDIT', true );
		}
	}
);

/**
 * Adds custom menu items to the primary navigation menu.
 *
 * This function appends custom menu items, such as contact phone and email links,
 * to the primary navigation menu when it is being rendered.
 *
 * @param string $items The HTML list content for the menu items.
 * @param object $args  An object containing wp_nav_menu() arguments.
 * @return string The modified HTML list content for the menu items.
 */
function add_custom_menu_item( $items, $args ) {
    if ( $args->theme_location == 'primary_nav' ) {
        $new_item  = '<li class="d-lg-none menu-item nav-item">' . do_shortcode( '[contact_phone icon="true" class="nav-link"]' ) . '</li>';
        $new_item .= '<li class="d-lg-none menu-item nav-item">' . do_shortcode( '[contact_email icon="true" class="nav-link" text="Email Us"]' ) . '</li>';
        $items    .= $new_item;
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'add_custom_menu_item', 10, 2 );

/**
 * Extracts the first heading, introductory paragraphs, and the rest of the content from the given HTML content.
 *
 * This function parses the provided HTML content and separates it into three parts:
 * - The first heading (h1-h6).
 * - Introductory paragraphs following the first heading.
 * - The remaining content after the introductory paragraphs.
 *
 * @param string $content The HTML content to process.
 * @return array An associative array with keys:
 *               - 'first_heading': The first heading as HTML.
 *               - 'intro_paragraphs': The introductory paragraphs as HTML.
 *               - 'rest_content': The remaining content as HTML.
 */
function extract_intro_content( $content ) {
    $result = array(
        'first_heading'    => '',
        'intro_paragraphs' => '',
        'rest_content'     => '',
	);

    libxml_use_internal_errors( true );
    $doc = new DOMDocument();

    $map = array(
        0x80,
        0xFFFF, // Encode characters from 128 to 65535.
        0,
        0xFFFF, // Encode all characters.
	);

    $encoded_content = mb_encode_numericentity( $content, $map, 'UTF-8' );
    $doc->loadHTML( '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $encoded_content );

    libxml_clear_errors();

    $body = $doc->getElementsByTagName( 'body' )->item( 0 );
	 // phpcs:ignore;
    $children = $body->childNodes;

    $found_heading      = false;
    $collect_paragraphs = false;
    $rest_fragments     = array();

    foreach ( $children as $node ) {
        // Skip non-element nodes (e.g. text nodes, comments).
		// phpcs:ignore
        if ( $node->nodeType !== XML_ELEMENT_NODE ) {
            continue;
        }

		// phpcs:ignore
        $tag = $node->nodeName;

        if ( ! $found_heading && in_array( $tag, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
            $result['first_heading'] = $doc->saveHTML( $node );
            $found_heading           = true;
            $collect_paragraphs      = true;
            continue;
        }

        if ( $collect_paragraphs ) {
            if ( 'p' === $tag ) {
                $result['intro_paragraphs'] .= $doc->saveHTML( $node );
                continue;
            } elseif ( in_array( $tag, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
                // Stop collecting once the next heading is found.
                $collect_paragraphs = false;
            }
        }

        if ( ! $collect_paragraphs && $found_heading ) {
            $rest_fragments[] = $doc->saveHTML( $node );
        }
    }

    $result['rest_content'] = implode( '', $rest_fragments );
    return $result;
}


add_filter( 'wpcf7_autop_or_not', '__return_false' );


add_filter(
    'wpcf7_validate',
    function ( $result, $tags ) {
        $submission = WPCF7_Submission::get_instance();
        if ( ! $submission ) {
            return $result;
        }

        $data = $submission->get_posted_data();

        // Honeypot field check (spam detection).
        if ( ! empty( $data['your-website'] ) ) {
            $result->invalidate( 'your-website', 'Spam detected.' );
        }

        // Check for excessive links in the message field.
        if ( isset( $data['your-message'] ) && substr_count( $data['your-message'], 'http' ) > 3 ) {
            $result->invalidate( 'your-message', 'Too many links detected. Possible spam.' );
        }

        // Validate email format.
        if ( isset( $data['your-email'] ) && ! filter_var( $data['your-email'], FILTER_VALIDATE_EMAIL ) ) {
            $result->invalidate( 'your-email', 'Invalid email address.' );
        }

        // Time-based spam check (ensure form isn't submitted too quickly).
        $start_time = isset( $_SESSION['form_start_time'] ) ? $_SESSION['form_start_time'] : 0;
        $current_time = time();
        if ( $current_time - $start_time < 5 ) { // Less than 5 seconds.
            $result->invalidate( 'your-message', 'Form submitted too quickly. Possible spam.' );
        }

        return $result;
    },
    10,
    2
);

// Start time for form submission (to be set when the form is loaded).
add_action(
    'wpcf7_enqueue_scripts',
    function () {
        if ( session_status() === PHP_SESSION_NONE ) {
            session_start();
        }
        $_SESSION['form_start_time'] = time();
    }
);

// Ensure session starts early in the WordPress lifecycle.
add_action(
    'init',
    function () {
        if ( session_status() === PHP_SESSION_NONE ) {
            session_start();
        }
    }
);

// Use cookies as a fallback for form start time.
add_action(
    'wpcf7_enqueue_scripts',
    function () {
        if ( session_status() === PHP_SESSION_NONE ) {
            session_start();
        }
        $start_time = time();
        $_SESSION['form_start_time'] = $start_time;
        setcookie( 'form_start_time', $start_time, time() + 3600, '/' ); // Cookie valid for 1 hour.
    }
);

// Update validation to check both session and cookie.
add_filter(
    'wpcf7_validate',
    function ( $result, $tags ) {
        $submission = WPCF7_Submission::get_instance();
        if ( ! $submission ) {
            return $result;
        }

        $data = $submission->get_posted_data();

        $start_time   = isset( $_SESSION['form_start_time'] ) ? intval( $_SESSION['form_start_time'] ) : ( isset( $_COOKIE['form_start_time'] ) ? intval( $_COOKIE['form_start_time'] ) : 0 );
        $current_time = time();
        if ( $current_time - $start_time < 5 ) { // Less than 5 seconds.
            $result->invalidate( 'your-message', 'Form submitted too quickly. Possible spam.' );
        }

        return $result;
    },
    10,
    2
);

/**
 * Sets the number of products displayed on the product archive page to unlimited.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function cb_show_all_products_on_archive( $query ) {
    if (
        !is_admin() &&
        $query->is_main_query() &&
        is_post_type_archive( 'product' )
    ) {
        $query->set('posts_per_page', -1);
    }
}
add_action( 'pre_get_posts', 'cb_show_all_products_on_archive' );


function enqueue_nouislider_assets() {
    if ( is_post_type_archive( 'product' ) || is_tax( 'product_type' ) || is_tax( 'product_category' ) || is_tax( 'edge_type' ) || is_tax( 'usage' ) ) {
        wp_enqueue_style( 'nouislider', 'https://cdn.jsdelivr.net/npm/nouislider@15.8.1/dist/nouislider.min.css' );
        wp_enqueue_script( 'nouislider', 'https://cdn.jsdelivr.net/npm/nouislider@15.8.1/dist/nouislider.min.js', array(), null, true );
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_nouislider_assets' );


function dimensions( $a, $b ) {
    if ( $a === $b ) {
        return $a . ' x ' . $b;
    }
    return $a . ' x ' . $b;
}


// BROCHURE GENERATION.
// This action hook listens for the 'generate_brochure' query parameter in the URL.
add_action(
    'init',
    function () {
	    if ( isset( $_GET['generate_brochure'] ) ) {
		    include get_stylesheet_directory() . '/pdf/generate-brochure.php';
		    exit;
	    }
    }
);

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(
        array(
            'page_title' => 'PDF Brochure Content',
            'menu_title' => 'Brochure Content',
            'menu_slug'  => 'pdf-brochure-content',
            'capability' => 'edit_posts',
            'redirect'   => false,
        )
    );
}


function brochure_image_resolver( $field ) {
    return fn() => ( $img = get_field( $field, 'option' ) ) && is_array( $img )
        ? [ 'sourceUrl' => $img['url'] ?? '', 'altText' => $img['alt'] ?? '' ]
        : null;
}

add_action( 'graphql_register_types', function () {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}

	// === PRODUCT META FIELDS ===
	$fields = array(
		'capacity'          => 'Float',
		'lid'               => 'Boolean',
		'product_name'      => 'String',
		'top_out_a'         => 'Float',
		'top_out_b'         => 'Float',
		'top_in_a'          => 'Float',
		'top_in_b'          => 'Float',
		'base_a'            => 'Float',
		'base_b'            => 'Float',
		'depth'             => 'Float',
		'weight'            => 'Float',
		'samples_available' => 'Boolean',
	);

	foreach ( $fields as $name => $type ) {
		register_graphql_field( 'Product', $name, [
			'type'        => $type,
			'description' => "ACF field `$name`",
			'resolve'     => function( $post ) use ( $name, $type ) {
				$value = get_field( $name, $post->ID );
				if ( $value === '' || $value === null ) {
					return null;
				}
				if ( $type === 'Float' ) {
					return (float) $value;
				}
				if ( $type === 'Boolean' ) {
					return (bool) $value;
				}
				return $value;
			},
		] );
	}

	// === STATIC OPTIONS FIELDS ===
	register_graphql_field( 'RootQuery', 'coverTitle', [
		'type'        => 'String',
		'description' => 'Cover page title',
		'resolve'     => fn() => get_field( 'cover_title', 'option' ),
	] );

	register_graphql_field( 'RootQuery', 'coverSubtitle', [
		'type'        => 'String',
		'description' => 'Cover page subtitle',
		'resolve'     => fn() => get_field( 'cover_subtitle', 'option' ),
	] );

    register_graphql_field( 'RootQuery', 'coverLogo', [
        'type'        => 'BrochureImage',
        'description' => 'Cover logo image',
        'resolve'     => brochure_image_resolver( 'cover_logo' ),
    ] );
    register_graphql_field( 'RootQuery', 'watermark', [
        'type'        => 'BrochureImage',
        'description' => 'Watermark image',
        'resolve'     => brochure_image_resolver( 'watermark' ),
    ] );

	register_graphql_field( 'RootQuery', 'aboutHeading', [
		'type'        => 'String',
		'description' => 'About heading',
		'resolve'     => fn() => get_field( 'about_heading', 'option' ),
	] );

	register_graphql_field( 'RootQuery', 'aboutText1', [
		'type'        => 'String',
		'description' => 'About text block 1',
		'resolve'     => fn() => get_field( 'about_text_1', 'option' ),
	] );

	register_graphql_field( 'RootQuery', 'aboutText2', [
		'type'        => 'String',
		'description' => 'About text block 2',
		'resolve'     => fn() => get_field( 'about_text_2', 'option' ),
	] );

	register_graphql_field( 'RootQuery', 'statementOfCompliance', [
		'type'        => 'String',
		'description' => 'Compliance statement',
		'resolve'     => fn() => get_field( 'statement_of_compliance', 'option' ),
	] );

	// === IMAGES ===
	register_graphql_object_type( 'BrochureImage', [
		'description' => 'Image with alt text',
		'fields'      => [
			'sourceUrl' => [ 'type' => 'String' ],
			'altText'   => [ 'type' => 'String' ],
		],
	] );

	register_graphql_field( 'RootQuery', 'aboutImage1', [
		'type'        => 'BrochureImage',
		'description' => 'About image 1',
		'resolve'     => brochure_image_resolver( 'about_image_1' ),
	] );

	register_graphql_field( 'RootQuery', 'aboutImage2', [
		'type'        => 'BrochureImage',
		'description' => 'About image 2',
		'resolve'     => brochure_image_resolver( 'about_image_2' ),
	] );

	register_graphql_field( 'RootQuery', 'aboutImage3', [
		'type'        => 'BrochureImage',
		'description' => 'About image 3',
		'resolve'     => brochure_image_resolver( 'about_image_3' ),
	] );


	register_graphql_field( 'RootQuery', 'sectorTitle', [
		'type'        => 'String',
		'description' => 'Sector Title',
		'resolve'     => fn() => get_field( 'sectors_page_title', 'option' ),
	] );

	register_graphql_field( 'RootQuery', 'sectorIntro', [
		'type'        => 'String',
		'description' => 'Sector Intro',
		'resolve'     => fn() => get_field( 'sectors_page_intro', 'option' ),
	] );

	// === SECTORS REPEATER ===
	register_graphql_object_type( 'Sector', [
		'description' => 'Sector row',
		'fields'      => [
			'sectorTitle' => [
				'type'    => 'String',
				'resolve' => fn( $row ) => $row['sector_title'] ?? null,
			],
			'sectorDescription' => [
				'type'    => 'String',
				'resolve' => fn( $row ) => $row['sector_description'] ?? null,
			],
		],
	] );

    register_graphql_field( 'RootQuery', 'sectorImage1', [
		'type'        => 'BrochureImage',
		'description' => 'Sectors image 1',
		'resolve'     => brochure_image_resolver( 'sector_image_1' ),
	] );
    register_graphql_field( 'RootQuery', 'sectorImage2', [
		'type'        => 'BrochureImage',
		'description' => 'Sectors image 2',
		'resolve'     => brochure_image_resolver( 'sector_image_2' ),
	] );
    register_graphql_field( 'RootQuery', 'sectorImage3', [
		'type'        => 'BrochureImage',
		'description' => 'Sectors image 3',
		'resolve'     => brochure_image_resolver( 'sector_image_3' ),
	] );
    register_graphql_field( 'RootQuery', 'sectorImage4', [
		'type'        => 'BrochureImage',
		'description' => 'Sectors image 4',
		'resolve'     => brochure_image_resolver( 'sector_image_4' ),
	] );

	register_graphql_field( 'RootQuery', 'sectors', [
		'type'        => [ 'list_of' => 'Sector' ],
		'description' => 'Brochure sector list',
		'resolve'     => fn() => get_field( 'sectors', 'option' ) ?: [],
	] );
} );


add_action( 'graphql_register_types', function() {
    register_graphql_field( 'RootQuery', 'productCount', [
        'type' => 'Int',
        'description' => __( 'Total number of products', 'your-textdomain' ),
        'resolve' => function() {
            $query = new WP_Query([
                'post_type' => 'product',
                'post_status' => 'publish',
                'fields' => 'ids',
                'nopaging' => true,
            ]);
            return $query->found_posts;
        },
    ] );
});

// Replace the entire CSV export section with this:

if ( function_exists( 'acf_add_options_page' ) ) {
    // Add Export Import Tools as top-level admin menu.
    add_action(
        'admin_menu',
        function () {
            add_menu_page(
                'Product Management Tools',
                'Product Management Tools',
                'manage_options',
                'export-products-csv',
                'render_export_products_page',
                'dashicons-database-export',
                30
            );
        }
    );
}

/**
 * Renders the CSV export admin page
 */
function render_export_products_page() {
    // Handle clear products action
    if (isset($_GET['action']) && $_GET['action'] === 'clear_products' && 
        isset($_GET['_wpnonce']) && wp_verify_nonce(wp_unslash($_GET['_wpnonce']), 'clear_products')) {
        clear_all_products();
        echo '<div class="notice notice-success"><p>All products have been deleted successfully!</p></div>';
    }

    // Display import results if available
    if (isset($_GET['import_complete']) && $_GET['import_complete'] === '1') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['import_results'])) {
            $results = $_SESSION['import_results'];
            unset($_SESSION['import_results']);
            
            echo '<div class="notice notice-success">';
            echo '<h3>Import Complete!</h3>';
            echo '<p><strong>Total Rows Processed:</strong> ' . esc_html($results['total_rows']) . '</p>';
            echo '<p><strong>Products Imported:</strong> ' . esc_html($results['imported']) . '</p>';
            echo '<p><strong>Products Updated:</strong> ' . esc_html($results['updated']) . '</p>';
            echo '<p><strong>Rows Skipped:</strong> ' . esc_html($results['skipped']) . '</p>';
            
            if (!empty($results['duplicates'])) {
                echo '<p><strong>Duplicate SKUs Found:</strong> ' . count($results['duplicates']) . '</p>';
                echo '<ul>';
                foreach ($results['duplicates'] as $duplicate) {
                    echo '<li>Row ' . esc_html($duplicate['row']) . ': SKU "' . esc_html($duplicate['sku']) . '" (' . esc_html($duplicate['action']) . ')</li>';
                }
                echo '</ul>';
            }
            
            if (!empty($results['errors'])) {
                echo '<h4 style="color: #d63638;">Errors:</h4>';
                echo '<ul>';
                foreach ($results['errors'] as $error) {
                    echo '<li style="color: #d63638;">' . esc_html($error) . '</li>';
                }
                echo '</ul>';
            }
            echo '</div>';
        }
    }

    // Display image import results if available
    if (isset($_GET['images_import_complete']) && $_GET['images_import_complete'] === '1') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['image_import_results'])) {
            $results = $_SESSION['image_import_results'];
            unset($_SESSION['image_import_results']);
            
            echo '<div class="notice notice-success">';
            echo '<h3>Image Import Complete!</h3>';
            echo '<p><strong>Total Images Processed:</strong> ' . esc_html($results['total_images']) . '</p>';
            echo '<p><strong>Images Successfully Imported:</strong> ' . esc_html($results['successful']) . '</p>';
            echo '<p><strong>Images Skipped/Failed:</strong> ' . esc_html($results['failed']) . '</p>';
            
            if (!empty($results['matches'])) {
                echo '<h4 style="color: #00a32a;">Successful Matches:</h4>';
                echo '<ul>';
                foreach ($results['matches'] as $match) {
                    echo '<li>' . esc_html($match['filename']) . ' → Product: ' . esc_html($match['sku']) . '</li>';
                }
                echo '</ul>';
            }
            
            if (!empty($results['failures'])) {
                echo '<h4 style="color: #d63638;">Failed/Skipped:</h4>';
                echo '<ul>';
                foreach ($results['failures'] as $failure) {
                    echo '<li style="color: #d63638;">' . esc_html($failure['filename']) . ': ' . esc_html($failure['reason']) . '</li>';
                }
                echo '</ul>';
            }
            echo '</div>';
        }
    }

    // Get product count for display
    $product_count = wp_count_posts( 'product' )->publish;
    ?>
    <style>
        .product_admin { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .product_admin h1 { grid-column: 1 / 3; }
        .product_admin .notice { grid-column: 1 / 3; }
        .product_admin .card { max-width: 100%; }
    </style>
    <div class="wrap product_admin">
        <h1>Products CSV Import/Export</h1>
        
        <div class="card">
            <h2>Product Database Export</h2>
            <p>Export all published products and their data to a CSV file.</p>
            <p><strong>Total Products:</strong> <?php echo esc_html( $product_count ); ?></p>
            
            <p>
                <a href="<?php echo admin_url('admin.php?page=export-products-csv&action=download_csv&_wpnonce=' . wp_create_nonce('download_csv')); ?>" 
                   class="button button-primary">
                    Download Products CSV
                </a>
            </p>
        </div>
        
        <div class="card">
            <h2>Product Images Export</h2>
            <p>Export all product featured images as a ZIP file. Images will be named {SKU}.{extension}.</p>
            <?php
            $products_with_images = get_posts([
                'post_type' => 'product',
                'post_status' => 'publish',
                'meta_query' => [
                    [
                        'key' => '_thumbnail_id',
                        'compare' => 'EXISTS'
                    ]
                ],
                'posts_per_page' => -1,
                'fields' => 'ids'
            ]);
            ?>
            <p><strong>Products with Images:</strong> <?php echo count($products_with_images); ?> of <?php echo esc_html( $product_count ); ?></p>
            
            <?php if (count($products_with_images) > 0): ?>
            <p>
                <a href="<?php echo admin_url('admin.php?page=export-products-csv&action=download_images&_wpnonce=' . wp_create_nonce('download_images')); ?>" 
                   class="button button-primary">
                    Download Product Images ZIP
                </a>
            </p>
            <?php else: ?>
            <p><em>No products with featured images to export.</em></p>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2>Product Database Import</h2>
            <p>Upload a CSV file to import products. The CSV should match the export format.</p>
            
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('import_products_csv', 'import_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">CSV File</th>
                        <td>
                            <input type="file" name="products_csv" accept=".csv" required>
                            <p class="description">Select a CSV file to import products.</p>
                        </td>
                    </tr>
                </table>
                <p>
                    <button type="submit" name="import_csv" class="button button-primary">
                        Import Products from CSV
                    </button>
                </p>
            </form>
        </div>
        
        <div class="card">
            <h2>Product Images Import</h2>
            <p>Upload a ZIP file containing product images. Images should be named {SKU}.{extension} where SKU matches the product's original SKU (not the slug).</p>
            <p><strong>Supported formats:</strong> .jpg, .jpeg, .png, .webp</p>
            
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('import_product_images', 'import_images_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">ZIP File</th>
                        <td>
                            <input type="file" name="product_images_zip" accept=".zip" required>
                            <p class="description">Select a ZIP file containing product images.</p>
                        </td>
                    </tr>
                </table>
                <p>
                    <button type="submit" name="import_images" class="button button-primary">
                        Import Product Images
                    </button>
                </p>
            </form>
        </div>
        
        <div class="card">
            <h2 style="color: #d63638;">Danger Zone</h2>
            <p><strong>Warning:</strong> This action will permanently delete ALL products and their featured images. This cannot be undone!</p>
            <p><strong>Current Products:</strong> <?php echo esc_html( $product_count ); ?></p>
            
            <p>
                <a href="<?php echo admin_url('admin.php?page=export-products-csv&action=clear_products&_wpnonce=' . wp_create_nonce('clear_products')); ?>" 
                   class="button button-secondary"
                   onclick="return confirm('Are you sure you want to delete ALL products? This cannot be undone!');"
                   style="background: #d63638; border-color: #d63638; color: white;">
                    Clear All Products
                </a>
            </p>
        </div>
        
        <!-- <div class="card">
            <h3>CSV Contents</h3>
            <p>The exported CSV will include the following fields:</p>
            <ul>
                <li>ID</li>
                <li>Title</li>
                <li>SKU</li>
                <li>Product Type(s)</li>
                <li>Product Categories</li>
                <li>Edge Types</li>
                <li>Usage</li>
                <li>Capacity</li>
                <li>Lid</li>
                <li>Additional</li>
                <li>Top Out A</li>
                <li>Top Out B</li>
                <li>Top In A</li>
                <li>Top In B</li>
                <li>Base A</li>
                <li>Base B</li>
                <li>Depth</li>
                <li>Weight</li>
                <li>Samples Available</li>
                <li>Featured Image URL</li>
                <li>Date Created</li>
                <li>Last Modified</li>
            </ul>
        </div> -->
    </div>
    <?php
}

// Handle the download via URL parameter instead of POST
add_action('admin_init', function() {
    if (isset($_GET['action']) && isset($_GET['page']) && $_GET['page'] === 'export-products-csv') {
        if ($_GET['action'] === 'download_csv' && wp_verify_nonce($_GET['_wpnonce'], 'download_csv')) {
            export_products_csv();
        }
        
        if ($_GET['action'] === 'download_images' && wp_verify_nonce($_GET['_wpnonce'], 'download_images')) {
            export_product_images();
        }
        
        if ($_GET['action'] === 'clear_products' && wp_verify_nonce($_GET['_wpnonce'], 'clear_products')) {
            // This is handled in the render function to show success message
            return;
        }
    }
    
    // Handle CSV import
    if (isset($_POST['import_csv']) && wp_verify_nonce($_POST['import_nonce'], 'import_products_csv')) {
        import_products_csv();
    }
    
    // Handle image import
    if (isset($_POST['import_images']) && wp_verify_nonce($_POST['import_images_nonce'], 'import_product_images')) {
        import_product_images();
    }
});

/**
 * Processes the uploaded CSV file and imports products
 */
function import_products_csv() {
    // Check if file was uploaded
    if (!isset($_FILES['products_csv']) || $_FILES['products_csv']['error'] !== UPLOAD_ERR_OK) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Error uploading file. Please try again.</p></div>';
        });
        return;
    }

    $file = $_FILES['products_csv']['tmp_name'];
    
    // Open and read the CSV file
    if (($handle = fopen($file, 'r')) === false) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Error reading CSV file.</p></div>';
        });
        return;
    }

    // Read header row
    $headers = fgetcsv($handle);
    if (!$headers) {
        fclose($handle);
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Invalid CSV file format.</p></div>';
        });
        return;
    }

    // Clean headers - remove BOM, quotes, and extra whitespace
    $headers = array_map(function($header) {
        // Remove UTF-8 BOM if present
        $header = str_replace("\xEF\xBB\xBF", '', $header);
        // Remove surrounding quotes if present
        $header = trim($header, '"\'');
        // Trim whitespace
        return trim($header);
    }, $headers);

    // Process the import
    $results = process_csv_import($handle, $headers);
    fclose($handle);

    // Store results in session to display on page reload
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['import_results'] = $results;

    // Redirect to avoid resubmission
    wp_redirect(admin_url('admin.php?page=export-products-csv&import_complete=1'));
    exit;
}

/**
 * Processes the CSV data and imports products
 */
function process_csv_import($handle, $headers) {
    $results = [
        'total_rows' => 0,
        'imported' => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors' => [],
        'duplicates' => []
    ];

    // Create header mapping
    $header_map = array_flip(array_map('trim', $headers));
    
    // DEBUG: Log all detected headers
    error_log("Import Debug - All detected headers: " . print_r($headers, true));
    error_log("Import Debug - Header mapping: " . print_r($header_map, true));
    
    $row_number = 1; // Start at 1 since we've read headers
    
    while (($data = fgetcsv($handle)) !== false) {
        $row_number++;
        $results['total_rows']++;
        
        // Skip empty rows
        if (empty(array_filter($data))) {
            $results['skipped']++;
            continue;
        }

        try {
            $import_result = import_single_product($data, $header_map, $row_number);
            
            if ($import_result['success']) {
                if ($import_result['action'] === 'created') {
                    $results['imported']++;
                } else {
                    $results['updated']++;
                }
                
                if ($import_result['is_duplicate']) {
                    $results['duplicates'][] = [
                        'row' => $row_number,
                        'sku' => $import_result['sku'],
                        'action' => $import_result['action']
                    ];
                }
            } else {
                $results['errors'][] = "Row {$row_number}: " . $import_result['error'];
                $results['skipped']++;
            }
        } catch (Exception $e) {
            $results['errors'][] = "Row {$row_number}: " . $e->getMessage();
            $results['skipped']++;
        }
    }

    return $results;
}

/**
 * Generates a product slug in the format: {product-name}-{top_out}-{top_in}-{sku}
 *
 * @param string $product_name The product name.
 * @param mixed  $top_out_a    The top out A dimension.
 * @param mixed  $top_in_a     The top in A dimension.
 * @param string $sku          The product SKU.
 * @return string The generated slug.
 */
function generate_product_slug( $product_name, $top_out_a, $top_in_a, $sku ) {
    $parts = array();
    
    // Add product name if available.
    if ( ! empty( $product_name ) ) {
        $parts[] = sanitize_title( $product_name );
    }
    
    // Add top_out_a if available.
    if ( ! empty( $top_out_a ) && is_numeric( $top_out_a ) ) {
        $parts[] = $top_out_a;
    }
    
    // Add top_in_a if available.
    if ( ! empty( $top_in_a ) && is_numeric( $top_in_a ) ) {
        $parts[] = $top_in_a;
    }
    
    // Add SKU (always present).
    if ( ! empty( $sku ) ) {
        $parts[] = sanitize_title( $sku );
    }
    
    return implode( '-', array_filter( $parts ) );
}

/**
 * Imports a single product from CSV row data
 */
function import_single_product($data, $header_map, $row_number) {
    // Extract SKU (our unique identifier)
    $sku = isset($header_map['SKU']) ? trim($data[$header_map['SKU']]) : '';
    
    if (empty($sku)) {
        return ['success' => false, 'error' => 'SKU is required'];
    }

    // Check if product with this SKU already exists
    $existing_posts = get_posts(
        array(
            'post_type'      => 'product',
            'post_status'    => 'any',
            'meta_query'     => array(),
            'title'          => $sku,
            'posts_per_page' => 1,
            'fields'         => 'ids',
        )
    );

    $is_duplicate = ! empty( $existing_posts );
    $post_id      = $is_duplicate ? $existing_posts[0] : 0;

    // Extract other fields
    $product_title = isset( $header_map['Product Title'] ) ? trim( $data[ $header_map['Product Title'] ] ) : '';
    
    // Prepare post data
    $post_data = array(
        'post_type'    => 'product',
        'post_title'   => $sku, // SKU becomes the post title
        'post_status'  => 'publish',
        'post_content' => '',
    );

    if ( $post_id ) {
        $post_data['ID'] = $post_id;
        $post_id = wp_update_post( $post_data );
        $action = 'updated';
    } else {
        $post_id = wp_insert_post( $post_data );
        $action = 'created';
    }

    if ( is_wp_error( $post_id ) ) {
        return array( 'success' => false, 'error' => 'Failed to create/update post: ' . $post_id->get_error_message() );
    }

    // Set ACF fields.
    if ( ! function_exists( 'update_field' ) ) {
        return array( 
            'success' => false, 
            'error'   => 'ACF (Advanced Custom Fields) is not available' 
        );
    }
    
    // Update the product_name field.
    update_field( 'product_name', $product_title, $post_id );
    
    // Set numeric fields
    $numeric_fields = array(
        'Capacity'   => 'capacity',
        'Top Out A'  => 'top_out_a',
        'Top Out B'  => 'top_out_b', 
        'Top In A'   => 'top_in_a',
        'Top In B'   => 'top_in_b',
        'Base A'     => 'base_a',
        'Base B'     => 'base_b',
        'Depth'      => 'depth',
        'Weight'     => 'weight',
    );

    foreach ( $numeric_fields as $csv_field => $acf_field ) {
        if ( isset( $header_map[ $csv_field ] ) ) {
            $value = trim( $data[ $header_map[ $csv_field ] ] );
            if ( ! empty( $value ) && is_numeric( $value ) ) {
                update_field( $acf_field, (float) $value, $post_id );
            }
        }
    }

    // Handle B field auto-fill from A fields
    $dimension_pairs = array(
        array( 'top_out_a', 'top_out_b' ),
        array( 'top_in_a', 'top_in_b' ),
        array( 'base_a', 'base_b' ),
    );

    foreach ( $dimension_pairs as $pair ) {
        $a_value = get_field( $pair[0], $post_id );
        $b_value = get_field( $pair[1], $post_id );
        
        if ( ! empty( $a_value ) && empty( $b_value ) ) {
            update_field( $pair[1], $a_value, $post_id );
        }
    }

    // Set boolean fields
    if ( isset( $header_map['Lid'] ) ) {
        $lid_value = trim( $data[ $header_map['Lid'] ] );
        $lid_bool  = ( strtolower( $lid_value ) === 'yes' || $lid_value === '1' );
        update_field( 'lid', $lid_bool, $post_id );
    }

    if ( isset( $header_map['Samples Available'] ) ) {
        $samples_value = trim( $data[ $header_map['Samples Available'] ] );
        $samples_bool  = ( strtolower( $samples_value ) === 'yes' || $samples_value === '1' );
        update_field( 'samples_available', $samples_bool, $post_id );
    }

    // Set taxonomies
    $taxonomy_fields = array(
        'Product Types'      => 'product_type',
        'Product Categories' => 'product_category',
        'Edge Types'         => 'edge_type',
        'Usage'              => 'usage',
    );

    foreach ( $taxonomy_fields as $csv_field => $taxonomy ) {
        if ( isset( $header_map[ $csv_field ] ) ) {
            $terms_string = trim( $data[ $header_map[ $csv_field ] ] );
            if ( ! empty( $terms_string ) ) {
                // Remove quotes and split by comma
                $terms_string = trim( $terms_string, '"' );
                $terms        = array_map( 'trim', explode( ',', $terms_string ) );
                
                $term_ids = array();
                foreach ( $terms as $term_name ) {
                    if ( ! empty( $term_name ) ) {
                        $term = get_term_by( 'name', $term_name, $taxonomy );
                        if ( ! $term ) {
                            // Create the term if it doesn't exist
                            $term_result = wp_insert_term( $term_name, $taxonomy );
                            if ( ! is_wp_error( $term_result ) ) {
                                $term_ids[] = $term_result['term_id'];
                            }
                        } else {
                            $term_ids[] = $term->term_id;
                        }
                    }
                }
                
                if ( ! empty( $term_ids ) ) {
                    wp_set_object_terms( $post_id, $term_ids, $taxonomy );
                }
            }
        }
    }

    // Generate and update the product slug after all fields are set.
    $top_out_a = get_field( 'top_out_a', $post_id );
    $top_in_a  = get_field( 'top_in_a', $post_id );
    $new_slug  = generate_product_slug( $product_title, $top_out_a, $top_in_a, $sku );
    
    if ( ! empty( $new_slug ) ) {
        wp_update_post(
            array(
                'ID'        => $post_id,
                'post_name' => $new_slug,
            )
        );
    }

    return array(
        'success'      => true,
        'action'       => $action,
        'is_duplicate' => $is_duplicate,
        'sku'          => $sku,
        'post_id'      => $post_id,
    );
}

/**
 * Generates and downloads the products CSV file
 */
function export_products_csv() {
    $filename = 'products-export-' . date( 'Y-m-d-H-i-s' ) . '.csv';
    
    // Set headers
    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Cache-Control: no-cache, must-revalidate' );
    header( 'Expires: 0' );
    header( 'Pragma: no-cache' );
    
    // Open output stream
    $output = fopen( 'php://output', 'w' );

    // Add BOM for proper UTF-8 encoding in Excel
    fwrite( $output, "\xEF\xBB\xBF" );

    // CSV Headers - matches our new mapping
    $headers = [
        'Product Title', 'SKU', 'Product Types', 'Product Categories', 'Edge Types', 
        'Usage', 'Capacity', 'Lid', 'Top Out A', 'Top Out B', 'Top In A', 
        'Top In B', 'Base A', 'Base B', 'Depth', 'Weight', 'Samples Available', 
        'Featured Image URL', 'Date Created', 'Last Modified'
    ];
    fputcsv( $output, $headers );

    // Get all products
    $products = get_posts([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC'
    ]);

    foreach ( $products as $product ) {
        // Get product types
        $product_types = get_the_terms( $product->ID, 'product_type' );
        $type_names = $product_types && !is_wp_error($product_types) ? 
            implode( ', ', wp_list_pluck( $product_types, 'name' ) ) : '';

        // Get product categories
        $product_categories = get_the_terms( $product->ID, 'product_category' );
        $category_names = $product_categories && !is_wp_error($product_categories) ? 
            implode( ', ', wp_list_pluck( $product_categories, 'name' ) ) : '';

        // Get edge types
        $edge_types = get_the_terms( $product->ID, 'edge_type' );
        $edge_type_names = $edge_types && !is_wp_error($edge_types) ? 
            implode( ', ', wp_list_pluck( $edge_types, 'name' ) ) : '';

        // Get usage types
        $usage_terms = get_the_terms( $product->ID, 'usage' );
        $usage_names = $usage_terms && !is_wp_error($usage_terms) ? 
            implode( ', ', wp_list_pluck( $usage_terms, 'name' ) ) : '';

        // Get featured image
        $featured_image = get_the_post_thumbnail_url( $product->ID, 'full' );

        // Build row data - using new field mapping
        $row = [
            get_field( 'product_name', $product->ID ) ?: '', // Product Title (descriptive name)
            $product->post_title, // SKU (now the post title)
            $type_names,
            $category_names,
            $edge_type_names,
            $usage_names,
            get_field( 'capacity', $product->ID ) ?: '',
            get_field( 'lid', $product->ID ) ? 'Yes' : '',
            get_field( 'top_out_a', $product->ID ) ?: '',
            get_field( 'top_out_b', $product->ID ) ?: '',
            get_field( 'top_in_a', $product->ID ) ?: '',
            get_field( 'top_in_b', $product->ID ) ?: '',
            get_field( 'base_a', $product->ID ) ?: '',
            get_field( 'base_b', $product->ID ) ?: '',
            get_field( 'depth', $product->ID ) ?: '',
            get_field( 'weight', $product->ID ) ?: '',
            get_field( 'samples_available', $product->ID ) ? 'Yes' : '',
            $featured_image ?: '',
            $product->post_date,
            $product->post_modified
        ];

        fputcsv( $output, $row );
    }

    fclose( $output );
    exit;
}

/**
 * Deletes all products and their featured images
 */
function clear_all_products() {
    // Get all products
    $products = get_posts([
        'post_type' => 'product',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);

    foreach ($products as $product_id) {
        // Delete featured image attachment
        $featured_image_id = get_post_thumbnail_id($product_id);
        if ($featured_image_id) {
            wp_delete_attachment($featured_image_id, true);
        }

        // Delete the product post
        wp_delete_post($product_id, true);
    }

    return count($products);
}

/**
 * Generates and downloads a ZIP file containing all product featured images
 */
function export_product_images() {
    // Check if ZipArchive is available
    if (!class_exists('ZipArchive')) {
        wp_die('ZIP extension not available on this server.');
        return;
    }

    // Get all products with featured images
    $products = get_posts([
        'post_type' => 'product',
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            ]
        ],
        'posts_per_page' => -1
    ]);

    if (empty($products)) {
        wp_die('No products with featured images found.');
        return;
    }

    // Create temporary file for the ZIP
    $temp_file = wp_tempnam('product-images.zip');
    if (!$temp_file) {
        wp_die('Could not create temporary file.');
        return;
    }

    $zip = new ZipArchive();
    if ($zip->open($temp_file, ZipArchive::CREATE) !== TRUE) {
        unlink($temp_file);
        wp_die('Could not create ZIP file.');
        return;
    }

    $exported_count = 0;
    $skipped_count = 0;
    $filename_conflicts = [];

    foreach ($products as $product) {
        $sku = get_the_title($product->ID);
        $attachment_id = get_post_thumbnail_id($product->ID);
        
        if (!$attachment_id) {
            $skipped_count++;
            continue;
        }

        $attachment_path = get_attached_file($attachment_id);
        if (!$attachment_path || !file_exists($attachment_path)) {
            $skipped_count++;
            continue;
        }

        // Get file extension
        $file_info = pathinfo($attachment_path);
        $extension = strtolower($file_info['extension']);
        
        // Create filename as SKU.extension
        $zip_filename = sanitize_file_name($sku) . '.' . $extension;
        
        // Check for filename conflicts
        if (in_array($zip_filename, $filename_conflicts)) {
            // Add product ID to make it unique
            $zip_filename = sanitize_file_name($sku) . '-' . $product->ID . '.' . $extension;
        }
        $filename_conflicts[] = $zip_filename;

        // Add file to ZIP
        if ($zip->addFile($attachment_path, $zip_filename)) {
            $exported_count++;
        } else {
            $skipped_count++;
        }
    }

    $zip->close();

    // Prepare download
    $download_filename = 'product-images-' . date('Y-m-d-H-i-s') . '.zip';
    
    // Set headers for download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $download_filename . '"');
    header('Content-Length: ' . filesize($temp_file));
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: 0');
    header('Pragma: no-cache');

    // Output file and clean up
    readfile($temp_file);
    unlink($temp_file);
    exit;
}

/**
 * Processes the uploaded ZIP file and imports product images
 */
function import_product_images() {
    // Check if file was uploaded
    if (!isset($_FILES['product_images_zip']) || $_FILES['product_images_zip']['error'] !== UPLOAD_ERR_OK) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Error uploading ZIP file. Please try again.</p></div>';
        });
        return;
    }

    $zip_file = $_FILES['product_images_zip']['tmp_name'];
    
    // Check if ZipArchive is available
    if (!class_exists('ZipArchive')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>ZIP extension not available on this server.</p></div>';
        });
        return;
    }

    $zip = new ZipArchive();
    if ($zip->open($zip_file) !== TRUE) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Error reading ZIP file.</p></div>';
        });
        return;
    }

    // Process the import
    $results = process_image_import($zip);
    $zip->close();

    // Store results in session to display on page reload
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['image_import_results'] = $results;

    // Redirect to avoid resubmission
    wp_redirect(admin_url('admin.php?page=export-products-csv&images_import_complete=1'));
    exit;
}

/**
 * Processes images from ZIP archive and matches them to products
 */
function process_image_import($zip) {
    $results = [
        'total_images' => 0,
        'successful' => 0,
        'failed' => 0,
        'matches' => [],
        'failures' => []
    ];

    // Get all products for matching
    $products = get_posts([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);

    // Create a lookup array of SKU to post ID
    $sku_lookup = [];
    foreach ($products as $product_id) {
        $sku = get_the_title($product_id);
        $sku_lookup[strtolower($sku)] = $product_id;
    }

    // Supported image extensions
    $supported_extensions = ['jpg', 'jpeg', 'png', 'webp'];

    for ($i = 0; $i < $zip->numFiles; $i++) {
        $filename = $zip->getNameIndex($i);
        $results['total_images']++;

        // Skip directories and hidden files
        if (substr($filename, -1) === '/' || strpos(basename($filename), '.') === 0) {
            $results['failed']++;
            $results['failures'][] = [
                'filename' => $filename,
                'reason' => 'Directory or hidden file'
            ];
            continue;
        }

        // Get file info
        $pathinfo = pathinfo($filename);
        $basename = $pathinfo['filename'];
        $extension = strtolower($pathinfo['extension']);

        // Check if extension is supported
        if (!in_array($extension, $supported_extensions)) {
            $results['failed']++;
            $results['failures'][] = [
                'filename' => $filename,
                'reason' => 'Unsupported file type'
            ];
            continue;
        }

        // Look for matching product by SKU
        $sku_lower = strtolower($basename);
        if (!isset($sku_lookup[$sku_lower])) {
            $results['failed']++;
            $results['failures'][] = [
                'filename' => $filename,
                'reason' => 'No matching product found for SKU: ' . $basename
            ];
            continue;
        }

        $product_id = $sku_lookup[$sku_lower];

        // Extract file content
        $file_content = $zip->getFromIndex($i);
        if ($file_content === false) {
            $results['failed']++;
            $results['failures'][] = [
                'filename' => $filename,
                'reason' => 'Could not extract file from ZIP'
            ];
            continue;
        }

        // Try to import the image
        $import_result = import_product_image($product_id, $filename, $file_content, $basename);
        
        if ($import_result['success']) {
            $results['successful']++;
            $results['matches'][] = [
                'filename' => $filename,
                'sku' => $basename,
                'attachment_id' => $import_result['attachment_id']
            ];
        } else {
            $results['failed']++;
            $results['failures'][] = [
                'filename' => $filename,
                'reason' => $import_result['error']
            ];
        }
    }

    return $results;
}

/**
 * Imports a single image and sets it as the product's featured image
 */
function import_product_image($product_id, $filename, $file_content, $sku) {
    // Create a temporary file
    $temp_file = wp_tempnam($filename);
    if (!$temp_file) {
        return ['success' => false, 'error' => 'Could not create temporary file'];
    }

    // Write content to temp file
    if (file_put_contents($temp_file, $file_content) === false) {
        unlink($temp_file);
        return ['success' => false, 'error' => 'Could not write image data'];
    }

    // Validate the image
    $image_info = getimagesize($temp_file);
    if (!$image_info) {
        unlink($temp_file);
        return ['success' => false, 'error' => 'Invalid image file'];
    }

    // Remove existing featured image if it exists
    $existing_featured = get_post_thumbnail_id($product_id);
    if ($existing_featured) {
        wp_delete_attachment($existing_featured, true);
    }

    // Prepare the attachment data
    $upload_dir = wp_upload_dir();
    $file_type = wp_check_filetype($filename);
    $new_filename = sanitize_file_name($filename);
    
    // Move the temp file to uploads directory
    $upload_path = $upload_dir['path'] . '/' . $new_filename;
    if (!move_uploaded_file($temp_file, $upload_path)) {
        if (!copy($temp_file, $upload_path)) {
            unlink($temp_file);
            return ['success' => false, 'error' => 'Could not move file to uploads directory'];
        }
        unlink($temp_file);
    }

    // Create the attachment
    $attachment_data = [
        'post_mime_type' => $file_type['type'],
        'post_title' => $sku . ' - Product Image',
        'post_content' => '',
        'post_status' => 'inherit'
    ];

    $attachment_id = wp_insert_attachment($attachment_data, $upload_path, $product_id);

    if (is_wp_error($attachment_id)) {
        unlink($upload_path);
        return ['success' => false, 'error' => 'Could not create attachment: ' . $attachment_id->get_error_message()];
    }

    // Generate attachment metadata
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attachment_metadata = wp_generate_attachment_metadata($attachment_id, $upload_path);
    wp_update_attachment_metadata($attachment_id, $attachment_metadata);

    // Set as featured image
    if (!set_post_thumbnail($product_id, $attachment_id)) {
        wp_delete_attachment($attachment_id, true);
        return ['success' => false, 'error' => 'Could not set as featured image'];
    }

    return ['success' => true, 'attachment_id' => $attachment_id];
}