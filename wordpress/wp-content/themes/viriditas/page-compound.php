<?php 
/*
* Template Name: Manage Compound Page Template
*/
get_header(); 
?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
	$compound_id="";
	$bundle_herbs="";
	$bundle_herb_ids="";
	$compound_id = $_REQUEST['compound'];
	$addition_box_class="compound-header";
?>
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
			<h5>Step 1. Search for herbs to add</h5>
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
			<?php 
				$sort_by = $_REQUEST['sort_by'];
			?>
			<h5>
				Step 2. Select herbs 
				<span>
					<a href="#" class="sort_by <?php if($sort_by=='' || $sort_by=='title') { echo "active";}?>" id="title">Latin</a> | 
					<a href="#" class="sort_by <?php if($sort_by=='folk_name') { echo "active" ;}?>" id="folk_name">Folk</a>
				</span>
			</h5>
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
						<form action="" method="post" class="add-recipe-herb">
							<div class="size-input"><input type="text" name="herb-size" value="0" class="numbers" id="herb-size" maxlength="2" /></div>
							<div><a href="#" class="button add-herb">Add Herb</a></div>
						</form>
					</div>
				</div>
				<?php
					echo '<ul class="product-list">';
					echo '</ul>';
					echo alphabets_filter();					
				?>
			</div>
		</section>	
		<section class="column-4">
			<h5>Step 3. Review Your Recipe</h5>
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
								if($compound_id !='') {
									$compound_sizes = get_the_terms( $compound_id, 'pa_size');
									sort($compound_sizes);
									$compound_prices = get_the_terms( $compound_id, 'pa_price');
									sort($compound_prices);
								}
								if($sizes) {
									$sizes=explode(",",$sizes);								
									$i=0;
									echo "<ul>";
									foreach($sizes as $sizeprice) {
										$sizeprice=explode("/",$sizeprice);
										$size=$sizeprice[0];
										$price=$sizeprice[1];
										if($compound_id){
											$size=$compound_sizes[$i]->name;
											$price=$compound_prices[$i]->name;
										}
										$additional_price=$sizeprice[2];
										$checked="";
										if($i==0) { 
											$checked='checked';
											$default_size=$sizeprice[0];
											$default_price=$sizeprice[1];
											if($compound_id){
												$default_size=$compound_sizes[0]->name;
												$default_price=$compound_prices[0]->name;
											}
											echo '<input type="hidden" name="cart_size" id="cart_size" value="'.$default_size.'">';
											echo '<input type="hidden" name="cart_price" id="cart_price" value="'.$default_price.'">';
										}
										$i++;
										echo "<li>";
											echo "<input type='radio' ".$checked." id='size-".$i."' data-additional='".$additional_price."' name='recipe-size' class='recipe-size' value='".$size."-".$price."'>";
											echo "<label for='size-".$i."'>".$size." mL - $".$price."</label>";
										echo "</li>";
									}
									echo "</ul>";								
								}
							//}
						?>
					</section>
					<?php 
						if($compound_id !='') { 
							$addition_box_class="";
					?>
					<section class="compound-header">
						<h6>Base Formulae</h6>
						<h6><?php echo strtoupper(get_the_title($compound_id));?></h6>
						<div class="column-8">
							<?php
								$bundle_data=get_post_meta($compound_id,'_bundle_data',true);
								foreach($bundle_data as $bundle_herb_id => $bundle_herb_values ) {
									$bundle_herbs[]= "<small><i>".get_the_title($bundle_herb_id)."</i></small>";
									$bundle_herb_ids[]=$bundle_herb_id;
								}
								if(count($bundle_herbs) > 1) {
									echo join(", ",$bundle_herbs);
									$bundle_herb_ids= join(", ",$bundle_herb_ids);
								} else {
									echo $bundle_herbs;
									$bundle_herb_ids= $bundle_herb_ids;
								}
							?>
						</div>	
						<div class="column-4">
							<div class="base-size">100%</div>
						</div>
					</section>
					<?php } ?>
					
					<input type="hidden" name="recipe-compound-id" id="recipe-compound-id" value="<?php echo $compound_id;?>">
					<input type="hidden" name="recipe-compound-herbs" id="recipe-compound-herbs" value="<?php echo $bundle_herb_ids;?>">
					<input type="hidden" name="product_type" id="product_type" value="bundle">
					<div class="addition-box">
						<section class="<?php echo $addition_box_class;?> additions">
							<h6>Your Additions</h6>
							<input type="hidden" name="compound-products" id="compound-products" value="">
							<ul>
								
							</ul>
						</section>
						<section class="compound-info">*Total must be equal 100%</section>
						<section class="compound-header total-box">
							<h6>TOTAL</h6>
							<h6 id="total_size">
								<?php if($compound_id!='') { echo "25%";} else { echo "0%";} ?>
							</h6>
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