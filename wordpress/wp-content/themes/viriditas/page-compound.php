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
		<div class="secondary">
			<a class="back-to-products" href="<?php bloginfo('url');?>/products">&larr; Back to products</a>
		</div>	
	</div>
	<div class="container compounds">
		<section class="column-4">
			<h5>Search herb to add</h5>
			<div class="compound-box" id="compound-box-1">
				<input type="text" name="by_folk_name" class="search-box" id="by_folk_name" value="<?php if($_REQUEST['keyword']) { echo $_REQUEST['keyword'];} ?>" placeholder="Search herb to add" />
				<section role="body-systems"></section>	
				<section role="actions"></section>				
			</div>
		</section>	
		<section class="column-4">
			<h5><a href="">Latin</a> / <a href="">Folk</a></h5>
			<div class="compound-list compound-box" id="compound-box-2">
				<?php
					echo '<ul class="product-list">';
					echo '</ul>';
					echo '<ul class="alphabets-list">';
							$alphas = range('A', 'Z');
							foreach($alphas as $alphabet) {
								if($_REQUEST['sort_by_alpha']==lcfirst($alphabet)) {
									echo "<li><a href='#' id='sort-".lcfirst($alphabet)."' class='active'>".$alphabet."</li>";
								}else {
									echo "<li><a href='#' id='sort-".lcfirst($alphabet)."'>".$alphabet."</a></li>";
								}
							}
					echo '</ul>';
				?>
			</div>
		</section>	
		<section class="column-4">
			<h5>Your Recipe</h5>
			<div class="compound-box" id="compound-box-3">
				<form action="" class="recipe-form">
					<section class="compound-header">
						<h6>Recipe Name</h6>
						<input type="text" id="recipe-name" required name="recipe-name" placeholder="Please give a name to your recipe.">
					</section>
					<section class="compound-sizes">
						<h6>Size (mL)</h6>
						<?php
							$sizes=get_option('wc_settings_tab_compound_sizes');
							if($sizes) {
								$sizes=explode(",",$sizes);
								$i=1;
								echo "<ul>";
								foreach($sizes as $sizeprice) {
									$sizeprice=explode("/",$sizeprice);
									$size=$sizeprice[0];
									$price=$sizeprice[1];
									$additional_price=$sizeprice[2];
									$checked="";
									if($i==1) { $checked='checked';}
									echo "<li>";
										echo "<input type='radio' ".$checked." id='size-".$i."' name='recipe-size' class='recipe-size' value='".$size."-".$price."'>";
										echo "<label for='size-".$i."'>".$size." ML - ".$price."$</label>";
									echo "</li>";
									$i++;
								}
								echo "</ul>";
							}
						?>
					</section>
					<section class="additions">
						<h6>Your Additions</h6>
						<input type="hidden" name="compound-products" id="compound-products" value="">
						<ul>
							
						</ul>
					</section>
					<input type="submit" name="" value="Add to cart">
				</form>
			</div>
		</section>	
	</div>
</section>
<?php endwhile; endif; ?>
<?php get_footer(); ?>