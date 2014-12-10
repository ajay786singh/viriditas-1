<div class="span-3 sidebar">
	<div class="shop-header">
		<h6 class="heading">Filter By Category</h6>
	</div>
	<?php 
		$product_categories = get_terms('product_cat', 'orderby=count&order=desc&hide_empty=0&hierarchical=0&parent=0&exclude=8,118');
		$body_systems = get_terms('body_system', 'orderby=count&order=desc&hide_empty=0&hierarchical=0&parent=0');
		$actions = get_terms('actions', 'orderby=count&order=desc&hide_empty=0&hierarchical=0&parent=0');
		if($product_categories) {
	?>
	<select class="filter by-category">
		<?php 
		foreach($product_categories as $product_category) {
			echo "<option value='".$product_category->term_id."'>".$product_category->name."</option>";
		} ?>
	</select>
	<?php } ?>
	<?php if($body_systems) { ?>
	<select class="filter by-body_system">
			<option value="">Select Body System</option>
		<?php 
		foreach($body_systems as $body_system) {
			echo "<option value='".$body_system->term_id."'>".$body_system->name."</option>";
		} ?>
	</select>
	<?php } ?>
	<?php if($actions) { ?>
	<select class="filter by-action">
		<option value="">Select Action</option>
		<?php 
		foreach($actions as $action) {
			echo "<option value='".$action->term_id."'>".$action->name."</option>";
		} ?>
	</select>
	<?php } ?>
</div>