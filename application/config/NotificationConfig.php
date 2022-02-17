<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();
/*************** Contribution Based Cases Start ***********************/
// Questions / Answer Related Cases
define('TOTAL_LIMIT',2);

// APP landing page identifiers
define("APP_HOME_PAGE", 0);
define("APP_QUESTION_DETAIL_PAGE", 1);
define("APP_DISCUSSION_DETAIL_PAGE", 2);
define("APP_TAG_DETAIL_PAGE", 3);
define("APP_USER_PROFILE_PAGE", 4);
define("HELP_PAGE", 5);
define('APP_INAPP_NOTIFICATION_PAGE',6);


// List of all Possible Actors
define("QUESTION_OWNER","question_owner");
define("ANSWER_OWNER","answer_owner");
define("QUESTION_FOLLOWER","question_follower");
define("COMMENTER_ON_ANSWER","commentor_on_answer");

define("DISCUSSION_OWNER","discussion_owner");
define("COMMENT_OWNER_DISCUSSION","comment_owner_discussion");
define("DISCUSSION_FOLLOWER","discussion_follower");
define("REPLYER_ON_COMMENT_DISCUSSION","replyer_on_comment");


$config['POSSIBLE_ACTORS_ON_QUESTION'] = array(QUESTION_OWNER,ANSWER_OWNER,QUESTION_FOLLOWER,COMMENTER_ON_ANSWER);


define('QUESTION_DETAIL_PAGE_DEFAULT',1);
define('QUESTION_DETAIL_PAGE_WITH_REFERENCE_ANSWER',2);
define('DISCUSSION_DETAIL_PAGE_DFEAULT',3);
define('DISCUSSION_DETAIL_PAGE_WITH_REFERENCE_COMMENT',4);
define('USER_PROFILE_PAGE_DEFAULT',5);
define('TAG_DETAIL_PAGE_DEFAULT',6);

define('QUESTION_DETAIL_PAGE_WITH_LINK_QUESTION',7);
define('DISCUSSION_DETAIL_PAGE_WITH_LINK_QUESTION',8);
define('LEVEL_UP_DIALOG',9);
define('POINTS_UP_DIALOG',10);
define('RATING_DIALOG',11);
define('GENERAL_NOTIFICATION',12);
define('WEBVIEW_NOTIFICATION',13);
define('INAPP_NOTIFICATION',14);


define("NEW_ANSWER_ON_QUESTION","new_answer_on_question");

$config['NEW_ANSWER_ON_QUESTION_DETAILS'] =array(
											QUESTION_OWNER => array(
												'gcm' => true,
												'message' => 'New answer posted on your question <b>$2</b>.',
												'title' => '<App>',
												'summation_messgae' => '<b>#count#</b> new answers posted on your question <b>$2</b>.',
												'apply_limit' => false,
												'action_item' => 'Answer',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => 'threadId',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_WITH_REFERENCE_ANSWER,
												'commandType' => 0,
												'trackingURL' => ''
											),
											QUESTION_FOLLOWER => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'New answer on the question <b>$2</b> you follow.',
												'summation_messgae' => '<b>#count#</b> new answers on the question <b>$2</b> you follow.',
												'apply_limit' => false,
												'action_item' => 'Answer',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchFollowersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_WITH_REFERENCE_ANSWER,
												'commandType' => 0,
												'trackingURL' => ''
								
											)
										);
$config['NEW_ANSWER_ON_QUESTION_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title')
												);


define("NEW_FOLLOWER_ON_QUESTION","new_follower_on_question");
$config['NEW_FOLLOWER_ON_QUESTION_DETAILS'] =array(
											QUESTION_OWNER => array(
												'gcm' => false,
												'title' => '<App>',
												'message' => '<b>$3</b> just followed your question <b>$2</b>.',
												'summation_messgae' => 'You have <b>#count#</b> new followers on your question <b>$2</b>.',
												'apply_limit' => true,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => 'threadId',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_DEFAULT,
												'commandType' => 0,
												'trackingURL' => ''
											)
										);
