(function ( $ ) {
	'use strict';

	$( function () {

	} );

	$( window ).load( function () {

	} );

	window.formreset = function(){
		$('#search #keyword').val('');
		$('form#search').submit();
	}

})( jQuery );
