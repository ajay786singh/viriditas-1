jQuery(document).ready(function() {
	var offset_val= 100;
	var mobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
    if (mobile==false) {  
        jQuery('.slidein-left').addClass("hidden").viewportChecker({
			classToAdd: 'visible animated fadeInRightBig', // Class to add to the elements when they are visible
			offset: offset_val    
		   });
		jQuery('.slidein-right').addClass("hidden").viewportChecker({
			classToAdd: 'visible animated fadeInLeftBig', // Class to add to the elements when they are visible
			offset: offset_val    
		   });
		jQuery('.hero-copy').addClass("hidden").viewportChecker({
			classToAdd: 'visible animated fadeIn', // Class to add to the elements when they are visible
			offset: offset_val    
		   });

    }
});

