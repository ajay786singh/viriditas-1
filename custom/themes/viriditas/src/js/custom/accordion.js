jQuery(document).ready(function(e){
	jQuery('.accordion-panel-header').click(function (e){
		jQuery('.accordion-panel').each(function(e) {
			jQuery(this).removeClass('active-header');
			jQuery('.accordion-panel-content').slideUp('fast').removeClass('active');
		});
		if(jQuery(this).next('.accordion-panel-content').css('display') != 'block'){
			jQuery('.active').slideUp('fast').removeClass('active');
			jQuery(this).addClass('active-header');
			jQuery(this).next('.accordion-panel-content').addClass('active').slideDown('slow');
			
		} else {
			jQuery('.active').slideUp('fast').removeClass('active');
			jQuery(this).removeClass('active-header');
		}
	});
});