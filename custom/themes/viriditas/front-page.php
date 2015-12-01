<?php get_header(); ?>
<?php 
	$welcome_page_id=1102;
	query_posts('post_type=page&p='.$welcome_page_id);
	if(have_posts()):while(have_posts()):the_post();
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
	$redirect_url=get_post_meta($post->ID,'_content_block_redirect_url',true);
?>

<section role="banner" style="background-image:url('<?php echo $image[0];?>');">
    <div class="container">
        <div class="banner-content">
			<div class="secondary">
				<?php 
					$sub_heading=get_post_meta($welcome_page_id,'_content_block_sub_heading',true);
					if($sub_heading) {
						echo "<h1>".$sub_heading."</h1>";
					}
				?>
				<?php the_content();?>
				<a href="<?php echo $redirect_url;?>" class="button">Learn more</a>
			</div>
        </div>
    </div>
</section>
<?php endwhile;endif;wp_reset_query();?>
<section role="content">
	<article class="container">
		<?php 
			$whatwedo_page_id=1105;
			query_posts('post_type=page&p='.$whatwedo_page_id);
			if(have_posts()):while(have_posts()):the_post();
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$redirect_url=get_post_meta($post->ID,'_content_block_redirect_url',true);
			if($image) {
		?>
		<div class="secondary">
			<img src="<?php echo $image[0];?>"  width="" height="">
		</div>
		<?php } ?>
		<div class="secondary">
			<?php the_title("<h2>","</h2>");?>
			<?php the_content();?>
			<p><a href="<?php echo $redirect_url;?>" class="button purple">Learn more</a></p>
		</div>
		<?php endwhile;endif;wp_reset_query();?>
	</article>	
	<article class="container divider">
		<?php 
			$args = array(
				'sort_order' => 'ASC',
				'sort_column' => 'menu_order',
				'child_of' => $welcome_page_id,
				'parent' => $welcome_page_id,
				'post_type' => 'page',
				'post_status' => 'publish'
			); 
			$pages = get_pages($args); 
			if($pages ) {
				foreach ( $pages as $page ) {				
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'featured-1' );
					if($image=="") {
						$img="http://placehold.it/700x500&text=No%20Preview";
					}else{
						$img=$image[0];
					}
					$content = $page->post_content;
					if ( ! $content ) // Check for empty page
						continue;
					$content = apply_filters( 'the_content', $content );
					$redirect_url=get_post_meta($page->ID,'_content_block_redirect_url',true);
		?>
						<div class="thumb">
							<a href="<?php echo $redirect_url;?>">
								<div class="img"><img src="<?php echo $img;?>" width="" height=""></div>
								<h5><?php echo $page->post_title;?></h5>
								<?php echo $content;?>
							</a>
						</div>
		<?php } 
		}
		?>
	</article>
</section>
<?php get_footer(); ?>