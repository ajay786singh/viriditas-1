<?php
/*
* Template Name: Appointment Form Page
*/
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<div class="content-grid content-left">
			<div class="column-8 border-right appointment-form-content">
				<?php the_title("<h1>","</h1>");?>
				<?php the_content();?>
			</div>
			<?php 
				$pdf=get_post_meta(get_the_ID(),'_appointment_block_appointment_pdf',true);
				if($pdf!='') {
			?>
			<aside class="column-4 sidebar">
				<h5>Prefer to fill this out by hand?</h5>
				<a href="<?php echo $pdf;?>" target="_blank" class="icon-download">Download Form</a>
			</aside>	
			<?php } ?>
		</div>
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>