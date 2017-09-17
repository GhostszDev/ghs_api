# [<img src="http://ghostszmusic.com/wp-content/uploads/2017/01/small-logo.png" style="width:35px !important;"> GHS API for wordpress](https://ghostszmusic.com)
This API is for connecting mobile and desktop to become one unified platform.

Function                            | Method| Url                         | Usage                                               | Completion |
------------------------------------|-------|-----------------------------|-----------------------------------------------------|------------|
[Login](#login)                     | POST  | /ghs_api/v1/login/          | This allows users to login remotely to site         | YES
Logout                              | GET   | /ghs_api/v1/logout/         | This allows user to sign out from site remotely     | NO
[SignUp](#signup)                   | POST  | /ghs_api/v1/signup/         | This allows user to sign up to site remotely        | YES
[Get User Data](#get-user-data)     | POST  | /ghs_api/v1/getuserdata/    | This get the users info from the database           | YES
[Send Game Data](#send-game-data)   | POST  | /ghs_api/v1/sendgameData/   | This get the current user data                      | YES
[Social](#social)                   | POST  | /ghs_api/v1/social/         | Sign in the user using his social key               | YES
[Mail List](#mail-list)             | POST  | /ghs_api/v1/mailing/        | Signs email of user to mailing list database        | YES
[Post Comments](#post-comments)     | POST  | /ghs_api/v1/post_comment/   | This allow user to post comments to any post        | YES
[Get Comments](#get-comments)       | POST  | /ghs_api/v1/getComments/    | This get the comments for what post is currently up | YES
[Single Post](#single-post)         | POST  | /ghs_api/singlePost/        | This gets a single post                             | YES
[Add Friend](#add-friend)           | POST  | /ghs_api/addfriend/         | This add a user to a another users friends list     | YES
[Friends List](#friends-list)       | POST  | /ghs_api/friendsList/       | This gets a users friends list                      | YES
[Grab Games List](#grab-games-list) | GET   | /ghs_api/grabGameList/      | This grabs the games list                           | YES
[Contact Us](#contact-us)           | POST  | /ghs_api/contactUs/         | Allows the user to send a message to company        | YES
[User Feed](#user-feed)             | POST  | /ghs_api/userFeed/          | This gets the users feed for their profile          | YES
[User Update](#user-feed-update)    | POST  | /ghs_api/userUpdate/        | This updates the users feed with updates and more   | YES
[Edit User](#edit-user)             | POST  | /ghs_api/edit_user/         | This updates the users information                  | NO
[Upload Media](#upload-media)       | POST  | /ghs_api/updateImg/         | This updates the users media file                   | NO
[Emails](#emails)                   | POST  | /ghs_api/emails/            | This sends a email to the users                     | NO
[Recent Comments](#recent-comments) | GET   | /ghs_api/getRecentComments/ | This get the most recent comments                   | YES

# Functions

### Login
Params         | Desc                                                                                   |
---------------|----------------------------------------------------------------------------------------|
user_login     | This is the email and/or username of the user who is trying to login                   |
user_password  | This is the password that the user made to grant access to the system                  |
gameID         | This is gameID needed to grab the users score for the current game                     |
remember       | This allows the site to remember the user info for about 90 days as stated by wordpress|
Returns        | All info needed for the user to have access to the site (i.e. username, ID and etc.    |

### SignUp
Params       | Desc                                                       |
-------------|------------------------------------------------------------|
user_login   | This is the new user's username                            |
firstName    | This is the first name for the user                        |
lastName     | This is the last name for the user                         |
user_email   | This is the email for the user                             |
user_pass    | This is is the password for the user                       |
gender       | The gender of the user                                     |
birthday     | The birthday connected to the user                         |
Returns      | Returns success or failure for the creation of the account |

### Get User Data
Params  | Desc                               |
--------|------------------------------------|
user_ID | This is the id of the current user |
Returns | The information for the user       |

### Send Game Data
Params     | Desc                                                                                                      |
-----------|-----------------------------------------------------------------------------------------------------------|
gameID     | This is the token value for the game to be recognized by the system to store all data regarding the game. |
score      | This is the number value for the score                                                                    |
userID     | This sends the user ID to games database                                                                  |
Returns    | This returns a success or failure and a message                                                           |

### Mail List
Params     | Desc                                               |
-----------|----------------------------------------------------|
first_name | This is the first name of the mail list subscriber |
last_name  | This is the last name of the mail list subscriber  |
email      | This is the email of the mail list subscriber      |
Returns    | This returns a success or failure and a message    |

### Post Comments
Params          | Desc                                               |
----------------|----------------------------------------------------|
postID          | This is the ID for the current post                |
user_id         | This is the comment posters user ID                |
user_name       | This is the comment posters user name              |
user_email      | This is the comment posters user email             |
comment         | This is the comment posters comment string         |
comment_parent  | This receives a 1 or 0 if it's a reply or not      |
Returns         | This returns success or failure                    |

### Get Comments
Params  | Desc                                              |
--------|---------------------------------------------------|
postID  | This is the ID for the current post               |
Returns | This returns all the comment for the current post |

### Single Post
Params  | Desc                                           |
--------|------------------------------------------------|
postID  | This is the ID for the post that is selected.  |
Returns | This returns all elements for the current post |

### Adding Friends
Params    | Desc                              |
----------|-----------------------------------|
userID    | This is the users ID              |
friendID  | This is the friend ID             |
Returns   | This returns success or failure   |

### Friends List
Params  | Desc                              |
--------|-----------------------------------|
userID  | This is the users ID              |
Returns | This returns a users friends list |

### Grab Games List
Params  | Desc                           |
--------|--------------------------------|
Returns | This returns the games list    |

### Contact Us
Params  | Desc                                             |
--------|--------------------------------------------------|
name    | The users name                                   |
email   | The users email                                  |
msg     | The users message to the comapny                 |
Returns | This returns the message for success or failure  |

### User Feed
Params  | Desc                              |
--------|-----------------------------------|
userID  | This is the users ID              |
Returns | This returns a users news feed    |

### User Feed Update
Params          | Desc                                               |
----------------|----------------------------------------------------|
user_id         | This is the comment posters user ID                |
comment         | This is the comment posters comment string         |
Returns         | This returns success or failure                    |

### Edit User
Params          | Desc                                               |
----------------|----------------------------------------------------|
userID          | This is the userID for current user                |
Returns         | This returns success or failure                    |

### Upload Media
Params          | Desc                                               |
----------------|----------------------------------------------------|
userID          | This is the userID for current user                |
blob            | This is the new media for current user             |
type            | This is the type of media being uploaded           |
Returns         | This returns success or failure                    |

### Emails
Params          | Desc                                               |
----------------|----------------------------------------------------|
Returns         | This returns success or failure                    |

### Social
Params       | Desc                                                       |
-------------|------------------------------------------------------------|
user_login   | This is the new user's username                            |
firstName    | This is the first name for the user                        |
lastName     | This is the last name for the user                         |
user_email   | This is the email for the user                             |
user_pass    | This is is the password for the user                       |
gender       | The gender of the user                                     |
birthday     | The birthday connected to the user                         |
Facebook ID  | This is the facebook user ID                               |
Google   ID  | This is the Google user ID                                 |
Returns      | Returns success or failure for the creation of the account |

### Recent Comments
Params          | Desc                                               |
----------------|----------------------------------------------------|
Returns         | This returns success or failure and comments       |

# Update Log
All updates and changes are mentioned below

* Added user editing ability ()
* Upload user profile img ()
* Recent Comments (09/17/17)
* Google sign added to login (09/11/17)
* social sign in (09/09/17)
* Added offset to post feed for pagination (07/11/17)
* Added user profile commenting (06/02/17)
* Updated login function to include highscore data when sent the gameID (06/01/17)
* Now users will get feed for comments left on post with post link (05/25/17)
* Added userfeed to user's profile (05/05/17)
* Now users are able to contact us for any reason (05/03/17)
* Grabbing game list done! (05/03/17)
* Users are now able to post comments (04/29/17)
* Get User Data now added (04/28/17)
* comment list now pulled by the post id (04/28/17)
* Added functionality for getting a single post by using post id. (04/28/17)
* Updated README.md (Now you can see all changes with update log. Also add a function section to keep track of all process.) 
* sendgameData now fully functional (This allows the user to send and/or update their scores in the database.)


