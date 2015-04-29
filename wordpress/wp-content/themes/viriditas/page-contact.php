<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<section role="banner" class="store-map">
    <input type="hidden" id="location" value="2775+Dundas+St+W,+Toronto,+ON+M6P+1Y4,+Canada">
	<div id="map"></div>
</section>

<section role="contact-content">
	<article class="container contact">
		<div class="secondary">
			<?php 
				$map_url="https://www.google.co.in/maps/place/2775+Dundas+St+W,+Toronto,+ON+M6P+1Y4,+Canada/@43.6652469,-79.4618064,17z/data=!3m1!4b1!4m2!3m1!1s0x882b3422899970ad:0xf3a8f7a3d3db7a62?hl=en";
			?>
			<h2>Viriditas</h2>
			<p>2775 Dundas Street West</p>
			<p>Toronto, ON M6P 1Y5 <a href="<?php echo $map_url;?>" title="View on map" class="view-map" target="_blank"><img src="<?php bloginfo('template_url');?>/dist/images/map-icon.png" width="16"></a></p>
			<hr>
			<p><span>p:</span> 416-767-3427</p>	
			<p><span>f:</span> 416-767-1215</p>	
			<p><a href="mailto://redden@viriditasherbalproducts.com">redden@viriditasherbalproducts.com</a></p>
			<a href="<?php bloginfo('url');?>/appointments" class="button">Book Appointment</a>
		</div>
	</article>
</section>	
<?php endwhile; endif; ?>

<?php get_footer(); ?>