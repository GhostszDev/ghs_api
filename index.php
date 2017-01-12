<?php
/**
 *
 * @link              http://Ghostszmusic.com
 * @since             1.0
 * @package           Ghs_api
 *
 * @wordpress-plugin
 * Plugin Name:       GHS API v1
 * Plugin URI:        http://Ghostszmusic.com
 * Description:       This is the main api for Ghostszmusic website and mobile app.
 * Version:           1.0
 * Author:            Steven "Ghost" Rivera
 * Author URI:        http://Ghostszmusic.com
 */

//adding actions
add_action('rest_api_init', 'ghs_webservice_route');

//functions
function ghs_webservice_route(){

    register_rest_route('ghs_api/v1', '/login/',
        array(
            'methods' => 'POST',
            'callback' => 'login'
        )
    );

    register_rest_route('ghs_api/v1', '/logout/',
        array(
            'methods' => 'GET',
            'callback' => 'logout'
        )
    );

    register_rest_route('ghs_api/v1', '/signup/',
        array(
            'methods' => 'POST',
            'callback' => 'signup'
        )
    );

    register_rest_route('ghs_api/v1', '/mailing/',
        array(
            'methods' => 'POST',
            'callback' => 'mailing'
        )
    );

    register_rest_route('ghs_api/v1', '/getuserdata/',
        array(
            'methods' => 'POST',
            'callback' => 'mailing'
        )
    );
}

function login(){

    $cred = [
        'user_login' => $_REQUEST['user_login'],
        'user_password' => $_REQUEST['user_password'],
        'remember' => $_REQUEST['remember']
    ];

    $user = wp_signon( $cred, false );

    if ( is_wp_error($user) ){
        $data['error'] = "Please check your username/and or password.";
        $data['success'] = false;
    } else {
        $data['success'] = true;
        $data['user_info'] = $user;
    }

    
    return $data;

}

function logout(){

    $data['success'] = false;

    if ( is_user_logged_in() ) {

        wp_clear_auth_cookie();
        $data['success'] = true;
    } else {

        $data['error'] = "You can't logout because you haven't logged in yet!";
    }

    return $data;
}

function insert_mailing_user($mail){

    $data['success'] = false;
    global $wpdb;

    if($mail['first_name'] && $mail['last_name'] && $mail['email']){
        $check = $wpdb->get_results( "SELECT email FROM wp_mailing WHERE email LIKE '". $mail['email'] ."'");

        if($check < 1) {
            $mailing = $wpdb->insert('wp_mailing', $mail);
        }

        if($mailing){
            $data['success'] = true;
            $data['message'] = 'User has been added to mailing list!';
            $data['mailing'] = $mailing;
        } else {
            $data['message'] = 'This email has already been used!';
        }
    } else {
        $data['message'] = 'Their is no data being passed';
    }

//    $data['mailing'] = $mail;

    return $data;

}

function mailing(){

    $mailing_user = [
        'first_name'  => $_REQUEST['first_name'],
        'last_name'  => $_REQUEST["last_name"],
        'email'  => $_REQUEST["email"]
    ];

//    $data['cool'] = $mailing_user;

    $insert = insert_mailing_user($mailing_user);
    $data['insert'] = $insert;

    return $data;

}

function signup(){

    $data['success'] = false;

    $new_user = [
        'user_name' => $_REQUEST['user_name'],
        'first_name' => $_REQUEST['first_name'],
        'last_name' => $_REQUEST['last_name'],
        'email'  => $_REQUEST['email'],
        'password'  => $_REQUEST['password'],
        'mailing' => $_REQUEST['mailing']
    ];

    if ( username_exists($new_user['user_name']) || email_exists($new_user['email']) ) {
        $data['message'] = "Username and/or Email is unavailable";
    }else{
        if($new_user['password']){
            $created = wp_create_user( $new_user['user_name'], $new_user['password'], $new_user['email'] );

            if($new_user['mailing']){
                $data['mailing'] = insert_mailing_user($new_user);
            }

        } else {
            $data['message'] = "Please enter a password";
        }

        if($created){
            $data['success'] = true;
            $data['message'] = "User has been created";
            $data['user'] = $new_user;
        }

    }


    return $data;

}

function social(){

    $social = [
        'id' => $_REQUEST[""],
        'name' => $_REQUEST[""]
    ];

    $data = $social;
    return $data;

}

function getuserdata(){

    $data['success'] = false;

    if ( is_user_logged_in() ) {

        $user = wp_get_current_user();
        $data['user'] = $user;
        $data['success'] = true;
    } else {

        $data['error'] = "You're not currently signed in!";
    }

    return $data;

}