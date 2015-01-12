<div class="span-3 sidebar">
	<div class="shop-header">
		<h6 class="heading">Filter Products</h6>
	</div>
	<?php 
		$product_categories = get_terms('product_cat', 'orderby=count&order=desc&hide_empty=0&hierarchical=0&parent=0&exclude=8,118');
		$body_system_args=array(
			'post_type' => 'product',
			'numberposts' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => array(327)
				)
			)
		);
		$objects_ids='';	
		$objects = get_posts( $body_system_args );
		foreach ($objects as $object) {
			$objects_ids[] = $object->ID;
		}
		$body_systems = wp_get_object_terms( $objects_ids, 'body_system' );
		if($product_categories) {
	?>
	<div class="filter filter-category">
		<select class="by-category">
			<?php 
			foreach($product_categories as $product_category) {
				echo "<option value='".$product_category->term_id."'>".$product_category->name."</option>";
			} ?>
		</select>
	</div>
	<?php } ?>
	<div class="filter filter-body_system">
	<?php if($body_systems) { ?>
		<select class="by-body_system">
				<option value="">Select Body System</option>
			<?php 
			foreach($body_systems as $body_system) {
				echo "<option value='".$body_system->term_id."'>".$body_system->name."</option>";
			} ?>
		</select>
	<?php } ?>
	</div>
	<div class="filter filter-actions">
			<div class="filter-actions-items">
			</div>
	</div>
	<div class="shop-header">
		<h6 class="heading">Sort Products</h6>
	</div>
	<div class="filter sort-product">
		<select class="sort-by-name">
			<option value="">Sort By Name</option>
			<option value="asc">Sort By Name ASC</option>
			<option value="desc">Sort By Name DESC</option>
		</select>
	</div>	
</div>