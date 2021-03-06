<?php

/**
 * @package Medoo
 * @require Database
 *
 * Plugin to integrate
 * the Medoo database
 * class
 */

// Check Database plugin is active
if ( !plugin_is_active('database') ) {
    throw new Exception('Medoo plugin requires Database to be active');
}

// Check Medoo exists
if ( !class_exists('medoo') ) {
    throw new Exception('Medoo plugin requires Medoo class to be included. Hint: <code>composer require catfan/medoo</code>');
}

// Create new instance of Medoo
$medoo = new medoo(array(
    'database_type' => 'mysql',
    'database_name' => DB_NAME,
    'server' => DB_HOST,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => 'utf8',
    'port' => DB_PORT
));

// Create medoo() function
function medoo() {
    global $medoo;
    return $medoo;
}
