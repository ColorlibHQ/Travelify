=== Travelify ===

Contributors: colorlib
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 3.1.2
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: blog, e-commerce, photography, two-columns, left-sidebar, right-sidebar, full-width-template, custom-background, custom-colors, custom-header, custom-logo, custom-menu, editor-style, featured-images, footer-widgets, sticky-post, theme-options, threaded-comments, translation-ready, rtl-language-support, block-styles, wide-blocks

A clean, responsive travel and blogging theme with a featured post slider, flexible sidebars and WooCommerce support.

== Description ==

Travelify is a clean, responsive travel and blogging theme that looks good on any screen. It ships a full-width featured post slider, a widgetised left or right sidebar you can set globally or per post, footer widgets, social icons and Customizer options for colours, header and footer with live preview.

The theme's own JavaScript runs without jQuery, the Ubuntu webfont is bundled rather than fetched from a third party, and the front end is built on HTML5 with block editor styles. Travelify is WooCommerce ready, SEO friendly, translation ready and comes with nineteen bundled translations. It also plays nicely with Breadcrumb NavXT, WP-PageNavi and Contact Form 7.

== Installation ==

1. In your WordPress dashboard go to Appearance > Themes and click "Add New".
2. Search for "Travelify", then install and activate it.
3. Go to Appearance > Customize to set the logo, colours, layout and featured slider.

To install manually, download the zip, unzip it and upload the `travelify` folder to `wp-content/themes/`, then activate the theme under Appearance > Themes.

== Frequently Asked Questions ==

= How do I set up the featured slider? =

Give each post or page you want to feature a featured image, then add its post ID under Appearance > Customize > Slider Options. Post IDs are shown in the last column of the All Posts table. Posts without a featured image are skipped. The recommended image size is 1018x460 pixels.

= How do I add my logo? =

Upload it under Appearance > Customize > Site Identity, then set Header Options > Show to "Header Logo Only".

= Can I use a different sidebar layout on one post? =

Yes. Each post and page has a "Select layout" box in the editor that overrides the global setting from Appearance > Customize > Layout Options.

== Copyright ==

Travelify WordPress Theme, Copyright 2013-2026 Colorlib
Travelify is distributed under the terms of the GNU GPL v2 or later.

This theme is based on the Attitude WordPress theme by Theme Horse,
https://wordpress.org/themes/attitude, licensed under the GPL v2 or later.

Bundled resources:

* Ubuntu font, Copyright 2010-2011 Canonical Ltd
  Licensed under the Ubuntu Font Licence 1.0
  https://ubuntu.com/legal/font-licence

* Genericons Neue, Copyright Automattic
  Licensed under the GPL
  https://github.com/Automattic/genericons-neue

* Images bundled with the theme (screenshot.jpg) were created by the theme
  author and are licensed under the GPL.

== Changelog ==

= 3.1.1 =
* Fixed the front page breaking where an older copy of the theme's stylesheet is still being served -- by a child theme carrying its own copy, or by a minify, cache or CDN layer holding a cached one. The slider and the mobile menu were relying on CSS rules that only exist in 3.1.0, so with an out-of-date stylesheet every slide rendered at full height down the page and the menu button did nothing. Both now set the layout they depend on from JavaScript, as the slider did before 3.1.0. If you saw this, clearing your minify or page cache will also fix it on 3.1.0
* Corrected the 3.1.0 changelog: the pre-3.1.0 mobile menu was not missing. TinyNav shipped bundled inside the minified functions.min.js and did build a drop-down; the standalone tinynav.js in library/js was unused, which is what led to the mistaken claim. The 3.1.0 menu is still a replacement for that drop-down, not a first one

