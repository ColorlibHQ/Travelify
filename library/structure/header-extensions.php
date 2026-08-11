<?php
/**
 * Adds header structures.
 *
 */

/****************************************************************************************/

add_action( 'travelify_links', 'travelify_add_links', 10 );
/**
 * Adding link to stylesheet file
 *
 * @uses get_stylesheet_uri()
 */
function travelify_add_links() {
	?>
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php
	// Only advertise the endpoint when pingbacks are actually accepted.
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

/****************************************************************************************/

add_action( 'wp_head', 'travelify_no_js_class', 0 );
/**
 * Swap the no-js body class for js as early as possible.
 *
 * Printed from a hook rather than hardcoded in header.php so child themes and
 * plugins can remove it.
 */
function travelify_no_js_class() {
	?>
	<script>document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/, 'js' );</script>
	<?php
}

/****************************************************************************************/

add_action( 'travelify_header', 'travelify_headerdetails', 10 );
/**
 * Shows Header Part Content
 *
 * Shows the site logo, title, description, searchbar, social icons etc.
 */
function travelify_headerdetails() {
?>
	<?php
		global $travelify_theme_options_settings;
   	$options = $travelify_theme_options_settings;

   	$elements = array();
		$elements = array(
			$options[ 'social_facebook' ],
			$options[ 'social_twitter' ],
			$options[ 'social_linkedin' ],
			$options[ 'social_pinterest' ],
			$options[ 'social_youtube' ],
			$options[ 'social_vimeo' ],
			$options[ 'social_flickr' ],
			$options[ 'social_tumblr' ],
			$options[ 'social_instagram' ],
			$options[ 'social_rss' ],
			$options[ 'social_github' ]
		);

		$flag = 0;
		if( !empty( $elements ) ) {
			foreach( $elements as $option) {
				if( !empty( $option ) ) {
					$flag = 1;
				}
				else {
					$flag = 0;
				}
				if( 1 == $flag ) {
					break;
				}
			}
		}
	?>

	<div class="container clearfix">
		<div class="hgroup-wrap clearfix">
					<section class="hgroup-right">
						<?php travelify_socialnetworks( $flag ); ?>
					</section><!-- .hgroup-right -->
				<div id="site-logo" class="clearfix">
					<?php
					/*
					 * Only the front page gets an <h1> here; on every other view
					 * the entry title is the page heading.
					 */
					$travelify_title_tag = ( is_home() || is_front_page() ) ? 'h1' : 'p';

					if ( 'header-text' === $options['header_show'] ) {
						printf(
							'<%1$s id="site-title"><a href="%2$s" title="%3$s" rel="home">%4$s</a></%1$s>',
							esc_attr( $travelify_title_tag ),
							esc_url( home_url( '/' ) ),
							esc_attr( get_bloginfo( 'name', 'display' ) ),
							esc_html( get_bloginfo( 'name', 'display' ) )
						);

						$travelify_description = get_bloginfo( 'description', 'display' );

						if ( $travelify_description || is_customize_preview() ) {
							echo '<p id="site-description">' . esc_html( $travelify_description ) . '</p>';
						}
					} elseif ( 'header-logo' === $options['header_show'] ) {
						echo '<' . esc_attr( $travelify_title_tag ) . ' id="site-title">';

						if ( has_custom_logo() ) {
							// Core handles srcset, sizing and the Customizer preview.
							the_custom_logo();
						} elseif ( ! empty( $options['header_logo'] ) ) {
							// Fallback for installs whose old option URL had no matching attachment.
							printf(
								'<a href="%1$s" title="%2$s" rel="home"><img src="%3$s" alt="%2$s"></a>',
								esc_url( home_url( '/' ) ),
								esc_attr( get_bloginfo( 'name', 'display' ) ),
								esc_url( $options['header_logo'] )
							);
						}

						echo '</' . esc_attr( $travelify_title_tag ) . '>';
					}
					?>

				</div><!-- #site-logo -->

		</div><!-- .hgroup-wrap -->
	</div><!-- .container -->
	<?php $header_image = get_header_image();
			if( !empty( $header_image ) ) :?>
				<img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo esc_attr(get_custom_header()->width); ?>" height="<?php echo esc_attr(get_custom_header()->height); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
			<?php endif; ?>
	<?php
		if ( has_nav_menu( 'primary' ) ) {
			$args = array(
				'theme_location'    => 'primary',
				'container'         => '',
				'items_wrap'        => '<ul class="root">%3$s</ul>'
			);
			echo '<nav id="main-nav" class="clearfix">
					<div class="container clearfix">';
				wp_nav_menu( $args );
			echo '</div><!-- .container -->
					</nav><!-- #main-nav -->';
		}
		else {
			echo '<nav id="main-nav" class="clearfix">
					<div class="container clearfix">';
				wp_nav_menu( array( 
					'theme_location' => 'primary',
					'fallback_cb'    => 'wp_page_menu',
					'items_wrap'     => '<ul class="root">%3$s</ul>',
					'menu_class'     => 'root'
				) );
			echo '</div><!-- .container -->
					</nav><!-- #main-nav -->';
		}
	?>
		<?php
		if( is_home() || is_front_page() ) {
			if( "0" == $options[ 'disable_slider' ] ) {
				if( function_exists( 'travelify_pass_cycle_parameters' ) )
   				travelify_pass_cycle_parameters();
   			if( function_exists( 'travelify_featured_post_slider' ) )
   				travelify_featured_post_slider();
   		}
   		}

		else {
			if( ( '' != travelify_header_title() ) || function_exists( 'bcn_display_list' ) ) {
		?>
			<div class="page-title-wrap">
	    		<div class="container clearfix">
	    			<?php
		    		if( function_exists( 'travelify_breadcrumb' ) )
						travelify_breadcrumb();
					?>
				   <h1 class="page-title"><?php echo esc_html( travelify_header_title() ); ?></h1><!-- .page-title -->
				</div>
	    	</div>
	   <?php
	   	}
		}
}

