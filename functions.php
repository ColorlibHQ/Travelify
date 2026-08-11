<?php
/**
 * Travelify defining constants, adding files and WordPress core functionality.
 *
 * @package Travelify
 */

/**
 * The theme version, read from the style.css header.
 *
 * Every asset is enqueued against this, so bumping the header cache-busts the
 * lot -- never hardcode a version in an enqueue.
 */
if ( ! defined( 'TRAVELIFY_VERSION' ) ) {
	$travelify_theme = wp_get_theme( get_template() );
	define( 'TRAVELIFY_VERSION', $travelify_theme->get( 'Version' ) );
}

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 700;
}


if ( ! function_exists( 'travelify_setup' ) ) :

	add_filter( 'widget_text', 'do_shortcode' );

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	add_action( 'after_setup_theme', 'travelify_setup' );

	/**
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 */
	function travelify_setup() {
		/**
		 * travelify_add_files hook
		 *
		 * Adding other addtional files if needed.
		 */
		do_action( 'travelify_add_files' );

		/* Travelify is now available for translation. */
		require get_template_directory() . '/library/functions/i18n.php';

		/** Load functions */
		require get_template_directory() . '/library/functions/functions.php';

		/** Load WP backend related functions */
		require get_template_directory() . '/library/panel/themeoptions-defaults.php';
		require get_template_directory() . '/library/panel/metaboxes.php';
		require get_template_directory() . '/library/panel/show-post-id.php';

		/** Load Shortcodes */
		require get_template_directory() . '/library/functions/shortcodes.php';

		/** Load WP Customizer */
		require get_template_directory() . '/library/functions/customizer.php';

		/** Load Structure */
		require get_template_directory() . '/library/structure/header-extensions.php';
		require get_template_directory() . '/library/structure/sidebar-extensions.php';
		require get_template_directory() . '/library/structure/footer-extensions.php';
		require get_template_directory() . '/library/structure/content-extensions.php';

		/**
		 * travelify_add_functionality hook
		 *
		 * Adding other addtional functionality if needed.
		 */
		do_action( 'travelify_add_functionality' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page.
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in header menu location.
		register_nav_menu( 'primary', __( 'Primary Menu', 'travelify' ) );

		// Add Travelify custom image sizes.
		add_image_size( 'travelify-featured', 670, 300, true );
		add_image_size( 'travelify-featured-medium', 230, 230, true );
		add_image_size( 'travelify-slider', 1018, 460, true );        // used on Featured Slider on Homepage Header
		add_image_size( 'travelify-gallery', 474, 342, true );                // used to show gallery all images

		// This feature enables WooCommerce support for a theme.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		/**
		 * Let WordPress emit valid HTML5 for the markup it generates on our behalf.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
				'navigation-widgets',
			)
		);

		/**
		 * Block editor support.
		 */
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'custom-line-height' );
		add_theme_support( 'custom-spacing' );
		add_theme_support( 'custom-units' );
		add_theme_support( 'editor-styles' );

		/**
		 * The site logo used to be a bare URL in the theme options. Core's
		 * custom-logo handles srcset, cropping and the Customizer preview;
		 * travelify_migrate_header_logo() moves the old value across.
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 100,
				'width'       => 300,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		/**
		 * This theme supports custom background color and image
		 */
		$args = array(
			'default-color' => '#d3d3d3',
			'default-image' => get_template_directory_uri() . '/images/background.png',
		);
		add_theme_support( 'custom-background', $args );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * This theme supports add_editor_style
		 *
		 * The bundled font goes in first so the editor uses Ubuntu for
		 * headings, exactly like the front end.
		 */
		add_editor_style( array( 'library/css/fonts.css', 'editor-style.css' ) );
	}
endif; // travelify_setup

add_action( 'after_setup_theme', 'travelify_migrate_custom_css', 11 );
/**
 * Fold the theme's own Custom CSS option into core's Additional CSS.
 *
 * Runs once: the option key is removed afterwards.
 */
function travelify_migrate_custom_css() {
	$travelify_options = get_option( 'travelify_theme_options' );

	if ( ! is_array( $travelify_options ) || ! isset( $travelify_options['custom_css'] ) ) {
		return;
	}

	$posts = get_posts(
		array(
			'post_type'   => 'custom_css',
			'name'        => 'travelify',
			'orderby'     => 'date',
			'order'       => 'DESC',
			'numberposts' => 1,
		)
	);

	if ( empty( $posts ) ) {
		return;
	}

	$travelify_wp_css = $posts[0];

	$travelify_wp_css->post_content .= $travelify_options['custom_css'];
	wp_update_post( $travelify_wp_css );

	unset( $travelify_options['custom_css'] );
	delete_transient( 'travelify_internal_css' );
	update_option( 'travelify_theme_options', $travelify_options );
}

add_action( 'after_setup_theme', 'travelify_migrate_header_logo', 11 );
/**
 * Move a logo stored in the theme options onto core's custom-logo.
 *
 * The old option held a bare URL, so the matching attachment has to be looked
 * up. Sites where that fails keep rendering from the option -- see
 * travelify_headerdetails().
 */
function travelify_migrate_header_logo() {
	if ( get_theme_mod( 'custom_logo' ) || get_theme_mod( 'travelify_logo_migrated' ) ) {
		return;
	}

	$options = get_option( 'travelify_theme_options' );

	if ( ! is_array( $options ) || empty( $options['header_logo'] ) ) {
		return;
	}

	$attachment_id = attachment_url_to_postid( $options['header_logo'] );

	if ( $attachment_id ) {
		set_theme_mod( 'custom_logo', $attachment_id );
	}

	// Only ever try once, successful or not.
	set_theme_mod( 'travelify_logo_migrated', true );
}
