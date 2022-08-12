<?php 
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


function acorntheme_theme_support() {
    add_theme_support('custom-logo');
    add_theme_support('title-tag');
    // remove_theme_support( 'widgets-block-editor' );
}

add_action('after_setup_theme','acorntheme_theme_support' );