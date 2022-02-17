<?php 

class HomePageMarketingCMS extends MX_Controller {
	private $tabId = 1023;
	
	public function __construct(){
		$this->load->helper("shikshautility");
		$this->homepagecmsmodel = $this->load->model("home/homepagecmsmodel");
	}

public function marketingContentIndex($type = 'marketingContent', $id){
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup'] != 'cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userId 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userId'] 		= 	$userId;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId;
		$data['id']					= $this->input->post('idForMrktng');
		$cmsPageArr['pageType']     =   $type;
		$cmsPageArr['action']       =   'add';
		$cmsPageArr['tableData'] 	= 	$this->homepagecmsmodel->getMarketingFoldData();
		//on form edit
		if(!empty($id)) {
			$cmsPageArr['action']   =   'edit';
			$cmsPageArr['id']   	=   $id;
			if(array_key_exists($id, $cmsPageArr['tableData'])) {
				$cmsPageArr['slotData'] = $cmsPageArr['tableData'][$id];
			}
		}
		
		
		$this->load->view('cms/marketingContentMain', $cmsPageArr);	
	}

	public function getFormForMarketingFold() {
		
		$id = $this->input->post('id');
		if($id != ''){
			$cmsPageArr['tableData'] 	=  $this->homepagecmsmodel->getMarketingFoldData('edit', $id);
			//on form edit
			$cmsPageArr['id']   	=   $id;
			$cmsPageArr['status'] 	= $this->input->post('status');
			$cmsPageArr['slotData'] = $cmsPageArr['tableData'][0];	
		} 
		$index = $this->input->post('index');
			if($index == 1){
				echo $this->load->view('cms/imgWithTxtAddForm', $cmsPageArr);
			}
			else if($index == 2){
				echo $this->load->view('cms/imgOnlyAddForm', $cmsPageArr);
			}
			else {

				echo $this->load->view('cms/videoOnlyAddForm', $cmsPageArr);
			}
	}
 
 public function saveMarketingFoldForHomePage() {
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
		$data['id']						= $this->input->post('idForMrktng');
        $data['status'] 				= $this->input->post('status') ? trim($this->input->post('status')) : 'draft';
        $data['header'] 				= $this->input->post('Header') != '' ? $this->input->post('Header'): '' ;
        $data['subheader']      		= $this->input->post('Subheader') != '' ? $this->input->post('Subheader'): '' ;
        $data['description']            = $this->input->post('Description') != '' ? $this->input->post('Description') : '';
        $data['pageType'] 				= $this->input->post('pageType');
        $data['type']                   = $this->input->post('selectedContent');
        $data['readMore']    			= $this->input->post('readMore') == 'on' ? 1 : 0;
        $data['targetUrl']				= $this->input->post('TargetUrl') != '' ? $this->input->post('TargetUrl') : '';
        $data['bannerImageUrl']			= $this->input->post('bannerImage') != '' ? $this->input->post('bannerImage') : '';
        $data['action']      			= $this->input->post('action');
        $data['creationDate']  			= $this->input->post('creationDate') != '' ? $this->input->post('creationDate') : '';
        $data['bannerRemoved']  		= $this->input->post('bannerRemoved');
        $data['marketingBannerId']      = $this->input->post('marketingBannerId') ? $this->input->post('marketingBannerId') : 0 ; 
         //HTTPS check in url
        if (!preg_match("~^(?:f|ht)tps?://~i", $data['targetUrl']) && $data['targetUrl'] != '' && $data['targetUrl'] != '0') {
				$data['targetUrl'] = "https://" . $data['targetUrl'];
			} else if (strpos($data['targetUrl'], 'http:') !== false){
				   $data['targetUrl'] = str_replace("http:","https:",$data['targetUrl']);
			}


         //Image upload and backend checks
         if(($data['bannerRemoved'] == '0' && $data['action'] == 'add') || ($data['bannerRemoved'] == '1' && $data['action'] == 'edit')) {
	        if($data['type'] == '1'){
		        $imgArr = array();
		        $imgArr = uploadBannerImageCMS($data, '250', '288', 'marketingImgWithTxt', $uploadClient );
		        $error['image'] = $imgArr[0];
		    	$exitFlag = $imgArr[1];
		    	$data['bannerImageUrl'] = $imgArr[2];
	    	}
	    	else if($data['type'] == '2'){
	    		$imgArr = array();
		        $imgArr = uploadBannerImageCMS($data, '512', '288', 'marketingOnlyImg', $uploadClient );
		        $error['image'] = $imgArr[0];
		    	$exitFlag = $imgArr[1];
		    	$data['bannerImageUrl'] = $imgArr[2];
	    	}
        }
        if($exitFlag) {
			echo json_encode(array('error'=>$error));
			exit;
		}
        $transStatus = $this->homepagecmsmodel->postMarketingFoldData($data);
       	if($data['status'] == 'live'){
			$this->resetMarketingCache();
		}
        echo json_encode(array('success'=>$transStatus));exit();
	}

	function removeMarketingBanner(){
		$data['id'] = $this->input->post('id');
		$data['type'] = $this->input->post('type');
		$data['action'] = 'remove';
		$this->homepagecmsmodel->postMarketingFoldData($data);
		if($data['status'] == 'live'){
				$this->resetMarketingCache();
			}
		echo json_encode(array('success'=>1));
		exit();
	}

	function makeMarketingBannerLive(){
		$data['id'] = $this->input->post('testimonialId');
		$data['status'] = $this->input->post('status');
		$data['action'] = 'makeLive';
		if($data['status'] != 'live'){
			$liveBanners = $this->homepagecmsmodel->getMarketingFoldData($data['action']);
			if(!empty($liveBanners)){
				$error = "A Marketing Banner is already live. Please remove that to add this banner.";
				echo json_encode(array('error'=>$error));
				exit;
			}
		}
		$this->homepagecmsmodel->postMarketingFoldData($data);
		$this->resetMarketingCache();
		echo json_encode(array('success'=>1));
		exit();

	}

	public function resetMarketingCache(){
		$this->load->library('cacheLib');
		$cacheLib = new cacheLib();
		$key = 'marketingFoldHomePage';
		if($cacheLib->get($key)!='ERROR_READING_CACHE'){
			$cacheLib->clearCacheForKey('marketingFoldHomePage');
		}
		return;
	}
}
