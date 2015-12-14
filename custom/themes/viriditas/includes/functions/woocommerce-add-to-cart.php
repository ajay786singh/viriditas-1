<?php
/*
*  Function to Manage Recipe By User(s)
*/
function manage_compound() {
	$current_user = wp_get_current_user();
	$errors = array();
	if ( $current_user->ID != 0 ) {
		//logged in.			
		$title = trim($_POST['title']);
		$product_type = trim($_POST['product_type']);
		$size = trim($_POST['size']);
		$price = trim($_POST['price']);
		$additional_price = trim($_POST['additional_price']);
		$compound_products = $_POST['compound_products'];
		$compound_herbs = $_POST['herbs'];
		$compound_id = $_POST['compound_id'];
		$bundle_herbs = $_POST['compound_herbs'];
		$expenses = $_POST['additional_price'];
		$recipeMono = $_POST['recipeMono'];
		$serviceFee=$_POST['service_fee'];
		$totalPrice = $price+$expenses+$serviceFee;
		//check for title not blank
		if (strlen($title) == 0) {
			array_push($errors, "Please enter your formula name."); 
		}	
		$herbs = array();
		if(count($compound_herbs)>0) {
			foreach($compound_herbs as $compound_herb) {
				$compound_herb = str_replace("size_","",$compound_herb);
				$compound_herb = explode("_",$compound_herb);
				$herb_id=$compound_herb[0];
				$herb_size=$compound_herb[1];
				$name=get_the_title($herb_id);
				if($herb_size=='' || $herb_size==0) {
					array_push($errors, "Please add size to herb: <b>".$name."</b>."); 
				} else {
					$expensive_herb = get_post_meta($herb_id,'_product_details_expensive_herb',true);	
					if($expensive_herb == '*' || $expensive_herb == '**') {
						if($herb_size > 60) {
							array_push($errors, "60% of this herb: <b>".$name."</b> is the maximum that can be added to a formula online. Please contact Viriditas to request greater than 60%. 416-767-3428"); 
						} else {
							$price=$price+$additional_price;
						}
					}			
					$herbs[$herb_id] =array(
						'product_id' => $herb_id,
						'optional' => 'yes',
						'bundle_quantity' => 1,
						'bundle_required_quantity' => 1,
						'bundle_quantity_max' => 1,
						'bundle_required_size' => $herb_size,
						'visibility' => 'visible'
					);
				}	
			}			
		} else {
			array_push($errors, "Please add herbs to your formula."); 
		}
		if($bundle_herbs !='') {
			$bundle_herbs=explode(",",$bundle_herbs);	
			foreach($bundle_herbs as $bundle_herb) {
				$herbs[$bundle_herb] =array(
					'product_id' => $bundle_herb,
					'optional' => 'yes',
					'bundle_quantity' => 1,
					'bundle_required_quantity' => 1,
					'bundle_quantity_max' => 1,
					'visibility' => 'visible'
				);
			}
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
			$product_attributes='';
			if ( $post_id != 0 ) {
				$product_attributes['pa_size']=array(
					'name' => 'pa_size',
					'value' => '',
					'position' => 0,
					'is_visible' => 1,
					'is_variation' => 0,
					'is_taxonomy' => 1,
				);
				$product_attributes['pa_price']=array(
					'name' => 'pa_price',
					'value' => '',
					'position' => 2,
					'is_visible' => 1,
					'is_variation' => 0,
					'is_taxonomy' => 1,
				);
				$sizes=get_option('wc_settings_tab_compound_sizes');
				if($compound_id !='' && $recipeMono=='') {
					$compound_sizes = get_the_terms( $compound_id, 'pa_size');
					sort($compound_sizes);
					$compound_prices = get_the_terms( $compound_id, 'pa_price');
					sort($compound_prices);
				}
				if($sizes) {
					$sizes=explode(",",$sizes);
					$avail_sizes="";
					$avail_prices="";
					$i=0;
					foreach($sizes as $sizeprice) {
						if($compound_id){
							$avail_sizes[]=$compound_sizes[$i]->name;
							$avail_prices[]=$compound_prices[$i]->name;
						} else {
							$sizeprice=explode("/",$sizeprice);
							$avail_sizes[]=$sizeprice[0];
							$avail_prices[]=$sizeprice[1];
						}
						$i++;
					}								
					wp_set_object_terms( $post_id, $avail_sizes, 'pa_size' );
					wp_set_object_terms( $post_id, $avail_prices, 'pa_price' );						
				}						
				$defaultWeight="1";
				$defaultHeight="25.5";
				$defaultWidth="9.0";
				$defaultLength="9.0";
				wp_set_object_terms( $post_id, 'Professional Herbal Combination', 'product_cat' );
				wp_set_object_terms($post_id, 'bundle', 'product_type');
				update_post_meta( $post_id, '_allowed_bundle_user', $current_user->ID );
				update_post_meta( $post_id, '_edit_last', '1' );
				update_post_meta( $post_id, '_visibility', 'visible' );
				update_post_meta( $post_id, '_stock_status', 'instock');
				update_post_meta( $post_id, 'total_sales', '0');
				update_post_meta( $post_id, '_downloadable', 'no');
				update_post_meta( $post_id, '_virtual', 'no');
				update_post_meta( $post_id, '_regular_price',  '1' );
				update_post_meta( $post_id, '_selling_price',  $totalPrice );
				update_post_meta( $post_id, '_selling_size',  $size );
				update_post_meta( $post_id, '_sale_price',  '' );
				update_post_meta( $post_id, '_purchase_note', "" );
				update_post_meta( $post_id, '_featured', "no" );
				update_post_meta( $post_id, '_weight', $defaultWeight );
				update_post_meta( $post_id, '_length', $defaultLength );
				update_post_meta( $post_id, '_width', $defaultWidth );
				update_post_meta( $post_id, '_height', $defaultHeight);
				update_post_meta($post_id, '_sku', "");
				update_post_meta( $post_id, '_product_attributes', $product_attributes);
				update_post_meta( $post_id, '_sale_price_dates_from', '' );
				update_post_meta( $post_id, '_sale_price_dates_to', '' );
				update_post_meta( $post_id, '_price', 1 );
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
				update_post_meta( $post_id, '_min_bundle_price', 1 );
				update_post_meta( $post_id, '_max_bundle_price', 1);
				global $woocommerce;
				$woocommerce->cart->add_to_cart($post_id,1);
				$cart_url=$woocommerce->cart->get_cart_url();
				$msg = "done";
			}
			else {
				$msg = '*Error occurred while adding the formula';
			}
		} else {
			$msg="Check Errors*";
		}
	} else {
		//Not Logged in.
		$msg="Please login to add your formula to cart.";
	} 		
	//Prepare errors for output
	$output='';
	if($msg == "done") {
		$output = $msg;
	} else {		
		$output = '<div class="error-header">'.$msg.'</div>';
		$output.= '<div class="error-content"><ol>';
		foreach($errors as $val) {
			$output.= "<li>$val</li>";
		}
		$output.= '</ol></div>';
	}
	echo $output;
	die();
}
add_action( 'wp_ajax_manage_compound', 'manage_compound' );
add_action( 'wp_ajax_nopriv_manage_compound', 'manage_compound' );
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
	if( $_POST['product_type']) {
        WC()->session->set( $cart_item_key.'_product_type', $_POST['product_type'] );
    } else {
        WC()->session->__unset( $cart_item_key.'_product_type' );
    } 
	if( $product_id ) {
        WC()->session->set( $cart_item_key.'_product_id', $product_id);
    } else {
        WC()->session->__unset( $cart_item_key.'_product_id' );
    }
	if( $_POST['additional_price'] ) {
        WC()->session->set( $cart_item_key.'_additional_price', $_POST['additional_price']);
    } else {
        WC()->session->__unset( $cart_item_key.'_additional_price' );
    }  	
	if( $_POST['service_fee'] ) {
        WC()->session->set( $cart_item_key.'_service_fee', $_POST['service_fee']);
    } else {
        WC()->session->__unset( $cart_item_key.'_service_fee' );
    } 	
	if( $_POST['orderID'] ) {
		WC()->session->set( $cart_item_key.'_order_id', $_POST['orderID']);
		//WC()->session->set( $cart_item_key.'_cart_size', '200 mL');
		$orderID=$_POST['orderID'];
		$order = new WC_Order($orderID);
		$items = $order->get_items();
		if ( sizeof( $items ) > 0 ) {
			foreach ( $items as $item_id => $item ) {
				$item_meta=$item['item_meta_array'];
				$_size="";
				$_product_id="";
				$_price="";			
				foreach($item_meta as $meta) {
					if($meta->key=='_product_id'){
						$_product_id=$meta->value;	
					}		
					if($meta->key=='_line_subtotal'){
						$_price=$meta->value;	
					}				
					if($meta->key=='pa_size'){
						$_size = $meta->value;
						$_size=explode("-",$_size);
						$_size=implode(" ",$_size);
						$_size=strrev(ucfirst(strrev($_size)));
					} else if($meta->key == 'Size'){
						$_size=$meta->value;	
					}
				}
				if($product_id==$_product_id) {
					WC()->session->set( $cart_item_key.'_cart_size', $_size);
					WC()->session->set( $cart_item_key.'_cart_price', $_price);
				}
				//WC()->cart->add_to_cart($_product_id,1);
			}
		}
	} else {
        WC()->session->__unset( $cart_item_key.'_order_id' );
    } 	
}
add_action( 'woocommerce_add_to_cart', 'save_cart_data_bundle_price', 1, 5 );
function calculate_bundle_price( $cart_object ) {
    global $woocommerce;
	$additionalPrice = 100;
    foreach ( $cart_object->cart_contents as $key => $value ) {       
        $productID=$value['product_id'];
		$totalPrice=get_post_meta($productID,'_selling_price',true);
		if($totalPrice !='') {
			$bundle_price=$totalPrice;
            $quantity = intval( $value['quantity'] );
            $orgPrice = intval( $value['data']->price );
			$value['data']->price = $bundle_price;
		} else if( WC()->session->__isset( $key.'_cart_price' ) ) {
			$bundle_price=WC()->session->get( $key.'_cart_price' ) + WC()->session->get( $key.'_additional_price' ) + WC()->session->get( $key.'_service_fee' );
            $quantity = intval( $value['quantity'] );
            $orgPrice = intval( $value['data']->price );
			$value['data']->price =  $bundle_price ;
        }           
    }
	$woocommerce->cart->persistent_cart_update();
}
add_action( 'woocommerce_before_calculate_totals', 'calculate_bundle_price', 1, 1 );
/* Function to add meta value size on cart item list */
function render_meta_on_cart_item( $title = null, $cart_item = null, $cart_item_key = null ) {
	if( $cart_item_key && is_cart() ) {
		$productID=$cart_item['product_id'];	
		$size=get_post_meta($productID,'_selling_size',true);
		// echo "<pre>";
		// print_r($cart_item);
		// echo "</pre>";
        if( $size !='') {
            echo $title. '<dl class="variation">
                <dt class="variation-Size">Size: </dt>
                <dd class="variation-Size">'.trim($size).'</dd>            
              </dl>';
        } else if( WC()->session->__isset( $cart_item_key.'_cart_size' ) ) {
			echo $title. '<dl class="variation">
                 <dt class="variation-Size">Size: </dt>
                 <dd class="variation-Size">'.trim(WC()->session->get($cart_item_key.'_cart_size')).'</dd>            
              </dl>';
        } else {
			//$unit=trim(WC()->session->get($cart_item_key.'_unit'));
            echo $title;
			//echo "<span id='size-unit' style='display:none;'>".$unit."</span>";
        }
    } else {
        echo $title;
    }
}
add_filter( 'woocommerce_cart_item_name', 'render_meta_on_cart_item', 1, 3 );
function render_meta_on_checkout_order_review_item( $quantity = null, $cart_item = null, $cart_item_key = null ) {
    if( $cart_item_key ) {
        $productID=$cart_item['product_id'];	
		$size=get_post_meta($productID,'_selling_size',true);
        if( $size !='') {
            echo $quantity. '<dl class="variation">
                 <dt class="variation-Size">Size : </dt>
                 <dd class="variation-Size">'.$size.'</dd>            
              </dl>';
        } else if( WC()->session->__isset( $cart_item_key.'_cart_size' ) ) {
			$size=trim(WC()->session->get($cart_item_key.'_cart_size'));	
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
	global $woocommerce;	
	
	if( WC()->session->__isset( $cart_item_key.'_product_id' ) ) {
		$product_id=trim(WC()->session->get($cart_item_key.'_product_id'));	
		$product_type=trim(WC()->session->get($cart_item_key.'_product_type'));	
		$product = get_product( $product_id );
		if($product_type=='bundle' || $product->is_type( 'bundle')>0) {
			$total_size=trim(WC()->session->get($cart_item_key.'_cart_size'));	
			$size=get_post_meta($product_id,'_selling_size',true);
			if($size!='') {
				$herbs=get_bundle_info($product_id,$size);
			} else {
				$size=$total_size;
				$herbs=get_bundle_info($product_id,$total_size);
			}
			wc_add_order_item_meta( $item_id, "Herbs", $herbs ); 
		}
    }
	if( WC()->session->__isset( $cart_item_key.'_cart_size' ) ) {
		$mainSize=trim(WC()->session->get($cart_item_key.'_cart_size'));	
		wc_add_order_item_meta( $item_id, "Size", $mainSize); 	
    } else if($size !='') {
		wc_add_order_item_meta( $item_id, "Size", $size); 
	}
}
add_action( 'woocommerce_add_order_item_meta', 'bundle_order_meta_handler', 1, 3 );
//Store the custom field
add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_custom_data_vase', 10, 2 );
function add_cart_item_custom_data_vase( $cart_item_meta, $product_id ) {
	global $woocommerce;
	$cart_item_meta['cart_price'] = $_POST['cart_price'];
	$cart_item_meta['cart_size'] = $_POST['cart_size'];
	$cart_item_meta['additional_price'] = $_POST['additional_price'];
	if($_POST['orderID']!='') {
		$cart_item_meta['order_id'] = $_POST['orderID'];
	}
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
	if ( array_key_exists( 'additional_price', $values ) ) {
		$item[ 'additional_price' ] = $values['additional_price'];
	}
	if ( array_key_exists( 'order_id', $values ) ) {
		$item[ 'order_id' ] = $values['order_id'];
	}
    return $item;
}
add_filter( 'woocommerce_get_cart_item_from_session', 'get_cart_items_from_session', 1, 3 );
/*
* Function to get cart item bundle information
*/
function get_bundle_info($id,$size) {
	$bundle_data=get_post_meta($id,'_bundle_data',true);
	$result="";
	$unit=get_post_meta($id,'_product_details_unit',true);
	if($unit=='') {
		$unit="mL";
	}
	if(count($bundle_data)>0) {
		$herbs="";
		foreach($bundle_data as $bundle_herb_id => $bundle_herb_values ) {
			//$herb_title=get_the_title($bundle_herb_id);
			$herb_size ="";
			$herb_size = $bundle_herb_values['bundle_required_size'];
			$expensive_herb = get_post_meta($bundle_herb_id,'_product_details_expensive_herb',true);	
			$herb_title=get_the_title($bundle_herb_id);
			if($herb_size !='') {
				$herb_size=($herb_size / 100) * $size;
				$herb_size= " - ".$herb_size." ".$unit;
				$herb_title=get_the_title($bundle_herb_id).$expensive_herb." ".$herb_size;
			}			
			$herbs[]="<small><i>".$herb_title."</i></small>";
		}
		$result = implode(", ",$herbs);
	}	
	return $result;
}
add_action( 'wp_ajax_add_to_cart_formula', 'add_to_cart_formula' );
add_action( 'wp_ajax_nopriv_add_to_cart_formula', 'add_to_cart_formula' );
function add_to_cart_formula() {
	global $woocommerce;
	$post_id=$_POST['product_id'];
	$woocommerce->cart->add_to_cart($post_id,1);
	die();
}
?>