<?php $this->load->view('systemMailer/CafeMailers/CafeHeaderMailer'); ?>
<?php
switch($mailer_name){
    case 'thumbUpMail':
        $this->load->view('systemMailer/CafeMailers/answerThumbUp');
        break;
    case 'discussionThumbMail':
        $this->load->view('systemMailer/CafeMailers/discussionThumbUp');
        break;
    case 'AnswerPostToQuestion':
        $this->load->view('systemMailer/CafeMailers/answerPostToQuestion');
        break;
    case 'AnswerPostToQuestionAllUser':
        $this->load->view('systemMailer/CafeMailers/answerPostToQuestionAllUser');
        break;
    case 'CommentPostOnAnswer':
        $this->load->view('systemMailer/CafeMailers/commentPostOnAnswer');
        break;
    case 'AnswerSelectedAsBestAnswer':
        if($type == 'bestAnsMail')
            $this->load->view('systemMailer/CafeMailers/bestAnswerMail');
        else
            $this->load->view('systemMailer/CafeMailers/bestAnswerMailAllUser');
        break;
    case 'QuestionDiscussionAnnouncementPost':
        if($type == 'askQuestion')
        {
            if($page_name == 'myShortlist_Ana')
                $this->load->view("systemMailer/CafeMailers/MailToUserFromShortlistPage");
            else if($catId == '2')
                $this->load->view("systemMailer/CafeMailers/askQuestionEngineering");
            else
                $this->load->view("systemMailer/CafeMailers/askQuestion");
        }
        else if($type == 'postTopic')
        {
            $this->load->view("systemMailer/CafeMailers/postDiscussionOrAnnouncement");
        }
        break;
    case 'dailyMailerToEnterpriseUser':
    case 'dailyMailerToNonEnterpriseUser':
        $this->load->view("systemMailer/CafeMailers/listingQnADailyMailer");
        break;
    case 'userLevelPromotion':
        $this->load->view('systemMailer/CafeMailers/userLevelPromotion');
        break;
}
?>
<?php $this->load->view('systemMailer/CafeMailers/CafeFooterMailer'); ?>