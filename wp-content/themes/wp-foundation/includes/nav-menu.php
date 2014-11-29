<?php

class Walker_WP_Foundation_Top_Bar_Nav_Menu extends Walker_Nav_Menu {

	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		$id_field = $this->db_fields['id'];
		if ( is_object( $args[0] ) ) {
			$element->has_children = ! empty( $children_elements[$element->$id_field] );
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if( is_array( $args ) )
			$args = (object) $args;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$slug = sanitize_title($item->title);
		$classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', '', $classes);
		$classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

		$classes[] = 'menu-item menu-item-' . $slug;

		$classes = array_unique( $classes );

		if ( $item->has_children ) {
			$classes[] = 'has-dropdown';
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		if( $depth === 0 )
			$output .= $indent . '<li class="divider"></li>';

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';

		if( property_exists( $item, 'title' ) ){
			$link_string = $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		} else {
			$link_string = $args->link_before . apply_filters( 'the_title', $item->post_title, $item->ID ) . $args->link_after;
		}
		$item_output = sprintf( '%s<a %s>%s</a>%s', $args->before, $attributes, $link_string, $args->after );

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {

		$indent = str_repeat("\t", 1);
		$output .= "\n$indent<ul class=\"dropdown\">\n";
	}
}

/**
 * Display or retrieve list of pages with optional home link.
 *
 * The arguments are listed below and part of the arguments are for {@link
 * wp_list_pages()} function. Check that function for more info on those
 * arguments.
 *
 *
 * @param array|string $args {
 *     Optional. Arguments to generate a page menu. {@see wp_list_pages()}
 *     for additional arguments.
 *
 * @type string     $sort_column How to short the list of pages. Accepts post column names.
 *                               Default 'menu_order, post_title'.
 * @type string     $menu_class  Class to use for the div ID containing the page list. Default 'menu'.
 * @type bool       $echo        Whether to echo the list or return it. Accepts true (echo) or false (return).
 *                               Default true.
 * @type string     $link_before The HTML or text to prepend to $show_home text. Default empty.
 * @type string     $link_after  The HTML or text to append to $show_home text. Default empty.
 * @type int|string $show_home   Whether to display the link to the home page. Can just enter the text
 *                               you'd like shown for the home link. 1|true defaults to 'Home'.
 * }
 * @return string html menu
 */
function wp_foundation_page_menu( $args = array() ) {
	$defaults = array(
		'sort_column' => 'menu_order, post_title',
		'menu_class' => 'menu',
		'container' => 'div',
		'container_class' => 'menu-container',
		'echo' => true,
		'link_before' => '',
		'link_after' => ''
	);
	$args = wp_parse_args( $args, $defaults );

	/**
	 * Filter the arguments used to generate a page-based menu.
	 *
	 * @see wp_foundation_page_menu()
	 *
	 * @param array $args An array of page menu arguments.
	 */
	$args = apply_filters( 'wp_foundation_page_menu_args', $args );

	$menu = '';

	$list_args = $args;

	// Show Home in the menu
	if ( ! empty($args['show_home']) ) {
		if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] )
			$text = __('Home');
		else
			$text = $args['show_home'];
		$class = '';
		if ( is_front_page() && !is_paged() )
			$class = 'class="current_page_item"';
		$menu .= '<li ' . $class . '><a href="' . home_url( '/' ) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
		// If the front page is a page, add it to the exclude list
		if (get_option('show_on_front') == 'page') {
			if ( !empty( $list_args['exclude'] ) ) {
				$list_args['exclude'] .= ',';
			} else {
				$list_args['exclude'] = '';
			}
			$list_args['exclude'] .= get_option('page_on_front');
		}
	}

	$list_args['echo'] = false;
	$list_args['title_li'] = '';
	$menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages($list_args) );

	if ( $menu )
		$menu = '<ul class="' . esc_attr( $args['menu_class'] ) . '">' . $menu . '</ul>';

	if( $args['container'] ){
		$allowed_tags = apply_filters( 'wp_foundation_page_menu_container_allowedtags', array( 'div', 'nav' ) );
		if ( in_array( $args['container'], $allowed_tags ) ) {
			$class = !empty( $args['container_class'] ) ? 'class="' . esc_attr( $args['container_class'] ) . '"' : '';
			$menu = '<div ' . $class . '>' . $menu . "</div>\n";
		}
	}

	/**
	 * Filter the HTML output of a page-based menu.
	 *
	 * @since 2.7.0
	 *
	 * @see wp_foundation_page_menu()
	 *
	 * @param string $menu The HTML output.
	 * @param array  $args An array of arguments.
	 */
	$menu = apply_filters( 'wp_foundation_page_menu', $menu, $args );
	if ( $args['echo'] )
		echo $menu;
	else
		return $menu;
}