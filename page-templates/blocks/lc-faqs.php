<?php
/**
 * Block template for LC FAQs.
 *
 * @package lc-saialupack2025
 */

defined( 'ABSPATH' ) || exit;
if ( have_rows( 'faqs' ) ) {
	?>
<section class="has-grey-100-background-color">
	<div class="container py-5">
		<h2>Frequently asked questions</h2>
		<div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">
	<?php
	while ( have_rows( 'faqs', $current_term ) ) {
		the_row();
		$question = get_sub_field( 'question' );
		$answer   = get_sub_field( 'answer' );
		?>
		<div class="schema-faq-section" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
          	<div class="schema-faq-question" itemprop="name"><strong><?php the_sub_field( 'question' ); ?></strong></div>
          	<div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
            	<div class="schema-faq-answer" itemprop="text"><?php the_sub_field( 'answer' ); ?></div>
          	</div>
        </div>
		<?php
	}
	?>
		</div>
	</div>
</section>
	<?php
}