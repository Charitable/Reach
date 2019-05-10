<?php
/**
 * Sets up the admin for Reach.
 *
 * @package     Reach/Classes/Reach_Admin
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2019, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Reach_Admin' ) ) :

	/**
	 * Reach_Admin
	 *
	 * @since       1.0.0
	 */
	class Reach_Admin {

		/**
		 * Create class object.
		 *
		 * @access  public
		 * @since   1.0.0
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'register_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_page_styles' ) );
			add_action( 'admin_notices', array( $this, 'add_admin_notices' ) );
			add_action( 'wp_ajax_reach_dismiss_notice', array( $this, 'dismiss_notice' ) );
		}

		/**
		 * Register the theme admin page.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function register_page() {
			add_theme_page(
				__( 'Reach', 'reach' ),
				__( 'Reach', 'reach' ),
				'edit_theme_options',
				'about-reach.php',
				array( $this, 'render' )
			);
		}

		/**
		 * Register the admin page stylesheet.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function register_admin_page_styles() {
			wp_register_script(
				'charitable-admin-script',
				get_template_directory_uri() . '/js/admin/admin.js',
				array(),
				reach_get_theme()->get_theme_version()
			);

			/**
			 * We are going to use the same styles as on Charitable, so if
			 * we already have those, bail.
			 */
			if ( wp_style_is( 'charitable-admin-pages', 'registered' ) ) {
				return;
			}

			wp_register_style(
				'charitable-admin-pages',
				get_template_directory_uri() . '/css/vendor/charitable-admin-pages.min.css',
				array(),
				reach_get_theme()->get_theme_version()
			);
		}

		/**
		 * Displays the content of the admin page.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function render() {
	?>
	<?php
	/**
	 * Display the Welcome page.
	 *
	 * @author  Studio 164a
	 * @package Charitable/Admin View/Welcome Page
	 * @since   1.0.0
	 */

	wp_enqueue_style( 'charitable-admin-pages' );

	?>
	<style>
	.charitable-wrap .recommended-plugins { margin-bottom: 0; padding: 0; background-color: #f1f1f1; }
	.charitable-wrap .recommended-plugins li { margin: 0; padding: 0; }
	.charitable-wrap .recommended-plugins > li { padding: 7px 21px; border-bottom: 1px solid #fff; }
	.charitable-wrap .recommended-plugins li:last-child { margin-bottom: 0; border: none; }
	.charitable-wrap .recommended-plugins .premium { background-color: #fef4e8; }
	.charitable-wrap .recommended-plugins .premium .upgrade-tag { margin-top: 7px; color: #f99d33; text-transform: uppercase; font-weight: bolder; }
	.charitable-wrap .docs li { margin: 0; padding: 7px 0; border-bottom: 1px solid #efefef; }
	</style>
	<div class="wrap about-wrap charitable-wrap">
		<h1>
			<strong>Reach</strong>
			<sup class="version"><?php echo reach_get_theme()->get_theme_version() ?></sup>
		</h1>
		<div class="intro">
			<?php printf(
				__( 'Enjoying Reach? Why not <a href="%s" target="_blank">leave a %s review</a> on WordPress.org? We\'d really appreciate it.', 'reach' ),
				'https://wordpress.org/support/view/theme-reviews/reach?rate=5#postform',
				'<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>'
			) ?>
		</div>
		<hr />
		<div class="column-left">
			<div class="column-inside">
				<h2><?php _e( 'Built for Fundraising', 'reach' ) ?></h2>
				<p><?php _e( 'We built Reach to help non-profits & social entrepreneurs run beautiful online fundraising campaigns. Install Charitable to start accepting donations today.', 'reach' ) ?></p>
				<p>
				<?php if ( class_exists( 'Charitable' ) ) : ?>
					<p style="color: #79ba49; font-weight: bolder;"><span class="dashicons dashicons-yes"></span><?php _e( 'Charitable is installed', 'reach' ) ?></p>
				<?php else : ?>
					<p><a href="<?php echo wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=charitable' ), 'install-plugin_charitable' ) ?>" class="button button-primary" title="<?php _e( 'Install Charitable', 'reach' ) ?>" alt="<?php _e( 'Install Charitable', 'reach' ) ?>"><?php _e( 'Install Charitable', 'reach' ) ?></a></p>
				<?php endif ?>
				<hr />
				<h3><?php _e( 'Supported Plugins', 'reach' ) ?></h3>
				<p><?php _e( 'Below are some plugins that can help you get the most out of Reach. They\'re all optional; Reach will work just fine without them.', 'reach' ) ?></p>
				<ul class="recommended-plugins">
					<li>
						<p><?php _e( '<strong>Charitable</strong> is a free WordPress donation plugin that we created to help you raise money on your website. Supports PayPal & offline donations out of the box.', 'reach' ) ?></p>
						<p><a href="https://wordpress.org/plugins/charitable/" title="<?php _e( 'Charitable WordPress.org page', 'reach' ) ?>" alt="<?php _e( 'Charitable WordPress.org page', 'reach' ) ?>"><?php _e( 'View Charitable', 'reach' ) ?></a></p>
					</li>
					<li>
						<p><?php printf( __( '<strong>Easy Digital Downloads</strong> by Pippin Williamson is a plugin that allows you to sell digital downloads through WordPress, for free. An optional add-on is the <a href="%s" title="Charitable Easy Digital Downloads product page" alt="Charitable Easy Digital Downloads product page">Charitable Easy Digital Downloads Connect plugin</a>, which allows you to also accept donations through Easy Digital Downloads.', 'reach' ), 'https://www.wpcharitable.com/extensions/charitable-easy-digital-downloads-connect/?utm_source=reach-welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=documentation' ) ?></p>
						<p><a href="https://wordpress.org/plugins/easy-digital-downloads/" title="<?php _e( 'Easy Digital Downloads WordPress.org page', 'reach' ) ?>" alt="<?php _e( 'Easy Digital Downloads WordPress.org page', 'reach' ) ?>"><?php _e( 'View Easy Digital Downloads', 'reach' ) ?></a></p>
					</li>
					<li>
						<p><?php _e( '<strong>The Events Calendar</strong> by Modern Tribe is a brilliant event management plugin. It\'s available as a free, fully featured plugin, with premium upgrades that allow you to do things like sell tickets, create recurring events and import events from Facebook.', 'reach' ) ?></p>
						<p><a href="https://wordpress.org/plugins/the-events-calendar/" title="<?php _e( 'The Events Calendar WordPress.org page', 'reach' ) ?>" alt="<?php _e( 'The Events Calendar WordPress.org page', 'reach' ) ?>"><?php _e( 'View The Events Calendar', 'reach' ) ?></a></p>
					</li>
					<li>
						<p><?php _e( '<strong>Ninja Forms</strong> by the WP Ninjas is a free form plugin. It features a robust form builder to allow you to create powerful forms for your site.', 'reach' ) ?></p>
						<p><a href="https://wordpress.org/plugins/ninja-forms/" title="<?php _e( 'Ninja Forms WordPress.org page', 'reach' ) ?>" alt="<?php _e( 'Ninja Forms WordPress.org page', 'reach' ) ?>"><?php _e( 'View Ninja Forms', 'reach' ) ?></a></p>
					</li>
					<li class="premium">
						<div class="upgrade-tag"><?php _e( 'Paid Upgrades', 'reach' ) ?></div>
						<ul>
							<li><p><?php printf( __( '<a href="%s"><strong>Charitable Ambassadors</strong></a> transforms your website into a peer-to-peer fundraising or crowdfunding platform, with front-end campaign submissions.', 'reach' ), 'https://www.wpcharitable.com/extensions/charitable-ambassadors/?utm_source=reach-welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=upgrades' ) ?></p></li>
							<li><p><?php printf( __( '<a href="%s"><strong>Charitable Stripe</strong></a> allows you to accept credit card donations securely on your website.', 'reach' ), 'https://www.wpcharitable.com/extensions/charitable-stripe/?utm_source=reach-welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=upgrades' ) ?></p></li>
							<li><p><?php printf( __( '<a href="%s"><strong>Charitable Authorize.Net</strong></a> allows your to accept donations through your Authorize.Net account, securely on your website.', 'reach' ), 'https://www.wpcharitable.com/extensions/charitable-authorize-net/?utm_source=reach-welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=upgrades' ) ?></p></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="column-right">
			<div class="column-inside">
				<h2><?php _e( 'Documentation', 'reach' ) ?></h2>
				<ul class="docs">
					<li><a href="http://demo.wpcharitable.com/reach/documentation/customizer-settings/?utm_source=reach-welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=documentation"><?php _e( 'Customizer settings', 'reach' ) ?></a></li>
					<li><a href="http://demo.wpcharitable.com/reach/documentation/setting-up-the-homepage/?utm_source=reach-welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=documentation"><?php _e( 'How to set up the homepage', 'reach' ) ?></a></li>
					<li><a href="http://demo.wpcharitable.com/reach/documentation/menus/?utm_source=reach-welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=documentation"><?php _e( 'Create the site menu', 'reach' ) ?></a></li>
					<li><a href="http://demo.wpcharitable.com/reach/documentation/adding-widgets/?utm_source=reach-welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=documentation"><?php _e( 'Set up widget areas', 'reach' ) ?></a></li>
					<li><a href="http://demo.wpcharitable.com/reach/documentation/how-to-use-the-child-theme/?utm_source=reach-welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=documentation"><?php _e( 'How to use the child theme', 'reach' ) ?></a></li>
				</ul>
				<p><strong><a href="http://demo.wpcharitable.com/reach/documentation/?utm_source=reach-welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=documentation" alt="<?php _e( 'Online documentation', 'reach' ) ?>" title="<?php _e( 'Online documentation', 'reach' ) ?>"><?php _e( 'More online documenation', 'reach' ) ?></a></strong></p>
				<hr />
				<h2><?php _e( 'Contribute to Reach', 'reach' ) ?></h2>
				<p><?php printf(
					__( 'Found a bug? Want to contribute a patch or create a new feature? <a href="%s">GitHub is the place to go!</a> Or would you like to translate Reach into your language? <a href="%s">Get involved on WordPress.org</a>.', 'reach' ),
					'https://github.com/Charitable/Reach',
					'https://translate.wordpress.org/projects/wp-themes/reach'
				) ?>
				</p>
			</div>
		</div>
	</div>

	<?php
		}

		/**
		 * Dismiss a notice.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.3
		 */
		public function dismiss_notice() {
			if ( ! isset( $_POST['notice'] ) ) {
				wp_send_json_error();
			}

			switch ( $_POST['notice'] ) {
				case 'logo-upgrade' :
					delete_transient( 'reach_show_custom_logo_notice', true );
					break;
			}

			wp_send_json_success();
		}

		/**
		 * Prints a welcome message in the admin notices.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.3
		 */
		public function add_admin_notices() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( get_transient( 'reach_show_custom_logo_notice' ) ) {

				wp_enqueue_script( 'charitable-admin-script' );

				$this->logo_upgrade_notice();
			}
		}

		/**
		 * The custom logo upgrade notice HTML.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.3
		 */
		public function logo_upgrade_notice() {
?>
<div class="updated notice reach-notice" data-notice="logo-upgrade">
	<p><?php printf( __( 'Logos have changed in Reach 1.0.3. <a href="%s">Find out more</a>', 'reach' ), 'http://demo.wpcharitable.com/reach/documentation/updating-your-logo-reach-103/'
	) ?>
	</p>
</div>
<?php
		}
	}

endif; // End class_exists check
