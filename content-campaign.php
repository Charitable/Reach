<?php 
/**
 * Campaign content block.
 *
 * @package Franklin
 */
?>
<div id="campaign-<?php echo get_the_ID() ?>" <?php post_class('campaign-content') ?>>
	<div class="title-wrapper">	
		<h2 class="block-title"><?php the_title() ?></h2>
	</div>
	<div class="entry">
		<?php the_content() ?>
	</div>
</div>