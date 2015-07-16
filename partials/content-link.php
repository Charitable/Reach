<?php 
/**
 * Content of link format post.
 * 
 * @package 	Reach
 */
?>
<article id="post-<?php the_ID() ?>" <?php post_class() ?>>	
	<?php 
	
	get_template_part( 'featured_image' );

	if ( is_single() ) :

		get_template_part('meta', 'above');

	endif;

	reach_post_header();

	$content = reach_link_format_the_content( null, false, false );

	if ( strlen($content) ) : 

	?>
		<div class="entry cf">
			<?php echo $content ?>
		</div>
	<?php 

	endif;

	if ( is_single() ) :
			
		get_template_part( 'partials/meta', 'taxonomy' );		

	else :				

		get_template_part('meta', 'below');

	endif ?>	
</article>