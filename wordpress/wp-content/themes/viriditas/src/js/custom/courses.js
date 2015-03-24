jQuery(document).ready(function($) {
	$('.sidebar li a').click(function(){
		var scroll = $(this).attr('rel');
		var padding=$('section[role="content"]').css('padding-top');
		var location =$("#"+scroll).offset().top-parseInt(padding, 10);
		  $('html, body').animate({
			scrollTop: location
		}, 500);
		return false;
	});
});