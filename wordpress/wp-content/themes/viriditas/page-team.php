<?php get_header(); ?>

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

	<div class="container divider">
		<div class="content-grid">
			<?php 
				$team_category = get_term_by('id', 1362, 'team_category');
			?>
				<h1><?php echo $team_category->name;?></h1>
				<p><?php echo $team_category->description; ?></p>
	
		</div>	
	</div>			
	<?php
		$args=array(
			'post_type'=>'team',
			'showposts'=>'-1',
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
			<div class="oddeven team">
				<div class="container">
					<span class="image">
						<?php echo get_the_post_thumbnail($post->ID, 'full'); ?>
					</span>
					<span class="content">
						<h5><?php the_title(); ?></h5>
						<?php
							$designation=get_post_meta($post->ID,'_content_block_designation',true);
							if($designation) {
								echo "<p>".$designation."</p>";
							}
						?>
						<?php the_excerpt();?>
					</span>  
				</div>	
			</div>
	<?php	endwhile;
		endif; wp_reset_query();	
	?>
	
	<div class="container divider">
		<div class="content-grid">
			<?php 
				$team_category = get_term_by('id', 1363, 'team_category');
			?>
				<h1><?php echo $team_category->name;?></h1>
				<p><?php echo $team_category->description; ?></p>
		</div>			
		<?php
			$args=array(
				'post_type'=>'team',
				'showposts'=>'-1',
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
				<div class="block-grid-3 team">
					<span class="image">
						<?php echo get_the_post_thumbnail($post->ID, 'full'); ?>
					</span>
					<span class="content">
						<h5><?php the_title(); ?></h5>
						<?php
							$designation=get_post_meta($post->ID,'_content_block_designation',true);
							if($designation) {
								echo "<p>".$designation."</p>";
							}
						?>
						<?php the_excerpt();?>
					</span> 
				</div>
		<?php	endwhile;
			endif; wp_reset_query();	
		?>
	</div>			
	
	
	
	
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>