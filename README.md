# [<img src="http://ghostszmusic.com/wp-content/uploads/2017/01/small-logo.png" style="width:35px !important;"> GHS API for wordpress](https://ghostszmusic.com)
This API is for connecting mobile and desktop to become one unified platform.

Function                          | Method| Url                       | Usage                                           | Completion |
----------------------------------|-------|---------------------------|-------------------------------------------------|------------|
[Login](#login)                   | POST  | /ghs_api/v1/login/        | This allows users to login remotely to site     | YES
Logout                            | GET   | /ghs_api/v1/logout/       | This allows user to sign out from site remotely | NO
[SignUp](#signup)                 | POST  | /ghs_api/v1/signup/       | This allows user to sign up to site remotely    | YES
[Get User Data](#get-user-data)   | POST  | /ghs_api/v1/getuserdata/  | This get the users info from the database       | YES
[Send Game Data](#send-game-data) | POST  | /ghs_api/v1/sendgameData/ | This get the current user data                  | YES
Social                            | POST  | /ghs_api/v1/social/       | Sign in the user using his social key           | NO
[Mail List](#mail-list)           | POST  | /ghs_api/v1/mailing/      | Signs email of user to mailing list database    | YES
[Get Comments](#get-comments)     | POST  | /ghs_api/v1/getComments/  | Signs email of user to mailing list database    | YES
[Single Post](#single-post)       | POST  | /ghs_api/singlePost/      | This gets a single post                         | YES

# Functions

### Login
Params         | Desc                                                                                   |
---------------|----------------------------------------------------------------------------------------|
user_login     | This is the email and/or username of the user who is trying to login                   |
user_password  | This is the password that the user made to grant access to the system                  |
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

# Update Log
All updates and changes are mentioned below

* Added functionality for getting a single post by using post id. (04/28/17)
* Updated README.md (Now you can see all changes with update log. Also add a function section to keep track of all process.) 
* sendgameData now fully functional (This allows the user to send and/or update their scores in the database.)


