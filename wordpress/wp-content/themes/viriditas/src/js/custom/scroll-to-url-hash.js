function scrollToElement(ele) {
	var headerHeight=jQuery('header').height();
	var diff=ele.offset().top - headerHeight+30;
	jQuery(window).scrollTop(diff).scrollLeft(ele.offset().left);
}
jQuery(document).ready(function($){
	var hash = window.location.hash;
	if(hash) {
		scrollToElement($(hash));
	}
});