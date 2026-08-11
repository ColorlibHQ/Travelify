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
	 * The stylesheet used to hide the menu below 768px and show a <select> that
	 * TinyNav was supposed to build -- but TinyNav was never enqueued, so the
	 * menu simply vanished. This replaces it with a real toggle button and
	 * expandable sub-menus.
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
		toggle.innerHTML = '<span class="menu-toggle-icon" aria-hidden="true"></span><span class="menu-toggle-text">' + ( strings.menu || 'Menu' ) + '</span>';

		if ( ! menu.id ) {
			menu.id = 'travelify-primary-menu';
		}

		menu.parentNode.insertBefore( toggle, menu );

		toggle.addEventListener( 'click', function () {
			var open = nav.classList.toggle( 'is-open' );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
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
			button.innerHTML = '<span aria-hidden="true"></span>';

			button.addEventListener( 'click', function () {
				var open = item.classList.toggle( 'submenu-open' );
				button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
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
				nav.classList.remove( 'is-open' );
				toggle.setAttribute( 'aria-expanded', 'false' );
				toggle.focus();
			}
		} );

		// Collapse when the layout goes back to the desktop menu.
		if ( window.matchMedia ) {
			var desktop = window.matchMedia( '(min-width: 768px)' );

			var reset = function ( query ) {
				if ( query.matches ) {
					nav.classList.remove( 'is-open' );
					toggle.setAttribute( 'aria-expanded', 'false' );

					Array.prototype.forEach.call( menu.querySelectorAll( '.submenu-open' ), function ( item ) {
						item.classList.remove( 'submenu-open' );

						var button = item.querySelector( '.submenu-toggle' );

						if ( button ) {
							button.setAttribute( 'aria-expanded', 'false' );
						}
					} );
				}
			};

			if ( desktop.addEventListener ) {
				desktop.addEventListener( 'change', reset );
			} else if ( desktop.addListener ) {
				desktop.addListener( reset );
			}
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
