<?php

if ( !is_loggedin() ) {
    header('location: /' . $sf_config['login_path']);
}

session_destroy();

header('location: /' . $sf_config['login_path']);
