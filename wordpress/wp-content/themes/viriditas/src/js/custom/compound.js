jQuery(document).ready(function($) {
	if($('.compounds').length) {
		$('.error-info').show();
		var pa=getParameterByName('pa');
		var pb=getParameterByName('pb');
		$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
		$('section[role="actions"]').fetchActions('body_system',pb,'pa',pa);
		$('.compound-list .product-list').showCompound();
		$('#by_folk_name').keypress(function (e) {
			if(e.keyCode == '13'){
				var keyword=$(this).val();
				if(keyword !='') {
					var url = replaceParam('keyword', keyword);
					window.history.pushState({path:url},'',url);	
				} else {
					var url=removeURLParameter('keyword');					
					window.history.pushState({path:url},'',url);
				}
				$('.compound-list .product-list').showCompound();
				e.preventDefault();
			}
		});
		$(".recipe-size").on('change',function(){
			//alert($(this).val());
		});
		$('.popup-compound .close-button').click(function(e){
			e.preventDefault();
			$(this).parent().closePopup();
		});
		// $('#herb-size').unbind('keyup change input paste').bind('keyup change input paste',function(e){
			// $(this).sizeInput($(this),e);
		// });
		//$('#herb-size').sizeInput($(this),e);
		$('#herb-size').bind('keydown',function(e){
			$(this).onlyNumbers(e);
		});
		$('.add-herb').click(function(e) {
			var herb_size=$('#herb-size').val();
			var herb_id=$('.herb-name').attr("id");
			var herb_pricy=$('.herb-name').attr("id");
			if(herb_size !='') {
				if(herb_pricy!='') {
					if(herb_size > 60 ) {
						$('.pop-up-error').empty().show().html("Herb size cann't be more than 60%.");
					} else {
						$('.pop-up-error').empty().hide();
						$('#size_'+herb_id).val(herb_size);
						$('.popup-compound').closePopup();
					}	
				} else {
					$('.pop-up-error').empty().hide();
					$('#size_'+herb_id).val(herb_size);
					$('.popup-compound').closePopup();
				}
			} else {
				$('.pop-up-error').empty().show().html("Please enter size of herb for recipe.");
			}
		});
		$('a.sort_by').click(function(e){
			var id=$(this).attr('id');			
			var url = replaceParam('sort_by', id);
			window.history.pushState({path:url},'',url);				
			$('.compound-list .product-list').showCompound();
			e.preventDefault();
		});
	}
});