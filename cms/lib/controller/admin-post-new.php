<?php

use CMS\Posts;
use CMS\Media;

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

# Sanitize data
$data = form_data();

# Filter Input
$form->filter($data, array(
    'title'      => 'trim|sanitize_string',
    'content'    => 'trim',
    'status'     => 'trim|sanitize_numbers'
));

# Validate Input
$form->validate($data, array(
    'title'      => 'required',
    'content'    => 'required',
    'status'     => 'required|numeric'
));

# Run GUMP
$response = $form->run( $data );

# Get Response
if ( $response === false ) {
    JSON::parse( 100, 'error', $form->get_readable_errors(true) );
} else {

    # Create new Posts object
    $posts = new Posts();

    # Get Photo
    $photo = $data['cover_photo'];

    # Remove Photo from $data
    unset($data['photo']);
    unset($data['cover_photo']);

    # Create new Post
    if ( !$posts->create($data) ) {
        print_r($posts);
        JSON::parse( 100, 'error', $data['title'] . ' was not saved.', null, true );
    }

    if ( !empty( $photo ) ) {

        # New Media Object
        $media = new Media();

        # Media fields
        $fields = array(
            'post'      => $posts->ID,
            'filename'  => $photo,
            'type'      => 'cover_image'
        );

        # Save it!
        if ( !$media->create($fields) ) {
            JSON::parse( 200, 'warning', $data['title'] . ' was saved but there was a problem saving the photo.', null, true );
        }

    }

    # Success!
    JSON::parse( 200, 'success', $data['title'] . ' has been saved!', null, true );

}
