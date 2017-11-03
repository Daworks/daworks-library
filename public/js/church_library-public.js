(function ( $ ) {
	'use strict';

	$( function () {
		window.formreset = function(){
			$('#search #keyword').val('');
			$('form#search').submit();
		}
	} );

	$( window ).load( function () {

	} );

})( jQuery );
