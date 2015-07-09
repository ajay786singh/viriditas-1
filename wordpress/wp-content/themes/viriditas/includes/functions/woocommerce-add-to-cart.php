<?php
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
		$additional_price = trim($_POST['additional_price']);
		$compound_products = $_POST['compound_products'];
		$compound_herbs = $_POST['herbs'];
		
		//check for title not blank
		if (strlen($title) == 0) {
			array_push($errors, "Please enter your recipe name."); 
		}
		
		$herbs = array();
		if(count($compound_herbs)>0) {
			$pricy=get_option('wc_settings_tab_compound_pricy');
			if($pricy) {
				$pricy=explode(",",$pricy);
			}
			foreach($compound_herbs as $compound_herb) {
				$compound_herb = str_replace("size_","",$compound_herb);
				$compound_herb = explode("_",$compound_herb);
				$herb_id=$compound_herb[0];
				$herb_size=$compound_herb[1];
				$name=get_the_title($herb_id);
				if($herb_size=='' || $herb_size==0) {
					array_push($errors, "Please add size to herb: <b>".$name."</b>."); 
				} else {
					if(in_array($herb_id,$pricy)) {
						if($herb_size > 60) {
							array_push($errors, "This herb: <b>".$name."</b> can't have size for than 60%."); 
						} else {
							$price=$price+$additional_price;
						}
					}
					//$main_size=$herb_size/100;
					$herbs[$herb_id] =array(
						'product_id' => $herb_id,
						'optional' => 'no',
						'bundle_quantity' => $herb_size,
						'bundle_required_quantity' => $herb_size,
						'bundle_quantity_max' => $herb_size,
						'visibility' => 'visible'
					);
				}	
			}			
		} else {
			array_push($errors, "Please add herbs to your recipe."); 
		}
		
		$price_per_unit = number_format(($price/$size),2, '.', '');
		
		// If no errors were found, proceed with storing the user input
		if (count($errors) == 0) {
			$post_id = wp_insert_post( array(
				'post_title'        => $title,
				'post_status'       => 'publish',
				'post_type'       => 'product',
				'post_author'       => '1'
			) );
		 
			if ( $post_id != 0 ) {
				wp_set_object_terms( $post_id, 'Professional Herbal Combination', 'product_cat' );
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

/* Function to change price of Product Bundle (Professional Herbal Combination)*/

//add_filter('woocommerce_get_price','change_price', 10, 2);
//add_filter('woocommerce_get_regular_price','change_price', 10, 2);
//add_filter('woocommerce_get_sale_price','change_price', 10, 2);
/*function change_price($price,$productd){	
	global $woocommerce;
	if($productd->product_type == 'bundle'){
		$cart =	WC()->session->get( 'cart', null );
		//echo $_POST['size'];
		echo "<pre>";
		print_r(WC_Session::get('_data:protected'));
		echo "</pre>";
		$price = get_post_meta($productd->id,"_cart_price",true);
		//foreach($cart as $key => $value){
			//echo $size=$key."_cart_size";
			//echo $value[$size];
			//echo "<pre>";print_r($value);echo "</pre>";
			//echo $value['product_id']."-".$key."<br>";
			
		//}
		
		 foreach(WC()->session->cart as $cart_item_key => $cart_item) {
			//print_r(WC_Cart::get_cart_item($cart_item_key));
			// $newprice = $cart_item['cart_price'];		
			// echo "<pre>";
				// print_r($cart_item );
			// echo "</pre>";	
		}
		//$price = $newprice;	
	}
	return $price;
}*/

// add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );

/*function add_custom_price( $cart_object ) {
	global $woocommerce;
    $custom_price = 10; // This will be your custome price 
	
    foreach ( $cart_object->cart_contents as $key => $value ) {
		
		if($value['data']->product_type =='bundle') {
			//echo $value['data'];//;['post']->product_type;
			//echo $new_price = $value['cart_price'];//['stamp']->cart_price;
			//$values['data']->set_price($new_price);
			echo $value['data']->price;
			$quantity = intval( $value['quantity'] );
            $orgPrice = intval( $value['data']->price );
			$value['data']->price = $custom_price;
			//echo $value['data']->price;
		}
		
    }
	$woocommerce->cart->persistent_cart_update();
}*/

function save_cart_data_bundle_price( $cart_item_key, $product_id = null, $quantity= null, $variation_id= null, $variation= null ) {
    if( $_POST['cart_price']) {
        WC()->session->set( $cart_item_key.'_cart_price', $_POST['cart_price'] );
    } else {
        WC()->session->__unset( $cart_item_key.'_cart_price' );
    }  
	if( $_POST['cart_size']) {
        WC()->session->set( $cart_item_key.'_cart_size', $_POST['cart_size'] );
    } else {
        WC()->session->__unset( $cart_item_key.'_cart_size' );
    }  	
}
add_action( 'woocommerce_add_to_cart', 'save_cart_data_bundle_price', 1, 5 );

function add_bundle_price($cart_item_key, $product_id = null, $quantity= null, $variation_id= null, $variation= null) {
	global $woocommerce;
	$id=$_POST['product_id'];
	$product = wc_get_product( $id );
	if($product->is_type( 'bundle' ) ) {
		$price=$_POST['cart_price'];
		update_post_meta($id, '_cart_price', $price);
	}
	//print_r($product->product_type);
	
}
//add_action( 'woocommerce_add_to_cart', 'add_bundle_price');

function calculate_bundle_price( $cart_object ) {
    global $woocommerce;
	$additionalPrice = 100;
    foreach ( $cart_object->cart_contents as $key => $value ) {       
        if( WC()->session->__isset( $key.'_cart_price' ) ) {
			$bundle_price=WC()->session->get( $key.'_cart_price' );
            $quantity = intval( $value['quantity'] );
            $orgPrice = intval( $value['data']->price );
            //$value['data']->price = ( ( $bundle_price ) * $quantity );
			$value['data']->price =  $bundle_price ;
        }           
    }
	$woocommerce->cart->persistent_cart_update();
}
add_action( 'woocommerce_before_calculate_totals', 'calculate_bundle_price', 1, 1 );

/* Function to add meta value size on cart item list */
function render_meta_on_cart_item( $title = null, $cart_item = null, $cart_item_key = null ) {
    if( $cart_item_key && is_cart() ) {
			//echo WC()->session->$cart_item_key.'_cart_price' ;
        if( WC()->session->__isset( $cart_item_key.'_cart_size' ) ) {
            echo $title. '<dl class="variation">
                 <dt class="variation-Size">Size: </dt>
                 <dd class="variation-Size">'.trim(WC()->session->get($cart_item_key.'_cart_size')).' ml</dd>            
              </dl>';
        } else {
            echo $title;
        }
    }else {
        echo $title;
    }
}
add_filter( 'woocommerce_cart_item_name', 'render_meta_on_cart_item', 1, 3 );

function render_meta_on_checkout_order_review_item( $quantity = null, $cart_item = null, $cart_item_key = null ) {
    if( $cart_item_key ) {
        if( WC()->session->__isset( $cart_item_key.'_cart_size' ) ) {
			$size=trim(WC()->session->get($cart_item_key.'_cart_size'))." ml";	
            echo $quantity. '<dl class="variation">
                 <dt class="variation-Size">Size : </dt>
                 <dd class="variation-Size">'.$size.'</dd>            
              </dl>';
        } else {
            echo $quantity;
        }
    }
}
add_filter( 'woocommerce_checkout_cart_item_quantity', 'render_meta_on_checkout_order_review_item', 1, 3 );

/* Function to add meta value size on order item list */
function bundle_order_meta_handler( $item_id, $values, $cart_item_key ) {
    if( WC()->session->__isset( $cart_item_key.'_cart_size' ) ) {
		//$size=trim(WC()->session->get($cart_item_key.'_cart_size')).' ml';	
		$size=trim(WC()->session->get($cart_item_key.'_cart_size'));	
        wc_add_order_item_meta( $item_id, "Size ", $size ); 
    }
}
add_action( 'woocommerce_add_order_item_meta', 'bundle_order_meta_handler', 1, 3 );

//Store the custom field
add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_custom_data_vase', 10, 2 );
function add_cart_item_custom_data_vase( $cart_item_meta, $product_id ) {
  global $woocommerce;
  $cart_item_meta['cart_price'] = $_POST['cart_price'];
  $cart_item_meta['cart_size'] = $_POST['cart_size'];
  return $cart_item_meta; 
}

//Get it from the session and add it to the cart variable
function get_cart_items_from_session( $item, $values, $key ) {
    if ( array_key_exists( 'cart_price', $values ) ) {
        $item[ 'cart_price' ] = $values['cart_price'];
	}
	if ( array_key_exists( 'cart_size', $values ) ) {
		$item[ 'cart_size' ] = $values['cart_size'];
	}		
    return $item;
}
add_filter( 'woocommerce_get_cart_item_from_session', 'get_cart_items_from_session', 1, 3 );

/* Function to set price cookie*/
function add_to_price_cookie() { 	 
	$price=$_POST['price'];
	unset($_COOKIE['donation']);
	setcookie("donation", $price, 0, "/");
	die();
}	
add_action( 'wp_ajax_add_to_price_cookie', 'add_to_price_cookie' );
add_action( 'wp_ajax_nopriv_add_to_price_cookie', 'add_to_price_cookie' );

/* Function to add product into cart for Professional Herbal Combination */
function add_to_cart_bundle() { 	 
	$price=$_POST['price'];
	$product_type=$_POST['product_type'];
	echo $id=$_POST['id'];
	$size=$_POST['size'];
	die();
}	
add_action( 'wp_ajax_add_to_cart_bundle', 'add_to_cart_bundle' );
add_action( 'wp_ajax_nopriv_add_to_cart_bundle', 'add_to_cart_bundle' );
?>