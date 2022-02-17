<?php $this->load->view('systemMailer/CampusAmbassador/CAHeaderMailer'); ?>
<?php
	switch($type){
		case 'CAAcceptMailer':
			if($parentId == '2'){
				$this->load->view("systemMailer/CampusAmbassador/CAAcceptMailerEngineering");
			}else{
				$this->load->view("systemMailer/CampusAmbassador/CAAcceptMailer");
			}
			break;
		case 'CARejectMalier':
			$this->load->view("systemMailer/CampusAmbassador/CARejectMalier");
			break;
		case 'CAIncompleteMailer':
			$this->load->view("systemMailer/CampusAmbassador/CAIncompleteMailer");
			break;
		case 'REB':
			$this->load->view("systemMailer/CampusAmbassador/campusAmbassadorREBMailer");
			break;
		case 'CAQuestionIntimation':
			$this->load->view("systemMailer/CampusAmbassador/questionmailtoCampusReps");
			break;
		case 'CAAnswerDisapprove':
			$this->load->view("systemMailer/CampusAmbassador/CRAnswerDisapprovalMailer");
			break;
		case 'CANewOpenTask':
			$this->load->view("systemMailer/CampusAmbassador/CRNewOpenTaskMailer");
			break;
		case 'CAUnansweredWeeklyDigest':
			$this->load->view("systemMailer/CampusAmbassador/CRUnansweredWeeklyDigestMailer");
			break;
		case 'MenteeRegistrationMailtoMentee':
			$this->load->view("systemMailer/Mentorship/menteeRegistrationMailtoMentee");
			break;
		case 'MentorAssignMailToMentee':
			$this->load->view('systemMailer/CampusAmbassador/mentorAssignMailer');
			break;
		case 'MenteeAssignMailToMentor':
			$this->load->view('systemMailer/CampusAmbassador/menteeAssignMailer');
			break;
		case 'requestChatByMentee':
			$this->load->view('systemMailer/CampusAmbassador/requestChatByMenteeMailer');
			break;
		case 'mentorToSetUpChat':
			$this->load->view('systemMailer/CampusAmbassador/mentorToSetUpChat');
			break;
		case 'menteeToSelectSlot':
			$this->load->view('systemMailer/CampusAmbassador/menteeToSelectSlot');
			break;
		case 'AcceptDeclineChatRequestByMentor':
			$this->load->view('systemMailer/CampusAmbassador/acceptDeclineChatRequestByMentor');
			break;
		case 'ChatScheduledMailToMentee':
			$this->load->view('systemMailer/CampusAmbassador/chatScheduledMailToMenteeMailer');
			break;
		case 'ChatScheduledMailToMentor':
			$this->load->view('systemMailer/CampusAmbassador/chatScheduledMailToMentorMailer');
			break;
		case 'ChatSessionCancelledByMentor':
			$this->load->view('systemMailer/CampusAmbassador/chatSessionCancelledByMentorMailer');
			break;
		case 'ChatSessionCancelledByMentee':
			$this->load->view('systemMailer/CampusAmbassador/chatSessionCancelledByMenteeMailer');
			break;
		case 'ChatCompletionMailToMentee':
			$this->load->view('systemMailer/CampusAmbassador/ChatCompletionToMenteeMailer');
			break;
		

	}
?>
<?php $this->load->view('systemMailer/CampusAmbassador/CAFooterMailer'); ?>
