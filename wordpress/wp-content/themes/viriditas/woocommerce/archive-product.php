<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//get_header( 'shop' );
get_header( 'shop' );
?>
	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		//do_action( 'woocommerce_before_main_content' );
	?>
		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<?php /*?><h1><?php woocommerce_page_title(); ?></h1>
            <h5> Have a great <?php echo date('l');?></h5><?php */?>

		<?php endif; ?>

		<?php /*?><h5><?php do_action( 'woocommerce_archive_description' ); ?></h5><?php */?>


			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				//do_action( 'woocommerce_before_shop_loop' );
//				taxonomy=product_cat&post_type=product
				$product_cats = get_terms('product_cat', 'parent=0&orderby=count&hide_empty=1');
				$current_cat = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); // get current term				
				$top_parent = get_term_top_most_parent($current_cat->term_id,get_query_var( 'taxonomy' ));
					if($product_cats) {
							
						$list=array();	
						
						$class='';
						if(get_query_var( 'term' )=='') {
							$class="class='active'";
						}
						
						foreach($product_cats as $category){
							$class='';
							$arrow='';
							if($current_cat->slug==$category->slug || $top_parent->term_id == $category->term_id) {
								$class="class='active'";
								$children= get_terms('product_cat', 'parent='.$category->term_id.'&orderby=count&hide_empty=0');//get_term_children($category->term_id,'product_cat');
								if($children != null) {
									$arrow="<span class='arrow'></span>";
								}
							}
							$list[]='<li>'.$arrow.'<a '.$class.' href="'.get_term_link( $category ).'">'.$category->name.'</a></li>';
							unset($category);
						}
					}
			?>
            <div class="filters">
                <div class="container">
	                <div class="span-10 center">
    		        	<ul><?php echo implode("", $list); ?></ul>
                    </div>
                </div>        
					<?php
					if($children) {						
                    ?>
                    <div class="sub-categories">
                			<div class="container">
	            			    <div class="span-10 center">    		        	
                                    <ul>	
                                    <?php 
                                    $child_cats='';	
									$class='';
									if($current_cat->slug==$top_parent->slug) {
										$class="class='active'";
									}
									$child_cats[]='<li><a '.$class.' href="'.get_term_link( $top_parent, 'product_cat' ).'">All</a></li>';					
                                    foreach($children as $child) {
                                        $cclass='';
                                        //$term = get_term_by( 'id', $child, 'product_cat');
                                        if($current_cat->term_id==$child->term_id) {
                                            $cclass="class='active'";
                                            $children= get_term_children($category->term_id,'product_cat');								 
                                        }
                                        $child_cats[]='<li><a '.$cclass.' href="' . get_term_link( $child, 'product_cat' ) . '">' . $child->name . '</a></li>';
                                    }
                                    echo implode("", $child_cats); 
                                    ?>
                                    </ul>
                                  </div>
                              </div>      
                    </div>    
                    <?php 
                   }
                    ?>
            </div>
            
<div class="container product-content">
	<div class="span-10 center">
		<?php
			$next_date = date('Y-m-d', strtotime($date .' +1 day'));
			//echo $next_date;
		?>
		<p style="display:none;" class="italic text-center">Today's orders will be delivered the week of <?php echo date('l, F jS', strtotime('next monday'));?></p>
		<?php if ( have_posts() ) : ?>
            
			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>
				<?php while ( have_posts() ) : the_post(); ?>
				
					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
				
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
					<?php wc_get_template( 'loop/no-products-found.php' ); ?>
		<?php endif; ?>
	
	</div>
</div>
<?php get_footer( 'shop' ); ?>