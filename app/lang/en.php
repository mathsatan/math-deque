<?php
// LABELS
define ('L_SITE_TITLE', 'math-deque.rhcloud.com');
define ('L_TITLE', 'Math for everyone');

    // top menu
/*define ('L_MENU', 'Menu');*/
define ('L_MAIN', 'Home');
define ('L_ARTICLES', 'Articles');
define ('L_FORMULA', 'Formula');
define ('L_ORDER', 'Order work');
define ('L_ABOUT', 'About us');
define ('L_CALC', 'Calculate');
define ('L_CALC_HINT', 'Calculate derivative online');
define ('L_CALC_DERIVATIVE', 'Calculate derivative analytically');

// categories
define ('L_NO_ARTICLES_FOUND', 'No one article found');

    // sign in & sign out
define ('L_LOGIN', 'Sign in');
define ('L_OUT', 'Sign out');
define ('L_REG', 'Registration');
define ('L_HELLO', 'Hi');
define ('L_REG_PAGE', 'Registration page');
define ('L_LOGIN_PAGE', 'Authorization page');
define ('L_USER_LOGIN', 'Your login');
define ('L_USER_PASS', 'Your password');
define ('L_USER_PASS_REPEAT', 'Repeat password');
define ('L_USER_MAIL', 'Your email');
define ('L_SUBMIT', 'Send data');
define ('E_USER_NOT_ACTIVE', 'User not active');
define ('E_USER_FORGOT_PASS', 'Forgot your password?');
define ('E_WRONG_CAPTCHA', 'Wrong captcha');
define ('E_PASSWORDS_DOESNT_MATCH', 'Typed passwords doesn\'t match');
define ('L_QUERY', 'Query');

    // news
define ('L_NEWS', 'News');
define ('L_SOCIAL_NETWORKS', 'Follow');

    // error page
define ('L_ERROR', 'Error');
/*define ('L_REPORT_ADMIN', 'Inform admin');*/

    // admin
define ('L_ADMIN_PANEL', 'Admin panel');
define ('L_USER_LIST', 'User list');
define ('L_ADD_USER', 'Add user');
define ('L_USER_ID', 'ID');
define ('L_USER_STATUS', 'Status');
define ('L_USER_IS_ACTIVE', 'Is active');
define ('L_USER_UPDATE', 'Update user');
define ('L_USER_DELETE', 'Delete user');
define ('L_USER_RESTORE_PASS', 'Restore password');

// admin.articles
define ('L_ARTICLES_LIST', 'Articles list');
define ('L_ADD_ARTICLE', 'Add article');
define ('L_ARTICLE_ID', 'ID');
define ('L_ARTICLE_TITLE', 'Title');
define ('L_ARTICLE_STRING_ID', 'String ID');
/*define ('L_ARTICLE_BRIEFLY', '');*/
define ('L_ARTICLE_UPDATE', 'Article update');
define ('L_ARTICLE_DELETE', 'Article delete');

    // admin menu
define ('L_ADMIN_MENU', 'Admin menu');
define ('L_USER_MANAGEMENT', 'User management');
define ('L_ARTICLES_MANAGEMENT', 'Article management');

define ('L_PIC_LIST', 'Image list');
define ('L_PIC_VIEW', 'View images');
define ('L_PIC_CODE', 'Code');
define ('L_PIC_UPDATE', 'Update image');
define ('L_PIC_DELETE', 'Delete image');
define ('L_PIC_ADD', 'Add image');
define ('L_PIC_ALIGN', 'Image align');
define ('L_PIC_ALT', 'Alt tag');
define ('L_PIC_HINT_OPEN', 'View original image');
define ('L_PIC_DESC', 'Image caption');

