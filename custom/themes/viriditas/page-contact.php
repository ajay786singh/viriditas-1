<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
	$map_url="https://www.google.co.in/maps/place/2775+Dundas+St+W,+Toronto,+ON+M6P+1Y4,+Canada/@43.6652469,-79.4618064,17z/data=!3m1!4b1!4m2!3m1!1s0x882b3422899970ad:0xf3a8f7a3d3db7a62?hl=en";		
?>
<section role="banner" class="store-map">
    <input type="hidden" id="location" value="2775+Dundas+St+W,+Toronto,+ON+M6P+1Y4,+Canada">
	<a href="<?php echo $map_url;?>" target="_blank">
		<div id="map"></div>
	</a>
</section>

<section role="contact-content">
	<article class="container contact-box">
		<div class="secondary">
			<?php the_content();?>
		</div>
	</article>
</section>	
<?php endwhile; endif; ?>

<?php get_footer(); ?>