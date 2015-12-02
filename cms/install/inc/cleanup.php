<?php

# Remove the Install Directory
rename( ROOT_DIR .'/app/plugins/cms/install', ROOT_DIR .'/app/plugins/cms/_install' );

# Remove Theme Files
$ui_files = array(
    'admin-install.twig',
    'admin-install-submit.twig',
    'admin-install-database.twig',
    'admin-install-database-submit.twig'
);

foreach ( $ui_files as $file ) {
    unlink( ROOT_DIR .'/app/theme/' . SITE_THEME .'/' . $file );
}

header('location: /admin');