$config['NEW_FOLLOWER_ON_QUESTION_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title'),
													'fetchNameById' => array('userName')
												);



define("EDIT_QUESTION_BY_MODERATOR","edit_question_by_moderator");
$config['EDIT_QUESTION_BY_MODERATOR_DETAILS'] =array(
											QUESTION_OWNER => array(
												'gcm' => false,
												'title' => '<App>',
												'message' => '<b><App></b> moderator has edited your question <b>$2</b>.',
												'summation_messgae' => '<b><App></b> moderator has edited your question <b>$2</b>.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => 'threadId',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_DEFAULT,
												'commandType' => 0,
												'trackingURL' => ''
											),
											ANSWER_OWNER => array(
												'gcm' => false,
												'title' => '<App>',
												/*'message' => '<b><App></b> moderator has edited the question <b>$2</b> on which you had contributed',
												'summation_messgae' => '<b><App></b> moderator has edited the question <b>$2</b> on which you had contributed',*/
												'message' => 'Question <b>$2</b> on which you had answered is edited.',
												'summation_messgae' => 'Question <b>$2</b> on which you had answered is edited.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchAnswerOrCommentGivenUsersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_DEFAULT,
												'commandType' => 0,
												'trackingURL' => ''
											),
											QUESTION_FOLLOWER => array(
												'gcm' => false,
												'title' => '<App>',
												'message' => 'Question <b>$2</b> you follow has been edited.',
												'summation_messgae' => 'Question <b>$2</b> you follow has been edited.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchFollowersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_DEFAULT,
												'commandType' => 0,
												'trackingURL' => ''
											)
										);
$config['EDIT_QUESTION_BY_MODERATOR_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title')
												);




define("LINK_QUESTION","link_question");
$config['LINK_QUESTION_DETAILS'] =array(
											QUESTION_OWNER => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'A question similar to your question <b>$2</b> has already been answered before.',
												'summation_messgae' => '<b>#count#</b> questions similar to your question <b>$2</b> has already been answered before.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => 'threadId',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_WITH_LINK_QUESTION,
												'commandType' => 0,
												'trackingURL' => ''
											),
											QUESTION_FOLLOWER => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'A question similar to the question <b>$2</b> you follow has already been answered before.',
												'summation_messgae' => '<b>#count#</b> questions similar to the question <b>$2</b> you follow has already been answered before.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchFollowersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_WITH_LINK_QUESTION,
												'commandType' => 0,
												'trackingURL' => ''
											)
										);
$config['LINK_QUESTION_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title')
												);



define("CLOSE_QUESTION","close_question");
$config['CLOSE_QUESTION_DETAILS'] =array(
											QUESTION_OWNER => array(
												'gcm' => false,
												'title' => '<App>',
												'message' => 'Your question <b>$2</b> is now closed for answering.',
												'summation_messgae' => 'Your question <b>$2</b> is now closed for answering.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => 'threadId',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_DEFAULT,
												'commandType' => 0,
												'trackingURL' => ''
											),
											
											QUESTION_FOLLOWER => array(
												'gcm' => false,
												'title' => '<App>',
												'message' => 'Question <b>$2</b> you follow is now closed for answering.',
												'summation_messgae' => 'Question <b>$2</b> you follow is now closed for answering.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchFollowersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_DEFAULT,
												'commandType' => 0,
												'trackingURL' => ''
											)
										);
$config['CLOSE_QUESTION_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title')
												);

define("UPVOTE_ANSWER","upvote_answer");
$config['UPVOTE_ANSWER_DETAILS'] =array(
											ANSWER_OWNER => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => '$3 upvoted your answer',
												'summation_messgae' => 'You have <b>#count#</b> new upvotes on your answer to <b>$2</b>.',
												'apply_limit' => true,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchAnswerOrCommentGivenParticularUsersForThread',
												'entity_on_action' => 'action_item_id'	,
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_WITH_REFERENCE_ANSWER,
												'commandType' => 0,
												'trackingURL' => ''

											)
										);
