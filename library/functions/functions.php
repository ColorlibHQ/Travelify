<?php
/**
 * Travelify functions and definitions
 *
 * This file contains all the functions and it's definition that particularly can't be
 * in other files.
 *
 */

/****************************************************************************************/

add_action( 'wp_enqueue_scripts', 'travelify_scripts_styles_method' );
/**
 * Register and enqueue the theme's styles and scripts.
 *
 * Everything is versioned from TRAVELIFY_VERSION so bumping the theme version
 * busts every cached asset; nothing here depends on jQuery.
 */
function travelify_scripts_styles_method() {

	global $travelify_theme_options_settings;
	$options = $travelify_theme_options_settings;

	$uri = get_template_directory_uri();

	/**
	 * Ubuntu, served from the theme.
	 *
	 * Loaded before the stylesheet so the @font-face rules are in place by the
	 * time anything asks for the family.
	 */
	wp_enqueue_style( 'travelify-fonts', $uri . '/library/css/fonts.css', array(), TRAVELIFY_VERSION );

	/**
	 * Loads our main stylesheet.
	 */
	wp_enqueue_style( 'travelify_style', get_stylesheet_uri(), array( 'travelify-fonts' ), TRAVELIFY_VERSION );

	if ( is_rtl() ) {
		wp_enqueue_style( 'travelify-rtl-style', $uri . '/rtl.css', array( 'travelify_style' ), TRAVELIFY_VERSION );
	}

	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'travelify_functions', $uri . '/library/js/functions.js', array(), TRAVELIFY_VERSION, true );

	wp_localize_script(
		'travelify_functions',
		'travelifyScreenReaderText',
		array(
			/*
			 * Deliberately reusing the nav menu location's label rather than a
			 * fresh "Menu" string: this one is already translated in all
			 * nineteen bundled locales, and it is the only new string that is
			 * visible rather than screen-reader-only.
			 */
			'menu'     => esc_html__( 'Primary Menu', 'travelify' ),
			'expand'   => esc_html__( 'Open sub-menu of', 'travelify' ),
			'collapse' => esc_html__( 'Close sub-menu of', 'travelify' ),
		)
	);

	/**
	 * The featured slider only runs where it is rendered.
	 */
	if ( ( is_home() || is_front_page() ) && '0' === (string) $options['disable_slider'] ) {
		wp_enqueue_script( 'travelify_slider', $uri . '/library/js/slider.js', array(), TRAVELIFY_VERSION, true );

		wp_localize_script(
			'travelify_slider',
			'travelifySliderL10n',
			array(
				'slide' => esc_html__( 'Slide', 'travelify' ),
			)
		);
	}
}

/****************************************************************************************/

add_filter( 'wp_page_menu', 'travelify_wp_page_menu' );
/**
 * Remove div from wp_page_menu() and replace with ul.
 * @uses wp_page_menu filter
 */
