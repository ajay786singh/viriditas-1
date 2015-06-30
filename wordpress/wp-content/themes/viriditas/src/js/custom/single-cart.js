jQuery(document).ready(function($) {
	$('#edit-formula').click(function(){
		var container=$(this).attr('id');
		$('body').addClass('open-popup');
	});
	$('.popup-overlay').click(function(){
		$('body').removeClass('open-popup');
	});
	if($('.variations').length>0) {
		var id=$('.variations ul li input:radio:checked').attr("id");
		var variation_id=id.replace('size_','');
		$('#variation_id').val(variation_id);
	}
	$('.variations ul li input:radio').unbind('click').bind("click", function(e){
		var id=$(this).attr('id');
		var variation_id=id.replace('size_','');
		//$('#variation_id').val('').change();
		$('#variation_id').val(variation_id);		
	});
	
	
	
	
});