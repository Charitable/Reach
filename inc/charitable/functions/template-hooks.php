<?php
/**
 * Custom Reach template funtions for Charitable hooks.
 *
 * @package     Reach
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2019, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Campaigns loop, before title.
 *
 * @see reach_template_campaign_loop_stats
 * @see reach_template_campaign_loop_creator
 */
add_action( 'charitable_campaign_content_loop_after', 'reach_template_campaign_loop_stats', 6 );
add_action( 'charitable_campaign_content_loop_after', 'reach_template_campaign_loop_creator', 8 );
remove_action( 'charitable_campaign_content_loop_after', 'charitable_template_campaign_progress_bar', 6 );
remove_action( 'charitable_campaign_content_loop_after', 'charitable_template_campaign_loop_donation_stats', 8 );
remove_action( 'charitable_campaign_content_loop_after', 'charitable_template_campaign_loop_donate_link', 10 );

/**
 * Single campaign, top of the page.
 *
 * @see reach_template_campaign_summary
 */
if ( function_exists( 'charitable_ambassadors_template_edit_campaign_link' ) ) {
	add_action( 'charitable_single_campaign_before', 'charitable_ambassadors_template_edit_campaign_link', 2 );
}

add_action( 'charitable_single_campaign_before', 'reach_template_campaign_summary', 2 );

/**
 * Single campaign, before summary.
 *
 * @see reach_template_campaign_title
 * @see charitable_template_campaign_description
 * @see charitable_campaign_summary_before
 */
add_action( 'charitable_campaign_summary_before', 'reach_template_campaign_title', 2 );
add_action( 'charitable_campaign_summary_before', 'charitable_template_campaign_description', 4 );
add_action( 'charitable_campaign_summary_before', 'reach_template_campaign_media_before_summary', 6 );


/**
 * Single campaign summary.
 *
 * @see charitable_template_campaign_finished_notice
 * @see charitable_template_donate_button
 * @see reach_template_campaign_progress_barometer
 * @see reach_template_campaign_stats
 */
add_action( 'charitable_campaign_summary', 'charitable_template_campaign_finished_notice', 2 );
add_action( 'charitable_campaign_summary', 'charitable_template_donate_button', 2 );
add_action( 'charitable_campaign_summary', 'reach_template_campaign_progress_barometer', 4 );
add_action( 'charitable_campaign_summary', 'reach_template_campaign_stats', 6 );
remove_action( 'charitable_campaign_summary', 'charitable_template_campaign_percentage_raised', 4 );
remove_action( 'charitable_campaign_summary', 'charitable_template_campaign_donation_summary', 6 );
remove_action( 'charitable_campaign_summary', 'charitable_template_campaign_donor_count', 8 );
remove_action( 'charitable_campaign_summary', 'charitable_template_donate_button', 12 );

/**
 * Single campaign, after summary.
 *
 * @see reach_template_campaign_share
 */
add_action( 'charitable_campaign_summary_after', 'reach_template_campaign_share', 2 );

/**
 * Single campaign, before content.
 *
 * @see reach_template_campaign_media_before_content
 */
add_action( 'charitable_campaign_content_before', 'reach_template_campaign_media_before_content', 6 );
remove_action( 'charitable_campaign_content_before', 'charitable_ambassadors_template_edit_campaign_link', 2 );
remove_action( 'charitable_campaign_content_before', 'charitable_template_campaign_description', 4 );
remove_action( 'charitable_campaign_content_before', 'charitable_videos_template_campaign_video', 5 );
remove_action( 'charitable_campaign_content_before', 'charitable_template_campaign_summary', 6 );

/**
 * Single campaign, after content.
 *
 * @see reach_template_campaign_after_content_widget_area
 * @see reach_template_campaign_comments
 */
add_action( 'charitable_campaign_content_after', 'reach_template_campaign_after_content_widget_area', 10 );
add_action( 'charitable_campaign_content_after', 'reach_template_campaign_comments', 12 );
