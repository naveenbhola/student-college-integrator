<?php

	class CollegePredictorCron extends MX_Controller {

		function __construct(){
			parent::__construct();
			$this->validateCron();
			$this->collegeshortlistmodel = $this->load->model('collegeshortlist');
		}

		function uploadExcel(){
			ini_set('max_execution_time', -1);
			ini_set('memory_limit',"2500M");
			$fileName = "Combined_tech_ready_v9.xlsx";
			$objPHPExcel = $this->_getExcelData($fileName);
			$validated = $this->_validateExcel($objPHPExcel);

			if($validated == false){
				error_log("Excel is invalid");
				return ;
			}

			error_log("Excel is valid");

			$objWorksheets = $objPHPExcel->getSheetNames();
			$dbUpdateData = array();
			$dbAllUpdateData = array();
			$batchCount = 1;
			foreach ($objWorksheets as $sheetIndex => $sheetName) {
				if(!is_numeric($sheetName)){
					error_log("Invalid exam Id :: ".$sheetName."</br> ".$sheetName."</br>");
				}
				$objWorksheet = $objPHPExcel->setActiveSheetIndex($sheetIndex);
				$highestColumn = $objWorksheet->getHighestColumn();
				$highestRow = $objWorksheet->getHighestRow();
				$columnNamesRow = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
				$columnRoundMapping = array();
				$categories = array();
				foreach ($columnNamesRow[1] as $key => $columnName) {
					if ($columnName[0] === 'R') {
						$splitedString = explode('-', $columnName);
						$allInfo = array();
						$substr = substr($splitedString[0],1);
						if(!isset($splitedString[0]) || !is_numeric($substr) || !isset($splitedString[1]) || !isset($splitedString[2])) {
							error_log("Invalid Column Name = ".$columnName[0]);
							$validated = false;
						}
						$allInfo['round'] = $substr;
						$allInfo['category'] = $splitedString[1];
						if(!in_array($splitedString[1], $categories)){
							$categories[] = $splitedString[1];
						}
						$allInfo['rank_type'] = ($splitedString[2] === "AI")?"allindia":"homestate";
						if( $splitedString[3] === 'F'){
							$allInfo['gender_category'] = "female";
						}
						else{
							$allInfo['gender_category'] = "all";
						}

						if(!empty($splitedString[4])) {
							$allInfo['subcategory'] = $splitedString[4];
						}

						$columnRoundMapping[$key]=$allInfo;
					}
				}

				$categoryIds = $this->collegeshortlistmodel->getCategories($categories,$sheetName);
				//_p($categoryIds);die;
				for ($row=2; $row <= $highestRow; $row++) { 
					$dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true)[$row];

					foreach ($dataRow as $columnIndex => $columnValue) {
						if($dataRow['A'] != $sheetName){
							error_log("Invalid exam Id in sheet = ".$sheetName.",Row = ".$row."</br> ".$sheetName."</br>");
							$validated = false;
						}
						if ($columnIndex === 'A') {
							$dbUpdateData['exam_id'] = $columnValue;
						}
						else if ($columnIndex === 'B') {
							$dbUpdateData['course_id'] = $columnValue;
						}
						else if ($columnIndex === 'D') {
							$dbUpdateData['remarks'] = !empty(trim($columnValue)) ? trim($columnValue) : null;
						}
						else if (isset($columnRoundMapping[$columnIndex]) && $columnValue > 0) {
							$dbUpdateData['category_id'] = $categoryIds[$columnRoundMapping[$columnIndex]['category']];
							$dbUpdateData['rank_type'] = $columnRoundMapping[$columnIndex]['rank_type'];
							$dbUpdateData['gender_category'] = $columnRoundMapping[$columnIndex]['gender_category'];
							$dbUpdateData['subcategory'] = !empty($columnRoundMapping[$columnIndex]['subcategory']) ? $columnRoundMapping[$columnIndex]['subcategory'] : null;
							$dbUpdateData['round'] = $columnRoundMapping[$columnIndex]['round'];
							$dbUpdateData['value'] = number_format($columnValue, 2);
							if($validated){
								$dbAllUpdateData[] = $dbUpdateData;
							}
						}
					}
				}
				$this->collegeshortlistmodel->uploadData($dbAllUpdateData,$sheetName);
				unset($dbAllUpdateData);
			}
			error_log("\n\n Final Upload Done\n\n");
		}

        private function _getExcelData($fileName){
                $directory = __dir__;
                $location = "/../config/";
                $excelPath = $directory.$location.$fileName;
                $this->load->library('common/PHPExcel');
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                $objReader->setReadDataOnly(true);
                try{
                    $objPHPExcel  = $objReader->load($excelPath);
                }
                catch(Exception $e){
                    echo "Exception Message:<br/>".$e->getMessage();
                    die;
                }
                return $objPHPExcel;
        }

        private function _validateExcel(&$objPHPExcel){
        	$validated = true;
        	$objWorksheets = $objPHPExcel->getSheetNames();
			foreach ($objWorksheets as $sheetIndex => $sheetName) {
				if(!is_numeric($sheetName)){
					error_log("Invalid exam Id :: ".$sheetName."</br> ".$sheetName."</br>");
					$validated = false;
				}
				$objWorksheet = $objPHPExcel->setActiveSheetIndex($sheetIndex);
				$highestColumn = $objWorksheet->getHighestColumn();
				$highestRow = $objWorksheet->getHighestRow();
				$columnNamesRow = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
				$categories = array();
				foreach ($columnNamesRow[1] as $key => $columnName) {
					if ($columnName[0] === 'R') {
						$splitedString = explode('-', $columnName);
						$allInfo = array();
						$substr = substr($splitedString[0],1);
						if(!isset($splitedString[0]) || !is_numeric($substr) || !isset($splitedString[1]) || !isset($splitedString[2])) {
							error_log("Invalid Column Name for sheet name ".$sheetName." = ".$columnName);
							$validated = false;
						}
						if(!in_array($splitedString[1], $categories)){
							$categories[] = $splitedString[1];
						}
					}
				}
				$categoryIds = $this->collegeshortlistmodel->getCategories($categories,$sheetName);
				if(sizeof($categoryIds) != sizeof($categories)){
					foreach ($categories as $key => $value) {
						if(!is_numeric($categoryIds[$value]) || $categoryIds[$value] <= 0 ){
							error_log("Invalid category short name for sheet name ".$sheetName." = ".$value);
							$validated = false;
						}
					}
				}
				for ($row=2; $row <= $highestRow; $row++) { 
					$dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true)[$row];

					foreach ($dataRow as $columnIndex => $columnValue) {
						if($dataRow['A'] != $sheetName && $columnIndex == 'A'){
							error_log("Invalid exam Id in sheet = ".$sheetName.",Row = ".$row."</br> ".$sheetName."</br>");
							$validated = false;
						}
					}
				}
			}
			return $validated;
        }
	}
?>