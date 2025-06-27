<h2>Sectors We Serve</h2>

<?php if (have_rows('sectors', 'option')): ?>
	<ul>
	<?php while (have_rows('sectors', 'option')): the_row(); ?>
		<li>
			<strong><?php the_sub_field('sector_title'); ?>:</strong>
			<?php the_sub_field('sector_description'); ?>
		</li>
	<?php endwhile; ?>
	</ul>
<?php endif; ?>
