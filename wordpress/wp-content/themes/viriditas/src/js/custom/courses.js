jQuery(document).ready(function($) {
	$('.sidebar li a').click(function(){
		var scroll = $(this).attr('rel');
		var location =$("#"+scroll).offset().top;
		  $('html, body').animate({
			scrollTop: location
		}, 2000);
		return false;
	});
});