<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();


define("D_QnA_PAGEVIEW", "qnaPage");
define("D_QnA_ASK_QUES_WIDGET", 70);
define("D_QnA_ANSWER_POST_WIDGET",73);
define("D_QnA_DCOMMENT_POST_WIDGET",75);
define("D_QnA_TUP_ANSWER_TUPLE",88);
define("D_QnA_TDOWN_ANSWER_TUPLE", 89);
define("D_QnA_TUP_DCOMMENT_TUPLE", 850);
define("D_QnA_TDOWN_DCOMMENT_TUPLE",851);
define("D_QnA_FOLLOW_QUES", 852);
define("D_QnA_FOLLOW_DISC", 853);
define("D_QnA_FOLLOW_TAGS_RECOMMENDATION", 854);
define("D_QnA_FOLLOW_USER_RECOMMENDATION", 855);
define('D_QnA_FOLLOW_USER_FOLLOWER_LIST',857);
//define('D_QnA_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI',4000);
define('D_QnA_FOLLOW_USER_RIGHT_MOST_ACTIVE',858);
define("D_QnA_RIGHT_REG_WIDGET",856);

define("D_DISC_PAGEVIEW", 'discussionPage');
define("D_DISC_ASK_QUES_WIDGET", 860);
define("D_DISC_POST_DISC_WIDGET", 859);
define("D_DISC_DCOMMENT_POST_WIDGET", 861);
define("D_DISC_TUP_DCOMMENT_TUPLE", 862);
define("D_DISC_TDOWN_DCOMMENT_TUPLE",863);
define("D_DISC_FOLLOW_DISC", 864);
define('D_DISC_FOLLOW_USER_FOLLOWER_LIST',866);
//define('D_DISC_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI',4001);
define('D_DISC_FOLLOW_USER_RIGHT_MOST_ACTIVE',865);
//define("D_DISC_RIGHT_REG_WIDGET",6001);


define('D_UANS_PAGEVIEW',"unAnsweredPage");
define("D_UANS_ASK_QUES_WIDGET", 872);
define("D_UANS_ANSWER_POST_WIDGET", 871);
define("D_UANS_FOLLOW_QUES", 867);
define('D_UANS_FOLLOW_USER_FOLLOWER_LIST',868);
//define('D_UANS_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI',4002);
define('D_UANS_FOLLOW_USER_RIGHT_MOST_ACTIVE',869);
//define("D_UANS_RIGHT_REG_WIDGET",6002);


define('D_QDP_PAGEVIEW','questionDetailPage');
define('D_QDP_ABUSE_QUESTION',108);
define('D_QDP_ABUSE_ANSWER', 109);
define('D_QDP_ABUSE_COMMENT',110);
define("D_QDP_ANSWER_POST_WIDGET", 106);
define("D_QDP_TUP_ANSWER_TUPLE",111);
define("D_QDP_TDOWN_ANSWER_TUPLE", 112);
define("D_QDP_COMMENT_POST_WIDGET", 107);
define("D_QDP_ASK_QUES_ASKNOW_BOTTOM_WIDGET", 104);
define("D_QDP_FOLLOW_QUES", 884);
define("D_QDP_ANSWER_LATER",895);
define("D_QDP_FOLLOW_USER_FOLLOWER_LIST",885);
define("D_QDP_FOLLOW_USER_UPVOTERS_LIST",886);
define('D_QDP_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI',887);
define('D_QDP_FOLLOW_USER_RIGHT_MOST_ACTIVE',888);
define("D_QDP_ASK_QUES_ASKNOW_RIGHT_WIDGET", 1196);


define("D_DDP_PAGEVIEW", "discussionDetailPage");
define('D_DDP_ABUSE_DISCUSSION', 117);
define('D_DDP_ABUSE_DISCCOMMENT', 118);
define('D_DDP_ABUSE_DISCREPLY', 119);
define("D_DDP_DCOMMENT_POST_WIDGET", 115);
define("D_DDP_TUP_DCOMMENT_TUPLE", 533);
define("D_DDP_TDOWN_DCOMMENT_TUPLE", 534);
define("D_DDP_ASK_QUES_ASKNOW_BOTTOM_WIDGET", 894);
define("D_DDP_REPLY_POST_WIDGET",116);
define("D_DDP_FOLLOW_DISC", 889);
define("D_DDP_COMMENT_LATER",896);
define("D_DDP_FOLLOW_USER_FOLLOWER_LIST",890);
define("D_DDP_FOLLOW_USER_UPVOTERS_LIST", 891);
define('D_DDP_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI',892);
define('D_DDP_FOLLOW_USER_RIGHT_MOST_ACTIVE',893);

define("D_TDP_PAGEVIEW","tagDetailPage");
define("D_TDP_ALL_TAB_PAGEVIEW","home");
define("D_TDP_DISC_TAB_PAGEVIEW", "discssuions");
define("D_TDP_UANS_TAB_PAGEVIEW", "unanswered");

