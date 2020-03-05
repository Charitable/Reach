<?php
/**
 * Core functions and definitions for Reach Theme.
 *
 * @package Reach
 */

require_once( 'inc/class-reach-theme.php' );

/**
 * Start the theme.
 */
reach_get_theme();

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

/**
 * Define whether we're in debug mode.
 *
 * This is set to false by default. If set to true,
 * scripts and stylesheets are NOT cached or minified
 * to make debugging easier.
 */
if ( ! defined( 'REACH_DEBUG' ) ) {
	define( 'REACH_DEBUG', false );
}

/**
 * Return the one true instance of the Reach_Theme.
 *
 * @return 	Reach_Theme
 * @since 	1.0.0
 */
function reach_get_theme() {
	return Reach_Theme::get_instance();
}
