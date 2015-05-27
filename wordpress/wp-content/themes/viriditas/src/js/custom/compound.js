jQuery(document).ready(function($) {
	if($('.filter-compound').length) {
		$('.error-info').show();
		var pa=getParameterByName('pa');
		var pb=getParameterByName('pb');
		$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
		$('section[role="actions"]').fetchActions('body_system',pb,'pa',pa);
		$('.compound-content .product-list').showCompound();
		$('#herb-size').allowNumberOnly();
		// $('.compound-product').change(function(){
			// var c = this.checked ? '#f00' : '#09f';
			// alert(this.checked);
		// });
		//$('.compound-product').unbind('change');
		
		/*$(".compound-product").bind("click", function(){
			var id = parseInt($(this).val(), 10);
			if($(this).is(":checked")) {
				// checkbox is checked -> do something
				alert(1);
			} else {
				alert(0);
				// checkbox is not checked -> do something different
			}
		});*/
	}
});