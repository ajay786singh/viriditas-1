<?php
//add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/*
* Function to get prodcut categories
*/
function get_product_categories($exclude=false) {
	$result="";
	$pc = ($_REQUEST['pc'] ? $_REQUEST['pc'] : '');
	$product_categories = get_terms('product_cat', 'orderby=count&order=desc&hide_empty=1&hierarchical=0&parent=0&exclude='.$exclude);
	if($product_categories) {	
		//$result='<div class="shop-header">';
			//$result.='<h6 class="heading">Filter by category</h6>';
		//$result.='</div>';
		$result.='<select class="by-category filter">';
		$result.='<option value="" class="hidden-option">PRODUCT CATEGORY</option>';
		$result.="<option value=''>Show all products</option>";
			foreach($product_categories as $product_category) {
				$category_name = $product_category->name;	
				if($product_category->term_id == '1391') {
					$category_name = $category_name." / Add to a combination";
				}
				if($pc!='' && $pc == $product_category->term_id) {
					$result.="<option selected value='".$product_category->term_id."'>".$category_name."</option>";
				}else {
					$result.="<option value='".$product_category->term_id."'>".$category_name."</option>";
				}
			}
			$result.="<option value='2219'>Create your own compound</option>";
		$result.='</select>';
	}
	echo $result;
}

