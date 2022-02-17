<?php
/**
 * ShikshaTrends Controller.
 * Controller for shiksha analytics/trends
 * @date    2017-09-19
 * @author  Romil Goel
 * @todo    none
*/
class ShikshaTrends extends MX_Controller
{

	function __construct(){
		parent::__construct();
		
		$this->load->helper(array('string','image','analytics'));
	}

	function trendsHomePage(){

		// get user details
		if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }

		// initialize resources
		$trendsHomeLibrary = $this->load->library("analytics/TrendsHomeLibrary");

		// prepare page data
		$displayData = array();
		$displayData = $trendsHomeLibrary->getTrendsHomeData($this->userStatus);
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userStatus'] = $this->userStatus;
        $displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		
		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_TrendsHomePage');
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		// render page
		$this->load->view("trendsHome", $displayData);
	}

	    function dataTester() {
        $trendsHomeLibrary = $this->load->library("analytics/TrendsHomeLibrary");
        _p($trendsHomeLibrary->getTestData());
        //$elasticLibrary = $this->load->library("analytics/ElasticLib");
        //_p($elasticLibrary->get_elastic_data());

    }

    function filterWidget(){

    	$widgetName = $this->input->post("widgetname");

    	switch ($widgetName) {
    		case 'popularuniversity':
				$univLocFilter       = $this->input->post("univLocFilter");
				$univOwnershipFilter = $this->input->post("univOwnershipFilter");
				$pageNumber			 = $this->input->post("pageNumber");

				// get the trend repo
		        $this->load->builder("analytics/TrendsBuilder");
		        $trendsBuilder = new TrendsBuilder();
		        $this->trendsRepo = $trendsBuilder->getTrendsRepository();

		        $popularUniversities          = $this->trendsRepo->getPopularUniversities($univLocFilter, $univOwnershipFilter, $pageNumber);
				$data['popular_universities'] = $popularUniversities;
				$data['entityId'] = $entityId;
				$data['entityType'] = $entityType;
				// Setting link type
				if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
		            $data['linkTarget'] = "";
		        }
		        else{
		            $data['linkTarget'] = "_blank";
		        }

				$viewData = $this->load->view("analytics/homeWidgets/popularUniversities", $data, true);

				$result = array();
				$result['html'] = $viewData;
				$result['ownershipData'] = $data['popular_universities']['ownership_data'];

				echo json_encode($result);
    			break;

    		case 'popularinstitute':
				$instLocFilter    = $this->input->post("instLocFilter");
				$instStreamFilter = $this->input->post("instStreamFilter");
				$pageNumber       = $this->input->post("pageNumber");
				$entityType       = $this->input->post("entityType");
				$entityId         = $this->input->post("entityId");

				// get the trend repo
		        $this->load->builder("analytics/TrendsBuilder");
		        $trendsBuilder = new TrendsBuilder();
		        $this->trendsRepo = $trendsBuilder->getTrendsRepository();

		        $popularInstitutes          = $this->trendsRepo->getPopularInstitutesData($instLocFilter, $instStreamFilter, $pageNumber, $entityType, $entityId);
				$data['popular_institutes'] = $popularInstitutes;
				$data['entityId'] = $entityId;
				$data['entityType'] = $entityType;
				$data['callType'] = "ajax";
				$data['totalResults'] = $popularInstitutes['totalResults'];

				// Setting link type
				if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
		            $data['linkTarget'] = "";
		        }
		        else{
		            $data['linkTarget'] = "_blank";
		        }
				$viewData = $this->load->view("analytics/homeWidgets/popularInstitutes", $data, true);

				$result = array();
				$result['html'] = $viewData;
				$result['streamData'] = $data['popular_institutes']['stream_data'];
				echo json_encode($result);
    			break;

    		case 'popularcourse':
				$courseLevelFilter      = $this->input->post("courseLevelFilter");
				$courseCredentialFilter = $this->input->post("courseCredentialFilter");
				$pageNumber             = $this->input->post("pageNumber");
				$entityType             = $this->input->post("entityType");
				$entityId               = $this->input->post("entityId");

				// get the trend repo
		        $this->load->builder("analytics/TrendsBuilder");
		        $trendsBuilder = new TrendsBuilder();
		        $this->trendsRepo = $trendsBuilder->getTrendsRepository();

		        $popularCourses          = $this->trendsRepo->getPopularCoursesData($courseLevelFilter, $courseCredentialFilter, $pageNumber, $entityType, $entityId);
				$data['popular_courses'] = $popularCourses;
				$data['entityId'] = $entityId;
				$data['entityType'] = $entityType;
				// Setting link type
				if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
		            $data['linkTarget'] = "";
		        }
		        else{
		            $data['linkTarget'] = "_blank";
		        }

				$viewData = $this->load->view("analytics/homeWidgets/popularCourses", $data, true);

				$result = array();
				$result['html'] = $viewData;
				$result['credentialData'] = $data['popular_courses']['credentials_data'];

				echo json_encode($result);
    			break;

    		case 'popularexam':
				$examStreamFilter = $this->input->post("examStreamFilter");
				$pageNumber       = $this->input->post("pageNumber");
				$entityType       = $this->input->post("entityType");
				$entityId         = $this->input->post("entityId");

				// get the trend repo
		        $this->load->builder("analytics/TrendsBuilder");
		        $trendsBuilder = new TrendsBuilder();
		        $this->trendsRepo = $trendsBuilder->getTrendsRepository();

		        $popularExams          = $this->trendsRepo->getPopularExamsData($examStreamFilter, $pageNumber, $entityType, $entityId);

		         if(!empty($popularExams['streams'])){
		            foreach ($popularExams['streams'] as $key => &$value) {
		                $value = str_replace("&", "and", $value);
		            }
		        }

				$data['popular_exams'] = $popularExams;
				$data['entityId'] = $entityId;
				$data['entityType'] = $entityType;

				// Setting link type
				if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
		            $data['linkTarget'] = "";
		        }
		        else{
		            $data['linkTarget'] = "_blank";
		        }

				$viewData = $this->load->view("analytics/homeWidgets/popularExams", $data, true);

				$result = array();
				$result['html'] = $viewData;

				echo json_encode($result);
    			break;

    		case 'popularspecialization':
				$specStreamFilter = $this->input->post("specStreamFilter");
				$pageNumber       = $this->input->post("pageNumber");
				$entityType       = $this->input->post("entityType");
				$entityId         = $this->input->post("entityId");

				// get the trend repo
		        $this->load->builder("analytics/TrendsBuilder");
		        $trendsBuilder = new TrendsBuilder();
		        $this->trendsRepo = $trendsBuilder->getTrendsRepository();

				$popularSpec                    = $this->trendsRepo->getPopularSpecializationsData($specStreamFilter, $pageNumber, $entityType, $entityId);

				if(!empty($popularSpec['specialization'])){
		            foreach ($popularSpec['specialization'] as $key => &$value) {
		                $value['text'] = str_replace("&", "and", $value['text']);
		            }
		        }
		        if(!empty($popularSpec['streams'])){
		            foreach ($popularSpec['streams'] as $key => &$value) {
		                $value = str_replace("&", "and", $value);
		            }
		        }

				$data['popular_specialization'] = $popularSpec;
				$data['entityId'] = $entityId;
				$data['entityType'] = $entityType;
				$data['callType'] = "ajax";
				$data['totalResults'] = $popularSpec['totalResults'];

				if(!empty($entityId))
					$data['popular_specialization']['fullwidthview'] = true;
				
				// Setting link type
				if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
		            $data['linkTarget'] = "";
		        }
		        else{
		            $data['linkTarget'] = "_blank";
		        }

				$viewData = $this->load->view("analytics/homeWidgets/popularSpecialization", $data, true);

				$result = array();
				$result['html'] = $viewData;

				echo json_encode($result);
    			break;

    		case 'interestregion':
				$specStreamFilter = $this->input->post("specStreamFilter");
				$pageNumber       = $this->input->post("pageNumber");
				$entityType       = $this->input->post("entityType");
				$entityId         = $this->input->post("entityId");

				// get the trend repo
		        $this->load->builder("analytics/TrendsBuilder");
		        $trendsBuilder = new TrendsBuilder();
		        $this->trendsRepo = $trendsBuilder->getTrendsRepository();

				$regionData         = $this->trendsRepo->getInterestByRegion($entityType, $entityId, $pageNumber);

				$data['region_data'] = $regionData;
				$data['entityId'] = $entityId;
				$data['entityType'] = $entityType;

				$viewData = $this->load->view("analytics/etpWidgets/interestByRegion", $data, true);

				$result = array();
				$result['html'] = $viewData;

				echo json_encode($result);
    			break;
    			
    		
    		
    		default:
    			# code...
    			break;
    	}
    }


    function showETP($entityType, $entityId){
    	// check if entityId is integer
        if( ! ctype_digit(strval($entityId)) ){
                 show_404();
        }

    	// get user details
		if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }

		// initialize resources
		$trendsETPLibrary = $this->load->library("analytics/TrendsETPLibrary");

		// get the data
		$displayData = $trendsETPLibrary->getPageData($entityType, $entityId);

		$displayData['validateuser'] = $this->userStatus;
		$displayData['userStatus'] = $this->userStatus;
		
        $displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_ShikshaTrends','pageType'=>$entityType,'entity_id'=>$entityId);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

    	// render view
    	$this->load->view("analytics/etpPage", $displayData);
    }


    function getExamId(){

    	$examName = $this->input->post("examName");

		$this->trendsmodel = $this->load->model("trendsmodel");
		$examId            = $this->trendsmodel->getExamIdByName($examName);

    	echo $examId;
    }

}
