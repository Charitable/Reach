<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'Reach_Customizer_Styles' ) ) :

	/**
	 * Reach Customizer Styles
	 *
	 * @author      Studio 164a
	 * @category    Classes
	 * @package     Reach/Customizer
	 * @since       1.0.0
	 */
	class Reach_Customizer_Styles {

		/**
		 * @var Reach_Theme $theme
		 */
		private $theme;

		/**
		 * Creates an instance of this class.
		 *
		 * This can only be run on the reach_theme_start hook. You should
		 * never need to instantiate it again (if you do, I'd love to hear
		 * your use case).
		 *
		 * @static
		 *
		 * @param   Reach_Theme  $theme
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public static function start( Reach_Theme $theme ) {
			if ( ! $theme->is_start() ) {
				return;
			}

			new Reach_Customizer_Styles( $theme );
		}

		/**
		 * Object constructor.
		 *
		 * @access  private
		 * @since   1.0.0
		 */
		private function __construct() {
			add_action( 'wp_head', array( $this, 'wp_head' ) );

			do_action( 'reach_customizer_styles', $this );
		}

		/**
		 * Return the key used to store customizer styles as a transient.
		 *
		 * @static
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public static function get_transient_key() {
			return 'reach_customizer_styles';
		}

		/**
		 * Insert output into end of <head></head> section.
		 *
		 * @return  void
		 * @access 	public
		 * @since 	1.0.0
		 */
		public function wp_head() {

			/* Check for saved customizer styles. */
			$styles = get_transient( self::get_transient_key() );

			if ( false === $styles ) {
				ob_start();

				?>
<style id="customizer-styles" media="all" type="text/css">
	<?php echo $this->get_accent_colour_css(); ?>
	<?php echo $this->get_background_colour_css(); ?>
	<?php echo $this->get_text_colour_css(); ?>
	<?php echo $this->get_header_text_colour_css(); ?>
	<?php echo $this->get_footer_text_colour_css(); ?>
	<?php echo $this->get_background_image_css( 'body_background', 'body', 'repeat' ); ?>
	<?php echo $this->get_background_image_css( 'blog_banner_background', '.banner', 'repeat' ); ?>
	<?php echo $this->get_background_image_css( 'campaign_feature_background', '.feature-block', 'repeat' ); ?>
</style>
				<?php
				$styles = ob_get_clean();

				$styles = reach_compress_css( $styles );

				set_transient( self::get_transient_key(), $styles, 0 );
			}

			/* Print the styles */
			echo $styles;
		}

		/**
		 * Returns an RGB CSS string.
		 *
		 * @param   array $rgb
		 * @param   int   $alpha
		 * @return  string
		 * @since   1.0.0
		 */
		private function rgb( $rgb, $alpha = '' ) {
			if ( empty( $alpha ) ) {
				return sprintf( 'rgb(%s)', implode( ', ', $rgb ) );
			}

			return sprintf( 'rgba(%s, %s)', implode( ', ', $rgb ), $alpha );
		}

		/**
		 * Return a HEX colour's RGB value as an array.
		 *
		 * @see  	http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
		 *
		 * @param   string $hex
		 * @return  array
		 * @since   1.0.0
		 */
		private function get_rgb_from_hex( $hex ) {
			$hex = str_replace( '#', '', $hex );

			if ( 3 == strlen( $hex ) ) {
				$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
				$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
				$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
			} else {
				$r = hexdec( substr( $hex, 0, 2 ) );
				$g = hexdec( substr( $hex, 2, 2 ) );
				$b = hexdec( substr( $hex, 4, 2 ) );
			}

			return array( $r, $g, $b );
		}

		/**
		 * Returns a block of CSS to change the accent colour.
		 *
		 * @return  string
		 * @access  private
		 * @since   1.0.0
		 */
		private function get_accent_colour_css() {
			$colour = esc_attr( reach_get_theme()->get_theme_setting( 'accent_colour' ) );

			if ( ! strlen( $colour ) ) {
				return '';
			}

			$output = "a, .menu-site a:hover, .menu-button, .button-alt.accent, .button-secondary.accent, .button.button-alt.accent, .button.button-secondary.accent, .block-title, .site-title a, .post-title a, .entry-header .entry-title a, .bypostauthor .post-author i, body.author .author-activity-feed .activity-summary .display-name, body.author .author-activity-feed .activity-summary a, .widget.widget_charitable_edd_campaign_downloads .download-price, .widget.widget_charitable_donate_widget .charitable-submit-field .button, .campaign .campaign-stats li span, .campaign-grid .campaign-stats li span, .charitable-donation-form .charitable-form-field .button, body.user-dashboard .charitable-submit-field .button.button-primary, .user-dashboard-menu li.current-menu-item a, .user-dashboard-menu li a:hover, .charitable-repeatable-form-field-table .remove:hover:before, .entry-header .entry-title, .user-post-actions a:hover, body .edd-submit.button.gray, body .edd-submit.button.white, body .edd-submit.button.blue, body .edd-submit.button.red, body .edd-submit.button.orange, body .edd-submit.button.green, body .edd-submit.button.yellow, body .edd-submit.button.dark-gray, .widget.widget_pp_campaign_events .download-price, .charitable-notice .errors li a, .charitable-form-fields .charitable-fieldset .charitable-form-header, .user-post-stats.campaign-stats .summary-item span, #charitable-donation-form .charitable-form-field .button { color: $colour; }";
			$output .= ".button-alt.accent:hover, .button-secondary.accent:hover, .button.button-alt.accent:hover, .button.button-secondary.accent:hover, .feature-block, .account-links .button.button-alt:hover, .banner, .widget.widget_charitable_donate_widget .charitable-submit-field .button:hover, .widget.widget_charitable_donate_widget .charitable-submit-field .button:focus, .charitable-donation-form .charitable-form-field .button:hover, .charitable-donation-form .charitable-form-field .button:focus, .charitable-donation-form .charitable-form-field .button:active, body.user-dashboard .charitable-submit-field .button.button-primary:hover, body .edd-submit.button.gray:focus, body .edd-submit.button.gray:active, body .edd-submit.button.gray:hover, body .edd-submit.button.white:focus, body .edd-submit.button.white:active, body .edd-submit.button.white:hover, body .edd-submit.button.blue:focus, body .edd-submit.button.blue:active, body .edd-submit.button.blue:hover, body .edd-submit.button.red:focus, body .edd-submit.button.red:active, body .edd-submit.button.red:hover, body .edd-submit.button.orange:focus, body .edd-submit.button.orange:active, body .edd-submit.button.orange:hover, body .edd-submit.button.green:focus, body .edd-submit.button.green:active, body .edd-submit.button.green:hover, body .edd-submit.button.yellow:focus, body .edd-submit.button.yellow:active, body .edd-submit.button.yellow:hover, body .edd-submit.button.dark-gray:focus, body .edd-submit.button.dark-gray:active, body .edd-submit.button.dark-gray:hover, #edd_checkout_wrap #edd-purchase-button, .charitable-ambassadors-campaign-creator-toolbar, #charitable-donation-form .charitable-form-field .button:hover, #charitable-donation-form .charitable-form-field .button:active, .sticky .sticky-tag { background-color: $colour; }";
			$output .= ".button-alt.accent, .button-secondary.accent, .button.button-alt.accent, .button.button-secondary.accent, .account-links .button.button-alt:hover, .widget.widget_charitable_donate_widget .charitable-submit-field .button, .charitable-donation-form .charitable-form-field .button, body.user-dashboard .charitable-submit-field .button.button-primary, body .edd-submit.button.gray, body .edd-submit.button.white, body .edd-submit.button.blue, body .edd-submit.button.red, body .edd-submit.button.orange, body .edd-submit.button.green, body .edd-submit.button.yellow, body .edd-submit.button.dark-gray, body .edd-submit.button.gray:focus, body .edd-submit.button.gray:active, body .edd-submit.button.gray:hover, body .edd-submit.button.white:focus, body .edd-submit.button.white:active, body .edd-submit.button.white:hover, body .edd-submit.button.blue:focus, body .edd-submit.button.blue:active, body .edd-submit.button.blue:hover, body .edd-submit.button.red:focus, body .edd-submit.button.red:active, body .edd-submit.button.red:hover, body .edd-submit.button.orange:focus, body .edd-submit.button.orange:active, body .edd-submit.button.orange:hover, body .edd-submit.button.green:focus, body .edd-submit.button.green:active, body .edd-submit.button.green:hover, body .edd-submit.button.yellow:focus, body .edd-submit.button.yellow:active, body .edd-submit.button.yellow:hover, body .edd-submit.button.dark-gray:focus, body .edd-submit.button.dark-gray:active, body .edd-submit.button.dark-gray:hover, .menu-site ul, #charitable-donation-form .charitable-form-field .button { border-color: $colour; }";
			$output .= ".site-navigation.toggled .menu-site, .home.blog #main { border-top-color: $colour; }";

			return $output;
		}

		/**
		 * Returns a block of CSS to change the body background colour.
		 *
		 * @return  string
		 * @access  private
		 * @since   1.0.0
		 */
		private function get_background_colour_css() {
			$colour = esc_attr( reach_get_theme()->get_theme_setting( 'background_colour' ) );

			if ( ! strlen( $colour ) ) {
				return '';
			}

			$output = "#custom-donation-amount-field.charitable-custom-donation-field-alone ::-webkit-input-placeholder, #custom-donation-amount-field.charitable-custom-donation-field-alone ::-moz-placeholder, #custom-donation-amount-field.charitable-custom-donation-field-alone :-ms-input-placeholder { color: $colour; }";
			$output .= "body, #custom-donation-amount-field.charitable-custom-donation-field-alone { background-color: $colour; }";
			$output .= "#charitable-donation-form .donation-amounts .donation-amount { border-color: $colour; }";

			return $output;
		}

		/**
		 * Returns a block of CSS to change the text colour.
		 *
		 * @return  string
		 * @access  private
		 * @since   1.0.0
		 */
		private function get_text_colour_css() {
			$colour = esc_attr( reach_get_theme()->get_theme_setting( 'text_colour' ) );

			if ( ! strlen( $colour ) ) {
				return '';
			}

			$output = ".with-icon::before, body, button:hover,input[type='button']:hover,input[type='reset']:hover,input[type='submit']:hover, .menu-site a, .button:hover, .button-alt,.button-secondary,.button.button-alt,.button.button-secondary, .modal .block-title, .modal-close:before, .meta a, #submit, .widget.widget_campaign_creator_widget .creator-profile-link a:hover, .campaign-grid .campaign-description, .campaign-grid .campaign-stats, .charitable-donation-form .charitable-form-field .button, #charitable-donation-form .donation-amounts .donation-amount, #custom-donation-amount-field.charitable-custom-donation-field-alone input, body.user-dashboard .charitable-submit-field .button, .charitable-form-field-editor .mce-btn button, .charitable-repeatable-form-field-table .remove:before, .charitable-repeatable-form-field-table .add-row.button, .share-widget .modal,.share-widget .modal .block-title, .user-post-actions a, div.printfriendly a,div.printfriendly a:link, div.printfriendly a:visited, body.events-single .tribe-events-tickets .tickets_price, body.events-single .tribe-events-tickets .tickets_name, #tribe-events .tribe-events-button { color: $colour; }";
			$output .= "button,input[type='button'],input[type='reset'],input[type='submit'], .button, .button-alt:hover,.button-secondary:hover,.button.button-alt:hover,.button.button-secondary:hover, #submit:hover, .widget.widget_campaign_creator_widget .creator-profile-link a, .charitable-donation-form .charitable-form-field .button:hover, #charitable-donation-form .donation-amounts .donation-amount.selected, #charitable-donation-form .donation-amounts .donation-amount:hover, body.user-dashboard .charitable-submit-field .button:hover, #tribe-events .tribe-events-button:hover { background-color: $colour; }";
			$output .= "button,input[type='button'],input[type='reset'],input[type='submit'], button:hover,input[type='button']:hover,input[type='reset']:hover,input[type='submit']:hover, .button, .button:hover, .button-alt,.button-secondary,.button.button-alt,.button.button-secondary, #submit, .widget.widget_campaign_creator_widget .creator-profile-link a, .widget.widget_campaign_creator_widget .creator-profile-link a:hover, .charitable-donation-form .charitable-form-field .button, #charitable-donation-form .donation-amounts .donation-amount.selected, #charitable-donation-form .donation-amounts .donation-amount:hover, body.user-dashboard .charitable-submit-field .button, .charitable-repeatable-form-field-table .add-row.button, #tribe-events .tribe-events-button { border-color: $colour; }";
			$output .= ".shadow-wrapper::before { border-right-color: $colour; }";
			$output .= ".shadow-wrapper::after { border-left-color: $colour; };";

			return $output;
		}

		/**
		 * Returns a block of CSS to change the colour of text in the footer.
		 *
		 * @return  string
		 * @access  private
		 * @since   1.0.0
		 */
		private function get_footer_text_colour_css() {
			$colour = esc_attr( reach_get_theme()->get_theme_setting( 'footer_text_colour' ) );

			if ( ! strlen( $colour ) ) {
				return '';
			}

			$output = "#site-footer, #site-footer a { color: $colour; }";

			return $output;
		}

		/**
		 * Returns a block of CSS to change the colour of text in the header.
		 *
		 * @return  string
		 * @access  private
		 * @since   1.0.0
		 */
		private function get_header_text_colour_css() {
			$colour = esc_attr( reach_get_theme()->get_theme_setting( 'header_text_colour' ) );

			if ( ! strlen( $colour ) ) {
				return '';
			}

			$output = ".social a, .account-links a, .account-links a:before, .account-links .button.button-alt { color: $colour; }";

			return $output;
		}

		/**
		 * Prints a block of background image CSS for the given image.
		 *
		 * @param   string  $key
		 * @param   string  $element
		 * @param   string  $repeat
		 * @return  string
		 * @access  private
		 * @since   1.0.0
		 */
		private function get_background_image_css( $key, $element, $repeat = 'repeat' ) {
			$image = reach_get_customizer_image_data( $key );

			if ( ! $image ) {
				return;
			}

			$output = sprintf( '%s { background-image: url("%s"); background-repeat: %s; }',
				$element,
				esc_url( $image['image'] ),
				esc_attr( $repeat )
			);

			if ( isset( $image['retina_image'] ) ) {

				$output .= '@media only screen and (-Webkit-min-device-pixel-ratio: 1.5), only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5) {';

				$output .= sprintf( '%s { background-image: url("%s"); background-size: %spx %spx; }',
					$element,
					esc_url( $image['retina_image'] ),
					esc_attr( $image['width'] ),
					esc_attr( $image['height'] )
				);

				$output .= '}';

			}

			return $output;
		}
	}

endif;
