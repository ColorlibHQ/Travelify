<?php
/**
 * Adds footer structures.
 *
 */

/****************************************************************************************/

add_action( 'travelify_footer', 'travelify_footer_widget_area', 10 );
/**
 * Displays the footer widgets
 */
function travelify_footer_widget_area() {
	get_sidebar( 'footer' );
}

/****************************************************************************************/

add_action( 'travelify_footer', 'travelify_open_sitegenerator_div', 20 );
/**
 * Opens the site generator div.
 */
function travelify_open_sitegenerator_div() {
	echo '<div id="site-generator">
				<div class="container">';
}

/****************************************************************************************/

add_action( 'travelify_footer', 'travelify_footer_info', 30 );
/**
 * function to show the footer info, copyright information
 */
function travelify_footer_info() {
	echo '<div class="copyright">'
		. esc_html__( 'Copyright &copy;', 'travelify' ) . ' '
		. esc_html( wp_date( 'Y' ) ) . ' '
		. travelify_site_link() . '. ' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper.
		. esc_html__( 'Theme by', 'travelify' ) . ' '
		. travelify_colorlib_link() . ' ' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper.
		. esc_html__( 'Powered by', 'travelify' ) . ' '
		. travelify_wp_link() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper.
		. '</div><!-- .copyright -->';
}

/****************************************************************************************/

add_action( 'travelify_footer', 'travelify_close_sitegenerator_div', 35 );


/**
 * add content to the right side of footer
 */
function travelify_footer_rightinfo() {
		echo '<div class="footer-right">';
		echo wp_kses_post( get_theme_mod( 'travelify_footer_textbox' ) );
		echo '</div>';
}
add_action( 'travelify_footer', 'travelify_footer_rightinfo', 30 );

/**
 * Closes the site generator div.
 */
function travelify_close_sitegenerator_div() {
	echo '<div style="clear:both;"></div>
			</div><!-- .container -->
			</div><!-- #site-generator -->';
}

/****************************************************************************************/

add_action( 'travelify_footer', 'travelify_backtotop_html', 40 );
/**
 * Shows the back to top icon to go to top.
 */
function travelify_backtotop_html() {
	echo '<div class="back-to-top"><a href="#branding"></a></div>';
}

?>