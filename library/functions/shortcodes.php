<?php
/**
 * Display a link back to the site.
 *
 * @uses get_bloginfo() Gets the site link
 * @return string
 */
function travelify_site_link() {
   return '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" ><span>' . esc_html( get_bloginfo( 'name', 'display' ) ) . '</span></a>';
}

/**
 * Display a link to WordPress.org.
 *
 * @return string
 */
function travelify_wp_link() {
   return '<a href="'.esc_url( 'https://wordpress.org' ).'" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'WordPress', 'travelify' ) . '"><span>' . esc_html__( 'WordPress', 'travelify' ) . '</span></a>';
}

/**
 * Display a link to colorlib.com.
 *
 * @return string
 */
function travelify_colorlib_link() {
   return '<a href="'.esc_url( 'https://colorlib.com/wp/travelify/' ).'" target="_blank" rel="noopener noreferrer" title="'.esc_attr__( 'Colorlib', 'travelify' ).'" ><span>' . esc_html__( 'Colorlib', 'travelify' ) . '</span></a>';
}

?>