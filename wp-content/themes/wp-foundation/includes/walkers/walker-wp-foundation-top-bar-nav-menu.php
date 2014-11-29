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

		$link_string = $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output = sprintf( '%s<a %s>%s</a>%s', $args->before, $attributes, $link_string, $args->after );

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {

		$indent = str_repeat("\t", 1);
		$output .= "\n$indent<ul class=\"dropdown\">\n";
	}
}