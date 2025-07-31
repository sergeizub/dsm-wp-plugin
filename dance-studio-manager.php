<?php
/*
Plugin Name: Dance Studio Manager
Description: Plugin for Dance Studio Manager.
Version: 1.0
Requires at least: 5
Requires PHP: 5.6
Author: DSM
Author URI: https://www.dancestudiomanager.com/
License: GPL v2 or later
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}
define('DSM_PHPDATE', 'M j, Y');
require_once( trailingslashit( dirname( __FILE__ ) ) . 'autoloader.php' );

function dsm_location_sort($a, $b) {
	return strcmp($a->LOCATION,$b->LOCATION);
}
function dsm_class_schedules_sort($a, $b) {
	return strtotime($a->data[0]->START_DATE) - strtotime($b->data[0]->START_DATE);
}

function dsm_array_map($func, $arr)
{
  $ret = array();
  foreach ($arr as $key => $val)
	$ret[$key] = (is_array($val) ? dsm_array_map($func, $val) : $func($val));

  return $ret;
}

function dsm_body_class( $classes ) {
    global $post;
    if( isset($post->post_content) && has_shortcode( $post->post_content, 'dsm_client' ) ) {
        $classes [] = 'dsm_body';
    }
    return $classes ;
}
add_filter( 'body_class', 'dsm_body_class' );

if ( !is_admin() || wp_doing_ajax() )
  $dsm_app = new DanceStudioManager\App();

$dsm_settings = new DanceStudioManager\Settings();