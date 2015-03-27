jQuery(function ($) {
	$('.popup-modal').magnificPopup({
		type: 'inline',
		preloader: false,
		alignTop: true,
		overflowY: 'scroll', 
		modal: true
	});
	$(document).on('click', '.popup-modal-dismiss', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});
});