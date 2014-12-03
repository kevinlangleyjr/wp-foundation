<?php
remove_shortcode('gallery');
add_shortcode( 'gallery', 'wp_foundation_clearing_gallery' );
/**
 * Overridden Gallery shortcode implementing the Zurb Foundation 5 Clearing Lightbox
 *
 * This implements the functionality of the Gallery Shortcode for displaying
 * WordPress images on a post.
 *
 *
 * @param array $attr {
 *     Attributes of the gallery shortcode.
 *
 *     @type string $order      Order of the images in the gallery. Default 'ASC'. Accepts 'ASC', 'DESC'.
 *     @type string $orderby    The field to use when ordering the images. Default 'menu_order ID'.
 *                              Accepts any valid SQL ORDERBY statement.
 *     @type int    $id         Post ID.
 *     @type string $itemtag    HTML tag to use for each image in the gallery.
 *                              Default 'dl', or 'figure' when the theme registers HTML5 gallery support.
 *     @type string $icontag    HTML tag to use for each image's icon.
 *                              Default 'dt', or 'div' when the theme registers HTML5 gallery support.
 *     @type string $captiontag HTML tag to use for each image's caption.
 *                              Default 'dd', or 'figcaption' when the theme registers HTML5 gallery support.
 *     @type int    $columns    Number of columns of images to display. Default 3.
 *     @type string $size       Size of the images to display. Default 'thumbnail'.
 *     @type string $ids        A comma-separated list of IDs of attachments to display. Default empty.
 *     @type string $include    A comma-separated list of IDs of attachments to include. Default empty.
 *     @type string $exclude    A comma-separated list of IDs of attachments to exclude. Default empty.
 *     @type string $link       What to link each image to. Default empty (links to the attachment page).
 *                              Accepts 'file', 'none'.
 * }
 * @return string HTML content to display gallery.
 */
function wp_foundation_clearing_gallery( $attr ) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	/**
	 * Filter the default gallery shortcode output.
	 *
	 * If the filtered output isn't empty, it will be used instead of generating
	 * the default gallery template.
	 *
	 *
	 * @param string $output The gallery output. Default empty.
	 * @param array  $attr   Attributes of the gallery shortcode.
	 */
	$output = apply_filters( 'post_gallery', '', $attr );
	if ( $output != '' ) {
		return $output;
	}

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( ! $attr['orderby'] ) {
			unset( $attr['orderby'] );
		}
	}

	$html5 = current_theme_supports( 'html5', 'gallery' );
	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => 'li',
		'icontag'    => 'div',
		'captiontag' => 'div',
		'columns'    => 3,
		'size'       => 'medium-cropped',
		'include'    => '',
		'exclude'    => '',
		'link'       => 'file'
	), $attr, 'gallery' );

	$id = intval( $atts['id'] );
	if ( 'RAND' == $atts['order'] ) {
		$atts['orderby'] = 'none';
	}

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}
		return $output;
	}

	$itemtag = tag_escape( $atts['itemtag'] );
	$captiontag = tag_escape( $atts['captiontag'] );
	$icontag = tag_escape( $atts['icontag'] );
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) ) {
		$itemtag = 'li';
	}
	if ( ! isset( $valid_tags[ $captiontag ] ) ) {
		$captiontag = 'div';
	}
	if ( ! isset( $valid_tags[ $icontag ] ) ) {
		$icontag = 'div';
	}

	$columns = intval( $atts['columns'] );

	$selector = "gallery-{$instance}";

	$size_class = sanitize_html_class( $atts['size'] );
	$gallery_div = "<ul id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} clearing-thumbs' data-clearing>";

	/**
	 * Filter the default gallery shortcode CSS styles.
	 *
	 * @param string $gallery_div   Opening HTML div container for the gallery shortcode output.
	 */
	$output = apply_filters( 'gallery_output', $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
			$image_output = wp_get_attachment_link( $id, $atts['size'], false, false );
		} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
			$image_output = wp_get_attachment_image( $id, $atts['size'], false );
		} else {
			$image_output = wp_get_attachment_link( $id, $atts['size'], true, false );
		}
		$image_meta  = wp_get_attachment_metadata( $id );

		$caption_text = wptexturize($attachment->post_excerpt);

		$image_output = str_replace('<img', '<img data-caption="' . $caption_text . '"', $image_output);

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
		}
		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . $caption_text . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
	}

	$output .= "
		</ul>\n";

	return $output;
}