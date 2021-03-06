<?php
/**
 *
 * @link              http://Ghostszmusic.com
 * @since             1.0
 * @package           Ghs_api
 *
 * @wordpress-plugin
 * Plugin Name:       GHS API
 * Plugin URI:        http://Ghostszmusic.com
 * Description:       This is the main api for Ghostszmusic website and mobile app.
 * Version:           1.0
 * Author:            Steven "Ghost" Rivera
 * Author URI:        http://Ghostszmusic.com
 */

//adding actions
add_action('rest_api_init', 'ghs_webservice_route');
include (dirname( __FILE__ ) . '/libs/ghs_includes.php');

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

    register_rest_route('ghs_api/v1', '/userFeed/',
        array(
            'methods' => 'POST',
            'callback' => 'userFeed'
        )
    );

    register_rest_route('ghs_api/v1', '/contactUs/',
        array(
            'methods' => 'POST',
            'callback' => 'contactUs'
        )
    );

    register_rest_route('ghs_api/v1', '/userUpdate/',
        array(
            'methods' => 'POST',
            'callback' => 'userUpdate'
        )
    );

    register_rest_route('ghs_api/v1', '/badgeUnlock/',
        array(
            'methods' => 'POST',
            'callback' => 'badgeUnlock'
        )
    );

    register_rest_route('ghs_api/v1', '/uploadMedia/',
        array(
            'methods' => 'POST',
            'callback' => 'uploadMedia'
        )
    );

    register_rest_route('ghs_api/v1', '/sendingEmail/',
        array(
            'methods' => 'POST',
            'callback' => 'sendingEmail'
        )
    );

    register_rest_route('ghs_api/v1', '/social/',
        array(
            'methods' => 'POST',
            'callback' => 'social'
        )
    );

    register_rest_route('ghs_api/v1', '/getRecentComments/',
        array(
            'methods' => 'GET',
            'callback' => 'getRecentComments'
        )
    );

    register_rest_route('ghs_api/v1', '/countUserCJ/',
        array(
            'methods' => 'GET',
            'callback' => 'countUserCJ'
        )
    );

    register_rest_route('ghs_api/v1', '/userCount/',
        array(
            'methods' => 'GET',
            'callback' => 'userCount'
        )
    );

    register_rest_route('ghs_api/v1', '/ghsfriendcheck/',
        array(
            'methods' => 'GET',
            'callback' => 'ghsfriendcheck'
        )
    );

    register_rest_route('ghs_api/v1', '/updateImg/',
        array(
            'methods' => 'POST',
            'callback' => 'updateImg'
        )
    );

    register_rest_route('ghs_api/v1', '/updataUser/',
        array(
            'methods' => 'POST',
            'callback' => 'updataUser'
        )
    );

    register_rest_route('ghs_api/v1', '/userToken/',
        array(
            'methods' => 'POST',
            'callback' => 'userToken'
        )
    );

	if ( class_exists( 'WooCommerce' ) ) {
		register_rest_route( 'ghs_api/v1', '/getCart/',
			array(
				'methods'  => 'POST',
				'callback' => 'getCart'
			)
		);
	}

}

function userToken($userName, $password){

    $data['success'] = false;

//    ($user['permissions'] == 'admin') ? true : false;
    $user_name = $_REQUEST['userName'] ?: $userName;
    $user_pass = $_REQUEST['user_password'] ?: $password;

    $result = wp_remote_post(site . 'wp-json/jwt-auth/v1/token', array(
        'method' => 'POST',
        'headers' => array(
            'Access-Control-Allow-Credentials' => true
        ),
        'body' => array(
            'username' => $user_name,
            'password' => $user_pass
        ),
        'cookies' => array()
    ));

    if ( is_wp_error( $result ) ) {
        $data['error_message'] = $result->get_error_message();
    } else {
        $data['success'] = true;
        $data['user'] = json_decode(wp_remote_retrieve_body($result), true);

//        $data['l'] = $data['user']['token'];
        setcookie('token', (string)$data['user']['token'], time() + (86400 * 7), '/', base_site);
        if(isset($_COOKIE['token'])){
            $data['cookie_set'] = true;
        } else {
            $data['cookie_set'] = false;
        }
    }

    return $data;
}

