<?php 
class pbtautomationmodel extends MY_Model {
function __construct(){
		parent::__construct('OnlineForms');
	}

	private function initiateModel($operation='read'){
		if($operation=='read'){
			return $this->getReadHandle();
		}else{
        	return $this->getWriteHandle();
		}
	}

	public function getPBTFormsForTheCollege($clgId){
		$dbHandle = $this->initiateModel();
		$queryCmd = "SELECT ef.id, ef.course_id, ef.url, sc.primary_id as institute_id, sc.name as courseTitle, CASE WHEN ofid.id is NULL THEN 0 ELSE ofid.id END as 'instDetailId'
						from OF_ExternalForms ef 
						join shiksha_courses sc on sc.course_id=ef.course_id and sc.status='live' and ef.status='live' and sc.primary_id = ?
						left join OF_InstituteDetails ofid on ofid.courseId=ef.course_id and ofid.externalURL != '' and ofid.status='live'";
		$query = $dbHandle->query($queryCmd,array($clgId));
		$result = $query->result_array();
		return $result;
	}

	public function getPBTInstDetailsForCourses($courseIds){
		$res = array();
		if(!empty($courseIds)){
			$dbHandle = $this->initiateModel();
			$queryCmd = "SELECT ofid.id, ofid.instituteId, ofid.courseId, ofid.fees, ofid.status, ofid.discount, ofid.last_date, ofid.min_qualification, ofid.exams_required, ofid.departmentId, ofid.departmentName, ofid.sessionYear, ofid.otherCourses, ef.url as clgURL from OF_InstituteDetails ofid join OF_ExternalForms ef on ef.course_id=ofid.courseId and ofid.status='live' and ef.status='live' where courseId in (?)";
			$query = $dbHandle->query($queryCmd,array($courseIds));
			$result = $query->result_array();
			foreach ($result as $key => $value) {
				$res[$value['courseId']] = $value;
			}
		}
		return $res;
	}

	public function getPBTInstDetails($pid){
		$res = array();
		if(!empty($pid)){
			$dbHandle = $this->initiateModel();
			$queryCmd = "SELECT ofid.instituteId, ofid.courseId, ofid.fees, ofid.discount, ofid.last_date, ofid.min_qualification, ofid.exams_required, ofid.departmentId, ofid.departmentName, ofid.sessionYear, ofid.otherCourses from OF_InstituteDetails ofid where ofid.id = ?";
			$query = $dbHandle->query($queryCmd, array($pid));
			$res = $query->result_array();
			$res = $res[0];
		}
		return $res;
	}

	public function getPBTConfigDetailsForCourses($courseIds){
		$res = array();
		if(!empty($courseIds)){
			$dbHandle = $this->initiateModel();
			$queryCmd = "SELECT instructionId, seoTitle, seoDescription, seoUrl, instituteId, courseId, status, externalUrl from OF_PBTSeoDetails where courseId in (?) and status='live' and externalUrl='yes'";
			$query = $dbHandle->query($queryCmd,array($courseIds));
			$result = $query->result_array();
			foreach ($result as $key => $value) {
				$res[$value['courseId']] = $value;
			}
		}
		return $res;
	}

	public function addInstDetailsForPBT($instData){
		$dbHandle = $this->initiateModel('write');
		$dbHandle->insert('OF_InstituteDetails', $instData); 
		return true;
	}

	public function editInstDetailsForPBT($instData, $pid){
		$dbHandle = $this->initiateModel('write');
		if($pid > 0 && !empty($instData)){
			$dbHandle->update('OF_InstituteDetails', $instData, array('id' => $pid));
			return true;
		}
		return false;
	}

	public function addInstConfigDetailsForPBT($instConfig){
		$dbHandle = $this->initiateModel('write');
		$dbHandle->insert('OF_PBTSeoDetails', $instConfig);
		return true;
	}

	public function getExternalFormConfigDetails(){
		$res = array();
		$dbHandle = $this->initiateModel();
		$dbHandle->select('instructionId, seoTitle, seoDescription, seoUrl, altImageHeader, instituteId, courseId, status, externalUrl');
		$dbHandle->from('OF_PBTSeoDetails');
		$dbHandle->where('status', 'live');
		$res = $dbHandle->get()->result_array();
		return $res;
	}
}

