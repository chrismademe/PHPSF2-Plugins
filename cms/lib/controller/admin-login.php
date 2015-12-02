<?php

# Login check
if ( is_loggedin() ) {
    header('location: /admin/posts');
}

# Set Page Title
$variables->extend('page', 'meta', array(
    'title' => 'Login | ' . $variables->get('site|name')
));