$config['UPVOTE_ANSWER_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title'),
													'fetchNameById' => array('userName')
												);



define("COMMENT_ON_ANSWER","comment_on_answer");
$config['COMMENT_ON_ANSWER_DETAILS'] =array(
											ANSWER_OWNER => array(
												'gcm' => true,
												'message' => '<b>$3</b> left a comment on your answer to <b>$2</b>',
												'title' => '<App>',
												'summation_messgae' => 'You have <b>#count#</b> new comments on your answer to <b>$2</b>.',
												'apply_limit' => true,
												'apply_day_limit' => true,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchAnswerOrCommentGivenParticularUsersForThread',
												'entity_on_action' => 'action_item_id',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_WITH_REFERENCE_ANSWER,
												'commandType' => 0,
												'trackingURL' => ''
											),
											COMMENTER_ON_ANSWER => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => '<b>$3</b> left a comment on the answer to <b>$2</b>',
												'summation_messgae' => 'You have <b>#count#</b> new comments on the answer to <b>$2</b>.',
												'apply_limit' => true,
												'apply_day_limit' => true,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchCommentOrReplyOnParticularAnsOrComment',
												'entity_on_action' => 'action_item_id',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_WITH_REFERENCE_ANSWER,
												'commandType' => 0,
												'trackingURL' => ''
											)
										);

$config['COMMENT_ON_ANSWER_FUNCTIONS'] =array(
										'fetchInfoOfQuestion'  => array('ownerId','title'),
										'fetchNameById' => array('userName')
				   						);


define("EDIT_ANSWER","edit_answer");
$config['EDIT_ANSWER_DETAILS'] =array(
											ANSWER_OWNER => array(
												'gcm' => false,
												'title' => '<App>',
												'message' => '<b><App></b> moderator has edited your answer to <b>$2</b>.',
												'summation_messgae' => '<b><App></b> moderator has edited your answer to <b>$2</b>.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchAnswerOrCommentGivenParticularUsersForThread',
												'entity_on_action' => 'action_item_id',
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_WITH_REFERENCE_ANSWER,
												'commandType' => 0,
												'trackingURL' => ''
											)
										);

$config['EDIT_ANSWER_FUNCTIONS'] =array(
										'fetchInfoOfQuestion'  => array('ownerId','title')
				   						);



$config['POSSIBLE_ACTORS_ON_DISCUSSION'] = array(DISCUSSION_OWNER,COMMENT_OWNER_DISCUSSION,DISCUSSION_FOLLOWER,REPLYER_ON_COMMENT_DISCUSSION);

// Discussions Related Cases
define("NEW_COMMENT_ON_DISCUSSION","new_comment_on_discussion");
$config['NEW_COMMENT_ON_DISCUSSION_DETAILS'] =array(
											DISCUSSION_OWNER => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'New comment on your discussion <b>$2</b>.',
												'summation_messgae' => 'You have <b>#count#</b> new comments on your discussion <b>$2</b>.',
												'apply_limit' => true,
												'apply_day_limit' => true,
												'action_item' => 'Comment',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => 'threadId',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_WITH_REFERENCE_COMMENT,
												'commandType' => 0,
												'trackingURL' => ''
											),
											COMMENT_OWNER_DISCUSSION => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'New comment on the discussion <b>$2</b>.',
												'summation_messgae' => '<b>#count#</b> new comments on the discussion <b>$2</b>.',
												'apply_limit' => true,
												'apply_day_limit' => true,
												'action_item' => 'Comment',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchAnswerOrCommentGivenUsersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_WITH_REFERENCE_COMMENT,
												'commandType' => 0,
												'trackingURL' => ''
											),
											DISCUSSION_FOLLOWER => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'New comment on discussion <b>$2</b> you follow.',
												'summation_messgae' => '<b>#count#</b> new comments on the discussion <b>$2</b> you follow.',
												'apply_limit' => true,
												'apply_day_limit' => true,
												'action_item' => 'Comment',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchFollowersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_WITH_REFERENCE_COMMENT,
												'commandType' => 0,
												'trackingURL' => ''								
											)

										);
