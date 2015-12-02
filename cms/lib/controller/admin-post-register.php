<?php

use CMS\User;

# Validate Input
switch (true) {

    # No data
    case !is_form_data():
        JSON::parse( 100, 'error', 'Nothing was submitted.', null, true );
    break;

}

# New GUMP Object
$form = new GUMP();

# Sanitize data
$data = $form->sanitize( form_data() );

# Filter Input
$form->filter($data, array(
    'email'      => 'trim|sanitize_email',
    'password'   => 'trim|sanitize_string'
));

# Validate Input
$form->validate($data, array(
    'email'    => 'required|valid_email',
    'password' => 'required'
));

# Run GUMP
$response = $form->run( $data );

# Get Response
if ( $response === false ) {
    JSON::parse( 100, 'error', $form->get_readable_errors(true), null, true );
} else {

    # New User Object
    $user = new User($pdo);

    # Attempt login
    if ( !$user->register( $data['email'], $data['password'] ) ) {
        JSON::parse( 100, 'error', $user->get_error(), null, true );
    }

    # Success!
    JSON::parse( 200, 'success', $data['email'] . ' added as user. <a class="small white button" href="/admin/posts">Go to Posts</a>', null, true );

}
