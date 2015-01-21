jQuery(document).ready(function() {
	jQuery('#nav-toggle').bigSlide({'side':'right'});
	
	jQuery(window).scroll(function() {
		var banner_height=jQuery('section[role=banner]').height();
		if (jQuery(this).scrollTop() > banner_height-100){  
			jQuery('header').addClass("active-header");
		}
		else{
			jQuery('header').removeClass("active-header");
		}
	});
});