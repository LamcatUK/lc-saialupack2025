<div class="product-card">
	<?php if ($image = get_the_post_thumbnail_url(get_the_ID(), 'medium')): ?>
		<img src="<?php echo esc_url($image); ?>" alt="" class="product-img">
	<?php endif; ?>

	<div class="product-info">
		<p><strong><?php the_title(); ?></strong></p>
		<p><strong>Product Code:</strong> <?php the_field('product_code'); ?></p>
		<table class="product-specs">
			<?php if ($top_out = get_field('top_out')): ?>
			<tr><td>Top Out</td><td>: <?php echo $top_out; ?></td></tr>
			<?php endif; ?>
			<?php if ($top_in = get_field('top_in')): ?>
			<tr><td>Top In</td><td>: <?php echo $top_in; ?></td></tr>
			<?php endif; ?>
			<?php if ($base = get_field('base')): ?>
			<tr><td>Base</td><td>: <?php echo $base; ?></td></tr>
			<?php endif; ?>
			<?php if ($depth = get_field('height_depth')): ?>
			<tr><td>Height/Depth</td><td>: <?php echo $depth; ?></td></tr>
			<?php endif; ?>
			<?php if ($capacity = get_field('capacity')): ?>
			<tr><td>Capacity</td><td>: <?php echo $capacity; ?></td></tr>
			<?php endif; ?>
			<?php if ($package = get_field('package_size')): ?>
			<tr><td>Package Size</td><td>: <?php echo $package; ?></td></tr>
			<?php endif; ?>
		</table>
	</div>
</div>
