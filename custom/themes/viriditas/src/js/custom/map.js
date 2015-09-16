jQuery(document).ready(function($) {
	if($(".store-map").length) {
		var window_height=$(window).height();
		$(".store-map").height(window_height/2);
		var geocoder =  new google.maps.Geocoder();
		var location=$('#location').val();
		geocoder.geocode( { 'address': location}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var zoom_level=15;
				var center=new google.maps.LatLng(results[0].geometry.location.lat(),results[0].geometry.location.lng());
				$('#map').gmap('option', 'center', center);
				$('#map').gmap('option', 'zoom', Number(zoom_level));
				$('#map').gmap('addMarker', { 'bounds':false, 'position': center });
			}
		});
	}
});