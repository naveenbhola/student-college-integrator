<?php
//Main class for Online Form Enterprise
class OnlineMailer extends MX_Controller {

	private function init(){
		
		$this->load->helper(array('form', 'url','date','image','shikshaUtility','utility_helper'));
		$this->load->library(array('OnlineFormEnterprise_client','Online_form_client','Online_form_mail_client','Alerts_client'));
		$this->load->model('enterprise_mailermodel');
	        $this->load->model('Online/onlineparentmodel');
		$this->load->model('Online/OnlineModel');
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$this->courseRepository = $listingBuilder->getCourseRepository();
	}
	
	/************************************************************************************
	//used for getting data of all the templates which is set in the select drop down box.
	*************************************************************************************/
	
	public function getAllTemplatesData(){
		$this->init();
		$courseId = trim($this->input->post('courseId'));
		$results=$this->enterprise_mailermodel->getallTemplates($courseId);
		$displayData = array();
		$displayData['mailerInfo'] = $results;
		$results = json_encode($displayData['mailerInfo']);
		echo $results;    
	}

	/***************************************************************************************************
	//function collects values of all the variables and calls the functions to send the template.
	****************************************************************************************************/
	
	public function sendFormData(){
		$successMsg1="Mail sent successfully.";
		$this->init();
		$subject = trim($this->input->post('subject'));
		$body = trim($this->input->post('body'));
		$formids = trim($this->input->post('formsMailed'));
		$course = $this->courseRepository->find(trim($this->input->post('courseId')));
		$institute = $this->OnlineModel->getOnlineInstituteForCourseId($course->getId());
		$date= date('Y-m-d H:i:s');
		$users = $this->enterprise_mailermodel->getdataforFormIds(json_decode($formids,true));
		foreach($users as $user){
			$this->_sendemail($user,$subject,$body,$course,$institute);
		}
		echo $successMsg1;
	}
	
	/**************************************************************
	//function is used for saving as well as sending the template.
	**************************************************************/
	
	public function sendnSaveFormData(){
		$successMsg2="Template updated and sent successfully.";
		$successMsg3="Template with new subject name added and mail sent successfully.";
		$failureMsg1="A Template with this name exists already! Create a new template.";
		$this->init();
		$subject = trim($this->input->post('subject'));
		$body = trim($this->input->post('body'));
		$templateId=isset($_POST['changeTemplate'])?trim($this->input->post('changeTemplate')):'';
		$selectBoxFlag=isset($_POST['selectBoxFlag'])?trim($this->input->post('selectBoxFlag')):'';
		$courseId = trim($this->input->post('courseId'));
		$course = $this->courseRepository->find($courseId);
		$formids = trim($this->input->post('formsMailed'));
		$institute = $this->OnlineModel->getOnlineInstituteForCourseId($course->getId());
		$date= date('Y-m-d H:i:s');
		$users = $this->enterprise_mailermodel->getdataforFormIds(json_decode($formids,true));
		//get the db subject of the template for updating when he has not checked create new radio button.
		if($templateId!='' && $selectBoxFlag=='1'){
		    $result = $this->enterprise_mailermodel->getTemplateData($templateId,$courseId);
		    $dbSubject=mysql_escape_string($result['Subject']);
		}
		$formids = trim($this->input->post('formsMailed'));
	    
	 
	   
		//this is used to compare the subject entered by the user and the subject in the db for particular selected subject id.    
		if(strcmp($subject,$dbSubject)==0){
			$result = $this->enterprise_mailermodel->updateTemplate($templateId,$dbSubject,$body,$date);
			if($result){
				foreach($users as $user){
					$this->_sendemail($user,$subject,$body,$course,$institute);
				}	
				echo $successMsg2;
			}
		}
		else{
			//check if template to be added and the template with same name exists already!!!
			$templates=$this->enterprise_mailermodel->getallTemplates($courseId);
			foreach($templates as $template){
				if(strcmp($subject,$template['templateName'])==0){
					echo $failureMsg1;
					return;
				}
			}
			 
			$result = $this->enterprise_mailermodel->addTemplate($subject,$body,$date,$courseId);
			if($result){
				foreach($users as $user){
					$this->_sendemail($user,$subject,$body,$course,$institute);
				}
				echo $successMsg3;
			}
		}
	}
        
	/**************************************************************
	//this function is used to get details of a particular template
	/**************************************************************/
	
	function getTemplateInfo(){
		$templateId=trim($this->input->post('id'));
		$courseId=trim($this->input->post('courseId'));
		$this->init();
		$result = $this->enterprise_mailermodel->getTemplateData($templateId,$courseId);
		if($result){
			echo json_encode($result);
		}
	}
	
	/*************************************
	//This function is used to send email
	Input :user,subject,body,course,institute
	/*************************************/
	
	private function _sendemail($user,$subject,$body,$course,$institute){
		$appId=12;
		$fromAddress=SHIKSHA_ADMIN_MAIL;
		$userEmail=$user['email'];
		(($user['middleName']!='')&&($user['firstName']!=''))?$space1 = " ":$space1="";
		(($user['lastName']!='')&&(($user['firstName']!='')||($user['middleName']!='')))?$space2 = " ":$space2="";
		$userName=($user['firstName']||$user['lastName']||$user['lastName'])?$user['firstName']."$space1".$user['middleName']."$space2".$user['lastName']:"Applicant";
		$content = 'Dear '.$userName.",<br/><br/>Following is the message from ".html_escape($course->getInstituteName());
		if($user['onlineFormId']){
			$content .= " regarding your application form number: ".$user['onlineFormId']." for ";
		}else{
			$content .= " regarding your application for ";
		}
		$content .= html_escape($course->getName())."<br/><br/>".$body;
		$content .= "<br/><br/>Note: If you have any concern regarding this message, please contact the Institute directly through email at ".$institute['instituteEmailId']." or ".$institute['instituteMobileNo']." You can also write to help@shiksha.com regarding any technical queries. ";
		$data['usernameemail'] = $userEmail;
		$data['content'] = $content;
		$content = $this->load->view('user/PasswordChangeMail',$data,true);
		$AlertClientObj = new Alerts_client();
		//$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
		Modules::run('systemMailer/SystemMailer/onlineFormMailers', $userEmail, "form_institute_sends_message", $subject, $content);
	}	

}
