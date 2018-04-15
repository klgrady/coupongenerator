<?php
/**
 * 
 * Plugin Name: Printable Coupon Generator
 * Description: With shortcodes, includes either a scheduled coupon (img file) or generates an img file in concert with an expiration.
 * Version: 1.0
 * Author: KL
 * 
 **/
 
 //Exit if accessed directly
 if(!defined('ABSPATH')) {
     exit;
 }
 
 //Call in globals
 global $wpdb, $coupontable;
 
 //define custom table 
 $coupontable = $wpdb->prefix . 'pcg_coupons';
 
 //Load Scripts
 require_once(plugin_dir_path(__FILE__) . '/scripts/printable-coupon-generator-scripts.php');
 

//Load admin page stuffs
if(is_admin()){
   // Load settings
   require_once(plugin_dir_path(__FILE__).'/scripts/printable-coupon-generator-settings.php');
}