= 3.1.0 =
* Replaced the mobile navigation. Below 768px the old theme swapped the menu for a TinyNav drop-down with no sub-menu structure and no accessible semantics; there is now a real toggle button, expandable sub-menus, Escape to close and a no-JavaScript fallback
* Security: the per-post layout box saved whatever was submitted without validating it, and the FeedBurner redirect passed a stored option straight to header(), where a value saved before the option was sanitised could inject response headers. Both are now validated
* Security: colours are re-validated when they are printed, so a theme mod saved by an older version cannot break out of the style block, and the remaining Customizer sanitizers coerce to the types their settings actually hold
* Security: escaped output that was being printed raw, including the header logo URL, the page title and the Customizer control labels
* The theme's JavaScript no longer uses jQuery. jQuery Cycle, TinyNav, cloneya, jQuery UI sortable and html5shiv are all gone; the slider, back to top and Customizer slide repeater are plain DOM code and load in the footer
* The featured slider honours prefers-reduced-motion, pauses while the tab is hidden, has a keyboard- and screen-reader-addressable pager, and skips posts with no featured image instead of cycling through a blank pane
* The Ubuntu font is bundled with the theme instead of being fetched from Google Fonts on every page load, which keeps visitor IP addresses off a third-party server
* The logo now uses WordPress' own custom logo, so it gets srcset, cropping and a live preview; a logo saved in the old theme option is migrated automatically
* Added block editor support: block styles, wide and full alignment, responsive embeds, custom line height, spacing and units, and an editor stylesheet that matches the front end instead of importing the whole layout
* Added WooCommerce product gallery zoom, lightbox and slider support
* Rebuilt the Customizer control styling. The slide rows laid out with inline-block, so a row put its label beside or above the field depending on which buttons it happened to show, and the edit, add and remove buttons landed at different sizes and positions on every row. Rows are now a grid: fields and buttons line up in fixed columns whether or not a row shows add or remove. The layout picker's thumbnails are equal-sized cards with the selection outlined, the homepage category list is tall enough to use, and the Important Links section header is no longer white on bright green
* Fixed the Customizer's Important Links: two were plain http, "Rate this Theme" was listed twice, and three pointed at URLs that only work via a redirect -- the support forum moved to colorlibsupport.com, the wordpress.org review page uses a layout retired in 2015, and twitter.com no longer answers at all. Every link is now https and resolves directly
* Removed the leftover Google+ social icon styling. The network shut down in 2019 and the theme stopped offering it as an option, but the CSS for the icon was still shipping
* Fixed the post title appearing inside the post meta bar next to the author and date. the_title_attribute() was being called with the old the_title() argument list, so instead of returning the title for the link's title attribute it printed it to the page
* Accessibility: the greys used for comment meta, reply links, footer credits and form fields were darkened to #727272 so they clear WCAG AA contrast; the theme's signature green is unchanged
* Accessibility: keyboard focus is visible again. A blanket `:focus { outline: 0 }` had removed the browser's focus ring, leaving elements that already carry the focus background -- the current menu item, for one -- with no indicator at all
* Accessibility: pinch zoom is no longer blocked, each view has exactly one h1 in the right place, author, date and post-type archives finally get a heading, and the menu and slider controls are real buttons with proper labels and focus styles
* Featured images and post thumbnails now serve the cropped sizes the theme registers; it had been falling back to the full-size upload
* Colour changes in the Customizer preview now cover hover and focus states as well
* Verified on WordPress 7.0 and PHP 8.5 with no notices; PHP compatibility checked from 7.4 to 8.5
* Removed the obsolete Grunt toolchain and the unused TGM Plugin Activation library, and optimised the screenshot from 1.1 MB to 347 KB

= 3.0.9 =
* Improved browser compatibility detection
* Removed outdated Internet Explorer detection code
* Enhanced HTML5 shim loading for better performance and compatibility with modern browsers
* Improved code for better PHP 8.x and WordPress 6.8 compatibility

= 3.0.8 =
* WP.org review
* Fixed extra closing bracket in style.css

= 3.0.7 =
* WP.org review

= 3.0.6 =
* Added wp_body_open
* Added unminified scripts and styles

= 3.0.5 =
* Improved accessibility with keyboard navigation

= 3.0.4 =
* Small bug fixes and improvements

= 3.0.3 =
* Added TGMPA

= 3.0.2 =
* Fixed problems with top navigation on mobile

= 3.0.1 =
* Prepared theme for WordPress 4.4
* Removed deprecated wp_title fallback

= 3.0 =
* Removed Options Framework in favour of the WordPress Customizer. This update might break child themes
* Other code cleanup
