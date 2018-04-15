<?php

//variables
global $wpdb;
global $coupontable;
global $couponfilename;

//define custom table 
$coupontable = $wpdb->prefix . 'pcg_coupons';

$thispage = get_permalink();

function pcg_settings_page() {
    //Show all active coupons and click to go to specific delete/edit page.
    global $wpdb, $coupontable;
    
    $coupons = $wpdb->get_results( 
        "SELECT id, img, name 
        from $coupontable" 
    ) ;
    
    foreach( $coupons as $coupon ) {
        ?>
            <figure>
                <a href="admin.php?page=edit_coupon&id=<?php echo $coupon->id; ?>"><img src="<?php echo $coupon->img; ?>"></a>
                <figcaption><?php echo $coupon->name; ?></figcaption>
            </figure>
        <?php
    }
}

function pcg_edit_coupon() {
    
}

function pcg_add_coupon_img() {
    //Form to upload a new coupon img and add it to the settings table
    global $wpdb, $coupontable;
    
    if ( $_SERVER['REQUEST_METHOD'] == "POST") {
        check_admin_referer('coupons');
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        $couponimg = $_POST['coupon_img'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        
        $coupon_img = $_POST['coupon_img'];
        //Get coupon image
        $imgid = media_handle_upload('coupon_img', 0);
        
        if ( is_wp_error( $imgid ) ) {
            $problem = $imgid->get_error_message();
    	} 
        
        $coupon_img = wp_get_attachment_url( $imgid );
        
        if( $type == "signup"){ //only one signup coupon
            
            $wpdb->update( 
                $coupontable,
                array(
                    'name' => $name,
                    'img' => $coupon_img
                ),
                array( 'type' => 'signup' )
            );
            
            
            
        } else { //multiple monthly coupons
            $wpdb->insert(
                $coupontable,
                array(
                    'name' => $name,
                    'img'   => $coupon_img,
                    'type' => $type
                )
            );    
        }
        
       ?><h2>New coupon recorded</h2><?php
    } else {
        ?><div><form method="POST" ENCTYPE="multipart/form-data"> <?php
        wp_nonce_field('coupons');
        ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th>Primary Coupon Label:</th>
                        <td><input type=text name="name" size=70></td>
                    </tr>
                    <tr>
                        <th>Type of Coupon:</th>
                        <td><select name="type">
                            <option value="0">For sign up</option>
                            <option value="1">January Coupon</option>
                            <option value="2">February Coupon</option>
                            <option value="3">March Coupon</option>
                            <option value="4">April Coupon</option>
                            <option value="5">May Coupon</option>
                            <option value="6">June Coupon</option>
                            <option value="7">July Coupon</option>
                            <option value="8">August Coupon</option>
                            <option value="9">September Coupon</option>
                            <option value="10">October Coupon</option>
                            <option value="11">November Coupon</option>
                            <option value="12">December Coupon</option>
                            </select></td>
                    </tr>
                    <tr>
                        <th>Coupon Image:</th>
                        <td><input type=file id="coupon_img" name="coupon_img" multiple="false"></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><?php submit_button('Add Coupon'); ?></td>
                    </tr>
                </tbody>
            </table>
        </form> <?php
    }
}

//installation instructions - creates custom tables in DB
function pcg_install() {
    global $wpdb, $coupontable;

    $ccollate = $wpdb->get_charset_collate();
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    //Create video table first
    $create = "CREATE IGNORE TABLE " . $coupontable . "(
        id mediumint(9) NOT NULL AUTO_INCREMENT, 
        name varchar(150), 
        type int(11), 
        img varchar(1000),
        PRIMARY KEY  (id)
        ) $ccollate;";
        
    dbDelta( $create );
}

function pcg_style_enqueue(){
    wp_register_style( 'pcg-admin-styles',  plugins_url() . '/printable-coupon-generator/css/admin_style.css' );
    wp_enqueue_style( 'pcg-admin-styles' );
}

add_action('admin_menu', 'pcg_options_menu_link');
add_action('admin_enqueue_scripts', 'pcg_style_enqueue');

function pcg_options_menu_link() {
  add_options_page(
    'Printable Coupon Generator Options', //title of page
    'Printable Coupon Generator',
    'manage_options',
    'pcg-options', //slug
    'pcg_options_content' //function to display content
  );
}

//Register the settings
function pcg_register_settings() {
  register_setting('pcg_settings_group', 'pcg_settings', 'pcg_sanitize_options');
}

//create cbdb options menu on the top level
function pcg_create_menu() {
    add_menu_page( 'Printable Coupons Plugin Page', 'Printable Coupons Plugin', 'manage_options', 'pcg_main_menu', 'pcg_settings_page');
    add_submenu_page( 'pcg_main_menu', 'Add New Images', 'Add Images', 'manage_options', 'add_coupon_img', 'pcg_add_coupon_img' );
    add_submenu_page( '', 'Edit Coupon', 'Edit Coupon', 'manage_options', 'edit_coupon', 'pcg_edit_coupon' );
    
    add_action('admin_init', 'pcg_register_settings');
}


add_action('admin_menu', 'pcg_create_menu');
?>