$config['NEW_COMMENT_ON_DISCUSSION_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title')
												);


define("NEW_FOLLOWER_ON_DISCUSSION","new_follower_on_discussion");
$config['NEW_FOLLOWER_ON_DISCUSSION_DETAILS'] =array(
											DISCUSSION_OWNER => array(
												'gcm' => false,
												'title' => '<App>',
												'message' => '<b>$3</b> just followed your discussion <b>$2</b>.',
												'summation_messgae' => 'You have <b>#count#</b> new followers on your discussion <b>$2</b>.',
												'apply_limit' => true,
												'action_item' => 'Answer',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => 'threadId',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_DFEAULT,
												'commandType' => 0,
												'trackingURL' => ''
											),
										);
$config['NEW_FOLLOWER_ON_DISCUSSION_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title'),
													'fetchNameById' => array('userName')
												);


define("EDIT_DISCUSSION_BY_MODERATOR","edit_discussion_by_moderator");

$config['EDIT_DISCUSSION_BY_MODERATOR_DETAILS'] =array(
											DISCUSSION_OWNER => array(
												'gcm' => false,
												'title' => '<App>',
												'message' => '<b><App></b> moderator has edited your discussion <b>$2</b>.',
												'summation_messgae' => '<b><App></b> moderator has edited your discussion <b>$2</b>.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => 'threadId',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_DFEAULT,
												'commandType' => 0,
												'trackingURL' => ''
											),
											COMMENT_OWNER_DISCUSSION => array(
												'gcm' => false,
												'title' => '<App>',
												// 'message' => '<b><App></b> moderation has edited the discussion <b>$2</b> on which you had contributed',
												// 'summation_messgae' => '<b><App></b> moderation has edited the discussion <b>$2</b> on which you had contributed',
												'message' => 'Discussion <b>$2</b> on which you had commented is edited.',
												'summation_messgae' => 'Discussion <b>$2</b> on which you had commented is edited.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchAnswerOrCommentGivenUsersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_DFEAULT,
												'commandType' => 0,
												'trackingURL' => ''
											),
											DISCUSSION_FOLLOWER => array(
												'gcm' => false,
												'title' => '<App>',
												// 'message' => '<b><App></b> moderation has edited the discussion <b>$2</b> you follow ',
												// 'summation_messgae' => '<b><App></b> moderation has edited the discussion <b>$2</b> on which you had contributed',
												'message' => 'Discussion <b>$2</b> which you follow has been edited.',
												'summation_messgae' => 'Discussion <b>$2</b> which you follow has been edited.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchFollowersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_DFEAULT,
												'commandType' => 0,
												'trackingURL' => ''
											)
										);
$config['EDIT_DISCUSSION_BY_MODERATOR_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title')
												);




define("LINK_DISCUSSION","link_discussion");

$config['LINK_DISCUSSION_DETAILS'] =array(
											DISCUSSION_OWNER => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'We have linked your discussion <b>$2</b> to a similar discussion.',
												'summation_messgae' => 'We have linked your discussion <b>$2</b> to a similar discussion.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => 'threadId',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_WITH_LINK_QUESTION,
												'commandType' => 0,
												'trackingURL' => ''
											),
											COMMENT_OWNER_DISCUSSION => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'We have linked discussion <b>$2</b> on which you had contributed to a similar discussion.',
												'summation_messgae' => 'We have linked discussion <b>$2</b> on which you had contributed to a similar discussion.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchAnswerOrCommentGivenUsersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_WITH_LINK_QUESTION,
												'commandType' => 0,
												'trackingURL' => ''
											),
											DISCUSSION_FOLLOWER => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'We have linked discussion <b>$2</b> which you follow to a similar discussion.',
												'summation_messgae' => 'We have linked discussion <b>$2</b> which you follow to a similar discussion.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchFollowersForThread',
												'entity_on_action' => 'threadId',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_WITH_LINK_QUESTION,
												'commandType' => 0,
												'trackingURL' => ''
											)
										);
