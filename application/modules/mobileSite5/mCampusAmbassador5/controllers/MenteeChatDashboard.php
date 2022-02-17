<?php
class MenteeChatDashboard extends ShikshaMobileWebSite_Controller{
	function init()
	{
		$this->userStatus = $this->checkUserValidation();

		$this->load->model('CA/mentormodel');
		$this->mentorModel = new MentorModel();
	}

	function checkMentorAssignToMentee($menteeId){
		$this->init();
		if($menteeId>0){
			$mentorInformation = $this->mentorModel->checkIfMentorAssignedToMentee(array($menteeId));
	    }else{
	    	$mentorInformation[$menteeId] = false;
	    }
	    return $mentorInformation;
	}

	function authCheck()
	{
	   $this->init();
	   $this->userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	   if($this->userId>0){
			$this->menteeToMentorMappingInfo = $this->checkMentorAssignToMentee($this->userId);
			$mentorInfo = $this->menteeToMentorMappingInfo;
			if(!$mentorInfo[$this->userId]){
				header('location:'.SHIKSHA_HOME);exit;
			}
	   }else{
	   		header('location:'.SHIKSHA_HOME);exit;
	   }
	}
	/**
	 *
	 * Show Menteee Dashboard Mobile
	 *
	 * @param    None
	 * @return   View with the Homepage
	 *
	 */

	function menteeDashboard()
	{
		redirect(SHIKSHA_HOME, 'location', 301);exit;		
		$this->authCheck();
		$mentorInfo = $this->menteeToMentorMappingInfo;
		$displayData['mentorId'] =  $mentorInfo[$this->userId]['mentorId'];
		$displayData['chatId']   = $mentorInfo[$this->userId]['chatId'];
		$displayData['boomr_pageid'] = 'MenteeDashboard';
		$displayData['slotData'] = $this->mentorModel->checkIfMenteeBookedOrRequestSlot($this->userId,$displayData['mentorId']);
		$displayData['scheduleData'] = $this->mentorModel->checkIfMenteeHasAnyScheduledChat($this->userId,$displayData['mentorId']);
		$this->load->view('mentorship/menteeDashboard',$displayData);
	}

	function mentorDetail($mentorId)
	{
		$this->init();
		$this->load->helper('CA/cr');
		$this->load->model('CA/camodel');
		$caModel = new CAModel();
		$mentorDetails = $caModel->getAllCADetails($mentorId);
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		$courseObj = $courseRepository->find($mentorDetails[0]['ca']['mainEducationDetails'][0]['courseId']);
		$displayData['courseObj'] = $courseObj;
		$displayData['mentorDetails'] = $mentorDetails;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$instObj = $instituteRepository->find($mentorDetails[0]['ca']['mainEducationDetails'][0]['instituteId']);	
		$displayData['instObj'] = $instObj;
		$mentorSlots = $this->mentorModel->checkMentorSlots($mentorId);
		$formattedMentorSlots  = formatMentorSlots($mentorSlots);
		$displayData['mentorSlots'] = $formattedMentorSlots;
		$this->load->view('mentorship/mentorWidget',$displayData);
	}

	function menteeChatWidget($mentorId,$slotData,$scheduleData, $chatId)
	{
		$this->init();
		$mentorSlots = $this->mentorModel->checkMentorSlots($mentorId);
		$formattedMentorSlots  = formatMentorSlots($mentorSlots);
		$displayData['mentorSlots'] = $formattedMentorSlots;
		$showSlotSection = 'ShowForm';
		$displayData['mentorId'] = $mentorId;
		$displayData['chatType'] = 'scheduled';
		if($slotData['slotStatus']=='booked'){
		        if($slotData['scheduleStatus']=='accepted'){
		                $showSlotSection = 'ChatScheduled';
		        }else if($slotData['scheduleStatus']=='started'){
		                $showSlotSection = 'StartChat';
		        }else{
		                $showSlotSection = 'ShowForm';
		        }
		}
		if($slotData['slotStatus']=='free'){
		    $showSlotSection = 'ChatScheduled';
		    $displayData['chatType'] = 'requested';
		}
		if(is_array($scheduleData[0]) && !empty($scheduleData[0]))
		{
		        $showSlotSection = 'StartChat';
		}
		if($showSlotSection=='ChatScheduled'){
			$displayData['slotData'] = $slotData;
	        $this->load->view('mentorship/menteeChatScheduledWidget',$displayData);
	    }else if($showSlotSection=='StartChat'){
		$displayData['chatId'] = $chatId;
		$displayData['showChatWidget'] = 'yes';
	        $this->load->view('mentorship/menteeChatStartWidget',$displayData);
	    }else{
	        $this->load->view('mentorship/menteeChatWidget',$displayData);
	    }
	}

