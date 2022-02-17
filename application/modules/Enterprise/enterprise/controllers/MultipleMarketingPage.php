<?php
/**
 * MultipleMarketingPage Application Controller
 *
 * This calss renders multiple marketing pages
 *
 * @package Enterprise
 */

class MultipleMarketingPage extends MX_Controller {

	private $MarketingPageClient;
	/**
	 * Loads required library and instantiate MultipleMarketingPageClient
	 *
	 * @access	private
	 * @return	void
	 */
	private function init() {
		$this->load->library(array('MultipleMarketingPageClient','Enterprise_client','LDB_Client','category_list_client'));
		$this->load->helper(array('url','form'));
		$this->MarketingPageClient = MultipleMarketingPageClient::getInstance();
                $this->abroadCommonLib  =  $this->load->library('listingPosting/AbroadCommonLib');

	}

    public function checkPageNameAjax($name)
    {
        $this->init();
        $this->getHeaderTabs();
        $response = json_decode($this->MarketingPageClient->checkPageName($name),true);
        echo $response['response'];
        exit();
    }
	/**
	 * It will add new marketing page
	 *
	 * @return	void
	 */
	public function addNewMarketingPage () {
		//load library and make backend call to add new marketing page
		$this->init();
		$this->getHeaderTabs();
		//$newId = json_decode($this->MarketingPageClient->addNewMarketingPage(),true);
		header('location:/enterprise/MultipleMarketingPage/renameMarketingPage/');
        //$newId = json_decode($this->MarketingPageClient->addNewMarketingPage(),true);
		exit();
	}
	/**
	 * renders main CMS Interface view and it will call marketingPageDetails() backend API to get the data
	 *
	 * @return	void
	 */
	public function marketingPageDetails ($arg1=0) {
		
		$this->init();
		
		$result = $this->MarketingPageClient->marketingPageDetails($arg1, 20);
        
		$marketing_list['marketing_list'] = array_slice($result,0,count($result)-2);
		$course_array                     = array_slice($result,-2,1);
		$marketing_list['course_count']   = json_decode($course_array['count_list'],true);
		$marketing_list['headerTabs']     =$this->getHeaderTabs();
		$marketing_list['prodId']         = 46;
		$marketing_list['validateuser']   = $this->userStatus;
		
		$this->load->library('pagination');
		$config['base_url']   = SHIKSHA_HOME.'/enterprise/MultipleMarketingPage/marketingPageDetails/';
		$config['total_rows'] = $result['totalRows'];
        unset($result);
        unset($course_array);
		$config['uri_segment'] = 4;
		$config['num_links']   = 6;
		$config['per_page']    = 20; 
        $this->pagination->initialize($config);
        // foreach($marketing_list as $k=>$v){ 
        //     if($k == 'marketing_list'){
        //         $marketing_list[$k] = array_slice($v,$arg1,20);
        //     }
        // }
        $formids = array();
        foreach($marketing_list['marketing_list'] as $list) {
        	$formids[] = $list['page_id'];
        }
        $cmp_model = $this->load->model('customizedmmp/customizemmp_model');
        
		$formCustomizationDetails                   = $cmp_model->getMultipleFormCustomizationData($formids, 'national');
		$marketing_list['formCustomizationDetails'] = $formCustomizationDetails;
        $this->load->view('enterprise/marketingpagedetails',$marketing_list);
	}

	/**
	 * It will render view pertaining to a particular marketing page
	 *
	 * @access	public
	 * @return	void
	 */
	public function marketingPageContents($pageID,$extraparam) {
		$this->init();
		$data['page_id'] = $extraparam;
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = 46;
		$data['details'] = $this->MarketingPageClient->getmarketingPageContents($extraparam);
		$marketing_list['validateuser'] = $this->userStatus;
		$this->load->view('enterprise/marketingPageContents',$data);

	}
	
