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
<section class="hero">
	<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<?= $bg; ?>
			</div>
		</div>
	</div>
	<div class="hero__overlay"></div>
	<div class="hero__content d-flex align-items-center">
		<div class="container">
			<div class="row">
				<div class="col-md-6 text-white">
					<?php
					$d = 0;
					?>
                    <img data-aos="fade" src="<?= esc_url( get_stylesheet_directory_uri() . '/img/sai-logo--wo.svg' ); ?>" alt="Sai Alupack Logo" class="hero__logo" />
                    <?php
                    $d += 100;
                    ?>
					<h1 data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>"><div class="hero__title">News &amp; Insights</div>
					<?php
					$d += 100;
					?>
					<div class="hero__buttons d-flex flex-wrap gap-2">
						<span data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>">
							<a class="button button-outline hero__button mt-3"
								href="/contact-us/"
								target="_self'">
								Contact Us
							</a>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
	<div class="container pb-5">
		<div class="yoast-breadcrumbs">
		<?php
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		}
		?>
		</div>
    <div>
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
            echo '<a class="button button--sm active" href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">All</a>';
            foreach ( $cats as $category_item ) {
                echo '<a class="button button--sm" href="' . esc_url( get_category_link( $category_item->term_id ) ) . ' ">' . esc_html( $category_item->cat_name ) . '</a>';
            }
            ?>
        </div>
        <div class="news__grid">
            <?php
            $first = true;

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
                } else {
                    $class = '';
                }

            	?>
                <a href="<?= esc_url( get_the_permalink() ); ?>"
                    class="news__item <?= esc_attr( $class ); ?>" data-aos="fade">
                    <div class="news__inner">
                        <div class="news__meta">
                            <div class="news__date">
                                Published <?= esc_html( get_the_date( 'dS M, Y' ) ); ?>
                            </div>
                            <div class="news__read">
                                <i class="fa-regular fa-hourglass"></i> <?= estimate_reading_time_in_minutes( get_the_content() ); ?> min
                            </div>
                        </div>
						<h2><?= esc_html( get_field( 'title' ) ? get_field( 'title' ) : get_the_title() ); ?></h2>
                        <?php
                        if ( $first ) {
                        	?>
                            <div><?= esc_html( get_field( 'excerpt' ) ? get_field( 'excerpt' ) : wp_trim_words( get_the_content(), 25 ) ); ?> <u>Read More</u></div>
                        	<?php
						}
                        ?>
                    </div>
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