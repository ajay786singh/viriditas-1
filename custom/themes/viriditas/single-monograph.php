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
				$make_formula_page_id=2219;//Make your compound page id
				if($make_formula_page_id !='') {
				$make_formula_page_url=get_permalink($make_formula_page_id)."?mono-compound=".$post->ID;
			?>
			<div class="add-formula">
				<a href="<?php echo $make_formula_page_url;?>"><span>Add to this formula</span></a>
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
			
			if($composition!='') {
				$indications = wp_get_object_terms( $composition, 'indication' );
				$actions = wp_get_object_terms( $composition, 'actions' );
			}
			if($composition!='') {
				$composition=explode(',',$composition);
				echo "<section class='column-7'>";
					echo "<h5>Composition</h5>";
					echo "<ul class='list composition-list'>";	
						for($i=0;$i<count($composition);$i++) {
							$herburl=get_permalink($composition[$i]);
							echo "<li><a href='".$herburl."'>";
							$folk_name=get_post_meta($composition[$i],'_product_details_folk_name',true);
							if($folk_name) {
								echo $folk_name."<br>";
							} 
							echo get_product_info($composition[$i]);
							echo "</a></li>";
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
			// if($indications) {
				// echo "<section class='column-7'>";
					// echo "<h5>Indications</h5>";
					// echo "<ul class='list composition-list'>";	
						// foreach($indications as $indication) {
							// $indication_url=$product_page_url."?pi=".$indication->term_id;
							// echo "<li>".$indication->name."</li>";
						// }
					// echo "</ul>";	
				// echo "</section>";	
			// }
			if($contradictions) {
				echo "<section class='column-7'>";
					echo "<h5>Contraindications, Warnings and Interactions</h5>";
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