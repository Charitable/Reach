<?php
/**
 * Single campaign template.
 *
 * This template is only used if Charitable is active.
 *
 * @package Reach
 */

if ( ! reach_has_charitable() ) :
	get_template_part( 'single' );
	return;
endif;

get_header();

?>

<main id="main" class="site-main site-content cf">

<?php

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		/**
		 * @var     Charitable_Campaign
		 */
		$campaign = charitable_get_current_campaign();

		/**
		 * @hook charitable_single_campaign_before
		 */
		do_action( 'charitable_single_campaign_before', $campaign );

		?>
		<div class="layout-wrapper">
			<div id="primary" class="content-area 
			<?php
			if ( ! is_active_sidebar( 'sidebar_campaign' ) ) :
				?>
				no-sidebar<?php endif ?>">
				<?php
				/**
				 * @hook charitable_campaign_content_before
				 */
				do_action( 'charitable_campaign_content_before', $campaign );

				get_template_part( 'partials/content', 'campaign' );

				/**
				 * @hook charitable_campaign_content_after
				 */
				do_action( 'charitable_campaign_content_after', $campaign );

				?>
			</div><!-- #primary -->

			<?php get_sidebar( 'campaign' ); ?>

		</div><!-- .layout-wrapper -->
		<?php

		/**
		 * @hook charitable_single_campaign_after
		 */
		do_action( 'charitable_single_campaign_after' );

	endwhile;
endif;

?>

</main><!-- #main -->

<?php

get_footer();