	public function marketingPageMailer($pageID,$extraparam)
	{
		$this->init();
		$data['page_id'] = $extraparam;
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = 46;
		$data['details'] = $this->MarketingPageClient->getmarketingPageContents($extraparam);
		$marketing_list['validateuser'] = $this->userStatus;
		$this->load->view('enterprise/marketingPageMailer',$data);
	}
	
	
	public function saveMarketingPageMailer() {
	    $this->init();
	    $this->getHeaderTabs();
	    $page_id = $this->input->post('page_id');
		$subject = trim($this->input->post('subject'));
		$content = trim($this->input->post('content'));
		$downloadConfirmationMessage = trim($this->input->post('downloadConfirmationMessage'));
		$remove_attachment = trim($this->input->post('remove_attachment'));
	    $attachment_url = '';
		$attachment_name = '';
		
		$update_attachment = 'no';
		
	    if(!empty($_FILES['myImage']['name'][0])) {
	    	$attachment_data = $this->uploadDocument($_FILES);
			if($attachment_data['data']) {
				//$attachment_url = str_replace('http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'], '', $attachment_data['data']);
				$attachment_url = $attachment_data['data'];
				$attachment_name = trim($_FILES['myImage']['name'][0]);
			}
			$update_attachment = 'yes';
	    }
		else if($remove_attachment) {
			$update_attachment = 'yes';
		}
		else {
			$update_attachment = 'intact';
		}
		
		if($attachment_url || $update_attachment == 'yes' || $update_attachment == 'intact') {
			$this->MarketingPageClient->saveMarketingPageMailer($page_id,$subject,$content,$attachment_url,$attachment_name,$update_attachment,$downloadConfirmationMessage);
		}
		
	    header('location:/enterprise/MultipleMarketingPage/marketingPageDetails');
		exit();
	}
	
