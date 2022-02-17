<?php

class UserCrons extends MX_Controller{


	function sendUserProfileReport(){
		ini_set("memory_limit", '2048M');
		ini_set('time_limit', -1);

		$memory = memory_get_usage(true);
		$time = microtime(true);

		$userProfileData = array();

		$userprofilemodel = $this->load->model('userProfile/userprofilemodeldesktop');
		$userProfileLib =  $this->load->library('user/UserProfileLib');

		$fromDate = date("Y-m-d", strtotime("-8 day"));
		$toDate = date("Y-m-d");

		$userIds = $userprofilemodel->getUserVistedProfilePage($fromDate, $toDate);
		if(!empty($userIds)){
			$userProfileData['userPersonalInfo'] = $userProfileLib->getuserPersonalInfo($userIds); 
			$userProfileData['userWorkEx'] = $userProfileLib->getUserWorkExData($userIds);
			$userProfileData['userEducation'] = $userProfileLib->getUserEducationData($userIds);
			$userProfileData['userPreference'] = $userProfileLib->getUserPreferenceData($userIds);
		}
		
		$userData = $userProfileLib->formatUserReportData($userProfileData);
		unset($userProfileData);
		$memory = (memory_get_usage(true) - $memory)/1024/1024;
		$time = microtime(true) - $time;
		$this->sendCSVMail($userData, $memory, $time);
		
	}

	public function sendCSVMail($userData, $memory, $time){
		$this->load->library('CollegeReviewForm/CollegeReviewLib');
		$crLib = new CollegeReviewLib;

		$date = date("Y-m-d");
		$message = 'Here is the weekly report of Unified Profile for '.$date.'. <br/><br/>';
		$message .= 'Total time taken: '.$time." s<br/>";
		$message .= 'Total memory taken: '.$memory." MB<br/>";
		$message .= '<br/>-Thanks,';
		$message .= '<br/>LDB Team,';

		$subject = 'Unified Profile Weekly Report for '.$date;
		
		$to = 'karan.chawla@shiksha.com, harshit.mago@shiksha.com';
		
		$crLib->send_csv_mail($userData,$message, $to, $subject, 'info@shiksha.com');
	}

	
}