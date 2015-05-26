<?php 
/*
* Template Name: Manage Compound Page Template
*/
get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<section role="content">
    <div class="container">
		<div class="secondary">
		<h1>Custom Formulae</h1>
		<?php the_content();?>
		</div>
	</div>
	<div class="container">
		<div class="column-4 filter-compound">
			<div class="compound-header">
				Search herb to add
			</div>
			<div class="compound-content">
				<input type="text" name="" id="" value="" placeholder="Search herb to add" />
				<section role="body-systems">
				
				
				</section>	
				<section role="actions"></section>				
			</div>
		</div>	
		
		<div class="column-4 result-box">
			<div class="compound-header">
				Latin / Folk
			</div>
			<div class="compound-content">
				<div class="compound-list product-list"></div>
			</div>
		</div>	
		
		<div class="column-4 conclude-box">
			<div class="compound-header">
				Your recipe
			</div>
			<div class="compound-header">
				Your Additions
			</div>
			<div class="additions">
				<ul>
					
				</ul>
			</div>
		</div>	
	</div>
</section>
<?php endwhile; endif; ?>
<?php get_footer(); ?>