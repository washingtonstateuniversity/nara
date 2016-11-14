// Sourced from https://codepen.io/BertoMejia/pen/gpXgZo and
// http://kristerkari.github.io/adventures-in-webkit-land/blog/2013/08/30/fixing-a-parallax-scrolling-website-to-run-in-60-fps/

( function( $, window, document ) {

	"use strict";

	var parallaxImages = [];

	$( ".parallax-image" ).each( function() {
		var parallaxImage = {};

		parallaxImage.element = $( this );
		parallaxImage.speed = parallaxImage.element.data( "speed" );
		parallaxImage.from = parallaxImage.element.data( "animate-from" );
		parallaxImage.axis = ( "left" === parallaxImage.from || "right" === parallaxImage.from ) ? "X" : "Y";

		parallaxImages.push( parallaxImage );

		parallaxImage.element.css( parallaxImage.from, parallaxImage.element.data( "offset" ) + "px" );
	} );

	$( document ).ready( function() {
		$( window ).on( "scroll", function() {
			window.requestAnimationFrame( animateImages );
		} );
	} );

	function animateImages() {
		$.each( parallaxImages, function( index, parallaxImage ) {
			var	image = parallaxImage.element,
				axis = parallaxImage.axis,
				operator = ( "right" === parallaxImage.from ) ? "-" : "",
				value = $( window ).scrollTop() * parallaxImage.speed;

			if ( inViewport( image ) ) {
				$( image ).css(
					"transform",
					"translate" + axis + "(" + operator + value + "px)"
				);
			}
		} );
	}

	/**
	 * Check if the element to be animated is in the viewport.
	 */
	function inViewport( element ) {
		if ( element instanceof jQuery ) {
			element = element[ 0 ];
		}

		var rect = element.getBoundingClientRect();

		return rect.bottom > 0 &&
			rect.right > 0 &&
			rect.left < ( window.innerWidth || document.documentElement.clientWidth ) &&
			rect.top < ( window.innerHeight || document.documentElement.clientHeight );
	}
}( jQuery, window, document ) );

//# sourceMappingURL=home-parallax.js.map