<?php
/**
 * A thin wrapper around Hybrid_Media_Grabber that adds support for grabbing media from post meta.
 *
 * @package		Reach/Classes/Reach_Media_Grabber
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2019, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Reach_Media_Grabber' ) ) :

	/**
	 * Reach_Media_Grabber
	 *
	 * @since 		1.0.0
	 */
	class Reach_Media_Grabber extends Hybrid_Media_Grabber {

		/**
		 * Constructor method.  Sets up the media grabber.
		 *
		 * @global  object $wp_embed
		 * @global  int    $content_width
		 * @param 	array $args
		 * @return  void
		 * @access 	public
		 * @since  	1.0.0
		 */
		public function __construct( $args = array() ) {
			global $wp_embed, $content_width;

			/* Use WP's embed functionality to handle the [embed] shortcode and autoembeds. */
			add_filter( 'hybrid_media_grabber_embed_shortcode_media', array( $wp_embed, 'run_shortcode' ) );
			add_filter( 'hybrid_media_grabber_autoembed_media',       array( $wp_embed, 'autoembed' ) );

			/* Don't return a link if embeds don't work. Need media or nothing at all. */
			add_filter( 'embed_maybe_make_link', '__return_false' );

			/* Set up the default arguments. */
			$defaults = array(
				'post_id'     => get_the_ID(),   // post ID (assumes within The Loop by default)
				'type'        => 'video',        // audio|video
				'before'      => '',             // HTML before the output
				'after'       => '',             // HTML after the output
				'split_media' => false,          // Splits the media from the post content
				'width'       => $content_width, // Custom width. Defaults to the theme's content width.
			);

			/* Set the object properties. */
			$this->types   = apply_filters( 'hybrid_media_grabber_valid_types', array( 'audio', 'video' ) );
			$this->args    = apply_filters( 'hybrid_media_grabber_args', wp_parse_args( $args, $defaults ) );
			$this->content = get_post_field( 'post_content', $this->args['post_id'] );
			$this->type    = isset( $this->args['type'] ) && in_array( $this->args['type'], $this->types ) ? $this->args['type'] : 'video';
			$this->valid_shortcodes = apply_filters( 'hybrid_media_grabber_valid_shortcodes', array( $this->type, 'playlist', 'embed', 'blip.tv', 'dailymotion', 'flickr', 'ted', 'vimeo', 'vine', 'youtube', 'wpvideo', 'soundcloud', 'bandcamp' ) );

			/* Find the media related to the post. */
			$this->set_media();
		}

		/**
		 * Sets the media for the post.
		 *
		 * @return 	void
		 * @access 	public
		 * @since  	1.0.0
		 */
		public function set_media() {
			if ( isset( $this->args['meta_key'] ) ) {
				$url = get_post_meta( get_the_ID(), $this->args['meta_key'], true );
				$this->media = do_shortcode( "[{$this->type} src='{$url}']" );
			}

			parent::set_media();
		}

		/**
		 * WordPress has a few shortcodes for handling embedding media:  [audio], [video], and [embed].  This
		 * method figures out the shortcode used in the content.  Once it's found, the appropriate method for
		 * the shortcode is executed.
		 *
		 * @return 	void
		 * @access 	public
		 * @since  	1.0.0
		 */
		public function do_shortcode_media() {

			/* Finds matches for shortcodes in the content. */
			preg_match_all( '/' . get_shortcode_regex() . '/s', $this->content, $matches, PREG_SET_ORDER );

			/* If matches are found, loop through them and check if they match one of WP's media shortcodes. */
			if ( ! empty( $matches ) ) {

				foreach ( $matches as $shortcode ) {

					if ( ! in_array( $shortcode[2], $this->valid_shortcodes ) ) {
						continue;
					}

					/* Call the method related to the specific shortcode found and break out of the loop. */
					if ( in_array( $shortcode[2], array( 'playlist', 'embed', $this->type ) ) ) {
						call_user_func( array( $this, "do_{$shortcode[2]}_shortcode_media" ), $shortcode );
						break;
					}

					/* Check for Jetpack audio/video shortcodes. */
					if ( in_array( $shortcode[2], array( 'blip.tv', 'dailymotion', 'flickr', 'ted', 'vimeo', 'vine', 'youtube', 'wpvideo', 'soundcloud', 'bandcamp' ) ) ) {
						$this->do_jetpack_shortcode_media( $shortcode );
						break;
					}

					/* Call shortcode handler for shortcode not handled before. */
					$callback = apply_filters( 'hybrid_media_grabber_do_shortcode_callback', array( $this, 'do_default_shortcode_media' ), $shortcode[2] );
					call_user_func( $callback, $shortcode );
				}
			}
		}

		/**
		 * Default callback method used to render a shortcode.
		 *
		 * @param  	array 	$shortcode
		 * @return 	void
		 * @access 	public
		 * @since  	1.0.0
		 */
		public function do_default_shortcode_media( $shortcode ) {

			$this->original_media = array_shift( $shortcode );

			$this->media = do_shortcode( $this->original_media );
		}
	}

endif; // End class_exists check
