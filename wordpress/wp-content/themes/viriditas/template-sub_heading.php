<?php
	$sub_heading=get_post_meta(get_the_ID(),'_content_block_sub_heading',true);
	if($sub_heading) {
		echo "<h4 class='sub-heading'>".$sub_heading."</h4>";
	}
?>