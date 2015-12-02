<?php

use CMS\Posts;
use CMS\Media;

# Validate Input
switch (true) {

    # Not logged in
    case !is_loggedin():
        JSON::parse( 100, 'error', 'You\'re not logged in.', null, true );
    break;

    # No ID
    case !isset($_GET['post_id']):
        JSON::parse( 100, 'error', 'No post specified.', null, true );
    break;

}

# New GUMP Object
$form = new GUMP();

# Sanitize data
$data = $_GET;

# Filter Input
$form->filter($data, array(
    'post_id'    => 'trim|sanitize_numbers'
));

# Validate Input
$form->validate($data, array(
    'post_id'    => 'required|numeric'
));

# Run GUMP
$response = $form->run( $data );

# Get Response
if ( $response === false ) {
    JSON::parse( 100, 'error', $form->get_readable_errors(true) );
} else {

    # Create new Posts object
    $posts = new Posts();

    # Get Post ID
    $ID = $data['post_id'];

    # Delete
    if ( !$posts->delete($ID) ) {
        JSON::parse( 100, 'error', $data['title'] . ' was not deleted.', true );
    }

    # Success!
    header('location: /admin/posts');

}
