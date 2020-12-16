<?php
/**
 * Displays the campaign loop.
 *
 * This overrides the default Charitable template defined at charitable/templates/campaign-loop.php
 *
 * @author  Studio 164a
 * @package Reach
 * @since   1.0.0
 * @version 1.0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$campaigns = $view_args['campaigns'];
$columns   = $view_args['columns'];
$args      = array();

if ( ! $campaigns->have_posts() ) :
	return;
endif;

if ( $columns > 1 ) :
	$loop_class = sprintf( 'campaign-loop campaign-grid campaign-grid-%d', $columns );
else :
	$loop_class = 'campaign-loop campaign-grid';
endif;

/**
 * @hook charitable_campaign_loop_before
 */
do_action( 'charitable_campaign_loop_before', $campaigns, $args );
?>
<ol class="<?php echo $loop_class; ?>">
<?php

while ( $campaigns->have_posts() ) :

	$campaigns->the_post();

	/**
	 * Loads `reach/charitable/campaign-loop/campaign.php`
	 */
	charitable_template( 'campaign-loop/campaign.php', $args );

endwhile;

wp_reset_postdata();

?>
</ol>
<?php
/**
 * @hook charitable_campaign_loop_after
 */
do_action( 'charitable_campaign_loop_after', $campaigns, $args );
