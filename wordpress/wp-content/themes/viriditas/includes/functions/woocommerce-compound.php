<?php
//includes all the functions relating to add/edit compound
/*
 Function to show notification for expensive products
*/
function show_compound_notice() {
	$sizes=get_option('wc_settings_tab_compound_sizes');
		if($sizes!='') {
			$sizes=explode(",",$sizes);								
			$i=1;
			$message="";
			$count=count($sizes);
			foreach($sizes as $sizeprice) {
				$sizeprice=explode("/",$sizeprice);
				$size=$sizeprice[0];
				$price=$sizeprice[2];
				if($count==$i) {
					$message[]="and $".$price." if added to ".$size."mL";
				}else {
					$message[]="$".$price." if added to ".$size."mL";
				}
				$i++;
			}
			$message=implode(", ",$message);
			$html .="<small><b>Note:</b> This herb is little more pricey: ".$message." </small>";	
		}
	echo $html;
}
?>