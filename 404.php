<?php
/**
 * Displays the 404 error page of the theme.
 */
?>

<?php get_header(); ?>

<div id="content">
		<header class="entry-header">
			<h1 class="entry-title"><?php esc_html_e( 'Error 404 - Page NOT Found', 'travelify' ); ?></h1>
		</header>
		<div class="entry-content clearfix" >
			<p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for.', 'travelify' ); ?></p>
			<h3><?php esc_html_e( 'This might be because:', 'travelify' ); ?></h3>
			<ul>
				<li><?php esc_html_e( 'You have typed the web address incorrectly', 'travelify' ); ?></li>
				<li><?php esc_html_e( 'The page you were looking for may have been moved, updated or deleted.', 'travelify' ); ?></li>
			</ul>
			<h3><?php esc_html_e( 'Please try the following:', 'travelify' ); ?></h3>
			<ul>
				<li><?php esc_html_e( 'Check for a mis-typed URL error', 'travelify' ); ?></li>
				<li><?php esc_html_e( 'Press the refresh button on your browser.', 'travelify' ); ?></li>
				<li><?php esc_html_e( 'Go back to', 'travelify' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php esc_html_e( 'Homepage', 'travelify' ); ?></a></li>
			</ul>
		</div><!-- .entry-content -->
	</div><!-- #content -->

<?php get_footer(); ?>