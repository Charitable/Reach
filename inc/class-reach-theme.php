<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Reach_Theme' ) ) :

	/**
	 * Core theme class. Everything starts here.
	 *
	 * The purpose of this class is to encapsulate all the core theme definitions
	 * inside a single class, to avoid namespace collisions.
	 *
	 * @package Reach/Classes/Core
	 * @author  Studio 164a
	 * @since   1.0.0
	 * @version 1.1.0
	 */
	class Reach_Theme {

		/**
		 * The one and only class instance.
		 *
		 * @var Reach_Theme
		 */
		private static $instance = null;

		/**
		 * The theme version.
		 */
		const VERSION = '1.1.0';

		/**
		 * Database version number.
		 *
		 * This is different to the theme version since it is used only to
		 * manage theme updates that require some sort of upgrade process.
		 */
		const DATABASE_VERSION = '1.0.3';

		/**
		 * Whether crowdfunding is enabled.
		 *
		 * @var string[]
		 */
		public $active_modules = array();

		/**
		 * Retrieve the class instance. If one hasn't been created yet, create it first.
		 *
		 * @since  1.0.0
		 *
		 * @return Reach_Theme
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new Reach_Theme();
			}

			return self::$instance;
		}

		/**
		 * Class constructor.
		 *
		 * This is only called once, since the only way to instantiate
		 * the theme is with the get_instance() method above.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function __construct() {
			$this->load_dependencies();

			$this->maybe_upgrade();

			$this->maybe_start_charitable();

			$this->maybe_start_edd();

			$this->maybe_start_jetpack();

			$this->maybe_start_tribe_events();

			$this->maybe_start_easy_google_fonts();

			$this->maybe_start_customizer();

			$this->maybe_start_admin();

			$this->attach_hooks_and_filters();

			/**
			 * If you want to do anything during the start of the
			 * theme, use this hook. You can also use this hook
			 * to remove any of the hooks or filters called during
			 * this phase.
			 */
			do_action( 'reach_theme_start', $this );
		}

		/**
		 * Returns the version number of the theme.
		 *
		 * @since  1.0.0
		 *
		 * @return string
		 */
		public function get_theme_version() {
			if ( defined( 'REACH_DEBUG' ) && REACH_DEBUG ) {
				return time();
			}

			return self::VERSION;
		}

		/**
		 * Checks whether the theme's start hook has already run.
		 *
		 * @since  1.0.0
		 *
		 * @return boolean
		 */
		public function started() {
			return did_action( 'reach_theme_start' );
		}

		/**
		 * Checks whether we are currently on the `reach_theme_start` hook.
		 *
		 * @since  1.0.0
		 *
		 * @return boolean
		 */
		public function is_start() {
			return 'reach_theme_start' == current_filter();
		}

		/**
		 * Returns the theme's user-defined settings.
		 *
		 * @since  1.0.0
		 *
		 * @param  boolean $use_cached Set this to false if you want to re-fetch the settings.
		 * @return array
		 */
		public function get_theme_settings( $use_cached = true ) {
			if ( ! isset( $this->settings ) || false === $use_cached ) {
				$this->settings = get_theme_mods();
			}

			return $this->settings;
		}

		/**
		 * Retuns the value for a given setting.
		 *
		 * @since  1.0.0
		 *
		 * @param  string  $key        The key of the setting you want to get.
		 * @param  boolean $use_cached Set this to false if you want to re-fetch the settings.
		 * @return mixed
		 */
		public function get_theme_setting( $key, $use_cached = true ) {
			$settings = $this->get_theme_settings( $use_cached );
			return isset( $settings[ $key ] ) ? $settings[ $key ] : false;
		}

		/**
		 * Load required files.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function load_dependencies() {
			require get_template_directory() . '/inc/vendors/hybrid-media-grabber.php';
			require get_template_directory() . '/inc/class-reach-media-grabber.php';
			require get_template_directory() . '/inc/class-reach-customizer-styles.php';
			require get_template_directory() . '/inc/functions/template-tags.php';
			require get_template_directory() . '/inc/functions/helper-functions.php';
			require get_template_directory() . '/inc/functions/comments.php';
		}

		/**
		 * Check whether the theme has been updated and needs an upgrade.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function maybe_upgrade() {
			$db_version = get_option( 'reach_version' );

			if ( '20150303' == $db_version ) {
				$db_version = false;
			}

			if ( self::DATABASE_VERSION !== $db_version ) {

				require_once( get_template_directory() . '/inc/class-reach-upgrade.php' );

				Reach_Upgrade::upgrade_from( $db_version, self::DATABASE_VERSION );
			}
		}

		/**
		 * Load up the Customizer helper class if we're using the Customizer.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function maybe_start_customizer() {
			global $wp_customize;

			if ( $wp_customize ) {

				$dir = get_template_directory();

				require_once( $dir . '/inc/admin/class-reach-customizer.php' );
				require_once( $dir . '/inc/admin/customizer-controls/class-reach-customizer-misc-control.php' );
				require_once( $dir . '/inc/admin/customizer-controls/class-reach-customizer-retina-image-control.php' );

				add_action( 'reach_theme_start', array( 'Reach_Customizer', 'start' ) );
			}
		}

		/**
		 * Set up admin support.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function maybe_start_admin() {
			if ( ! is_admin() ) {
				return;
			}

			require_once( get_template_directory() . '/inc/admin/class-reach-admin.php' );

			new Reach_Admin();
		}

		/**
		 * Set up Jetpack support if it's enabled.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function maybe_start_jetpack() {
			if ( defined( 'JETPACK__VERSION' ) ) {

				require_once( get_template_directory() . '/inc/jetpack/class-reach-jetpack.php' );

				add_action( 'reach_theme_start', array( 'Reach_Jetpack', 'start' ) );

				$this->active_modules[] = 'jetpack';
			}
		}

		/**
		 * Set up Tribe Events support if it's enabled.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function maybe_start_tribe_events() {
			if ( class_exists( 'Tribe__Events__Main' ) ) {

				require_once( get_template_directory() . '/inc/tribe-events/class-reach-tribe-events.php' );

				add_action( 'reach_theme_start', array( 'Reach_Tribe_Events', 'start' ) );

				$this->active_modules[] = 'tribe-events';
			}
		}

		/**
		 * Set up Easy Google Fonts support if it is enabled.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function maybe_start_easy_google_fonts() {
			if ( class_exists( 'Easy_Google_Fonts' ) ) {

				require_once( get_template_directory() . '/inc/easy-google-fonts/class-reach-easy-google-fonts.php' );

				add_action( 'reach_theme_start', array( 'Reach_Easy_Google_Fonts', 'start' ) );

				$this->active_modules[] = 'easy-google-fonts';
			}
		}

		/**
		 * Set up Charitable support if it is enabled.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function maybe_start_charitable() {
			if ( class_exists( 'Charitable' ) ) {

				require_once( get_template_directory() . '/inc/charitable/class-reach-charitable.php' );

				add_action( 'reach_theme_start', array( 'Reach_Charitable', 'start' ) );

				$this->active_modules[] = 'charitable';
			}
		}

		/**
		 * Set up EDD support if it is enabled.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function maybe_start_edd() {
			if ( class_exists( 'Easy_Digital_Downloads' ) ) {

				require_once( get_template_directory() . '/inc/easy-digital-downloads/class-reach-edd.php' );

				add_action( 'reach_theme_start', array( 'Reach_EDD', 'start' ) );

				$this->active_modules[] = 'edd';
			}
		}

		/**
		 * Set up callback methods for various core WordPress hooks and filters.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function attach_hooks_and_filters() {
			/**
			 * Core theme classes hooked in on the `reach_theme_start` hook.
			 */
			add_action( 'reach_theme_start', array( 'Reach_Customizer_Styles', 'start' ) );

			/**
			 * Methods within this class that are hooked into core WordPress action hooks.
			 */
			add_action( 'after_switch_theme', array( $this, 'add_welcome_message' ) );
			add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );
			add_action( 'widgets_init', array( $this, 'setup_sidebars' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'setup_scripts' ) );
			add_action( 'wp', array( $this, 'setup_author' ) );
			add_action( 'wp_head', array( $this, 'maybe_setup_title_tag' ) );
			add_action( 'wp_head', array( $this, 'setup_header_styling' ) );
			add_action( 'wp_footer', array( $this, 'setup_fonts' ) );
			add_action( 'wp_ajax_set_primary_navigation_offset', array( $this, 'save_primary_navigation_offset' ) );
			add_action( 'wp_ajax_nopriv_set_primary_navigation_offset', array( $this, 'save_primary_navigation_offset' ) );

			/**
			 * Methods within this class that are hooked into core WordPress filter hooks.
			 */
			add_filter( 'wp_title', array( $this, 'wp_title' ), 10, 2 );
			add_filter( 'wp_page_menu_args', array( $this, 'page_menu_args' ) );
			add_filter( 'theme_page_templates', array( $this, 'filter_page_templates' ) );
			add_filter( 'body_class', array( $this, 'body_classes' ) );
			add_filter( 'post_class', array( $this, 'post_classes' ) );
			add_filter( 'the_content_more_link', array( $this, 'the_content_more_link_filter' ), 10, 2 );
			add_filter( 'next_posts_link_attributes', array( $this, 'posts_navigation_link_attributes' ) );
			add_filter( 'previous_posts_link_attributes', array( $this, 'posts_navigation_link_attributes' ) );
			add_filter( 'next_comments_link_attributes', array( $this, 'posts_navigation_link_attributes' ) );
			add_filter( 'previous_comments_link_attributes', array( $this, 'posts_navigation_link_attributes' ) );
			add_filter( 'oembed_dataparse', array( $this, 'wrap_fullwidth_videos' ), 10, 3 );
			add_filter( 'hybrid_media_grabber_valid_shortcodes', array( $this, 'add_valid_media_grabber_shortcodes' ) );
			add_filter( 'video_embed_html', 'reach_fullwidth_video' );
		}

		/**
		 * Add a welcome message as an admin notification immediately after switching to Reach.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function add_welcome_message() {
			add_action( 'admin_notices', array( $this, 'add_welcome_notice' ) );
		}

		/**
		 * Prints a welcome message in the admin notices.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function add_welcome_notice() {
			?>
			<div class="updated notice is-dismissible">
				<p>
				<?php
					printf(
						/* translators: %s: link */
						__( 'Thanks for choosing Reach! You can read hints and tips on how get the most out of your new theme on the <a href="%s">welcome screen</a>.', 'reach' ),
						esc_url( admin_url( 'themes.php?page=about-reach' ) )
					);
				?>
				</p>
				<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=about-reach' ) ); ?>" class="button" style="text-decoration: none;"><?php _e( 'Get started with Reach', 'reach' ); ?></a></p>
			</div>
			<?php
		}

		/**
		 * Set up main theme supports and definitions.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function setup_theme() {
			/**
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 */
			load_theme_textdomain( 'reach', get_template_directory() . '/languages' );

			/**
			 * Add default posts and comments RSS feed links to head.
			 */
			add_theme_support( 'automatic-feed-links' );

			/**
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
			 */
			add_theme_support( 'post-thumbnails' );
			set_post_thumbnail_size( 910, 607, true );
			add_image_size( 'reach-post-thumbnail-medium', 527, 352, true );

			/**
			 * This theme uses wp_nav_menu() in one location.
			 */
			register_nav_menus(
				array(
					'primary_navigation' => __( 'Primary Menu', 'reach' ),
				)
			);

			/**
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support(
				'html5',
				array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
				)
			);

			/**
			 * Enable support for Post Formats.
			 *
			 * @link    http://codex.wordpress.org/Post_Formats
			 */
			add_theme_support(
				'post-formats',
				array(
					'aside',
					'video',
					'quote',
					'link',
				)
			);

			/**
			 * Enable support for Hide Meta plugin.
			 */
			add_theme_support( 'hide-meta' );

			/**
			 * Add support for the title tag.
			 */
			add_theme_support( 'title-tag' );

			/**
			 * Add theme support for selective refresh of widgets.
			 */
			add_theme_support( 'customize-selective-refresh-widgets' );

			/**
			 * Add Custom Logo support.
			 */
			add_theme_support( 'custom-logo' );

			/**
			 * Add editor styles.
			 */
			$editor_fonts = apply_filters( 'reach_font_path', '//fonts.googleapis.com/css?family=Merriweather:400,400italic,700italic,700,300italic,300|Oswald:400,300' );
			$editor_fonts = str_replace( ',', '%2C', $editor_fonts );
			add_editor_style( $editor_fonts );
			add_editor_style( 'css/editor-style.css' );

			/**
			 * Convert to Custom Logo.
			 */
			$this->maybe_convert_to_custom_logo();
		}

		/**
		 * For users upgrading Reach who are on WordPress 4.5, migrate core's Custom Logo.
		 *
		 * @since  1.0.3
		 *
		 * @return void
		 */
		public function maybe_convert_to_custom_logo() {

			/* Return if update has already been run */
			if ( -1 != get_theme_mod( 'custom_logo', -1 ) ) {
				return;
			}

			/* Make sure we're on WP 4.5 */
			if ( ! function_exists( 'the_custom_logo' ) ) {
				return;
			}

			$custom_logo  = false;
			$current_logo = reach_get_customizer_image_data( 'logo' );

			if ( $current_logo ) {

				/* Bail early if we don't have a valid logo anyway. */
				if ( ! isset( $current_logo['image'] ) ) {
					set_theme_mod( 'custom_logo', $custom_logo );
					return;
				}

				/* Swap the retina image for the standard. */
				if ( isset( $current_logo['id'] ) ) {
					$logo = intval( $current_logo['id'] );
				} else {
					$logo = attachment_url_to_postid( $current_logo['image'] );
				}

				if ( is_int( $logo ) ) {
					$custom_logo = $logo;
				}

				remove_theme_mod( 'logo' );
			}

			set_theme_mod( 'custom_logo', $custom_logo );
		}

		/**
		 * Set up sidebars.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function setup_sidebars() {
			register_sidebar(
				array(
					'id'            => 'default',
					'name'          => __( 'Default sidebar', 'reach' ),
					'description'   => __( 'The default sidebar.', 'reach' ),
					'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
					'after_title'   => '</h4></div>',
				)
			);

			register_sidebar(
				array(
					'id'            => 'sidebar_campaign',
					'name'          => __( 'Campaign sidebar', 'reach' ),
					'description'   => __( 'The campaign sidebar.', 'reach' ),
					'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
					'after_title'   => '</h4></div>',
				)
			);

			register_sidebar(
				array(
					'id'            => 'campaign_after_content',
					'name'          => __( 'Campaign below content', 'reach' ),
					'description'   => __( 'Displayed below the campaign\'s content, but above the comment section.', 'reach' ),
					'before_widget' => '<aside id="%1$s" class="widget block content-block cf %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<div class="title-wrapper"><h3 class="widget-title">',
					'after_title'   => '</h3></div>',
				)
			);

			register_sidebar(
				array(
					'id'            => 'footer_left',
					'name'          => __( 'Footer', 'reach' ),
					'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
					'after_title'   => '</h4></div>',
				)
			);
		}

		/**
		 * Register and enqueue scripts and stylesheets.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function setup_scripts() {
			$theme_dir = untrailingslashit( get_template_directory_uri() );

			wp_register_style( 'reach-base', $theme_dir . '/css/base.css', array(), $this->get_theme_version() );
			wp_register_style( 'reach-style', $theme_dir . '/css/main.css', array(), $this->get_theme_version() );
			wp_enqueue_style( 'reach-style' );

			$ext = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.js' : '.min.js';

			// Allow other scripts to add their scripts to the dependencies.
			$reach_script_dependencies = apply_filters(
				'reach_script_dependencies',
				array(
					'jquery-ui-accordion',
					'rrssb',
					'hoverIntent',
					'fitvids',
					'lean-modal',
					'jquery',
				)
			);

			if ( ! wp_script_is( 'lean-modal', 'registered' ) ) {
				wp_register_script( 'lean-modal', $theme_dir . '/js/vendors/leanmodal/jquery.leanModal' . $ext, array( 'jquery' ), $this->get_theme_version(), true );
			}

			wp_register_script( 'rrssb', $theme_dir . '/js/vendors/rrssb/rrssb.min.js', array( 'jquery' ), $this->get_theme_version(), true );
			wp_register_script( 'fitvids', $theme_dir . '/js/vendors/fitvids/jquery.fitvids.min.js', array( 'jquery' ), '1.0', true );
			wp_register_script( 'reach-lib', $theme_dir . '/js/reach-lib' . $ext, $reach_script_dependencies, $this->get_theme_version(), true );
			wp_register_script( 'reach', $theme_dir . '/js/reach.js', array( 'reach-lib' ), $this->get_theme_version(), true );

			wp_localize_script(
				'reach',
				'REACH_VARS',
				array(
					'primary_navigation_offset' => get_transient( 'reach_navigation_offset' ),
					'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
				)
			);

			wp_enqueue_script( 'reach' );

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
		}

		/**
		 * Sets the authordata global when viewing an author archive.
		 *
		 * This provides backwards compatibility with
		 * http://core.trac.wordpress.org/changeset/25574
		 *
		 * It removes the need to call the_post() and rewind_posts() in an author
		 * template to print information about the author.
		 *
		 * @since  1.0.0
		 *
		 * @global WP_Query $wp_query WordPress Query object.
		 * @return void
		 */
		public function setup_author() {
			global $wp_query;

			if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
				$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
			}
		}

		/**
		 * Set up title tag in <HEAD> in versions prior to 4.1.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function maybe_setup_title_tag() {
			if ( function_exists( '_wp_render_title_tag' ) ) {
				return;
			}

			?>
<title><?php wp_title(); ?></title>
			<?php
		}

		/**
		 * Sets up styles for the header to ensure that the nav & branding bits don't overlap.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function setup_header_styling() {
			$offset = get_transient( 'reach_navigation_offset' );

			if ( ! $offset ) {
				return;
			}
			?>
<style>@media screen and (min-width: 50em) {
	.site-navigation{
		margin-top: <?php echo $offset['top']; ?>px;
		margin-top: <?php echo $offset['top'] / 10; ?>rem;
		max-width: -webkit-calc(100% - <?php echo $offset['left'] / 10; ?>rem);
		max-width: -moz-calc(100% - <?php echo $offset['left'] / 10; ?>rem);
		max-width: calc(100% - <?php echo $offset['left'] / 10; ?>rem);
	}
}</style>
			<?php
		}

		/**
		 * Set up custom fonts.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function setup_fonts() {
			echo apply_filters( 'reach_font_link', "<link href='//fonts.googleapis.com/css?family=Merriweather:400,400italic,700italic,700,300italic,300%7COswald:400,300' rel='stylesheet' type='text/css'>" );
		}

		/**
		 * Save the top offset for the primary navigation.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function save_primary_navigation_offset() {
			if ( ! isset( $_POST['offset_t'] ) || ! isset( $_POST['offset_l'] ) ) {
				wp_send_json_error();
			}

			set_transient( 'reach_navigation_offset', array( 'top' => $_POST['offset_t'], 'left' => $_POST['offset_l'] ) );

			wp_send_json_success();
		}

		/**
		 * Filters <title> tag based on what is being viewed.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $title Default title text for current view.
		 * @param  string $sep   Optional separator.
		 * @return string The filtered title.
		 */
		public function wp_title( $title, $sep ) {
			if ( is_feed() ) {
				return $title;
			}

			global $page, $paged;

			// Add the blog name
			$title .= get_bloginfo( 'name', 'display' );

			// Add the blog description for the home/front page.
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) ) {
				$title .= " $sep $site_description";
			}

			// Add a page number if necessary:
			if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
				$title .= " $sep " . sprintf( __( 'Page %s', 'reach' ), max( $paged, $page ) );
			}

			return $title;
		}

		/**
		 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
		 *
		 * @since  1.0.0
		 *
		 * @param  array $args Configuration arguments.
		 * @return array
		 */
		public function page_menu_args( $args ) {
			$args['show_home'] = true;
			return $args;
		}

		/**
		 * Remove any templates that require (or only make sense with) Charitable.
		 *
		 * @since  1.0.0
		 *
		 * @param  string[] $page_templates
		 * @return string[]
		 */
		public function filter_page_templates( $page_templates ) {
			if ( ! in_array( 'charitable', $this->active_modules ) ) {
				unset(
					$page_templates['page-templates/homepage.php'],
					$page_templates['page-templates/user-dashboard.php']
				);
			}

			return $page_templates;
		}

		/**
		 * Adds the layout class to the body.
		 *
		 * @since  1.0.0
		 *
		 * @param  string[] $classes
		 * @return string[]
		 */
		public function body_classes( $classes ) {
			$layout    = $this->get_theme_setting( 'layout', true );
			$classes[] = $layout ? $layout : 'layout-wide';

			if ( is_page_template( 'page-templates/user-dashboard.php' ) ) {
				$classes[] = 'user-dashboard';
			}

			return $classes;
		}

		/**
		 * Filters the post class.
		 *
		 * @since  1.0.0
		 *
		 * @param  string[] $classes
		 * @return string[]
		 */
		public function post_classes( $classes ) {
			if ( is_page_template( 'page-templates/homepage.php' ) && ! in_array( 'campaign-widget', $classes ) ) {
				return array_merge( $classes, array( 'feature-block' ) );
			}

			return $classes;
		}

		/**
		 * Filters the "more" link on post archives.
		 *
		 * @since  1.0.0
		 *
		 * @return string
		 */
		public function the_content_more_link_filter() {
			$title = sprintf(
				/* translators: %s: post title */
				esc_attr_x( 'Keep reading %s', 'keep reading post', 'reach' ),
				get_the_title()
			);

			return sprintf(
				'<span class="aligncenter"><a href="%1$s" class="more-link button-alt" title="%2$s">%3$s</a></span>',
				get_permalink(),
				$title,
				__( 'Continue Reading', 'reach' )
			);

		}

		/**
		 * Filters the next & previous posts links.
		 *
		 * @since  1.0.0
		 *
		 * @return string
		 */
		public function posts_navigation_link_attributes() {
			return 'class="button-alt"';
		}

		/**
		 * Wrap videos inside fit_video class.
		 *
		 * @since  1.0.0
		 *
		 * @param  string    $html
		 * @param  WP_oEmbed $data
		 * @param  string    $url
		 * @return string
		 */
		public function wrap_fullwidth_videos( $html, $data, $url ) {
			if ( 'video' === $data->type ) {
				return reach_fullwidth_video( $html );
			}
			return $html;
		}

		/**
		 * Add some custom shortcodes as valid shortcodes for the Reach_Media_Grabber class.
		 *
		 * @since  1.0.0
		 *
		 * @param  string[] $shortcodes
		 * @return string[]
		 */
		public function add_valid_media_grabber_shortcodes( $shortcodes ) {
			$shortcodes[] = 'layerslider';
			$shortcodes[] = 'rev_slider';
			$shortcodes[] = 'soliloquy';
			return $shortcodes;
		}
	}

endif; // End class_exists
