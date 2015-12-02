<?php

use CMS\Media;
use CMS\Image;

# Validate Input
switch (true) {

    # Not logged in
    case !is_loggedin():
        JSON::parse( 100, 'error', 'You\'re not logged in.', null, true );
    break;

    # No data
    case !is_form_data():
        JSON::parse( 100, 'error', 'Nothing was submitted.', null, true );
    break;

}

# New GUMP Object
$form = new GUMP();

# Get Input
$data = form_data();

# Validate Input
$form->validate($data, array(
    'photo'      => 'required'
));

# Run GUMP
$response = $form->run( $data );

# Get Response
if ( $response === false ) {
    JSON::parse( 100, 'error', $form->get_readable_errors(true) );
} else {

    # Set Image Properties
    $properties = array(
        'filename'  => time(),
        'min_width' => 300,
        'max_width' => 1200,
        'thumbnail' => array(
            'width'     => 300,
            'height'    => 180
        )
    );

    # New Image Object
    $image = new Image();

    # Upload Image
    $image->upload_image( $data['photo'], $properties );

    # Get Response
    if ( !$image->get_image() ) {
        JSON::parse( 100, 'error', 'Image was not uploaded.', $data, true );
    }

    # Success!
    JSON::parse( 200, 'success', 'Testing', array('url' => $image->get_thumbnail()), true );

}
