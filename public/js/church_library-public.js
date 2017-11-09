(function ( $ ) {
	'use strict';

	$( function () {
		window.formreset = function(){
			$('#search #keyword').val('');
			$('form#search').submit();
		}
		$('.pagination').addClass('.pagination-sm');
	} );

	$( window ).load( function () {

	} );

})( jQuery );
