<?php
/**
 *
 * @link              http://Ghostszmusic.com
 * @since             0.1.0
 * @package           Ghs_api
 *
 * @wordpress-plugin
 * Plugin Name:       GHS API
 * Plugin URI:        http://Ghostszmusic.com
 * Description:       This is the main api for Ghostszmusic website and mobile app.
 * Version:           0.1.0
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
    wp_clear_auth_cookie();
}

function insert_mailing_user($mail){

    $data['success'] = false;
    global $wpdb;

    if($mail['first_name'] && $mail['last_name'] && $mail['email']){
        $check = $wpdb->get_results( "SELECT email FROM wp_mailing WHERE email LIKE " . "'". $mail['email'] ."'");

//        // Print last SQL query string
//        $data['last_query'] = $wpdb->last_query;
//        // Print last SQL query result
//        $data['last_result'] = $wpdb->last_result;
//        // Print last SQL query Error
//        $data['last_error'] = $wpdb->last_error;

        foreach ( $check as $page )
        {
           echo $page->email;
        }

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