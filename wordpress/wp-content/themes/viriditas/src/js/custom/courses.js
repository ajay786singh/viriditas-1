jQuery(document).ready(function($) {
	$('.post-list li a').click(function(){
		var scroll = $(this).attr('rel');
		var location =$("#"+scroll).offset().top;
		  $('html, body').animate({
			scrollTop: location
		}, 500);
		return false;
	});
});