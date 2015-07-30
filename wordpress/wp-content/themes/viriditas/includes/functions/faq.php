<?php 
function get_faqs() {
	$html='';
	$html.='<h5>FAQ</h5>';
	$args=array(
		'post_type' => 'faq',
		'post_status' => 'publish',
		'showposts' => '-1',
	);
	$query = new WP_Query( $args );
	if($query->have_posts()):	
		$html.='<div class="accordion">';
		while($query->have_posts()):$query->the_post();
				$description    = get_the_content();
				$description=apply_filters('the_content', $description);
				$html.="<div class='accordion-panel'>";
				$html.='<h5 class="accordion-panel-header">'.get_the_title().'</h5>';		
				$html.="<div class='accordion-panel-content'>";
					if($description):
						$html.=$description;         
					endif;
				$html.="</div>";
			$html.="</div>";
		endwhile;
		$html.="</div>";			
	else:
		$html.="<p>No faqs found.</p>";
	endif;	
	return $html;
}

function get_worksheets() {
	$html='';
	$tax='worksheet_category';
	$html.='<h5>Worksheets</h5>';
	
	$args=array(
		'post_type' => 'worksheet',
		'post_status' => 'publish',
		'showposts' => '-1',
	);
	
	$worksheet_categories = get_terms($tax, 'orderby=count&order=desc&hide_empty=1&hierarchical=0&parent=0');	
	if($worksheet_categories) {		
		foreach($worksheet_categories as $wc) {
			$name=$wc->name;
			$slug=$wc->slug;
			$id=$wc->term_id;
			$args['tax_query'] = array(
				array(
					'taxonomy' => $tax,
					'field' => 'term_id',
					'terms' => array($wc->term_id)
				)
			);	
			$query = new WP_Query( $args );						
			if($query->have_posts()):
				$html.='<div class="accordion">';
				$html.="<div class='accordion-panel'>";
				$html.='<h5 class="accordion-panel-header">'.$name.'</h5>';		
					$html.="<div class='accordion-panel-content'><ul class='list'>";
				while($query->have_posts()):$query->the_post();
					$pid=get_the_ID();
					$download_link=get_post_meta($pid,'_worksheet_details_file',true);
					$download_link=('' != $download_link) ? $download_link : '#'; 
					$html.="<li><a href='".$download_link."' target='_blank'>".get_the_title()."</a></li>";
				endwhile;
					$html.="</ul></div>";
				$html.="</div>";
				$html.="</div>";
			endif;	
		}
	} else {
		$html.="<p>No worksheet found.</p>";
	}	
	return $html;
}

function get_monographs() {
	global $wpdb,$wp_query;
	$html='';
		$html.='<h5>Monographs</h5>';	
		$html.='<div class="accordion">';
			$html.="<div class='accordion-panel'>";
				$html.='<h5 class="accordion-panel-header monograph-header" data-rel="single-herb-tincture">Single Herb Tincture</h5>';		
				$html.="<div class='accordion-panel-content' id='monograph-single-herb-tincture'>";
					$single_args = array(
						'post_type' => 'product',
						'post_status' => 'publish',
						'showposts' => '-1',
						'orderby' => 'title',
						'order' => 'ASC',
						'tax_query' => array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'slug',
								'terms'    => 'single-herb-tincture',
							),
						),
					);
					$results = new WP_Query( $single_args );
					if($results->have_posts()):
						$letter="A";
						$html.="<ul class='list'>";
						while($results->have_posts()):$results->the_post();
							$title = get_the_title();
							$monograph = get_post_meta(get_the_ID(),'_product_details_monograph_link',true);
							$first_letter=substr($title,0,1);
							if($letter == $first_letter) {	
								if($monograph !='') {
									$html.="<li><a href='".$monograph."' target='_blank'>".get_the_title()."</a></li>";		
								}
							}
						endwhile;
						$html.="</ul>";
					else: 
						$html.="<p>No monograph found.</p>";
					endif; wp_reset_query();
				$html.="</div>";
			$html.="</div>";
			
			$html.="<div class='accordion-panel'>";
				$html.='<h5 class="accordion-panel-header monograph-header" data-rel="professional-herbal-combination">Professional Herbal Combination</h5>';		
				$html.="<div class='accordion-panel-content' id='monograph-professional-herbal-combination'>";
					$letter="V";
					$monograph_query="SELECT * FROM $wpdb->posts
							WHERE post_title LIKE '$letter%'
							AND post_type = 'monograph'
							AND post_status = 'publish'";
					$monographs = $wpdb->get_results($monograph_query);
					if ($monographs):
						$html.="<ul class='list'>";
						foreach ( $monographs as $post ) :
							setup_postdata ( $post ); 
							$id = $post->ID;
							$title = $post->post_name;
							$html.="<li><a href='".get_the_permalink($id)."'>".$title."</a></li>";		
						endforeach;
						$html.="</ul>";
					else: 
						$html.="<p>No monograph found.</p>";
					endif;wp_reset_query();	
				$html.="</div>";
			$html.="</div>";
			
		$html.="</div>";			
	return $html;
}

function get_faqs_box_content() {
	$html='<div id="faq-box" class="mfp-hide white-popup-block">';
	$html.='<p><a class="popup-modal-dismiss" href="#">X</a></p>';
	$html.='<h2>Ordering Support</h2>';
		$html.='<div class="column-9">';
				$html.='<div class="faq-content">';
				$html.= "<section>".get_faqs()."</section>";
				$html.= "<section>".get_worksheets()."</section>";
				$html.= "<section>".get_monographs()."</section>";
				$html.= "<a href='".get_bloginfo('url')."/contact' class='button'>Contact Us</a>";
				$html.='</div>';
		$html.='</div>';
	$html.='</div>';
	return $html;
}

function manage_monograph() {
	global $wp_query;	
	$html='';
	$category = $_POST['category'];
	$args=array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'showposts' => '-1',
	);
	if($category=='single-herb-tincture') {
		$post_type='product';
		$args['product_cat'] = 'single-herb-tincture';
	} else {
		$post_type="monograph";
	}
	$query=new WP_Query($args);
	if($query->have_posts()):
		$html.="<ul class='list'>"; 
		while($query->have_posts()):the_post();
			$html.="<li><a href='".get_the_permalink()."'>".get_the_title()."</a></li>";
		endwhile;
		$html.="</ul>"; 
	else:
		$html.="<p>No monograph found.</p>";
	endif;wp_reset_query();
	die(0);
}
add_action( 'wp_ajax_manage_monograph', 'manage_monograph' );
add_action( 'wp_ajax_nopriv_manage_monograph', 'manage_monograph' );