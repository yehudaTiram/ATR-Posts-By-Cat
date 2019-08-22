(function( $ ) {
	'use strict';
	$("#checkAll").change(function () {
		$("input:checkbox").prop('checked', $(this).prop("checked"));
	});


})( jQuery );
