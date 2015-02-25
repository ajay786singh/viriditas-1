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
	var body_system,category,indication,sort_by_name;
	category=$('.by-category .cs-placeholder').html();
	body_system=$('.by-body_system .cs-options .cs-selected').attr('data-value');
	indication=$('.by-indication .cs-options .cs-selected').attr('data-value');
	if(jQuery('.sort_by_name').hasClass('active-sort')==true) {
		sort_by_name='ASC';
	}
	var filter_action = new Array();
	var n = jQuery(".by-action:checked").length;
	if (n > 0){
		jQuery(".by-action:checked").each(function(){
			filter_action.push($(this).val());
		});
	}
	var $results = $('.product-list');	
	var $loader=$('.loading');
	var $no_records=$('.no-records');
	//$loader;	
		$loader.show();
		$no_records.hide();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{action: 'load_products','filter_type_category':category,'filter_type_body_system':body_system,'filter_type_action':filter_action,'filter_type_indication':indication,'sort_by_name':sort_by_name,'paged':paged },
			beforeSend: function() {
				$loader.show();
				$no_records.hide();
			},
			success: function(html) {
				$loader.hide();
				$results.append(html);
				//$results.find('li .product-title').equalHeights();	
				if (html == "") {
				  	
				  $no_records.show();
				} 
				window.busy = false;
			}
		});
}
function get_body_systems() {
	var category=$('.by-category .cs-options .cs-selected').attr('data-value'); 
	$('.filter-body_system').empty().html('Loading...');
	$('.filter-actions-items').empty();
	$.ajax({
		type: 'POST',
		url: ajaxurl,
		data:{action: 'load_body_systems','category':category },
		success: function(html) {
			$('.filter-body_system').html(html);
			//$('.filter select').selectOrDie();
			//Select.init({selector: '.filter select'});
			[].slice.call( document.querySelectorAll( 'select.by-body_system' ) ).forEach( function(el) {	
				new SelectFx(el);
			} );
		}
	});	
}
function get_actions() {
	var body_system=$('.by-body_system .cs-options .cs-selected').attr('data-value'); 
	var category=$('.by-category .cs-placeholder').html();
	$('.filter-actions-items').empty().html('Loading...');
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
	//$('.filter select').selectOrDie();
	//Select.init({selector: '.filter select'});
	[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
		new SelectFx(el);
	} );
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
	$('.by-category .cs-options').click(function(e){
		e.preventDefault();
		paged=1;
		get_body_systems();
		$('.product-list').empty();
		displayRecords(paged);
		//return false;
	});
	jQuery(".filter-body_system").unbind('click').on("click", ".by-body_system .cs-options", function(event){
		paged=1;
		get_actions();
		$('.product-list').empty();
		displayRecords(paged);
		//return false;
	});
	
	jQuery(".filter-actions").unbind('change').on("change", ".by-action", function(event){
		paged=1;
		event.stopPropagation();
		$('.product-list').empty();
		displayRecords(paged);
		return false;
	});
	jQuery(".filter-indication").unbind('click').on("click", ".by-indication .cs-options", function(event){
		paged=1;
		event.stopPropagation();
		$('.product-list').empty();
		displayRecords(paged);
		return false;
	});
	jQuery(".sort-product a").click(function(e){
		jQuery(this).toggleClass('active-sort');
		paged=1;
		event.stopPropagation();
		$('.product-list').empty();
		displayRecords(paged);
		return false;
	});
	// jQuery(".sort-product").unbind('change').on("change", ".sort-by-name", function(event){
		// paged=1;
		// event.stopPropagation();
		// $('.product-list').empty();
		// displayRecords(paged);
		// return false;
	// });
});