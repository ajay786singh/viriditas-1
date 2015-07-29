/*faqs Monograph JS*/
jQuery(document).ready(function($) {
	// $('.monograph-header').click(function(e){
		//alert(1);
	// });
	if($('#faq-box').length >0){
		if($('.monograph-header').length>0){
			$('.monograph-header').each(function(){
				var category = $(this).attr("data-rel");
				//var cha = $(this).attr("data-rel");
				var data = {'action':'manage_monograph','category':category};
				var container=$('#monograph-'+category);	
				container.empty();	
				container.addClass("loading");	
				// $.ajax({		
					// type: 'POST',		
					// url: ajaxurl,		
					// data: data,		
					// success: function(html) {	
						// container.removeClass("loading");	
						// container.append(html);
					// }		
				// });	
			});
		}
	}
});