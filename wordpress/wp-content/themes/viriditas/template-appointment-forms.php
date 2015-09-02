<?php
/*
* Template Name: Appointment Form Page
*/
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<div class="column-9 border-right">
			<?php the_title("<h1>","</h1>");?>
			<?php the_content();?>
		</div>
		<?php 
			$pdf=get_post_meta(get_the_ID(),'_appointment_block_appointment_pdf',true);
			if($pdf!='') {
		?>
		<aside class="column-3 sidebar">
			<h5>Prefer to fill this out by hand?</h5>
			<a href="<?php echo $pdf;?>" target="_blank" class="icon-download">Download Form</a>
		</aside>	
		<?php } ?>
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>