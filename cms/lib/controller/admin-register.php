<?php

# Login check
if ( !is_loggedin() ) {
    header('location: /' . $sf_config['login_path']);
}

# Admin user check
if ( $variables->get('user|id') !== $sf_config['admin_user_id'] ) {
    header('location: /' . $sf_config['login_path']);
}
