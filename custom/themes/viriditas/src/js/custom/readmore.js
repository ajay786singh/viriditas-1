jQuery(document).ready(function($) {
	// $('.post-course .content').each(function(){
		// alert($(this).height());
	// });
	
	$('.post-course .content').readmore({
	  speed: 75,
	  moreLink: '<a href="#" class="more-link">Learn more</a>',
	  lessLink: '<a href="#" class="more-link less">Show less</a>'
	});
	$('.team-member .team-content').readmore({
	  speed: 75,
	  moreLink: '<a href="#" class="more-link">Learn more</a>',
	  lessLink: '<a href="#" class="more-link less">Show less</a>' 
	});
});