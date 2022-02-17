<?php

class HomePageCMS extends MX_Controller {
	private $tabId = 1023;
	
	public function __construct(){
		$this->load->helper("shikshautility");
		$this->homepagecmsmodel = $this->load->model("home/homepagecmsmodel");
	}
	
	public function index($type = 'banner', $id) {
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup'] != 'cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userId 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		if($userId == '1563'){
			$type = 'testimonials';
		}
		$cmsPageArr = array();
		$cmsPageArr['userId'] 		= 	$userId;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId;
		$cmsPageArr['pageType']     =   $type;
		$cmsPageArr['action']       =   'add';
		if($type == 'testimonials'){
			$cmsPageArr['tableData'] 	= $this->homepagecmsmodel->getTestimonialData('table');	
		}
		else if($type == 'article' && $userId != '1563'){
			$cmsPageArr['tableData'] = $this->homepagecmsmodel->getFeaturedArticleBannerData($type);
		}
		else {
			if($userId != '1563'){
				$cmsPageArr['tableData'] 	= $this->homepagecmsmodel->getTableData($type);
			}
		}
		//on form edit
		if(!empty($id)) {
			$cmsPageArr['action']   =   'edit';
			$cmsPageArr['id']   	=   $id;
			if(array_key_exists($id, $cmsPageArr['tableData'])) {
				$cmsPageArr['slotData'] = $cmsPageArr['tableData'][$id];
			}
		}
		if($type == 'testimonials'){
			$this->load->view('cms/testimonials', $cmsPageArr);
		}
		else if($type == 'article' && $userId != '1563'){
			$this->load->view('cms/articleBannerMain', $cmsPageArr);
		}
		else {
			if($userId != '1563'){	
				$this->load->view('cms/bannerMain', $cmsPageArr);
		}
}
	}

	
	public function saveBannerFeaturedTextForm() {
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
        $data['clientId'] 				= $this->input->post('clientId');
        $data['title'] 					= trim($this->input->post('title'));
        $data['location'] 				= trim($this->input->post('location'));
        $data['url'] 					= $this->input->post('url');
        $data['fromDate']      			= $this->input->post('from_date');
        $data['toDate']        			= $this->input->post('to_date');
        $data['pageType'] 				= $this->input->post('pageType');
        $data['action']      			= $this->input->post('action');
        $data['userId']        			= $this->input->post('userId');
        $data['isDefault']     			= $this->input->post('default') == 'on' ? 1 : 0;
        $data['bannerId']       		= $this->input->post('bannerId');
        $data['bannerRemoved']  		= $this->input->post('bannerRemoved');
        $data['originalBannerImage']  	= $this->input->post('originalBannerImage');

        //Image upload and backend checks
        if($data['pageType'] == 'banner') {
        	if(($data['bannerRemoved'] == '0' && $data['action'] == 'add') || ($data['bannerRemoved'] == '1' && $data['action'] == 'edit')) {
        		$bannerImage = $_FILES['bannerImage']['name'];
        		if(empty($bannerImage[0])) { //check for empty image
        			$error['image'] = "Banner image cannot be empty";
					$exitFlag = true;
        		} else {
        			$banner = $uploadClient->uploadFile($appId,'image',$_FILES,$_FILES['bannerImage']['name'],"-1","homepageCoverBanner",'bannerImage');
			        if($banner['status'] == 1) {
						for($k = 0;$k < $banner['max'] ; $k++) {
						    $arrContextOptions=array(
							     "ssl"=>array(
							         "verify_peer"=>false,
							         "verify_peer_name"=>false,
							     ),
							);
							$response = file_get_contents(addingDomainNameToUrl(array('url' => $banner[$k]['imageurl'] , 'domainName' =>MEDIA_SERVER)),false, stream_context_create($arrContextOptions));
							$serverImage = imagecreatefromstring($response);
							$width = imagesx($serverImage);
							$height = imagesy($serverImage);
							/*$tmpSize = getimagesize(addingDomainNameToUrl(array('url' => $banner[$k]['imageurl'] , 'domainName' =>MEDIA_SERVER)));
							list($width, $height, $type, $attr) = $tmpSize;*/
							
							//check for image dimensions
							/*if( !( (($width  == 1366) && ($height == 446)) || (($width  == 1920) && ($height == 528)) ) ) {
								$error['image'] = "Please upload an image with correct dimensions";
								$exitFlag = true;
							}*/
							$data['bannerImageUrl'] = $banner[$k]['imageurl'];
						}
					} else {
						$error['image'] = $banner;
						$exitFlag = true;
					}
        		}
        	} else {
        		$urlData = parse_url($data['originalBannerImage']);
				$data['bannerImageUrl'] = $urlData['path'];
        	}
		} else {
			$data['displayText'] = trim($this->input->post('displayText'));
			$data['usp'] = trim($this->input->post('usp'));

			if(empty($data['usp'])) {
				$error['usp'] = "USP cannot be empty";
				$exitFlag = true;
			}
		}
		$this->refreshHomepageBannerCache();
		//validate range
		if($data['pageType'] == 'banner') {
			$numberOfOverlapsAllowed = 3;
		} else {
			$numberOfOverlapsAllowed = 10;
		}
		$newStartDate = strtotime($data['fromDate']);
		$newEndDate = strtotime($data['toDate']);
		if($newStartDate > $newEndDate) {
			$error['range'] = 'START date cannot be greater than END date';
			$exitFlag = true;
		} else if(!$data['isDefault']) {
			$existingRange = $this->homepagecmsmodel->getAllRange($data['pageType'], $data['action'], $data['bannerId']);
			foreach ($existingRange as $key => $date) {
				$startDate = strtotime($date['start_date']);
				$endDate = strtotime($date['end_date']);
				if( (($startDate <= $newStartDate && $endDate >= $newStartDate ) || 
					($startDate <= $newStartDate && $endDate >= $newEndDate) || 
					($startDate <= $newEndDate && $endDate >= $newEndDate) || 
					($startDate >= $newStartDate && $endDate <= $newEndDate)) ) {
					$numberOfOverlaps++;
					if($numberOfOverlapsAllowed <= $numberOfOverlaps) {
						$error['range'] = "This schedule overlaps with ".$numberOfOverlapsAllowed." other EXISTING schedules!";
						$exitFlag = true;
					}
				}
			}
		}

        if($exitFlag) {
			echo json_encode(array('error'=>$error));
			exit;
		}
		
        $this->homepagecmsmodel->postBannerForm($data);
        echo json_encode(array('success'=>1));
        exit();
	}

