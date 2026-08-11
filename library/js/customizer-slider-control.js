/**
 * Travelify featured-slider Customizer control.
 *
 * Add, remove and reorder slides, keeping the hidden JSON field in step.
 * Plain DOM APIs -- replaces the jQuery cloneya / jQuery UI sortable pair.
 */
( function () {
	'use strict';

	function init() {
		var list  = document.querySelector( '.featured-slider-sortable' );
		var field = document.getElementById( 'featured_slider' );

		if ( ! list || ! field ) {
			return;
		}

		/**
		 * Renumber the rows and write the slide IDs back to the hidden input.
		 *
		 * The stored array is 1-indexed; travelify_sanitize_slider() rebuilds it
		 * that way on save, so match it here or the Customizer sees a change on
		 * every load.
		 */
		function sync() {
			var rows = list.querySelectorAll( 'li' );
			var data = [];

			Array.prototype.forEach.call( rows, function ( row, index ) {
				var position = index + 1;
				var count    = row.querySelector( '.count' );
				var input    = row.querySelector( 'input' );
				var edit     = row.querySelector( '.slider_edit' );

				if ( count ) {
					count.textContent = position;
				}

				row.id = 'customize-control-travelify_theme_options-featured_post_slider-' + position;

				if ( input ) {
					input.name = 'travelify_theme_options[featured_post_slider][' + position + ']';
					data[ position ] = input.value;

					if ( edit ) {
						edit.href = edit.href.replace( /post=\d*/, 'post=' + ( parseInt( input.value, 10 ) || 0 ) );
					}
				}
			} );

			field.value = JSON.stringify( data );

			// The Customizer only notices a programmatic value change if we say so.
			field.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		}

		list.addEventListener( 'click', function ( event ) {
			var clone = event.target.closest( '.clone' );
			var remove = event.target.closest( '.delete' );

			if ( clone ) {
				event.preventDefault();

				var source = clone.closest( 'li' );
				var copy   = source.cloneNode( true );
				var input  = copy.querySelector( 'input' );

				if ( input ) {
					input.value = '';
				}

				source.parentNode.insertBefore( copy, source.nextSibling );
				sync();

				if ( input ) {
					input.focus();
				}

				return;
			}

			if ( remove ) {
				event.preventDefault();

				var row = remove.closest( 'li' );

				// Always leave one row behind, so the control never empties out.
				if ( list.querySelectorAll( 'li' ).length > 1 ) {
					row.parentNode.removeChild( row );
					sync();
				}
			}
		} );

		list.addEventListener( 'input', function ( event ) {
			if ( event.target.matches( '.featured_post_slider' ) ) {
				sync();
			}
		} );

		// Reordering: drag the slide label.
		var dragged = null;

		Array.prototype.forEach.call( list.querySelectorAll( 'li' ), makeDraggable );

		function makeDraggable( row ) {
			row.draggable = true;
		}

		list.addEventListener( 'dragstart', function ( event ) {
			dragged = event.target.closest( 'li' );

			if ( dragged ) {
				event.dataTransfer.effectAllowed = 'move';
				// Firefox will not start a drag without data on the transfer.
				event.dataTransfer.setData( 'text/plain', '' );
				dragged.classList.add( 'is-dragging' );
			}
		} );

		list.addEventListener( 'dragover', function ( event ) {
			if ( ! dragged ) {
				return;
			}

			event.preventDefault();

			var target = event.target.closest( 'li' );

			if ( ! target || target === dragged ) {
				return;
			}

			var box = target.getBoundingClientRect();
			var after = ( event.clientY - box.top ) > ( box.height / 2 );

			target.parentNode.insertBefore( dragged, after ? target.nextSibling : target );
		} );

		list.addEventListener( 'drop', function ( event ) {
			event.preventDefault();
		} );

		list.addEventListener( 'dragend', function () {
			if ( dragged ) {
				dragged.classList.remove( 'is-dragging' );
				dragged = null;
				sync();
			}
		} );

		// Newly cloned rows need to be draggable too.
		var observer = new MutationObserver( function ( mutations ) {
			mutations.forEach( function ( mutation ) {
				Array.prototype.forEach.call( mutation.addedNodes, function ( node ) {
					if ( node.nodeType === 1 && node.tagName === 'LI' ) {
						makeDraggable( node );
					}
				} );
			} );
		} );

		observer.observe( list, { childList: true } );
	}

	if ( document.readyState !== 'loading' ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}
}() );
