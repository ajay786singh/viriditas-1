<?php
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	//AJAX Category Filter
	function load_products () {
		global $wp_query;
		$cat_id=$_POST['filter_type_category'];
		$body_system_id=$_POST['filter_type_body_system'];
		$action_id=$_POST['filter_type_action'];
		
		$paged = $_POST['paged'];
		
		$args=array(
			'post_type' =>'product',
			'order' => 'DESC',
			'paged'=>$paged
		);
		
		$product_cat="";
		if($cat_id !='') {
			$cterm = get_term_by( 'id', $cat_id, 'product_cat' );
			$filter_terms['product_cat'] = $cterm->slug;	
		}
		$body_system="";
		if($body_system_id !='') {
			$body_system_term = get_term_by( 'id', $body_system_id, 'body_system' );
			$filter_terms['body_system'] = $body_system_term->slug;	
		}
		$action="";
		if($action_id !='') {
			$action_term = get_term_by( 'id', $action_id, 'actions' );
			$filter_terms['actions'] = $action_term->slug;	
		}
		
		if(count($filter_terms)== 1) {
			foreach ($filter_terms as $key => $value) {
				$args['tax_query'] = array(
						array(
						'taxonomy' => $key,
						'terms' => array($value),
						'field' => 'slug',
						)
				);	
				
			}
		}else if(count($filter_terms) > 1){
			$tax_query="";
			$tax_query['relation'] = 'AND';
			foreach ($filter_terms as $key => $value) {
				$tax_query[] = array(
								'taxonomy' => $key,
								'terms' => array($value),
								'field' => 'slug',
							);
				
			}
			$args['tax_query'] = $tax_query;
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