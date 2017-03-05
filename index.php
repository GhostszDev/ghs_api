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

    register_rest_route('ghs_api/v1', '/ghs_post/',
        array(
            'methods' => 'POST',
            'callback' => 'ghs_post'
        )
    );

    register_rest_route('ghs_api/v1', '/logout/',
        array(
            'methods' => 'GET',
            'callback' => 'logout'
        )
    );

    register_rest_route('ghs_api/v1', '/carouselItems/',
        array(
            'methods' => 'GET',
            'callback' => 'carouselItems'
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

    register_rest_route('ghs_api/v1', '/post_comment/',
        array(
            'methods' => 'POST',
            'callback' => 'post_comment'
        )
    );
}

function logout(){

    $data['success'] = false;

    if ( is_user_logged_in() ) {

        wp_clear_auth_cookie();
        $data['success'] = true;
    } else {

        $data['error_message'] = "You can't logout because you haven't logged in yet!";
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

        $data['error_message'] = "You're not currently signed in!";
    }

    return $data;

}

function send_highscore(){
    global $wpdb;

    $data['success'] = false;

    $game = $_REQUEST['game'];

    if($game){

        $stats = [
            'score' => $_REQUEST['score'],
            'gamer_id' => $_REQUEST['user_id']
        ];

        $wpdb->insert($game, $stats);
        $data['success'] = true;

    } else {

        $data['success'] = false;
        $data['error_message'] = "Their is no such game!";

    }


    return $data;

}

function post_comment(){

    $data['success'] = false;

    $comment_post_ID = $_REQUEST['comment_id'];
    $comment_author = $_REQUEST['user_name'];
    $comment_author_email = $_REQUEST['user_email'];
    $comment_content = $_REQUEST['comment'];
    $comment_type = $_REQUEST['comment_type'];
    $comment_parent = $_REQUEST['comment_parent'];
    $user_id = $_REQUEST['user_id'];

    $commentdata = array(
        'comment_post_ID' => $comment_post_ID, // to which post the comment will show up
        'comment_author' => $comment_author, //fixed value - can be dynamic
        'comment_author_email' => $comment_author_email, //fixed value - can be dynamic
        'comment_author_url' => '', //fixed value - can be dynamic
        'comment_content' => $comment_content, //fixed value - can be dynamic
        'comment_type' => $comment_type, //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
        'comment_parent' => $comment_parent, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
        'user_id' => $user_id, //passing current user ID or any predefined as per the demand
    );

//Insert new comment and get the comment ID
    $comment_id = wp_new_comment( $commentdata );

    if($comment_id){
        $data['success'] = true;
    }

    return $data;

}

function login(){

    $cred = [
        'user_login' => $_REQUEST['user_login'],
        'user_password' => $_REQUEST['user_password'],
        'remember' => $_REQUEST['remember']
    ];

    $user = wp_signon( $cred, false );

    if ( is_wp_error($user) ){
        $data['error_message'] = "Please check your username/and or password.";
        $data['success'] = false;
    } else {
        $data['success'] = true;
        $data['user_info'] = getuserdata();
    }


    return $data;

}

function ghs_post(){

    $data['success'] = false;

    $args = [
        'numberposts' => $_REQUEST['post_num'],
        'category' => $_REQUEST['cat'],
        'exclude' => $_REQUEST['ex']
    ];

    $post = get_posts($args);

    if($post){

        $data['success'] = true;
        $data['posts'] = $post;

        $key = 0;
        foreach ($post as $p){

            $thumb = get_the_post_thumbnail_url( $p->ID, 'medium_large' );

            $data['post'][$key]['url'] = site_url('/') . 'blog/' . $p->post_name;
            $data['post'][$key]['title'] = ucwords($p->post_title);
            $data['post'][$key]['comment_count'] = $p->comment_count;
            $data['post'][$key]['date'] = get_the_time('M j, Y', $p->ID);
            $data['post'][$key]['id'] = $p->ID;
            $data['post'][$key]['short_ver'] = substr($p->post_content, 0, 150) . ' <a href="' . site_url('/') . 'blog/' . $p->post_name . '" title="' . ucwords($p->post_title) . '" style="color: #8777ff;"> [more]</a>';
            $data['post'][$key]['thumb'] = $thumb;

            $key++;
        }

    }

    return $data;

}

function carouselItems(){

    $data['success'] = false;

    $post1 = get_posts(
        array(
        'numberposts' => 1,
        'category' => 94
        )
    );
    $post2 = get_posts(
        array(
            'numberposts' => 1,
            'category' => 71
        )
    );
    $post3 = get_posts(
        array(
            'numberposts' => 1,
            'category' => 51
        )
    );

    $post = array_merge($post1, $post2, $post3);

    if($post){

        $data['success'] = true;
        $data['posts'] = $post;

        $key = 0;
        foreach ($post as $p){

            $thumb = get_the_post_thumbnail_url( $p->ID, 'medium_large' );

            $data['post'][$key]['url'] = site_url('/') . 'blog/' . $p->post_name;
            $data['post'][$key]['title'] = ucwords($p->post_title);
            $data['post'][$key]['comment_count'] = $p->comment_count;
            $data['post'][$key]['date'] = get_the_time('M j, Y', $p->ID);
            $data['post'][$key]['id'] = $p->ID;
            $data['post'][$key]['short_ver'] = substr($p->post_content, 0, 150) . ' <a href="' . site_url('/') . 'blog/' . $p->post_name . '" title="' . ucwords($p->post_title) . '" style="color: #8777ff;"> [more]</a>';
            $data['post'][$key]['thumb'] = $thumb;

            $key++;
        }

    }

    return $data;

}