// articles
define ('L_NO_COMMENTS', 'There are no answers');
define ('L_COMMENTS', 'Answers:');
define ('L_YOUR_COMMENT', 'Add a comment');
define ('L_ARTICLE_AUTHOR', 'Author');
define ('L_ARTICLE_TEXT', 'Article text');
define ('L_ARTICLE_CAT', 'Article category');
define ('L_DATE', 'Date');
define ('L_LOAD_PIC', 'Load image');
define ('L_URL_PIC', 'Image from URL');
define ('L_PUBLISH', 'Publish');
define ('L_SHARE', 'Share');    // not use
define ('L_TAGS', 'Tags');
define ('L_DESCRIPTION', 'Description');

// INFO
define ('I_LOGIN_SUCCESS', 'Login success');
define ('I_REG_SUCCESS', 'Registration successful');
// admin
define ('I_UPDATE_SUCCESS', 'Update success');
define ('I_DELETE_SUCCESS', 'Delete success');
define ('I_INSERT_SUCCESS', 'Insert success');

// ERRORS & EXCEPTIONS
define ('E_EMPTY_FIELD', 'Empty fields!');
define ('E_USER_NOT_FOUND', 'User not found!');
define ('E_WRONG_LOGIN_OR_PASS', 'Wrong login or password!');
define ('E_LOGIN_ALREADY_EXIST', 'User already exist!');
define ('E_INVALID_EMAIL', 'Wrong email format!');
define ('E_EMAIL_NOT_EXIST', 'This email doesn\'t exist!');
define ('E_NOT_ALLOWED', 'Not allowed!');
define ('E_FAIL_GET_ARTICLES', 'Error to get articles!');
define ('E_MODEL_FILE_DOESNT_EXIST', 'Model file doesn\'t exist!');
define ('E_CONTROLLER_FILE_DOESNT_EXIST', 'Controller file doesn\'t exist!');
define ('E_TEMPLATE_FILE_DOESNT_EXIST', 'Template file doesn\'t exist!');
define ('E_INCORRECT_ACTION', 'Incorrect action!');
define ('E_INCORRECT_PARAMS', 'Incorrect parameters!');

define ('E_WRONG_ID', 'Wrong id format');
define ('E_NO_ARTICLE_DATA', 'No article data found');
define ('E_INCORRECT_DATA', 'Incorrect data');
define ('E_DENY_HTML_TAGS', 'Deny html tags found');

// admin
define ('E_UPDATE_FAIL', 'Update fail');
define ('E_DELETE_FAIL', 'Delete fail');
define ('E_INSERT_FAIL', 'Insert fail');
// admin.articles
define ('E_ARTICLES_NOT_FOUND', 'Articles not found');
define ('E_WRONG_DATE', 'Incorrect date');
define ('E_CRITICAL_FILE_SIZE', 'Wrong size of file');
define ('E_LOADING_FILE_FAIL', 'Fail to load file');
define ('E_WRONG_STR_ID', 'Incorrect string id');
define ('E_CLASS_NOT_FOUND', 'Class not found');
define ('E_PICS_NOT_FOUND', 'Images not found');
/*define ('E_FILE_NOT_FOUND', 'File not found');*/
define ('E_CANT_LOAD_PIC', 'Can\'t load image');
define ('E_FILE_ALREADY_EXIST', 'File already exist');
define ('E_PIC_TOO_SMALL', 'Too small image size');
// smtp class
define ('E_CONNECTION_ERROR', 'Connection error');
define ('E_SENDING_HELO_ERROR', 'Error of command sending: HELO');
define ('E_AUTH_ERROR', 'Autorization error');
define ('E_SENDING_MAIL_FROM_ERROR', 'Error of command sending: MAIL FROM');
define ('E_SENDING_RCPT_TO_ERROR', 'Error of command sending: RCPT TO');
define ('E_DATA_ERROR', 'Error of command sending: DATA');
define ('E_EMAIL_DIDNT_SENT', 'E-mail didn\'t sent');

// SEO
define ('L_ARTICLES_MATH', 'articles about math');
define ('L_ORDER_WORK_MATH', 'order work');
define ('L_DONATE', 'Donate');
define ('L_FORMULA_DESC', 'math formula');