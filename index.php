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
    wp_logout();
}

function signup(){

}

function social(){

    $social = [
        id => $_REQUEST(""),
        name => $_REQUEST("")
    ];

    $data = $social;
    return $data;

}