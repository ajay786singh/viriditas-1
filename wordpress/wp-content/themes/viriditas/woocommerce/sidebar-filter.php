<div class="column-3 sidebar">
	<section role="search_by_folk_name">
		<div class="shop-header">
			<h6 class="heading">Filter by Folk Name</h6>
		</div>
		<input type="text" name="by_folk_name" id="by_folk_name" value="<?php if($_REQUEST['s']) { echo $_REQUEST['s'];} ?>" placeholder="Please enter folk name.." />
	</section>
	<section role="category"><?php get_product_categories();?></section>
	<section role="body-systems"></section>	
	<section role="actions"></section>	
	<section role="indications"></section>	
</div>