<?php
/**
 * Campaign grid template.
 *
 * This template is used to display a grid of Charitable campaigns. It will
 * not be used if Charitable is not active.
 *
 * @package Reach
 */

if ( ! reach_has_charitable() ) :
	return;
endif;

?>
<div class="campaigns-grid-wrapper">
	<h3 class="section-title"><?php _e( 'Latest Projects', 'reach' ); ?></h3>
	<?php
	$campaigns = Charitable_Campaigns::query();

	charitable_template_campaign_loop( $campaigns, 4 );

	wp_reset_postdata();

	if ( $campaigns->max_num_pages > 1 ) :
		?>
		<p class="center">
			<a class="button button-alt" href="<?php echo esc_url( home_url( apply_filters( 'reach_previous_campaigns_link', '/campaigns/page/2/' ) ) ); ?>">
				<?php echo apply_filters( 'reach_previous_campaigns_text', __( 'Previous Campaigns', 'reach' ) ); ?>
			</a>
		</p>
		<?php
	endif;
	?>
</div><!-- .campaigns-grid-wrapper -->
