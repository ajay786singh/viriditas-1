jQuery(document).ready(function($) {
	if($('.filter-compound').length) {
		$('.error-info').show();
		var pa=getParameterByName('pa');
		var pb=getParameterByName('pb');
		$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
		$('section[role="actions"]').fetchActions('body_system',pb,'pa',pa);
		$('.compound-list').showCompound();
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
				$('.compound-list').showCompound();
				e.preventDefault();
			}
		});
		// $('.herb-sizes').on('input', function (event) { 
			// this.value = this.value.replace(/[^0-9]/g, '');
			// alert(this.value);
		// });
	}
});