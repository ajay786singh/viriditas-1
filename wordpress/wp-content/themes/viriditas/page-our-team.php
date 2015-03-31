<?php 
/*
* Template Name: Team Page Template
*/
get_header();?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<div class="content-grid">
			<?php
				the_title("<h1>","</h1>");
				get_template_part( 'template', 'sub_heading' );
				echo "<div class='content-grid'>";
					the_content();
				echo "</div>";
			?>
		</div>	
	</div>
</section>
<?php 
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
?>
<img src="<?php echo $image[0];?>" width="100%" />
<section role="content">
	<div class="container divider">
		<div class="content-grid">
			<?php 
				$clinic_team_category_id=1362;
				$team_category = get_term_by('id', $clinic_team_category_id, 'team_category');
			?>
				<h1><?php echo $team_category->name;?></h1>
				<p><?php echo $team_category->description; ?></p>
		</div>	
	</div>			
	<?php
		$args=array(
			'post_type'=>'team',
			'showposts'=>'-1',
			'orderby' =>'menu_order',
			'order' =>'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => 'team_category',
					'field'    => 'slug',
					'terms'    => $team_category->slug,
				),
			)
		);
		$query = new WP_Query($args);
		if($query->have_posts()):
			while($query->have_posts()):$query->the_post();
		?>							
			<div class="oddeven team-member">
				<div class="container">
					<span class="image">
						<?php 
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
							if($image=="") {
								$img="http://placehold.it/700x400&text=No%20Preview";
							}else{
								$img=$image[0];
							}
						?>
						<img src="<?php echo $img;?>">
					</span>
					<span class="content">
						<h5><?php the_title(); ?></h5>
						<?php
							$designation=get_post_meta($post->ID,'_content_block_designation',true);
							if($designation) {
								echo "<p class='designation'>".$designation."</p>";
							}
						?>
						<div class="team-content">
							<?php the_content();?>
						</div>
					</span>  
				</div>	
			</div>
	<?php	endwhile;
		endif; wp_reset_query();	
	?>
	
	<div class="container divider">
		<div class="content-grid">
			<?php 
				$production_team_category_id=1363;
				$team_category = get_term_by('id', $production_team_category_id, 'team_category');
			?>
				<h1><?php echo $team_category->name;?></h1>
				<p><?php echo $team_category->description; ?></p>
		</div>			
		<?php
			$args=array(
				'post_type'=>'team',
				'showposts'=>'-1',
				'orderby' =>'menu_order',
				'order' =>'ASC',
				'tax_query' => array(
					array(
						'taxonomy' => 'team_category',
						'field'    => 'slug',
						'terms'    => $team_category->slug,
					),
				)
			);
			$query = new WP_Query($args);
			if($query->have_posts()):
				while($query->have_posts()):$query->the_post();
			?>							
				<div class="block-grid-3 team-member">
					<span class="image">
						<?php 
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
							if($image=="") {
								$img="http://placehold.it/600x300&text=No%20Preview";
							}else{
								$img=$image[0];
							}
						?>
						<img src="<?php echo $img;?>">
					</span>
					<span class="content">
						<h5><?php the_title(); ?></h5>
						<?php
							$designation=get_post_meta($post->ID,'_content_block_designation',true);
							if($designation) {
								echo "<p class='designation'>".$designation."</p>";
							}
						?>
						<div class="team-content">
							<?php the_content();?>
						</div>
					</span> 
				</div>
		<?php	endwhile;
			endif; wp_reset_query();	
		?>
	</div>				
</section>
<?php endwhile; endif; ?>
<?php get_footer(); ?>