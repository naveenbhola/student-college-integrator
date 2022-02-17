<?php 
/**
 * HomepageSlideshowWidget Application Controller
 *
 * This calss renders carousel on homepage
 *
 * @package Enterprise
 */
//error_reporting(E_ALL);
class StudyAbroadPageWidget extends MX_Controller {

	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	private function _init() {
		//set user details
		$this->_validateuser = $this->checkUserValidation();
		if(($this->_validateuser == "false" )||($this->_validateuser == "")) {
			header('location:'.ENTERPRISE_HOME);exit();
		}
		if(is_array($this->_validateuser) && $this->_validateuser['0']['usergroup']!='cms') {
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		//load the required library
		$this->load->library('Enterprise_client');
		$this->load->model('enterprise/studyabroadwidgetmodel');
		$this->studyabroadwidgetmodel = new studyabroadwidgetmodel();
	}

	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function index($edit =0) {
		// call init method to set basic objects
		$this->_init();
		$data['headerContentaarray'] = $this->loadHeaderContent();
		$post_array = $_POST;
		$carousel_title = addslashes(strip_tags($this->input->post('carousel_title',true)));
		$carousel_description = addslashes(strip_tags($this->input->post('carousel_description',true)));
		if(is_array($post_array) && !empty($carousel_title) && !empty($carousel_description)) {
			$carousel_photo_data = $this->uploadImage($_FILES);
			if(is_array($carousel_photo_data) && !empty($carousel_photo_data['data'])) {
				$carousel_photo_url = $carousel_photo_data['data'];
			} else {
				$carousel_photo_url = '';
				$data['error_message'] = $carousel_photo_data['error_message'];
			}
			$data['carousel_title'] = $carousel_title;
			$data['carousel_description'] = $carousel_description;
			$data['carousel_photo_url'] = $carousel_photo_url;
					
			if($edit ==1 && !empty($carousel_photo_url)) {
				$date = date('y-m-d h:i:s', time());
				$array_to_update = array('registrationLayerTitle'=>$carousel_title,
				                        'registrationLayerMsg'=>$carousel_description,
				                        'registrationBannerURL'=>$carousel_photo_url,
				                        'carousle_id'=>$this->input->post('carousle_id',true),
				                        'status'=>'live',
				                        'addedOn'=>$date
										);
				$result = $this->studyabroadwidgetmodel->updateCarouselDeatils($array_to_update);
				$data['main_suc_message'] = 'Your settings have been updated';
			} else {
				if(!empty($carousel_photo_url)) {
					$result = $this->studyabroadwidgetmodel->addContentToCarouselWidget($carousel_title,$carousel_photo_url,
				$carousel_description);
				$data['main_suc_message'] = 'Your settings have been saved';
				}
			}
		}
		$data['carousel_array'] = json_decode($this->studyabroadwidgetmodel->renderCarouselDeatils(),true);
		$data['edit'] = $edit;
		if(count($data['carousel_array'])>0) {
			foreach ($data['carousel_array'] as $value) {
					$data['carousel_title'] = $value['registrationLayerTitle'];
					$data['carousel_description'] = $value['registrationLayerMsg'];
					$data['carousel_photo_url'] = $value['registrationBannerURL'];
					$data['carousle_id'] = $value['id'];
			}
		}
		$this->load->view('enterprise/studyabroadPageWidget',$data);
	}

	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function loadHeaderContent() {
		$this->_init();
		$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($this->_validateuser[0]['displayname'])?$this->_validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>'',
        'prodId'=>778
		);
		$headerTabs = $this->enterprise_client->getHeaderTabs(1,$this->_validateuser[0]['usergroup'],$this->_validateuser[0]['userid']);
		$headerTabs['0']['prodId'] = 778;
		$headerComponents['headerTabs'] = $headerTabs;
		$headerCMSHTML = $this->load->view('enterprise/headerCMS', $headerComponents,true);
		$headerTABSHTML = $this->load->view('enterprise/cmsTabs',$headerComponents,true);
		return array($headerCMSHTML,$headerTABSHTML);
	}
	/**
	 * Upload and save banner image
	 *
	 * @access	private
	 * @return	array
	 */
	public function uploadImage($files, $vcard = 0,$appId = 1){
		$this->_init();
		if($files['myImage']['tmp_name'][0] == '')
		$data['error_message'] = "Please select a photo to upload";
		else
		{
			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$upload = $uploadClient->uploadFile($appId,'image',$files,array(),$this->_validateuser['0']['userid'],"user", 'myImage');
			if(!is_array($upload)) {
				$data['error_message'] =  $upload;
			} else
			{
				list($width, $height, $type, $attr) = getimagesize($upload[0]['imageurl']);
				if(!($width == '207' && $height == '151')) {
					$data['error_message'] = "Please upload an image of size 207 * 151";
					return $data;
				}
				$data['data'] =  $upload[0]['imageurl'];
				$data['success_message'] = "Image has been successfully uploaded, please click on above save button to save the image";
			}

		}
		return $data;

	}
}
