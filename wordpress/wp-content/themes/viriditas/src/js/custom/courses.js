function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
jQuery(document).ready(function($) {
	$('.sidebar li a').click(function(){
		var scroll = $(this).attr('rel');
		var padding=$('section[role="content"]').css('padding-top');
		var location =$("#"+scroll).offset().top-parseInt(padding, 10);
		  $('html, body').animate({
			scrollTop: location
		}, 500);
		return false;
	});
	if($('#gform_wrapper_1').length){
		var course_id=getParameterByName('course_id');
		var form_container=$("#gform_wrapper_1");
		if(course_id!='') {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: ajaxurl,
				data:{action: 'get_course','id':course_id },
				success: function(html) {
					var title=html.title;
					var price="$"+html.price;
					form_container.find('label[for="input_1_4"]').html(title);
					form_container.find('input[name="input_4.1"]').val(title);
					form_container.find('#input_1_4').html(price);
					form_container.find('input[name="input_4.2"]').val(price);
				}
			});	
		}
	}
});