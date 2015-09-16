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
				$message[]=" $".$price." extra when added to ".$size;
			}
			$message=implode(", ",$message);
			$html .="<small><b>Note:</b> There is an extra fee for this herb: ".$message." </small>";	
		}
	echo $html;
}

function show_compound_notice_extra() {
	$sizes=get_option('wc_settings_tab_compound_sizes');
		if($sizes!='') {
			$sizes=explode(",",$sizes);								
			$message="";
			$count=count($sizes);
			foreach($sizes as $sizeprice) {
				$sizeprice=explode("/",$sizeprice);
				$size=$sizeprice[0];
				$price=$sizeprice[3];
				$message[]=" $".$price." extra when added to ".$size;
				$i++;
			}
			$message=implode(", ",$message);
			$html .="<small><b>Note:</b> There is an extra fee for this herb: ".$message." </small>";	
		}
	echo $html;
}
?>