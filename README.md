# [Travelify](https://colorlib.com/wp/themes/travelify/)

## Description

Travelify is a clean, responsive travel and blogging theme that looks good on any screen. It ships a full-width featured post slider, a widgetised left or right sidebar you can set globally or per post, footer widgets, social icons and Customizer options for colours, header and footer with live preview.

The theme's own JavaScript runs without jQuery, the Ubuntu webfont is bundled rather than fetched from a third party, and the front end is built on HTML5 with block editor styles. Travelify is WooCommerce ready, SEO friendly, translation ready and comes with nineteen bundled translations (English, French, German, Hungarian, Italian, Spanish, Dutch, Hebrew, Slovak, Turkish, Swedish, Brazilian Portuguese, Polish, Finnish, Bulgarian, Greek, Persian, Russian and Chinese). It also plays nicely with Breadcrumb NavXT, WP-PageNavi, Contact Form 7 and WPML.

**Requires WordPress 6.0 or later and PHP 7.4 or later. Tested up to WordPress 7.0 and PHP 8.5.**

## More info
More about this theme you can find in the [following link](https://colorlib.com/wp/themes/travelify/).

## Demo
Travelify preview [is available here](https://colorlib.com/travelify/)

## Installation

#### Manual installation:

1. Download the theme using the "Download zip" button on the right
2. Upload the `travelify` folder to the `/wp-content/themes/` directory
3. Activate the theme through the Appearance > Themes menu in WordPress
4. See Appearance > Customize to change theme specific options

#### Automated installation:

1. Go to WordPress dashboard - Appearance - Themes
2. Click "Add New" and search for Travelify
3. Now install and activate this theme

## Customization

This theme has a range of customization options available in the WordPress Customizer under Appearance > Customize. For more information you can read the **[theme documentation](https://colorlib.com/wp/support/travelify/)**.

## License

This theme is based on Attitude WordPress theme by Theme Horse.

The theme is released for free under the terms of the GNU General Public License version 2 and some parts under their respective licenses.
In general words, feel free and encouraged to use, modify and redistribute this theme however you like.
You may remove any copyright references (unless required by third party components) and crediting is not necessary.
The theme is offered free of charge. If someone asked money for it, someone just tricked you.

Unless otherwise specified, all the theme files, scripts and images are licensed under GNU General Public License version 2, see file license.txt.

**The exceptions to this license are as follows:**

* [Ubuntu font](https://ubuntu.com/legal/font-licence) by Canonical Ltd, licensed under the Ubuntu Font Licence 1.0
* Genericons Neue (https://github.com/Automattic/genericons-neue) is licensed under GPL

= Incorporated Code Copyright Attribution =
* Travelify WordPress Theme incorporates code from Attitude WordPress Theme, by Theme Horse
  License: GPLv2 or later License URI: https://www.gnu.org/licenses/gpl-2.0.html

= Images bundled with the theme =
* screenshot.jpg :
	* screenshot.jpg - Created/Taken by theme author
  	Licensed under GPL


## Change Log

**= 3.1.1 =**
* Fixed the front page breaking where an older copy of the theme's stylesheet is still being served -- by a child theme carrying its own copy, or by a minify, cache or CDN layer holding a cached one. The slider and the mobile menu were relying on CSS rules that only exist in 3.1.0, so with an out-of-date stylesheet every slide rendered at full height down the page and the menu button did nothing. Both now set the layout they depend on from JavaScript, as the slider did before 3.1.0. If you saw this, clearing your minify or page cache will also fix it on 3.1.0
* Corrected the 3.1.0 changelog: the pre-3.1.0 mobile menu was not missing. TinyNav shipped bundled inside the minified functions.min.js and did build a drop-down; the standalone tinynav.js in library/js was unused, which is what led to the mistaken claim. The 3.1.0 menu is still a replacement for that drop-down, not a first one

**= 3.1.0 =**
* Replaced the mobile navigation. Below 768px the old theme swapped the menu for a TinyNav drop-down with no sub-menu structure and no accessible semantics; there is now a real toggle button, expandable sub-menus, Escape to close and a no-JavaScript fallback
* Security: the per-post layout box saved whatever was submitted without validating it, and the FeedBurner redirect passed a stored option straight to `header()`, where a value saved before the option was sanitised could inject response headers. Both are now validated
* Security: colours are re-validated when they are printed, so a theme mod saved by an older version cannot break out of the style block, and the remaining Customizer sanitizers coerce to the types their settings actually hold
* Security: escaped output that was being printed raw, including the header logo URL, the page title and the Customizer control labels
* The theme's JavaScript no longer uses jQuery. jQuery Cycle, TinyNav, cloneya, jQuery UI sortable and html5shiv are all gone; the slider, back to top and Customizer slide repeater are plain DOM code and load in the footer
* The featured slider honours `prefers-reduced-motion`, pauses while the tab is hidden, has a keyboard- and screen-reader-addressable pager, and skips posts with no featured image instead of cycling through a blank pane
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
* Accessibility: pinch zoom is no longer blocked, each view has exactly one `h1` in the right place, author, date and post-type archives finally get a heading, and the menu and slider controls are real buttons with proper labels and focus styles
* Featured images and post thumbnails now serve the cropped sizes the theme registers; it had been falling back to the full-size upload
* Colour changes in the Customizer preview now cover hover and focus states as well
* Verified on WordPress 7.0 and PHP 8.5 with no notices; PHP compatibility checked from 7.4 to 8.5
* Removed the obsolete Grunt toolchain and the unused TGM Plugin Activation library, and optimised the screenshot from 1.1 MB to 347 KB

**= 3.0.9 =**
* Improved browser compatibility detection
* Removed outdated Internet Explorer detection code
* Enhanced HTML5 shim loading for better performance and compatibility with modern browsers
* Improved code for better PHP 8.x and WordPress 6.8 compatibility

**= 3.0.8 =**
* WP.org review
* Fixed extra closing bracket (`}`) in style.css 

**= 3.0.7 =**
* WP.org review

**= 3.0.6 =**

* Added wp_body_open
* Added unminified Scripts and styles

**= 3.0.5 =**

* Improved accesibility with keyboard navigation

**= 3.0.4 =**

* Small bug fixes and improvements

**= 3.0.3 =**
* Added TGMPA

**= 3.0.2 =**

* Fixed problems with top navigation on mobile.

**= 3.0.1 =**

* Prepared theme for WordPress 4.4
* Removed depractated wp_title fallback

**= 3.0 =**

* Removed Options Framework in favor to WordPress Customizer. This update might break Child Themes.
* Other code cleanup

**= 2.4.2 =**
* Added Russian translation thanks to Evgeny

**= 2.4.1 =**
* Removed redundant sprintf from travelify_posted_on
* Fixed numerous typos

**= 2.4.0 =**
* Improved markup to pass Schema tests and eliminate all errors in Google Webmaster Tools. Might be useful for SEO.
* Improved RTL support
* Minor code cleanup

**= 2.3.3 =**
- Added GitHub icon that you can set via Theme Options >> Social Links

**= 2.3.2 =**

- Added Greek translation thanks to Ecoengineer

**= 2.3.1 =**
- Added Persian (Farsi) translation thanks to Farzam Parto
- Improved output from WordPress Theme Customizer
- Removed changelog from readme.txt and now this is going to be the only place for changelog.

**= 2.3.0 =**
- Created sanitize callback for WordPress Customizer
- Removed  add_shortcode functionality (plugin territory)
- Escaped URLs where needed
- Added support for title-tag as for WordPress 4.1
- Some minor code cleanup

**= 2.2.2 =**
- Added Bulgarian translation thanks to Kaloyan Dimitrov

**= 2.2.1 =**
- Code cleanup
- Updated Genericons to versions 3.3

**= 2.2.0 =**
- Added full WPML plugin support. We have also made it certified by WPML.
- Added Github friendly readme file (README.md)

**= 2.1.5 =**
- Added Polish translation thanks to Kamil Kuczek
- Added Finnish translation thanks to Antti Vähälummukka
- Fixed WordPress Customizer style for Firefox

**= 2.1 =**
- Added Swedish translation
- Added Brazilian Portuguese translation thanks to Ariel de Souza
- Improved code consistency for Child Theme support.

**= 2.0 =**
- Added 15 color pickers via WordPress Customizer API making this theme fully customizable
- Improved responsiveness on all devices
- Improved footer widget section
- Several CSS improvements and bug fixes. Code cleanup.
- Added WooCommerce 2.1+ support
- Updated translations
- Improved Theme Options
- Custom Footer text
- Updated JavaScript libraries for slider transitions and HTML5

**= 1.5.0 =**
- Updated Spanish translation
- Fixed title issues for single page
- Fixed problems with navigation bar
- Slightly imporved RTL support for navigation and header
- Updated Genericons and changed G+ icon
- Added Turkish language by @anisaspildagi
- Updated Hungarian translation
- Updated editor style
- Changed author URI

**= 1.4.2 =**
- Removed rateme notice

**= 1.4.1 =**
- Tiny single post title tweak

**= 1.4.0 =**
- Added Slovak translation thanks to Lukas Kostensky
- Added Hebrew translation thanks to Nitzan Eini
- Updated translation files
- Improved RTL support
- Added WooCommerce left sidebar support
- Fixed dropdown menu for more than 3 submenus
- Changed theme credits
- Removed active title link from single posts
- Removed custom scripts from header and footer (plugin territory)
- Other tweaks and improvements

**= 1.3.7 =**
- Dutch translation thanks to Sietze Kuiper
- Optimization for Disqus

**= 1.3.7 =**
- Dutch translation thanks to Sietze Kuiper
- Optimization for Disqus

**= 1.3.6 =**
- Fixed problems with custom header scripts

**= 1.3.5 =**
- Added Italian and Spanish translations thanks to Mario

**= 1.3.4 =**
- Fixed custom script output in header
- Minor CSS tweaks

**= 1.3.3 =**
- Added Hungarian translation thanks to Tamás
- Fixed issue with sub-menus.

**= 1.3.2 =**
- Added German translation
- Improved support for IE8.

**= 1.3.1 =**
- Added Chinese translation thanks to Seam Wills
- Added missing translation for French and default language files.
- Fixed meta information when long meta information could overlap post area.
- Fixed problems with cyrillic writing in featured slider.

**= 1.3.0 =**
- Added French translation thanks to Christophe Rossi
- Improved translation process via i18n.php
- Improved responsiveness for No Sidebar layout
- Fixed mobile navigation
- Updated license information
- Added custom header
- Cleaned functions.php file to leave it for most crucial functions.

**= 1.2.1 =**
- Fixed bug with no-sidebar and full-width page templates
- New file added content-nosidebar.php to deal better with no-sidebar pages

**= 1.2.0 =**
- Full WooCommerce eCommerce plugin support
- Added sidebar.php to support WooCommerce plugin
- Updated theme description
- Renamed blog page templates to avoid template conflicts
- Added documentation for blog page templates.
- Added rating button on Theme Options

**= 1.1.2 =**
- Removed webmaster tools from Theme Options
- Other small tweaks in Theme Options

**= 1.1.1 =**
- Updated 404.php file
- Updated default language file
- Updated theme credits link
- Analytics now renamed to Custom Scripts inside Theme Options

**= 1.1 =**
- Improved responsiveness for different devices
- Fixed footer widget area
- Several other minor tweaks and improvements

**= 1.0 =**
- Initial Release
