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
	$html='';
	$args=array(
		'post_type' => 'monograph',
		'post_status' => 'publish',
		'showposts' => '-1',
	);
	$query = new WP_Query( $args );
	if($query->have_posts()):
		$html.='<h5>Monographs</h5>';	
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
	endif;	
	return $html;
}

function get_faqs_box_content() {
	$html='<div id="faq-box" class="mfp-hide white-popup-block">';
	$html.='<p><a class="popup-modal-dismiss" href="#">X</a></p>';
	$html.='<h1>Ordering Support</h1>';
		$html.='<div class="column-9">';
				$html.='<div class="faq-content">';
				$html.= get_faqs();
				$html.= get_worksheets();
				$html.= get_monographs();
				$html.= "<a href='".get_bloginfo('url')."/contact' class='button'>Contact Us</a>";
				$html.='</div>';
		$html.='</div>';
	$html.='</div>';
	return $html;
}