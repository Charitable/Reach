<?php
/**
 * A collection of handy functions that are used by the theme.
 *
 * @package     Reach
 * @category    Functions
 */

/**
 * Returns whether a given is enabled.
 *
 * @return  boolean
 * @since   1.0.0
 */
function reach_has_module( $module ) {
	return in_array( $module, reach_get_theme()->active_modules );
}

/**
 * Returns whether Charitable is enabled.
 *
 * @return  boolean
 * @since   1.0.0
 */
function reach_has_charitable() {
	return reach_has_module( 'charitable' );
}

/**
 * Returns whether EDD is enabled.
 *
 * @return  boolean
 * @since   1.0.0
 */
function reach_has_edd() {
	return reach_has_module( 'edd' );
}

/**
 * Returns the given URL minus the
 *
 * @param   string $url
 * @return  string
 * @since   1.0.0
 */
function reach_condensed_url( $url ) {
	$parts = parse_url( $url );

	if ( isset( $parts['host'] ) ) {

		$output = $parts['host'];

		if ( isset( $parts['path'] ) ) {
			$output .= $parts['path'];
		}
	} else {
		$output = $url;
	}

	return $output;
}

/**
 * A helper function to determine whether the current post should have the meta displayed.
 *
 * @param   WP_Post $post Optional. If a post is not passed, the current $post object will be used.
 * @return  boolean
 * @since   1.0.0
 */
function reach_hide_post_meta( $post = '' ) {

	if ( ! strlen( $post ) ) {
		global $post;
	}

	if ( reach_has_edd() && edd_is_checkout() ) {
		return true;
	}

	if ( function_exists( 'hide_meta_start' ) ) {
		return get_post_meta( $post->ID, '_hide_meta', true );
	} else {
		return get_post_meta( $post->ID, '_reach_hide_post_meta', true );
	}
}

/**
 * Returns the array of supported social media sites for profiles.
 *
 * @return  array
 * @since   1.0.0
 */
function reach_get_social_sites() {

	/* This is used for backwards compatibility. */
	$sites = apply_filters( 'sofa_social_sites', array() );

	$sites = array_merge( $sites, array(
		'facebook'      => __( 'Facebook', 'reach' ),
		'flickr'        => __( 'Flickr', 'reach' ),
		'foursquare'    => __( 'Foursquare', 'reach' ),
		'google-plus'   => __( 'Google+', 'reach' ),
		'instagram'     => __( 'Instagram', 'reach' ),
		'linkedin'      => __( 'Linkedin', 'reach' ),
		'pinterest'     => __( 'Pinterest', 'reach' ),
		'renren'        => __( 'Renren', 'reach' ),
		'skype'         => __( 'Skype', 'reach' ),
		'tumblr'        => __( 'Tumblr', 'reach' ),
		'twitter'       => __( 'Twitter', 'reach' ),
		'vk'            => __( 'VK', 'reach' ),
		'weibo'         => __( 'Weibo', 'reach' ),
		'windows'       => __( 'Windows', 'reach' ),
		'xing'          => __( 'Xing', 'reach' ),
		'youtube'       => __( 'YouTube', 'reach' ),
	) );

	return apply_filters( 'reach_social_sites', $sites );
}

/**
 * The currently viewed author on an author archive.
 *
 * @return  WP_User
 * @since   1.0.0
 */
function reach_get_current_author() {
	static $author;

	if ( ! isset( $author ) ) {
		if ( get_query_var( 'author_name' ) ) {
			$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
		} else {
			$author = get_userdata( get_query_var( 'author' ) );
		}
	}

	return $author;
}

/**
 * Get the Charitable_User object of the currently viewed author on an author archive.
 *
 * @return  Charitable_User
 * @since   1.0.0
 */
function reach_get_current_charitable_user() {
	return charitable_get_user( reach_get_current_author()->ID );
}

/**
 * Return the banner title for the current page.
 *
 * @return  string
 * @since   1.0.0
 */
