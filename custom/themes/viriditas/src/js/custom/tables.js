/*Tabs*/
jQuery(document).ready(function($) {
	$('body').find('table').each(function (){
		//alert(e(this).html());
		$(this).wrap( "<div class='tablepress-container'></div>" );
	});
});