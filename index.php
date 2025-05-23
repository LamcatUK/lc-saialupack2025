<?php
/**
 * Index template for Valewood Bathrooms theme.
 *
 * This file is responsible for rendering the main blog page with posts and categories.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

$page_for_posts = get_option( 'page_for_posts' );
$bg             = get_the_post_thumbnail( $page_for_posts, 'full', array( 'class' => 'page_hero__bg' ) );

get_header();
?>
<main id="main">
    <section class="page_hero">
        <?= $bg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <div class="container-xl">
			<h1 class="page_hero__title">Sai Alupack News and Insights</h1>
			<div class="page_hero__content"><a href="/contact/" class="button button-outline">Contact us</a></div>
        </div>
    </section>
    <section class="breadcrumbs py-4">
        <div class="container-xl">
            <?php
            if ( function_exists( 'yoast_breadcrumb' ) ) {
                yoast_breadcrumb();
            }
        	?>
        </div>
    </section>
    <div class="container-xl pb-5 news">
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
            echo '<a class="button button--sm active" href="/insights/">All</a>';
            foreach ( $cats as $category_item ) {
                echo '<a class="button button--sm" href="' . esc_url( get_category_link( $category_item->term_id ) ) . ' ">' . esc_html( $category_item->cat_name ) . '</a>';
            }
            ?>
        </div>
        <div class="news__grid">
            <?php
            $c               = 0;
            $col_count       = 0;
            $columns_per_row = 3;
            $first           = true;

            while ( have_posts() ) {
                the_post();
                $img = get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'news__img' ) );
                if ( ! $img ) {
                    $img = '<img src="' . get_stylesheet_directory_uri() . '/img/default-blog.jpg" class="news__img">';
                }
                $cats     = get_the_category();
                $category = wp_list_pluck( $cats, 'name' );

                if ( $first ) {
                    $class = 'news__first'; // First row class.
                    $delay = 0; // First row has no delay.
                } else {
                    // ✅ Reset delay when starting a new row AFTER the first row.
                    if ( ( $col_count % $columns_per_row ) === 0 ) {
                        $c = 0;
                    }

                    $class = ''; // Normal rows.
                    $delay = $c;
                }

                if ( has_category( 'event' ) ) {
                    $the_date = get_field( 'start_date', get_the_ID() );
                } else {
                    $the_date = null;
                }

                if ( 0 === $col_count % $columns_per_row ) {
                    $c = 0;
                }

            	?>
                <a href="<?= esc_url( get_the_permalink() ); ?>"
                    class="news__item <?= esc_attr( $class ); ?>" data-aos="fade" data-aos-delay="<?= esc_attr( $c ); ?>">
                    <div class="news__image">
                        <?= $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php
                        if ( ! empty( $category ) ) {
                            echo '<div class="pills">';
                            foreach ( $category as $category_name ) {
                                echo '<div class="catflash">' . esc_html( $category_name ) . '</div>';
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

                if ( $first ) {
                    $first = false; // ✅ Mark first row as processed.
                } else {
                    // ✅ Only increment delay for normal rows.
                    $c += 200;
                    ++$col_count;
                }
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