/****************************************************************************************/

if ( ! function_exists( 'travelify_socialnetworks' ) ) :
/**
 * This function for social links display on header
 *
 * Get links through Theme Options
 */
function travelify_socialnetworks( $flag ) {

	global $travelify_theme_options_settings;
   $options = $travelify_theme_options_settings;

	$travelify_socialnetworks = '';
	if ( ( !$travelify_socialnetworks = get_transient( 'travelify_socialnetworks' ) ) && ( 1 == $flag ) )  {

		$travelify_socialnetworks .='
			<div class="social-icons clearfix">
				<ul>';

				$social_links = array(
					'Facebook'    => 'social_facebook',
					'Twitter'     => 'social_twitter',
					'Pinterest'   => 'social_pinterest',
					'YouTube'     => 'social_youtube',
					'Vimeo'       => 'social_vimeo',
					'LinkedIn'    => 'social_linkedin',
					'Flickr'      => 'social_flickr',
					'Tumblr'      => 'social_tumblr',
					'Instagram'   => 'social_instagram',
					'RSS'         => 'social_rss',
					'GitHub'      => 'social_github'
				);

				foreach( $social_links as $key => $value ) {
					if ( !empty( $options[ $value ] ) ) {
						$travelify_socialnetworks .=
							'<li class="'.strtolower($key).'"><a href="'.esc_url( $options[ $value ] ).'" title="'.sprintf( esc_attr__( '%1$s on %2$s', 'travelify' ), get_bloginfo( 'name' ), $key ).'" target="_blank"></a></li>';
					}
				}

				$travelify_socialnetworks .='
			</ul>
			</div><!-- .social-icons -->';

		set_transient( 'travelify_socialnetworks', $travelify_socialnetworks, 86940 );
	}
	echo $travelify_socialnetworks;
}
endif;


/****************************************************************************************/

if ( ! function_exists( 'travelify_featured_post_slider' ) ) :
/**
 * display featured post slider
 *
 */
