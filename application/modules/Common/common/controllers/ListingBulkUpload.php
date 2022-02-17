<?php

class ListingBulkUpload extends MX_Controller{

	var $totalDataPointsUpdated = 0;
	
	function __construct() {
		$this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
		$this->model = $this->load->model('common/listingbulkupdatemodel');
		$this->nationalCourseCache = $this->load->library('nationalCourse/NationalCourseCache');
		$this->creationmodel = $this->load->model('common/listingbulkupdatecreationmodel');
	}

	public function listingBulkUpload($file = ""){
    
	    //Define the file location
	    if($file == ""){
	        $fileName = "/var/www/html/shiksha/public/enterpriseDoc/Combined.xlsx";
	    }
	    else{
	        $fileName = "/var/www/html/shiksha/public/enterpriseDoc/$file.xlsx";
	    }
	    
	    
	    $this->load->library('common/reader');
	    $this->load->library('common/PHPExcel/IOFactory');
	    ini_set('memory_limit','2048M');
    
	    //Load the File
	    $inputFileName = $fileName;
	    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objReader->setReadDataOnly(true);
    
	    /**  Load $inputFileName to a PHPExcel Object  **/
	    $objPHPExcel = $objReader->load($inputFileName);
    
	    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	    $highestRow = $objWorksheet->getHighestRow();
	    $highestColumn = $objWorksheet->getHighestColumn();

	    $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
	    $headingsArray = $headingsArray[1];
    
	    //Now, run a loop to fetch each Row
	    for ($row = 2; $row <= $highestRow; ++$row) {
		$dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
		if(isset($dataRow[$row]) && is_array($dataRow[$row]) && count($dataRow[$row])>1){
			$this->updateCourseData($dataRow[$row]);
		}
	    }
	    
	    error_log("Total Data Points Updated = ".$this->totalDataPointsUpdated);
    
	}

	public function updateCourseData($data){
		//1. Check if we have the Course Id / Inst Id + Base CourseId
		if($data['C'] != 'NA' && $data['C'] != 'ALL' && $data['C'] != '' && $data['C'] > 0){
			$this->updateCourseDataInDB($data['C'],$data);
		}
		else{
			$instId = $data['B'];
			$baseCourseId = $data['E'];
			if($instId > 0){
				$preFetchedCourseIds = $this->model->getAllDirectCourses($instId, $baseCourseId);
				foreach ($preFetchedCourseIds as $course){
					$this->updateCourseDataInDB($course['courseId'],$data);
				}
			}
		}
		
	}
	
	public function updateCourseDataInDB($courseId,$data){
		$dataUpdated = false;
		
		$dataUpdated = $this->model->updateCourseData($courseId,$data);
		
		if($dataUpdated > 0){
			//ReIndex the Course
			$this->reindex($courseId);
			echo "<br>Data updated for CourseId = $courseId.";
			error_log("Data updated for CourseId = $courseId.");
			
			$this->totalDataPointsUpdated += $dataUpdated;
		}
	}
		
	public function reindex($courseId){
		modules::run('search/Indexer/addToQueue', $courseId, 'course', 'index');
		$this->nationalCourseCache->removeCourseEligibility($courseId);
	}
	
	public function listingBulkUploadAppDates(){
    
	    //Define the file location
	    $fileName = "/var/www/html/shiksha/public/enterpriseDoc/LDA.xlsx";
	    
	    
	    $this->load->library('common/reader');
	    $this->load->library('common/PHPExcel/IOFactory');
	    ini_set('memory_limit','2048M');
    
	    //Load the File
	    $inputFileName = $fileName;
	    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objReader->setReadDataOnly(true);
    
	    /**  Load $inputFileName to a PHPExcel Object  **/
	    $objPHPExcel = $objReader->load($inputFileName);
    
	    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	    $highestRow = $objWorksheet->getHighestRow();
	    $highestColumn = $objWorksheet->getHighestColumn();

	    $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
	    $headingsArray = $headingsArray[1];
    
	    //Now, run a loop to fetch each Row
	    for ($row = 2; $row <= $highestRow; ++$row) {
		$dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
		if(isset($dataRow[$row]) && is_array($dataRow[$row]) && count($dataRow[$row])>1){
			$this->updateDatesData($dataRow[$row]);
		}
	    }
	
		error_log("Total Courses updates == ".$this->totalNumberOfCourses);
	}

	public function updateDatesData($data){
		$instId = $data['A'];
		$baseCourseId = $data['C'];
		if($instId > 0){
			$preFetchedCourseIds = $this->model->getAllDirectCourses($instId, $baseCourseId);
			foreach ($preFetchedCourseIds as $course){
				$this->updateDatesDataInDB($course['courseId'],$data);
			}
		}
	}

