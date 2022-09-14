<?php 
/**
 * 
 * Enqueue Assets
 * 
 * 
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


function obiaa_theme_assets() {

    wp_enqueue_script( 'bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array('jquery'), NULL, true );

    wp_enqueue_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', false, '5.1.3', 'all' );

    wp_enqueue_style('Font_Awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css', false, '6.1.1', 'all' );

    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap', false, null, 'all' );

    wp_enqueue_style('obiaatheme-stylesheet', get_stylesheet_directory_uri().'/dist/assets/css/main.css', array('main','bootstrap_css' ), '1.0.0', 'all');

    wp_enqueue_script('obiaatheme-scripts', get_stylesheet_directory_uri() .'/dist/assets/js/bundle.js' , array('bootstrap_js','mainjs'), '1.0', true);

}

add_action('wp_enqueue_scripts', 'obiaa_theme_assets');