	function removeBanner() {
		$data['bannerId'] = $this->input->post('bannerId');
		$data['type'] = $this->input->post('type');
		$data['action'] = 'remove';
		if($data['type'] == 'featuredCollege'){
			$this->homepagecmsmodel->postBannerFormForFeaturedCollege($data);
		}else {
		$this->homepagecmsmodel->postBannerForm($data);
		}
		$this->refreshHomepageBannerCache();
		echo json_encode(array('success'=>1));
		exit();
	}

	function removeFeaturedArticleBanner(){
		$data['id'] = $this->input->post('id');
		$data['type'] = $this->input->post('type');
		$data['action'] = 'remove';
		$this->homepagecmsmodel->postBannerFormForFeaturedArticle($data);
		echo json_encode(array('success'=>1));
		exit();	
	}
	function refreshHomepageBannerCache() {

		// insert into html cache purging queue
        	$arr = array("cache_type" => "htmlpage", "entity_type" => "homepage", "entity_id" => 0, "cache_key_identifier" => "");
        	$shikshamodel = $this->load->model("common/shikshamodel");
        	$shikshamodel->insertCachePurgingQueue($arr);

		$this->load->library('homepage/Homepageslider_client');
		$HomepagesliderClient = Homepageslider_client::getInstance();
		$HomepagesliderClient->deleteHomepageCoverBannerCache();
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
        $data['action']      			= $this->input->post('action');
        $data['isDefault']     			= $this->input->post('default') == 'on' ? 1 : 0;
        $data['bannerId']       		= $this->input->post('bannerId') ? $this->input->post('bannerId') : 0 ;
        $data['bannerRemoved']  		= $this->input->post('bannerRemoved');
        $data['originalBannerImage']  	= $this->input->post('originalBannerImage');
        $data['creationDate']  			= $this->input->post('creationDate');

        //Image upload and backend checks
        $imgArr = array();
        $imgArr = uploadBannerImageCMS($data, '200', '110', 'homepageFeaturedCollegeBanner', $uploadClient );
        $error['image'] = $imgArr[0];
    	$exitFlag = $imgArr[1];
    	$data['bannerImageUrl'] = $imgArr[2];
		
	//	$this->refreshHomepageBannerCache();
		//validate range
		$numberOfOverlapsAllowedForFeature = 16;
		
		$newStartDate = strtotime($data['fromDate']);
		$newEndDate = strtotime($data['toDate']);
		if($newStartDate > $newEndDate) {
			$error['range'] = 'START date cannot be greater than END date';
			$exitFlag = true;
		} else if(!$data['isDefault']) {
			$existingRange = $this->homepagecmsmodel->getAllRangeForFeaturedBannerHome($data['pageType'], $data['action'], $data['bannerId']);
			
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


			/*	if( (($startDate <= $newStartDate && $endDate >= $newStartDate ) || 
					($startDate <= $newStartDate && $endDate >= $newEndDate) || 
					($startDate <= $newEndDate && $endDate >= $newEndDate) || 
					($startDate >= $newStartDate && $endDate <= $newEndDate)) ) {
					$numberOfOverlaps++;
					if($numberOfOverlapsAllowedForFeature <= $numberOfOverlaps) {
						$error['range'] = "This schedule overlaps with ".$numberOfOverlapsAllowedForFeature." other EXISTING schedules!";

						$exitFlag = true;
					}
				} */
			}
		}

