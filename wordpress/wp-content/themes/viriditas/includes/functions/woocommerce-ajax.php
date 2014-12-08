<?php
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	//AJAX Category Filter
	function load_products () {
		global $wp_query;
		$cat_id=$_POST['filter_type_category'];
		
		$paged = $_POST['paged'];
		//$offset = (intval($_POST['offset']) != 0 ) ? $_POST['offset'] : 0;

		$args=array(
			'post_type' =>'product',
			//'posts_per_page' => 12,
			'order' => 'DESC',
			'paged'=>$paged
		);
		if($cat_id !='') {
			$taxonomy_cat='product_cat';
			$term = get_term_by( 'id', $cat_id, $taxonomy_cat );
			//print_r($term);
			$args[$taxonomy_cat]= $term->slug;
		}else {
			$args[$taxonomy_cat]= 'single-herb-tincture';
		}
		ob_start ();
		$query=new WP_Query($args);
		if($query->have_posts()):
			while($query->have_posts()):$query->the_post();
		?>	
			<?php get_template_part( 'woocommerce/content-product', 'woocommerce'); ?>
		<?php
			endwhile;
		endif;wp_reset_query();
		$response = ob_get_contents();
		ob_end_clean();
		echo $response;
		die();
	}
	add_action( 'wp_ajax_load_products', 'load_products' );
	add_action( 'wp_ajax_nopriv_load_products', 'load_products' );
?>