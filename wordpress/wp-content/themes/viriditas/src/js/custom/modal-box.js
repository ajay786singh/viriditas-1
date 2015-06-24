jQuery(function($) {
	$('.popup-modal').magnificPopup({
		type: 'inline',
		preloader: false,
		alignTop: true,
		overflowY: 'scroll', 
		modal: true
	});
	jQuery(document).on('click', '.popup-modal-dismiss', function (e) {
		e.preventDefault();
		jQuery.magnificPopup.close();
	});
});