function travelify_wp_page_menu( $page_markup ) {
	if ( ! preg_match( '/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches ) ) {
		return $page_markup;
	}

	$divclass   = $matches[1];
	$replace    = array( '<div class="' . $divclass . '">', '</div>' );
	$new_markup = str_replace( $replace, '', $page_markup );
	$new_markup = preg_replace( '/^<ul>/i', '<ul class="' . $divclass . '">', $new_markup );

	return $new_markup;
}

/****************************************************************************************/

if ( ! function_exists( 'travelify_pass_cycle_parameters' ) ) :
/**
 * Function to pass the slider effectr parameters from php file to js file.
 */
function travelify_pass_cycle_parameters() {

    global $travelify_theme_options_settings;
    $options = $travelify_theme_options_settings;

		$transition_effect   = $options[ 'transition_effect' ];
		$transition_delay    = $options[ 'transition_delay' ] * 1000;
		$transition_duration = $options[ 'transition_duration' ] * 1000;
    wp_localize_script(
        'travelify_slider',
        'travelify_slider_value',
        array(
						'transition_effect'   => $transition_effect,
						'transition_delay'    => $transition_delay,
						'transition_duration' => $transition_duration
        )
    );

}
endif;

/****************************************************************************************/

add_filter( 'excerpt_length', 'travelify_excerpt_length' );
/**
 * Sets the post excerpt length to 30 words.
 *
 * function tied to the excerpt_length filter hook.
 *
 * @uses filter excerpt_length
 */
function travelify_excerpt_length( $length ) {
	return 40;
}

add_filter( 'excerpt_more', 'travelify_continue_reading' );
/**
 * Returns a "Continue Reading" link for excerpts
 */
function travelify_continue_reading() {
	return '&hellip; ';
}

/****************************************************************************************/

add_filter( 'body_class', 'travelify_body_class' );
/**
 * Filter the body_class
 *
 * Throwing different body class for the different layouts in the body tag
 */
function travelify_body_class( $classes ) {
	global $post;
	global $travelify_theme_options_settings;
	$options = $travelify_theme_options_settings;

	if( $post ) {
		$layout = get_post_meta( $post->ID,'travelify_sidebarlayout', true );
	}
	if( empty( $layout ) || is_archive() || is_search() || is_home() ) {
		$layout = 'default';
	}
	if( 'default' == $layout ) {

		$themeoption_layout = $options[ 'default_layout' ];

		if( 'left-sidebar' == $themeoption_layout ) {
			$classes[] = 'left-sidebar-template';
		}
		elseif( 'right-sidebar' == $themeoption_layout  ) {
			$classes[] = '';
		}
		elseif( 'no-sidebar-full-width' == $themeoption_layout ) {
			$classes[] = '';
		}
		elseif( 'no-sidebar-one-column' == $themeoption_layout ) {
			$classes[] = 'one-column-template';
		}
		elseif( 'no-sidebar' == $themeoption_layout ) {
			$classes[] = 'no-sidebar-template';
		}
	}
	elseif( 'left-sidebar' == $layout ) {
      $classes[] = 'left-sidebar-template';
   }
   elseif( 'right-sidebar' == $layout ) {
		$classes[] = '';
	}
	elseif( 'no-sidebar-full-width' == $layout ) {
		$classes[] = '';
	}
	elseif( 'no-sidebar-one-column' == $layout ) {
		$classes[] = 'one-column-template';
	}
	elseif( 'no-sidebar' == $layout ) {
		$classes[] = 'no-sidebar-template';
	}

	if ( is_page_template( 'templates/template-blog-medium-image.php' ) ) {
		$classes[] = 'blog-medium';
	}

	return $classes;
}

/****************************************************************************************/

add_action('wp_head', 'travelify_internal_css');
/**
 * Hooks the Custom Internal CSS to head section
 */
function travelify_internal_css() {

	$travelify_internal_css = get_transient( 'travelify_internal_css' );

	if ( false === $travelify_internal_css ) {

		global $travelify_theme_options_settings;
		$options = $travelify_theme_options_settings;

		$travelify_internal_css = '';

		if ( ! empty( $options['custom_css'] ) ) {
			/*
			 * strip_tags() here is deliberate: the stored value is CSS, so any
			 * tag in it can only be an attempt to break out of the <style>
			 * block. Legacy installs may hold values saved before the option
			 * was sanitised.
			 */
			$travelify_internal_css  = '<style id="travelify-custom-css" media="screen">' . "\n";
			$travelify_internal_css .= wp_strip_all_tags( $options['custom_css'] ) . "\n";
			$travelify_internal_css .= '</style>' . "\n";
		}

		set_transient( 'travelify_internal_css', $travelify_internal_css, 86940 );
	}

	echo $travelify_internal_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built and sanitised above.
}


/****************************************************************************************/

add_action('template_redirect', 'travelify_feed_redirect');
/**
 * Redirect WordPress Feeds To FeedBurner
 */
function travelify_feed_redirect() {
	if ( ! is_feed() ) {
		return;
	}

	global $travelify_theme_options_settings;
	$options = $travelify_theme_options_settings;

	if ( empty( $options['feed_url'] ) ) {
		return;
	}

	/*
	 * Re-validate at output time. esc_url_raw() strips the CR/LF that would
	 * otherwise let a stored value inject extra response headers, and rejects
	 * anything that is not an http(s) URL.
	 */
	$feed_url = esc_url_raw( $options['feed_url'], array( 'http', 'https' ) );

	if ( empty( $feed_url ) ) {
		return;
	}

	$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';

	// Never redirect the services that are fetching the feed on our behalf.
	if ( preg_match( '/feedburner|feedvalidator/i', $user_agent ) ) {
		return;
	}

	wp_redirect( $feed_url, 302 );
	exit;
}

/****************************************************************************************/

add_action( 'pre_get_posts','travelify_alter_home' );
/**
 * Alter the query for the main loop in home page
 *
 * @uses pre_get_posts hook
 */
function travelify_alter_home( $query ) {
	if ( ! $query->is_main_query() || ! $query->is_home() ) {
		return;
	}

	global $travelify_theme_options_settings;
	$options = $travelify_theme_options_settings;

	$slides = isset( $options['featured_post_slider'] ) && is_array( $options['featured_post_slider'] ) ? array_map( 'absint', $options['featured_post_slider'] ) : array();
	$cats   = isset( $options['front_page_category'] ) && is_array( $options['front_page_category'] ) ? array_map( 'absint', $options['front_page_category'] ) : array();

	if ( ! empty( $slides ) && '0' !== (string) $options['exclude_slider_post'] ) {
		$query->query_vars['post__not_in'] = $slides;
	}

	if ( ! empty( $cats ) && ! in_array( 0, $cats, true ) ) {
		$query->query_vars['category__in'] = $cats;
	}
}


/****************************************************************************************/

add_filter('wp_page_menu', 'travelify_wp_page_menu_filter');
/**
 * @uses wp_page_menu filter hook
 */
if ( !function_exists('travelify_wp_page_menu_filter') ) {
	function travelify_wp_page_menu_filter( $text ) {
		$replace = array(
			'current_page_item' => 'current-menu-item'
	 	);

	  $text = str_replace(array_keys($replace), $replace, $text);
	  return $text;
	}
}

/**************************************************************************************/

/**
 * WooCommerce
 *
 * Unhook/Hook the WooCommerce Wrappers
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'travelify_responsive_woocommerce_wrapper', 10);
add_action('woocommerce_after_main_content', 'travelify_responsive_woocommerce_wrapper_end', 10);

function travelify_responsive_woocommerce_wrapper() {
  echo '<div id="content-woocommerce" class="main">';
}

function travelify_responsive_woocommerce_wrapper_end() {
  echo '</div><!-- end of #content-woocommerce -->';
}

/**************************************************************************************/

/**
 * Function to register the widget areas(sidebar) and widgets.
 */
function travelify_widgets_init() {

	// Registering main left sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Left Sidebar', 'travelify' ),
		'id'            => 'travelify_left_sidebar',
		'description'   => esc_html__( 'Shows widgets at Left side.', 'travelify' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	// Registering main right sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Right Sidebar', 'travelify' ),
		'id'            => 'travelify_right_sidebar',
		'description'   => esc_html__( 'Shows widgets at Right side.', 'travelify' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	// Registering footer widgets
	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'travelify' ),
		'id'            => 'travelify_footer_widget',
		'description'   => esc_html__( 'Shows widgets at footer.', 'travelify' ),
		'before_widget' => '<div class="col-3"><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside></div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
		)
	);
}
add_action( 'widgets_init', 'travelify_widgets_init' );

