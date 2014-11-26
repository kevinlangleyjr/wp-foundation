<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package WP Foundation
 */

if ( ! is_active_sidebar( 'main-sidebar' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area large-4 columns" role="complementary">
	<?php dynamic_sidebar( 'main-sidebar' ); ?>
</div><!-- #secondary -->
