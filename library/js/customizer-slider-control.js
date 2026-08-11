/**
 * Travelify featured-slider Customizer control.
 *
 * Add, remove and reorder slides, keeping the hidden JSON field in step.
 * Plain DOM APIs -- replaces the jQuery cloneya / jQuery UI sortable pair.
 *
 * Everything is delegated from the document: the Customizer renders control
 * content lazily, so the list often does not exist yet at DOMContentLoaded.
 */
( function () {
	'use strict';

	var LIST = '.featured-slider-sortable';

	function listFor( node ) {
		return node ? node.closest( LIST ) : null;
	}

	function fieldFor( list ) {
		var control = list.closest( '.customize-control' ) || document;
		return control.querySelector( '#featured_slider' ) || document.getElementById( 'featured_slider' );
	}

	/**
	 * Renumber the rows and write the slide IDs back to the hidden input.
	 *
	 * The stored array is 1-indexed; travelify_sanitize_slider() rebuilds it
	 * that way on save, so match it here.
	 *
	 * @param {Element}  list   The slide list.
	 * @param {boolean}  notify Whether to tell the Customizer the value changed.
	 */
	function sync( list, notify ) {
		var field = fieldFor( list );

		if ( ! field ) {
			return;
		}

		var data = [];

		Array.prototype.forEach.call( list.querySelectorAll( 'li' ), function ( row, index ) {
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

		if ( notify ) {
			// The Customizer only notices a programmatic value change if we say so.
			field.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		}
	}

	function prepare( list ) {
		if ( list.dataset.travelifyReady ) {
			return;
		}

		list.dataset.travelifyReady = '1';

		Array.prototype.forEach.call( list.querySelectorAll( 'li' ), function ( row ) {
			row.draggable = true;
		} );

		/*
		 * The Customizer links the hidden input to the setting, and its value is
		 * an object, so the field starts out reading "[object Object]". Rewrite
		 * it from the rows -- without notifying, or every Customizer load would
		 * open with unsaved changes.
		 */
		sync( list, false );
	}

	function prepareAll() {
		Array.prototype.forEach.call( document.querySelectorAll( LIST ), prepare );
	}

	document.addEventListener( 'click', function ( event ) {
		var list = listFor( event.target );

		if ( ! list ) {
			return;
		}

		var clone  = event.target.closest( '.clone' );
		var remove = event.target.closest( '.delete' );

		if ( clone ) {
			event.preventDefault();

			var source = clone.closest( 'li' );
			var copy   = source.cloneNode( true );
			var input  = copy.querySelector( 'input' );

			if ( input ) {
				input.value = '';
			}

			copy.draggable = true;
			source.parentNode.insertBefore( copy, source.nextSibling );
			sync( list, true );

			if ( input ) {
				input.focus();
			}

			return;
		}

		if ( remove ) {
			event.preventDefault();

			// Always leave one row behind, so the control never empties out.
			if ( list.querySelectorAll( 'li' ).length > 1 ) {
				remove.closest( 'li' ).remove();
				sync( list, true );
			}
		}
	} );

	document.addEventListener( 'input', function ( event ) {
		if ( ! event.target.matches( '.featured_post_slider' ) ) {
			return;
		}

		var list = listFor( event.target );

		if ( list ) {
			sync( list, true );
		}
	} );

	// Reordering: drag a slide row.
	var dragged = null;

	document.addEventListener( 'dragstart', function ( event ) {
		var row = event.target.closest ? event.target.closest( LIST + ' li' ) : null;

		if ( ! row ) {
			return;
		}

		dragged = row;
		event.dataTransfer.effectAllowed = 'move';
		// Firefox will not start a drag without data on the transfer.
		event.dataTransfer.setData( 'text/plain', '' );
		row.classList.add( 'is-dragging' );
	} );

	document.addEventListener( 'dragover', function ( event ) {
		if ( ! dragged ) {
			return;
		}

		var target = event.target.closest ? event.target.closest( LIST + ' li' ) : null;

		if ( ! target || target === dragged || target.parentNode !== dragged.parentNode ) {
			return;
		}

		event.preventDefault();

		var box   = target.getBoundingClientRect();
		var after = ( event.clientY - box.top ) > ( box.height / 2 );

		target.parentNode.insertBefore( dragged, after ? target.nextSibling : target );
	} );

	document.addEventListener( 'drop', function ( event ) {
		if ( dragged ) {
			event.preventDefault();
		}
	} );

	document.addEventListener( 'dragend', function () {
		if ( ! dragged ) {
			return;
		}

		var list = listFor( dragged );

		dragged.classList.remove( 'is-dragging' );
		dragged = null;

		if ( list ) {
			sync( list, true );
		}
	} );

	// The control may be rendered long after this script runs.
	if ( window.MutationObserver ) {
		new MutationObserver( prepareAll ).observe( document.documentElement, { childList: true, subtree: true } );
	}

	if ( document.readyState !== 'loading' ) {
		prepareAll();
	} else {
		document.addEventListener( 'DOMContentLoaded', prepareAll );
	}
}() );
