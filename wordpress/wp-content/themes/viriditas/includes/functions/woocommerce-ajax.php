<?php
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/*
* Function to get prodcut categories
*/
function get_product_categories($exclude=false) {
	$reslut="";
	$pc=$_REQUEST['pc'];
	$product_categories = get_terms('product_cat', 'orderby=count&order=desc&hide_empty=1&hierarchical=0&parent=0&exclude='.$exclude);
	if($product_categories) {	
		$result='<div class="shop-header">';
			$result.='<h6 class="heading">Filter by category</h6>';
		$result.='</div>';
		$result.='<select class="by-category">';
			foreach($product_categories as $product_category) {
				if($pc!='' && $pc== $product_category->term_id) {
					$result.="<option selected value='".$product_category->term_id."'>".$product_category->name."</option>";
				}else {
					$result.="<option value='".$product_category->term_id."'>".$product_category->name."</option>";
				}
			}
		$result.='</select>';
	}
	echo $result;
}

/*
* Function to get all products
*/
function load_products () {
	global $wp_query;
	$cat_id=$_POST['filter_type_category'];
	$body_system_id=$_POST['filter_type_body_system'];
	$action_id=$_POST['filter_type_action'];
	$indication_id=$_POST['filter_type_indication'];
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
	
	$indication="";
	if($indication_id !='') {
		$indication_term = get_term_by( 'id', $indication_id, 'indication' );
		$filter_terms['indication'] = $indication_term->slug;	
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
	} else if(count($filter_terms) > 1){
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
	$max_pages=$query->max_num_pages;
	if($query->have_posts()){
		$next = get_next_posts_link('Older', $max_pages);
		echo '<ul>';
		while($query->have_posts()):$query->the_post();
	?>	
		<li id="product-<?php echo get_the_ID();?>">		
			<?php get_template_part( 'woocommerce/content-product', 'woocommerce'); ?>
		</li>
	<?php
		endwhile;
		echo "</ul>";
			if($next!='' && $paged < $max_pages) {
				echo "<input type='hidden' id='current-page' value='".$paged."'>";
				echo "<div class='load-more'><a href='#'>Load more products</a></div>";
			}
	} else {
		echo "1";
	}
	wp_reset_query();
	$response = ob_get_contents();
	ob_end_clean();
	echo $response;
	die();
}
add_action( 'wp_ajax_load_products', 'load_products' );
add_action( 'wp_ajax_nopriv_load_products', 'load_products' );

/*
* Function to get actions 
* @param: Category, Body system, Indication
*/
function get_actions() {
	global $wp_query;
	$cat=$_POST['category'];
	$category = get_term_by('name', $cat, 'product_cat');
	
	$body_system_id=$_POST['body_system'];
	$args=array(
			'post_type' => 'product',
			'numberposts' => -1,
			'tax_query' => array(
				'relation'=>'AND',
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $cat
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
		$result='<div class="filter filter-actions">';
		$result.='<div class="shop-header">';
		$result.='<h6 class="heading">Filter by Actions</h6>';
		$result.='</div>';
		$result.='<ul>';	
		foreach($actions as $action) {
			$action_match = explode(" ",$action->name);	
			if($action_match[0]==$body_match[0]) {
				$result.="<li><a href='#' data-value='".$action->term_id."'>".$action->name."</a></li>";
			}
		}
		$result.='</ul>';
		$result.='</div>';
	}
	echo $result;
	die();
}
add_action( 'wp_ajax_get_actions', 'get_actions' );
add_action( 'wp_ajax_nopriv_get_actions', 'get_actions' );	

/*
* Function to get Body Systems 
* @param: Category, Actions, Indication
*/

function get_body_systems() {
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
		$result='<div class="filter filter-body_system">';
		$result.='<div class="shop-header">';
		$result.='<h6 class="heading">Filter by Body System</h6>';
		$result.='</div>';
		$result.='<select class="by-body_system">';
			$result.='<option value="" class="hidden-option">Select Body System</option>';
		foreach($body_systems as $body_system) {
			
			$result.="<option value='".$body_system->term_id."'>".$body_system->name."</option>";
		}
		$result.='</select>';	
		$result.='</div>';	
	}
	echo $result;
	die();
}
add_action( 'wp_ajax_get_body_systems', 'get_body_systems' );
add_action( 'wp_ajax_nopriv_get_body_systems', 'get_body_systems' );	

/*
* Function to get Indications 
* @param: Category, Body System, Actions
*/
function get_indications() {
	global $wp_query;
	$cat_id=$_POST['category'];
	if($cat_id ==''){
		$cat_id = 327;
	}
	$args=array(
		'post_type' => 'product',
		'numberposts' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => array($cat_id)
			)
		)
	);
	$objects_ids='';	
	$objects = get_posts( $args );
	foreach ($objects as $object) {
		$objects_ids[] = $object->ID;
	}
	$indications = wp_get_object_terms( $objects_ids, 'indication');
	if($indications) { 
		$result='<div class="filter filter-indication">';
		$result.='<div class="shop-header">';
		$result.='<h6 class="heading">Filter by Indication</h6>';
		$result.='</div>';
		$result.='<select class="by-indication">';
		$result.='<option value="" class="hidden-option">Select Indication</option>';
		foreach($indications as $indication) {
			$result.="<option value='".$indication->term_id."'>".$indication->name."</option>";
		} 
		$result.='</select>';
		$result.='</div>';
	}
	echo $result;
	if($_POST['category']) {
		die(0);
	}
}

add_action( 'wp_ajax_get_indications', 'get_indications' );
add_action( 'wp_ajax_nopriv_get_indications', 'get_indications' );	

/*
* Function to get Product details with AJAX
* @param: request product id
*/
function get_product_detail() {
	global $wp_query;
	$post_id=$_POST['product_id'];
	$args = array( 'p' => $post_id,'post_type'=>'product' );
	$post_query = new WP_Query( $args );
		while( $post_query->have_posts() ) : $post_query->the_post();
			get_template_part( 'woocommerce/content-single-product', 'woocommerce');
		endwhile; wp_reset_query();
	die(0);
}
add_action( 'wp_ajax_get_product_detail', 'get_product_detail' );
add_action( 'wp_ajax_nopriv_get_product_detail', 'get_product_detail' );	

/*
* Function to get Product details without AJAX
* @param: request product id
*/
function get_product_info($id) {
	global $wp_query;
	$args = array( 'p' => $id,'post_type'=>'product' );
	$post_query = new WP_Query( $args );
	if($post_query->have_posts()): while( $post_query->have_posts() ) : $post_query->the_post();
		echo "<li class='block-grid-3'><a href='".get_permalink()."'><i>".get_the_title()."</i></a></li>";
	endwhile; endif; wp_reset_query();
}