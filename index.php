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
            'callback' => 'getuserdata'
        )
    );

    register_rest_route('ghs_api/v1', '/post_comment/',
        array(
            'methods' => 'POST',
            'callback' => 'post_comment'
        )
    );

    register_rest_route('ghs_api/v1', '/sendgameData/',
        array(
            'methods' => 'POST',
            'callback' => 'sendgameData'
        )
    );

    register_rest_route('ghs_api/v1', '/singlePost/',
        array(
            'methods' => 'POST',
            'callback' => 'singlePost'
        )
    );

    register_rest_route('ghs_api/v1', '/getComments/',
        array(
            'methods' => 'POST',
            'callback' => 'getComments'
        )
    );

    register_rest_route('ghs_api/v1', '/friendsList/',
        array(
            'methods' => 'POST',
            'callback' => 'friendsList'
        )
    );

    register_rest_route('ghs_api/v1', '/addFriend/',
        array(
            'methods' => 'POST',
            'callback' => 'addFriend'
        )
    );

    register_rest_route('ghs_api/v1', '/grabGameList/',
        array(
            'methods' => 'GET',
            'callback' => 'grabGameList'
        )
    );

    register_rest_route('ghs_api/v1', '/searchFriend/',
        array(
            'methods' => 'POST',
            'callback' => 'searchFriend'
        )
    );

    register_rest_route('ghs_api/v1', '/contactUs/',
        array(
            'methods' => 'POST',
            'callback' => 'contactUs'
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
    global $wpdb;

    $new_user = [
        'user_login' => $_REQUEST['user_name'],
        'firstName' => $_REQUEST['first_name'],
        'lastName' => $_REQUEST['last_name'],
        'user_email'  => $_REQUEST['email'],
        'user_pass'  => $_REQUEST['password'],
        'gender' => $_REQUEST['gender'],
        'birthday' => $_REQUEST['birthday']
    ];

//    $data['test'] = $new_user;

    if ( username_exists($new_user['user_login']) || email_exists($new_user['user_email']) ) {
        $data['error_message'] = "Username and/or Email is unavailable";
    }else{

        if($new_user['user_pass']){
            $created = $wpdb->insert('wp_users', $new_user);

            if($created){
                $data['success'] = true;
                $data['message'] = "User was created!";

                if($new_user['mailing']){
                    $data['mailing'] = insert_mailing_user($new_user);
                } else {
                    $data['mailing'] = 'Not on the mailing list';
                }

            }else{
                $data['success'] = false;
                $data['error_message'] = "Error: User was not created!";
            }


        } else {
            $data['message'] = "Please enter a password";
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

    $user_ID = $_REQUEST['user_ID'];

    $user = get_userdata($user_ID);
//    $data['test'] = $user;

    if($user){

        $data['success'] = true;

        $data['user']['ID'] = $user->data->ID;
        $data['user']['name'] = ucwords($user->data->firstName . ' ' . $user->data->lastName);
        $data['user']['email'] = $user->data->user_email;
        $data['user']['gender'] = $user->data->gender;
        $data['user']['userName'] = ucwords($user->data->user_login);
        $data['user']['birthday'] = $user->data->birthday;
        $data['user']['role'] = $user->roles;
        $data['user']['user_icon'] = get_avatar_url($user->data->ID, array(
            'size'=> 40
        ));
        $data['user']['user_icon_big'] = get_avatar_url($user->data->ID, array(
            'size'=> 72
        ));

    } else {

        $data['error_message'] = "You're not currently signed in!";
    }

    return $data;

}

function sendgameData(){
    global $wpdb;

    $data['success'] = false;
//    $data['gameToken'] = "World Def'er: " . md5('WorldDefer');

    $game = $_REQUEST['gameID'];
    $score = $_REQUEST['score'];
    $userID = $_REQUEST['userID'];

//    $data['userID'] = $userID;

    $getGameID = $wpdb->get_results( "SELECT ID, db_name FROM `game_list` WHERE `token` LIKE '" . $game . "'" );

//    $data['test'] = $getGameID[0]->db_name;

    if($getGameID){

        $data['success'] = true;
        $isUserThere = $wpdb->get_results( "SELECT userID FROM `". $getGameID[0]->db_name ."` WHERE `userID` LIKE '" . $userID . "'" );
//        $data['last_query'] = $wpdb->last_query;
//        $data['isUserThere'] = $isUserThere;

        if(!$isUserThere) {

            if ($score) {
                $wpdb->insert(
                    $getGameID[0]->db_name,
                    array(
                        'score' => $score,
                        'userID' => $userID
                        )
                );
                $data['message'] = "Score was added!";
            }

        } else {

            if ($score) {
                $wpdb->update(
                    $getGameID[0]->db_name,
                    array(
                        'score' => $score,
                        'userID' => $userID
                    ),
                    array('userID' => $userID)
                );
                $data['message'] = "Score was added!";
            }

        }

    } else {

        $data['success'] = false;
        $data['error_message'] = "Score failed to be uploaded!";

    }


    return $data;

}

function post_comment(){

    $data['success'] = false;

    $comment_post_ID = $_REQUEST['postID'];
    $comment_author = $_REQUEST['user_name'];
    $comment_author_email = $_REQUEST['user_email'];
    $comment_content = $_REQUEST['comment'];
    $comment_parent = $_REQUEST['comment_parent'];
    $user_id = $_REQUEST['user_id'];

    $commentdata = array(
        'comment_post_ID' => $comment_post_ID, // to which post the comment will show up
        'comment_author' => $comment_author, //fixed value - can be dynamic
        'comment_author_email' => $comment_author_email, //fixed value - can be dynamic
        'comment_author_url' => '', //fixed value - can be dynamic
        'comment_content' => $comment_content, //fixed value - can be dynamic
        'comment_type' => '', //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
        'comment_parent' => $comment_parent, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
        'user_id' => $user_id, //passing current user ID or any predefined as per the demand
    );

//Insert new comment and get the comment ID
    $comment_id = wp_new_comment( $commentdata );

    if($comment_id){
        $data['success'] = true;
    } else {
        $data['error_message'] = "The comment failed to post";
    }

    return $data;

}

function login(){

    $cred = [
        'user_login' => $_REQUEST['user_login'],
        'user_password' => $_REQUEST['user_password'],
        'remember' => $_REQUEST['remember']
    ];

    $user = wp_signon( $cred, true );

    if ( is_wp_error($user) ){
        $data['error_message'] = "Please check your username/and or password.";
        $data['success'] = false;
    } else {
        $data['success'] = true;
        wp_set_auth_cookie($user->data->ID);
        do_action('wp_login', $user->data);
        $userInfo = $user->data;

        $data['ID'] = $userInfo->ID;
        $data['firstName'] = $userInfo->firstName;
        $data['lastName'] = $userInfo->lastName;
        $data['userName'] = $userInfo->user_login;
        $data['email'] = $userInfo->user_email;
        $data['password'] = $cred['user_password'];
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
//        $data['posts'] = $post;

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
//        $data['posts'] = $post;

        $key = 0;
        foreach ($post as $p){

            $thumb = get_the_post_thumbnail_url( $p->ID, 'medium_large' );

            $data['post'][$key]['url'] = site_url('/') . 'blog/' . $p->post_name;
            $data['post'][$key]['title'] = ucwords($p->post_title);
            $data['post'][$key]['comment_count'] = $p->comment_count;
            $data['post'][$key]['date'] = get_the_time('M j, Y', $p->ID);
            $data['post'][$key]['id'] = $p->ID;
            $data['post'][$key]['short_ver'] = substr($p->post_content, 0, 150) . '<a href="' . site_url('/') . 'blog/' . $p->post_name . '" title="' . ucwords($p->post_title) . '" style="color: #8777ff;"> [more]</a>';
            $data['post'][$key]['thumb'] = $thumb;

            $key++;
        }

    }

    return $data;

}

function singlePost(){

    $data['success'] = false;

    $post_id = $_REQUEST['postID'];

    $post = get_post($post_id);

    if(!$post){
        $data['error_message'] = "No Post with ID: " . $post_id;
    } else {

        $data['success'] = true;
        $data['post'] = $post;
    }

    return $data;

}

function getComments(){

    $data['success'] = false;

    $post = array(
        'post_id' => $_REQUEST['postID']
    );

    $comments = get_comments($post);

    if(!$comments){

        $data['error_message'] = "No Comments";

    }else{

        $data['success'] = true;

        $key = 0;
        foreach ($comments as $c){

            $data['comment'][$key]['comment_ID'] = $c->comment_ID;
            $data['comment'][$key]['user'] = ucwords($c->comment_author);
            $data['comment'][$key]['user_ID'] = $c->user_id;
            $data['comment'][$key]['date'] = get_comment_date('m/d/Y', $c->comment_ID);
            $data['comment'][$key]['comment'] = $c->comment_content;
            $data['comment'][$key]['comment_parent'] = $c->comment_parent;
            $data['comment'][$key]['comment_parent'] = $c->comment_parent;
            $data['comment'][$key]['user_img'] = get_avatar_url($c->comment_ID, array(
                'size'=> 40
            ));

            $key++;

        }

    }

    return $data;

}

function friendsList(){

    $userID = $_REQUEST['userID'];

    $data['success'] = false;
    global $wpdb;

    $friends = $wpdb->get_results( "SELECT * FROM friends WHERE userID LIKE '". $userID ."'");

    if($friends){

        $data['success'] = true;
        $data['friend'] = $friends;

    } else {

        $data['message'] = "Please add a friend for your list.";

    }

    return $data;

}

function addFriend(){

    $data['success'] = false;
    $userID = $_REQUEST['userID'];
    $friendID = $_REQUEST['friendID'];
    $data['test'] = $userID;

}

function grabGameList(){

    global $wpdb;
    $data['success'] = false;

    $game = $wpdb->get_results( "SELECT `ID`, `img`, `Name`, `link`, 'des' FROM `game_list` " );

    if($game){
        $data['success'] = true;
        $data['gameList'] = $game;
    } else {
        $data['error_message'] = "No games available";
    }

    return $data;

}

function contactUs(){

    $data['success'] = false;
    global $wpdb;

    $contact = array(
        "name" => $_REQUEST['name'],
        "email" => $_REQUEST['email'],
        "msg" => $_REQUEST['msg']
    );

//    $data['test'] = $contact;

    if(!$contact['name'] || !$contact['email'] || !$contact['msg']){

        $data['success'] = false;
        $data['error_message'] = "Something went wrong when sending the contact us message.";

    } else {

        $check = $wpdb->insert('contact-Us', $contact);

        if($check){

            $data['success'] = true;
            $data['message'] = "Message has been sent";

        } else {

            $data['success'] = false;
            $data['error_message'] = "Something went wrong when sending the contact us message to us.";

        }

    }

    return $data;

}