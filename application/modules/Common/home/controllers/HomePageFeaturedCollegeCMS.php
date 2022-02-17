<?php 

class HomePageFeaturedCollegeCMS extends MX_Controller {
	private $tabId = 1023;
	
	public function __construct(){
		$this->load->helper("shikshautility");
		$this->homepagecmsmodel = $this->load->model("home/homepagecmsmodel");
	}

public function featuredCollegeIndex($type = 'featuredCollege', $id) {
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup'] != 'cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userId 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		$marketingArr = array(1563);
		if(in_array($userId, $marketingArr)){
			$type = 'testimonials';
		}
		$cmsPageArr = array();
		$cmsPageArr['userId'] 		= 	$userId;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId;
		$cmsPageArr['pageType']     =   $type;
		$cmsPageArr['action']       =   'add';
		if(!in_array($userId, $marketingArr)){
		$cmsPageArr['tableData'] 	= $this->homepagecmsmodel->getFeaturedCollegeBannerData($type);
		}
		//on form edit
		
		if(!in_array($userId, $marketingArr)){
			$this->load->view('cms/featuredCollegeBannerMain', $cmsPageArr);
		}
	}


public function saveFeaturedBannerForHomePage() {
		// validate user
        $cmsUserInfo     = $this->cmsUserValidation();
        $coursePageCache = $this->load->library('coursepages/cache/CoursePagesCache');
        
        if($cmsUserInfo['usergroup']!='cms'){
            return;
        }
        $this->load->library('upload_client');
        $uploadClient = new Upload_client();

        

		$error							= array();
		$exitFlag						= false;
		$data                   		= array();
        $data['clientId'] 				= $this->input->post('clientId') != '' ? $this->input->post('clientId'): 0 ;
        $data['title'] 					= trim($this->input->post('title')) != '' ? trim($this->input->post('title')) : 0;
        $data['url'] 					= $this->input->post('url') != '' ? $this->input->post('url') : 0 ;
        $data['fromDate']      			= $this->input->post('from_date');
        $data['toDate']        			= $this->input->post('to_date');
        $data['pageType'] 				= $this->input->post('pageType');
        $data['type']                   = $this->input->post('selectCntnt');
        $data['action']      			= $this->input->post('action');
        $data['isDefault']     			= $this->input->post('default') == 'on' ? 1 : 0;
        $data['bannerId']       		= $this->input->post('bannerId') != '' ? $this->input->post('bannerId') : 0 ;
        $data['bannerRemoved']  		= $this->input->post('bannerRemoved');
        $data['originalBannerImage']  	= $this->input->post('originalBannerImage');
        $data['creationDate']  			= $this->input->post('creationDate');

        if (!preg_match("~^(?:f|ht)tps?://~i", $data['url']) && $data['url'] != '' && $data['url'] != '0') {
				$data['url'] = "https://" . $data['url'];
			} else if (strpos($data['url'], 'http:') !== false && strpos($data['url'], 'shiksha.com') !== false){
				   $data['url'] = str_replace("http:","https:",$data['url']);
		}

        //Image upload and backend checks
        $imgArr = array();
        if($data['type'] == 1){
	        $imgArr = uploadBannerImageCMS($data, '200', '110', 'homepageFeaturedCollegeBanner', $uploadClient );
        }else {
	        $imgArr = uploadBannerImageCMS($data, '220', '120', 'homepageFeaturedCollegeBanner', $uploadClient );
		}
        $error['image'] = $imgArr[0];
    	$exitFlag = $imgArr[1];
    	$data['bannerImageUrl'] = $imgArr[2];
				//validate range
		if($data['type'] == '1'){
			$numberOfOverlapsAllowedForFeature = 16;
		}
		else {
			$numberOfOverlapsAllowedForFeature = 8;
		}
		$newStartDate = strtotime($data['fromDate']);
		$newEndDate = strtotime($data['toDate']);
		if($newStartDate > $newEndDate) {
			$error['range'] = 'START date cannot be greater than END date';
			$exitFlag = true;
		} else if(!$data['isDefault']) {
			$existingRange = $this->homepagecmsmodel->getAllRangeForFeaturedBannerHome($data['pageType'], $data['action'], $data['bannerId'], $data['type']);
			
			for ( $i = $newStartDate; $i <= $newEndDate; $i = $i + 86400 ) {
					$numberOfOverlaps = 0;
					$currentDate = date( 'Y-m-d', $i ); // 2010-05-01, 2010-05-02, etc
					foreach ($existingRange as $key => $date) {
					$startDate = $date['start_date'];
					$endDate = $date['end_date'];
					if($currentDate == $startDate || $currentDate == $endDate || ($currentDate > $startDate && $currentDate  < $endDate)){
							$numberOfOverlaps++;
						
					}
				}

				if($numberOfOverlapsAllowedForFeature <= $numberOfOverlaps) {
						$error['range'] = "This schedule overlaps with ".$numberOfOverlapsAllowedForFeature." other EXISTING schedules!";
							$exitFlag = true;
						}
			}
		}

        if($exitFlag) {
			echo json_encode(array('error'=>$error));
			exit;
		}

        $transStatus = $this->homepagecmsmodel->postBannerFormForFeaturedCollege($data);
        echo json_encode(array('success'=>$transStatus));exit();
	}

	public function getFormForFeaturedCollege(){

		$cmsPageArr['id'] = $this->input->post('bannerId') != '' ? $this->input->post('bannerId') : '';
		$cmsPageArr['index']  = $this->input->post('index') != '' ? $this->input->post('index') : 1;
		if($cmsPageArr['id'] != ''){
			$cmsPageArr['tableData'] 	=  $this->homepagecmsmodel->getFeaturedCollegeBannerData('edit', $cmsPageArr['id']);
			//on form edit
			$cmsPageArr['status'] 	= $this->input->post('status');
			$cmsPageArr['slotData'] = $cmsPageArr['tableData'][0];	
		} 
		echo $this->load->view('cms/addFormView', $cmsPageArr);

}

}