<?php
/**
 * Responsible for setting up theme-specific customisation of Tribe Events.
 *
 * @package     Reach/Classes/Reach_Tribe_Events
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2019, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Reach_Tribe_Events' ) ) :

	/**
	 * Reach_Tribe_Events
	 *
	 * @since       1.0.0
	 */
	class Reach_Tribe_Events {

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

			new Reach_Tribe_Events();
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
			remove_action( 'tribe_events_single_event_after_the_content', array( 'Tribe__Events__iCal', 'single_event_links' ) );
			add_action( 'tribe_events_single_event_before_the_content', array( 'Tribe__Events__iCal', 'single_event_links' ) );

			add_filter( 'reach_banner_title', array( $this, 'set_banner_title' ) );
			add_filter( 'reach_banner_subtitle', array( $this, 'set_banner_subtitle' ) );
		}

		/**
		 * Set the banner title for a single event.
		 *
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function set_banner_title( $title ) {
			if ( is_singular( 'tribe_events' ) ) {
				$title = get_the_title();
			}

			return $title;
		}

		/**
		 * Set the banner subtitle for a single event.
		 *
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function set_banner_subtitle( $subtitle ) {
			if ( is_singular( 'tribe_events' ) ) {
				$subtitle = tribe_events_event_schedule_details( get_the_ID() );
			}

			return $subtitle;
		}
	}

endif; // End class_exists check
