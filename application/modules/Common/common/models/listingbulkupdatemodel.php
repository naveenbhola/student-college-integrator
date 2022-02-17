<?php 
class listingbulkupdatemodel extends MY_Model {
	
	var $updateComment = "Listing data updated by Script for Seats/Fees/Eligibility";
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

	public function getAllDirectCourses($instId, $baseCourseId){
		$this->initiateModel('read');
		
		//First fetch the Courses mapped to this Institute & base course
		$courses = array();
		$sql =  "SELECT distinct(sc.course_id) as courseId FROM shiksha_courses sc, shiksha_courses_type_information sci WHERE sc.status = 'live' AND sc.primary_id = ? AND sc.course_id = sci.course_id AND sci.status = 'live' AND sci.base_course = ?";
		$courses = $this->dbHandle->query($sql, array($instId, $baseCourseId))->result_array();
		return $courses;
	}

	public function updateCourseData($courseId,$data){
		$dataUpdated = false;
		
		$this->initiateModel('write');
		$this->dbHandle->trans_start();		
		$comment = '';
		$dataPoint = 0;
		
		//Now, for each course Check the Fees
		if($data['F'] == 'Y'){
			$this->updateSeats($courseId,$data);
			$dataUpdated = true;
			$comment = "Seats";
			$dataPoint++;
		}
		
		if($data['H'] == 'Y'){
			$this->updateFees($courseId,$data);
			$dataUpdated = true;
			$comment .= ($comment == '')?'Fees':', Fees';
			$dataPoint++;
		}

		if($data['M'] == 'Y'){
			$this->updateEligibility($courseId,$data);
			$dataUpdated = true;
			$comment .= ($comment == '')?'Eligibility':', Eligibility';
			$dataPoint++;
		}

		if($dataUpdated){
			$comment .= " data updated during bulk upload";
			$this->updateComment($courseId, $comment);	
		}
		
		$this->dbHandle->trans_complete();
    
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
			echo "Transaction for CourseId $courseId failed.";
			error_log("Transaction for CourseId $courseId failed.");
			return false;
		}
			
