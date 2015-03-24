<?php 
/**
 * Campaign video.
 *
 * @package Benny
 */

$campaign  = charitable_get_current_campaign();

if ( ! $campaign->has_video() ) {
	return;
}
?>
<section class="campaign-video">
	<?php echo $campaign->embed_video() ?>
</section><!-- .campaign-video -->