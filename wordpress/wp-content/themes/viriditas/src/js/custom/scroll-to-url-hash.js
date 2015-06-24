function scrollToElement(ele) {
	var header_height=jQuery('header').height();
	var diff=ele.offset().top - header_height+30;
	jQuery(window).scrollTop(diff).scrollLeft(ele.offset().left);
}
jQuery(document).ready(function($){
	var hash = window.location.hash;
	if(hash) {
		scrollToElement($(hash));
	}
});