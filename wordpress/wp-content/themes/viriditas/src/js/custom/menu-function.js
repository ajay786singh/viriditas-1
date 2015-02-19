jQuery(document).ready(function() {
	var menu = new Menu;
	
	jQuery(window).scroll(function() {		
		var banner_height=jQuery('section[role=banner]').height();		
		if (jQuery(this).scrollTop() > banner_height-100){  		
			jQuery('body').addClass("active-header");		
		}		
		else{		
			jQuery('body').removeClass("active-header");		
		}		
	});

});