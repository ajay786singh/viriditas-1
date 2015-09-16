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

$page->add_meta_box(
    'appointment_block',
    'Appointment content (Optional: This will be displayed only with appointment forms.)', 
    array(
        array(
            'name'          => 'appointment_pdf',
            'label'         => 'Upload appointment form PDF',
            'description'   => 'This is only for pdf file.',
            'type'          => 'file',   
        )
    )
);
?>