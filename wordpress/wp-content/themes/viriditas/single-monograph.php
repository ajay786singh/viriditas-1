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
		<?php the_title("<h4>","</h4>");?>
		<?php 
			$composition = get_post_meta($post->ID,'_monograph_details_composition',true);
			$preparation = get_post_meta($post->ID,'_monograph_details_preparation',true);
			$synthesis = get_post_meta($post->ID,'_monograph_details_synthesis',true);
			$contradictions = get_post_meta($post->ID,'_monograph_details_contradictions',true);
			$dosage = get_post_meta($post->ID,'_monograph_details_dosage',true);
			$synergy = get_post_meta($post->ID,'_monograph_details_synergy',true);
			if($composition!=-1) {
				echo "<section class='column-7'>";
					echo "<h5>Composition</h5>";
					echo "<ul class='list'>";	
						for($i=0;$i<count($composition);$i++) {
							get_product_info($composition[$i]);
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
		?>
		
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>