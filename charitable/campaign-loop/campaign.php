<?php
/**
 * The template for displaying campaign content within loops.
 *
 * This overrides the default Charitable template defined at charitable/templates/campaign-loop/campaign.php
 *
 * @author  Studio 164a
 * @package Reach
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$campaign = charitable_get_current_campaign();

?>
<li id="campaign-<?php echo get_the_ID(); ?>" <?php post_class( array( 'campaign-widget', 'campaign', 'block', 'cf' ) ); ?>>
	<?php

	/**
	 * @hook charitable_campaign_content_loop_before
	 */
	do_action( 'charitable_campaign_content_loop_before', $campaign, $view_args );

	/**
	 * @hook charitable_campaign_content_loop_before_title
	 */
	do_action( 'charitable_campaign_content_loop_before_title', $campaign, $view_args );

	?>
	<div class="title-wrapper">
		<h3 class="block-title">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_parent"><?php the_title(); ?></a>
		</h3>
	</div>
	<?php

	/**
	 * @hook charitable_campaign_content_loop_after_title
	 */
	do_action( 'charitable_campaign_content_loop_after_title', $campaign, $view_args );

	/**
	 * @hook charitable_campaign_content_loop_after
	 */
	do_action( 'charitable_campaign_content_loop_after', $campaign, $view_args );

	?>
</li>