$config['LINK_DISCUSSION_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title')
												);

define("UPVOTE_ON_COMMENT","upvote_on_comment");
$config['UPVOTE_ON_COMMENT_DETAILS'] =array(
											COMMENT_OWNER_DISCUSSION => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => '$3 upvoted your comment.',
												'summation_messgae' => 'You have <b>#count#</b> new upvotes on your comment to <b>$2</b>.',
												'apply_limit' => true,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchAnswerOrCommentGivenParticularUsersForThread',
												'entity_on_action' => 'action_item_id',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_WITH_REFERENCE_COMMENT,
												'commandType' => 0,
												'trackingURL' => ''

											)
										);
$config['UPVOTE_ON_COMMENT_FUNCTIONS'] =array(
													'fetchInfoOfQuestion'  => array('ownerId','title'),
													'fetchNameById' => array('userName')
												);



define("REPLY_ON_COMMENT","reply_on_comment");
$config['REPLY_ON_COMMENT_DETAILS'] =array(
											COMMENT_OWNER_DISCUSSION => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => '<b>$3</b> left a reply on your comment to <b>$2</b>',
												'summation_messgae' => 'You have <b>#count#</b> new replies on your comment to <b>$2</b>.',
												'apply_limit' => true,
												'apply_day_limit' => true,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchAnswerOrCommentGivenParticularUsersForThread',
												'entity_on_action' => 'action_item_id',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_WITH_REFERENCE_COMMENT,
												'commandType' => 0,
												'trackingURL' => ''
											),
											REPLYER_ON_COMMENT_DISCUSSION => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => '<b>$3</b> left a reply on the comment to <b>$2</b>',
												'summation_messgae' => '<b>#count#</b> new replies on the comment to <b>$2</b>.',
												'apply_limit' => false,
												'apply_day_limit' => true,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchCommentOrReplyOnParticularAnsOrComment',
												'entity_on_action' => 'action_item_id',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_WITH_REFERENCE_COMMENT,
												'commandType' => 0,
												'trackingURL' => ''
											)
										);

$config['REPLY_ON_COMMENT_FUNCTIONS'] =array(
										'fetchInfoOfQuestion'  => array('ownerId','title'),
										'fetchNameById' => array('userName')
				   						);


define("EDIT_COMMENT","edit_comment");
$config['EDIT_COMMENT_DETAILS'] =array(
											COMMENT_OWNER_DISCUSSION => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => '<b><App></b> moderator has edited your comment on <b>$2</b>.',
												'summation_messgae' => '<b><App></b> moderator has edited your comment on <b>$2</b>.',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => true,
												'recipientFunction' => 'fetchAnswerOrCommentGivenParticularUsersForThread',
												'entity_on_action' => 'action_item_id',
												'landing_page' => APP_DISCUSSION_DETAIL_PAGE,
												'notificationType' => DISCUSSION_DETAIL_PAGE_WITH_REFERENCE_COMMENT,
												'commandType' => 0,
												'trackingURL' => ''
											),
											
										);

$config['EDIT_COMMENT_FUNCTIONS'] =array(
										'fetchInfoOfQuestion'  => array('ownerId','title')
				   						);
