<?php

# Check Permissions
$variables->add('cms_theme_folder', is_writeable( APP_DIR .'/theme/' . SITE_THEME ));
$variables->add('cms_assets_folder', is_writeable( ROOT_DIR . '/public_html' ));

# Check Dependencies
if ( class_exists( 'GUMP' ) ) {
    $variables->add('cms_gump', true);
}

if ( class_exists( 'PHPAuth\Auth' ) ) {
    $variables->add('cms_phpauth', true);
}

if ( class_exists( 'abeautifulsite\SimpleImage' ) ) {
    $variables->add('cms_simpleimage', true);
}
