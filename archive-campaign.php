<?php
/**
 * Campaign post type archive.
 *
 * This template is only used when Charitable is activated.
 *
 * @package     Reach
 */

get_header();

?>
<main id="main" class="site-main site-content cf">
	<div class="layout-wrapper">
		<div id="primary" class="content-area no-sidebar">
			<?php
			get_template_part( 'partials/banner' );
			?>
			<div class="campaigns-grid-wrapper">
				<?php

				/**
				 * This renders a loop of campaigns that are displayed with the
				 * `reach/charitable/campaign-loop.php` template file.
				 *
				 * @see charitable_template_campaign_loop
				 */
				charitable_template_campaign_loop( false, 4 );

				reach_paging_nav( __( 'Older Campaigns', 'reach' ), __( 'Newer Campaigns', 'reach' ) );

				?>
			</div><!-- .campaigns-grid-wrapper -->
		</div><!-- #primary -->
	</div><!-- .layout-wrapper -->
</main><!-- #main -->
<?php

get_footer();
