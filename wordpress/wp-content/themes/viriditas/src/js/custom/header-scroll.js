jQuery(function() {
  jQuery(window).scroll(function() {		
		if(jQuery('section[role=banner]').length!=0) {
			var banner_height=200;//jQuery('section[role=banner]').height();		
			if (jQuery(this).scrollTop() > banner_height){  		
				jQuery('header').addClass("active-header");		
			}		
			else{		
				jQuery('header').removeClass("active-header");		
			}		
		}
	});
});