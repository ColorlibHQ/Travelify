/**
 * Travelify theme behaviour.
 *
 * Plain DOM APIs, no jQuery. Loaded in the footer.
 */
( function () {
	'use strict';

	function prefersReducedMotion() {
		return window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	}

	/**
	 * Back to top.
	 *
	 * Fades in past 1000px, as the jQuery version did, and scrolls to the top
	 * of the document when activated.
	 */
	function initBackToTop() {
		var widget = document.querySelector( '.back-to-top' );

		if ( ! widget ) {
			return;
		}

		var link = widget.querySelector( 'a' );

		widget.classList.add( 'back-to-top--ready' );

		var visible = false;
		var ticking = false;

		function update() {
			var shouldShow = ( window.pageYOffset || document.documentElement.scrollTop ) > 1000;

			if ( shouldShow !== visible ) {
				visible = shouldShow;
				widget.classList.toggle( 'is-visible', visible );
			}

			ticking = false;
		}

		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				window.requestAnimationFrame( update );
				ticking = true;
			}
		}, { passive: true } );

		update();

		if ( ! link ) {
			return;
		}

		link.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			window.scrollTo( {
				top: 0,
				behavior: prefersReducedMotion() ? 'auto' : 'smooth'
			} );

			// Send focus somewhere sensible rather than leaving it at the bottom.
			var target = document.getElementById( 'branding' );

			if ( target ) {
				target.setAttribute( 'tabindex', '-1' );
				target.focus( { preventScroll: true } );
			}
		} );
	}

	/**
	 * Mobile navigation.
	 *
	 * Replaces the <select> that TinyNav built below 768px. TinyNav shipped
	 * bundled inside the old functions.min.js -- the standalone tinynav.js in
	 * library/js was dead code, which is easy to misread as the menu being
	 * absent. It worked; it was just a <select> with no sub-menu structure and
	 * no accessible semantics.
	 */
	function initMobileMenu() {
		var nav = document.getElementById( 'main-nav' );

		if ( ! nav ) {
			return;
		}

		var menu = nav.querySelector( 'ul' );

		if ( ! menu ) {
			return;
		}

		var strings = window.travelifyScreenReaderText || {};

		var toggle = document.createElement( 'button' );
		toggle.className = 'menu-toggle';
		toggle.type = 'button';
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.setAttribute( 'aria-controls', menu.id || 'travelify-primary-menu' );
		var label = document.createElement( 'span' );
		label.className = 'menu-toggle-text';
		label.textContent = strings.menu || 'Menu';

		var icon = document.createElement( 'span' );
		icon.className = 'menu-toggle-icon';
		icon.setAttribute( 'aria-hidden', 'true' );

		toggle.appendChild( icon );
		toggle.appendChild( label );

		if ( ! menu.id ) {
			menu.id = 'travelify-primary-menu';
		}

		menu.parentNode.insertBefore( toggle, menu );

		/*
		 * Whether the menu is on screen is decided here, not in the stylesheet.
		 *
		 * The CSS rules that hide the list and reveal it again on .is-open only
		 * exist in this version of style.css. Where that file is out of step --
		 * a child theme carrying its own copy, a minify or CDN layer serving a
		 * cached one -- the class still toggles but nothing moves, and the
		 * button is dead. Setting display from here means the menu opens
		 * regardless of which stylesheet arrived.
		 *
		 * MOBILE_BREAKPOINT mirrors the max-width in style.css; keep them equal.
		 */
		var MOBILE_BREAKPOINT = 767;

		function isMobile() {
			return window.innerWidth <= MOBILE_BREAKPOINT;
		}

		function applyMenuState( open ) {
			if ( isMobile() ) {
				toggle.style.display = 'flex';
				menu.style.display = open ? 'block' : 'none';
			} else {
				// Desktop: hand both back to the stylesheet.
				toggle.style.display = 'none';
				menu.style.display = '';
			}
		}

		function setOpen( open ) {
			nav.classList.toggle( 'is-open', open );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			applyMenuState( open );
		}

		setOpen( false );

		toggle.addEventListener( 'click', function () {
			setOpen( ! nav.classList.contains( 'is-open' ) );
		} );

		// Sub-menu toggles: hover cannot open a dropdown on a touch screen.
		Array.prototype.forEach.call( menu.querySelectorAll( 'li' ), function ( item ) {
			var submenu = item.querySelector( 'ul' );

			if ( ! submenu ) {
				return;
			}

			item.classList.add( 'has-submenu' );

			var link = item.querySelector( 'a' );
			var button = document.createElement( 'button' );

			button.className = 'submenu-toggle';
			button.type = 'button';
			button.setAttribute( 'aria-expanded', 'false' );
			button.setAttribute(
				'aria-label',
				( strings.expand || 'Open sub-menu of' ) + ' ' + ( link ? link.textContent.trim() : '' )
			);
			var chevron = document.createElement( 'span' );
			chevron.setAttribute( 'aria-hidden', 'true' );
			button.appendChild( chevron );

			button.addEventListener( 'click', function () {
				var open = item.classList.toggle( 'submenu-open' );
				button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );

				// As above: do not depend on the stylesheet to reveal it.
				if ( isMobile() ) {
					submenu.style.display = open ? 'block' : 'none';
				}
				button.setAttribute(
					'aria-label',
					( open ? ( strings.collapse || 'Close sub-menu of' ) : ( strings.expand || 'Open sub-menu of' ) ) + ' ' + ( link ? link.textContent.trim() : '' )
				);
			} );

			if ( link ) {
				link.parentNode.insertBefore( button, link.nextSibling );
			} else {
				item.insertBefore( button, item.firstChild );
			}
		} );

		// Escape closes whatever is open and returns focus to the toggle.
		nav.addEventListener( 'keydown', function ( event ) {
			if ( event.key !== 'Escape' ) {
				return;
			}

			if ( nav.classList.contains( 'is-open' ) ) {
				setOpen( false );
				toggle.focus();
			}
		} );

		// Collapse when the layout goes back to the desktop menu.
		if ( window.matchMedia ) {
			var desktop = window.matchMedia( '(min-width: 768px)' );

			var reset = function () {
				setOpen( false );

				Array.prototype.forEach.call( menu.querySelectorAll( '.has-submenu' ), function ( item ) {
					item.classList.remove( 'submenu-open' );

					var button = item.querySelector( '.submenu-toggle' );
					var sub = item.querySelector( 'ul' );

					if ( button ) {
						button.setAttribute( 'aria-expanded', 'false' );
					}

					// Clear the inline display so the stylesheet governs again.
					if ( sub ) {
						sub.style.display = '';
					}
				} );
			};

			if ( desktop.addEventListener ) {
				desktop.addEventListener( 'change', reset );
			} else if ( desktop.addListener ) {
				desktop.addListener( reset );
			}

			window.addEventListener( 'resize', function () {
				applyMenuState( nav.classList.contains( 'is-open' ) );
			} );
		}
	}

	/**
	 * Keyboard support for the desktop dropdowns.
	 *
	 * The stylesheet opens sub-menus on :focus-within, which covers tabbing.
	 * This adds the class as well so older engines behave the same way.
	 */
	function initDesktopMenuFocus() {
		var nav = document.getElementById( 'main-nav' );

		if ( ! nav ) {
			return;
		}

		Array.prototype.forEach.call( nav.querySelectorAll( 'a' ), function ( link ) {
			link.addEventListener( 'focus', function () {
				var item = link.parentNode;

				while ( item && item !== nav ) {
					if ( item.tagName === 'LI' ) {
						item.classList.add( 'focus' );
					}
					item = item.parentNode;
				}
			} );

			link.addEventListener( 'blur', function () {
				var item = link.parentNode;

				while ( item && item !== nav ) {
					if ( item.tagName === 'LI' ) {
						item.classList.remove( 'focus' );
					}
					item = item.parentNode;
				}
			} );
		} );
	}

	function init() {
		initBackToTop();
		initMobileMenu();
		initDesktopMenuFocus();
	}

	if ( document.readyState !== 'loading' ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}
}() );
