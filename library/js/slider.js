/**
 * Travelify featured slider.
 *
 * Replaces jQuery Cycle. Plain DOM APIs, no dependencies.
 *
 * Settings arrive from PHP as window.travelify_slider_value:
 *   transition_effect   one of the effects offered in the Customizer
 *   transition_delay    time a slide is held, in milliseconds
 *   transition_duration length of the transition itself, in milliseconds
 */
( function () {
	'use strict';

	/*
	 * jQuery Cycle shipped far more effects than are worth reimplementing.
	 * Every stored value still resolves to something sensible: the ones with
	 * no CSS equivalent fall back to a cross-fade.
	 */
	var EFFECTS = {
		fade: 'fade',
		wipe: 'wipe',
		cover: 'wipe',
		scrollUp: 'up',
		scrollDown: 'down',
		scrollLeft: 'left',
		scrollRight: 'right',
		blindX: 'blind-x',
		blindY: 'blind-y',
		blindZ: 'fade',
		shuffle: 'left'
	};

	function prefersReducedMotion() {
		return window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	}

	function initSlider( root ) {
		var track = root.querySelector( '.slider-cycle' );

		if ( ! track ) {
			return;
		}

		var slides = Array.prototype.slice.call( track.querySelectorAll( '.slides' ) );

		if ( slides.length === 0 ) {
			return;
		}

		var settings = window.travelify_slider_value || {};
		var effect   = EFFECTS[ settings.transition_effect ] || 'fade';
		var delay    = parseInt( settings.transition_delay, 10 );
		var duration = parseInt( settings.transition_duration, 10 );

		// The Customizer stores seconds; PHP multiplies them up. Zero means "unset".
		delay    = delay > 0 ? delay : 4000;
		duration = duration > 0 ? duration : 1000;

		var reduced = prefersReducedMotion();

		if ( reduced ) {
			duration = 0;
		}

		var current = 0;
		var timer   = null;
		var paused  = false;

		root.classList.add( 'travelify-slider' );
		root.setAttribute( 'data-effect', effect );
		track.style.setProperty( '--travelify-slide-duration', duration + 'ms' );

		/*
		 * Stack the slides from here rather than relying on the stylesheet.
		 *
		 * These four declarations are what makes this a slider at all: without
		 * them every slide renders in normal flow and the page becomes a column
		 * of full-height images. Leaving them to style.css means the component
		 * silently breaks wherever that file is out of step with this one -- a
		 * child theme that copied the parent stylesheet, a minify or CDN layer
		 * serving a cached copy, a custom stylesheet loaded in its place. The
		 * jQuery Cycle version this replaced set them inline for the same
		 * reason.
		 *
		 * Everything cosmetic -- the transitions, the effects, the pager
		 * colours -- stays in the stylesheet, where it can be overridden.
		 */
		track.style.position = 'relative';

		slides.forEach( function ( slide, index ) {
			slide.classList.remove( 'displayblock', 'displaynone' );
			slide.classList.toggle( 'is-active', index === current );
			slide.setAttribute( 'aria-hidden', index === current ? 'false' : 'true' );

			slide.style.position = 'absolute';
			slide.style.top = '0';
			slide.style.left = '0';
			slide.style.width = '100%';
		} );

		/*
		 * Slides are stacked with absolute positioning, so the track needs an
		 * explicit height. Take it from the tallest slide and keep it in step
		 * with viewport changes.
		 */
		function resize() {
			var tallest = 0;

			slides.forEach( function ( slide ) {
				tallest = Math.max( tallest, slide.offsetHeight );
			} );

			if ( tallest > 0 ) {
				track.style.height = tallest + 'px';
			}
		}

		var pager = root.querySelector( '#controllers' );
		var dots  = [];

		if ( pager && slides.length > 1 ) {
			slides.forEach( function ( slide, index ) {
				var dot = document.createElement( 'button' );

				dot.type = 'button';
				dot.className = index === current ? 'active' : '';

				/*
				 * Box model only, for the same reason the slides are positioned
				 * from here: without the stylesheet these are default buttons,
				 * and a slider with forty of them turns into rows of grey
				 * rectangles across the image. Colour and shape stay in CSS so
				 * they remain themeable.
				 */
				dot.style.boxSizing = 'border-box';
				dot.style.width = '10px';
				dot.style.height = '10px';
				dot.style.minWidth = '0';
				dot.style.padding = '0';
				dot.style.fontSize = '0';
				dot.style.lineHeight = '0';
				dot.setAttribute( 'aria-label', ( window.travelifySliderL10n && window.travelifySliderL10n.slide ? window.travelifySliderL10n.slide : 'Slide' ) + ' ' + ( index + 1 ) );
				dot.setAttribute( 'aria-current', index === current ? 'true' : 'false' );

				dot.addEventListener( 'click', function () {
					show( index );
					restart();
				} );

				pager.appendChild( dot );
				dots.push( dot );
			} );
		}

		function show( next ) {
			if ( next === current || next < 0 || next >= slides.length ) {
				return;
			}

			var outgoing = slides[ current ];
			var incoming = slides[ next ];

			// Direction lets the slide effects know which way to travel.
			var forwards = next > current;
			track.classList.toggle( 'is-reversing', ! forwards );

			outgoing.classList.remove( 'is-active' );
			outgoing.classList.add( 'is-leaving' );
			outgoing.setAttribute( 'aria-hidden', 'true' );

			incoming.classList.add( 'is-active' );
			incoming.setAttribute( 'aria-hidden', 'false' );

			window.setTimeout( function () {
				outgoing.classList.remove( 'is-leaving' );
			}, duration );

			dots.forEach( function ( dot, index ) {
				dot.className = index === next ? 'active' : '';
				dot.setAttribute( 'aria-current', index === next ? 'true' : 'false' );
			} );

			current = next;
		}

		function advance() {
			show( ( current + 1 ) % slides.length );
		}

		function start() {
			// A single slide has nowhere to go, and reduced motion means no autoplay.
			if ( timer || slides.length < 2 || reduced ) {
				return;
			}

			timer = window.setInterval( function () {
				if ( ! paused ) {
					advance();
				}
			}, delay );
		}

		function stop() {
			if ( timer ) {
				window.clearInterval( timer );
				timer = null;
			}
		}

		function restart() {
			stop();
			start();
		}

		// pause:1 and pauseOnPagerHover:1 in the old Cycle configuration.
		root.addEventListener( 'mouseenter', function () {
			paused = true;
		} );
		root.addEventListener( 'mouseleave', function () {
			paused = false;
		} );
		root.addEventListener( 'focusin', function () {
			paused = true;
		} );
		root.addEventListener( 'focusout', function () {
			paused = false;
		} );

		// Nothing is gained by cycling a slider nobody is looking at.
		document.addEventListener( 'visibilitychange', function () {
			if ( document.hidden ) {
				stop();
			} else {
				start();
			}
		} );

		resize();
		window.addEventListener( 'resize', resize );
		window.addEventListener( 'load', resize );

		// Featured images change the height once they decode.
		Array.prototype.forEach.call( track.querySelectorAll( 'img' ), function ( img ) {
			if ( ! img.complete ) {
				img.addEventListener( 'load', resize );
			}
		} );

		start();
	}

	function init() {
		Array.prototype.forEach.call( document.querySelectorAll( '.featured-slider' ), initSlider );
	}

	if ( document.readyState !== 'loading' ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}
}() );
