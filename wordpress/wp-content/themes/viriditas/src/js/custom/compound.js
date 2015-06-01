jQuery(document).ready(function($) {
	if($('.filter-compound').length) {
		$('.error-info').show();
		var pa=getParameterByName('pa');
		var pb=getParameterByName('pb');
		$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
		$('section[role="actions"]').fetchActions('body_system',pb,'pa',pa);
		$('.compound-content .product-list').showCompound();
		$('#by_folk_name').keypress(function (e) {
			if(e.keyCode == '13'){
				var keyword=$(this).val();
				if(keyword !='') {
					var url = replaceParam('keyword', keyword);
					window.history.pushState({path:url},'',url);	
				}else {
					var url=removeURLParameter('keyword');					
					window.history.pushState({path:url},'',url);
				}
				$('.compound-content .product-list').showCompound();
				e.preventDefault();
			}
		});
		$('.recipe-form').submit(function(e){
			e.preventDefault();
			var title = $('#recipe-name').val();
			var form_type=$('#form_type').val();
			var size_price=$('.recipe-size:checked').val();
			size_price=size_price.split("-");
			var size=size_price[0];
			var price=size_price[1];
			var compound_products=$('#compound-products').val();
			var data= {'action':'manage_compound','title':title,'size':size,'price':price,'form_type':form_type,'compound_products':compound_products};
			var message=$('.errors');
			message.fadeIn().empty();
			message.loaderShow();
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data:data,
				success: function(html) {
					alert(html);
					message.loaderHide();
					message.empty();
					message.append(html);
				}
			});
		});
	}
});