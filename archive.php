<?php
/**
 * Archive Template
 *
 * This template is used to display archive pages for posts.
 *
 * @package WordPress
 * @subpackage lc-saialupack2025
 * @since lc-saialupack2025 1.0
 */

defined( 'ABSPATH' ) || exit;

$page_for_posts = get_option( 'page_for_posts' );
$bg             = get_the_post_thumbnail( $page_for_posts, 'full', array( 'class' => 'page_hero__bg' ) );

$category = get_queried_object(); // Get current category ID.

get_header();
?>
<main id="main">
    <section class="page_hero">
        <?= $bg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <div class="container-xl">
            <h1 class="page_hero__title">Valewood Bathrooms News and Insights</h1>
            <h2 class="text-white"><?= esc_html( $category->name ); ?></h2>
            <a href="/contact/" class="button button-outline">Contact us</a>
        </div>
    </section>

    <div class="container-xl py-5 news">
        <?php
        if ( get_the_content( null, false, $page_for_posts ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '<div class="mb-5">' . apply_filters( 'the_content', get_the_content( null, false, $page_for_posts ) ) . '</div>';
			// phpcs:enable
        }

        $cats = get_categories();
        ?>
        <div class="filters mb-4">
            <?php
            echo '<a class="button button--sm" href="/insights/">All</a>';
            foreach ( $cats as $single_cat ) {
                $active_class = ( $single_cat->term_id === $category->term_id ) ? ' active' : ''; // Check if it's the current category.
                echo '<a class="button button--sm' . esc_attr( $active_class ) . '" href="' . esc_url( get_category_link( $single_cat->term_id ) ) . '">' . esc_html( $single_cat->cat_name ) . '</a>';
            }
            ?>
        </div>
        <div class="news__grid">
            <?php
            $first = true;
            while ( have_posts() ) {
                the_post();
                $img      = get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'news__img' ) ) ? get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'news__img' ) ) : '<img src="' . esc_url( get_stylesheet_directory_uri() ) . '/img/default-blog.jpg" class="news__img">';
                $cats     = get_the_category();
                $category = wp_list_pluck( $cats, 'name' );
                $class    = $first ? 'news__first' : '';

                if ( has_category( 'event' ) ) {
                    $the_date = get_field( 'start_date', get_the_ID() );
                } else {
                    $the_date = null;
                }

            	?>
                <a href="<?= esc_url( get_the_permalink() ); ?>"
                    class="news__item <?= esc_attr( $class ); ?>">
                    <div class="news__image">
                        <?= $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php
                        if ( ! empty( $category ) ) {
                            echo '<div class="pills">';
                            foreach ( $category as $single_category ) {
                                echo '<div class="catflash">' . esc_html( $single_category ) . '</div>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <div class="news__inner">
                        <h3><?= esc_html( get_field( 'title' ) ? get_field( 'title' ) : get_the_title() ); ?></h3>
                        <?php
                        if ( $first ) {
                        	?>
							<div><?= esc_html( get_field( 'excerpt' ) ? get_field( 'excerpt' ) : wp_trim_words( get_the_content(), 25 ) ); ?></div>
                        	<?php
                        }
                        ?>
                        <div class="news__meta">
                            <div class="news__date">
								<?= esc_html( $the_date ); ?>
                            </div>
                            <div class="news__link">Read More</div>
                        </div>
                    </div>
                </a>
            	<?php
                $first = false;
            }
            ?>
        </div>
        <div class="mt-5">
            <?= understrap_pagination(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    </div>
</main>
<?php
get_footer();
?>