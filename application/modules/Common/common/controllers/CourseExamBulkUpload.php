<?php

class CourseExamBulkUpload extends MX_Controller{

	var $totalDataPointsUpdated = 0;
	var $totalNumberOfCourses = 0;
	
	function __construct() {
		$this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
		$this->model = $this->load->model('common/courseexambulkuploadmodel');
	}

	public function courseExamBulkUpload(){
    
	    //Define the file location
	    $fileName = "/var/www/html/shiksha/public/enterpriseDoc/ExamEligibility.xlsx";
	    //$fileName = "/var/www/html/shiksha/public/enterpriseDoc/Book1.xlsx";
	    
	    
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
			$this->updateExamData($dataRow[$row]);
		}
	    }
	    
	    error_log("Total Data Points Updated = ".$this->totalDataPointsUpdated);
    
	}

	public function updateExamData($data){
		if($data['A'] != 'NA' && $data['A'] != 'ALL' && $data['A'] != '' && $data['A'] > 0){
			$this->updateExamDataInDB($data['A'],$data);
		}		
	}
	
	public function updateExamDataInDB($courseId,$data){
		$dataUpdated = false;
		
		$dataUpdated = $this->model->updateExamData($courseId,$data);
		
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
	}

	public function reindexInstitute($instId){
		modules::run('search/Indexer/addToQueue', $instId, 'institute', 'index');
	}

	public function shortNameBulkUpload(){
    
	    //Define the file location
	    $fileName = "/var/www/html/shiksha/public/enterpriseDoc/ShortNameIULP.xlsx";    
	    
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
			$this->updateShortName($dataRow[$row]);
		}
	    }
	    
	    error_log("Total Institutes updated = ".$this->totalDataPointsUpdated);
    
	}

	public function updateShortName($data){
		$instId = $data['A'];
		$shortName = $data['B'];
		$this->model->updateShortName($instId, $shortName);		
		//ReIndex the Course
		$this->reindexInstitute($instId);
		echo "<br>Data updated for UILP = $instId.";
		error_log("Data updated for UILP = $instId.");
		$this->totalDataPointsUpdated++;
	}
	
}