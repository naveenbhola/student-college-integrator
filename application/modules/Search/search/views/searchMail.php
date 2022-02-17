<?php
//if($page_name != 'myShortlist_Ana' && $page_name != 'myShortlistAnA_anwser' && $page_name != 'myShortlistAnA_comment' && $type != 'new-answer-to-question-user' && $type != 'new-answer-to-all-user' && $type != 'myShortlist_Ana' && $type != 'new-reply-to-answer')
if($page_name != 'myShortlist_Ana' && $type != 'myShortlist_Ana')
{
	$this->load->view('common/mailContentHeader');
}
?>

<style>
ul,p{font-family:Arial, Helvetica, sans-serif;font-size:12px}
ul.ml_0{margin-left:0; list-style-position:inside}
a{color:#0066FF}
</style>
<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A1A1A; padding-bottom: 10px; line-height:20px;">
<?php
	switch($type){
		case 'new-answer-to-question-user':{
			//if($page_name=='myShortlistAnA_anwser'){
			//	$this->load->view("common/mailToUserAnswerFromShortlist",$course_id);
			//}else{
				$this->load->view("messageBoard/newAnswerToQuestionMail");	
			//}
		}
			break;
		case 'new-answer-to-all-user':{
			//if($page_name=='myShortlistAnA_anwser'){
			//	$this->load->view("common/mailToAnsAllUserFromShortlist",$course_id);
			//}else{
				$this->load->view("messageBoard/newAnswerToAllMail");
			//}
		}
			break;
		case 'new-reply-to-answer':
			//if($page_name == 'myShortlistAnA_comment' ){
			//	$this->load->view("common/mailToUserForCommentInshortlistPage",$course_id);
			//	
			//}else{
				$this->load->view("messageBoard/replyToAnsMail");
			//}	
			break;
		case 'comment-on-article':
			$this->load->view("blogs/commentOnArticleMail");
			break;
		case 'reply-to-comment-on-article':
			$this->load->view("blogs/replyToCommentOnArticleMail");
			break;
		case 'qnaShareQuestion':
			$this->load->view("messageBoard/shareThisQuestionMail");
			break;
		case 'listingAnADailyMailer':
			$this->load->view("messageBoard/listingQnADailyMailer");
			break;
		case 'listingAnADailyMailerForNonEnterpriseUsers':
			$this->load->view("messageBoard/listingQnADailyMailer");
			break;
		case 'mailThisQuestion':
			echo $bodyOfMail;
			//$this->load->view("messageBoard/mailThisQuestion");
			break;
		case 'bestAnsMail' :
			$this->load->view("messageBoard/bestAnswerMail");
			break;
		case 'bestAnsMailAll' :
			$this->load->view("messageBoard/bestAnswerMailAll");
			break;
		case 'digActivityMail' :
			$this->load->view("messageBoard/diggDiffMail");
			break;
		case 'alumFeedbackMail' :
			$this->load->view("common/mailAlumFeed");
			break;
		case 'abuseDeleteMail' :
			$this->load->view("common/mailAbuseDelete");
			break;
		case 'abuseAutoDeleteMail' :
			$this->load->view("common/mailAbuseAutoDelete");
			break;
		case 'abuseReportMail' :
			$this->load->view("common/mailAbuseReport");
			break;
		case 'republishAbuseMail' :
			$this->load->view("common/mailRepublishAbuse");
			break;
		case 'republishUserAbuseMail' :
			$this->load->view("common/mailRepublishUserAbuse");
			break;
		case 'abuseDigestMail' :
			$this->load->view("common/mailAbuseDigest");
			break;
		case 'abusePeopleMail' :
			$this->load->view("common/mailAbusePeople");
			break;
		case 'vcardMail' :
			$this->load->view("common/mailVCard");
			break;
		case 'userMail' :
			echo nl2br($content);
			break;
		case 'commentMail' :
			$this->load->view("common/mailCommentPosted");
			break;
		case 'sendCommentMailOwner' :
			$this->load->view("common/sendCommentMailOwner");
			break;
		case 'blogEmailThis':
            		$this->load->view("blogs/blogEmailThis");
			break;
		case 'promotionMail' :
			$this->load->view("common/sendPromotionMail");
			break;
		case 'thumbUpMail' :
			$this->load->view("common/sendThumbUpMail");
			break;
		case 'rejectAbuseMail' :
			$this->load->view("common/mailRejectAbuseMailOwner");
			break;
		case 'askQuestion' :
			{
			if($page_name == 'myShortlist_Ana'){
				$this->load->view("common/MailToUserFromShortlistPage");
				
			}else if($catId == '2'){	
				$this->load->view("common/mailAskQuestionEngineering");
			}else{
				$this->load->view("common/mailAskQuestion");
			}
			}
			break;
		case 'subscribeEvents' :
                        $this->load->view("user/ImpDatesReminderMail",$contentArray);
                        break;
		case 'deliveryOptionsasap' :
                        $this->load->view("user/delOptionsMailAsap",$contentArray);
                        break;
		case 'delOptionsMailCSV' :
                        $this->load->view("user/delOptionsMailCSV",$contentArray);
                        break;
                case 'deliveryOptions' :
                        $this->load->view("user/deliveryOptionsMail",$contentArray);
                        break;
                case 'dailyQuota' :
                        $this->load->view("user/dailyQuotaMail",$contentArray);
                        break;
		case 'dailyQuotaMail' :
                        $this->load->view("user/dailyQuotaMailMail",$contentArray);
                        break;
		case 'dailyQuotaSms' :
                        $this->load->view("user/dailyQuotaMailSms",$contentArray);
                        break;
                case 'globalQuota' :
                        $this->load->view("user/globalQuotaMail",$contentArray);
                        break;
		case 'postTopic' :
			$this->load->view("common/mailPostTopic");
			break;
		case 'new-comment-to-post' :
			$this->load->view("common/mailCommentOnPost");
			break;
		case 'new-reply-to-post' :
			$this->load->view("common/mailReplyOnPost");
			break;
                case 'InactiveUser':
                        $this->load->view("messageBoard/tenDaysMailer");
			break;
                case 'deleteTitle':
                        $this->load->view("messageBoard/deleteTitleMail");
			break;
                case 'liveTitle':
                        $this->load->view("messageBoard/liveTitleMail");
			break;
                case 'editTitle':
                        $this->load->view("messageBoard/editTitleMail");
			break;
                case 'mentionMail':
                        $this->load->view("common/mentionMail");
			break;
                case 'powerUserQuestionSuggestion':
                        $this->load->view("messageBoard/powerUserQuestionSuggestionMailer");
			break;
		case 'makePowerUser':
			$this->load->view("messageBoard/makePowerUserMailer");
			break;
		case 'powerUserDiscussionSuggestion':
                        $this->load->view("messageBoard/powerUserDiscussionSuggestionMailer");
			break;
		case 'approveExpertMail':
                        $this->load->view("messageBoard/approveExpertMail");
			break;
		case 'rejectExpertMail':
                        $this->load->view("messageBoard/rejectExpertMail");
			break;
		case 'questionClosureMail':
			$this->load->view("questionClosure/questionClosureMailer");
			break;
		case 'selectBestAnswer':
			$this->load->view("questionClosure/chooseBestAnswerMailer");
			break;
		default:
			$this->load->view("common/commonMailContent");
	}
?>
<?php /*if($page_name != "myShortlist_Ana" && $page_name != 'myShortlistAnA_anwser' && $page_name != 'myShortlistAnA_comment' && $type != 'new-answer-to-question-user' && $type != 'new-answer-to-all-user' && $type != 'myShortlist_Ana' && $type != 'new-reply-to-answer')*/
if($page_name != 'myShortlist_Ana' && $type != 'myShortlist_Ana')
{
	$this->load->view('common/mailContentFooter');
} ?>
