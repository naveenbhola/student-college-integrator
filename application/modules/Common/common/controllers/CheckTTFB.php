<?php

class CheckTTFB extends MX_Controller{

	function __construct() {
		$this->model = $this->load->model('common/checkttfbmodel');
	}

	public function checkTTFB(){
    
		$config['URLS'] = array(
				array('pageName'=>'Home Page', 'site'=>'Shiksha','URL'=>'https://www.shiksha.com','team_name'=>'Domestic'),
				array('pageName'=>'MBA CTP', 'site'=>'Shiksha','URL'=>'https://www.shiksha.com/mba/colleges/mba-colleges-bangalore','team_name'=>'Domestic'),
				array('pageName'=>'CLP', 'site'=>'Shiksha','URL'=>'https://www.shiksha.com/mba/course/post-graduate-program-in-management-indian-institute-of-management-ahmedabad-vastrapur-1653','team_name'=>'Domestic'),
				array('pageName'=>'CAT EP', 'site'=>'Shiksha','URL'=>'https://www.shiksha.com/mba/cat-exam','team_name'=>'Domestic'),
				array('pageName'=>'UILP', 'site'=>'Shiksha','URL'=>'https://www.shiksha.com/college/iim-ahmedabad-indian-institute-of-management-ahmedabad-vastrapur-307','team_name'=>'Domestic'),
				array('pageName'=>'Home Page', 'site'=>'CollegeDunia','URL'=>'https://collegedunia.com/','team_name'=>'Domestic','team_name'=>'Domestic'),
				array('pageName'=>'MBA CTP', 'site'=>'CollegeDunia','URL'=>'https://collegedunia.com/management/bangalore-colleges','team_name'=>'Domestic','team_name'=>'Domestic'),
				array('pageName'=>'CLP', 'site'=>'CollegeDunia','URL'=>'https://collegedunia.com/university/25494-indian-institute-of-management-iim-ahmedabad/courses-fees?course_id=1861','team_name'=>'Domestic'),
				array('pageName'=>'CAT EP', 'site'=>'CollegeDunia','URL'=>'https://collegedunia.com/exams/cat','team_name'=>'Domestic'),
				array('pageName'=>'UILP', 'site'=>'CollegeDunia','URL'=>'https://collegedunia.com/university/25494-indian-institute-of-management-iim-ahmedabad','team_name'=>'Domestic'),				
				array('pageName'=>'Home Page', 'site'=>'Careers360','URL'=>'https://www.careers360.com/','team_name'=>'Domestic'),
				array('pageName'=>'MBA CTP', 'site'=>'Careers360','URL'=>'https://bschool.careers360.com/colleges/list-of-mba-colleges-in-bengaluru','team_name'=>'Domestic'),
				array('pageName'=>'CLP', 'site'=>'Careers360','URL'=>'https://www.careers360.com/university/indian-institute-of-management-ahmedabad/pgpm-course','team_name'=>'Domestic'),
				array('pageName'=>'CAT EP', 'site'=>'Careers360','URL'=>'https://bschool.careers360.com/exams/cat','team_name'=>'Domestic'),
				array('pageName'=>'UILP', 'site'=>'Careers360','URL'=>'https://www.careers360.com/university/indian-institute-of-management-ahmedabad','team_name'=>'Domestic')
			);

		foreach ($config['URLS'] as $page){
			$this->getData($page, 'desktop');
		}
		foreach ($config['URLS'] as $page){
			$this->getData($page, 'mobile');
		}
		foreach ($config['URLS'] as $page){
			$this->getData($page, 'desktop');
		}
		foreach ($config['URLS'] as $page){
			$this->getData($page, 'mobile');
		}
    
	}

	public function getData($page, $device){
		//For each URL, make a curl call
		$info = $this->makeCURL($page['URL'], $device);
		
		//After CURL call, extract the data
		$totalTime = $info['total_time'];
		$nameLookUp = $info['namelookup_time'];
		$connectTime = $info['connect_time'];
		$startTransfer = $info['starttransfer_time'];
		
		//Store this data in DB
		$this->model->storeData($page['pageName'], $page['site'], $page['URL'], $device, $totalTime, $nameLookUp, $connectTime, $startTransfer);

	}
	
	public function makeCURL($url, $device = 'mobile'){
		if($device == "mobile"){
			$userAgent = "Mozilla/5.0 (iPhone; CPU iPhone OS 12_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/69.0.3497.105 Mobile/15E148 Safari/605.1";
		}
		else{
			$userAgent = "Mozilla/5.0 (X11; CrOS x86_64 8172.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.64 Safari/537.36";
		}
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, [
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url,
		    CURLOPT_USERAGENT => $userAgent
		]);
		
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		$info = curl_getinfo($curl);
		
		// Close request to clear up some resources
		curl_close($curl);
		
		return $info;
	}	
}