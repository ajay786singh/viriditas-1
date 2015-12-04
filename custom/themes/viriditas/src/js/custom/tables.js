/*Tabs*/
jQuery(document).ready(function($) {
	$('body').find('table.tablepress').each(function(){
		$(this).wrap( "<div class='tablepress-container'></div>" );
	});
});