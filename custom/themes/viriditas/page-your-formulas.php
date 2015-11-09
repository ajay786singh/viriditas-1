<?php 
/*
* Template Name: Your Formulas Page Template
*/
get_header(); 
?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
	$compound_id="";
	$bundle_herbs="";
	$bundle_herb_ids="";
	$compound_id = $_REQUEST['compound'];
	$mono_compound_id = $_REQUEST['mono-compound'];
	$addition_box_class="compound-header";
?>

<div class="popup-compound">
	<div class="popup-compound-content">
		<div class="error pop-up-error"></div>
		<a href="#" class="close-button"></a>
		<div class="pop-up-action">
			<h6>What percent of the total formula will <span class="herb-name"></span> comprise?</h6>
			<form action="" method="post" class="add-recipe-herb">
				<div class="size-input"><input type="text" name="herb-size" value="" placeholder="0" class="numbers" id="herb-size" maxlength="2" /></div>
				<div><a href="#" class="button add-herb">Add Herb</a></div>
			</form>
			<div class="pop-up-note message_expensive"><?php show_compound_notice();?></div>
			<div class="pop-up-note message_extra_expensive"><?php show_compound_notice_extra();?></div>
		</div>
	</div>
</div>
<section role="content">
    <div class="container">
		<div class="column-8">
		<?php the_title("<h1>","</h1>");?>
		<?php the_content();?>
		</div>
		<div class="column-4 compound-links">
			<span><a class="back-to-products back-link" href="<?php bloginfo('url');?>/products">&larr; Back to products</a></span>
			<span><a class="back-to-products popup-modal" href="#faq-box">View monographs and worksheets</a></span>
		</div>	
	</div>
	<div class="container your-formulas">
		<div class="column-9">
			<?php 
				global $wp_query;
				$current_user = wp_get_current_user();
				//echo $current_user->ID;
				$args=array(
					'post_type'=>'product',
					'showposts'=>-1
				);
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => '1391'
					),
				);
				$args['meta_query'] = array(
					array(
					   'key' => '_allowed_bundle_user',
					   'value' => $current_user->ID,
					   'compare' => '=='
					)
				);
				$formulas=new WP_Query($args);
				if($formulas->have_posts()):
					echo '<div class="product-list"><ul class="thumb_view">';
					while($formulas->have_posts()):$formulas->the_post();
						?>
						<li class="equal-height-item" id="product-<?php echo get_the_ID();?>">		
							<?php get_template_part( 'woocommerce/content-formulas-product', 'woocommerce');?>
						</li>
						<?php
					endwhile;
					echo "</ul></div>";
				else:
					$yourFormulasURL= get_bloginfo('url').'/make-your-compound/';
					echo "<h6>You haven't made any custom formulas yet. <a href='".$yourFormulasURL."'>Click here</a> to get started.</h6>";
				endif;
			?>
		</div>	
	</div>
</section>
<?php endwhile; endif; ?>
<?php get_footer(); ?>