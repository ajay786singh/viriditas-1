<?php
$page = new Cuztom_Post_Type('page');
$page->add_meta_box(
    'content_block',
    'Content Area (Optional)', 
    array(
        array(
            'name'          => 'sub_heading',
            'label'         => 'Sub Heading',
            'description'   => 'This will display under Heading.',
            'type'          => 'textarea',
            
        ),
		array(
			'name'          => 'redirect_url',
			'label'         => 'Redirect URL',
			'description'   => 'This will redirect page to mentioned url.',
			'type'          => 'text',
		)        
    )
);
?>