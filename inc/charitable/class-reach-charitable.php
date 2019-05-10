<?php
/**
 * Handles how Charitable features are integrated into the theme.
 *
 * @package     Reach/Classes/Reach_Charitable
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2019, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
if ( ! class_exists( 'Reach_Charitable' ) ) :

	/**
	 * Reach_Charitable
	 *
	 * @since       1.0.0
	 */
	class Reach_Charitable {

		/**
		 * This creates an instance of this class.
		 *
		 * If the reach_theme_start hook has already run, this will not do anything.
		 *
		 * @param   Reach_Theme     $theme
		 * @static
		 * @access  public
		 * @since   1.0.0
		 */
		public static function start( Reach_Theme $theme ) {
			if ( ! $theme->is_start() ) {
				return;
			}

			new Reach_Charitable();
		}

		/**
		 * Create object instance.
		 *
		 * @access  private
		 * @since   1.0.0
		 */
		private function __construct() {
			$this->attach_hooks_and_filters();
		}

		/**
		 * Set up hooks and filters.
		 *
		 * @return  void
		 * @access  private
		 * @since   1.0.0
		 */
		private function attach_hooks_and_filters() {
			add_action( 'after_setup_theme', array( $this, 'load_dependencies' ), 20 ); // Priority must be greater than 10
			add_action( 'wp_enqueue_scripts', array( $this, 'setup_localized_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_styles' ), 100 );
			add_filter( 'reach_script_dependencies', array( $this, 'setup_script_dependencies' ) );
			add_filter( 'charitable_campaign_post_type', array( $this, 'turn_on_campaign_archives' ) );
			add_filter( 'body_class', array( $this, 'add_body_classes' ) );
			add_filter( 'reach_banner_title', array( $this, 'set_banner_title' ) );
			add_filter( 'charitable_is_in_user_dashboard', array( $this, 'load_donation_receipt_in_user_dashboard' ) );
			add_filter( 'charitable_user_dashboard_template', array( $this, 'set_user_dashboard_template' ) );
			add_filter( 'charitable_donation_processing_page_template', array( $this, 'set_stripped_template' ) );
			add_filter( 'charitable_campaign_ended', 'reach_campaign_ended_text' );
			add_filter( 'charitable_force_user_dashboard_template', '__return_true' );
			add_filter( 'charitable_campaign_submission_campaign_fields', array( $this, 'campaign_submission_fields' ) );
			add_filter( 'charitable_ambassadors_my_campaign_thumbnail_size', array( $this, 'my_campaign_thumbnail_size' ) );
			add_filter( 'charitable_use_campaign_template', '__return_false' );
			add_filter( 'charitable_modal_window_class', array( $this, 'modal_window_class' ) );
			add_filter( 'charitable_campaign_video_embed_args', array( $this, 'video_embed_args' ), 5 );
			add_filter( 'charitable_add_custom_styles', '__return_false' );
			add_filter( 'charitable_campaign_widget_thumbnail_size', array( $this, 'set_campaign_widget_thumbnail_size' ) );
		}

		/**
		 * Include required files.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function load_dependencies() {
			require_once( 'functions/helper-functions.php' );
			require_once( 'functions/template-hooks.php' );
			require_once( 'functions/template-tags.php' );
		}

		/**
		 * Set up localized script.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function setup_localized_scripts() {
			wp_localize_script('reach', 'REACH_CROWDFUNDING', array(
				'need_minimum_pledge'   => __( 'Your pledge must be at least the minimum pledge amount.', 'reach' ),
				'years'                 => __( 'Years', 'reach' ),
				'months'                => __( 'Months', 'reach' ),
				'weeks'                 => __( 'Weeks', 'reach' ),
				'days'                  => __( 'Days', 'reach' ),
				'hours'                 => __( 'Hours', 'reach' ),
				'minutes'               => __( 'Minutes', 'reach' ),
				'seconds'               => __( 'Seconds', 'reach' ),
				'year'                  => __( 'Year', 'reach' ),
				'month'                 => __( 'Month', 'reach' ),
				'day'                   => __( 'Day', 'reach' ),
				'hour'                  => __( 'Hour', 'reach' ),
				'minute'                => __( 'Minute', 'reach' ),
				'second'                => __( 'Second', 'reach' ),
				'timezone_offset'       => reach_get_timezone_offset(),
			) );
		}

		/**
		 * Dequeue styles.
		 *
		 * @return  void
		 * @access  public
		 * @since   0.9.24
		 */
		public function dequeue_styles() {
			wp_deregister_style( 'charitable-ambassadors-my-campaigns-css' );
			wp_deregister_style( 'charitable-ambassadors-styles' );
		}

		/**
		 * Register scripts required for crowdfunding functionality.
		 *
		 * @param   array       $dependencies
		 * @return  array
		 * @access  public
		 * @since   1.0.0
		 */
		public function setup_script_dependencies( $dependencies ) {
			$dependencies[] = 'raphael';
			$dependencies[] = 'jquery-masonry';

			wp_register_script( 'raphael', get_template_directory_uri() . '/js/vendors/raphael/raphael-min.js', array( 'jquery' ), reach_get_theme()->get_theme_version(), true );

			if ( 'campaign' == get_post_type() ) {
				wp_register_script( 'countdown-plugin', get_template_directory_uri() . '/js/vendors/jquery-countdown/jquery.plugin.min.js', array( 'jquery' ), reach_get_theme()->get_theme_version(), true );
				wp_register_script( 'countdown', get_template_directory_uri() . '/js/vendors/jquery-countdown/jquery.countdown.min.js', array( 'countdown-plugin' ), reach_get_theme()->get_theme_version(), true );

				$dependencies[] = 'countdown';
			}

			return $dependencies;
		}

		/**
		 * Turn on post type archives for the campaigns.
		 *
		 * @param   array $post_type_args
		 * @return  array
		 * @access  public
		 * @since   1.0.0
		 */
		public function turn_on_campaign_archives( $post_type_args ) {
			$post_type_args['has_archive'] = true;
			return $post_type_args;
		}

		/**
		 * If we are viewing a single campaign page, add a class to the body for the style of donation form.
		 *
		 * @param   string[] $classes
		 * @return  string[]
		 * @access  public
		 * @since   1.0.0
		 */
		public function add_body_classes( $classes ) {
			if ( charitable_is_campaign_page() ) {

				$campaign = new Charitable_Campaign( get_the_ID() );

				if ( $campaign->has_ended() ) {
					$classes[] = 'campaign-ended';
				} else {
					$classes[] = 'donation-form-display-' . charitable_get_option( 'donation_form_display', 'separate_page' );
				}
			}

			return $classes;
		}

		/**
		 * Set banner title for campaign donation page.
		 *
		 * @global  WP_Query    $wp_query
		 * @param   string      $title
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function set_banner_title( $title ) {
			global $wp_query;

			if ( isset( $wp_query->query_vars['donate'] ) && is_singular( 'campaign' ) ) {
				$title = get_the_title();
			} elseif ( charitable_is_page( 'donation_receipt_page' ) ) {
				$title = apply_filters( 'reach_banner_title_donation_receipt', __( 'Donation Receipt', 'reach' ) );
			} elseif ( charitable_get_user_dashboard()->in_nav() ) {
				$title = apply_filters( 'reach_banner_title_user_dashboard', __( 'Dashboard', 'reach' ) );
			}

			return $title;
		}

		/**
		 * Load the donation receipt inside the user dashboard.
		 *
		 * @param   boolean $ret
		 * @return  boolean
		 * @access  public
		 * @since   1.0.0
		 */
		public function load_donation_receipt_in_user_dashboard( $ret ) {
			if ( is_front_page() || is_home() ) {
				return false;
			}

			if ( charitable_is_page( 'donation_receipt_page' ) ) {
				$ret = true;
			}

			return $ret;
		}

		/**
		 * Use the User Dashboard page template for donation receipts.
		 *
		 * @param   string $template
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function set_user_dashboard_template( $template ) {
			return 'page-templates/user-dashboard.php';
		}

		/**
		 * Use the Stripped page template.
		 *
		 * This is used for the Donation Processing page.
		 *
		 * @param   string $template
		 * @return  string
		 * @access  public
		 * @since   1.0.3
		 */
		public function set_stripped_template( $template ) {
			return 'page-templates/stripped.php';
		}

		/**
		 * Apply custom styles to the WP editor.
		 *
		 * @return  array
		 * @access  public
		 * @since   1.0.0
		 */
		public function campaign_submission_fields( $fields ) {
			if ( ! isset( $fields['content'] ) ) {
				return $fields;
			}

			$fields['content']['editor'] = array(
				'tinymce' => array(
					'content_css' => get_template_directory_uri() . '/css/editor-style.css',
				),
			);

			return $fields;
		}

		/**
		 * Set the thumbnail size for campaign images displayed on the "My Campaigns" page.
		 *
		 * @param   string  $size
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function my_campaign_thumbnail_size( $size ) {
			return 'campaign-thumbnail-medium';
		}

		/**
		 * Set the modal window class.
		 *
		 * @param   string  $class
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function modal_window_class( $class ) {
			return 'modal';
		}

		/**
		 * Video embed width argument set to 1098px (fullwidth).
		 *
		 * @param   array   $args
		 * @return  array
		 * @access  public
		 * @since   1.0.0
		 */
		public function video_embed_args( $args ) {
			$args['width'] = 1098;
			return $args;
		}

		/**
		 * Set the thumbnail size for campaign widgets.
		 *
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function set_campaign_widget_thumbnail_size() {
			return 'reach-post-thumbnail-medium';
		}
	}

endif;
