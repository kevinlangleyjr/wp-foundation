<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package WP Foundation
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function wp_foundation_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'wp_foundation_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function wp_foundation_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'wp_foundation_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function wp_foundation_wp_title( $title, $sep ) {
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
		$title .= " $sep " . sprintf( __( 'Page %s', 'wp_foundation' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'wp_foundation_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function wp_foundation_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'wp_foundation_setup_author' );

function wp_foundation_wp_link_pages_link( $link, $i ){
	global $page;

	if( $page == $i ){
		if( strpos( $link, '<a' ) === false ){
			$link = '<li class="current"><a href="#">' . $link . '</a></li>';
		} else {
			$link = '<li class="current">' . $link . '</li>';
		}
	} else {
		$link = '<li>' . $link . '</li>';
	}

	return $link;
}
add_filter( 'wp_link_pages_link', 'wp_foundation_wp_link_pages_link', 10, 2 );

function wp_foundation_password_form(){
	global $post;

	$label = 'pwbox-' . ( empty($post->ID) ? rand() : $post->ID );
	ob_start();

	?>
	<form method="post" action="<?php echo esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ); ?>" class="post-password-form">
		<p><?php _e( 'This content is password protected. To view it please enter your password below:' ); ?></p>
		<div class="row collapse">
			<div class="large-9 columns">
				<input name="post_password" id="<?php echo esc_attr( $label ); ?>" type="password" size="20" placeholder="Password">
			</div>
			<div class="large-3 columns">
				<input type="submit" name="Submit" value="<?php echo esc_attr__( 'Submit' ); ?>" class="button postfix">
			</div>
		</div>
	</form>
	<?php

	$output = ob_get_clean();

	return $output;
}
add_filter( 'the_password_form', 'wp_foundation_password_form' );

function wp_foundation_more_link() {
	return sprintf('<a class="more-link button" href="%s">%s</a>', esc_url( get_permalink() ), __( 'Read More', 'wp_foundation' ) );
}
add_filter( 'the_content_more_link', 'wp_foundation_more_link' );