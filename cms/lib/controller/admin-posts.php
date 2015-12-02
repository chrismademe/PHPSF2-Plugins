<?php

use CMS\Posts;

# Login check
if ( !is_loggedin() ) {
    header('location: /' . $sf_config['login_path']);
}

# Create Posts Object
$cms = new Posts();

# Get All Posts
$posts = $cms->get(array(
    'status' => array(0,1)
));

# If only 1, nest it so it works in the loop
if ( is_object($posts) ) {
    $the_posts[] = $posts;
} elseif ( is_array($posts) ) {
    $the_posts = $posts;
}

# Pass to theme
if ( isset($the_posts) ) {
    $variables->extend('cms', 'posts', $the_posts);
}

# Set Page Title
$variables->extend('page', 'meta', array(
    'title' => 'All Posts | ' . $variables->get('site|name')
));
