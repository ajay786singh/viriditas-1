// Onclick action to open/close offcanvas menu
jQuery(document).ready(function($) {
	$('.search-icon a').on('click',function(e){
		$('#search').slideToggle( "slow" );
		e.preventDefault();
	});
});