/**
 * Sets up the WordPress core custom header arguments and settings.
 *
 * @uses add_theme_support() to register support for 3.4 and up.
 * @uses travelify_header_style() to style front-end.
 * @uses travelify_admin_header_style() to style wp-admin form.
 * @uses travelify_admin_header_image() to add custom markup to wp-admin form.
 *
 */
	$args = array(
		// Text color and image (empty to use none).
		'default-text-color'     => '',
		'default-image'          => '',

		// Set height and width, with a maximum value for the width.
		'height'                 => apply_filters( 'travelify_header_image_height', 250 ),
		'width'                  => apply_filters( 'travelify_header_image_width', 1018 ),
		'max-width'              => 1018,

		// Support flexible height and width.
		'flex-height'            => true,
		'flex-width'             => true,

		// Random image rotation off by default.
		'random-default'         => false,

		// No Header Text Feature
		'header-text'            => false,

		// Callbacks for styling the header and the admin preview.
		'wp-head-callback'       => '',
		'admin-head-callback'    => 'travelify_admin_header_style',
		'admin-preview-callback' => 'travelify_admin_header_image',
	);

	add_theme_support( 'custom-header', $args );

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 */

function travelify_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg img {
		max-width: <?php echo esc_attr( get_theme_support( 'custom-header', 'max-width' ) ); ?>px;
	}
	</style>
<?php
}

/**
 * Outputs markup to be displayed on the Appearance > Header admin panel.
 * This callback overrides the default markup displayed there.
 */

function travelify_admin_header_image() {
	?>
	<div id="headimg">
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="" />
		<?php endif; ?>
	</div>

<?php }


if ( ! function_exists( 'travelify_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function travelify_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	$byline = sprintf(
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);
	echo '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>' . '</span>';
}
endif;

?>