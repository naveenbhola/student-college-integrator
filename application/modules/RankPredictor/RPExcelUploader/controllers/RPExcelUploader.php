<?php
/*
   Copyright 2015 Info Edge India Ltd

   $Author: Ankur

   $Id: RPExcelUploader.php

 */
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	
class RPExcelUploader extends MX_Controller {

    function init(){
	$this->load->helper(array('form', 'url','date','image','shikshaUtility','utility_helper'));
        $this->load->library(array('OnlineFormEnterprise_client','Online_form_client','Online_form_mail_client'));
	$this->userStatus = $this->checkUserValidation();
    }

    function loadExcelUploader(){
	$this->load->view('RPExcelUploader/rpExcelUploader');
    }


    function loadData(){
	$examName = $_POST['examname'];
	//unlink("/tmp/collegepredictor.csv");
	//$targetPath = "/tmp/collegepredictor.csv";
	//move_uploaded_file($_FILES["datafile"]["tmp_name"], $targetPath);

	$this->load->library('common/reader');
	$this->load->library('common/PHPExcel/IOFactory');
	//ini_set('memory_limit','400M');
	
	//$inputFileName = $targetPath;
	$inputFileName = $_FILES["datafile"]["tmp_name"];
	$inputFileType = PHPExcel_IOFactory::identify($inputFileName);  
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);  
	$objReader->setReadDataOnly(true);  
	/**  Load $inputFileName to a PHPExcel Object  **/  
	$objPHPExcel = $objReader->load($inputFileName);
	$this->load->model('RPExcelUploader/RPExcelUploaderModel');
//	$count = $objPHPExcel->getSheetCount();
	$count  =  1;
	for($i=0;$i<$count;$i++){
		$objWorksheet = $objPHPExcel->setActiveSheetIndex($i);
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
		$headingsArray = $headingsArray[1];

		$dataArray = array();
		$r=0;
		for ($row = 2; $row <= $highestRow; ++$row) {
		    $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
		    if ((isset($dataRow[$row])) && ($dataRow[$row] > '')) {
			$j=0; 
			foreach($headingsArray as $columnKey => $columnHeading) {
				if($headingsArray[$columnKey]!=''){
					$headingName = str_replace(" ","",$headingsArray[$columnKey]);
					$dataArray[$r][$headingName] = $dataRow[$row][$columnKey];
				}
				$j++;
			}
			$r++;
		    }
		}
		var_dump($dataArray);
		$this->RPExcelUploaderModel->saveDataIntoTable($dataArray,$examName);
	}
    }

    function replaceSpecial($str){
        $chunked = str_split($str,1);
        $str = "";
        foreach($chunked as $chunk){
            $num = ord($chunk);
            // Remove non-ascii & non html characters
            if ($num >= 32 && $num <= 123){
                    $str.=$chunk;
            }
        }
        return $str;
    }
    
    function makeCityStateCollegeRelation($dataArray,$examName){
		$cityStateCollegeArr = array();
		$i=0;$tmp=array();$l=0;
		
		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$configData = $this->config->item('settings','CollegePredictorConfig');
		
		foreach($dataArray as $key=>$value){
			$cityName = trim($this->replaceSpecial($value['city']));
			$stateName = trim($this->replaceSpecial($value['state']));
			$cityStateKey = base64_encode($cityName.$stateName);
			$cityStateCollegeArr[$cityStateKey]['city'] = $cityName;
			$cityStateCollegeArr[$cityStateKey]['state'] = $stateName;
			$collegeName = trim($this->replaceSpecial($value['collegeName']));
			$branchName = trim($this->replaceSpecial($value['branchName']));
			$shikshaBranchName = trim($value['ShikshaBranchName']);
			if(!in_array($collegeName,$cityStateCollegeArr[$cityStateKey]['collegeName'])){
				$encodeVar = base64_encode($collegeName.$cityName.$stateName);
			        $tmp[$encodeVar] = $i;
				$cityStateCollegeArr[$cityStateKey]['collegeName'][$tmp[$encodeVar]] = $collegeName;
				if(trim($value['collegeGroupName'])==''){
					$cityStateCollegeArr[$cityStateKey]['collegeGroupName'][$tmp[$encodeVar]] = NULL;
				}else{
					$cityStateCollegeArr[$cityStateKey]['collegeGroupName'][$tmp[$encodeVar]] = trim($this->replaceSpecial($value['collegeGroupName']));
				}
			}
	   
			if(trim($value['instCourseLink'])==''){
				$value['instCourseLink'] = 'NULL';
			}
			if(trim($value['instLink'])==''){
				$value['instLink'] = 'NULL';
			}
			$encodeVar1 = base64_encode($collegeName.$cityName.$stateName);
			if(!in_array($branchName,$cityStateCollegeArr[$cityStateKey]['branchName'][$tmp[$encodeVar1]])){
			    				
				$cityStateCollegeArr[$cityStateKey]['branchName'][$tmp[$encodeVar1]][$l] = $branchName;
				$cityStateCollegeArr[$cityStateKey]['branchAcronym'][$tmp[$encodeVar1]][$l] = $shikshaBranchName;
				$cityStateCollegeArr[$cityStateKey]['shikshaCourseId'][$tmp[$encodeVar1]][$l] = trim($this->replaceSpecial($value['shikshaCourseId']));
				$cityStateCollegeArr[$cityStateKey]['courseCode'][$tmp[$encodeVar1]][$l] = trim($this->replaceSpecial($value['courseCode']));
				$cityStateCollegeArr[$cityStateKey]['instCourseLink'][$tmp[$encodeVar1]][$l] = trim($this->replaceSpecial($value['instCourseLink']));
				$cityStateCollegeArr[$cityStateKey]['instLink'][$tmp[$encodeVar1]][$l] = trim($this->replaceSpecial($value['instLink']));
				$encodeVar3 = base64_encode($branchName.$collegeName.$cityName.$stateName);
				$tmp[$encodeVar3] = $i;
			}
			$encodeVar2 = base64_encode($branchName.$collegeName.$cityName.$stateName);
			//JEE-Mains
			if($examName=='JEE-Mains'){
			for($round=1,$j=$round-1;$round<=5;$round++,$j=$j+8){
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['round']= $round;
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['category']= 'Gen';
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_OP']));
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+1]['round']= $round;
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+1]['category']= 'Gen-PD'; 				
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+1]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_OP-PD']));
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+1]['rankType'] = trim($this->replaceSpecial($value['rankType']));

				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+2]['round']= $round;
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+2]['category']= 'SC'; 					
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+2]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_SC']));
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+2]['rankType'] = trim($this->replaceSpecial($value['rankType']));

				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+3]['round']= $round;
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+3]['category']= 'SC-PD'; 				
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+3]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_SC-PD']));
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+3]['rankType'] = trim($this->replaceSpecial($value['rankType']));

				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+4]['round']= $round;
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+4]['category']= 'ST'; 					
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+4]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_ST']));
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+4]['rankType'] = trim($this->replaceSpecial($value['rankType']));

				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+5]['round']= $round;
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+5]['category']= 'ST-PD'; 				
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+5]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_ST-PD']));
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+5]['rankType'] = trim($this->replaceSpecial($value['rankType']));

				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+6]['round']= $round;
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+6]['category']= 'OBC'; 					
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+6]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_OBC']));
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+6]['rankType'] = trim($this->replaceSpecial($value['rankType']));

				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+7]['round']= $round;
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+7]['category']= 'OBC-PD'; 					
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+7]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_OBC-PD']));
				$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+7]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				
			}
			}
		    
			
			//KCET
			if($examName=='KCET'){
			    for($round=1,$j=$round-1;$round<=2;$round++,$j=$j+24){
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['category']= '1G';
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_1G']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+1]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+1]['category']= '1K'; 				
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+1]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_1K']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+1]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+2]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+2]['category']= '1R'; 				
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+2]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_1R']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+2]['rankType'] = trim($this->replaceSpecial($value['rankType']));
    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+3]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+3]['category']= '2AG'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+3]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_2AG']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+3]['rankType'] = trim($this->replaceSpecial($value['rankType']));
    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+4]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+4]['category']= '2AK'; 				
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+4]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_2AK']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+4]['rankType'] = trim($this->replaceSpecial($value['rankType']));
    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+5]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+5]['category']= '2AR'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+5]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_2AR']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+5]['rankType'] = trim($this->replaceSpecial($value['rankType']));
    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+6]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+6]['category']= '2BG'; 				
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+6]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_2BG']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+6]['rankType'] = trim($this->replaceSpecial($value['rankType']));
    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+7]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+7]['category']= '2BK'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+7]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_2BK']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+7]['rankType'] = trim($this->replaceSpecial($value['rankType']));
    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+8]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+8]['category']= '2BR'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+8]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_2BR']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+8]['rankType'] = trim($this->replaceSpecial($value['rankType']));
    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+9]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+9]['category']= '3AG'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+9]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_3AG']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+9]['rankType'] = trim($this->replaceSpecial($value['rankType']));
    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+10]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+10]['category']= '3AK'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+10]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_3AK']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+10]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+11]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+11]['category']= '3AR'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+11]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_3AR']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+11]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+12]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+12]['category']= '3BG'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+12]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_3BG']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+12]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+13]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+13]['category']= '3BK'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+13]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_3BK']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+13]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+14]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+14]['category']= '3BR'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+14]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_3BR']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+14]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+15]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+15]['category']= 'GM'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+15]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_GM']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+15]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+16]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+16]['category']= 'GMK'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+16]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_GMK']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+16]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+17]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+17]['category']= 'GMR'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+17]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_GMR']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+17]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+18]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+18]['category']= 'SCG'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+18]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_SCG']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+18]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+19]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+19]['category']= 'SCK'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+19]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_SCK']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+19]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+20]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+20]['category']= 'SCR'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+20]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_SCR']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+20]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    /*$cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+21]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+21]['category']= 'SNQ'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+21]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_SNQ']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+21]['rankType'] = trim($this->replaceSpecial($value['rankType']));*/
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+21]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+21]['category']= 'STG'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+21]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_STG']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+21]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+22]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+22]['category']= 'STK'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+22]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_STK']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+22]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				    
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+23]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+23]['category']= 'STR'; 					
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+23]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_STR']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+23]['rankType'] = trim($this->replaceSpecial($value['rankType']));
			    }		    
			}
		
			//COMEDK
			if($examName=='COMEDK'){
			    for($round=1,$j=$round-1;$round<=1;$round++){
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['category']= 'GM';
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_GEN']));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j]['rankType'] = trim($this->replaceSpecial($value['rankType']));
			    }
			}
			
			//KEAM
			if($examName=='KEAM'){
			    $totalRounds = $configData['KEAM']['totalRound'];
			    $categoryData = $configData['KEAM']['categoryData'];	    
			    $categoryData = array_keys($categoryData);
			    
			    for($round=1,$j=$round-1;$round<=$totalRounds;$round++,$j=$j+count($categoryData)){
				for($num=0;$num<count($categoryData);$num++){
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+$num]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+$num]['category']= $categoryData[$num];
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+$num]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_'.$categoryData[$num]]));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+$num]['rankType'] = trim($this->replaceSpecial($value['rankType']));

				}
			    }
			}
			$i++;$l++;

		}
		return $cityStateCollegeArr;
    }

}
?>
