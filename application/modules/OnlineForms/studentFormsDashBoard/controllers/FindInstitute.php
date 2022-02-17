<?php
/**
 * This class is responsible for rendering institutes based on user search criteria.
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
include_once 'ManageBreadCrumb.php';
class FindInstitute extends MX_Controller {
	// it stores user details of the logged in user
	private $_validateuser;
	// it holdes the reference of breadcrumb library
	private $_manageBreadCrumb_object;
	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	private function _init() {
		//load the required library
		$this->load->library('StudentDashboardClient');
		$this->load->library('dashboardconfig');
		$this->load->library('Online/courseLevelManager');
                $this->load->helper(array('shikshautility_helper'));
		//set user details
		$this->_validateuser = $this->checkUserValidation();
                if(($this->_validateuser == "false" )||($this->_validateuser == "")) {
			header('location:'.$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']);exit();
		}
		//instantiate object of bread crumb
		$this->_manageBreadCrumb_object = new ManageBreadCrumb();
		
	}
	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function index() {
		// call init method to set basic objects
		$this->_init();
		// set data needs to be send to template
		$data['validateuser'] = $this->_validateuser;
		$data['breadCrumbHTML'] = $this->_manageBreadCrumb_object->renderBredCrumbDetails();
		// get required input post parameters
		$key_word = trim($this->input->post('keyWord'));
		$category_id = trim($this->input->post('categoryId'));
		$of_institute_ids_array = json_decode($this->studentdashboardclient->getTheIdsOfInstituteHavingOF($key_word,$category_id,$this->courselevelmanager->getCurrentDepartment()),true);
		// api return each and every details for a list of institute
		if(!empty($of_institute_ids_array) && is_array($of_institute_ids_array)){	
		$data['instituteList'] = $this->studentdashboardclient->renderInstituteListWithDetails($of_institute_ids_array);
		$data['institute_features'] = json_decode($this->studentdashboardclient->returnOfInstitutesOfferandOtherDetails($of_institute_ids_array),true);
		$data['config_array'] = DashboardConfig::$institutes_autorization_details_array;
		$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', $of_institute_ids_array);
		$data['config_array'] += $PBTSeoData;
		}
                /* code addded for pagiantion */
		$data['page_type_for_identification'] = "STUDENT_HOME_PAGE";
		$data['total_number_pages'] = 0;
		$data['count_result'] = count($data['instituteList']);
		if($data['count_result']%INSTITUTE_PER_PAGE == 0) {
			$data['total_number_pages'] = (int)($data['count_result']/INSTITUTE_PER_PAGE);
		} else {
			$data['total_number_pages'] = (int)($data['count_result']/INSTITUTE_PER_PAGE)+1;
		}
		foreach ($data['instituteList'] as $inst_id1=>$instituteList_object1):
		$inst_id1_arry[] = $inst_id1;
		endforeach;
		$data['inst_id1_arry'] = $inst_id1_arry;
		$current_page = strip_tags($_REQUEST['start']);
		$current_page = intval($current_page,10);
		if(!empty($current_page)) {
			$current_page_new = ($current_page/INSTITUTE_PER_PAGE)+1;
		} else {
			$current_page_new = 1;
		}
		$paginationHTML = doPagination($data['count_result'],SHIKSHA_HOME."/studentFormsDashBoard/FindInstitute/index?start=@start@&num=@count@",$current_page,INSTITUTE_PER_PAGE,$data['total_number_pages']);
		$data['paginationHTML'] = $paginationHTML;
		$offset = ($current_page_new-1)*INSTITUTE_PER_PAGE;
		$return_array = $this->studentdashboardclient->handlePagination($inst_id1_arry,$data['total_number_pages'],$current_page_new,INSTITUTE_PER_PAGE) ;
		if(!empty($return_array) && count($return_array)>0) {
			$data['instituteList'] = $return_array['instituteList'];
			$data['institute_features'] = $return_array['institute_features'];
		}

		//below code used for beacon tracking
		$data['trackingpageIdentifier'] = 'studentFormsDashBoardPage';
		$data['trackingtype'] = 'findInstitute';
		$data['trackingcountryId']=2;


		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($data);
		// load required view
		$this->load->view('studentFormsDashBoard/findInstitute',$data);
	}
}
?>
