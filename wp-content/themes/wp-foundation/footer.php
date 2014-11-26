<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WP Foundation
 */
?>
		</div><!-- .row -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
			<p class="site-info">
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'wp_foundation' ) ); ?>" target="_blank"><?php printf( __( 'Proudly powered by %s', 'wp_foundation' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php printf( __( 'Theme: %1$s by %2$s', 'wp_foundation' ), 'wp_foundation', '<a href="http://kevinlangleyjr.com/" target="_blank" rel="designer">Kevin Langley Jr.</a>' ); ?>
				<span class="sep"> | </span>
				Based off of <a href="http://underscores.me/" target="_blank">_s</a> by <a href="http://automattic.com/" target="_blank">Automattic</a>
				<span class="sep"> | </span>
				Using <a href="http://foundation.zurb.com/" target="_blank">Foundation 5</a> by <a href="http://zurb.com/" target="_blank">Zurb</a>
			</p><!-- .site-info -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
