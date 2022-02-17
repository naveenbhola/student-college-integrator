<?php 
class courseexambulkuploadmodel extends MY_Model {
	
	var $updateComment = "Eligibility data updated by Script for Exams";
	var $udpatedById = "11";

	function __construct() {
		parent::__construct('default');
	}

	private function initiateModel($mode = "write", $module = ''){
		if($mode == 'read') {
		    $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
		    $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function updateExamData($courseId,$data){
		$dataUpdated = false;
		
		$this->initiateModel('write');
		$this->dbHandle->trans_start();		
		$comment = '';
		$dataPoint = 0;

		//Now, for each course, Check the Action
		switch ($data['E']){
			case 'Redirection to SA page' : $this->redirectionToSA($courseId,$data);
							$dataUpdated = true;
							break;
			case 'To be deleted' 		: $this->deleteExamMapping($courseId,$data);
							$dataUpdated = true;
							break;
			case 'Redirection to SD page' : $this->redirectionToSD($courseId,$data);
							$dataUpdated = true;
							break;			
		}
		if($dataUpdated){
			$this->updateComment($courseId);
			$dataPoint++;
		}
		
		$this->dbHandle->trans_complete();
    
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
			echo "Transaction for CourseId $courseId failed.";
			error_log("Transaction for CourseId $courseId failed.");
			return false;
		}
			
		return $dataPoint;		
	}

	public function redirectionToSA($courseId,$data){
		$examId = $data['B'];
		$examName = $data['C'];
		
		switch ($examName){
			case 'GMAT':
					if($examId == 0){
						$this->updateEntry($courseId, $examId, $examName, '310');
					}
					break;
			case 'SAT':
					if($examId == 0){
						$this->updateEntry($courseId, $examId, $examName, '418');
					}
					break;
			case 'GRE':
					if($examId == 0){
						$this->updateEntry($courseId, $examId, $examName, '2494');
					}
					break;
			case 'IELTS':
					if($examId == 0){
						$this->updateEntry($courseId, $examId, $examName, '3300');
					}
					break;
			case 'Subject test Score (SAT)':
					if($examId == 0){
						$this->updateEntry($courseId, $examId, $examName, '418');
					}
					break;
		}
	}

	public function updateEntry($courseId,$examId,$examName,$newExamId){
		$updateData = array();

		$updateData['exam_id']     		= $newExamId;
		$updateData['exam_name']     		= NULL;
		$updateData['updated_by'] 		= $this->udpatedById;

		$this->dbHandle->where('exam_id',$examId);
		if($examName != 'NULL'){
			if($examName == 'SAT'){
				$this->dbHandle->where_in('exam_name',array('SAT','Scholastic Aptitude Test (SAT)'));
			}
			else{
				$this->dbHandle->where('exam_name',$examName);
			}
		}
		$this->dbHandle->where('course_id',$courseId);
		$this->dbHandle->where('status','live');

		$status = $this->dbHandle->update('shiksha_courses_eligibility_exam_score', $updateData);			
	}
	
	public function deleteExamMapping($courseId,$data){
		$examId = $data['B'];
		$examName = $data['C'];
		
		$updateData = array();
                $updateData['status'] = 'history';
                $updateData['updated_by'] = $this->updatedById;
                $this->dbHandle->where('exam_id',$examId);
		if($examName != 'NULL'){
			$this->dbHandle->where('exam_name',$examName);
		}
                $this->dbHandle->where('course_id',$courseId);
		$this->dbHandle->where('status','live');
                $status = $this->dbHandle->update('shiksha_courses_eligibility_exam_score', $updateData);
	}

	public function redirectionToSD($courseId,$data){
		$examId = $data['B'];
		$examName = $data['C'];

		if($examId == 0){
			$updateData = array();

			$updateData['exam_id']     		= $data['I'];
			$updateData['exam_name']     		= NULL;
			$updateData['updated_by'] 		= $this->udpatedById;

			$this->dbHandle->where('exam_id',$examId);
			if($examName != 'NULL'){
				$this->dbHandle->where('exam_name',$examName);
			}
			$this->dbHandle->where('course_id',$courseId);
			$this->dbHandle->where('status','live');

			$status = $this->dbHandle->update('shiksha_courses_eligibility_exam_score', $updateData);			
		}		
	}
	
	public function updateComment($courseId){
		$this->initiateModel('write');
		$data                  		= array();
		$data['userId']        		= $this->udpatedById;
		$data['comments'] 		= $this->updateComment;
		$data['tabUpdated']     	= 'course';
		$data['listingId'] 		= $courseId;
		$this->dbHandle->insert('listingCMSUserTracking',$data);		
	}

	public function updateShortName($instId, $shortName){
		$this->initiateModel('write');
		if($shortName != "" && $instId > 0){
			$updateData = array();

			$updateData['short_name']     		= trim($shortName);
			$updateData['updated_by'] 		= $this->udpatedById;
			$updateData['updated_on']		= date('Y-m-d H:i:s');
			
			$this->dbHandle->where('listing_id',$instId);
			$this->dbHandle->where('status','live');

			$status = $this->dbHandle->update('shiksha_institutes', $updateData);			


			$data                  		= array();
			$data['userId']        		= $this->udpatedById;
			$data['comments'] 		= "Shortname updated by Script";
			$data['tabUpdated']     	= 'institute';
			$data['listingId'] 		= $instId;
			$this->dbHandle->insert('listingCMSUserTracking',$data);		
		}		
	}		
}
?>