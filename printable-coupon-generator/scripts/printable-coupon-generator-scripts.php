<?php

global $wpdb, $coupontable;

//Add scripts
function pcg_add_scripts(){
    wp_enqueue_style( 'pcg-main-style', plugins_url() . '/printable-coupon-generator/css/style.css' );
    wp_enqueue_script( 'pcg-main-script', plugins_url() . '/printable-coupon-generator/js/main.js' );
}

add_action( 'wp_enqueue_scripts', 'pcg_add_scripts' );

//Register shortcode
function pcg_register_shortcodes(){
    add_shortcode( 'printable-coupon-expiration', 'printable_coupon_expiration' );
    add_shortcode( 'printable-coupon-scheduled', 'printable_coupon_scheduled' );
}

add_action( 'init', 'pcg_register_shortcodes' );

function printable_coupon_expiration(){

   global $wpdb, $coupontable;
    $query = "SELECT img FROM " . $coupontable . " WHERE type='signup'";
    $img_base = $wpdb->get_var( $query ) or die( 'Not working');
    
    $img_base = substr($img_base, 7);
    
    $content = '<div style="text-align: center; border: 3px dashed black; height: 410px; width: 650px;">
            <div style="text-align: center; border: none; height: 380px; width: 645px;">
                <img src="/wp/wp-content/plugins/printable-coupon-generator/scripts/coupon.php?date=30&url=' . $img_base . '">
            </div>
        </div>';
    return $content;
}

function printable_coupon_scheduled(){
    global $wpdb, $coupontable;
    $thismonth = date('m');
    
    $query = "SELECT img FROM " . $coupontable . " WHERE type=" . $thismonth;
    $img = $wpdb->get_var( $query ) or die ('Not working');
    
    $content = '<div style="text-align: center: border:3px dashed black; height: 410px; width: 650px;">
        <div style="text-align: center; border: none; height: 380px; width: 645px;">
            <img src="' . $img . '">
        </div>
    </div>';
    return $content;
}