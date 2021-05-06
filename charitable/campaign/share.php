<?php
/**
 * The template for displaying the campaign sharing icons on the campaign page.
 *
 * Override this template by copying it to your-child-theme/charitable/campaign/summary.php
 *
 * @author  Studio 164a
 * @package Reach
 * @since   1.0.0
 * @version 1.2.4
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

$campaign   = $view_args['campaign'];
$permalink  = urlencode( get_the_permalink( $campaign->ID ) );
$title      = urlencode( get_the_title( $campaign->ID ) );
$widget_url = esc_url( charitable_get_permalink( 'campaign_widget_page' ) );

?>
<ul class="campaign-sharing share horizontal rrssb-buttons">
	<li><h6><?php _e( 'Share', 'reach' ) ?></h6></li>
	<li class="share-twitter">
		<a href="https://twitter.com/intent/tweet?text=<?php echo $title ?>&amp;url=<?php echo $permalink ?>" class="popup icon" data-icon="&#xf099;"></a>
	</li>
	<li class="share-facebook">
		<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $permalink ?>" class="popup icon" data-icon="&#xf09a;"></a>
	</li>
	<li class="share-googleplus">
		<a href="https://plus.google.com/share?url=<?php echo $title . $permalink ?>" class="popup icon" data-icon="&#xf0d5;"></a>
	</li>
	<li class="share-linkedin">
		<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $permalink ?>&amp;title=<?php echo $title ?>" class="popup icon" data-icon="&#xf0e1;"></a>
	</li>
	<li class="share-pinterest">
		<a href="http://pinterest.com/pin/create/button/?url=<?php echo $permalink ?>&amp;description=<?php echo $title ?>" class="popup icon" data-icon="&#xf0d2;"></a>
	</li>
	<li class="share-widget">
		<a href="#campaign-widget-<?php the_ID() ?>" class="icon" data-icon="&#xf121;" data-trigger-modal></a>
		<div id="campaign-widget-<?php the_ID() ?>" class="modal">
			<a class="modal-close"></a>
			<h4 class="block-title"><?php _e( 'Share Campaign', 'reach' ) ?></h4>
			<div class="block">
				<p><strong><?php _e( 'Embed Code', 'reach' ) ?></strong></p>
				<pre><?php echo htmlspecialchars( '<iframe src="' . $widget_url . '" width="275" height="468" /></iframe>' ) ?></pre>
			</div>
			<div class="block iframe-block">
				<p><strong><?php _e( 'Preview', 'reach' ) ?></strong></p>
				<iframe src="<?php echo $widget_url ?>" width="275" height="468" /></iframe>
			</div>
		</div>
	</li>
</ul>
