<div class="span-3 sidebar">
	<div class="shop-header">
		<h6 class="heading">Filter By Category</h6>
	</div>
	<?php 
		$product_categories = get_terms('product_cat', 'orderby=count&hide_empty=0&hierarchical=0');
		//print_r($product_categories);
		if($product_categories) {
	?>
	<ul class="filter by-category">
		<?php foreach($product_categories as $product_category) {
			echo "<li><a href='?filter-type-category=".$product_category->term_id."'>".$product_category->name."</a></li>";
		 } ?>
	</ul>
	<?php } ?>
</div>