<?php

# Stylesheets
$variables->extend('site', 'styles', array(
    array('src' => assets_dir() . '/cms/css/style.css'),
    array('src' => '//fonts.googleapis.com/css?family=Lato:300,400,600,700,900'),
    array('src' => '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')
));

# Javascript
$variables->extend('site', 'scripts', array(
    array('src' => '//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js'),
    array('src' => assets_dir() . '/cms/js/main.js')
));