		return $dataPoint++;		
	}
	
	public function updateSeats($courseId,$data){
		$totalSeats = $data['G'];

		//Mark Seats data as History
		$this->deleteSeatsBreakup($courseId, $this->udpatedById);

		if($totalSeats > 0){			
			//Add new entry for Seats
			$this->updateTotalSeats($courseId, $totalSeats, $this->udpatedById);
		}
	}

	public function updateFees($courseId,$data){
		$feesYear = $data['I'];
		$feesValue = $data['J'];
		$feesInfo = $data['K'];
		$feesDisclaimer = ($data['L'] == 'Y')?1:0;

		//Mark Fees data as History
		$this->deleteFeesData($courseId, $this->udpatedById);

		//Add new entry for Fees
		if($feesValue > 0){
			$this->udpateFeesData($courseId, $feesYear, $feesValue, $feesDisclaimer, $this->udpatedById);
		}
		
		//Add new entry for Fees Info
		if($feesInfo != ''){
			$this->udpateFeesInfo($courseId, $feesYear, $feesInfo, $feesDisclaimer, $this->udpatedById);			
		}
	}

	public function updateEligibility($courseId,$data){
		$year = $data['N'];
		$description = $data['Y'];
		$exams = $data['X'];
		$scoreType = $data['O'];
		
		$scores['gen12'] = $data['P'];
		$scores['obc12'] = $data['Q'];
		$scores['sc12'] = $data['R'];
		$scores['st12'] = $data['S'];
		$scores['genGrad'] = $data['T'];
		$scores['obcGrad'] = $data['U'];
		$scores['scGrad'] = $data['V'];
		$scores['stGrad'] = $data['W'];

		$this->deleteEligibilityData($courseId, $this->udpatedById);
		
		$this->updateScores($courseId, $scoreType, $scores, $this->udpatedById);
		
		if($exams != ''){			
			$this->udpateEligibilityExams($courseId, $scoreType, $exams, $this->udpatedById);
		}

		if($description != ''){			
			$this->udpateEligibilityDesc($courseId, $year, $description, $this->udpatedById);
		}
	}
	
	public function deleteSeatsBreakup($courseId, $updatedById){
                //$this->initiateModel('write');
                $data['status'] = 'history';
                $data['updated_by'] = $updatedById;
                $this->dbHandle->where('course_id', $courseId);
		$this->dbHandle->where('status','live');
                $status = $this->dbHandle->update('shiksha_courses_seats_breakup', $data);
                return $status;				
	}
	
	public function updateTotalSeats($courseId, $totalSeats, $updatedBy){
                //$this->initiateModel('write');
                $this->dbHandle->set('total_seats',$totalSeats, FALSE);
                $this->dbHandle->where('course_id',$courseId);
		$this->dbHandle->where('status','live');
                $status = $this->dbHandle->update('shiksha_courses');
                return $status;		
	}

	public function deleteFeesData($courseId, $updatedById){
                //$this->initiateModel('write');
                $data['status'] = 'history';
                $data['updated_by'] = $updatedById;
                $this->dbHandle->where('course_id',$courseId);
		$this->dbHandle->where('status','live');
		$this->dbHandle->where('fees_type !=','hostel');
                $status = $this->dbHandle->update('shiksha_courses_fees', $data);
                return $status;				
	}
	

	public function udpateFeesData($courseId, $feesYear, $feesValue, $feesDisclaimer, $udpatedById){
		//$this->initiateModel('write');
		$data               	       	= array();
		$data['course_id']  	       	= $courseId;
		$data['listing_location_id']   	= '-1';
		$data['fees_value']      	= $feesValue;
		$data['fees_unit']      	= '1';
		$data['batch_year']     	= $feesYear;
		$data['period'] 		= 'overall';
		$data['fees_type'] 		= 'total';
		$data['category'] 		= 'general';
		$data['order'] 			= '1';
		$data['fees_disclaimer'] 	= $feesDisclaimer;
		$data['status'] 		= 'live';		
		$data['updated_by'] 		= $udpatedById;		
		$this->dbHandle->insert('shiksha_courses_fees',$data);
	}

	public function udpateFeesInfo($courseId, $feesYear, $feesInfo, $feesDisclaimer, $udpatedById){
		//$this->initiateModel('write');
		$data                      	= array();
		$data['course_id']        	= $courseId;
		$data['listing_location_id'] 	= '-1';
		$data['batch_year']     	= $feesYear;
		$data['order'] 			= '0';
		$data['other_info']		= $feesInfo;
		$data['fees_disclaimer'] 	= $feesDisclaimer;
		$data['status'] 		= 'live';		
		$data['updated_by'] 		= $udpatedById;		
		$this->dbHandle->insert('shiksha_courses_fees',$data);
	}

	public function updateComment($courseId, $comment){
		$this->initiateModel('write');
		$data                  		= array();
		$data['userId']        		= $this->udpatedById;
		$data['comments'] 		= $comment;
		$data['tabUpdated']     	= 'course';
		$data['listingId'] 		= $courseId;
		$this->dbHandle->insert('listingCMSUserTracking',$data);		
	}
	
	public function deleteEligibilityData($courseId, $updatedById){
                //$this->initiateModel('write');
		
                $data['status'] = 'history';
                $data['updated_by'] = $updatedById;
                $this->dbHandle->where('course_id',$courseId);
		$this->dbHandle->where('status','live');
                $status = $this->dbHandle->update('shiksha_courses_eligibility_main', $data);

                $this->dbHandle->where('course_id',$courseId);
		$this->dbHandle->where('status','live');
                $status = $this->dbHandle->update('shiksha_courses_eligibility_score', $data);

                $this->dbHandle->where('course_id',$courseId);
		$this->dbHandle->where('status','live');
                $status = $this->dbHandle->update('shiksha_courses_eligibility_exam_score', $data);

                $this->dbHandle->where('course_id',$courseId);
		$this->dbHandle->where('status','live');
                $status = $this->dbHandle->update('shiksha_courses_eligibility_base_entities', $data);

                return $status;				
	}

	public function updateScores($courseId, $scoreType, $scores, $udpatedById){
		//$this->initiateModel('write');
		
		$dataArray = array();
		foreach ($scores as $key=>$value){
			
			if($value != ''){
				$data                      	= array();
				$data['course_id']        	= $courseId;
	
				switch ($key){
					case 'gen12':
							$data['standard']     		= 'XII';
							$data['category'] 		= 'general';
							break;
					case 'obc12':
							$data['standard']     		= 'XII';
							$data['category'] 		= 'obc';
							break;
					case 'sc12':
							$data['standard']     		= 'XII';
							$data['category'] 		= 'sc';
							break;
					case 'st12':
							$data['standard']     		= 'XII';
							$data['category'] 		= 'st';
							break;
					case 'genGrad':
							$data['standard']     		= 'graduation';
							$data['category'] 		= 'general';
							break;
					case 'obcGrad':
							$data['standard']     		= 'graduation';
							$data['category'] 		= 'obc';
							break;
					case 'scGrad':
							$data['standard']     		= 'graduation';
							$data['category'] 		= 'sc';
							break;
					case 'stGrad':
							$data['standard']     		= 'graduation';
							$data['category'] 		= 'st';
							break;
				}
	
				$data['value']	 		= $value;
				$data['unit']	 		= 'percentage';
				$data['status'] 		= 'live';		
				$data['max_value']	 		= '100';
				$data['updated_by'] 		= $udpatedById;
				$dataArray[] = $data;
			}
		}

		if(count($dataArray) > 0){
			$this->dbHandle->insert_batch('shiksha_courses_eligibility_score',$dataArray);
		}
	}
	
	public function udpateEligibilityExams($courseId, $scoreType, $exams, $udpatedById){
		//$this->initiateModel('write');
		
		//First fetch the Exam names		
		$examArray = explode(',',$exams);
		$sql =  "SELECT id, name FROM exampage_main WHERE status = 'live' AND id IN (?)";
		$examData = $this->dbHandle->query($sql, array($examArray))->result_array();
		
		$dataArray = array();
		foreach ($examData as $exam){
			$data                      	= array();
			$data['course_id']        	= $courseId;
			$data['exam_id']     		= $exam['id'];
			$data['exam_name'] 		= $exam['name'];
			$data['status'] 		= 'live';		
			$data['updated_by'] 		= $udpatedById;
			$dataArray[] = $data;
		}
		
		if(count($dataArray) > 0){
		    $this->dbHandle->insert_batch('shiksha_courses_eligibility_exam_score',$dataArray);
		}
		
	}
	
	public function udpateEligibilityDesc($courseId, $year, $description, $udpatedById){
		//$this->initiateModel('write');
		$data                      	= array();
		$data['course_id']        	= $courseId;
		$data['batch_year']     	= $year;
		$data['description'] 		= $description;
		$data['status'] 		= 'live';		
		$data['updated_by'] 		= $udpatedById;		
		$this->dbHandle->insert('shiksha_courses_eligibility_main',$data);
	}

	public function updateDatesData($courseId,$data){
		$dataUpdated = false;
		
		$this->initiateModel('write');
		$this->dbHandle->trans_start();		
		
		//Now, for each course update the Application dates
		$this->deleteAppDate($courseId, $this->udpatedById);
		
		$startDate = $data['B'];
                $startDate = ($startDate - 25569) * 86400;
                $startDate = gmdate("d/m/Y", $startDate);
		$startDate = explode('/',$startDate);
		$this->updateAppDate($courseId, $startDate[1], $startDate[0], $startDate[2], $this->udpatedById);
		
		$comment = "Last Date of Application updated via Bulk";
		$this->updateComment($courseId, $comment);	
		
		$this->dbHandle->trans_complete();
    
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
			echo "Transaction for CourseId $courseId failed.";
			error_log("Transaction for CourseId $courseId failed.");
			return false;
		}
			
		return true;		
	}

	public function deleteAppDate($courseId, $updatedById){
                //$this->initiateModel('write');
                $data['status'] = 'history';
                $data['updated_by'] = $updatedById;
                $this->dbHandle->where('course_id',$courseId);
		$this->dbHandle->where('status','live');
		$this->dbHandle->where('event_name','Application Submit Date');
                $status = $this->dbHandle->update('shiksha_courses_important_dates', $data);
                return $status;				
	}
	

	public function updateAppDate($courseId, $startDate, $startMonth, $startYear, $udpatedById){
		//$this->initiateModel('write');
		$data               	       	= array();
		$data['course_id']  	       	= $courseId;
		$data['event_name']  	       	= 'Application Submit Date';
		$data['start_date']   		= $startDate;
		$data['start_month']      	= $startMonth;
		$data['start_year']      	= $startYear;
		$data['status'] 		= 'live';		
		$data['updated_by'] 		= $udpatedById;		
		$this->dbHandle->insert('shiksha_courses_important_dates',$data);
	}


	public function addHeirarchy($courseId, $data){
		$this->initiateModel('write');

		//First get the data.
		$data_hei = array();
		$sql =  "SELECT * FROM shiksha_courses_type_information WHERE status = 'live' AND course_id = ?";
		$data_hei = $this->dbHandle->query($sql, array($courseId))->result_array();

		//Check if there are any duplicates
		/*
		foreach ($data_hei as $row){
			if($row['type'] == 'entry' && strtolower($data['keepExisting']) == 'y'){
				foreach ($data['heirarchy'] as $hei){
										
				}
			}
		}
		*/

		//Fetch the values
		$type = 'entry';
		$credential = 0;
		$course_level = 0;
		foreach ($data_hei as $row){
			if($row['type'] == 'entry'){
				$credential = $row['credential'];
				$course_level = $row['course_level'];
			}
		}

		//If no existing value to be kept, delete the old ones.
		if(strtolower($data['keepExisting']) == 'n'){
			$uData = array();
	                $uData['status'] = 'history';
        	        $uData['updated_by'] = $this->updatedById;
	                $this->dbHandle->where('course_id',$courseId);
	                $this->dbHandle->where('primary_hierarchy',0);
			$this->dbHandle->where('status','live');
                	$status = $this->dbHandle->update('shiksha_courses_type_information', $uData);
		}

		//Now insert the values
		$dataArray = array();
		foreach ($data['heirarchy'] as $hei){
			$dataA                      	= array();
			$dataA['course_id']        	= $courseId;
			$dataA['type']     		= $type;
			$dataA['credential'] 		= $credential;
			$dataA['course_level'] 		= $course_level;
			$dataA['base_course'] 		= 0;
			$dataA['stream_id'] 		= isset($hei['streamId'])?$hei['streamId']:0;
			$dataA['substream_id'] 		= isset($hei['subStreamId'])?$hei['subStreamId']:0;
			$dataA['specialization_id'] 	= isset($hei['specializationId'])?$hei['specializationId']:0;
			$dataA['primary_hierarchy'] 	= 0;
			$dataA['status'] 		= 'live';		
			$dataA['updated_by'] 		= $this->udpatedById;
			$dataArray[] = $dataA;
		}

		//error_log(print_r($dataArray,true));		
		if(count($dataArray) > 0){
		    $this->dbHandle->insert_batch('shiksha_courses_type_information',$dataArray);
		}
	}	
	
}
?>