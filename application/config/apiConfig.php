<?php
/**
 * Config file to define all API constants
 * @date    2015-07-10
 * @author  Romil Goel
 * @todo    none
*/

$config['apiConfig'] = "";

// status codes
define("STATUS_CODE_SUCCESS"				, 0);
define("STATUS_CODE_FAILURE"				, 1);
define("STATUS_CODE_FORCE_LOGOUT"			, 2);
define("STATUS_CODE_SITE_UNDER_MAINTAINANCE", 3);

// Shared Identifier Between Mobile APP and Server for device verification
define("API_IDENTIFIER", "SH!ksH@");
define("SHIKSHA_APP_NAME" , "shiksha");

// API flags
define("SKIP_DEVICE_VERIFICATION", 0);
define("SKIP_KEYWORD_VERIFICATION", 0);
define("SHOW_INTERMEDIATE_PAGE", true);
define("APP_UNDER_MAINTAINANCE", 0);

// gcm api key
define("GCM_API_KEY" , "AIzaSyDBIVu6MGa2g2_dm1qX4GtOfQ6d6slVDXQ");

// list all the APIs here for which we do not want to verify device
$config['skipDeviceVerificationAPIs'] = array("/registerDevice");

// list of apis to be skipped for tracking
$config['skipTrackingAPIList'] = array("autoSuggestor");

// list of apis to be skipped for validation of user's logged-in state
$config['nonLoggedInAPIsList'] = array("registerDevice", "login", "resetPassword", "forgotPassword", "register", "fbLogin", "getUserFeedBackData", "logout", "updateGCMId", "getQuestionDetailWithAnswers", "getCommentDetails", "getDiscussionDetailWithComments", "getLinkedAndRelatedThread", "search", "autoSuggestor", "getReplyDetails",'getDataFromCheckSum','updateFCMId');

/*
* forceUpgradeMapping will contain value only for those version of APPs that needs to be displayed soft-upgrade
* Key of this array is APP version and its value contains the values of force-upgrade
*/
$config['forceUpgradeMapping'] = array( 13 => array("isForceUpgrade" => true, "forceUpgradeMessage" => "This version of app is outdated. Please upgrade it.", "upgradeButtonLabel" => "Upgrade", "skipButtonLabel" => "Skip"));

define("MAX_APP_CRON_ATTEMPTS", 4);

$config['noInfoAvailableText'] = array("search" => "No results found",
									   "discussion" => "No discussions yet",
									   "question" => "No questions yet",
									   "unanswered" => "No unanswered questions yet",
									   "home_unanswered" => "No unanswered questions for you",
									   "notifications" => "No Notifications",
									   "all" => "No questions/discussions yet");

define("NO_INFO_AVAILABLE", "No Info Available.");
define("FORCE_LOGOUT_MESSAGE","Please re-login to continue");

$config['smartRatingLayerDaysInterval'] = array(7,14,30);
$config['smartRatingLayerText'] = "You seem to have liked the answer.\n\n Do you feel the App is worth 5 star rating ?";

define("APP_FEEDBACK_EMAIL","appfeedback@shiksha.com");

define("QUESTION_VIEW_DURATION",5); // in seconds
define("ANSWER_VIEW_DURATION",10); // in seconds
define("VIEWCOUNT_TRACKING_INTERVAL",5000); // in milliseconds
