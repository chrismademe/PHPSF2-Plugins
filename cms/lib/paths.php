<?php

/**
 * Path Handler
 */

$sf_paths = array(
    'admin',
    'admin/update',
    'admin/new',
    'admin/edit',
    'admin/delete',
    'admin/posts',
    'admin/install',
    'admin/post',
    'admin/post/edit',
    'admin/post/new',
    'admin/post/delete',
    'admin/post/upload',
    $sf_config['login_path'],
    $sf_config['login_handler'],
    $sf_config['logout_path'],
    $sf_config['register_path'],
    $sf_config['register_handler']
);

# Route Requests to Admin
if ( in_array( $path, $sf_paths ) ) {

    # Change Controller Dir
    $controller->useController(__DIR__ . '/controller');

    # Change Active Theme
    $theme->useTheme('admin');

    # Override Theme Settings
    require_once __DIR__ . '/assets.php';

}