        if($exitFlag) {
			echo json_encode(array('error'=>$error));
			exit;
		}

        $transStatus = $this->homepagecmsmodel->postBannerFormForFeaturedCollege($data);
        echo json_encode(array('success'=>$transStatus));exit();
	}

	public function saveArticleForHomePage() {
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
		$data['id']						= $this->input->post('idForArticle');
       	$data['position']       		= $this->input->post('position') != '' ? $this->input->post('position'): 0 ;
        $data['articleId'] 				= $this->input->post('articleId') != '' ? $this->input->post('articleId'): 0 ;
        $data['fromDate']      			= $this->input->post('from_date');
        $data['toDate']        			= $this->input->post('to_date');
        $data['pageType'] 				= $this->input->post('pageType');
        $data['action']      			= $this->input->post('action');
        $data['bannerRemoved']  		= $this->input->post('bannerRemoved');
        $data['originalBannerImage']  	= $this->input->post('originalBannerImage');
        $data['creationDate']  			= $this->input->post('creationDate');

        
		//validate if article is live and if it has to be shown on HomePage
		$validateArticle = $this->homepagecmsmodel->getArticleStatus($data['articleId']); 
		if(empty($validateArticle)){
			$error['range'] = 'The article mentioned is not live';
			$exitFlag = true;
		}
		else {
			if($validateArticle[0]['homepageImgURL'] == ''){
			  $error['range'] = 'The article mentioned is not enabled for HomePage';
			  $exitFlag = true;
			}
		}
		//validate range
		$numberOfOverlapsAllowedForFeature = 1;
		
		$newStartDate = strtotime($data['fromDate']);
		$newEndDate = strtotime($data['toDate']);
		if($newStartDate > $newEndDate) {
			$error['range'] = 'START date cannot be greater than END date';
			$exitFlag = true;
		} 
			$existingRange = $this->homepagecmsmodel->getAllRangeForFeaturedArticleHome($data['pageType'], $data['action'], $data['articleId'], $data['position'], $data['id']);
			for ( $i = $newStartDate; $i <= $newEndDate; $i = $i + 86400 ) {
					$numberOfOverlaps = 0;
					$currentDate = date( 'Y-m-d', $i ); // 2010-05-01, 2010-05-02, etc
					foreach ($existingRange as $key => $date) {
					$startDate = $date['start_date'];
					$endDate = $date['end_date'];
					if($currentDate == $startDate || $currentDate == $endDate || ($currentDate > $startDate && $currentDate  < $endDate)){
					$error['range'] = "This schedule overlaps with other existing schedule";
						$exitFlag = true;	
						break;					
					}
				}

			/*	if($numberOfOverlaps >= 1) {
						$error['range'] = "This schedule overlaps with other existing schedule on ".$currentDate;
							$exitFlag = true;
						} */
			}
		

        if($exitFlag) {
			echo json_encode(array('error'=>$error));
			exit;
		}

        $transStatus = $this->homepagecmsmodel->postBannerFormForFeaturedArticle($data);
        echo json_encode(array('success'=>$transStatus));exit();
	}

	

	function saveTestimonialsForHomePage(){
		$cmsUserInfo     = $this->cmsUserValidation();
		if($cmsUserInfo['usergroup']!='cms'){
           	 return;
	        }
        $this->load->library('upload_client');
        $uploadClient = new Upload_client();
        $data                   		= array();
        $data['name'] 					= trim($this->input->post('Name'));
        $data['status'] 				= $this->input->post('status') ? trim($this->input->post('status')) : 'draft';
		$data['designation'] 			= trim($this->input->post('Designation'));
		$data['testimonial'] 			= trim($this->input->post('Testimonial'));
        $data['url'] 					= $this->input->post('url') != '' ? $this->input->post('url') : 0 ;
        $data['pageType'] 				= $this->input->post('pageType');
        $data['action']      			= $this->input->post('action');
        $data['bannerImage']  			= $this->input->post('bannerImage');
         $data['testimonialId']       	= $this->input->post('testimonialId') ? $this->input->post('testimonialId') : 0 ;
        $data['bannerRemoved']  		= $this->input->post('bannerRemoved');
        $data['originalBannerImage']  	= $this->input->post('originalBannerImage');
        $data['creationDate']  			= $this->input->post('creationDate');
        if(($data['bannerRemoved'] == '0' && $data['action'] == 'add') || ($data['bannerRemoved'] == '1' && $data['action'] == 'edit')) {
	        $imgArr = array();
	        $imgArr = uploadBannerImageCMS($data, '70', '70', 'homepageFeaturedCollegeBanner', $uploadClient );
	        $error['image'] = $imgArr[0];
	    	$exitFlag = $imgArr[1];
	    	$data['bannerImageUrl'] = $imgArr[2];
	    	if($exitFlag) {
				echo json_encode(array('error'=>$error));
				exit;
			}
		}else{
			$data['bannerImageUrl'] = $data['originalBannerImage'];
		}
		$transStatus = $this->homepagecmsmodel->postFormForTestimonials($data);
		if($data['status'] == 'live'){
			$this->resetTestimonialCache();
		}
        echo json_encode(array('success'=>$transStatus));exit();
	}

	function removeElement(){
		$id = $this->input->post('id');
		$data['type'] = $this->input->post('type');
		$data['status'] = $this->input->post('status');
		$data['action'] = 'remove';
		if($data['type'] == 'testimonials'){
			$data['testimonialId'] = $id;
			$this->homepagecmsmodel->postFormForTestimonials($data);
			if($data['status'] == 'live'){
				$this->resetTestimonialCache();
			}
		}
		echo json_encode(array('success'=>1));
		exit();
	}

	function makeTestimonialLive(){
		$data['testimonialId'] = $this->input->post('testimonialId');
		$data['type'] = $this->input->post('type');
		$data['status'] = $this->input->post('status');
		$data['action'] = 'makeLive';
		if($data['status'] != 'live'){
			$numberOfLiveTestimonials = $this->homepagecmsmodel->getTestimonialData($data['action']);
			if($numberOfLiveTestimonials >= 2){
				$error = "You have already added 2 testimonials. Please remove one of them to add this";
				echo json_encode(array('error'=>$error));
				exit;
			}
		}
		$this->homepagecmsmodel->postFormForTestimonials($data);
		$this->resetTestimonialCache();
		echo json_encode(array('success'=>1));
		exit();

	}

	public function resetTestimonialCache(){
		$this->load->library('cacheLib');
		$cacheLib = new cacheLib();
		$key = 'testimonialsHomePage';
		if($cacheLib->get($key)!='ERROR_READING_CACHE'){
			$cacheLib->clearCacheForKey('testimonialsHomePage');
		}
		return;
	}
}
