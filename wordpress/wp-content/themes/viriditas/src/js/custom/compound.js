jQuery(document).ready(function($) {
	if($('.filter-compound').length) {
		$('.error-info').show();
		var pa=getParameterByName('pa');
		var pb=getParameterByName('pb');
		$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
		$('section[role="actions"]').fetchActions('body_system',pb,'pa',pa);
		$('.compound-content .product-list').showCompound();
		$('#herb-size').allowNumberOnly();
	}
});