define("D_TDP_ALL_ANSWER_POST_WIDGET", 822);
define("D_TDP_ALL_DCOMMENET_POST_WIDGET", 823);
define("D_TDP_ALL_TUP_ANSWER_TUPLE", 824);
define("D_TDP_ALL_TDOWN_ANSWER_TUPLE", 825);
define("D_TDP_ALL_TUP_DCOMMENT_TUPE", 826);
define("D_TDP_ALL_TDOWN_DCOMMENT_TUPLE", 827);
define("D_TDP_ALL_FOLLOW_TAG_STATS_WIDGET", 828);
define("D_TDP_ALL_FOLLOW_QUES", 829);
define("D_TDP_ALL_FOLLOW_DISC", 830);
define("D_TDP_ALL_FOLLOW_USER_ACTIVE_WIDGET", 831);
define("D_TDP_ALL_FOLLOW_TAG_RELATED_WIDGET", 832);
define('D_TDP_ALL_FOLLOW_USER_FOLLOWER_LIST',844);
define('D_TDP_ALL_FOLLOW_USER_RIGHT_MOST_ACTIVE',847);
define('D_TDP_ALL_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI',831);
define("D_TDP_ALL_RIGHT_REG_WIDGET",833);

define("D_TDP_DISC_TAB_DCOMMENT_POST_WIDGET", 834);
define("D_TDP_DISC_TAB_TUP_DCOMMENT_TUPLE", 835);
define("D_TDP_DISC_TAB_TDOWN_DCOMMENT_TUPLE", 836);
define("D_TDP_DISC_FOLLOW_DISC", 837);
//define("D_TDP_DISC_FOLLOW_USER_ACTIVE_WIDGET", 1051);
define("D_TDP_DISC_FOLLOW_TAG_RELATED_WIDGET", 839);
define('D_TDP_DISC_FOLLOW_USER_FOLLOWER_LIST',845);
define('D_TDP_DISC_FOLLOW_USER_RIGHT_MOST_ACTIVE',848);
define('D_TDP_DISC_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI',838);
define("D_TDP_DISC_RIGHT_REG_WIDGET",6003);

define("D_TDP_UANS_TAB_ANSWER_POST_WIDGET", 840);
define("D_TDP_UANS_FOLLOW_QUES", 841);
//define("D_TDP_UANS_FOLLOW_USER_ACTIVE_WIDGET", 1055);
define("D_TDP_UANS_FOLLOW_TAG_RELATED_WIDGET", 843);
define('D_TDP_UANS_FOLLOW_USER_FOLLOWER_LIST',846);
define('D_TDP_UANS_FOLLOW_USER_RIGHT_MOST_ACTIVE',849);
define('D_TDP_UANS_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI',842);
//define("D_TDP_UANS_RIGHT_REG_WIDGET",6003);
define("D_VIEW_TAGS_PAGEVIEW", 'viewAllTagsPage');

//
define("D_ALL_QUES_PAGEVIEW", "allQuestionsPage");
define("D_ALL_QUES_ASK_QUE_STICKY", 90);
define("D_ALL_QUES_ANSWER_POST_WIDGET",93);
define("D_ALL_QUES_TUP_ANSWER_TUPLE",870);
define("D_ALL_QUES_TDOWN_ANSWER_TUPLE", 873);
define("D_ALL_QUES_FOLLOW_QUES", 874);
define('D_ALL_QUES_FOLLOW_USER_FOLLOWER_LIST',875);
define('D_ALL_QUES_FOLLOW_USER_RIGHT_MOST_ACTIVE',876);
define('D_ALL_QUES_RIGHT_REG_WIDGET',897);

define("D_ALL_DISC_PAGEVIEW", "allDiscussionsPage");
define("D_ALL_DISC_ASK_QUE_STICKY", 878);
define("D_ALL_DISC_POST_DISC_STICKY", 99);
define("D_ALL_DISC_DCOMMENT_POST_WIDGET", 877);
define("D_ALL_DISC_TUP_DCOMMENT_TUPLE", 879);
define("D_ALL_DISC_TDOWN_DCOMMENT_TUPLE",880);
define("D_ALL_DISC_FOLLOW_DISC", 881);
define('D_ALL_DISC_FOLLOW_USER_FOLLOWER_LIST',882);
define('D_ALL_DISC_FOLLOW_USER_RIGHT_MOST_ACTIVE',883);
define('D_ALL_DISC_RIGHT_REG_WIDGET',898);


define('D_EP_PAGEVIEW','advisoryBoardPage');
define('D_EP_FOLLOW_USER',538);

define('D_QDP_RIGHT_REGISTRATION',1194);
define('D_DDP_RIGHT_REGISTRATION',1209);

?>
