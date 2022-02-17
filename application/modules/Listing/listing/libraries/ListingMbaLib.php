<?php

class ListingMbaLib{
	private $CI;

	function __construct() {
		$this->CI =& get_instance();
	}

	function findCourseIdsWithOnlineForm(){
		$this->myshortlistmodel                     = $this->CI->load->model("myShortlist/myshortlistmodel");
		return $this->myshortlistmodel->findCourseIdsWithOnlineForm();
	}

	function getOnlineApplicationCoursesUrl(){
		$this->CI->load->library('studentFormsDashBoard/dashboardconfig');
		$internalForms = DashboardConfig::$institutes_autorization_details_array;
		$PBTSeoData    = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails');
		$internalForms += $PBTSeoData;
		return $internalForms;
	}
}