<?php
/**
 * Block template for LC Latest News.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;

$posts = get_posts(
    array(
        'post_type'      => 'post',
        'posts_per_page' => 5,
    )
);
?>
<section class="latest_news py-5 has-grey-100-background-color">
    <div class="container news">
        <h2>Latest News</h2>
        <p class="mb-4">Stay updated with our latest news and insights.</p>
        <div class="splide" id="latestNewsSplide">
            <div class="splide__track pb-3">
                <ul class="splide__list">
                    <?php
                    if ( $posts ) {
                        foreach ( $posts as $news_post ) {
                            setup_postdata( $news_post );
                            ?>
					<li class="splide__slide">
						<a href="<?= esc_url( get_the_permalink( $news_post ) ); ?>" class="news__card">
							<img src="<?= esc_url( get_the_post_thumbnail_url( $news_post->ID, 'large' ) ); ?>"
								alt="" class="news__image">
							<div class="news__content">
								<div class="news__meta">
									<div class="news__date">
										Published <?= esc_html( get_the_date( 'dS M, Y', $news_post ) ); ?>
									</div>
									<div class="news__read">
										<i class="fa-regular fa-hourglass"></i> <?= estimate_reading_time_in_minutes( get_the_content() ); ?> min
									</div>
								</div>
								<h3 class="news__title"><?= esc_html( get_the_title( $news_post ) ); ?></h3>
							</div>
						</a>
					</li>
                            <?php
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<li><p>No news found.</p></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.Splide) {
        new Splide('#latestNewsSplide', {
            type   : 'loop',
            perPage: 4,
            gap    : '1rem',
			autoplay: true,
			pagination: false,
            breakpoints: {
                1200: { perPage: 3 },
                768: { perPage: 2 },
                576: { perPage: 1 }
            }
        }).mount();
    }
});
</script>