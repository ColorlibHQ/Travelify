/**
 * Theme Customizer live preview.
 *
 * Colour changes are applied by rewriting a single <style> block rather than
 * setting inline styles on a matched set: inline styles cannot express :hover
 * or :focus, so the old jQuery version could only ever preview half of each
 * rule. Plain DOM APIs, no jQuery.
 *
 * Settings that change markup (the logo, the header display mode and the
 * sidebar layout) use the refresh transport instead -- reproducing the
 * template in JavaScript drifted out of step with it.
 */
( function ( api ) {
	'use strict';

	var STYLE_ID = 'travelify-customizer-preview';

	// mod name -> function returning the CSS for that mod's current value.
	var rules = {
		travelify_link_color: function ( to ) {
			return 'a { color: ' + to + '; }';
		},
		travelify_link_hover_color: function ( to ) {
			return 'a:focus, a:active, a:hover, .tags a:hover, .tags a:focus,' +
				'.custom-gallery-title a, .widget-title a,' +
				'#content ul a:hover, #content ul a:focus, #content ol a:hover, #content ol a:focus,' +
				'.widget ul li a:hover, .widget ul li a:focus,' +
				'.entry-title a:hover, .entry-title a:focus,' +
				'.entry-meta a:hover, .entry-meta a:focus,' +
				'#site-generator .copyright a:hover, #site-generator .copyright a:focus { color: ' + to + '; }';
		},
		travelify_logo_color: function ( to ) {
			return '#site-title a { color: ' + to + '; }';
		},
		travelify_logo_hover_color: function ( to ) {
			return '#site-title a:hover, #site-title a:focus { color: ' + to + '; }';
		},
		travelify_wrapper_color: function ( to ) {
			return '.wrapper { background: ' + to + '; }';
		},
		travelify_social_color: function ( to ) {
			return '.social-icons ul li a { color: ' + to + '; }';
		},
		travelify_menu_color: function ( to ) {
			return '#main-nav { background: ' + to + '; border-color: ' + to + '; }' +
				'#main-nav ul li ul, body { border-color: ' + to + '; }';
		},
		travelify_menu_hover_color: function ( to ) {
			return '#main-nav a:hover, #main-nav a:focus,' +
				'#main-nav ul li.current-menu-item a, #main-nav ul li.current_page_ancestor a,' +
				'#main-nav ul li.current-menu-ancestor a, #main-nav ul li.current_page_item a,' +
				'#main-nav ul li:hover > a, #main-nav ul li:focus-within > a,' +
				'#main-nav li:hover > a, #main-nav li:focus-within > a,' +
				'#main-nav ul ul :hover > a, #main-nav ul ul :focus-within > a { background: ' + to + '; }' +
				'#main-nav ul li ul li a:hover, #main-nav ul li ul li a:focus,' +
				'#main-nav ul li ul li:hover > a, #main-nav ul li ul li:focus-within > a { color: ' + to + '; }';
		},
		travelify_menu_item_color: function ( to ) {
			return '#main-nav a, #main-nav a:hover, #main-nav a:focus,' +
				'#main-nav ul li.current-menu-item a, #main-nav ul li.current_page_ancestor a,' +
				'#main-nav ul li.current-menu-ancestor a, #main-nav ul li.current_page_item a,' +
				'#main-nav ul li:hover > a, #main-nav ul li:focus-within > a { color: ' + to + '; }';
		},
		travelify_content_bg_color: function ( to ) {
			return '.widget, article { background: ' + to + '; }';
		},
		travelify_header_color: function ( to ) {
			return '.entry-title, .entry-title a, .entry-title a:focus, h1, h2, h3, h4, h5, h6, .widget-title { color: ' + to + '; }';
		},
		travelify_entry_color: function ( to ) {
			return '.entry-content { color: ' + to + '; }';
		},
		travelify_element_color: function ( to ) {
			return 'input[type="reset"], input[type="button"], input[type="submit"],' +
				'.entry-meta-bar .readmore, #controllers a:hover, #controllers a.active,' +
				'.pagination span, .pagination a:hover span, .pagination a:focus span,' +
				'.wp-pagenavi .current, .wp-pagenavi a:hover, .wp-pagenavi a:focus {' +
				'background: ' + to + '; border-color: ' + to + ' !important; }' +
				'::selection, .back-to-top:focus-within a { background: ' + to + '; }' +
				'blockquote { border-color: ' + to + '; }';
		},
		travelify_element_hover_color: function ( to ) {
			return 'input[type="reset"]:hover, input[type="reset"]:focus,' +
				'input[type="button"]:hover, input[type="button"]:focus,' +
				'input[type="submit"]:hover, input[type="submit"]:focus,' +
				'input[type="reset"]:active, input[type="button"]:active, input[type="submit"]:active,' +
				'.entry-meta-bar .readmore:hover, .entry-meta-bar .readmore:focus, .entry-meta-bar .readmore:active,' +
				'ul.default-wp-page li a:hover, ul.default-wp-page li a:focus, ul.default-wp-page li a:active {' +
				'background: ' + to + '; border-color: ' + to + '; }';
		}
	};

	var values = {};

	function styleElement() {
		var style = document.getElementById( STYLE_ID );

		if ( ! style ) {
			style = document.createElement( 'style' );
			style.id = STYLE_ID;
			document.head.appendChild( style );
		}

		return style;
	}

	function render() {
		var css = '';

		Object.keys( values ).forEach( function ( name ) {
			if ( values[ name ] ) {
				css += rules[ name ]( values[ name ] ) + '\n';
			}
		} );

		styleElement().textContent = css;
	}

	Object.keys( rules ).forEach( function ( name ) {
		api( name, function ( setting ) {
			setting.bind( function ( to ) {
				values[ name ] = to;
				render();
			} );
		} );
	} );

	// Site title and description.
	api( 'blogname', function ( value ) {
		value.bind( function ( to ) {
			var target = document.querySelector( '#site-title a' );

			if ( target && ! target.querySelector( 'img' ) ) {
				target.textContent = to;
			}
		} );
	} );

	api( 'blogdescription', function ( value ) {
		value.bind( function ( to ) {
			var target = document.getElementById( 'site-description' );

			if ( target ) {
				target.textContent = to;
			}
		} );
	} );
}( wp.customize ) );
