<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<?php //the_title("<h1>","</h1>");?>
		<h1>Book an Appointment</h1>
		<div class="column-9 border-right">
			<?php
				$practioners=get_practioners();
				$services=get_services();
				if($services !='' || $practioners !='') {
					echo "<form action='' class='appointments-form'>";
				}
				if($practioners!="") {
			?>
				<div class="app_workers">
					<div class="app_workers_dropdown">
						<div class="app_workers_dropdown_title">Please choose a practitioner</div>
						<div class="app_workers_dropdown_select">
							<?php echo $practioners;?>
						</div>
					</div>
					<div class="app_worker_excerpts">
						<div class="app_worker_excerpt" id="app_worker_excerpt_4"></div>
					</div>
				</div>
			<?php
				}
				if($services!="") {
			?>
			<div class="app_services">
				<div class="app_services_dropdown">
					<div class="app_services_dropdown_title">Please select a service:</div>
					<div class="app_services_dropdown_select">
						<?php echo $services;?>
					</div>
				</div>
			</div>	
			<?php	
				}
				if($services !='' || $practioners !='') {
					echo "<input type='submit' name='' value='Show available times'>";
					echo "</form>";
				}
				the_content();
			?>
		</div>	
		<?php get_sidebar('appointment');?>
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>