	public function updateDatesDataInDB($courseId,$data){
		$dataUpdated = false;
		
		$dataUpdated = $this->model->updateDatesData($courseId,$data);
		
		if($dataUpdated > 0){
			//ReIndex the Course
			$this->reindex($courseId);
			echo "<br>Data updated for CourseId = $courseId.";
			error_log("Data updated for CourseId = $courseId.");
			
			$this->totalNumberOfCourses++;
		}
	}

	public function listingBulkUploadHierarchy($file = ""){
    
	    //Define the file location
	    if($file == ""){
	        $fileName = "/var/www/html/shiksha/public/enterpriseDoc/heirarchy.xlsx";
	    }
	    else{
	        $fileName = "/var/www/html/shiksha/public/enterpriseDoc/$file.xlsx";
	    }
	    
	    
	    $this->load->library('common/reader');
	    $this->load->library('common/PHPExcel/IOFactory');
	    ini_set('memory_limit','2048M');
    
	    //Load the File
	    $inputFileName = $fileName;
	    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objReader->setReadDataOnly(true);
    
	    /**  Load $inputFileName to a PHPExcel Object  **/
	    $objPHPExcel = $objReader->load($inputFileName);
    
	    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	    $highestRow = $objWorksheet->getHighestRow();
	    $highestColumn = $objWorksheet->getHighestColumn();

	    $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
	    $headingsArray = $headingsArray[1];
    
	    //Now, run a loop to fetch each Row
	    $data = array();
	    $courseIdMain = 0;
	    for ($row = 2; $row <= $highestRow; ++$row) {
		$dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
		if(isset($dataRow[$row]) && is_array($dataRow[$row]) && count($dataRow[$row])>1){
			$courseId = $dataRow[$row]['A'];
			if($courseId == '' || $courseId == $courseIdMain){
				$data[$courseIdMain]['heirarchy'][] = array('streamId' => $dataRow[$row]['B'], 'subStreamId' => $dataRow[$row]['C'], 'specializationId' => $dataRow[$row]['D']);
			}
			else{
				$data[$courseId]['heirarchy'][] = array('streamId' => $dataRow[$row]['B'], 'subStreamId' => $dataRow[$row]['C'], 'specializationId' => $dataRow[$row]['D']);
				$data[$courseId]['keepExisting'] = $dataRow[$row]['E'];
				$courseIdMain = $courseId;
			}
		}
	    }

	    if(count($data) > 0){
			error_log(print_r($data,true));
			$this->updateCourseDataHeirarchy($data);		
	    }
	    
	    error_log("Data Updated for Courses!!!");
    
	}

	function updateCourseDataHeirarchy($data){
		//Validate all the data
		foreach ($data as $courseId => $row){
			foreach ($row['heirarchy'] as $heir){

				error_log(print_r($heir,true));
				$this->validateInteger($heir['streamId'], 'stream');
				$this->checkStream($heir['streamId']);

				$this->validateInteger($heir['subStreamId'], 'substream');
				$this->checkSubStream($heir['subStreamId'],$heir['streamId']);

				$this->validateInteger($heir['specializationId'], 'specialization');
				$this->checkSpecialization($heir['specializationId'], $heir['subStreamId'],$heir['streamId']);
			}
		}

		//Once the data is validated, now add the data in DB
		foreach ($data as $courseId => $row){
			$this->model->addHeirarchy($courseId, $row);
		}

	}	

	public function checkStream($fieldValue){
		if($this->isBlank($fieldValue)){
			error_log("Stream cannot be blank");
			exit;
		}
		$res = $this->creationmodel->checkStream($fieldValue);
		if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
			error_log("Stream does not contain valid Streams");
			exit;
		}
	}

	public function checkSubStream($fieldValue, $streamId){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$res = $this->creationmodel->checkSubStream($fieldValue);
		if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
			error_log("Sub stream value is not correct");
			exit;
		}

		$res = $this->creationmodel->checkSubStreamMapping($fieldValue, $streamId);
		if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
			error_log("Sub stream does not Map with Streams.");
			exit;
		}

	}

	public function checkSpecialization($fieldValue, $substreamId, $streamId){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$res = $this->creationmodel->checkSpecialization($fieldValue);
		if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
			error_log("Specialization value is not correct");
			exit;
		}

		if($substreamId == ''){
			$res = $this->creationmodel->checkSpecializationStreamMapping($fieldValue, $streamId);
			if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
				error_log("Specializatino does not Map with Streams.");
				exit;
			}
		}
		else{
			$res = $this->creationmodel->checkSpecializationSubStreamMapping($fieldValue, $substreamId);
			if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
				error_log("Specializatino does not Map with Sub Streams.");
				exit;
			}
		}
	}

	public function isBlank($fieldValue){
		if($fieldValue == '' && $fieldValue != '0'){
			return true;
		}
		return false;
	}

    public function validateInteger($number, $type) {
        if (!preg_match('/^[0-9]*$/', $number)) {
    		error_log("$type is not Integer. Only Integers are allowed in this field");
	    	exit;
        }
  	}
	
}