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
	var category=$.urlParam('filter-type-category'); 
	var $results = $('.product-list');	
	var $loader=$('.loader');
	$loader.show();	
	$.ajax({
		type: 'POST',
		url: ajaxurl,
		data:{action: 'load_products',filter_type_category:category,'paged':paged },
		beforeSend: function() {
			//$loader.html("").hide();
			$loader.show();
		},
		success: function(html) {
			$results.append(html);
			$results.find('li .product-title').equalHeights();	
			$loader.hide();
			if (html == "") {
			  $loader.html('<button class="btn btn-default" type="button">No more records.</button>').show()
			} else {
			  $loader.html('<button class="btn btn-default" type="button">Loading please wait...</button>').show();
			}
			window.busy = false;
		}
	});
}

function get_products(){
	var c = $('.product-list');
	var loader=$('.loader');	
	//var busy = false;
	//var limit = 15
	//var offset = 0;
		var category=$.urlParam('filter-type-category'); 		
		loader.show();
		c.empty();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {action: 'load_products',filter_type_category:category },
			success: function(response) {
				loader.hide();
				c.empty().html(response);
				c.find('li .product-title').equalHeights();	
				return false;
			}
		});
}

jQuery(document).ready(function($){
	
	//get_products();
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
	
	
	$('.filter.by-category li a').click(function(){
		var	pageurl = $(this).attr('href');
		paged=1;
		$('.product-list').empty();
		if(pageurl!=window.location){
			window.history.pushState({path:pageurl},'',pageurl);
		}
		displayRecords(paged);
		return false;
	});
});