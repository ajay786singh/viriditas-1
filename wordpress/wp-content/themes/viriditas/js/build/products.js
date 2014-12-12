jQuery.urlParam = function(name){
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results==null){
	   return null;
	}
	else{
	   return results[1] || 0;
	}
}

var busy = false;
var paged = 1;
//var offset = 0;

function displayRecords(paged) {
	//alert(1);
	var category=$('.by-category').val(); 
	var body_system=$('.by-body_system').val(); 
	//alert(body_system);
	//var filter_action=$('.by-action').val(); 
	//var filter_action=$( ".filter-actions input:checkbox" ).map(function() { return this.id;}).get().join();
	var filter_action = new Array();
	var n = jQuery(".by-action:checked").length;
	if (n > 0){
		jQuery(".by-action:checked").each(function(){
			filter_action.push($(this).val());
		});
	}
	var $results = $('.product-list');	
	var $loader=$('.loader');
	//$loader;	
		$loader.html('<div class="loading">Loading please wait...</div>').show();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{action: 'load_products','filter_type_category':category,'filter_type_body_system':body_system,'filter_type_action':filter_action,'paged':paged },
			beforeSend: function() {
				//$loader.html("").hide();
				$loader.show();
			},
			success: function(html) {
				$loader.hide();
				$results.append(html);
				$results.find('li .product-title').equalHeights();	
				if (html == "") {
				  $loader.html('<div class="loading">No more records.</div>').show()
				} 
				window.busy = false;
			}
		});
}
function get_actions() {
	var body_system=$('.by-body_system').val(); 
	var category=$('.by-category').val(); 
	$.ajax({
		type: 'POST',
		url: ajaxurl,
		data:{action: 'load_actions','body_system':body_system,'category':category },
		success: function(html) {
			$('.filter-actions-items').empty().html(html);
		}
	});	
}
jQuery(document).ready(function($){
	$('.filter select').selecter();
	// start to load the first set of data
		if (busy == false) {
		  busy = true;
		  // start to load the first set of data
		  displayRecords(paged);
		}

		$(window).scroll(function() {
          // make sure u give the container id of the data to be loaded in.
          if ($(window).scrollTop() + $(window).height() > $('.product-list').height() && !busy) {
            busy = true;
            paged++;
            // this is optional just to delay the loading of data
            setTimeout(function() { displayRecords(paged); }, 500);

            // you can remove the above code and can use directly this function
            // displayRecords(limit, offset);

          }
        });
	
	//$(".filter").unbind('change');
	$('.by-category').unbind('change').change(function(e){
		e.preventDefault();
		paged=1;
		$('.by-body_system').val('');
		$('.filter-body_system .selecter-options .selecter-item').each(function(){
			$(this).removeClass('selected');
		});
		$('.filter-body_system .selecter-options .selecter-item:first-child').addClass('selected');
		$('.filter-body_system .selecter-selected').html('Select Body System');
		$('.filter-actions').hide();
		$('.by-action').removeAttr('checked');
		$('.product-list').empty();
		displayRecords(paged);
		//return false;
	});
	$('.by-body_system').unbind('change').change(function(e){
		e.preventDefault();
		paged=1;
		if($(this).val()==''){
			$('.filter-actions').hide();
		}else {
			get_actions();
			$('.filter-actions').show();
		}
		$('.product-list').empty();
		displayRecords(paged);
		//return false;
	});
	// jQuery(".by-action").unbind('change').change(function(e){
		//e.preventDefault();
		// paged=1;
		// $('.product-list').empty();
		// displayRecords(paged);
		// return false;
	// });
	jQuery("body").unbind('change').on("change", ".by-action", function(event){
		paged=1;
		$('.product-list').empty();
		displayRecords(paged);
		return false;
	});
});