function travelify_featured_post_slider() {
	global $post;

	global $travelify_theme_options_settings;
  	$options = $travelify_theme_options_settings;

	$travelify_featured_post_slider = '';

	$slides = isset( $options['featured_post_slider'] ) && is_array( $options['featured_post_slider'] ) ? array_filter( array_map( 'absint', $options['featured_post_slider'] ) ) : array();

	if ( ! empty( $slides ) ) {
		$get_featured_posts = new WP_Query( array(
			'posts_per_page'      => absint( $options['slider_quantity'] ),
			'post_type'           => array( 'post', 'page' ),
			'post__in'            => $slides,
			'orderby'             => 'post__in',
			'suppress_filters'    => false,
			'ignore_sticky_posts' => 1, // ignore sticky posts
		));

		$travelify_slides_markup = '';
		$i                       = 0;

			while ( $get_featured_posts->have_posts() ) :
				$get_featured_posts->the_post();

				/*
				 * The featured image is what a slide is; .featured-text is
				 * positioned over it. Without one the slide has no height at
				 * all, so skip it rather than cycle through a blank pane.
				 */
				if ( ! has_post_thumbnail() ) {
					continue;
				}

				$i++;

				$title_attribute = get_the_title( $post->ID );
				$excerpt         = get_the_excerpt();
				$classes         = ( 1 === $i ) ? 'slides displayblock' : 'slides displaynone';

				$travelify_slides_markup .= '
				<div class="' . esc_attr( $classes ) . '">';

						$travelify_slides_markup .= '<figure><a href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( $title_attribute ) . '">';

						$travelify_slides_markup .= get_the_post_thumbnail( $post->ID, 'travelify-slider', array( 'alt' => esc_attr( $title_attribute ), 'class' => 'pngfix' ) ) . '</a></figure>';

						if ( '' !== $title_attribute || '' !== $excerpt ) {
							$travelify_slides_markup .= '
							<article class="featured-text">';
							if ( '' !== $title_attribute ) {
								$travelify_slides_markup .= '<div class="featured-title"><a href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( $title_attribute ) . '">' . esc_html( get_the_title() ) . '</a></div><!-- .featured-title -->';
							}
							if ( '' !== $excerpt ) {
								$travelify_slides_markup .= '<div class="featured-content">' . wp_kses_post( $excerpt ) . '</div><!-- .featured-content -->';
							}
							$travelify_slides_markup .= '
							</article><!-- .featured-text -->';
						}
				$travelify_slides_markup .= '
				</div><!-- .slides -->';
			endwhile;
			wp_reset_postdata();

		// Nothing renderable: emit no markup at all rather than an empty shell.
		if ( '' !== $travelify_slides_markup ) {
			$travelify_featured_post_slider = '
		<section class="featured-slider"><div class="slider-cycle">'
				. $travelify_slides_markup . '</div>
		<nav id="controllers" class="clearfix">
		</nav><!-- #controllers --></section><!-- .featured-slider -->';
		}
	}
	echo $travelify_featured_post_slider; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped as it is built.
}
endif;

/****************************************************************************************/

if ( ! function_exists( 'travelify_breadcrumb' ) ) :
/**
 * Display breadcrumb on header.
 *
 * If the page is home or front page, slider is displayed.
 * In other pages, breadcrumb will display if breadcrumb NavXT plugin exists.
 */
function travelify_breadcrumb() {
	if( function_exists( 'bcn_display_list' ) ) {
		echo '<div class="breadcrumb">
		<ul>';
		bcn_display_list();
		echo '</ul>
		</div> <!-- .breadcrumb -->';
	}

}
endif;

/****************************************************************************************/

if ( ! function_exists( 'travelify_header_title' ) ) :
/**
 * Show the title in header
 */
function travelify_header_title() {
	if ( is_category() ) {
		$travelify_header_title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$travelify_header_title = single_tag_title( '', false );
	} elseif ( is_tax() ) {
		$travelify_header_title = single_term_title( '', false );
	} elseif ( is_author() ) {
		$travelify_header_title = get_the_author();
	} elseif ( is_post_type_archive() ) {
		$travelify_header_title = post_type_archive_title( '', false );
	} elseif ( is_date() ) {
		// Author, date and post-type archives used to fall through with no heading at all.
		$travelify_header_title = get_the_archive_title();
	} elseif ( is_archive() ) {
		$travelify_header_title = single_cat_title( '', false );
	} elseif ( is_search() ) {
		$travelify_header_title = __( 'Search Results', 'travelify' );
	} elseif ( is_page_template() ) {
		$travelify_header_title = get_the_title();
	} else {
		$travelify_header_title = '';
	}

	return $travelify_header_title;
}
endif;
?>