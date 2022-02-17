<?php
/**
 * Controller for Study abroad Sales Dashboard.
*/

class saSalesDashboard extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
        //ini_set("memory_limit", "256M");
        $this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
        $this->saSalesLib = $this->load->library('trackingMIS/saSalesLib');
	}

	public function index(){
		$data = array();
		$validUser = $this->trackingLib->checkValidSASalesUser();
		$data['userDataArray'] = reset($validUser);
		$this->load->config('cdTrackingMISConfig');
        $data['leftMenuArray'] = $this->config->item("leftMenuArray");
        $topFilters = $this->config->item('FILTER');
        $data['topFilters'] = $topFilters['SASALES'];
        $data['misSource'] = "SASALES";
        $teamSource = 'CD';
        $data['teamName'] = 'SA Sales';
        $data['source'] = $teamSource;
        //_p($data['topFilters']);die;
		$data['includeUpdatedJS'] = true;
		$data['skipNavigationSASales'] = $validUser['isSASalesDashboardUser'];
		$this->load->view('trackingMIS/misTemplate', $data);

	}

	public function getUniversityDetails(){
		$userDataArray = $this->trackingLib->checkValidSASalesUser(false,true);
		if($userDataArray == "noauth"|| $userDataArray == "unauth")
		{
			echo json_encode(array('auth'=>$userDataArray));
			return ;
		}
		$univId = (int)$this->input->post('univId');
		$dateRange = $this->input->post('dateRange');
		$days = $this->input->post('days');
		$courseType  = $this->input->post('courseType');
		$univDetails = $this->saSalesLib->getUniversityDetails($univId,$dateRange,$courseType);
		//$courseIds = $univDetails['courseIds'];
		$courseIds = $univDetails['activeCourseIds'];
		//_p($courseIds);
		//_p($univDetails['univTrafficData']);die;
		echo json_encode($univDetails);

	}

	public function getUniversityTraffic()
	{
		$univId = $this->input->post('univId');
		$dateRange = $this->input->post('dateRange');
		$days = $this->input->post('days');
		$courseIds = $this->input->post('courseIds');
		// get data from elastic search
		$univTrafficData = $this->saSalesLib->getUniversityTrafficFromES($univId,$courseIds,$dateRange,$days);
		echo json_encode($univTrafficData);
		return;
	}

	public function getUniversityResponses(){
		$univId = $this->input->post('univId');
		$courseIds = $this->input->post('courseIds');
		$dateRange = $this->input->post('dateRange');
		$days = $this->input->post('days');
		$univDetails = array();
		if($univId !='' && $courseIds !='' && $dateRange !='' && $days!=''){
			$univDetails['univResponseData'] = $this->saSalesLib->getUniversityResponseFromES($univId,$courseIds,$dateRange,$days);
		}
		echo json_encode($univDetails);
	}
	
	public function getTopResponseCities()
	{
		$dateRange = $this->input->post('dateRange');
		$courseIds = $this->input->post('courseIds');
		$responseCitiesWithCount = $this->saSalesLib->getTopResponseCities($courseIds,$dateRange);
		echo json_encode(array_slice($responseCitiesWithCount,0,10));
		return;
	}
		
	public function getPopularCourseResponses()
	{
		$dateRange = $this->input->post('dateRange');
		$courseIds = $this->input->post('courseIds');
		$courseType = $this->input->post('courseType');
		$paidCourseIds = $this->input->post('paidCourseIds');
		$popularCoursesResponses = $this->saSalesLib->getPopularCourseResponsesFromES($courseIds,$dateRange,$courseType, $paidCourseIds);
		echo json_encode($popularCoursesResponses);
		return;
	}

	public function getComparedUnivs()
	{
		$dateRange = $this->input->post('dateRange');
		$courseIds = $this->input->post('courseIds');
		$exclusionIds = $this->input->post('allCourseIds');
		$comparedUnivs = $this->saSalesLib->getComparedUniversities($courseIds,$exclusionIds,$dateRange);
		echo json_encode($comparedUnivs,true);
		return;
	}
	
	public function getRMCWithExamsStudentData()
	{
		$dateRange = $this->input->post('dateRange');
		$courseIds = $this->input->post('courseIds');
		$userCount = $this->saSalesLib->getRMCWithExamsStudentData($courseIds,$dateRange);
		echo json_encode(array('labels' => array_keys($userCount),'values'=>array_values($userCount),'total'=>array_sum($userCount)));
		return;
	}
	
	public function getApplicationProcessWidgetData()
	{
		$dateRange = $this->input->post('dateRange');
		$courseIds = $this->input->post('courseIds');
		$userCount = $this->saSalesLib->getApplicationProcessWidgetData($courseIds,$dateRange);
		$valueArr = array();
		$fullData = $userCount;
		unset($userCount['countData']['Editorial']);
		unset($fullData['countData']);
		echo json_encode(array('fullData' =>$fullData,
							   'labels' => array_keys($userCount['countData']),
							   'values'=>array_values($userCount['countData']),
							   'total'=>array_sum($userCount['countData'])),true);
		return;
	}
	public function getUserStageBarData(){
		$courseIds = $this->input->post('courseIds');
		$dateRange = $this->input->post('dateRange');
		$studentDetails = array();
		if($courseIds !='' && $dateRange !=''){
			$studentDetails['shortlistData']   = $this->saSalesLib->getShortlistedStudent($courseIds,$dateRange);
			$studentDetails['finalizedData']   = $this->saSalesLib->getUniversityFinalizedStudent($courseIds,$dateRange);
			$studentDetails['applicationData'] = $this->saSalesLib->getUniversityFinalizedStudent($courseIds,$dateRange,true);
		}
		echo json_encode($studentDetails);

	}

	public function getAppliedStudentData(){
		$courseIds = $this->input->post('courseIds');
		$dateRange = $this->input->post('dateRange');
		$studentDetails = array();
		if($courseIds !='' && $dateRange !=''){
			$appliedData   = $this->saSalesLib->getAppliedStudent($courseIds,$dateRange);
		}
		echo json_encode($appliedData);

	}

	public function getStudentDetails()
	{
		$studentIds = $this->input->post('studentIds');
		$tabName = ($this->input->post('tabName')==''?'shortlist':$this->input->post('tabName'));
		if(is_array($studentIds) && count($studentIds)>0){
			$studentDetails = $this->saSalesLib->getStudentProfileDetails($studentIds);
		}
		
		$displayData['studentDetail'] = $studentDetails;
		$displayData['count'] = count($studentDetails);
		$displayData['tabName'] = $tabName;
		$displayData['heading'] = $this->saSalesLib->getStudentDetailTabHeading($tabName,$displayData['count']);
		if($tabName=='visa' || $tabName=='admitted'){
			$displayData['applied'] = $this->saSalesLib->getAppliedStudentDetails($tabName,$studentIds);
			if($tabName=='visa')
            {
                /* Documents Types considered
                 *
                 * 3  :Admission offer letter (unconditional)
                 * 9  :CAS (confirmation of acceptance of studies)
                 * 10 :COE (confirmation of enrollment)
                 * 20 :I-20
                 * 39 :Scholarship letter from university
                 */
                $documentTypeIds = array(3,9,10,20,39);
                $displayData['candidatesDocumentsDetails'] = $this->saSalesLib->getCandidatesDocumentsByDocumentType($studentIds,$documentTypeIds);
            }
		}
		if(in_array($tabName,array('Accepted','Submitted','Rejected')) !== false)
		{
			$displayData['scholarship'] = $this->input->post('extraData');
		}
		$this->load->view('trackingMIS/saSales/widgets/studentProfileDetails', $displayData);
	}
}


?>