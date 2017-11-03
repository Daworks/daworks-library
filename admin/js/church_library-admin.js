(function ( $ ) {
	'use strict';

	$( function () {

		$('#checkall').click(function(){
			var items = $('table input[type="checkbox"]:not(#checkall)');
			if ( $(this).is(':checked') )
			{
				items.prop('checked', true);
			}
			else
			{
				items.prop('checked', false);
			}
		});

	} );

	$( window ).load( function () {

	} );

	window.checkedValue = function(){
		var chekedItems = $('table input[type="checkbox"]:not(#checkall)'),
				values = [];

		$.each(chekedItems, function(){
			if ( $(this).is(':checked') ) {
				values.push($(this).val());
			}
		});

		if ( values.length === 0 ) {
			alert('삭제할 항목을 하나 선택하세요.');
			return false;
		}

		if ( confirm('항목 삭제를 시작합니다. \n\r삭제되면 복구할 수 없습니다. 삭제할까요?') )
		{
			$.each(values, function(i,v){
				deleteBook(v);
			});
		}

		return false;

	}

	window.deleteBook = function (id)
	{
		var data = {action: 'church_library_destroy', id : id};
		$.post(ajaxurl, data, function(xhr){

			var r = $.parseJSON(xhr);

			if ( r.status == 'success' ) {
				$('#book_'+id).remove();

				if ( $('table tbody tr').length === 0 ) {
					location.href = '/wp-admin/admin.php?page=church-library';
				}
			}
			else {
				alert(r.msg);
				return false;
			}
		});
	}

})( jQuery );