define("FACEBOOK_FRIEND_JOINED","facebook_friend_joined");
$config['FACEBOOK_FRIEND_JOINED_DETAILS'] =array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'Your facebook friend <b>$1</b> has joined <b><App></b>.',
												'summation_messgae' => 'Your facebook friend <b>$1</b> has joined <b><App></b>.',
												'gcm_message' => 'Your facebook friend $1 has joined <App>',
												'apply_limit' => true,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => '',
												'entity_on_action' => '',
												'landing_page' => APP_USER_PROFILE_PAGE,
												'notificationType' => USER_PROFILE_PAGE_DEFAULT,
												'commandType' => 0,
												'trackingURL' => ''
										);

// Common to Both Discussions & Questions
define("REPORT_ABUSE","report_abuse");
$config['REPORT_ABUSE_DETAILS'] =array(
											'content_owner' => array(
												'gcm' => true,
												'title' => '<App>',
												'message' => '',
												'summation_messgae' => '',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => '',
												'entity_on_action' => '',
												'landing_page' => HELP_PAGE,
												'notificationType' => WEBVIEW_NOTIFICATION,
												'commandType' => 0,
												'trackingURL' => ''
											),

											'request_initiater' => array(
												'gcm' => false,
												'title' => '<App>',
												'message' => '',
												'summation_messgae' => '',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => '',
												'entity_on_action' => '',
												'landing_page' => HELP_PAGE,
												'notificationType' => WEBVIEW_NOTIFICATION,
												'commandType' => 0,
												'trackingURL' => ''
											),											
										);


define("AUTOMATIC_DELETION_OF_CONTENT","automatic_deletion_of_content");
define("REPORT_ABUSE_ACCEPTED_WITH_PENALTY","report_abuse_accepted_with_penalty");
define("REPORT_ABUSE_ACCEPTED_WITHOUT_PENALTY","report_abuse_accepted_without_penalty");
define("REPORT_ABUSE_REJECTED_WITH_PENALTY","report_abuse_rejected_with_penalty");
define("REPORT_ABUSE_REJECTED_WITHOUT_PENALTY","report_abuse_rejected_without_penalty");

define('LEVEL_UP', 'level_up');
define('ACHIEVEMENT_UPVOTE_ANSWER', 'achievement_upvote_answer');
define('ACHIEVEMENT_USER_FOLLOW', 'achievement_user_follow');
define('JOINING_BONUS', 'joining_bonus');
define('USER_FOLLOW', 'user_follow');


$config['LEVEL_UP_MESSAGES'] = array(
								'1' => "Off to a great start",
								'2' => "Keep it up",
								'3' => "You're on the right track",
								'4' => "Climbing up the ladder",
								'5' => "Woah! Great Jump",
								'6' => "Stunning success",
								'7' => "Someone’s getting famous",
								'8' => "Outstanding success",
								'9' => "You're a rockstar!",
								'10' => "When you talk, people hear",
								'11' => "Brilliant work!",
								'12' => "You deserve a trophy!",
								'13' => "Slow clap for yourself",
								'14' => "Respect is all we have for you",
								'15' => "Now that is stunning progress",
								'16' => "You're an elite expert",
								'17' => "You're an elite expert",
								'18' => "You're an elite expert"
									);

$config['LEVEL_UP_DETAILS'] =array(
												'gcm' => false,
												'title' => '<App>',
											//	'message' => '<b>$1.$2.</b>You have reached <b>$1</b>. Keep answering',
												'message' => 'Congrats! You have been promoted to <b>$1</b>. Keep answering!',
												'gcm_message' => 'Congrats! You have been promoted to <b>$1</b>. Keep answering!',
												'summation_messgae' => 'Congrats! You have been promoted to <b>$1</b>. Keep answering!',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => null,
												'landing_page' => APP_USER_PROFILE_PAGE,
												'notificationType' => USER_PROFILE_PAGE_DEFAULT,
												'gcm_notificationType' => LEVEL_UP_DIALOG,
												'commandType' => 1,
												'trackingURL' => '',
												'followersMessage'=>'<b>$4</b> whom you follow is now promoted to <b>$1</b>',
												'followersGCM' => false
										);