/*
* Function to get all products
*/
function load_products () {
	global $wp_query,$wpdb;
	$current_user = wp_get_current_user();
	$cat_id=$_POST['filter_type_category'];
	$body_system_id=$_POST['filter_type_body_system'];
	$action_id = $_POST['filter_type_action'];
	$indication_id=$_POST['filter_type_indication'];
	$keyword=$_POST['search_folk'];
	$sort_by=$_POST['sort_by'];
	$sort_by_alpha=$_POST['sort_by_alpha'];
	$order=$_POST['order'];
	$paged = $_POST['paged'];
	$view_mode = $_POST['view_mode'];
	if($action_id !='') {
		$body_system_id=$action_id;
	}
	if($view_mode=='') {
		$view_mode='thumb_view';
	}
	$args=array(
		'post_type' =>'product',
		'orderby' => 'title',
		'order' => 'ASC',
		'paged'=>$paged,
		'posts_per_page'=>36,
		'post_status'=>array('publish')
	);
	if($order !='') {
		$args['order']=$order;
	}else {
		$args['order']='ASC';
	}
	// $args['meta_key'] ='_allowed_bundle_user';
	
	if($sort_by!=''){
		if($sort_by=='folk_name') {
			$args['meta_key'] = '_product_details_folk_name';
			$args['meta_type']  = 'CHAR';
			$args['orderby']  = array( 'meta_value' => 'ASC','title'=>'ASC' );
			$args['order']  = 'ASC';
			$args['meta_query'] = array(
				array(
				   'key' => '_product_details_folk_name',
				   'value' => '' ,
				   'compare' => '!='
				)
			);
		} else {
			$args['orderby']=$sort_by;
		}
	}
	if($keyword !='') {
		if($sort_by != '' && $sort_by == 'folk_name') {
			//echo $sort_by;
			$args['meta_query'] = array(
				array(
				   'key' => '_product_details_folk_name',
				   'value' => $keyword ,
				   'compare' => 'LIKE'
				)
			);
		} else {
			$args['s'] = $keyword;	
		}
	}
	$compound_ids='';	
	if($cat_id==1391) {	
		$post_type='product';
		$no_of_posts='-1';
		$taxonomy='product_cat';
		$argss=array(
			'post_type' => $post_type,
			'numberposts' => $no_of_posts,
		);
		
		$argss['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => '1391'
			),
		);
		
		$results =get_posts( $argss ); 
		if($results): 
			foreach($results as $result) {
				$hide_user= get_post_meta($result->ID,'_allowed_bundle_user',true);
				if($hide_user != '') {
					$compound_ids[] = $result->ID;
				}
			}
			$args['post__not_in']=$compound_ids;
		endif;
	}
	if($sort_by_alpha !='') {
		if($sort_by!='' && $sort_by=='folk_name') {
			$postids = $wpdb->get_col("
				SELECT wposts.ID 
				FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
				WHERE wposts.ID = wpostmeta.post_id 
				AND wpostmeta.meta_key = '_product_details_folk_name' 
				AND wpostmeta.meta_value LIKE '".$wpdb->escape($sort_by_alpha)."%'
				AND wposts.post_type = 'product' 
				ORDER BY wpostmeta.meta_value ASC
				");
		} else {
			$postids = $wpdb->get_col("
				SELECT p.ID
				FROM $wpdb->posts p
				WHERE p.post_title REGEXP '^" . $wpdb->escape($sort_by_alpha) . "'
				AND p.post_status = 'publish' 
				AND p.post_type = 'product'
				ORDER BY p.post_title ASC"
			);
		}	
		unset($args['post__in']);
		if($postids !='' && $compound_ids!='') {
			$args['post__in']=array_diff($postids,$compound_ids);
		} else { 
			if($postids !='') {
				$args['post__in'] = $postids;
			}
		}
	}
	
	$filter=array(
		'product_cat'=>array_filter(array($cat_id)),
		'body_system'=>array_filter(array($body_system_id)),
		'indication'=>array_filter(array($indication_id))
	);
	$filter_terms=array_filter($filter);
	if(count($filter_terms)== 1) {
		foreach ($filter_terms as $key => $value) {
			$args['tax_query'] = array(
					array(
					'taxonomy' => $key,
					'field' => 'term_id',
					'terms' => $value,
					)
			);		
		}
	} else if(count($filter_terms) > 1){
		$tax_query="";
		$tax_query['relation'] = 'AND';
		foreach ($filter_terms as $key => $value) {
			$tax_query[] = array(
					'taxonomy' => $key,
					'field' => 'term_id',
					'terms' => $value,
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
		<li class="equal-height-item" id="product-<?php echo get_the_ID();?>">		
			<?php get_template_part( 'woocommerce/content-product', 'woocommerce');?>
		</li>
	<?php
		endwhile;
		echo "</ul>";
			if($next!='' && $paged < $max_pages) {
				$class="";
				if($view_mode !='' && $view_mode =='list_view') {
					$class="list_view_mode";
				}
				echo "<input type='hidden' id='current-page' value='".$paged."'>";
				echo "<div class='load-more ".$class."'><a href='#'>Load more products</a></div>";
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
	$pa=$_POST['active_val'];
	$pb=$_POST['pb'];
	$taxonomies = array( 'body_system' );
	$args = array(
		'hide_empty'        => true, 
		'hierarchical'      => true, 
		'child_of'          => 0,
	); 
	if($pb!='') {
		$args['child_of']=$pb;
	}
	$terms = get_terms( $taxonomies, $args );
	if($terms) {
		$result='<div class="filter filter-actions">';
		$result.='<select class="by-actions">';
			$result.='<option value="" class="hidden-option">FILTER BY ACTIONS </option>';
			$result.="<option value=''>Show all</option>";
		foreach($terms as $term) {
			if($pa==$term->term_id) {
				$result.="<option selected value='".$term->term_id."'>".$term->name."</option>";
			} else {
				$result.="<option value='".$term->term_id."'>".$term->name."</option>";
			}
		}
		$result.='</select>';	
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
	$html='';
	$args = array( 'p' => $id,'post_type'=>'product' );
	$post_query = new WP_Query( $args );
	$product_page_url=get_bloginfo('url')."/products"; 
	if($post_query->have_posts()): while( $post_query->have_posts() ) : $post_query->the_post();
		$id=get_the_ID();
		$single_product_url=$product_page_url."?show_product=".$id;
		//$html.="<a href='".get_the_permalink($id)."'><i>".get_the_title()."</i></a>";
		$html.="<span><i>".get_the_title()."</i></span>";
	endwhile; endif; wp_reset_query();
	return $html;
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
	$pc='327';
	if($_POST['pc']!='') {
		$pc=$_POST['pc'];
	}
	$pi=$_POST['pi'];
	$filters=array_filter(array('body_system'=>$pa,'body_system'=>$pb,'product_cat'=>$pc,'indication'=>$pi));
	$args=array(
		'post_type' => $post_type,
		'numberposts' => $no_of_posts,
	);
	$tax_query="";
	if(count($filters)> 0) {
		$tax_query['relation']='AND';
		foreach($filters as $category=>$cat_value) {
			$tax_query[]=array(
				'taxonomy' => $category,
				'field' => 'term_id',
				'terms' => $cat_value
			);	
		}
		$args['tax_query'] = $tax_query;
	}
	
	$objects_ids='';	
	$objects = get_posts( $args );
		foreach ($objects as $object) {
			$objects_ids[] = $object->ID;
		}
	$terms = wp_get_object_terms( $objects_ids, $taxonomy );
		if($terms) {
			$result='<div class="filter filter-'.$taxonomy.'">';
			$result.='<select class="by-'.$taxonomy.'">';
				$result.='<option value="" class="hidden-option">FILTER BY '.strtoupper($taxonomy_name).'</option>';
				$result.="<option value=''>Show all</option>";
			foreach($terms as $term) {
				if($taxonomy=='body_system') {
					$parent=$term->parent;
					if($parent==0) {
						if($pb==$term->term_id) {
							$result.="<option selected value='".$term->term_id."'>".$term->name."</option>";
						} else {
							$result.="<option value='".$term->term_id."'>".$term->name."</option>";
						}
					}
				} else {
					if($taxonomy=='indication' && $pi!='') {
						if($pi==$term->term_id) {
							$result.="<option selected value='".$term->term_id."'>".$term->name."</option>";
						} else {
							$result.="<option value='".$term->term_id."'>".$term->name."</option>";
						}
					} else {
						$result.="<option value='".$term->term_id."'>".$term->name."</option>";
					}	
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

//Change the placeholder image in WooCommerce
add_action( 'init', 'ccw_custom_woo_placeholder' );
function ccw_custom_woo_placeholder(){
	add_filter('woocommerce_placeholder_img_src','ccw_custom_placeholder_callback');
	function ccw_custom_placeholder_callback($src){
		$src = get_bloginfo('template_url')."/dist/images/product_default.jpg";
		return $src;
	}
}

/*
* Show Compound List
*/

function show_compound_products() {
	global $wp_query,$wpdb;
	$body_system_id=$_POST['filter_type_body_system'];
	$action_id = $_POST['filter_type_action'];
	$keyword=$_POST['search_folk'];
	$sort_by_alpha=$_POST['sort_by_alpha'];
	$sort_by=$_POST['sort_by'];
	$compound_id=$_POST['compound_id'];
	$mono_compound_id=$_POST['mono_compound_id'];
	$bundle_herbs="";
	$postids="";
	
	if($action_id !='') {
		$body_system_id=$action_id;
	}
	$args=array(
		'post_type' =>'product',
		'orderby' => 'title',
		'order' => 'ASC',
		'paged'=>$paged,
		'showposts'=>-1,
		'post_status'=>array('publish')
	);
	
	if($compound_id!="") {
		$bundle_data=get_post_meta($compound_id,'_bundle_data',true);
		if($bundle_data !='') {
			foreach($bundle_data as $bundle_herb_id => $bundle_herb_values ) {
				$bundle_herbs[]= $bundle_herb_id;
			}
			$args['post__not_in']=$bundle_herbs;
		}
	}
	
	if($mono_compound_id!="") {
		$bundle_data=get_post_meta($mono_compound_id,'_monograph_details_composition',true);
		if($bundle_data !='') {
			foreach($bundle_data as $bundle_herb_id) {
				$bundle_herbs[]= $bundle_herb_id;
			}
			$args['post__not_in']=$bundle_herbs;
		}
	}
	
	if($sort_by!=''){
		if($sort_by=='folk_name') {
			$args['meta_key'] = '_product_details_folk_name';
			$args['meta_type']  = 'CHAR';
			$args['orderby']  = array( 'meta_value' => 'ASC','title'=>'ASC' );
			$args['order']  = 'ASC';
			$args['meta_query'] = array(
				array(
				   'key' => '_product_details_folk_name',
				   'value' => '' ,
				   'compare' => '!='
				)
			);
		} else {
			$args['orderby']=$sort_by;
		}
	}
	
	if($keyword !='') {
		if($sort_by != '' && $sort_by == 'folk_name') {
			//echo $sort_by;
			$args['meta_query'] = array(
				array(
				   'key' => '_product_details_folk_name',
				   'value' => $keyword ,
				   'compare' => 'LIKE'
				)
			);
		} else {
			$args['s'] = $keyword;	
		}
	}
	
	$filter=array(
		'body_system'=>array_filter(array($body_system_id)),
		'product_cat'=>array_filter(array(327)),
	);
	
	if($sort_by_alpha !='') {
		if($sort_by!='' && $sort_by=='folk_name') {
			$postids = $wpdb->get_col("
				SELECT wposts.ID 
				FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
				WHERE wposts.ID = wpostmeta.post_id 
				AND wpostmeta.meta_key = '_product_details_folk_name' 
				AND wpostmeta.meta_value LIKE '".$wpdb->escape($sort_by_alpha)."%'
				AND wposts.post_type = 'product' 
				ORDER BY wpostmeta.meta_value ASC
				");
		} else {
			$postids = $wpdb->get_col("
				SELECT p.ID
				FROM $wpdb->posts p
				WHERE p.post_title REGEXP '^" . $wpdb->escape($sort_by_alpha) . "'
				AND p.post_status = 'publish' 
				AND p.post_type = 'product'
				ORDER BY p.post_title ASC"
			);
		}	
		if($postids) {
			$args['post__in']=$postids;
		}
	}
	
	if($bundle_herbs !='' && $postids !='') {
		unset($args['post__not_in']);
		unset($args['post__in']);
		$args['post__in']=array_diff($postids, $bundle_herbs);
	}
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
	if($query->have_posts()){
		while($query->have_posts()):$query->the_post();
		$product_id=get_the_ID();
			$expensive_herb = get_post_meta($product_id,'_product_details_expensive_herb',true);
			$data_pricy="";
			if($expensive_herb == 'on') {
				$data_pricy="*";
			}
	?>	
		<li id="product-<?php echo get_the_ID();?>">
			<a href="#" id="compound-<?php echo get_the_ID();?>" data-pricy="<?php echo $data_pricy;?>" data-id="<?php echo get_the_ID();?>" data-name="<?php the_title();?>" class="compound-product">
			
			<?php 
				$title = "<em>".get_the_title().$data_pricy."</em>";
				if($sort_by!='' && $sort_by=='folk_name') {
					$folk_name=get_post_meta(get_the_ID(),'_product_details_folk_name',true);
					if($folk_name) {
						echo $folk_name."<br>";
						echo $title;
					} else { 
						echo $title;
					}
				} else {
					echo $title;
				}
			?>
			</a>
		</li>
	<?php
		endwhile;
	} else {
		echo "1";
	}
	wp_reset_query();
	$response = ob_get_contents();
	ob_end_clean();
	echo $response;
	die();
}
add_action( 'wp_ajax_show_compound_products', 'show_compound_products' );
add_action( 'wp_ajax_nopriv_show_compound_products', 'show_compound_products' );