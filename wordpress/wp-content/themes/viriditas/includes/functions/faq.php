<?php 
function get_faqs() {
	$html='';
	$args=array(
		'post_type' => 'faq',
		'post_status' => 'publish',
		'showposts' => '-1',
	);
	$query = new WP_Query( $args );
	if($query->have_posts()):
		$html.='<h5>FAQ</h5>';	
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
		$html.='<div class="column-7">';
				$html.='<div class="faq-content">';
				$html.= get_faqs();
				$html.= get_monographs();
				$html.= "<a href='".get_bloginfo('url')."/contact' class='button'>Contact Us</a>";
				$html.='</div>';
		$html.='</div>';
	$html.='</div>';
	return $html;
}
?>