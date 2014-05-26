function defaultImage(img,error_img)
{
	img.onerror = "";
	img.src = error_img;
}
	
jQuery(document).ready(function($){
    var $container = $('#isotope-container .isotope');
    $container.isotope({
        filter: '*',
		layoutMode: 'fitRows',
        animationOptions: {
            duration: 750,
            easing: 'linear',
            queue: false,
        }
    });
 
    $('.filters a').click(function(){
        var selector = $(this).attr('data-filter');
		$('.filters a').each(function(){
			$(this).removeClass('is-checked');
		});
		$(this).addClass('is-checked');
        $container.isotope({ 
            filter: selector,
			layoutMode: 'fitRows',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false,
            }
        });
       
        return false;
    });
 
    //var page = 1;
// 
//    $(window).scroll(function(){
// 
//        var scrollTop = $(window).scrollTop();
//        var windowHeight = $(window).height();
//        var docuHeight = $(document).height();
// 
//        if(scrollTop + windowHeight == docuHeight){
//                 
//            $('body').append('<div id="temp-load"></div>');
// 			 	$('.loading').show();
//				var LastDiv = $(".item:last"); /* get the last div of the dynamic content using ":last" */
//				var LastId  = $(".item:last").attr("id").replace('item_',''); /* get the id of the last div */
//				var ValueToPass = "action=read_json_feeds&lastid="+LastId; /* create a variable that containing the url parameters which want to post to getdata.php file */
//				$.ajax({ /* post the values using AJAX */
//				type: "POST",
//				url: "http://192.168.1.5/github/social/wordpress/wp-admin/admin-ajax.php",
//				data: ValueToPass,
//				cache: false,
//					success: function(data){
//						$('.loading').hide();
//						if(data){
//								$('#temp-load').html('<div id="content">'+data+'</div>');
//								$('#temp-load > #content').children().css({
//									opacity: 0
//								}); 	
//								var toAdd = $('#temp-load > #content').html();										
//								$container.isotope('insert', $(toAdd), function(){
//
//									$container.children().css({
//										opacity: 1
//									}); 	 
//								});		
//								$('#temp-load').html("");
//						}
//					}
//				});
//			 
//             
//        }
// 
//    });

});