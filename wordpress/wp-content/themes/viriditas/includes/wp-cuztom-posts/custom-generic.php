<?php //Generic Modular Content Template for Posts and Pages

function remove_meta_boxes() {
    remove_meta_box('postcustom', 'post', 'normal'); //Custom fields metabox
    remove_meta_box('postcustom', 'page', 'normal'); //Custom fields metabox
    remove_meta_box('commentstatusdiv', 'post', 'normal'); //Comments status metabox (discussion)
    remove_meta_box('commentstatusdiv', 'page', 'normal'); //Comments status metabox (discussion)
    remove_meta_box('commentsdiv', 'page', 'normal'); //Comments metabox
    remove_meta_box('commentsdiv', 'post', 'normal'); //Comments metabox
    remove_meta_box('trackbacksdiv', 'post', 'normal'); //Trackbacks metabox
    remove_meta_box('trackbacksdiv', 'page', 'normal'); //Trackbacks metabox
    remove_meta_box('authordiv', 'post', 'normal'); //Author metabox
    remove_meta_box('authordiv', 'page', 'normal'); //Author metabox
    remove_meta_box('slugdiv', 'post', 'normal'); //Slug metabox
    remove_meta_box('slugdiv', 'page', 'normal'); //Slug metabox
    //remove_meta_box('postexcerpt', 'post', 'normal'); //Excerpt metabox
    //remove_meta_box('postexcerpt', 'page', 'normal'); //Excerpt metabox
    //remove_meta_box('postimagediv', 'post', 'normal'); //Featured image metabox
    //remove_meta_box('postimagediv', 'page', 'normal'); //Featured image metabox
}

add_action( 'admin_menu' , 'remove_meta_boxes' );

$posts = new Cuztom_Post_Type('post');
$pages = new Cuztom_Post_Type('page');

$pages->add_meta_box(
    'banner',
    'Featured banner OR video (Optional)', 
    array(
        array(
            'name'          => 'heading',
            'label'         => 'Headline',
            'description'   => 'Enter Headline',
            'type'          => 'text',
            
        ),
        array(
            'name'          => 'subheading',
            'label'         => 'Subheading',
            'description'   => 'Enter Subheadine',
            'type'          => 'text',
            
        ),
        
        array(
            'name'          => 'video',
            'label'         => 'Banner Video',
            'description'   => 'Enter Video URL/Link',
            'type'          => 'text',
            
        ),
        array(
            'name'          => 'image',
            'label'         => 'Banner Image',
            'description'   => 'Dimensions 1200px x 600px',
            'type'          => 'image',
        ),

    )
);

$pages->add_meta_box( 
    'full_width',
    'Full width image (Optional)',
    array(
        array(
            'name'          => 'image',
            'label'         => 'Image',
            'description'   => 'Dimensions 1200px x 600px',
            'type'          => 'image',
        ),
        array(
            'name'          => 'text',
            'label'         => 'Text Overlay',
            'description'   => 'Text that will overlay image',
            'type'          => 'textarea',
        ),
        array(
            'name'          => 'order',
            'label'         => 'Modular Order',
            'description'   => 'Add a large Concise sub Heading',
            'type'          => 'select',
            'options'       => array(
                'value1'    => '1',
                'value2'    => '2',
                'value3'    => '3'
            ),
            'default_value' => 'value1'
        )
    )
);

$pages->add_meta_box(
    'content_block_1',
    'Content Area (Optional)', 
    array(
        array(
            'name'          => 'text',
            'label'         => 'Text',
            'description'   => 'Text area',
            'type'          => 'wysiwyg',
            
        ),
        array(
            'name'          => 'order',
            'label'         => 'Modular Order',
            'description'   => 'Add a large Concise sub Heading',
            'type'          => 'select',
            'options'       => array(
                'value1'    => '1',
                'value2'    => '2',
                'value3'    => '3'
            ),
            'default_value' => 'value1'
        )        
    )
);

$posts->add_meta_box(
    'banner',
    'Featured banner OR video (Optional)', 
    array(
        array(
            'name'          => 'heading',
            'label'         => 'Headline',
            'description'   => 'Enter Headline',
            'type'          => 'text',
            
        ),
        array(
            'name'          => 'subheading',
            'label'         => 'Subheading',
            'description'   => 'Enter Subheadine',
            'type'          => 'text',
            
        ),
        
        array(
            'name'          => 'video',
            'label'         => 'Banner Video',
            'description'   => 'Enter Video URL/Link',
            'type'          => 'text',
            
        ),
        array(
            'name'          => 'image',
            'label'         => 'Banner Image',
            'description'   => 'Dimensions 1200px x 600px',
            'type'          => 'image',
        ),
        array(
            'name'          => 'logo',
            'label'         => 'Logo',
            'description'   => 'Upload Logo',
            'type'          => 'file',  
        ),

    )
);

$posts->add_meta_box( 
    'full_width',
    'Full width image (Optional)',
    array(
        array(
            'name'          => 'image',
            'label'         => 'Image',
            'description'   => 'Dimensions 1200px x 600px',
            'type'          => 'image',
        ),
        array(
            'name'          => 'text',
            'label'         => 'Text Overlay',
            'description'   => 'Text that will overlay image',
            'type'          => 'textarea',
        ),
        array(
            'name'          => 'order',
            'label'         => 'Modular Order',
            'description'   => 'Add a large Concise sub Heading',
            'type'          => 'select',
            'options'       => array(
                'value1'    => '1',
                'value2'    => '2',
                'value3'    => '3'
            ),
            'default_value' => 'value1'
        )
    )
);

$posts->add_meta_box(
    'content_block_1',
    'Content Area (Optional)', 
    array(
        array(
            'name'          => 'text',
            'label'         => 'Text',
            'description'   => 'Text area',
            'type'          => 'wysiwyg',
            
        ),
        array(
            'name'          => 'order',
            'label'         => 'Modular Order',
            'description'   => 'Add a large Concise sub Heading',
            'type'          => 'select',
            'options'       => array(
                'value1'    => '1',
                'value2'    => '2',
                'value3'    => '3'
            ),
            'default_value' => 'value1'
        )        
    )
);

?>