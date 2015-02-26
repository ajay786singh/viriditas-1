<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<section role="banner" style="background-image:url('<?php bloginfo('template_url' ); ?>/dist/images/contact-banner.jpg');">
    <div class="container contact">
        <div class="banner-content">
			<div class="secondary">
				<h4>Viriditas</h4>
				<p>2775 Dundas Street West</p>
				<p>Toronto, ON M6P 1Y5</p>
			</div>
        </div>
    </div>
</section>

<section role="contact-content">
	<article class="container contact">
		<div class="secondary">
			<hr>
			<p><span>p:</span> 416-767-3427</p>	
			<p><span>f:</span> 416-767-1215</p>	
			<p><a href="mailto://redden@viriditasherbalproducts.com">redden@viriditasherbalproducts.com</a></p>
			<a href="" class="button">Book Appointment</a>
		</div>
	</article>
</section>	
<?php endwhile; endif; ?>

<?php get_footer(); ?>