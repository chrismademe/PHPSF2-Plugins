<?php

# Theme File
$file_theme = __DIR__ . '/assets/admin.zip';

# New ZipArchive object
$zip = new ZipArchive();

# Attempt to Extract Theme
if ( $zip->open($file_theme) ) {

    # Extract It
    $zip->extractTo( ROOT_DIR . '/app/theme' );
    $zip->close();

    # Success!
    $variables->add('cms_copy_theme', true);

} else {
    $variables->add('cms_copy_theme', false);
}

# Assets File
$file_assets = __DIR__ . '/assets/cms.zip';

# Attempt to Extract Theme
if ( $zip->open($file_assets) ) {

    # Extract It
    $zip->extractTo( ROOT_DIR . '/' . PUBLIC_ROOT . '/assets' );
    $zip->close();

    # Success!
    $variables->add('cms_copy_assets', true);

} else {
    $variables->add('cms_copy_assets', false);
}

# Copy Sample Widget
copy( __DIR__ .'/assets/cms-posts.twig', ROOT_DIR .'/app/theme/' . SITE_THEME .'/partials/cms-posts.twig' );
