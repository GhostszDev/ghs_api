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

    register_rest_route('ghs_api/v1', '/cool/',
        array(
            'methods' => 'GET',
            'callback' => 'login'
        )
    );
}

function login(){

    $creds = [
        'user_login' => $_POST("user_login"),
        'user_password' => $_POST("user_password"),
        'remember' => $_POST("remember")
    ];

    $user = wp_signon( $creds, false );

    if ( is_wp_error($user) ) {

        $data['error'] = $user->get_error_message();
        $data["success"] = false;

    } else {
        $data["success"] = true;
    }

    return json_encode($data);

}