	/**
	 * It will save the changes done for selected marketing page
	 *
	 * @access	public
	 * @return	void
	 */
	public function savemarketingPageContents() {
		$this->init();
		$this->getHeaderTabs();
		$page_id = $this->input->post('page_id');
		$header_text = addslashes(trim($this->input->post('header_text')));
		$banner_zone_id = addslashes(trim($this->input->post('banner_zone_id')));
		$banner_text = addslashes(trim($this->input->post('banner_text')));
		$form_heading = addslashes(trim($this->input->post('form_heading')));
		$subheading = trim($this->input->post('subheading'));
		$background_url = $this->input->post('background_url');
		$background_image = '';
		$banner_url = '';
		$display_on_page = $this->input->post('display_on_page');
		$pixel_codes = $this->input->post('pixel_codes');
		$submitButtonText = $this->input->post('submitButtonText');

		//If the type of mmp is newmmp, the new interface needs to be shown
		if($display_on_page == 'newmmp '){


			if(!empty($_FILES['myHeaderImage']['name'][0])) {

				$header_image_data = $this->uploadHeaderImage($_FILES);

				if(!array_key_exists('data', $header_image_data)) {
					
					$header_image_data['page_id'] = $page_id;
					$header_image_data['header_text'] = $header_text;
					$header_image_data['banner_zone_id'] = $banner_zone_id;
					$header_image_data['banner_text'] = $banner_text;
					$header_image_data['form_heading'] = $form_heading;
					$header_image_data['subheading'] = $subheading;
					$header_image_data['display_on_page'] = $display_on_page;
				
					$this->load->view('enterprise/marketingPageContents',$header_image_data);
					return;
				} else {
					$header_image_url = str_replace('http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'], '', $header_image_data['data']);
				}
			} else {
				$header_image_url = $this->input->post('header_image');
			}

			if(!empty($_FILES['myImage']['name'][0])) {
				
				$background_data = $this->uploadBackgroundImage($_FILES);
				
				if($background_data['error_message']){
					
					$background_data['page_id'] = $page_id;
					$background_data['form_heading'] = $form_heading;
					$background_data['subheading'] = $subheading;
					$background_data['background_url'] = $background_url;
					$background_data['display_on_page'] = $display_on_page;
					$background_data['pixel_codes'] = $pixel_codes;
					$background_data['submitButtonText'] = $submitButtonText;
					$this->load->view('enterprise/marketingPageContents',$background_data);
					return;
				
				} else {
					if(!array_key_exists('data', $background_data)) {
					
						$background_data['page_id'] = $page_id;
						$background_data['form_heading'] = $form_heading;
						$background_data['subheading'] = $subheading;
						$background_data['background_url'] = $background_url;
						$background_data['display_on_page'] = $display_on_page;
						$background_data['pixel_codes'] = $pixel_codes;
						$background_data['submitButtonText'] = $submitButtonText;

						$this->load->view('enterprise/marketingPageContents',$background_data);
						return;
					} else {
						$background_image = str_replace('http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'], '', $background_data['data']);

					}
				}
			} else {
				$background_image = $this->input->post('background_image');
			}

		} else {

			
			if(!empty($_FILES['myImage']['name'][0])) {

				$banner_data = $this->uploadBannerImage($_FILES);
				if(!array_key_exists('data', $banner_data)) {
				
					$banner_data['page_id'] = $page_id;
					$banner_data['header_text'] = $header_text;
					$banner_data['banner_zone_id'] = $banner_zone_id;
					$banner_data['banner_text'] = $banner_text;
					$banner_data['form_heading'] = $form_heading;
					$background_data['subheading'] = $subheading;
					$banner_data['display_on_page'] = $display_on_page;
			
					$this->load->view('enterprise/marketingPageContents',$banner_data);
					return;
				} else {
					$banner_url = str_replace('http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'], '', $banner_data['data']);
				}
			}
	    }
	    
	    
	    $this->MarketingPageClient->savemarketingPageContents($page_id, $header_text, $banner_zone_id, $banner_text, $form_heading,$subheading,$banner_url,$background_url,$background_image,$pixel_codes, $submitButtonText,$header_image_url);
	    header('location:/enterprise/MultipleMarketingPage/marketingPageDetails');
		exit();
	}
	/**
	 * Loads the view to rename a marketing page
	 *
	 * @access	public
	 * @return	void
	 */
	public function renameMarketingPage($page_id) {
		$this->init();
	    $data['headerTabs'] = $this->getHeaderTabs();
	    $data['prodId'] = 46;
	    $data['page_id'] = $page_id;
	    $data['page_name'] = $this->input->post('page_name');
	    $data['destination_url'] = $this->input->post('destination_url');
	    $marketing_list['validateuser'] = $this->userStatus;
	    $this->load->view('enterprise/renamemarketingpage',$data);
	}
	/**
	 * save modified marketing page name
	 *
	 * @access	public
	 * @return	void
	 */
	public function savemarketingPageName(){
		$this->init();
		$this->getHeaderTabs();
        
		//$page_id = $this->input->post('page_id');
	    $page_name = addslashes($this->input->post('page_name'));
		$display_on_page = addslashes($this->input->post('display_on_page'));
        $page_id = json_decode($this->MarketingPageClient->addNewMarketingPage(),true);
	    $this->MarketingPageClient->savemarketingPageName($page_id['current_page_id'],$page_name, $display_on_page);
	    header('location:/enterprise/MultipleMarketingPage/marketingPageDetails');
		exit();
	}
	/**
	 * Loads the view to change destination url
	 *
	 * @access	public
	 * @return	void
	 */
	public function changeDestinationURL($page_id){
		$this->init();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = 46;
		$data['page_id'] = $page_id;
		$data['destination_url'] = $this->input->post('destination_url');
		$page_details= $this->MarketingPageClient->marketingPageDetailsById($page_id);
		$data['page_url'] = $page_details['page_url'];
		$marketing_list['validateuser'] = $this->userStatus;
		$this->load->view('enterprise/editdestinationurl',$data);
	}
	/**
	 * saves changed destination url
	 *
	 * @access	public
	 * @return	void
	 */
	public function saveDestinationURL(){
		$this->init();
		$this->getHeaderTabs();
		$page_id = $this->input->post('page_id');
		$destination_url = addslashes($this->input->post('destination_url'));
		$this->MarketingPageClient->saveDestinationURL($page_id,$destination_url);
		header('location:/enterprise/MultipleMarketingPage/marketingPageDetails');
		exit();
	}
	function ary_diff( $ary_1, $ary_2 ) {
	  // compare the value of 2 array
	  // get differences that in ary_1 but not in ary_2
	  // get difference that in ary_2 but not in ary_1
	  // return the unique difference between value of 2 array
	  $diff = array();
	  
	  // get differences that in ary_1 but not in ary_2
	  foreach ( $ary_1 as $v1 ) {
	    $flag = 0;
	    foreach ( $ary_2 as $v2 ) {
	      $flag |= ( $v1 == $v2 );
	      if ( $flag ) break;
	    }
	    if ( !$flag ) array_push( $diff, $v1 );
	  }
	  
	  // get difference that in ary_2 but not in ary_1
	  foreach ( $ary_2 as $v2 ) {
	    $flag = 0;
	    foreach ( $ary_1 as $v1 ) {
	      $flag |= ( $v1 == $v2 );
	      if ( $flag ) break;
	    }
	    if ( !$flag && !in_array( $v2, $diff ) ) array_push( $diff, $v2 );
	  }
	  
	  return $diff;
	}
	
	/**
	 * Loads the view to add/remove courses to a selected page
	 *
	 * @access	public
	 * @return	void
	 */
	public function addRemoveMMPageCourses($page_id,$pagetype,$error='') {
		$this->init();
        if($error == 'E')
            $data['errormsg'] = "No Courses Chosen";
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = 46;
		$data['page_id'] = $page_id;
		$data['validateuser'] = $this->userStatus;
		$page_details= $this->MarketingPageClient->marketingPageDetailsById($page_id);
		$data['page_url'] = $page_details['page_url'];
		$data['count_courses'] = $page_details['count_courses'];
		if(empty($pagetype))
		$pagetype = $page_details['page_type'];
		$data['original_page_type'] = $page_details['page_type'];
		$data['page_type'] = $pagetype;
		if($pagetype=='indianpage') {
			// $ldbObj = new LDB_Client();
		    // Load courses lists on basis of page id
			// $data['courses_list']= json_decode($this->MarketingPageClient->getCourseSpecializationForAllCategoryIdGroup($page_id),true);

		    // Load all courses of MBA
			// $data['mba_courses_all'] = json_decode($ldbObj->sgetCourseList($appId,3),true);
		    // Load all distance_mba courses (as we display specialization Id as courses)
			// $distance_course = json_decode($ldbObj->sgetSpecializationListByParentId($appId,24),true);

		    // Now merge both mba courses and DistanceMBA specialization list
			// $data['mba_courses_all'] = array_merge($data['mba_courses_all'],$distance_course);
		    // Get list of all mapped courses by mmp page id
			// $saved_courses_list = json_decode($this->MarketingPageClient->getCourselistForApage($page_id,'category'),true);

			// $data['saved_courses_list'] =$saved_courses_list['courses_list'];
			// $data['course_ids'] = str_replace(',',' ',$saved_courses_list['course_id']);
			// $data['management_courseids'] = trim(($saved_courses_list['management_courseids']));
			// $data['saved_management_courses'] =
			// $this->MarketingPageClient->getManagementCourses($data['management_courseids']);

			// $new_value = $this->ary_diff($data['mba_courses_all'],$data['saved_management_courses']);

			// $data['mba_courses'] = $new_value; 
			// $view_template = 'enterprise/addremovemmpagecourses';
			$this->load->helper('string');
            $data['regFormId'] = random_string('alnum', 6);
	
			$cmp_model = $this->load->model('customizedmmp/customizemmp_model');
			$mmpFormData = $cmp_model->getFormCustomizationData($page_id);
			$data['customization_fields'] = $mmpFormData['customization_fields'];		
			
			$view_template = 'enterprise/customizeDomesticMMP';
		} else if($pagetype=='abroadpage') {
			// load data for courses list
                        if(STUDY_ABROAD_NEW_REGISTRATION) {
				
				$data['abroad_main_ldb_courses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();  
                                //_P($data['abroad_main_ldb_courses']); 	
				$data['courses_list'] = json_decode($this->MarketingPageClient->getStudyAbroadCoursesListForApage(1,$page_id,$pagetype,'complete_list','newabroad'),true);
                        } else {
				$data['courses_list'] = json_decode($this->MarketingPageClient->getStudyAbroadCoursesListForApage(1,$page_id,$pagetype,'complete_list'),true);
                        } 
			
			$categories = array();
			foreach($data['courses_list']['courses_list'] as $category) {
                if($category[0]['categoryName'][0] != 'Retail')
    				$categories[$category[0]['categoryID'][0]] = $category[0]['categoryName'][0];
			}
			asort($categories);
			$data['courses_list']= $categories;
                        if(STUDY_ABROAD_NEW_REGISTRATION) {                                                                  
				$data['saved_courses_lists']= json_decode($this->MarketingPageClient->getStudyAbroadCoursesListForApage(1,$page_id,$pagetype,'saved_list','newabroad'),true);
				//_P($data['saved_courses_lists']);
                        } else {
				$data['saved_courses_lists']= json_decode($this->MarketingPageClient->getStudyAbroadCoursesListForApage(1,$page_id,$pagetype,'saved_list'),true);
                        }
			
                        //_P($data['saved_courses_lists']);
                        //var_dump($NEW_ABROAD_REGISTRATION);
                        if(STUDY_ABROAD_NEW_REGISTRATION) {
                                //_P($data['abroad_main_ldb_courses']); 
				$course_ids = $data['saved_courses_lists']['course_id'];
                                $temp_array = explode(",",$course_ids);
				$final_course_ids_ldb = array(); 
				$final_course_ids = array();
				$ldb_main_courses = array();
				foreach($data['abroad_main_ldb_courses'] as $val) {
					$ldb_main_courses[] = $val['SpecializationId'];
				}
                                foreach($temp_array as $id) {
				
					if(in_array($id,$ldb_main_courses)) {
						$final_course_ids_ldb[] = $id;	
					} else {
						$final_course_ids[] = $id;
                                        }
				
				}
				$data['course_ids'] = implode(' ',$final_course_ids);
				$data['final_course_ids_ldb'] = $final_course_ids_ldb;
                        } else {
				$data['course_ids'] = $data['saved_courses_lists']['course_id'];
				$data['course_ids'] = str_replace(',',' ',$data['course_ids']);
                        }
			
			$savedcatgeories = array();
			foreach($data['saved_courses_lists']['courses_list'] as $category) {
				$savedcatgeories[$category[0]['categoryID'][0]] = $category[0]['categoryName'][0];
			}
			asort($savedcatgeories);
			$data['saved_courses_lists']= $savedcatgeories;
                        if(STUDY_ABROAD_NEW_REGISTRATION) {
				$view_template = 'enterprise/addremovemmpagenewabroadcourses';		
                        } else {
				$view_template = 'enterprise/addremovemmpageabroadcourses';
                        } 
			
		} else if($pagetype=='testpreppage') {
			//$categoryClient = new Category_list_client();
			$data['courses_list'] = json_decode($this->MarketingPageClient->getTestPrepCoursesListForApage(1,$page_id,$pagetype,'complete_list'),true);
			$data['courses_list'] = $data['courses_list']['courses_list'];
			$data['saved_courses_lists']= json_decode($this->MarketingPageClient->getTestPrepCoursesListForApage(1,$page_id,$pagetype,'saved_list'),true);
			$data['saved_courses_list'] = $data['saved_courses_lists']['courses_list'];
			$data['course_ids'] = $data['saved_courses_lists']['course_id'];
			$data['course_ids'] = str_replace(',',' ',$data['course_ids']);
			$view_template = 'enterprise/addremovemmpagetestprepcourses';
		}
		$this->load->view($view_template,$data);
	}
	/**
	 * saves selected courses for a selected page
	 *
	 * @access	public
	 * @return	void
	 */
	public function saveMMPageCourses($page_id,$page_type) {

		$this->init();
		$courseIds = $_REQUEST['courses_ids'];
		$mcourseIds = $_REQUEST['management_courses_ids'];
		if(isset($_REQUEST['skipbutton'])){
		    header('location:/customizedmmp/mmp/customizePage/'.$page_id);
		    exit();
		}

		$this->getHeaderTabs();
		$data['page_id'] = $page_id;
		//echo $page_type;exit;
		$courses_ids = trim($this->input->post('courses_ids'));

		$page_details= $this->MarketingPageClient->marketingPageDetailsById($page_id);

		if(empty($page_type))
		$page_type = $page_details['page_type'];
		if($page_type=='indianpage') {
			$management_courses_ids = trim($this->input->post('management_courses_ids'));
			$courses_ids = $courses_ids.'-'.$management_courses_ids;
		}
		$final_ldb_courses = array();
		if(STUDY_ABROAD_NEW_REGISTRATION && $page_type == 'abroadpage') {
			$main_ldb_course = $this->input->post('main_ldb_course',true);
			$main_ldb_course_array = explode(",",$main_ldb_course);
			$main_ldb_course_array = array_unique($main_ldb_course_array);
                        
			foreach($main_ldb_course_array as $id) {
				
				if($id>0) {
					$final_ldb_courses[] = $id;
				}
			
                        } 			
                }

		//var_dump($final_ldb_courses);exit;
		if((trim($courseIds) == '' && trim($mcourseIds) == '') && count($final_ldb_courses) == 0){
		    header('location:/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/'.$page_type.'/E');
		    exit();
		}
		//echo $courses_ids;                
		if(count($final_ldb_courses)>0) {
			$courses_ids = $courses_ids." ".implode(" ",$final_ldb_courses);
		}
		//echo $courses_ids;exit;
		$courses_ids = trim($courses_ids," ");

		$this->MarketingPageClient->saveMMPageCourses($page_id,$courses_ids,$page_type);
        	header('location:/customizedmmp/mmp/customizePage/'.$page_id);
		//header('location:/enterprise/MultipleMarketingPage/marketingPageDetails');
		exit();
	}
	/**
	 * Loads the view which lists courses for a mmmarketing page
	 *
	 * @access	public
	 * @return	void
	 */
	public function viewMMPageCourses($page_id){
	    $this->init();
		$data['headerTabs'] = $this->getHeaderTabs();
		$page_details= $this->MarketingPageClient->marketingPageDetailsById($page_id);
		$pagetype = $page_details['page_type'];
		$data['prodId'] = 46;
		$data['page_id'] = $page_id;
		$data['page_url'] = $page_details['page_url'];
		if($pagetype=='indianpage') {
		$saved_courses_list = json_decode($this->MarketingPageClient->getCourselistForApage($page_id,'category'),true);
		$data['saved_courses_list'] =$saved_courses_list['courses_list'];
		$data['management_courseids'] = trim(str_replace(',',' ',$saved_courses_list['management_courseids']));
		$data['saved_management_courses'] = $this->MarketingPageClient->getManagementCourses($data['management_courseids']);
		$view_template = 'enterprise/viewmmpagecourses';
		} else if($pagetype=='testpreppage') {
			$data['saved_courses_lists']= json_decode($this->MarketingPageClient->getTestPrepCoursesListForApage(1,$page_id,$pagetype,'saved_list'),true);
			$data['saved_courses_list'] = $data['saved_courses_lists']['courses_list'];
			$view_template = 'enterprise/viewmmtestpreppagecourses';
		}  else if($pagetype=='abroadpage') {
                        if(STUDY_ABROAD_NEW_REGISTRATION) {
				$data['abroad_main_ldb_courses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();
				$data['saved_courses_lists']= json_decode($this->MarketingPageClient->getStudyAbroadCoursesListForApage(1,$page_id,$pagetype,'saved_list','newabroad'),true);
				$course_ids = $data['saved_courses_lists']['course_id'];
                                $temp_array = explode(",",$course_ids);
				$final_course_ids_ldb = array(); 
				$final_course_ids = array();
				$ldb_main_courses = array();
				foreach($data['abroad_main_ldb_courses'] as $val) {
					$ldb_main_courses[] = $val['SpecializationId'];
				}
                                foreach($temp_array as $id) {
				
					if(in_array($id,$ldb_main_courses)) {
						$final_course_ids_ldb[] = $id;	
					} else {
						$final_course_ids[] = $id;
                                        }
				
				}
				//$data['course_ids'] = implode(' ',$final_course_ids);
				$data['final_course_ids_ldb'] = $final_course_ids_ldb;
                        } else {
				$data['saved_courses_lists']= json_decode($this->MarketingPageClient->getStudyAbroadCoursesListForApage(1,$page_id,$pagetype,'saved_list'),true);
                        }
			$savedcatgeories = array();
			foreach($data['saved_courses_lists']['courses_list'] as $category) {
				$savedcatgeories[$category[0]['categoryID'][0]] = $category[0]['categoryName'][0];
			}
			asort($savedcatgeories);
			$data['saved_courses_lists']= $savedcatgeories;
			$view_template = 'enterprise/viewmmstudyabroadpagecourses';
		}
		$data['validateuser'] = $this->userStatus;
		$this->load->view($view_template,$data);
	}
	/**
	 * It will render view pertaining to a particular marketing page
	 *
	 * @access	private
	 * @return	array
	 */
	private function getHeaderTabs () {
	 $this->userStatus = $this->checkUserValidation();
		if(($this->userStatus == "false" )||($this->userStatus == "")) {
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		}
		if(is_array($this->userStatus) && $this->userStatus['0']['usergroup']!='cms') {
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$entObj = new Enterprise_client();
		$headerTabs = $entObj->getHeaderTabs(1,$this->userStatus[0]['usergroup'],$this->userStatus[0]['userid']);
		$headerTabs['0']['selectedTab'] =46;
		return $headerTabs;
	}

	/**
	 * Upload and save banner image
	 *
	 * @access	private
	 * @return	array
	 */
	public function uploadBannerImage($files, $vcard = 0,$appId = 1){
		$this->init();
		$this->userStatus = $this->checkUserValidation();
		if($files['myImage']['tmp_name'][0] == '')
			$data['error_message'] = "Please select a photo to upload";
		else{
			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$upload = $uploadClient->uploadFile($appId,'image',$files,array(),$this->userStatus['0']['userid'],"user", 'myImage');
			if(!is_array($upload))
				$data['error_message'] =  $upload;
			else{
				list($width, $height, $type, $attr) = getimagesize($upload[0]['imageurl']);
				$data['data'] =  $upload[0]['imageurl'];
				$data['success_message'] = "Image has been successfully uploaded, please click on above save button to save the image";
			}
		}
	
        return $data;

    }


    public function uploadHeaderImage($files, $vcard = 0,$appId = 1){
		$this->init();
		$this->userStatus = $this->checkUserValidation();
		unset($files['myImage']);


		if($files['myHeaderImage']['tmp_name'][0] == '')
			$data['error_message'] = "Please select a photo to upload";
		else{
			list($width, $height, $type, $attr) = getimagesize($files['myHeaderImage']['tmp_name'][0]);

			if( $width != 230 || $height != 100 ){
				$data['header_error_message'] =  'Please update image of 230*100 px dimension';
				return $data;
			}

			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$upload = $uploadClient->uploadFile($appId,'image',$files,array(),$this->userStatus['0']['userid'],"user", 'myHeaderImage');
			
			if(!is_array($upload))
				$data['header_error_message'] =  $upload;
			else{
				
				$data['data'] =  $upload[0]['imageurl'];
				$data['header_success_message'] = "Image has been successfully uploaded.";
			}

			
		}

	
        return $data;

    }
	
	public function uploadDocument($files, $vcard = 0,$appId = 1){
		$this->init();
		$this->userStatus = $this->checkUserValidation();
		if($files['myImage']['tmp_name'][0] == '') {
			$data['error_message'] = "Please select a document to upload";
		}
		else {
			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$upload = $uploadClient->uploadFile($appId,'pdf',$files,array(),$this->userStatus['0']['userid'],"notification", 'myImage');
			
			error_log("UPL: ".print_r($upload,true));
			
			if(!is_array($upload)) {
				$data['error_message'] =  $upload;
			}
			else {
				list($width, $height, $type, $attr) = getimagesize($upload[0]['imageurl']);
				$data['data'] =  $upload[0]['imageurl'];
				$data['success_message'] = "Image has been successfully uploaded, please click on above save button to save the image";
			}
        }
		
        return $data;
    }
    
	/**
	 * Upload and save background image
	 *
	 * @access	private
	 * @return	array
	 */
	public function uploadBackgroundImage($files, $vcard = 0,$appId = 1){
		$this->init();
		$this->userStatus = $this->checkUserValidation();
		
		if($files['myImage']['tmp_name'][0] == ''){
			$data['error_message'] = "Please select a photo to upload";
	
		}else {
			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$upload = $uploadClient->uploadFile($appId,'image',$files,array(),$this->userStatus['0']['userid'],"user", 'myImage');
		
			if(!is_array($upload)){

				$data['error_message'] =  $upload;
	
			}else {
				
				list($width, $height, $type, $attr) = getimagesize($upload[0]['imageurl']);
				$data['data'] =  $upload[0]['imageurl'];
				$data['success_message'] = "Image has been successfully uploaded, please click on above save button to save the image";
			}
		}
        
	return $data;

    }

    function loadFormForMMPCustomization(){
    	$regFormId = $this->input->post('regFormId');
    	$customization = $this->input->post('customization');
    	$customization =json_decode($customization, true);
    	echo Modules::run('registration/RegistrationForms/LDB','nationalPreference','userProfile', array('showInterestPrefilled'=>'no', 'regFormId'=>$regFormId, 'customFields'=>$customization)); 
    }

}
?>
