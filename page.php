<?php
/**
 * Template Name: Custom Page Template
 * Description: A custom page template for the Valewood Bathrooms theme.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main">
    <?php
    the_post();
    the_content();

    get_template_part( 'page-templates/blocks/lc-cta' );
    ?>
</main>
<?php
get_footer();