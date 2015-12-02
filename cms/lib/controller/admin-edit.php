<?php

use CMS\Posts;

# Login check
if ( !is_loggedin() ) {
    header('location: /' . $sf_config['login_path']);
}

# Create Posts Object
$cms = new Posts();

# Check for Post ID
if ( !isset($_GET['post_id']) || isset($_GET['post_id']) && !$cms->post_exists($_GET['post_id']) ) {

    $variables->extend('cms', 'error', array(
        'type'      => 'negative',
        'message'   => 'We couldn\'t find that post.<br><code>Error code: 404</code>'
    ));

    # Set Page Title
    $variables->extend('page', 'meta', array(
        'title' => 'Edit | ' . $variables->get('site|name')
    ));

} else {

    # Get All Posts
    $post = $cms->get(array(
        'ID' => $_GET['post_id']
    ));

    # Friendly date
    $post->date = date('d/m/Y @ H:i', strtotime($post->date));

    # Pass to theme
    $variables->extend('cms', 'post', $post);

    # Set Page Title
    $variables->extend('page', 'meta', array(
        'title' => 'Edit ' . $post->title . ' | ' . $variables->get('site|name')
    ));

}
