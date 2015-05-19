jQuery(document).ready(function($) {
	$('#edit-formula').click(function(){
		var container=$(this).attr('id');
		$('body').addClass('open-popup');
	});
	$('.popup-overlay').click(function(){
		$('body').removeClass('open-popup');
	});
});