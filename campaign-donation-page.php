<?php
/**
 * Donation page template.
 *
 * This template is only used if Charitable is active.
 *
 * @package Reach
 */

if ( ! reach_has_charitable() ) :
	get_template_part( 'page' );
	return;
endif;

get_header( 'stripped' );

?>
<main id="main" class="site-main site-content cf">
	<div class="layout-wrapper">
		<div id="primary" class="content-area">
		<?php

		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();

				$campaign = charitable_get_current_campaign();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php get_template_part( 'partials/banner' ); ?>
					<div class="block entry-block">
						<div class="entry">
							<?php $campaign->get_donation_form()->render() ?>
						</div><!-- .entry -->
					</div><!-- .entry-block -->
				</article><!-- post-<?php the_ID() ?> -->
				<?php

			endwhile;
		endif;
		?>
		</div><!-- #primary -->
		<aside id="secondary" class="campaign-benefiting" role="complementary">

			<p class="header"><?php _e( 'Thank you for supporting this campaign', 'reach' ) ?></p>

			<?php
			if ( $campaign && has_post_thumbnail( $campaign->ID ) ) :

				echo get_the_post_thumbnail( $campaign->ID, 'reach-post-thumbnail-medium' );

			endif ?>

			<div class="campaign-title">
				<a href="<?php echo get_permalink( $campaign->ID ) ?>" title="<?php the_title_attribute() ?>"><?php echo get_the_title( $campaign->ID ) ?></a>
			</div><!-- .campaign-title -->
		</aside><!-- .campaign-benefiting -->
	</div><!-- .layout-wrapper -->
<!-- #main -->
<?php

get_footer( 'stripped' );