function reach_get_banner_title() {
	$title = '';

	/* Homepage */
	if ( is_home() ) {
		if ( 'page' == get_option( 'show_on_front' ) ) {
			$title = get_the_title( get_option( 'page_for_posts' ) );
		} else {
			$title = apply_filters( 'reach_banner_title_posts', '' );
		}
	} /* 404 Page */
	elseif ( is_404() ) {
		$title = apply_filters( 'reach_banner_title_404', '404' );
	} /* Author */
	elseif ( is_author() ) {
		$author = reach_get_current_author();
		$title = apply_filters( 'reach_banner_title_author', $author->display_name, $author );
	} /* Search Results */
	elseif ( is_search() ) {
		$title = apply_filters( 'reach_banner_title_search', sprintf( __( 'Search Results for: %s', 'reach' ), get_search_query() ) );
	} /* Post Type Archive */
	elseif ( is_post_type_archive() ) {
		$title = apply_filters( 'reach_banner_title_post_type_archive', post_type_archive_title( '', false ) );
	} /* Year Archive */
	elseif ( is_year() ) {
		$title = apply_filters( 'reach_banner_title_year_archive', sprintf( __( 'Year: %s', 'reach' ), get_the_date( _x( 'Y', 'yearly archives date format', 'reach' ) ) ) );
	} /* Month Archive */
	elseif ( is_month() ) {
		$title = apply_filters( 'reach_banner_title_month_archive', sprintf( __( 'Month: %s', 'reach' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'reach' ) ) ) );
	} /* Day Archive */
	elseif ( is_day() ) {
		$title = apply_filters( 'reach_banner_title_day_archive', sprintf( __( 'Day: %s', 'reach' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'reach' ) ) ) );
	} /* General Archive */
	elseif ( is_archive() ) {
		$title = apply_filters( 'reach_banner_title_archive', single_term_title( '', false ) );
	} /* Fallback */
	else {
		$title = apply_filters( 'reach_banner_title_page', get_the_title() );
	}

	return apply_filters( 'reach_banner_title', $title );
}

/**
 * Return the banner sub title for the current page.
 *
 * @return  string
 * @since   1.0.0
 */
function reach_get_banner_subtitle() {
	$title = '';

	return apply_filters( 'reach_banner_subtitle', $title );
}

/**
 * Return the media associated with the post.
 *
 * @param   array       $args
 * @return  string
 * @since   1.0.0
 */
function reach_get_media( $args = array() ) {
	$media = new Reach_Media_Grabber( $args );

	return $media->get_media();
}

/**
 * Returns all anchors from the content.
 *
 * @param   string  $content
 * @return  array
 * @since   1.0.0
 */
function reach_get_first_anchor( $content ) {
	preg_match( '/<a(.*)>(.*)<\/a>/', $content, $matches );
	return $matches;
}

/**
 * Strips anchors from the content, up to the set limit (defaults to 1).
 *
 * @param   string  $content
 * @param   int     $limit
 * @return  string
 * @since   1.0.0
 */
function reach_strip_anchors( $content, $limit = 1 ) {
	return preg_replace( '/<a(.*)>(.*)<\/a>/', '', $content, $limit );
}

/**
 * Simple CSS compression.
 *
 * Removes all comments, removes spaces after colons and strips out all the whitespace.
 *
 * Based on http://manas.tungare.name/software/css-compression-in-php/
 *
 * @param   string $css The block of CSS to be compressed.
 * @return  string The compressed CSS
 * @since   1.0.0
 */
if ( ! function_exists( 'reach_compress_css' ) ) :

	function reach_compress_css( $css ) {
		// Remove comments
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );

		// Remove space after colons
		$css = str_replace( ': ', ':', $css );

		// Remove whitespace
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );

		return $css;
	}

endif; // End reach_compress_css

/**
 * Based on a single key, retrieve the data for a particular image, including whether it is retina and its dimensions.
 *
 * @param   string $key
 * @return  false|array     False if not set, or array if set.
 * @since   1.0.0
 */
function reach_get_customizer_image_data( $key ) {
	$data = array();

	$key_value = esc_url( reach_get_theme()->get_theme_setting( $key ) );

	if ( ! strlen( $key_value ) ) {
		return false;
	}

	$data['width']  = absint( reach_get_theme()->get_theme_setting( $key . '_width' ) );
	$data['height'] = absint( reach_get_theme()->get_theme_setting( $key . '_height' ) );

	if ( reach_get_theme()->get_theme_setting( $key . '_is_retina' ) ) {

		/* Retrieve the post ID of the logo, then get the non-retina version */
		$data['id'] 		  = absint( get_theme_mod( $key . '_id' ) );
		$data['image'] 		  = get_post_meta( $data['id'], '_non_retina', true );
		$data['retina_image'] = $key_value;

	} else {

		$data['image']  	  = $key_value;

	}

	return $data;
}
