<?php
/**
 * HomepageSlideshowWidget Application Controller
 *
 * This calss renders carousel on homepage
 *
 * @package Enterprise
 */
//error_reporting(E_ALL);
class HomepageSlideshowWidget extends MX_Controller {

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
		$this->load->library('homepage/Homepageslider_client');
	}

	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function index($edit=0,$carousel_id=0) {
		// call init method to set basic objects
		$this->_init();
		$data['headerContentaarray'] = $this->loadHeaderContent();
		$post_array = $_POST;
		if(is_array($post_array) && !empty($post_array['carousel_title'])) {
			$carousel_title = addslashes($this->input->post('carousel_title',true));
			$carousel__destination_url = addslashes($this->input->post('carousel__destination_url',true));
			$carousel_description = addslashes($this->input->post('carousel_description',true));
			$carousel_open_new_window = $this->input->post('carousel_open_new_window',true);
			if(empty($carousel_open_new_window)) {
				$carousel_open_new_window = 'NO';
			}
			$carousel_photo_data = $this->uploadImage($_FILES);
			//var_dump($carousel_photo_data);
			if(is_array($carousel_photo_data) && !empty($carousel_photo_data['data'])) {
				$carousel_photo_url = $carousel_photo_data['data'];
			} else {
				$carousel_photo_url = '';
			}
			if($edit ==1) {
				$array_to_update = array('carousel_title'=>$carousel_title,
										'carousel__destination_url'=>$carousel__destination_url,
				                        'carousel_description'=>$carousel_description,
										'carousel_open_new_window'=>$carousel_open_new_window,
				                        'carousel_photo_url'=>$carousel_photo_url,
				                        'carousel_id'=>$carousel_id
				);
				$result = $this->homepageslider_client->updateCarouselDeatils($array_to_update);
			} else {
				$result = $this->homepageslider_client->addContentToCarouselWidget($carousel_title,$carousel__destination_url,$carousel_photo_url,
				$carousel_description,$carousel_open_new_window);
			}
		}
		$data['carousel_array'] = json_decode($this->homepageslider_client->renderCarouselDeatils(),true);
		$data['edit'] = $edit;
		if($edit == 1 && $carousel_id>0) {
			foreach ($data['carousel_array'] as $value) {
				if($value['carousel_id'] == $carousel_id) {
					$data['carousel_title'] = $value['carousel_title'];
					$data['carousel__destination_url'] = $value['carousel__destination_url'];
					$data['carousel_description'] = $value['carousel_description'];
					$data['carousel_open_new_window'] = $value['carousel_open_new_window'];
					$data['carousel_photo_url'] = $value['carousel_photo_url'];
					$data['carousle_id'] = $carousel_id;
				}
			}
		}
		$this->load->view('homepage/homepageslidewidget',$data);
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
        'prodId'=>201
		);
		$headerTabs = $this->enterprise_client->getHeaderTabs(1,$this->_validateuser[0]['usergroup'],$this->_validateuser[0]['userid']);
		$headerTabs['0']['prodId'] = 201;
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
				$data['data'] =  $upload[0]['imageurl'];
				$data['success_message'] = "Image has been successfully uploaded, please click on above save button to save the image";
			}

		}
		return $data;

	}
	/**
	 * Upload and save banner image
	 *
	 * @access	private
	 * @return	array
	 */
	public function deleteCarousel($carousel_id,$carousel_order) {
		$this->_init();
		$rows = $this->homepageslider_client->deleteCarousel($carousel_id,$carousel_order);
		$this->index();
	}
	/**
	 * Upload and save banner image
	 *
	 * @access	private
	 * @return	array
	 */
	public function reorderCarousel($org_order,$orgnl_id,$new_order,$new_id) {
		$this->_init();
		$array = array($org_order,$orgnl_id,$new_order,$new_id);
		if(is_array($array) && count($array)>0) {
			$rows = $this->homepageslider_client->reorderCarousel($array);
		}
		$this->index();
	}
	public function getDataForHomepageCafeWidget() {
		$this->load->library('homepage/Homepageslider_client');
		$rows = $this->homepageslider_client->getDataForHomepageCafeWidget();
		$rows = json_decode($rows,true);
	}
        public function deleteHomepageCacheHTMLFile() {
        	$this->load->library('homepage/Homepageslider_client');
                $this->homepageslider_client->deleteHomepageCacheHTMLFile(true);
	}

}
