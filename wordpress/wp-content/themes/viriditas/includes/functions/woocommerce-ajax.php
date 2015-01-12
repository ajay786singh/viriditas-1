<?php
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	//AJAX Category Filter
	function load_products () {
		global $wp_query;
		$cat_id=$_POST['filter_type_category'];
		$body_system_id=$_POST['filter_type_body_system'];
		$action_id=$_POST['filter_type_action'];
		$sort_by_name=$_POST['sort_by_name'];
		$paged = $_POST['paged'];
		
		$args=array(
			'post_type' =>'product',
			'order' => 'DESC',
			'paged'=>$paged,
			'posts_per_page'=>12
		);
		if($sort_by_name!=''){
			$args['orderby']='title';
			$args['order']=$sort_by_name;
		}
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
			if(count($action_id)==1) {
				$action_term = get_term_by( 'id', $action_id[0], 'actions' );
				$filter_terms['actions'] = $action_term->slug;
			}else {
				$actions_slug='';
				for($i=0;$i<count($action_id);$i++) {
					$action_term = get_term_by( 'id', $action_id[$i], 'actions' );
					$actions_slug[] = $action_term->slug;
				}
				$filter_terms['actions'] = implode(",",$actions_slug);
			}
		}
		
		if(count($filter_terms)== 1) {
			foreach ($filter_terms as $key => $value) {
				$args['tax_query'] = array(
						array(
						'taxonomy' => $key,
						'terms' => explode(',',$value),
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
						'terms' => explode(',',$value),
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
function load_actions() {
	global $wp_query;
	
	$cat_id=$_POST['category'];
	$body_system_id=$_POST['body_system'];
	$args=array(
			'post_type' => 'product',
			'numberposts' => -1,
			'tax_query' => array(
				'relation'=>'AND',
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $cat_id
				),
				array(
					'taxonomy' => 'body_system',
					'field' => 'term_id',
					'terms' => $body_system_id
				)
			)
		);
	$objects_ids='';
	$body_system_term = get_term_by( 'id', $body_system_id, 'body_system' );
	$body_match = explode(" ",$body_system_term->name);	
	
	$objects = get_posts( $args );
	foreach ($objects as $object) {
		$objects_ids[] = $object->ID;
	}
	$actions = wp_get_object_terms( $objects_ids, 'actions' );
	if($actions) {
		$result='<h6>Select Action</h6>';	
		foreach($actions as $action) {
			$action_match = explode(" ",$action->name);	
			if($action_match[0]==$body_match[0]) {
				$result.="<label><input type='checkbox' name='actions[]' class='by-action' value='".$action->term_id."'><span>".$action->name."</span></label>";
			}
		}
	}
	echo $result;
	die();
}
add_action( 'wp_ajax_load_actions', 'load_actions' );
add_action( 'wp_ajax_nopriv_load_actions', 'load_actions' );	

add_action( 'wp_ajax_load_body_systems', 'load_body_systems' );
add_action( 'wp_ajax_nopriv_load_body_systems', 'load_body_systems' );	
function load_body_systems() {
	global $wp_query;
	
	$cat_id=$_POST['category'];
	$args=array(
			'post_type' => 'product',
			'numberposts' => -1,
			'tax_query' => array(
				'relation'=>'AND',
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $cat_id
				)
			)
		);
	$objects_ids='';	
	$objects = get_posts( $args );
	foreach ($objects as $object) {
		$objects_ids[] = $object->ID;
	}
	$body_systems = wp_get_object_terms( $objects_ids, 'body_system' );
	if($body_systems) {
		$result='<select class="by-body_system">';
			$result.='<option value="">Select Body System</option>';
		foreach($body_systems as $body_system) {
			$result.="<option value='".$body_system->term_id."'>".$body_system->name."</option>";
		}
		$result.='</select>';	
	}
	echo $result;
	die();
}
?>