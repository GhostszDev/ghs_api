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

namespace ghs_api\api{

    class webservice {

        //main login for all users of ghostszmusic
        function login(){

            $results = [
                'success' => false,
                'message' => "I'm super cool!"
            ];

            $data = json_encode($results);

            return $data;

        }

    }

}