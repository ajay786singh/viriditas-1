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
	var category=$('.by-category').val(); 
	var body_system=$('.by-body_system').val(); 
	var filter_action=$('.by-action').val(); 
	var $results = $('.product-list');	
	var $loader=$('.loader');
	//$loader;	
	$loader.html('<button class="btn btn-default" type="button">Loading please wait...</button>').show();
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
			  $loader.html('<button class="btn btn-default" type="button">No more records.</button>').show()
			} 
			window.busy = false;
		}
	});
}

jQuery(document).ready(function($){
	
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
	
	
	$('.filter').change(function(){
		paged=1;
		$('.product-list').empty();
		displayRecords(paged);
		return false;
	});
});