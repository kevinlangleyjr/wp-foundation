<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package WP Foundation
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="main-container">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'wp_foundation' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="contain-to-grid">
			<nav class="top-bar" data-topbar>
				<ul class="title-area">
					<li class="name">
						<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					</li>
					<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
					<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>
				<section class="top-bar-section">
					<?php wp_nav_menu( array(
							'theme_location' => 'primary',
							'container' => false,
							'depth' => 0,
							'items_wrap' => '<ul id="%1$s" class="%2$s right">%3$s</ul>',
							'walker' => new Walker_WP_Foundation_Top_Bar_Nav_Menu()
					) ); ?>
				</section>
			</nav>
		</div><!-- .container -->
	</header><!-- #masthead -->

	<div id="content" class="site-content container">
		<div class="row">
