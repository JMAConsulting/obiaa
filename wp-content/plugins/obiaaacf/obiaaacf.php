<?php
 
/**
 
 * @package ACF OBIAA
 
 */
 
/*
 
Plugin Name: ACF-OBIAA
 
Plugin URI: https://obiaa.com/
 
Description: Plugin to handle customization related to ACFE

Version: 1.0.0
 
Author: Edsel Roque Lopez
 
Author URI: https://jmaconsulting.biz
 
License: GPLv2 or later
 
Text Domain: acfe
 
*/

add_filter('acfe/form/prepare/create-activity', 'prepare_activity_type', 10, 4);
function prepare_activity_type($prepare, $form, $post_id, $action){

    
    // return
    return $prepare;

}


