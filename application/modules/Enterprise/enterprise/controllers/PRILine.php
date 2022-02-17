<?php

class PRILine extends MX_Controller
{
    private function _init()
    {
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
    
    public function index()
    {
        // call init method to set basic objects
		$this->_init();
		$data['headerContentaarray'] = $this->loadHeaderContent();
        
        $this->load->model('categoryList/categorymodel');
        $data['categories'] = $this->categorymodel->getMainCategories();
        
        $this->load->model('location/locationmodel');
        $data['cities'] = $this->locationmodel->getCities(2);
        
        if($_POST['category'] && $_POST['city']) {
			$data['selectedCategory'] = $_POST['category'];
			$data['selectedCity'] = $_POST['city'];
				
            $this->load->model('listing/coursemodel');
            $courses = $this->coursemodel->getCoursesByCategoryAndLocation($_POST['category'],$_POST['city']);
			if(count($courses) > 0){
				$courseIds = array_keys($courses);
				$this->load->model('LDB/ldbmodel');
				$responseCounts = $this->ldbmodel->getResponseCountForCourses($courseIds);
				
				$finalCourses = array();
				foreach($courses as $course) {
					$responseCountForCourse = intval($responseCounts[$course['courseId']]);
					if($responseCountForCourse > 5) {
						$course['responseCount'] = $responseCountForCourse;
						$finalCourses[] = $course;
					}
				}
				
				$data['courses'] = $finalCourses;
				
				$this->load->model('enterprise/prilinemodel');
				$data['PRIAssignments'] = $this->prilinemodel->getPRIAssignments();
			}
        }
        
        $this->load->view('enterprise/PRILineAdmin',$data);
    }
    
    public function viewMapped()
    {
        // call init method to set basic objects
		$this->_init();
		$data['headerContentaarray'] = $this->loadHeaderContent();

        $this->load->model('enterprise/prilinemodel');
        $data['PRIMapping'] = $this->prilinemodel->getPRIMapping();   
        $data['PRIMappingHistory'] = $this->prilinemodel->getPRIMapping(TRUE);
        
        $this->load->view('enterprise/PRILineAssignments',$data);
    }
    
    public function set()
    {
        $this->_init();
        $courseId = $_POST['courseId'];
        $PRINumber = $_POST['PRINumber'];
        $cityId = $_POST['cityId'];
        $categoryId = $_POST['categoryId'];
        $setOn = date('Y-m-d H:i:s');
        
        $this->load->model('enterprise/prilinemodel');
        if($this->prilinemodel->isAlreadyAssigned($PRINumber)) {
            echo json_encode(array('status' => 'ALREADY_ASSIGNED'));    
        }
        else {
            $this->prilinemodel->setPRINumber($courseId,$PRINumber,$cityId,$categoryId,$setOn);
            echo json_encode(array('status' => 'SUCCESS','PRINumber' => $PRINumber,'setOn' => date('M j Y',strtotime($setOn))));    
        }
    }
    
    public function reset()
    {
        $this->_init();
        $courseId = $_POST['courseId'];
        
        $this->load->model('enterprise/prilinemodel');
        $this->prilinemodel->resetPRINumber($courseId);
        
        //echo json_encode(array('PRINumber' => $PRINumber,'setOn' => date('M j Y',strtotime($setOn))));
    }
    
    public function loadHeaderContent()
    {
		$this->_init();
		$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($this->_validateuser[0]['displayname'])?$this->_validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>'',
        'prodId'=>791
		);
		$headerTabs = $this->enterprise_client->getHeaderTabs(1,$this->_validateuser[0]['usergroup'],$this->_validateuser[0]['userid']);
		$headerTabs['0']['prodId'] = 791;
		$headerComponents['headerTabs'] = $headerTabs;
		$headerCMSHTML = $this->load->view('enterprise/headerCMS', $headerComponents,true);
		$headerTABSHTML = $this->load->view('enterprise/cmsTabs',$headerComponents,true);
		return array($headerCMSHTML,$headerTABSHTML);
	}
}
