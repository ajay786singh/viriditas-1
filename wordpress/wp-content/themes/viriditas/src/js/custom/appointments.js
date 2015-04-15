jQuery(document).ready(function($){
	if($('.appointments-form').length) {
		$('.app_select_workers').on('change',function() {
			var $this=$(this);
			var value=$this.val();
			var $service=$('.app_service_id');
			$service.attr('disabled','disabled').css('opacity','0.8');
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{
					action: 'get_services_by_practioner',
					'app_select_workers':value 
				},
				success: function(html) {
					$('.app_service_id').empty();
					$service.removeAttr('disabled').css('opacity','1');
					$('.app_service_id').append(html);
				}
			});
		});
	}
});