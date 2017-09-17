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

}

function getuserdata($ID){

    $data['success'] = false;
    global $wpdb;

    $user_ID = $_REQUEST['user_ID'] ?: $ID;

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
        $data['user']['user_icon_100'] = get_avatar_url($user->data->ID, array(
            'size'=> 100
        ));

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

    $cred = [
        'user_login' => $_REQUEST['user_login'] ?: $user['user_name'],
        'user_password' => $_REQUEST['user_password'] ?: $user['password'],
        'remember' => $_REQUEST['remember'] ?: true
    ];

    $data['user'] = $cred;

    $gameID = $_REQUEST['gameID'];

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
        $data['facebook'] = $userInfo->facebook;
        $data['google'] = $userInfo->google;

        if($gameID){

            switch($gameID) {
                case '7bd41fb04c8fac3edd23b749405d052a':
                    $score = $wpdb->get_results("SELECT `ID`, `score` FROM `wdef_db` WHERE `ID` = '" . $userInfo->ID . "'");
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

    $friends = $wpdb->get_results( "SELECT friendID, approved FROM friends WHERE userID LIKE '". $userID ."'");

    if($friends){

        $data['success'] = true;

        $key = 0;
        foreach ($friends as $f){

            $gt = $wpdb->get_results( "SELECT ID, firstName, lastName, user_login, user_status FROM wp_users WHERE ID LIKE '". $f->friendID ."'");

            foreach ($gt as $r){

                $role = get_userdata($r->ID);

                $data['friend'][$key]['ID'] = $r->ID;
                $data['friend'][$key]['firstName'] = $r->firstName;
                $data['friend'][$key]['lastName'] = $r->lastName;
                $data['friend'][$key]['userName'] = $r->user_login;
                $data['friend'][$key]['role'] = $role->roles[0];

                if($r->user_status == 0){
                    $data['friend'][$key]['status'] = "offline";
                } else {
                    $data['friend'][$key]['status'] = "online";
                }

                $data['friend'][$key]['user_icon'] = get_avatar_url($r->ID, array(
                    'size'=> 40
                ));

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

function userFeed(){

    $data['success'] = false;
    global $wpdb;

    $check = $wpdb->get_results( "SELECT `userID`, `comment`, `date` FROM `userFeed` ORDER BY `date` DESC");

    $key = 0;

    if($check) {

        $data['success'] = true;

        foreach ($check as $c) {

            $data['feed'][$key]['comment'] = $c->comment;

            $time = strtotime($c->date);
            $date = date("m/d/y", $time);

            $data['feed'][$key]['date'] = $date;

            $user = $wpdb->get_results("SELECT ID, firstName, lastName, user_login FROM wp_users WHERE ID LIKE '" . $c->userID . "'");

            foreach ($user as $r) {

                $role = get_userdata($r->ID);

                $data['feed'][$key]['ID'] = $r->ID;
                $data['feed'][$key]['firstName'] = ucwords($r->firstName);
                $data['feed'][$key]['lastName'] = ucwords($r->lastName);
                $data['feed'][$key]['userName'] = ucwords($r->user_login);
                $data['feed'][$key]['role'] = $role->roles[0];

                $data['feed'][$key]['user_icon'] = get_avatar_url($r->ID, array(
                    'size' => 40
                ));

            }

            $key++;

        }
    } else {

        $data['success'] = false;
        $data['error_message'] = "No comments...";

    }

    return $data;

}

function userUpdate($userID = "", $comment = ""){

    $data['success'] = false;
    global $wpdb;

    $user = array(
        'userID' => $_REQUEST['userID'] ?: $userID,
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
      'userID' => $_REQUEST['userID'],
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
    $user_id = $_REQUEST['user_id'];
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

function uploadMedia(){

    global $wpdb;
    $data['success'] = false;

    $media = array(
        "userID" => $_REQUEST['userID'],
        "content" => $_REQUEST['content'],
        "type" => $_REQUEST['type'],
        "mediaType" => $_REQUEST['mediaType']
    );

    $update = $wpdb->insert('media', $media);

    if($update){
        $data['success'] = true;
    } else {
        $data['success'] = false;
        $data['error_message'] = "There was a problem uploading your media content";
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