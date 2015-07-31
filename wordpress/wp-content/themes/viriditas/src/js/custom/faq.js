/*faqs Monograph JS*/
jQuery(document).ready(function($) {
    $("#monograph-1-list").listnav({
		includeNums: false,
		initLetter: 'a',
		includeAll: false,
		showCounts: false,
		noMatchText: 'No monograph found.',
	});
	$("#monograph-2-list").listnav({
		includeNums: false,
		initLetter: 'a',
		includeAll: false,
		showCounts: false,
		 noMatchText: 'No monograph found.',
	});
	// $("#monograph-single-herb-tincture .alphabets-list li a").on('click',function(e){
		// var id = $(this).attr("id");
		// var sort_by = $(this).attr("data-sort");
		// var postType = 'product';
		// $("#monograph-single-herb-tincture .alphabets-list li a").each(function(){
			// $(this).removeClass("alphabet-active");
		// });
		// $(this).addClass("alphabet-active");
		// var data= {'action':'manage_monograph','post_type':postType,'sort_by':sort_by};		
		// $('#monograph-1').empty();
		// $('#monograph-1').loaderShow();
		// $.ajax({		
			// type: 'POST',		
			// url: ajaxurl,		
			// data:data,		
			// success: function(html) {	
				// alert(html);
				// $('#monograph-1').loaderHide();		
				// $('#monograph-1').append(html);		
			// }		
		// });	
	// });
	// $("#monograph-professional-herbal-combination .alphabets-list li a").click(function(){
		
	// });
});