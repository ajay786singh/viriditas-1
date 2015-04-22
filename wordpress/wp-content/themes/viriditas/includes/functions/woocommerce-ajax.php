<?php
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/*
* Function to get prodcut categories
*/
function get_product_categories($exclude=false) {
	$result="";
	$pc=$_REQUEST['pc'];
	$product_categories = get_terms('product_cat', 'orderby=count&order=desc&hide_empty=1&hierarchical=0&parent=0&exclude='.$exclude);
	if($product_categories) {	
		$result='<div class="shop-header">';
			$result.='<h6 class="heading">Filter by category</h6>';
		$result.='</div>';
		$result.='<select class="by-category">';
		$result.='<option value="" class="hidden-option">Select category</option>';
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
	$action_id = $_POST['filter_type_action'];
	$indication_id=$_POST['filter_type_indication'];
	$keyword=$_POST['search_folk'];
	$sort_by=$_POST['sort_by'];
	$order=$_POST['order'];
	$paged = $_POST['paged'];
	$view_mode = $_POST['view_mode'];
	if($view_mode=='') {
		$view_mode='thumb_view';
	}
	$args=array(
		'post_type' =>'product',
		'orderby' => 'title',
		'order' => 'ASC',
		'paged'=>$paged,
		'posts_per_page'=>12
	);
	if($order !='') {
		$args['order']=$order;
	}else {
		$args['order']='ASC';
	}
	if($sort_by!=''){
		if($sort_by=='folk_name') {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = '_product_details_folk_name';
		} else {
			$args['orderby']=$sort_by;
		}
	}
	
	if($keyword !='') {
		$args['meta_query'] = array(
            array(
               'key' => '_product_details_folk_name',
               'value' => $keyword,
               'compare' => 'LIKE'
            )
        );
	}
	
	$filter=array(
		'product_cat'=>array_filter(array($cat_id)),
		'body_system'=>array_filter(array($body_system_id)),
		'actions'=>array_filter(array($action_id)),
		'indication'=>array_filter(array($indication_id))
	);
	$filter_terms=array_filter($filter);
	if(count($filter_terms)== 1) {
		foreach ($filter_terms as $key => $value) {
			$args['tax_query'] = array(
					array(
					'taxonomy' => $key,
					'terms' => $value,
					'field' => 'term_id',
					)
			);		
		}
	} else if(count($filter_terms) > 1){
		$tax_query="";
		$tax_query['relation'] = 'AND';
		foreach ($filter_terms as $key => $value) {
			$tax_query[] = array(
					'taxonomy' => $key,
					'terms' => $value,
					'field' => 'term_id',
				);				
		}
		$args['tax_query'] = $tax_query;
	}
	ob_start ();
	$query=new WP_Query($args);
	$max_pages=$query->max_num_pages;
	if($query->have_posts()){
		$next = get_next_posts_link('Older', $max_pages);
		echo '<ul class="'.$view_mode.'">';
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
	$product_page_url=get_bloginfo('url')."/products"; 
	if($post_query->have_posts()): while( $post_query->have_posts() ) : $post_query->the_post();
		$id=get_the_ID();
		$single_product_url=$product_page_url."?show_product=".$id;
		echo "<li class='block-grid-3'><a href='".$single_product_url."'><i>".get_the_title()."</i></a></li>";
	endwhile; endif; wp_reset_query();
}

/*
* Function to get terms
* @param: Taxonomy
*/

function get_product_terms() {
	global $wp_query;
	$post_type='product';
	$no_of_posts='-1';
	$taxonomy=$_POST['taxonomy'];
	$taxonomy_name=implode(" ",explode("_",$taxonomy));
	$pa=$_POST['pa'];
	$pb=$_POST['pb'];
	$pc=$_POST['pc'];
	$pi=$_POST['pi'];
	
	$args=array(
		'post_type' => $post_type,
		'numberposts' => $no_of_posts,
	);
	if($pc !='') {
		$tax_query=array(
			'taxonomy' => 'product_cat',
			'field' => 'term_id',
			'terms' => $pc
		);
		$args['tax_query'] = array(
			'relation'=>'AND',
			$tax_query
		);
	}
	$objects_ids='';	
	$objects = get_posts( $args );
		foreach ($objects as $object) {
			$objects_ids[] = $object->ID;
		}
	$terms = wp_get_object_terms( $objects_ids, $taxonomy );
		if($terms) {
			$result='<div class="filter filter-'.$taxonomy.'">';
			$result.='<div class="shop-header">';
			$result.='<h6 class="heading">Filter by '.$taxonomy_name.'</h6>';
			$result.='</div>';
			$result.='<select class="by-'.$taxonomy.'">';
				$result.='<option value="" class="hidden-option">Select '.$taxonomy_name.'</option>';
			foreach($terms as $term) {
				if($taxonomy=='body_system' && $pb!='') {
					if($pb==$term->term_id) {
						$result.="<option selected value='".$term->term_id."'>".$term->name."</option>";
					} else {
						$result.="<option value='".$term->term_id."'>".$term->name."</option>";
					}
				} else if($taxonomy=='indication' && $pi!='') {
					if($pi==$term->term_id) {
						$result.="<option selected value='".$term->term_id."'>".$term->name."</option>";
					} else {
						$result.="<option value='".$term->term_id."'>".$term->name."</option>";
					}
				}else if($taxonomy=='actions' && $pa!='') {
					if($pa==$term->term_id) {
						$result.="<option selected value='".$term->term_id."'>".$term->name."</option>";
					} else {
						$result.="<option value='".$term->term_id."'>".$term->name."</option>";
					}
				} else {
					$result.="<option value='".$term->term_id."'>".$term->name."</option>";
				}
			}
			$result.='</select>';	
			$result.='</div>';	
		}
	echo $result;	
	die(0);
}
add_action( 'wp_ajax_get_product_terms', 'get_product_terms' );
add_action( 'wp_ajax_nopriv_get_product_terms', 'get_product_terms' );