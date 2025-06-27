<h1><?php the_field('about_heading', 'option'); ?></h1>
<p><?php the_field('about_text', 'option'); ?></p>

<?php if ($image = get_field('about_image', 'option')): ?>
	<img src="<?php echo esc_url($image['url']); ?>" alt="" style="max-width: 100%;">
<?php endif; ?>
