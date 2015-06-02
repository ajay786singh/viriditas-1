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
				<input type="text" name="by_folk_name" class="search-box" id="by_folk_name" value="<?php if($_REQUEST['keyword']) { echo $_REQUEST['keyword'];} ?>" placeholder="Search herb to add" />
				<section role="body-systems"></section>	
				<section role="actions"></section>				
			</div>
		</div>	
		
		<div class="column-4 result-box">
			<div class="compound-header">
				Latin / Folk
			</div>
			<div class="compound-content">
				<div class="popup-compound">
					<h6>What percent of the total formula will <span class="herb-name"></span> compromise?</h6>
					<div class="pop-up-action">
						<p><input type="text" name="" id="herb-size" max="100" /> <label>%</label></p>
						<a href="#" class="button">Submit</a>
					</div>
				</div>
				<div class="compound-list product-list"></div>
			</div>
		</div>	
		
		<div class="column-4 conclude-box">
			<h4>Your Recipe</h4>
			<form action="" class="recipe-form">
				<div class="compound-header">
					Recipe Name
				</div>
				<div class="compound-content">
					<input type="text" id="recipe-name" required name="recipe-name" placeholder="Please give a name to your recipe.">
				</div>	
				<div class="compound-header">
					Size (ML)
				</div>
				<div class="compound-content">
					<?php
						// $products=unserialize('a:2:{i:1383;a:9:{s:10:"product_id";s:4:"1383";s:14:"hide_thumbnail";s:2:"no";s:14:"override_title";s:2:"no";s:20:"override_description";s:2:"no";s:8:"optional";s:2:"no";s:15:"bundle_quantity";i:1;s:19:"bundle_quantity_max";i:1;s:15:"bundle_discount";s:0:"";s:10:"visibility";s:7:"visible";}i:1389;a:9:{s:10:"product_id";s:4:"1389";s:14:"hide_thumbnail";s:2:"no";s:14:"override_title";s:2:"no";s:20:"override_description";s:2:"no";s:8:"optional";s:2:"no";s:15:"bundle_quantity";i:1;s:19:"bundle_quantity_max";i:1;s:15:"bundle_discount";s:0:"";s:10:"visibility";s:7:"visible";}}');
						// echo "<pre>";
						// print_r($products);
						// echo "</pre>";
					?>
				
				
					<ul>
						<li><label><input type="radio" name="recipe-size" class="recipe-size" value="500-60" checked> 500 ML - 60$</label></li>
						<li><label><input type="radio" name="recipe-size" class="recipe-size" value="1000-100"> 1000 ML - 100$</label></li>
					</ul>
				</div>	
				<div class="compound-header">
					Your Additions
					<input type="hidden" name="compound-products" id="compound-products" value="">
				</div>
				<div class="additions compound-content">
					<ul class="border-list">
					
					</ul>
				</div>
				<div class="compound-header-2">
					* Total must equal 100%
					<input type="text" name="" id="total_percentage" value="">
				</div>
				<div class="compound-content">
					<ul class="border-list">
						<li>
							<div class="left"><strong>Total</strong></div>
							<div class="right">0%</div>
						</li>
					</ul>
				</div>
				<div class="compound-header-2">
					<input type="hidden" name="form_type" id="form_type" value="add">
					<h5>Done ?</h5> <input type="submit" class="manage-recipe button" value="Add to cart">
				</div>
				<div class="errors"></div>
			</form>	
		</div>	
	</div>
</section>
<?php endwhile; endif; ?>
<?php get_footer(); ?>