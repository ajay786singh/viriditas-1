<?php

/**
* Accordion shortcode
* @example [accordion][section title="title"]content[/section][/accordion]
*/

	function add_jquery_dependencies(){
		wp_enqueue_script( 'jquery-ui-accordion' );
	}
	add_action('wp_enqueue_scripts', 'add_jquery_dependencies');

	add_shortcode("accordion", "accordion_shortcode");
	function accordion_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		"collapsible" => "false"
	), $atts));
	//	Check if it's collapsible
	if ($collapsible == true) {
		$collapsible = "{collapsible: true}";
	} else {
		$collapsible = "";
	}
	$GLOBALS["section_count"] = 0;
	//	Get the content
	do_shortcode($content);
	//	Generate output
	if (is_array( $GLOBALS["sections"] )) {
		foreach ($GLOBALS["sections"] as $section) {
			$panes[] = '<h3><a href="#'. str_replace(" ", "-", strtolower($section["name"])) .'">'. $section["name"] .'</a></h3>
			<div id="'. str_replace(" ", "-", strtolower($section["name"])) .'">
				<p>'. do_shortcode($section["content"]) .'</p>
			</div>';
		}
	}
	$output = '
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$(".accordion").accordion('. $collapsible .');
			});
		</script>
		<div class="accordion">'. implode("", $panes) .'</div>
	';
	return $output;
}
/*	Add the individual section shortcode
	Usage: [section name="Section name"]Content here[/section]
*/
add_shortcode("section", "accordion_section_function");
function accordion_section_function($atts, $content = null) {
	extract(shortcode_atts(array(
		"name" => "Section name"
	), $atts));
	
	$x = $GLOBALS["section_count"];
	$GLOBALS["sections"][$x] = array(
								"name"   => sprintf( $name, $GLOBALS["section_count"] ),
								"content" => $content
							);
	$GLOBALS["section_count"] += 1;
}
?>