<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Reach
 */
?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes() ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="hfeed site-container">
		<div class="layout-wrapper">
			<a class="skip-link screen-reader-text" href="#main"><?php _e( 'Skip to content', 'reach' ); ?></a>
			<?php get_template_part( 'partials/social-profiles' ) ?>
			<?php get_template_part( 'partials/account-links' ) ?>
		</div><!-- .layout-wrapper -->
		<div class="body-wrapper">
			<header id="header" class="cf site-header">
				<div class="layout-wrapper">
					<div class="site-branding site-identity">
						<?php reach_site_identity() ?>
					</div><!-- .site-branding -->
					<nav class="site-navigation">
						<a class="menu-toggle menu-button toggle-button" aria-controls="primary-navigation" aria-expanded="false"></a>
						<?php wp_nav_menu( array(
							'theme_location' 	=> 'primary_navigation',
							'container' 		=> false,
							'menu_class' 		=> 'menu menu-site responsive_menu',
							'menu_id'			=> 'primary-navigation',
						) ) ?>
				    </nav><!-- .site-navigation -->
				</div><!-- .layout-wrapper -->
			</header><!-- #header -->
