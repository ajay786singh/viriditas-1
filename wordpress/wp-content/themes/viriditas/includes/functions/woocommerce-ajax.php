<?php
//add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/*
* Function to get prodcut categories
*/
function get_product_categories($exclude=false) {
	$result="";
	$pc=$_REQUEST['pc'];
	$product_categories = get_terms('product_cat', 'orderby=count&order=desc&hide_empty=1&hierarchical=0&parent=0&exclude='.$exclude);
	if($product_categories) {	
		//$result='<div class="shop-header">';
			//$result.='<h6 class="heading">Filter by category</h6>';
		//$result.='</div>';
		$result.='<select class="by-category filter">';
		$result.='<option value="" class="hidden-option">PRODUCT CATEGORY</option>';
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
	global $wp_query,$wpdb;
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
	if($sort_by!=''){
		if($sort_by=='folk_name') {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = '_product_details_folk_name';
		} else {
			$args['orderby']=$sort_by;
		}
	}
	
	if($keyword !='') {
		$args['s'] = $keyword;
		// $args['meta_query'] = array(
            // array(
               // 'key' => '_product_details_folk_name',
               // 'value' => $keyword,
               // 'compare' => 'LIKE'
            // )
        // );
	}
	
	if($sort_by_alpha !='') {
		//$args['s'] = $sort_by_alpha;
		$postids = $wpdb->get_col("
			SELECT p.ID
			FROM $wpdb->posts p
			WHERE p.post_title REGEXP '^" . $wpdb->escape($sort_by_alpha) . "'
			AND p.post_status = 'publish' 
			AND p.post_type = 'product'
			ORDER BY p.post_title ASC"
		);
		if($postids) {
			$args['post__in']=$postids;
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
	//echo $count=$query->found_posts;
	$max_pages=$query->max_num_pages;
	if($query->have_posts()){
		$next = get_next_posts_link('Older', $max_pages);
		echo '<ul class="'.$view_mode.'">';
		while($query->have_posts()):$query->the_post();
	?>	
		<li class="equal-height-item" id="product-<?php echo get_the_ID();?>">		
			<?php get_template_part( 'woocommerce/content-product', 'woocommerce'); ?>
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
		$html.="<a href='".get_the_permalink($id)."'><i>".get_the_title()."</i></a>";
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
			foreach($terms as $term) {
				// if($taxonomy!='body_system' && $parent<=0) {
					// if($taxonomy=='body_system' && $pb!='') {
						// if($pb==$term->term_id) {
							// $result.="<option selected value='".$term->term_id."'>".$term->name."</option>";
						// } else {
							// $result.="<option value='".$term->term_id."'>".$term->name."</option>";
						// }
					// } else if($taxonomy=='indication' && $pi!='') {
						// if($pi==$term->term_id) {
							// $result.="<option selected value='".$term->term_id."'>".$term->name."</option>";
						// } else {
							// $result.="<option value='".$term->term_id."'>".$term->name."</option>";
						// }
					// }else if($taxonomy=='actions' && $pa!='') {
						// if($pa==$term->term_id) {
							// $result.="<option selected value='".$term->term_id."'>".$term->name."</option>";
						// } else {
							// $result.="<option value='".$term->term_id."'>".$term->name."</option>";
						// }
					// } else {
						// $result.="<option value='".$term->term_id."'>".$term->name."</option>";
					// }
				// }
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
	//echo "<pre>"; print_r($_POST); echo "</pre>";
	$body_system_id=$_POST['filter_type_body_system'];
	$action_id = $_POST['filter_type_action'];
	$keyword=$_POST['search_folk'];
	$sort_by_alpha=$_POST['sort_by_alpha'];
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
	
	if($keyword !='') {
		$args['s'] = $keyword;
		// $args['meta_query'] = array(
            // array(
               // 'key' => '_product_details_folk_name',
               // 'value' => $keyword,
               // 'compare' => 'LIKE'
            // )
        // );
	}
	
	$filter=array(
		'body_system'=>array_filter(array($body_system_id)),
		'product_cat'=>array_filter(array(327)),
	);
	
	if($sort_by_alpha !='') {
		//$args['s'] = $sort_by_alpha;
		$postids = $wpdb->get_col("
			SELECT p.ID
			FROM $wpdb->posts p
			WHERE p.post_title REGEXP '^" . $wpdb->escape($sort_by_alpha) . "'
			AND p.post_status = 'publish' 
			AND p.post_type = 'product'
			ORDER BY p.post_title ASC"
		);
		if($postids) {
			$args['post__in']=$postids;
		}
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
	//echo $count=$query->found_posts;
	$max_pages=$query->max_num_pages;
	if($query->have_posts()){
		$next = get_next_posts_link('Older', $max_pages);
		echo '<ul class="product-list">';
		while($query->have_posts()):$query->the_post();
	?>	
		<li id="product-<?php echo get_the_ID();?>">
			<a href="#" id="compound-<?php echo get_the_ID();?>" data-id="<?php echo get_the_ID();?>" data-name="<?php the_title();?>" class="compound-product"><?php the_title();?></a>
		</li>
	<?php
		endwhile;
		echo "</ul>";
		echo '<ul class="alphabets-list">';
				$alphas = range('A', 'Z');
				foreach($alphas as $alphabet) {
					if($_REQUEST['sort_by_alpha']==lcfirst($alphabet)) {
						echo "<li><a href='#' id='sort-".lcfirst($alphabet)."' class='active'>".$alphabet."</li>";
					}else {
						echo "<li><a href='#' id='sort-".lcfirst($alphabet)."'>".$alphabet."</li>";
					}
				}
		echo '</ul>';
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

/*
*  Function to Manage Recipe By User(s)
*/

function manage_compound() {
	$current_user = wp_get_current_user();
	$errors = array();
	if ( $current_user->ID != 0 ) {
		//logged in.			
		$action = trim($_POST['form_type']);
		$title = trim($_POST['title']);
		$size = trim($_POST['size']);
		$price = trim($_POST['price']);
		$price_per_unit = number_format(($price/$size),2, '.', '');
		$compound_products = trim($_POST['compound_products']);
		
		//check for title not blank
		if (strlen($title) == 0) {
			array_push($errors, "Please enter your recipe name."); 
		}
		
		//Check for selected herbs
		$herbs = array();
		if($compound_products ==0) {
			array_push($errors, "Please add herbs your recipe."); 
		} else {
			$compound_products=explode(",",$compound_products);
			foreach($compound_products as $compound_herb) {
				$herbs[$compound_herb] =array(
					'product_id' => $compound_herb,
					'optional' => 'no',
					'bundle_quantity' => 1,
					'bundle_quantity_max' => 1,
					'visibility' => 'visible'
				);
			}
		}
		
		
		// If no errors were found, proceed with storing the user input
		if (count($errors) == 0) {
			$post_id = wp_insert_post( array(
				'post_title'        => $title,
				'post_status'       => 'publish',
				'post_type'       => 'product',
				'post_author'       => '1'
			) );
		 
			if ( $post_id != 0 ) {
				wp_set_object_terms( $post_id, 'Single Herb Tincture', 'product_cat' );
				wp_set_object_terms($post_id, 'bundle', 'product_type');
				update_post_meta( $post_id, '_allowed_bundle_user', $current_user->ID );
				update_post_meta( $post_id, '_edit_last', '1' );
				update_post_meta( $post_id, '_visibility', 'visible' );
				update_post_meta( $post_id, '_stock_status', 'instock');
				update_post_meta( $post_id, 'total_sales', '0');
				update_post_meta( $post_id, '_downloadable', 'no');
				update_post_meta( $post_id, '_virtual', 'no');
				update_post_meta( $post_id, '_regular_price',  $price_per_unit );
				update_post_meta( $post_id, '_sale_price',  '' );
				update_post_meta( $post_id, '_purchase_note', "" );
				update_post_meta( $post_id, '_featured', "no" );
				update_post_meta( $post_id, '_weight', "" );
				update_post_meta( $post_id, '_length', "" );
				update_post_meta( $post_id, '_width', "" );
				update_post_meta( $post_id, '_height', "" );
				update_post_meta($post_id, '_sku', "");
				update_post_meta( $post_id, '_product_attributes', array());
				update_post_meta( $post_id, '_sale_price_dates_from', '' );
				update_post_meta( $post_id, '_sale_price_dates_to', '' );
				update_post_meta( $post_id, '_price', $price_per_unit );
				update_post_meta( $post_id, '_sold_individually', "" );
				update_post_meta( $post_id, '_manage_stock', "no" );
				update_post_meta( $post_id, '_backorders', "no" );
				update_post_meta( $post_id, '_stock', "" );
				update_post_meta( $post_id, '_upsell_ids',  array());
				update_post_meta( $post_id, '_crosssell_ids',  array());
				update_post_meta( $post_id, '_per_product_pricing_active', "no" );
				update_post_meta( $post_id, '_per_product_shipping_active', "no" );
				update_post_meta( $post_id, '_bundle_data', $herbs);
				update_post_meta( $post_id, '_product_image_gallery',  '');
				update_post_meta( $post_id, '_product_details_folk_name',  '');
				update_post_meta( $post_id, '_min_bundle_price', $price_per_unit );
				update_post_meta( $post_id, '_max_bundle_price', $price_per_unit);
				
				global $woocommerce;
				$woocommerce->cart->add_to_cart($post_id,$size);
				$cart_url=$woocommerce->cart->get_cart_url();
				$msg = "Congrats!!! <br> Your recipe has been added to your cart. Please click to view <a href='".$cart_url."'>cart</a>.";
			}
			else {
				$msg = '*Error occured while adding the recipe';
			}
		} else {
			$msg="Check Errors*";
		}
	} else {
		//Not Logged in.
		$msg="Please login to add your recipe to cart.";
	}
		
	//Prepare errors for output
	$output='';
	$output .= '<div class="error-header">'.$msg.'</div>';
	$output .= '<div class="error-content"><ol>';
	foreach($errors as $val) {
		$output .= "<li>$val</li>";
	}
	$output .= '</ol></div>';
	echo $output;
	die();
}

add_action( 'wp_ajax_manage_compound', 'manage_compound' );
add_action( 'wp_ajax_nopriv_manage_compound', 'manage_compound' );