<?php

# Login check
if ( !is_loggedin() ) {
    header('location: /' . $sf_config['login_path']);
}

# Set Page Title
$variables->extend('page', 'meta', array(
    'title' => 'New Post | ' . $variables->get('site|name')
));
