<?php
/**
 * Campaign stats.
 *
 * Override this template by copying it to your-child-theme/charitable/campaign-loop/stats.php
 *
 * @package Reach
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * @var 	Charitable_Campaign
 */
$campaign = $view_args['campaign'];

/**
 * Get the progress as a number. i.e. 66 = 66%.
 *
 * If the campaign does not have a goal, this will equal false.
 *
 * @var 	int|false
 */
$progress = $campaign->get_percent_donated_raw();

if ( false === $progress ) :
	return;
endif;

?>
<ul class="campaign-stats">
	<li class="barometer"
		data-progress="<?php echo esc_attr( $progress ) ?>"
		data-width="42"
		data-height="42"
		data-strokewidth="8"
		data-stroke="<?php echo esc_attr( get_theme_mod( 'secondary_border', '#dbd5d1' ) ) ?>"
		data-progress-stroke="<?php echo esc_attr( get_theme_mod( 'accent_colour', '#7bb4e0' ) ) ?>">
	</li>
	<li class="campaign-raised">
		<span><?php echo number_format( $progress, 2 ) ?><sup>%</sup></span>
		<?php _e( 'Funded', 'reach' ) ?>
	</li>
	<li class="campaign-pledged">
		<span><?php echo charitable_format_money( $campaign->get_donated_amount() ) ?></span>
		<?php _e( 'Pledged', 'reach' ) ?>
	</li>
	<li class="campaign-time-left">
		<?php echo $campaign->get_time_left() ?>
	</li>
</ul>