$config['JOINING_BONUS_DETAILS'] =array(
												'gcm' => false,
												'title' => '<App>',
												'message' => 'Congratulations! You have been given joining bonus of <b>$1</b> points',
												'gcm_message' => 'Congratulations! You have been given joining bonus of $1 points',
												'summation_messgae' => 'Congratulations! You have been given joining bonus of <b>$1</b> points',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => null,
												'landing_page' => HELP_PAGE,
												'notificationType' => WEBVIEW_NOTIFICATION,
												'gcm_notificationType' => POINTS_UP_DIALOG,
												'commandType' => 1,
												'trackingURL' => '',
										);

$config['ACHIEVEMENT_UPVOTE_ANSWER_DETAILS'] =array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'Great answer! <b>$2</b> people have upvoted your answer to <b>$1</b>',
												'gcm_message' => 'Great answer! <b>$2</b> people have upvoted your answer to <b>$1</b>',
												'summation_messgae' => 'Great answer! <b>$2</b> people have upvoted your answer to <b>$1</b>',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => null,
												'landing_page' => APP_QUESTION_DETAIL_PAGE,
												'notificationType' => QUESTION_DETAIL_PAGE_WITH_REFERENCE_ANSWER,
												'gcm_notificationType' => QUESTION_DETAIL_PAGE_WITH_REFERENCE_ANSWER,
												'commandType' => 0,
												'trackingURL' => ''

										);

$config['ACHIEVEMENT_USER_FOLLOW_DETAILS'] =array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'Congratulations! You are becoming popular on <b><App></b>, you have now <b>#count#</b> followers',
												'gcm_message' => 'Congratulations! You are becoming popular on <App>, you have now #count# followers',
												'summation_messgae' => 'Congratulations! Your answer to <b>$1</b> has now <b>$2</b> upvotes',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => null,
												'landing_page' => APP_USER_PROFILE_PAGE,
												'notificationType' => USER_PROFILE_PAGE_DEFAULT,
												'gcm_notificationType' => USER_PROFILE_PAGE_DEFAULT,
												'commandType' => 0,
												'trackingURL' => ''

										);

$config['USER_FOLLOW_DETAILS'] =array(
												'gcm' => true,
												'title' => '<App>',
												'message' => '<b>$1</b> is following you',
												'gcm_message' => '$1 is following you',
												'summation_messgae' => '<b>#count#</b> new followers',
												'apply_limit' => true,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => null,
												'landing_page' => APP_USER_PROFILE_PAGE,
												'notificationType' => USER_PROFILE_PAGE_DEFAULT,
												'gcm_notificationType' => USER_PROFILE_PAGE_DEFAULT,
												'commandType' => 0,
												'trackingURL' => ''

										);




define('FOLLOWS_USER','follows_user');
define('MOST_ACTIVE_USER_ON_TAG','most_active_user_on_tag');
$config['MOST_ACTIVE_USER_ON_TAG_DETAILS'] =array(
												'gcm' => true,
												'title' => '<App>',
												'message' => 'Congratulations! You are one of the top 10 active people associated with the tag - <b>$1</b>.',
												'gcm_message' => 'Congratulations! You are one of the top 10 active people associated with the tag - $1.',
												'summation_messgae' => '',
												'apply_limit' => false,
												'action_item' => '',
												'isMultipleRecipient' => false,
												'recipientFunction' => null,
												'entity_on_action' => null,
												'landing_page' => APP_USER_PROFILE_PAGE,
												'notificationType' => USER_PROFILE_PAGE_DEFAULT,
												'gcm_notificationType' => USER_PROFILE_PAGE_DEFAULT,
												'commandType' => 0,
												'trackingURL' => ''

										);


?>