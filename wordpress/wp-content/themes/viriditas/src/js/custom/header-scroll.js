jQuery(function() {
	if(jQuery('section[role=banner]').length) {
		jQuery(window).scroll(function() {		
			var banner_height=200;//jQuery('section[role=banner]').height();		
			if (jQuery(this).scrollTop() > banner_height){  		
				jQuery('header.top-header').addClass("active-header");		
			}		
			else{		
				jQuery('header.top-header').removeClass("active-header");		
			}
		});			
	}else {
		jQuery('header.top-header').addClass("active-header");
	}
});