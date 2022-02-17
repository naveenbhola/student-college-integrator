<?php
/*
   Copyright 2014 Info Edge India Ltd

   $Author: Pranjul

   $Id: ExcelUploader.php

 */
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	
//Main class for Online Form Enterprise
class ExcelUploader extends MX_Controller {
	
	static $pattern = "/^[a-zA-Z0-9 ()&.,:'_\/+-]+$/";
	static $mandatoryColumn = array('state','collegeName','branchName','ShikshaBranchName','rankType');
	
    function init(){
		$this->load->helper(array('form', 'url','date','image','shikshaUtility','utility_helper'));
	    $this->load->library(array('OnlineFormEnterprise_client','Online_form_client','Online_form_mail_client'));
		$this->userStatus = $this->checkUserValidation();
    }

    function loadExcelUploader(){
    	$userStatus = $this->checkUserValidation();
    	if($userStatus == 'false'){
    		redirect('/enterprise/Enterprise/loginEnterprise');
    	}
    	$this->load->library('session');
		$this->load->view('ExcelUploader/excelUploader');
    }

    function loadData(){
		ini_set('max_execution_time', 60000);
		$examName = $_POST['examname'];
		$inputFileName = $_FILES["datafile"]["tmp_name"];

		$this->load->library('session');	
		$this->validateField($examName, $inputFileName,'loadExcelUploader');

		$this->load->library('common/reader');
		$this->load->library('common/PHPExcel/IOFactory');
		ini_set('memory_limit','500M');

		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);  
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);  
		$objReader->setReadDataOnly(true);  
		/**  Load $inputFileName to a PHPExcel Object  **/  
		$objPHPExcel = $objReader->load($inputFileName);
		$this->load->model('ExcelUploader/ExcelUploaderModel');
		//$count = $objPHPExcel->getSheetCount();
		$count  =  1;
		for($i=0;$i<$count;$i++){
			$objWorksheet = $objPHPExcel->setActiveSheetIndex($i);
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
			$headingsArray = $headingsArray[1];

			//Step 1- Report column if does not exist.
			$this->mandatoryColumn($headingsArray);

			$dataArray = array();
			$r=0;
			for ($row = 2; $row <= $highestRow; ++$row) {
			    $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
			    if ((isset($dataRow[$row])) && ($dataRow[$row] > '')) {
				$j=0; 
				foreach($headingsArray as $columnKey => $columnHeading) {
					if($headingsArray[$columnKey]!=''){

						// find error in file if occurred then show the error msg
						if(trim($dataRow[$row][$columnKey])){
							$error = $this->validateExcel($dataRow[$row][$columnKey], $headingsArray[$columnKey], $row, $examName);
							if(count($error)>0){
								$errorData[$row] = array_merge((array)$errorData[$row], (array)$error[$row]);
							}	
						}else{ //Step 2- Report if no value corresponding to the columns
							$error = $this->reportColumnValue($dataRow[$row][$columnKey], $headingsArray[$columnKey], $row);
							if(count($error)>0){
								$errorData[$row] = array_merge((array)$errorData[$row], (array)$error[$row]);
							}
						}
						$dataArray[$r][$headingsArray[$columnKey]] = $dataRow[$row][$columnKey];
					}
					$j++;
				}
				$r++;
			    }
			}

			//Step 3- Report special characters
			if(count($errorData)>0){
				$errorData['errorType'] = 'Special characters / empty values';
				$this->generateReport($errorData);return;
			}

			//Step 4- Report duplicate rows in excel
			//Step 5- Report if no data corresponding to any round
			$errorData = $this->reportDuplicateRow($dataArray, $examName);
			if(count($errorData)>0){
				$errorData['errorType'] = 'Duplicate rows / empty values';
				$this->generateReport($errorData);return;	
			}

			$cityStateCollegeArr = $this->makeCityStateCollegeRelation($dataArray,$examName);
			//call to remove old data of college predictor
			$this->removeOldDataOfCollegePredictor($examName);
			$this->ExcelUploaderModel->saveDataIntoTable($cityStateCollegeArr,$examName);
			$this->load->view('ExcelUploader/success');
		}
    }

    private function removeOldDataOfCollegePredictor($examName){
    	$this->load->model('ExcelUploader/ExcelUploaderModel');

		//get all college ids from CollegePredictor_Colleges table
		$collegeIds = $this->ExcelUploaderModel->getAllCollegeIdsForExam($examName);

		//get all the branch ids from CollegePredictor_BranchInformation table using college ids
		$branchIds = $this->ExcelUploaderModel->getAllBranchesForColleges($collegeIds);

		//update CollegePredictor_CategoryRoundRankMapping table using branch ids
		$this->ExcelUploaderModel->removeAllRoundInfoForCollegePredictor($branchIds);

		//update CollegePredictor_BranchInformation table using college ids
		$this->ExcelUploaderModel->removeAllBranchOfCollegePredictor($collegeIds);

		//update CollegePredictor_Colleges table using exam name
		$this->ExcelUploaderModel->removeAllCollegesForCollegePredictor($examName);
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

    function mandatoryColumn($columnList){
    	foreach (self::$mandatoryColumn as $key => $column) {
    		if(!in_array($column, $columnList)){
    			$error[] = $column;
    		}
    	}
    	if(count($error)>0){
    		$this->redirect($error);		
    	}
    }
    
    function redirect($error){
    	$this->session->set_flashdata('error',1);
		$this->session->set_flashdata('columnError', $error);
		redirect('/ExcelUploader/ExcelUploader/loadExcelUploader');
    }

    function makeCityStateCollegeRelation($dataArray,$examName){
    	if($examName == 'JEE-Mains' ){
    		$examName = 'JEE-MAINS';	
    	}	
		$cityStateCollegeArr = array();
		$i=0;$tmp=array();$l=0;
		
		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$this->load->config('nationalInstitute/CollegeCutoffConfig',TRUE);
		$configData = $this->config->item('settings','CollegePredictorConfig');
		$collegesConfig = $this->config->item('colleges','CollegeCutoffConfig');
		$configData = array_merge($configData,$collegesConfig);

		foreach($dataArray as $key=>$value){
			$cityName = trim($this->replaceSpecial($value['city']));
			$stateName = trim($this->replaceSpecial($value['state']));
			$cityStateKey = base64_encode(strtolower($cityName.$stateName));
			$cityStateCollegeArr[$cityStateKey]['city'] = $cityName;
			$cityStateCollegeArr[$cityStateKey]['state'] = $stateName;
			$collegeName = trim($this->replaceSpecial($value['collegeName']));
			$branchName = trim($this->replaceSpecial($value['branchName']));
			$shikshaBranchName = trim($value['ShikshaBranchName']);
			$courseId = trim($this->replaceSpecial($value['shikshaCourseId']));
			$rankType = trim($this->replaceSpecial($value['rankType']));
			
			if(!in_array($collegeName,$cityStateCollegeArr[$cityStateKey]['collegeName'][$tmp[$encodeVar]])){
				$encodeVar = base64_encode(strtolower($collegeName.$cityName.$stateName.$courseId));
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
			if(trim($value['Remarks'])==''){
				$value['Remarks'] = '';
			}
			$encodeVar1 = base64_encode(strtolower($collegeName.$cityName.$stateName.$courseId));
			
			if(!in_array($branchName,$cityStateCollegeArr[$cityStateKey]['branchName'][$tmp[$encodeVar1]])){
				$tmp[$encodeVar1] = $i;
				$cityStateCollegeArr[$cityStateKey]['branchName'][$tmp[$encodeVar1]][$l] = $branchName;
				$cityStateCollegeArr[$cityStateKey]['branchAcronym'][$tmp[$encodeVar1]][$l] = $shikshaBranchName;
				$cityStateCollegeArr[$cityStateKey]['shikshaCourseId'][$tmp[$encodeVar1]][$l] = trim($this->replaceSpecial($value['shikshaCourseId']));
				$cityStateCollegeArr[$cityStateKey]['courseCode'][$tmp[$encodeVar1]][$l] = trim($this->replaceSpecial($value['courseCode']));
				$cityStateCollegeArr[$cityStateKey]['instCourseLink'][$tmp[$encodeVar1]][$l] = trim($this->replaceSpecial($value['instCourseLink']));
				$cityStateCollegeArr[$cityStateKey]['instLink'][$tmp[$encodeVar1]][$l] = trim($this->replaceSpecial($value['instLink']));
				$cityStateCollegeArr[$cityStateKey]['remarks'][$tmp[$encodeVar1]][$l] = trim($this->replaceSpecial($value['Remarks']));
				$encodeVar3 = base64_encode(strtolower($branchName.$collegeName.$cityName.$stateName.$courseId));
				$tmp[$encodeVar2] = $i;
				$tmp[$encodeVar3] = $i;
			}
			
			$encodeVar2 = base64_encode(strtolower($branchName.$collegeName.$cityName.$stateName.$courseId));
			
			
		    $totalRounds = $configData[$examName]['totalRound'];
		    $categoryData = $configData[$examName]['categoryData'];	    
		    $categoryData = array_keys($categoryData);			    
			for($round=1,$j=$round-1;$round<=$totalRounds;$round++,$j=$j+count($categoryData)){
				for($num=0;$num<count($categoryData);$num++){
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+$num]['round']= $round;
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+$num]['category']= $categoryData[$num];
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+$num]['closingRank']= trim($this->replaceSpecial($value['R'.$round.'_'.$categoryData[$num]]));
				    $cityStateCollegeArr[$cityStateKey]['roundInfo'][$tmp[$encodeVar1]][$tmp[$encodeVar2]][$value['rankType']][$j+$num]['rankType'] = trim($this->replaceSpecial($value['rankType']));
				}
		    }
		    
			$i++;$l++;
		}
		/*_p($cityStateCollegeArr);
		 die;*/
		return $cityStateCollegeArr;
    }

    //validate excel and report an error in file
    function validateExcel($data, $columnName, $row, $examName){
 		$data = trim($data);
 		$data = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $data); // remove non printed special char

    	$this->load->config('CP/CollegePredictorConfig',TRUE);
    	$this->load->config('nationalInstitute/CollegeCutoffConfig',TRUE);

		$configData = $this->config->item('settings','CollegePredictorConfig');
		$collegesConfig = $this->config->item('colleges','CollegeCutoffConfig');
		$configData = array_merge($configData,$collegesConfig);

		$patternList = array('Rank'=>'[0-9]+','Score'=>'([0-9]+)((.){0,1}([0-9]+))','shikshaCourseId'=>'[0-9]+','default'=>'[0-9]+');
		$allowPattern = ($configData[$examName]['inputType']) ? $patternList[$configData[$examName]['inputType']] : $patternList['default'];
		
        // Step 1 - allow some special char for these $allowedColumn column only
		$allowedColumn = array('state','city','collegeName','branchName','ShikshaBranchName','rankType');
		if(in_array($columnName, $allowedColumn) && preg_match(self::$pattern,$data)==false){
			$reportData[$row] = array('excel Row'=>$row, $columnName=>$data);
		}

		// Step 2 - validate int for except $notIntColumn column
		$notIntColumn  = array('state','city','collegeName','branchName','ShikshaBranchName','instCourseLink','instLink','rankType','shikshaCourseId','Remarks');

		if($columnName == 'shikshaCourseId' && preg_match("/^$patternList[$columnName]$/",$data)==false){
			$reportData[$row] = array('excel Row'=>$row, $columnName=>$data);
		}elseif(!in_array($columnName, $notIntColumn) && preg_match("/^$allowPattern$/",$data)==false){
			$reportData[$row] = array('excel Row'=>$row, $columnName=>$data);
		}

		if(in_array($columnName,array('collegeName','branchName','ShikshaBranchName','rankType')) && empty($data)){			
			$reportData[$row] = array('excel Row'=>$row, $columnName=>'NA');	
		}
		return $reportData;
    }

    // report no data corresponding to these columns
    function reportColumnValue($data, $columnName, $row){
    	if(in_array($columnName,array('collegeName','branchName','ShikshaBranchName','rankType')) && empty($data)){			
			$reportData[$row] = array('excel Row'=>$row, $columnName=>'');	
		}
		return $reportData;
    }

    ////
    //Desc - Compare college predictor excel data to database. If found mismatch then report an error.
    //@uthor - akhter
    ///
    function compareExcelData(){
    	$this->load->config('CP/CollegePredictorConfig',TRUE);
		$this->load->config('nationalInstitute/CollegeCutoffConfig',TRUE);
		$settingArray = $this->config->item('settings','CollegePredictorConfig');

		$this->load->library('session');

		if($this->input->post('submit')){

			$examName = $this->input->post('examName');
			$inputFileName = $_FILES["datafile"]["tmp_name"];
			$this->validateField($examName, $inputFileName);
			$examStatus = $this->isExamLive($examName);
		
			if($examStatus == false){
				$this->session->set_flashdata('error',1);
				$this->showError('No exam found in database.');
			}

			$this->load->library('common/reader');
			$this->load->library('common/PHPExcel/IOFactory');

			ini_set('memory_limit','1000M');
			
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);  
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);  
			$objReader->setReadDataOnly(true);  
			/**  Load $inputFileName to a PHPExcel Object  **/  
			$objPHPExcel = $objReader->load($inputFileName);

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
							$dataArray[$r][$headingsArray[$columnKey]] = $dataRow[$row][$columnKey];
						}
						$j++;
					}
					$r++;
				    }
				}
				
				$cityStateCollegeArr = $this->makeCityStateCollegeRelation($dataArray,$examName);

				foreach ($cityStateCollegeArr as $key => $value) {
					$repoData = $this->matchData($value, $examName);
					if(count(array_keys($repoData))){
						$finalReport[] = $repoData;
						unset($repoData);
					}
				}
			}
			
			$displayData['finalReport']  = empty($finalReport[0]) ? array() : $finalReport;	
			if(count($displayData['finalReport'])<=0){
	    		$this->showError('No error found in database.');
			}
		}
		
		$examList = $settingArray['defaultTabInfo']['DESKTOP'];
		$collegesConfig = $this->config->item('colleges','CollegeCutoffConfig');
		$examList = array_merge($examList,$collegesConfig);

		$displayData['examList'] = $examList;
    	$this->load->view('ExcelUploader/compareExcel',$displayData);
    }

    function showError($msg){
    	$this->session->set_flashdata('msg', $msg);
	    redirect('/ExcelUploader/ExcelUploader/compareExcelData');
    }

    function isExamLive($examName){
    	$this->load->model('ExcelUploader/ExcelUploaderModel','excelModel');
    	return $this->excelModel->isExamLive($examName);
    }

    function validateField($examName, $inputFileName, $functionName='compareExcelData'){
    	if($examName =='' || $inputFileName ==''){
			$this->session->set_flashdata('error',1);
			$this->session->set_flashdata('msg', 'Please select your file / exam.');
    		redirect('/ExcelUploader/ExcelUploader/'.$functionName);
		}
    }

    function matchData($excelData, $examName){
    	$time_start = microtime_float(); $start_memory = memory_get_usage();
    	$this->load->model('ExcelUploader/ExcelUploaderModel','excelModel');
    	$examName = $examName;
    	$cityName = $excelData['city'];
    	$stateName = $excelData['state'];
    	$cityStateKey = base64_encode(strtolower($cityName.$stateName));	
    	$result = $this->excelModel->getPredictorData($examName,$cityName,$stateName);
    	
    	if(empty($result) || count($result)<=0){
    		return;
    	}
    	$dbData = array();
    	$this->prepareDBdata($result, $dbData);
		$this->getRoundInfo($dbData);    	
		$finalExcelData = array();
		$this->formateExcelData($excelData, $finalExcelData);

		unset($result, $excelData);
		
		$report = array();
		$report['isError'] = false;

		// Compare data except roundInfo from excel to database. if error then report an error
		$this->compareData($finalExcelData, $dbData, $report);
		// compare roundinfo from excel to database. if error then report an error
		$this->compareRoundData($finalExcelData, $dbData, $report);
		
		unset($finalExcelData,$dbData);
		
		if($report['isError']){
			$report['city']     = $cityName;
			$report['state']    = $stateName;
			$report['examName'] = $examName;
		}
		unset($report['isError']);
		error_log("CP | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_HOMEPAGE_PERFORMANCE_DATA_FILE_NAME);
	 	return $report;
    }

    function compareData($finalExcelData, $dbData, &$report){
    	foreach ($finalExcelData as $key => $value1) {
			foreach ($value1 as $columnName => $value) {
				// compare unique data for these columns
				if(in_array($columnName, array('collegeName','branchName','branchAcronym','shikshaCourseId')) && (count(array_unique($value)) != count(array_unique($dbData[$key][$columnName])) && count(array_unique($dbData[$key][$columnName]))>0)){
					$diffvalue = $this->getDiffData(array_unique($value), array_unique($dbData[$key][$columnName]));
					$report['excel'][$key]['collegeName']  = $value1['collegeName'][0];
					$report['excel'][$key][$columnName] = count(array_unique($value));
		    		$report['db'][$key]['collegeName']  = $dbData[$key]['collegeName'][0];
		    		$report['db'][$key][$columnName]    = array('count'=>count(array_unique($dbData[$key][$columnName])),'diff'=>$diffvalue);
		    		$report['isError'] = true;
		    		$report['excel'][$key]['type_'.$columnName] = 'aaaa';
		    		unset($diffvalue);
				}else if(!in_array($columnName, array('collegeName','branchName','branchAcronym','shikshaCourseId','roundInfo')) && count($value) != count($dbData[$key][$columnName]) && count($dbData[$key][$columnName])>0){
					$diffvalue = $this->getDiffData($value, $dbData[$key][$columnName]);
					$report['excel'][$key]['collegeName']  = $value1['collegeName'][0];
					$report['excel'][$key][$columnName] = count($value);
					$report['db'][$key]['collegeName']  = $dbData[$key]['collegeName'][0];
		    		$report['db'][$key][$columnName]    = array('count'=>count($dbData[$key][$columnName]),'diff'=>$diffvalue);
		    		$report['isError'] = true;
		    		$report['excel'][$key]['type_'.$columnName] = 'bbbb';
		    		unset($diffvalue);
				}else if(empty($dbData[$key][$columnName])){
					if($columnName !='collegeName'){
						$report['excel'][$key][$columnName]    = count($value);
					}
					$report['excel'][$key]['collegeName']  = $value1['collegeName'][0];
					$report['db'][$key]['collegeName']     = 'NA';
		    		$report['db'][$key][$columnName]       = 'NA';
		    		$report['isError'] = true;
		    		$report['excel'][$key]['type_'.$columnName] = 'cccc';
				}
				// verify shikshacourseId is live or not.
				if($columnName == 'shikshaCourseId'){
					$courseArr = array_unique($dbData[$key][$columnName]);
					$res = $this->getLiveCourse($courseArr);
					if(count($res)>0){
						$report['excel'][$key]['collegeName']  = $value1['collegeName'][0];
						$report['excel'][$key]['SHK_CourseId']    = count(array_unique($value));

						$report['db'][$key]['collegeName']  = $dbData[$key]['collegeName'][0];
						$report['db'][$key]['SHK_CourseId']    = array('count'=>count($res),'diff'=>$res,'isLive'=>'No');
    					$report['isError'] = true;
    					$report['excel'][$key]['type_'.$columnName] = 'ddddd';
					}
				}
			}
		}
    }

    function compareRoundData($finalExcelData, $dbData, &$report){
    	$report = array();
		foreach ($finalExcelData as $collegeIndex => $mainValue) {
			foreach ($mainValue['roundInfo'] as $branchName => $roundValue) {
				foreach ($roundValue as $rankType => $roundData) {
						foreach ($roundData as $round => $roundArr) {
							foreach ($roundArr as $category => $value) {
								$excelRCount = count($value);
								$dbRCount    = count($dbData[$collegeIndex]['roundInfo'][$branchName][$rankType][$round][$category]);
								if($excelRCount != $dbRCount){
									$report['excel'][$collegeIndex]['collegeName'] = $mainValue['collegeName'][0];
									$report['excel'][$collegeIndex]['roundInfo'][$branchName][$rankType][$round][$category] = $excelRCount;
									$report['db'][$collegeIndex]['collegeName'] = $mainValue['collegeName'][0];
				    				$report['db'][$collegeIndex]['roundInfo'][$branchName][$rankType][$round][$category]    = $dbRCount;
				    				$report['isError'] = true;
								}
							}
						}
					
				}
			}
		}
    }

    function prepareDBdata($result, &$dbData){
    	foreach ($result as $k => $value) {
    		foreach ($value as $index => $indexValue) {
    			if(!empty($indexValue) && $indexValue !='NULL'){
    				if($index == 'branchName'){
    					$dbData[base64_encode(strtolower($value['collegeName']))][$index][$value['branchId']] = trim($indexValue);
    				}else{
    					$dbData[base64_encode(strtolower($value['collegeName']))][$index][] = trim($indexValue);
    				}
    			}
    		}
    	}unset($result);
    }

    function getRoundInfo(&$dbData){
    	// get round information
 		$tmpData = $dbData;
		foreach ($tmpData as $index => $value1) {
			$collegRoundData  = $this->excelModel->getRoundInfo(implode(',', array_keys($value1['branchName'])));
			foreach ($collegRoundData as $key => $value) {
    			$dbData[$index]['roundInfo'][$value1['branchName'][$value['branchId']]][$value['rankType']][$value['round']][$value['categoryName']][] = array('rankType'=>$value['rankType'],'round'=>$value['round'],'category'=>$value['categoryName'],'closingRank'=>$value['closingRank']);
    		}
			unset($dbData[$index]['branchId'],$collegRoundData);
		}unset($tmpData);
    }

    function formateExcelData($excelData, &$finalExcelData){

    	foreach ($excelData['collegeName'] as $collegeIndex => $college) {

			foreach ($excelData as $columnName => $value) {

				if(!in_array($columnName, array('city','state'))){

					if(is_array($value[$collegeIndex])>0 && count($value[$collegeIndex])>0){

						foreach ($value[$collegeIndex] as $index => $v) {
							if(!empty($v) && $v !='NULL' && $columnName == 'roundInfo'){
								$uniqueData[$excelData['branchName'][$collegeIndex][$index]] = $this->prepareRoundData($v);	
							}elseif(!empty($v) && $v !='NULL'){
								$uniqueData[$index] = $v;
							}
						}
					}else{
						$uniqueData = $value[$collegeIndex];
					}

					if($columnName == 'collegeName' && !empty($uniqueData)){
						$finalExcelData[base64_encode(strtolower($college))][$columnName][] = $uniqueData;
					}else if($columnName != 'collegeName' && !empty($uniqueData)){
						$finalExcelData[base64_encode(strtolower($college))][$columnName]   = $uniqueData;
					}
					unset($uniqueData);
				}
			}
		}unset($excelData);
    }

    // get diffrence between two given array
    function getDiffData($array1, $array2){
    	if(count($array2) > count($array1)){
    		$result = array_diff($array2, $array1);	
    	}else{
    		$result = array_diff($array1, $array2);	
    	}
    	return $result;
    }

    function prepareRoundData($roundData)
    {
    	$round  = array();
    	foreach ($roundData as $rankType => $roundArr) {
			foreach ($roundArr as $key => $roundValue) {
				$round[$rankType][$roundValue['round']][$roundValue['category']][] = array('rankType'=>$roundValue['rankType'],'round'=>$roundValue['round'],'category'=>$roundValue['category'],'closingRank'=>$roundValue['closingRank']);	
			}
			
		}
		return $round;
    }

    function getLiveCourse($courseArr){
    	$res = $this->excelModel->getLiveCourse($courseArr);
    	return array_diff($courseArr, $res);
    }

    //Desc - if (branchName==ShikshaBranchName==shikshaCourseId==rankType) values are same in the multiple columns then report error
    function reportDuplicateRow($dataArray, $examName){
    	$mainBucket = array();
    	$reportBucket = array();
    	foreach ($dataArray as $key => $row) {
    		$erow = ($key+2);
    		$row['rankType'] = trim($row['rankType']);
    		if((!empty($row['shikshaCourseId']) && !in_array($row['rankType'], $mainBucket['excel'][$row['state']][$row['city']][$row['collegeName']][$row['branchName']][$row['ShikshaBranchName']][$row['shikshaCourseId']]['rankType']))){
    			
    			$mainBucket['excel'][$row['state']][$row['city']][$row['collegeName']][$row['branchName']][$row['ShikshaBranchName']][$row['shikshaCourseId']]['rankType'][]   = $row['rankType'];


    		}else if(empty($row['shikshaCourseId']) && !in_array($row['rankType'], $mainBucket['excel'][$row['state']][$row['city']][$row['collegeName']][$row['branchName']]['rankType'])){

    			$mainBucket['excel'][$row['state']][$row['city']][$row['collegeName']][$row['branchName']]['rankType'][]   = $row['rankType'];
    		}else{
    			$reportBucket[$erow]['excel Row'] = $erow;
    			$reportBucket[$erow]['state'] = $row['state'];
    			$reportBucket[$erow]['city'] = $row['city'];
    			$reportBucket[$erow]['collegeName'] = $row['collegeName'];
    			$reportBucket[$erow]['branchName'] = $row['branchName'];
    			$reportBucket[$erow]['ShikshaBranchName'] = $row['ShikshaBranchName'];
    			$reportBucket[$erow]['shikshaCourseId'] = $row['shikshaCourseId'];
    			$reportBucket[$erow]['rankType'] = $row['rankType'];

    		}
    		
    		foreach ($row as $rColumn => $value) {
    			if(!in_array($rColumn, array('state','city','collegeName','branchName','ShikshaBranchName','shikshaCourseId','instCourseLink','instLink','rankType'))){
    				$tmpRound[$erow]['excel Row'] = $erow;
    				$tmpRound[$erow][$rColumn] = $value;
    			}
    		}
    		unset($row);
    	}
    	// if no round then generate report
    	foreach ($tmpRound as $excelRow => $value) {
    		$tmpValue = $value;
    		unset($value['excel Row']);
    		if(count(array_filter($value))<=0){
    			$reportBucket[$excelRow] = $tmpValue;//$dataArray[($excelRow-2)];
    		}
    	}
    	unset($mainBucket,$tmpRound);
    	return $reportBucket;
    }

    function generateReport($errorData){
    	$this->load->view('ExcelUploader/errorInExcel',array('finalData'=>$errorData));
		unset($errorData);	
    }

     



    function loadDataForSpellChecker(){

		ini_set('memory_limit','400M');
		$file_name = "/var/www/html/shiksha/public/spellCheck_finalWords.xlsx";
        $this->load->library('common/PHPExcel');
        $objReader= PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel=$objReader->load($file_name);
		$objWorksheet = $objPHPExcel->getSheetNames();
		$sheetCount   = $objPHPExcel->getSheetCount();
       	$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
		$highestColumn = $objWorksheet->getHighestColumn();
		$headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
		$headingsArray = $headingsArray[1];
		$duplicateArray = array();
		for ($row = 1; $row <= 4464; ++$row) {
			$dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
			$dataRow = $dataRow[$row]['A'];
			$result[$row]['word'] = $dataRow;
			$result[$row]['creation_date'] = date("Y-m-d H:i:s");								
		}
		$this->load->model('ExcelUploader/ExcelUploaderModel');
		$this->ExcelUploaderModel->saveDataIntoSpellCheckTable($result);
    }

}
?>
