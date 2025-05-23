<?php
/**
 * The template for displaying search results pages
 *
 * @package lc-saialupack2025
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

// translators: %s is replaced with the search query entered by the user.
$page_title = sprintf( esc_html__( 'Search Results for: %s', 'understrap' ), '<span>' . get_search_query() . '</span>' );
$theme      = 'default';
?>
<main id="main">
	<!-- page_title -->
	<div class="container-xl pb-5">
		<h1><?= wp_kses_post( $page_title ); ?></h1>
		<?= wp_kses_post( do_blocks( '<!-- wp:search {"label":"Enter your search term","showLabel":true,"buttonText":"Search","placeholder":"Search term goes here"} /-->' ) ); ?>
	</div>
	<div class="container-xl pb-4">
		<?php
		// Total number of search results.
		global $wp_query;
		$total_results = $wp_query->found_posts;

		// Get the current page number.
		$current_page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

		// Number of posts per page.
		$posts_per_page = get_query_var( 'posts_per_page' );

		// Calculate the first and last result number on the current page.
		$first_result = ( $current_page - 1 ) * $posts_per_page + 1;
		$last_result  = min( $current_page * $posts_per_page, $total_results );

		// Display the results count.
		if ( $total_results > 0 ) {
			echo '<div class="search-results-count fw-500">';
			printf(
				'Showing %d—%d of %d results for “%s”',
				esc_html( $first_result ),
				esc_html( $last_result ),
				esc_html( $total_results ),
				esc_html( get_search_query() )
			);
			echo '</div>';
		} else {
			echo '<div class="search-results-count">';
			echo 'No results found for "' . get_search_query() . '"';
			echo '</div>';
		}
		?>

	</div>
	<div class="container-xl pb-5">
		<?php
		$all_disclaimers = get_field( 'disclaimers', 'option' );

		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				if ( get_post_type() === 'document' ) {
					$file_link      = get_field( 'file' );
					$attachment_url = wp_get_attachment_url( get_field( 'file', get_the_ID() ) );

					$disclaimers = get_field( 'disclaimers_selection', get_the_ID() ) ?? null;

					if ( ! empty( $disclaimers ) && is_array( $disclaimers ) && isset( $disclaimers[0] ) ) {
						$file_id = esc_attr( $file_link );
						?>
						<div class="border-bottom pb-3 mb-2 w-100" data-bs-toggle="modal" data-bs-target="#modal_<?= esc_attr( $file_id ); ?>" style="cursor:pointer">
							<div class="fs-300"><?= esc_html( get_the_terms( get_the_ID(), 'doccat' )[0]->name ?? '' ); ?></div>
							<div class="d-flex justify-content-between">
								<h2 class="h4 pt-3"><?php the_title(); ?></h2>
								<div class="icon-download"></div>
							</div>
						</div>
						<div class="modal fade" id="modal_<?= esc_attr( $file_id ); ?>" tabindex="-1">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-body">
										<div id="disclaimer-list-<?= esc_attr( $id ); ?>" class="disclaimer-list">
											<?php
											foreach ( $disclaimers as $index => $disclaimer_name ) {

												foreach ( $all_disclaimers as $disclaimer ) {
													if ( $disclaimer['disclaimer_name'] === $disclaimer_name ) {
														?>
														<div class="disclaimer-container" id="disclaimer-<?= esc_attr( $file_id ); ?>">
															<label for="disclaimer-<?= esc_attr( $file_id ); ?>-<?= esc_attr( $index ); ?>" class="switch-label">
																<?= esc_html( $disclaimer['disclaimer_content'] ); ?>
															</label>
															<div class="switch-container">
																<input type="checkbox" class="disclaimer-checkbox" id="disclaimer-<?= esc_attr( $file_id ); ?>-<?= esc_attr( $index ); ?>">
																<label class="switch" for="disclaimer-<?= esc_attr( $file_id ); ?>-<?= esc_attr( $index ); ?>"></label>
															</div>
														</div>
														<?php
														break;
													}
												}
											}
											?>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="button button-secondary" data-bs-dismiss="modal">Close</button>
										<button type="button" class="button accept-button" id="accept-button-<?= esc_attr( $file_id ); ?>" onclick="window.open('<?= esc_url( $attachment_url ); ?>', '_blank')" disabled>Accept</button>
									</div>
								</div>
							</div>
						</div>
						<?php
					} else {
					 	?>
						<div class="border-bottom pb-3 mb-2">
							<a href="<?php echo esc_url( $attachment_url ); ?>" class="w-100" download>
								<div class="fs-300"><?= esc_html( get_the_terms( get_the_ID(), 'doccat' )[0]->name ?? '' ); ?></div>
								<div class="d-flex justify-content-between">
									<h2 class="h4 pt-3"><?php esc_html( the_title() ); ?></h2>
									<div class="icon-download"></div>
								</div>
							</a>
						</div>
						<?php
					}
				} else {
					?>
					<div class="border-bottom pb-3 mb-2">
						<a href="<?= esc_url( get_permalink() ); ?>" rel="bookmark" class="noline">
							<h2 class="h4 pt-3" style="text-transform:initial"><?= esc_html( get_the_title() ); ?></h2>
							<?php the_excerpt(); ?>
						</a>
					</div>
					<?php
				}
			}
			?>
			<div class="mt-4">
				<?php understrap_pagination(); ?>
			</div>
	</div>
			<?php
		}

		echo '</main>';

		get_footer();
