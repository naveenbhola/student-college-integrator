<?php

class CitiesBulkCreation extends MX_Controller{

	var $states = array();
	var $updateCache = false;

        function __construct() {
                $this->model = $this->load->model('common/citiesbulkcreationmodel');
        }

        public function citiesCreation($fileName = ''){

            //Define the file location
	    if($fileName == ''){
		$fileName = "Cities";
	    }
            $fileName = "/var/www/html/shiksha/public/enterpriseDoc/$fileName.xlsx";


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

            for ($row = 2; $row <= $highestRow; ++$row) {
		error_log("Row: ".$row);
                $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
                if(isset($dataRow[$row]) && is_array($dataRow[$row]) && count($dataRow[$row])>1){
			$response = $this->createCity($dataRow[$row]);
                }
            }

	    if($this->updateCache){
		Modules::run("nationalCourse/Demo/clearLocationCache ");
	    }
        }

        public function createCity($data){

		$cityName = $data['A'];
		$stateArray = $this->fetchStates();
		if($stateId = $this->validateState($stateArray, $data['B'])){
			if($this->validateCityName($cityName)){
				$this->createCityDB($cityName, $stateId);
				$this->updateCache = true;
				error_log("City created with Name = $cityName");
			}
			else{
				error_log("This cityname already exists in India.");
			}
		}
		else{
			error_log("StateName is not valid");
		}
        }


        public function fetchStates($data){
		if(count($this->states) == 0){
			$this->states = $this->model->getStates();
		}
		return $this->states;
        }

	public function validateState($stateArray, $stateName){
		foreach ($stateArray as $state){
			if($stateName == $state['state_name']){
				return $state['state_id'];
			}
		}
		return false;
	}

	public function validateCityName($cityName){
		$returnVal = $this->model->checkCityName($cityName);
		if(count($returnVal) > 0){
			return false;
		}
		return true;
	}

	public function createCityDB($cityName, $stateId){
		$this->model->createCity($cityName, $stateId);
	}

}