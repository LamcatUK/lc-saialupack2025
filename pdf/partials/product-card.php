<td style="width:33%;padding:10px;border:1px solid #4682b4;vertical-align:top;">
	<?php if ($image = get_the_post_thumbnail_url(get_the_ID(), 'medium')): ?>
		<img src="<?php echo esc_url($image); ?>" alt="" class="product-img">
	<?php endif; ?>

	<p><strong><?php the_title(); ?></strong></p>
	<p><strong>Code:</strong> <?php the_field('product_code'); ?></p>
	<table class="product-specs">
		<?php foreach (['top_out', 'top_in', 'base', 'height_depth', 'capacity', 'package_size'] as $field): ?>
			<?php if ($value = get_field($field)): ?>
				<tr><td><?php echo ucfirst(str_replace('_', ' ', $field)); ?></td><td>: <?php echo $value; ?></td></tr>
			<?php endif; ?>
		<?php endforeach; ?>
	</table>
</td>
