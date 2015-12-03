<?php
/**
 * @package Reach
 */
?>
<article id="post-<?php the_ID() ?>" <?php post_class() ?>>
	<?php 

	/* Add the header banner if this is a single post. */
	if ( is_single() ) : 
		get_template_part( 'partials/banner' );
	endif; 

	?>
	<div class="block entry-block">
		<?php

		echo reach_get_media( array( 
			'split_media' 	=> true, 
			'meta_key' 		=> 'video'
		) );

		/* Display meta above content if this is a single post. */
		if ( is_single() ) :
			get_template_part( 'meta', 'above' );

		/* If this is an archive, display the post title. */
		else : 
			reach_post_header();
		endif;

		?>
		<div class="entry cf">				
			<?php 

			the_content(); 			

			wp_link_pages( array( 
				'before' => '<p class="entry_pages">' . __( 'Pages: ', 'reach' ) 
			) );

			?>
		</div><!-- .entry -->
		<?php 

		/* Display taxonomy meta on single posts. */
		if ( is_single() ) :
			get_template_part( 'partials/meta', 'taxonomy' );

		/* Display alternative meta on archives. */
		else :
			get_template_part( 'meta', 'below' );
		endif;

		?>
	</div><!-- .entry-block -->
</article><!-- post-<?php the_ID() ?> -->