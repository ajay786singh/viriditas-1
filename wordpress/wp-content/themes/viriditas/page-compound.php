<?php 
/*
* Template Name: Manage Compound Page Template
*/
get_header();?>
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
				<div class="search-input">
					<i class="icon-search"></i>
					<input type="search" name="by_folk_name" class="search-box" id="by_folk_name" value="<?php if($_REQUEST['keyword']) { echo $_REQUEST['keyword'];} ?>" placeholder="Search herb to add" />
				</div>				
				<section role="body-systems"></section>	
				<section role="actions"></section>				
			</div>
		</section>	
		<section class="column-4">
			<h5><a href="#" class="sort_by" id="title">Latin</a> / <a href="#" class="sort_by" id="folk_name">Folk</a></h5>
			<div class="compound-list compound-box" id="compound-box-2">
				<!-- Pop Up Box Content -->
				<!--<div class="compound-error error">
					Error
				</div>-->
				<div class="popup-compound">
					<div class="error pop-up-error"></div>
					<h6>What percent of the total formula will <span class="herb-name"></span> compromise?</h6>
					<a href="#" class="close-button"></a>
					<div class="pop-up-action">
						<div class="size-input"><input type="text" name="herb-size" value="0" class="numbers" id="herb-size" maxlength="2" /></div>
						<div><a href="#" class="button add-herb">Add Herb</a></div>
					</div>
				</div>
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
									if($i==1) { 
										$checked='checked';
										$default_size=$sizeprice[0];
										$default_price=$sizeprice[1];
										echo '<input type="hidden" name="cart_size" id="cart_size" value="'.$default_size.'">';
										echo '<input type="hidden" name="cart_price" id="cart_price" value="'.$default_price.'">';
									}
									echo "<li>";
										echo "<input type='radio' ".$checked." id='size-".$i."' data-additional='".$additional_price."' name='recipe-size' class='recipe-size' value='".$size."-".$price."'>";
										echo "<label for='size-".$i."'>".$size." ML - ".$price."$</label>";
									echo "</li>";
									$i++;
								}
								echo "</ul>";								
							}
						?>
					</section>
					<div class="addition-box">
						<section class="compound-header additions">
							<h6>Your Additions</h6>
							<input type="hidden" name="compound-products" id="compound-products" value="">
							<ul>
								
							</ul>
						</section>
						<section class="compound-info">*Total must be equal 100%</section>
						<section class="compound-header total-box">
							<h6>TOTAL</h6>
							<h6 id="total_size">0%</h6>
						</section>
						<div class="errors"></div>
						<input type="submit" name="" value="ADD TO CART" id="add-recipe" class="button">
					</div>
					<section class="compound-info hide-info">Please add herbs to make your recipe.</section>
				</form>
			</div>
		</section>	
	</div>
</section>
<?php endwhile; endif; ?>
<?php get_footer(); ?>