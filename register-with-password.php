<?php

/*
Plugin Name: RegisterWithPassword
Plugin URI: http://github.com/derektu/registerwithpassword
Description: Allows user to set password during registration.
Text Domain: rwop
Author: DerekTu
Author URI: http://github.com/derektu
Licence: GPLv3
Version: 1.0.0
*/

function myplugin_init() {
    load_plugin_textdomain( 'rwop', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'myplugin_init');

// Hide the 'a password will be emailed to you' message on the registration form
//
add_action('login_enqueue_scripts', 'xq_enqueue_scripts');
function xq_enqueue_scripts() {
    wp_enqueue_style('register-with-password-css', plugins_url('css/style.css', __FILE__ ));
}

// Add password fields to registration form
//
add_action( 'register_form', 'xq_add_password_fields' );
function xq_add_password_fields() {
?>
    <p>
        <label for="password"><?php _e("Password","rwop")?> <br/>
            <input id="password" class="input" type="password" size="25" value="" name="password" />
        </label>
    </p>
    <p>
        <label for="repeat_password"><?php _e("Repeat password","rwop")?><br/>
            <input id="repeat_password" class="input" type="password" size="25" value="" name="repeat_password" />
        </label>
    </p>
<?php
}

// Check the form for errors
//
add_action( 'register_post', 'xq_check_password_fields', 10, 3);
function xq_check_password_fields($login, $email, $errors) {
    if ( $_POST['password'] !== $_POST['repeat_password'] ) {
        $errors->add( 'passwords_not_matched', '<strong>' . __("ERROR","rwop") . '</strong>' . __(": Passwords do not match","rwop") );
    }
    if ( strlen( $_POST['password'] ) < 8 ) {
        $errors->add( 'password_too_short', '<strong>' . __("ERROR","rwop") . '</strong>' . __(": Passwords must be at least eight characters long","rwop"));
    }
}

// Storing WordPress user password into database on registration
//
add_action( 'user_register', 'xq_store_password_fields', 100 );
function xq_store_password_fields( $user_id ){
    $userdata = array();

    $userdata['ID'] = $user_id;
    if ( $_POST['password'] !== '' ) {
        $userdata['user_pass'] = $_POST['password'];
    }
    $new_user_id = wp_update_user( $userdata );
}

// Disable user email notification
//
if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification() {
    }
}

// TODO: 註冊成功後重新登入的畫面上有一句 "註冊完畢，請檢查你的email" (不知道怎麼拿掉)
// 目前先用 Login/Logout redirect, 把post registration後的url改成 'wp-login.php?checkemail=', 這樣子就變成一個標準的login form了
//
