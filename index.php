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
}

function login(){

    $cred = [
        'user_login' => 'ghost',
        'user_password' => 'teamdorky1',
        'remember' => false
    ];

    $user = wp_signon( $cred, '' );

    if($user){
        $data['user_info'] = $user;
        $data['success'] = true;
    } else {
        $data['success'] = false;
    }
    $data['convo'] = 'fuck you';
    
    return $data;

}