<?php 
/**
 * Campaign stats.
 *
 * @package Reach
 */

$campaign 			= charitable_get_current_campaign();
$currency_helper 	= charitable()->get_currency_helper();
?>
<ul class="campaign-stats">
	<li class="barometer" data-progress="<?php echo $campaign->get_percent_donated_raw() ?>" 
		data-width="42" 
		data-height="42" 
		data-strokewidth="8" 
		data-stroke="<?php echo get_theme_mod('secondary_border', '#dbd5d1') ?>" 
		data-progress-stroke="<?php echo get_theme_mod('accent_colour', '#d95b43') ?>">
	</li>
	<li class="campaign-raised">
		<span><?php echo number_format( $campaign->get_percent_donated_raw(), 2 ) ?><sup>%</sup></span>
		<?php _e( 'Funded', 'reach' ) ?>		
	</li>
	<li class="campaign-pledged">
		<span><?php echo $currency_helper->get_monetary_amount( $campaign->get_donated_amount() ) ?></span>
		<?php _e( 'Pledged', 'reach' ) ?>				
	</li>
	<li class="campaign-time-left">
		<?php echo $campaign->get_time_left() ?>				
	</li>		
</ul>