	function chatHistoryWidget($mentorId)
	{
		$this->init();
		$displayData = array();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		if($mentorId > 0  && $userId > 0)
		{
			$displayData['completedChats'] = $this->mentorModel->getMentorshipChatHistory($mentorId, $userId, 'mobile');
		}
		//_p($displayData);
		$this->load->view('mentorship/chatHistoryWidget', $displayData);
	}

	function bookFreeSlotByMentee(){
		$this->init();
		$params = array();
		$params['slotId'] = $this->input->post('slotId');
		$status = $this->mentormodel->checkSlotStatus($params['slotId']);
		if($status=='free'){
			$params['menteeId'] = $this->input->post('menteeId');
			$params['mentorId'] = $this->input->post('mentorId');
			$params['discussionTopic'] = $this->input->post('discussionTopic');
			$this->mentormodel->bookFreeSlotByMentee($params);
		}
		echo $status;exit;
	}


	function requestChatSlotbyMentee(){
		$this->init();
		$ymd = explode('/', $this->input->post('slotDate'));
		$date = $ymd[2].'-'.$ymd[1].'-'.$ymd[0].', '.$this->input->post('slotHour').':'.$this->input->post('slotMin').' '.$this->input->post('amPmStr');
		$data = array();
		$data['userId']           = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$data['slotTime']         = date('Y-m-d H:i:s', strtotime($date));
		$data['discussionTopic']  = $this->input->post('discussionTopic');
		$data['userType']         = 'mentee';
		$data['slotStatus']       = 'free';
		$data['createdDate']      = date('Y-m-d H:i:s');
		$data['modificationDate'] = date('Y-m-d H:i:s');
		$data['mentorId']         = $this->input->post('mentorId');
		$slotStatus = $this->mentormodel->checkIfRquestedSlotAlreadyBooked($data['slotTime'],$data['mentorId'],$data['userId']);
		if($slotStatus=='1'){
			echo "BOOKED";exit;
		}
		$new_time = date("Y-m-d H:i:s", strtotime('+2 hours'));
		if(strtotime($data['slotTime'])<=strtotime($new_time)){
			echo "PAST_TIME";exit;
		}
		$this->mentormodel->addMentorShipChatSlot($data);
		echo 'done';exit;
	}

	function cancelScheduledChat()
	{
		$this->init();
		$slotId       = (isset($_POST['slotId']) && $_POST['slotId'] != '')?$this->input->post('slotId'):0;
		$scheduleId   = (isset($_POST['scheduleId']) && $_POST['scheduleId'] != '')?$this->input->post('scheduleId'):0;
		$userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userType      = isset($_POST['userType'])?$this->input->post('userType'):'mentor';
		if($userType=='mentee'){
			$this->mentormodel->updateChatSlotStatus('decline', $slotId);
		}else{
			$this->mentormodel->updateChatSlotStatus('free', $slotId);
		}
		if($scheduleId>0){
			$this->mentormodel->updateChatScheduleStatus('cancelled', $userId, $scheduleId);
		}
		echo 'success';die;
	}
	
	function getMentorshipChat()
	{
		$this->init();
		if($this->input->is_ajax_request()){
			$logId  = (isset($_POST['chatLogId']) && $_POST['chatLogId'] != '')?$this->input->post('chatLogId'):0;
			$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			if($userId > 0)
			{
				$chatText = $this->mentormodel->getMentorshipChatById($logId);
				if($chatText != '')
					echo json_encode(array('status'=>'success', 'chatHistory'=>$chatText));die;
			}
		}
		echo json_encode(array('status'=>'error', 'chatHistory'=>'Sorry no chat is available.'));
	}
}
?>
