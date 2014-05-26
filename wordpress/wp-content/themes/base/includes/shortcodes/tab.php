<?php

/**
* Tabgroups shortcode
* @example [tabsgroup][tab title="Title1"]Title 1[/tab][/tabsgroup]
*/
	
	function tabs_shortcode($atts, $content) {
        $GLOBALS["tab_count"] = 0;
        do_shortcode($content);
        if (is_array( $GLOBALS["tabs"] )) {
			if(count($GLOBALS['tabs'])>0)
			{
				$tab_filter=rand(1, 1000000);
				$output = '<div class="tabs clearfix" id="tab-'.$tab_filter.'">';
					$output .='<ul class="tab-header">';						
						for($i=0;$i<count($GLOBALS['tabs']);$i++)
						{	
							if($i==0){
								$output .= '<li><a href="#tab-'.$tab_filter.'-'.$i.'" class="active">'. $GLOBALS["tabs"][$i]['title'] .'</a>';
							} else {
								$output .= '<li><a href="#tab-'.$tab_filter.'-'.$i.'">'. $GLOBALS["tabs"][$i]['title'] .'</a>';							
							}			
						}
							
					$output .='</ul>';		
					$output .= '<div class="tab-content">';
							
					for($j=0;$j<count($GLOBALS['tabs']);$j++)
					{		
						if($j==0){
							$output .= '<div class="tab active-tab" id="tab-'.$tab_filter.'-'.$j.'">';
						} else {
								$output .= '<div class="tab" id="tab-'.$tab_filter.'-'.$j.'">';
							}		
							$output .=  do_shortcode($GLOBALS["tabs"][$j]['content']);
							$output .= '</div>';
					}
						
					$output .= "</div>";
				$output .= "</div>";
			}
        }
		return $output;
	}
	add_shortcode("tabsgroup", "tabs_shortcode");

/* Code for single Tab */

	function individual_tab($atts, $content = null) {
        extract(shortcode_atts(array(
                "title" => "Tab name"
        ), $atts));
       
        $x = $GLOBALS["tab_count"];
			$GLOBALS["tabs"][$x] = array(
								"title"   => sprintf( $title, $GLOBALS["tab_count"] ),
								"content" => $content
		 						);
        $GLOBALS["tab_count"] += 1;
	}
	add_shortcode("tab", "individual_tab");

?>
