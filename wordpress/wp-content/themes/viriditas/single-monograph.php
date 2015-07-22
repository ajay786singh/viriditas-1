<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
	if($image) {
?>
<section role="banner" style="background-image:url('<?php echo $image[0];?>');">
    <div class="container">
        <div class="banner-content">
			<div class="secondary">
				<?php 
					the_title("<h1>","</h1>");
					$sub_heading=get_post_meta($post->ID,'_monograph_details_sub_heading',true);
					if($sub_heading) {
						echo "<p>".$sub_heading."</p>";
					}
				?>
			</div>
        </div>
    </div>
</section>
<?php } ?>
<section role="content">
    <div class="container">
		<div class="title-monograph">
		<?php 
			the_title("<h3>","</h3>");
			echo do_shortcode('[printfriendly]');
		?>
			<?php 
				$edit_formula_page_id=2440;//Make your compound page id
				if($edit_formula_page_id !='') {
				$edit_formula_page_url=get_permalink($edit_formula_page_id)."?compound=".$post->ID;
			?>
			<div class="add-formula">
				<a href="<?php echo $edit_formula_page_url;?>">Add to this formula</a>
			</div>
			<?php 
				}
			?>
		</div>
		<div class="content-monograph">
		<?php 
			$monograph_id=$post->ID;
			$composition = get_post_meta($post->ID,'_monograph_details_composition',true);
			$preparation = get_post_meta($post->ID,'_monograph_details_preparation',true);
			$synthesis = get_post_meta($post->ID,'_monograph_details_synthesis',true);
			$contradictions = get_post_meta($post->ID,'_monograph_details_contradictions',true);
			$dosage = get_post_meta($post->ID,'_monograph_details_dosage',true);
			$synergy = get_post_meta($post->ID,'_monograph_details_synergy',true);
			$product_ids="";
			$indications="";
			$actions="";
			$product_page_url=get_bloginfo('url')."/products"; 
			/*$args = array(
				'showposts' => -1,
				'post_type' => 'product',
				'meta_key' => '_product_details_monograph'
			);
			$products = new WP_Query($args);
			if($products->have_posts()):while($products->have_posts()):$products->the_post();
				$monograph=get_post_meta($post->ID,'_product_details_monograph',true);
					if(in_array($monograph_id,$monograph)) {
						$product_ids[]=$post->ID;
					}
			endwhile;endif;wp_reset_query();			
			if(count($product_ids)>0) {
				$indications = wp_get_object_terms( $product_ids, 'indication' );
				$actions = wp_get_object_terms( $product_ids, 'actions' );
			}*/
			if($composition!=-1) {
				echo "<section class='column-7'>";
					echo "<h5>Composition</h5>";
					echo "<ul class='list'>";	
						for($i=0;$i<count($composition);$i++) {
							echo "<li class='block-grid-3'>";
							echo get_product_info($composition[$i]);
							echo "</li>";
						}
					echo "</ul>";	
				echo "</section>";	
			}
			
			if($preparation) {
				echo "<section class='column-7'>";
					echo "<h5>Preparation</h5>";
					echo apply_filters('the_content', $preparation);
				echo "</section>";	
			}
			
			if($synthesis) {
				echo "<section class='column-7'>";
					echo "<h5>Synthesis and Mechanism of Action</h5>";
					echo apply_filters('the_content', $synthesis);
				echo "</section>";	
			}
			if($indications) {
				echo "<section class='column-7'>";
					echo "<h5>Indications</h5>";
					echo "<ul class='list'>";	
						foreach($indications as $indication) {
							$indication_url=$product_page_url."?pi=".$indication->term_id;
							echo "<li><a href='".$indication_url."'>".$indication->name."</a></li>";
						}
					echo "</ul>";	
				echo "</section>";	
			}
			if($contradictions) {
				echo "<section class='column-7'>";
					echo "<h5>Contradictions, Warnings and Interactions</h5>";
					echo apply_filters('the_content', $contradictions);
				echo "</section>";	
			}
			
			if($dosage) {
				echo "<section class='column-7'>";
					echo "<h5>Dosage</h5>";
					echo apply_filters('the_content', $dosage);
				echo "</section>";	
			}
			
			if($synergy) {
				echo "<section class='column-7'>";
					echo "<h5>Synergy & Compounding</h5>";
					echo apply_filters('the_content', $synergy);
				echo "</section>";	
			}
			if($actions) {
				echo "<section class='column-7'>";
					echo "<p>If you have suggestions do not fit your needs, you may continue on linking with the catalogue. For additional symptoms & conditions link first to the system involved, select the actions required and then choose an herb from the action category.</p>";
					echo "<ul class='list'>";	
						foreach($actions as $action) {
							$action_url=$product_page_url."?pa=".$action->term_id;
							echo "<li><a href='".$action_url."'>".$action->name."</a></li>";
						}
					echo "</ul>";
				echo "</section>";	
			}
		?>
		</div>
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>