function getuserdata($userFeed = false){

	$uf = $userFeed;

    $data['success'] = false;
    global $wpdb;

    $userID = apply_filters('determine_current_user', false);
	$blog_id = get_current_blog_id();

    $user = get_userdata($userID);
//    $data['test'] = $userID;

    if($user){

        $data['success'] = true;

        $data['user']['ID'] = $user->data->ID;
        $data['user']['first_name'] = ucwords($user->data->firstName);
        $data['user']['last_name'] = ucwords($user->data->lastName);

        if(!empty($user->data->firstName) && !empty($user->data->lastName)){
            $data['user']['name'] = ucwords($user->data->firstName . ' ' . $user->data->lastName);
        } else {
            $data['user']['name'] = '';
        }

        $data['user']['email'] = $user->data->user_email;
        $data['user']['gender'] = $user->data->gender;
        $data['user']['userName'] = ucwords($user->data->user_login);
        $data['user']['birthday'] = $user->data->birthday;
        list($year, $month, $day) = explode('[/.-]', $user->data->birthday);
        $data['user']['month'] = $month;
        $data['user']['date'] = $day;
        $data['user']['year'] = $year;
        $userIcon[] = get_user_img($user->data->ID, 40);
        $userIconBig[] = get_user_img($user->data->ID, 72);
        $userIcon100[] = get_user_img($user->data->ID, 100);
        $data['user']['useBlob'] = $userIcon[0]['yes'];
        $data['user']['user_icon'] = $userIcon[0]['img'];
        $data['user']['user_icon_big'] = $userIconBig[0]['img'];
        $data['user']['user_icon_100'] = $userIcon100[0]['img'];
        $data['user']['blog_id'] = $blog_id;
        $data['user']['google'] = $user->data->google;
        $data['user']['facebook'] = $user->data->facebook;

        $img = $wpdb->get_results("SELECT `backgroundImg` FROM `userStats` WHERE `userID` = '" . $user->data->ID ."'");

        if($img){

            $data['user']['backgroundImg'] = $img;

        } else {

            $data['user']['backgroundImg'] = "/wp-content/uploads/2017/05/profile-bg.png";

        }

    } else {

        $data['error_message'] = "You're not currently signed in!";
    }

    return $data;

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
        $check = $wpdb->get_results( "SELECT email FROM wp_mailing WHERE email LIKE '". $mail['email'] ."' LIMIT 1");

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

function mailing($firstName, $lastName, $email){

    $mailing_user = [
        'first_name'  => $_REQUEST['first_name'] ?: $firstName,
        'last_name'  => $_REQUEST["last_name"] ?: $lastName,
        'email'  => $_REQUEST["email"] ?: $email
    ];

//    $data['cool'] = $mailing_user;

    $insert = insert_mailing_user($mailing_user);
    $data['insert'] = $insert;

    return $data;

}

function login($user = []){

    global $wpdb;
    $data['success'] = false;

    $cred = [
        'user_login' => $_REQUEST['user_name'] ?: $user['user_name'],
        'user_password' => $_REQUEST['user_password'] ?: $user['password'],
        'remember' => $_REQUEST['remember'] ?: true
    ];



    $gameID = $_REQUEST['gameID'];

    $user = wp_signon( $cred, true );


    if ( is_wp_error($user) ){
        $data['error_message'] = "Please check your username/and or password.";
        $data['success'] = false;
    } else {
        $data['success'] = true;
        do_action('wp_login', $cred['user_login'], $user);
        $token = userToken($cred['user_login'], $cred['user_password']);


        $data['token'] = $token['user']['token'];
        $user = getuserdata();
        $data['user'] = $user['user'];

        if($gameID){

            switch($gameID) {
                case '7bd41fb04c8fac3edd23b749405d052a':
                    $score = $wpdb->get_results("SELECT `ID`, `score` FROM `wdef_db` WHERE `ID` = '" . $user->data->ID . "'");
                    break;
            }

            if($score){
                $data['highscore'] = $score[0]->score;
            }

        }
    }

    return $data;

}

function signup($user = []){

    $data['success'] = false;
    global $wpdb;

    $new_user = [
        'user_login' => $_REQUEST['user_name'] ?: $user['user_name'],
        'firstName' => $_REQUEST['first_name'] ?: $user['first_name'],
        'lastName' => $_REQUEST['last_name'] ?: $user['last_name'],
        'user_email'  => $_REQUEST['email'] ?: $user['email'],
        'user_pass'  => $_REQUEST['password'] ?: $user['user_pass'],
        'gender' => $_REQUEST['gender'] ?: $user['gender'],
        'birthday' => $_REQUEST['birthday'] ?: $user['birthday'],
        'facebook' => $_REQUEST['FBID'] ?: $user['FBID'],
        'google' => $_REQUEST['GID'] ?: $user['GID'],
        'mailing' => $_REQUEST['mailing'] ?: $user['mailing']
    ];

//    $data['test'] = $new_user;
    $insertData = [
        'firstName' => $new_user['firstName'],
        'lastName' => $new_user['lastName'],
        'gender' => $new_user['gender'],
        'birthday' => $new_user['birthday'],
        'facebook' => $new_user['facebook'],
        'google' => $new_user['google']
    ];

    if ( username_exists($new_user['user_login']) || email_exists($new_user['user_email']) ) {
        $data['error_message'] = "Username and/or Email is unavailable";
    }else{

        if($new_user['user_pass']){
            $created = wp_insert_user($new_user);
            $data['user']['ID'] = $created;
            $insert = $wpdb->update('wp_users', $insertData, array('ID'=>$created));
            addMeToFriend($created);
//            $data['insert'] = $insert;
//            $data['insertData'] = $insertData;

            if($created && $insert){
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

    global $wpdb;
    $data['success'] = false;

    $social = [
        'user_login' => $_REQUEST['user_name'],
        'firstName' => $_REQUEST['first_name'],
        'lastName' => $_REQUEST['last_name'],
        'user_email'  => $_REQUEST['email'],
        'user_pass'  => $_REQUEST['password'],
        'gender' => $_REQUEST['gender'],
        'birthday' => $_REQUEST['birthday'],
        'FBID' => $_REQUEST['FBID'],
        'GID' => $_REQUEST['GID'],
        'mailing' => true
    ];

//    $data['social'] = $social;


    if($social['FBID'] != "" || $social['FBID'] != null){

        $check = $wpdb->get_results('SELECT `id`, `user_login`, `user_pass`, `facebook` FROM `wp_users` WHERE ' . ' `facebook` =  "' . $social['FBID'] . '" OR `facebook` != ""' . " LIMIT 1");

    } else if($social['GID'] != "" || $social['GID'] != null) {

        $check = $wpdb->get_results('SELECT `id`, `user_login`, `user_pass`, `google` FROM `wp_users` WHERE ' . ' `google` =  "' . $social['GID'] . '" OR `google` != ""' . " LIMIT 1");

    }

//    $data['last'] = $wpdb->last_query;
//    $data['error'] = $wpdb->show_errors;
//    $data['result'] = $check;

    if($check){

        foreach ($check as $c){

            $u['user_name'] = $c->user_login;
            $u['password'] = $c->user_pass;
            $u['id'] = $c->id;

        }

//        $data['user'] = $u;

        wp_set_current_user( $u['id'], $u['user_name'] );
        wp_set_auth_cookie( $u['id'] );
        do_action( 'wp_login', $u);
        $data['success'] = true;

    } else {

        $social['user_pass'] = wp_generate_password( $length=12, $include_standard_special_chars=false );
        $signup = signup($social);

//        $data['signup'] = $signup;
        $user = getuserdata($signup['user']['ID']);

        wp_set_current_user( $user['user']['ID'], $user['user']['userName'] );
        wp_set_auth_cookie( $user['user']['ID'] );
        do_action( 'wp_login', $user['user']);
        $data['success'] = true;

    }

    return $data;

}

function sendgameData(){
    global $wpdb;

    $data['success'] = false;
//    $data['gameToken'] = "World Def'er: " . md5('WorldDefer');

    $game = $_REQUEST['gameID'];
    $score = $_REQUEST['score'];
    $userID = get_current_user_id();

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

function ghs_post(){

    $data['success'] = false;

    $args = [
        'numberposts' => $_REQUEST['post_num'],
        'category' => $_REQUEST['cat'],
        'exclude' => $_REQUEST['ex'],
        'offset' => $_REQUEST['offset'],
        'post_status' => 'publish'
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

            $role = get_userdata($c->ID);

            $data['comment'][$key]['comment_ID'] = $c->comment_ID;
            $data['comment'][$key]['user'] = ucwords($c->comment_author);
            $data['comment'][$key]['user_ID'] = $c->user_id;
            $data['comment'][$key]['date'] = get_comment_date('m/d/Y', $c->comment_ID);
            $data['comment'][$key]['comment'] = $c->comment_content;
            $data['comment'][$key]['comment_parent'] = $c->comment_parent;
            $data['comment'][$key]['role'] = $role->roles[0];
            $data['comment'][$key]['user_img'] = get_user_img($c->user_id, 40);

            $key++;

        }

    }

    return $data;

}

function friendsList(){

    $userID = get_current_user_id();

    $data['success'] = false;
    global $wpdb;

    $friends = $wpdb->get_results( "SELECT friendID, userID, approved FROM friends WHERE userID = '". $userID ."' OR friendID = '" . $userID . "'");

    if($friends){

        $data['success'] = true;

        $key = 0;
        foreach ($friends as $f){

            if($f->friendID != $userID) {
                $gt = $wpdb->get_results("SELECT ID, firstName, lastName, user_login, user_status FROM wp_users WHERE ID LIKE '" . $f->friendID . "'");

                foreach ($gt as $r) {

                    $role = get_userdata($r->ID);

                    $data['friend'][$key]['ID'] = $r->ID;
                    $data['friend'][$key]['firstName'] = $r->firstName;
                    $data['friend'][$key]['lastName'] = $r->lastName;
                    $data['friend'][$key]['userName'] = $r->user_login;
                    $data['friend'][$key]['role'] = $role->roles[0];

                    if ($r->user_status == 0) {
                        $data['friend'][$key]['status'] = "offline";
                    } else {
                        $data['friend'][$key]['status'] = "online";
                    }

                    $data['friend'][$key]['user_icon'] = get_user_img($r->ID, 40);
                    $data['friend'][$key]['link'] = "user-profile/" . $r->user_login;

                }
            } else {

                $gt = $wpdb->get_results("SELECT ID, firstName, lastName, user_login, user_status FROM wp_users WHERE ID LIKE '" . $f->userID . "'");

                foreach ($gt as $r) {

                    $role = get_userdata($r->ID);

                    $data['friend'][$key]['ID'] = $r->ID;
                    $data['friend'][$key]['firstName'] = $r->firstName;
                    $data['friend'][$key]['lastName'] = $r->lastName;
                    $data['friend'][$key]['userName'] = $r->user_login;
                    $data['friend'][$key]['role'] = $role->roles[0];

                    if ($r->user_status == 0) {
                        $data['friend'][$key]['status'] = "offline";
                    } else {
                        $data['friend'][$key]['status'] = "online";
                    }

                    $data['friend'][$key]['user_icon'] = get_user_img($r->ID, 40);
                    $data['friend'][$key]['link'] = "user-profile/" . $r->user_login;

                }

            }

            $key++;
        };

        $data['friendCount'] = $key;

    } else {

        $data['message'] = "Please add a friend for your list.";

    }

    return $data;

}

function addFriend(){

    $data['success'] = false;
    $userID = get_current_user_id();
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

function userFeed(){

    $data['success'] = false;
    global $wpdb;

    $userName = $_REQUEST['userName'];

    $check = $wpdb->get_results('SELECT `ID`, `user_login` FROM `wp_users` WHERE `user_login` = "' . $userName . '" LIMIT 1');

    if($check){

        $data['success'] = true;

        $getFeed = $wpdb->get_results('SELECT `userID`, `comment`, `date` FROM `userfeed` WHERE `userID` = "' . $check[0]->ID . '"');

        $userData = getuserdata($check[0]->ID, true);
        $data['user'] = $userData['user'];

        if($getFeed){

            $data['success'] = true;

            $user_meta = get_userdata($check[0]->ID, true);

            $key = 0;
            foreach ($getFeed as $f){

                $data['feed'][$key]['ID'] = $f->userID;
                $data['feed'][$key]['comment'] = $f->comment;
                $data['feed'][$key]['date'] = $f->date;
                $data['feed'][$key]['firstName'] = $user_meta->data->firstName;
                $data['feed'][$key]['lastName'] = $user_meta->data->lastName;
                $data['feed'][$key]['role'] = $user_meta->roles[0];
                $data['feed'][$key]['userName'] = ucwords($check[0]->user_login);
                $data['feed'][$key]['user_icon'] = get_user_img($f->userID, 40);
                $key++;

            }

        } else {

            $data['success'] = false;
            $data['error_message'] = "No messages in feed!";

        }

    }

    return $data;

}

function get_user_img($userID, $imgSize){

    global $wpdb;
    $data['img'] = '';
    $data['yes'] = false;

    $check = $wpdb->get_results('SELECT `ID`, `userImg` FROM `userstats` WHERE `ID` = "' . $userID . '" LIMIT 1');

    if($check[0]->userImg){
        $data['img'] = $check[0]->userImg;
        $data['yes'] = true;
    } else {
        $data['img'] = get_avatar_url($userID, array(
            'size'=> $imgSize
        ));
        $data['yes'] = false;
    }

    return $data;

}

function userUpdate($userID = "", $comment = ""){

    $data['success'] = false;
    global $wpdb;

    $user = array(
        'userID' => get_current_user_id() ?: $userID,
        'comment' => $_REQUEST['comment'] ?: $comment,
        'comment_parent' => $_REQUEST['comment_parent']
    );

    if($user['userID'] && $user['comment']){

        $update = $wpdb->insert('userFeed', $user);

        if($update){

            $data['success'] = true;

        } else {

            $data['success'] = false;
            $data['error_message'] = "Status updated failed!";

        }

    } else {

        $data['success'] = false;
        $data['error_message'] = "Missing some params for updating your status.";

    }

    return $data;

}

function badgeUnlock(){

    $data['success'] = false;
    global $wpdb;

    $unlock = array(
        'userID' => get_current_user_id(),
        'badgeID' => $_REQUEST['badgeID'],
        'gameID' => $_REQUEST['gameID']
    );

    $userName = get_userdata($unlock['userID']);

    if($unlock['userID'] && $unlock['badgeID']){

        switch($unlock['gameID']) {

            case '7bd41fb04c8fac3edd23b749405d052a':
                $getBadge = $wpdb->get_results("SELECT `ID`, `badgeName`, `badgeImg` FROM `wd_badges` WHERE `ID` = '" . $unlock['badgeID'] . "'");

                if ($getBadge) {
                    $data['success'] = true;

                    foreach ($getBadge as $badge) {

                        $unlockUpdate = "<div class='col-xs-4'>
                        <img style='width: 100%' src='". site_url() . $badge->badgeImg . "' />
                    </div>
                    <div class='col-xs-8'>
                        <p>" . ucwords($userName->user_login) . " just unlocked '" . $badge->badgeName . "' Badge</p>
                    </div>";

                        $j = userUpdate($unlock['userID'], $unlockUpdate);


                    }


                } else {

                    $data['success'] = false;
                    $data['error_message'] = "No such badge and/or game exist!";

                }
                break;
        }

    }

    return $data;
}

function post_comment(){

    $data['success'] = false;
    global $wpdb;

    $comment_post_ID = $_REQUEST['postID'];
    $comment_author = $_REQUEST['user_name'];
    $comment_author_email = $_REQUEST['user_email'];
    $comment_content = $_REQUEST['comment'];
    $comment_parent = $_REQUEST['comment_parent'];
    $user_id = get_current_user_id();
    $userName = get_userdata($user_id);

    $userFeedCom = "<div class='col-xs-4'>
                        <a href='" . get_post_permalink($comment_post_ID) . "'><img style='width: 100%' src='" . get_the_post_thumbnail_url($comment_post_ID) . "' /></a>
                    </div>
                    <div class='col-xs-8'>
                        <p>" . $userName->user_login . " posted " . substr($comment_content, 0, 100) . "</p>
                    </div>";

    $user = array(
        "userID" => $user_id,
        "comment" => $userFeedCom
    );

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

        if($comment_parent == 0) {
            $userFeed = $wpdb->insert('userFeed', $user);
            $data['userFeed'] = $userFeed;
        }

    } else {
        $data['error_message'] = "The comment failed to post";
    }

    return $data;

}

function sendingEmail(){

    $data['success'] = false;

    $mail = [
        '' => $_REQUEST['']
    ];

    return $data;

}

function getRecentComments(){

    global $wpdb;
    $data['success'] = false;
    $blogtime = new DateTime(current_time( 'mysql' ));

    $getComments = $wpdb->get_results('SELECT `comment_post_ID`, `user_id`, `comment_content`, `comment_date` FROM `wp_comments` WHERE `user_id` != 0 ORDER BY `comment_date` DESC LIMIT 10');

    if(!$getComments){

        $data['error_message'] = "No Comments";

    }else{

        $data['success'] = true;

        $key = 0;
        foreach ($getComments as $g){

            $user = get_userdata($g->user_id);
            $pastDate = new DateTime($g->comment_date);

            $data['recentComments'][$key]['userComment'] = substr($g->comment_content, 0, 100);
            $diff = date_diff($blogtime, $pastDate);

            $data['recentComments'][$key]['commentTime'] = $diff->format('%a') . " Days Ago";
            $post = get_post($g->comment_post_ID);

            if($post){
                $data['recentComments'][$key]['postName'] = substr($post->post_title, 0, 100);
                $data['recentComments'][$key]['postLink'] = get_permalink($g->comment_post_ID);
            }

            if($user) {

                $data['recentComments'][$key]['userName'] = ucwords($user->data->user_login);

                $data['recentComments'][$key]['user_icon'] = get_avatar_url($user->data->ID, array(
                    'size' => 53
                ));
            }

            $key++;
        }

    }

    return $data;

}

function countUserCJ(){

    $data['success'] = false;
    global $wpdb;

    $query = $wpdb->get_results('SELECT COUNT(*) as users FROM `wp_users`');
//    $check = $wpdb->last_query;

    if($query) {
        $data['success'] = true;
//        $data['usersCount'] = $query[0]->users;
        $send = [
            'userCount' => $query[0]->users
        ];

        $insert = $wpdb->insert('ana_userCount',  $send);

        if($insert){

            $data['success'] = true;

        } else {

            $data['success'] = false;
            $data['error_message'] = "userCount failed to insert in table";
        }
    }

    return $data;


}

function userCount(){

    $data['success'] = false;
    global $wpdb;

    $query = $wpdb->get_results('SELECT * FROM `ana_userCount` ORDER BY `ID` DESC LIMIT 1');

    if($query){

        $data['success'] = true;
        $data['result'] = $query;

    }

    return $data;

}

function addMeToFriend($userID){

    $data['success'] = false;
    global $wpdb;

    $friendID = $userID;

    $insertData = [
        'userID' => 1,
        'friendID' =>  $friendID,
        'approved' => 1
    ];

    if($userID){

        $check = $wpdb->get_results('SELECT * FROM `friends` WHERE `userID` = 1 AND `friendID` = ' . $insertData['friendID'] . ' LIMIT 1');

//        $data['check'] = $check;

        if(!$check) {
            $insert = $wpdb->insert('friends', $insertData);

            if ($insert) {

                $data['success'] = true;

            }
        } else {

            $data['success'] = false;
        }

    }

    return $data;

}

function ghsfriendcheck(){

    $data['success'] = false;
    global $wpdb;

    $users = $wpdb->get_results('SELECT `ID` FROM `wp_users` WHERE `ID` != 1');


    if($users) {

        $data['success'] = true;

        $key = 0;
        foreach ($users as $u) {

            $data['users'][$key]['ID'] = $u->ID;
            $data['users'][$key]['added'] = addMeToFriend($data['users'][$key]['ID']);

            $key++;

        }
    }

    return $data;
}

function updateImg(){

    global $wpdb;
    $data['success'] = false;

    $img = $_REQUEST['img'];
    $user_ID = get_current_user_id();

    if(isset($img) && isset($user_ID)){
        $check = $wpdb->get_results('SELECT `ID`, `userImg` FROM `userstats` WHERE `ID` = '. $user_ID);

        if(empty($check)) {
            $insertData = [
                'ID' => $user_ID,
                'userImg' => $img
            ];

            $insert = $wpdb->insert('userstats', $insertData);

            if($insert){
                $data['success'] = true;
            } else {
                $data['success'] = false;
                $data['error_message'] = "Error Code: 829";
            }

        } else {

            $updateData = [
                'userImg' => $img
            ];

            $update = $wpdb->update('userstats', $updateData, array('ID'=>$user_ID));

            if($update){
                $data['success'] = true;
            } else {
                $data['success'] = false;
                $data['error_message'] = "Error Code: 830";
            }

        }
    }

    return $data;

}

function updataUser($user){
    global $wpdb;
    $data['success'] = false;

    $userID = get_current_user_id() ?: $user['user_ID'];

    $user_data = [
        'firstName' => $_REQUEST['first_name'] ?: $user['first_name'],
        'lastName' => $_REQUEST['last_name'] ?: $user['last_name'],
        'user_email'  => $_REQUEST['email'] ?: $user['email'],
        'gender' => $_REQUEST['gender'] ?: $user['gender'],
        'birthday' => $_REQUEST['birthday'] ?: $user['birthday'],
        'facebook' => $_REQUEST['FBID'] ?: $user['FBID'],
        'google' => $_REQUEST['GID'] ?: $user['GID']
    ];

    $data['user'] = $userID;

    $update = $wpdb->update('wp_users', $user_data, array('ID'=>$userID));

    if($update){
        $data['success'] = true;
    } else {
        $data['success'] = false;
        $data['error_message'] = "Error Code: 49251";
    }

    return $data;
}

function getCart(){
	$data['success'] = false;

	WC()->session = new WC_Session_Handler();
	WC()->session->init();
	WC()->customer = new WC_Customer( get_current_user_id(), true );
	WC()->cart = new WC_Cart();

	$data['cart'] = WC()->cart->cart_contents;

	return $data;
}