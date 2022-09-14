<?php 

/**
 * 
 * Customizer Settings
 * 
 * 
 */

 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


function obiaa_customize_register($wp_customize) {


// Add Section
$wp_customize->add_section('obiaa_options_sections', array(
    'title' => esc_html__('OBIAA Settings', 'obiaa-child')
));

// OBIAA Logo
$wp_customize->add_setting('obiaa_logo_image', array(

));

$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'obiaa_logo_image', array(
    'label' => esc_html__('Logo Image', 'obiaa-child'),
    'section' => 'obiaa_options_sections'
)) );



// Hero Image
$wp_customize->add_setting('obiaa_hero_image', array(

));

$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'obiaa_hero_image', array(
    'label' => esc_html__('Hero Image', 'obiaa-child'),
    'section' => 'obiaa_options_sections'
)) );

// Mobile Hero Image
$wp_customize->add_setting('obiaa_mobile_hero_image', array(

));

$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'obiaa_mobile_hero_image', array(
    'label' => esc_html__('Mobile Hero Image', 'obiaa-child'),
    'section' => 'obiaa_options_sections'
)) );


// Name Suffix
$wp_customize->add_setting('obiaa_name_suffix', array(
    'default' => 'MainStreetRM',
    'sanitize_callback' => 'sanitize_text_field'

));

$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'obiaa_name_suffix', array(
    'label' => esc_html__('Title Suffix', 'obiaa-child'),
    'section' => 'obiaa_options_sections',
    'type' => 'text'
)) );

}

add_action('customize_register', 'obiaa_customize_register');


