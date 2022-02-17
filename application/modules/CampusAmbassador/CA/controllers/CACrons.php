<?php
/*

   Copyright 2013-14 Info Edge India Ltd

   $Author: Virender

   $Id: CampusAmbassor

 */

class CACrons extends MX_Controller
{
	function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array(  $helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
	}
	function CAWeeklyDigestOfUnansweredQuestion()
	{
		$this->validateCron();
		$this->load->helper('cr');
		$fromDate = date('Y-m-d H:i:s', strtotime('-7 days'));
		$dashboard_unanswer = SHIKSHA_HOME.'/CA/CRDashboard/getCRUnansweredTab';
		$attachment = array();
		$this->load->model('qnamodel');
		$allCRData = $this->qnamodel->getAllCRDetailsForWeeklyDigestMail();
		$this->load->model('crdashboardmodel');
		if(!empty($allCRData))
		{
			$this->load->library('mailerClient');
			$MailerClient = new MailerClient();
			foreach($allCRData as $cr)
			{
				$CRCourseIdStr = $this->crdashboardmodel->getAllCourseIdFromCR($cr['userId']);
				
				if($CRCourseIdStr!='')
				{
					$CRUnansweredQues = $this->crdashboardmodel->getUnansweredQuestionsFromDbForWeeklyDigestMailer($cr['userId'], $CRCourseIdStr, $fromDate);
					if($CRUnansweredQues['total'] > 0)
					{
						//send mailer to user - begin
						$contentArr = array();
						$contentArr['crName'] = $cr['firstname'].' '.$cr['lastname'];
						$contentArr['ques'] = $CRUnansweredQues['result'];
						$contentArr['type'] = 'CAUnansweredWeeklyDigest';
						$contentArr['urlOfLandingPage'] = $dashboard_unanswer;
						$contentArr['autoLoginUrl'] = $MailerClient->generateAutoLoginLink(1, $cr['email'], $dashboard_unanswer);
						$this->load->model('mentormodel');
						$mentor = $this->mentormodel->checkUserIfMentor(array($cr['userId']));
						
						if($mentor[$cr['userId']] != 'false' && is_array($mentor[$cr['userId']]) && $mentor[$cr['userId']] != '')
						{
							$this->isMentor = true;	
						}
						else
						{
							$this->isMentor = false;	
						}
						
						$data['isCAEng'] = ($this->isMentor) ? 'Engineering' : '';
						
						$money = Array();
						foreach((object)$CRUnansweredQues['result'] as $result)
						{
							$timeDiff = (strtotime(date('Y-m-d H:i:s')) - strtotime($result->creationDate));
							$total = getCREarning($timeDiff,$data['isCAEng']);
							$money[] = $total['money'];
						}
						$contentArr['potentialEarn'] = array_sum($money);
						
						Modules::run('systemMailer/SystemMailer/CRUnansweredWeeklyDigestMail', $cr['email'], $contentArr, $attachment);
						//send mailer to user - end
					}
				}
			}
		}
	}
	
	/*
	Controller to send weekly list
	of all opened tasks for a CA
	for which he has not made any submission
	
	Author : Virender
	*/
	function CAWeeklyDigestOfOpenTasks()
	{
		$this->validateCron();
		$attachment = array();
		$this->load->model('cacronsmodel');
		//Gets all the active Campus Reps eligible to send mailers
		$allCRData = $this->cacronsmodel->getCAListForOpenTasks();
		
		$this->load->library('CACronsLib');
		$CACronsLib = new CACronsLib();
		
		foreach($allCRData as $data)
		{       
			//Get the open tasks for each user
			$allOpenTasks = $this->cacronsmodel->getOpenTasksForCAMailer($data['userId'], $data['programId']);
			
			if(!empty($allOpenTasks))
			{
			    //Formatting of task's data on the basis of end date task and tasks without end dates
			    $finalArr = $CACronsLib->formatCAOpenTaskData($allOpenTasks, $data['email']);
			    $contentArr = array();
			    $contentArr['crName'] = ucwords($data['firstname'].' '.$data['lastname']);
			    $contentArr['tasks'] = $finalArr;
			    Modules::run('systemMailer/SystemMailer/CROpenTasksWeeklyMail', $data['email'], $contentArr, $attachment);
			}
		}
	}
	
	function WeeklyMailerToSelectSlotForChat(){
		
		$this->load->model('cacronsmodel');
		
		//Gets all the mentor who have not see up any chat in last 1 week
		$allMentorDetails = $this->cacronsmodel->getMentorsToSetUpChat();
		
		foreach($allMentorDetails as $data)
		{
			$contentArr['mentorName'] = ucwords($data['firstname']);
			$urlOfLandingPage = SHIKSHA_HOME."/CA/CRDashboard/myChatTab";
			$contentArr['urlOfLandingPage'] = $urlOfLandingPage;
			Modules::run('systemMailer/SystemMailer/mentorToSetUpChatAvailability', $data['email'], $contentArr);
			
		}
		
		//Gets all the mentees who have not scheduled any chat in last 1 week
		$menteeArray1 = $this->cacronsmodel->getMenteeToSelectSlot();
		$menteeArray2 = $this->cacronsmodel->getMenteeToAcceptChatSchedule();
		
		$finalMenteeArray =  $this->array_intersect_fixed($menteeArray1,$menteeArray2);
		
		foreach($finalMenteeArray as $data)
		{
			$contentArr['menteeName'] = ucwords($data['firstname']);
			$urlOfLandingPage = SHIKSHA_HOME."/user/MyShiksha/index";
			$contentArr['urlOfLandingPage'] = $urlOfLandingPage;
			Modules::run('systemMailer/SystemMailer/menteeToSelectSlot', $data['email'], $contentArr);
			
		}
				
	}
	
	function array_intersect_fixed($array1, $array2) { 
		$result = array(); 
		foreach ($array1 as $val) { 
		  if (($key = array_search($val, $array2, TRUE))!==false) { 
		     $result[] = $val; 
		     unset($array2[$key]); 
		  } 
		} 
		return $result; 
	}
}
?>
