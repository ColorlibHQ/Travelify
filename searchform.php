<?php
/**
 * Displays the searchform of the theme.
 */

// Unique per form: the search form can appear more than once on a page.
$travelify_search_id = wp_unique_id( 'search-field-' );
?>
	<form role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="searchform clearfix" method="get">
		<label class="assistive-text" for="<?php echo esc_attr( $travelify_search_id ); ?>"><?php esc_html_e( 'Search', 'travelify' ); ?></label>
		<input type="search" id="<?php echo esc_attr( $travelify_search_id ); ?>" placeholder="<?php esc_attr_e( 'Search', 'travelify' ); ?>" class="s field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>">
	</form>
