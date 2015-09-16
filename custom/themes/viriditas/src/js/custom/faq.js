/*faqs Monograph JS*/
jQuery(document).ready(function($) {
    $("#monograph-1-list").listnav({
		includeNums: false,
		includeAll: true,
		showCounts: false,
		noMatchText: 'No monograph found.',
	});
	$("#monograph-2-list").listnav({
		includeNums: false,
		includeAll: true,
		showCounts: false,
		noMatchText: 'No monograph found.',
	});
	$('.show-contact-form').unbind('click').bind('click',function(e){
		$('#faq-contact-form').toggle();
		e.preventDefault();
	});
});