<?php
class PercentilePredictorCron extends MX_Controller {
		
		function __construct(){
			parent::__construct();
			$this->validateCron();
		}
		function uploadExcel(){
			$rankpredictorModel = $this->load->model("rpmodel");
			$fileName = "Percentile_Predictor_Final.xlsx";
			$objWorksheet = $this->_getExcelData($fileName);
			$data = array();
			foreach ($objWorksheet->getRowIterator() AS $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = array();
                $flag = 0;
                foreach ($cellIterator as $cell) {
                	$cellValue = $cell->getValue();
 					if ($flag === 0) {
 						if(is_numeric($cellValue)){
 							$flag = 1;
 						}
 						else{
 							$flag = 2;
 						}
 					}
 					if($flag === 1){
 						$columnName = $cell->getColumn();
 						if($columnName == 'A'){
                            $cells['status'] = 'live';
 							$cells['examName'] = "jee-main";
 							$cells['maxScore'] = $cellValue;
 						}
 						else if($columnName == 'B'){
 							$cells['minScore'] = $cellValue;
 						}
 						else if($columnName == 'C'){
 							$cells['minRank'] = $cellValue;
 						}
 						else if($columnName == 'D'){
 							$cells['maxRank'] = $cellValue;
 						}
 						else if($columnName == 'E'){
 							if($cellValue == null){
 								$cellValue = 0;
 							}
 							$cells['slope'] = $cellValue;
 						}
	                }
                }
                if ($flag === 1) {
                	$data[] = $cells;
                }
		    }
		    $rankpredictorModel->insertExcelData($data);
		    echo "Uploading Done";
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
                $objWorksheet = $objPHPExcel->getActiveSheet();
                return $objWorksheet;
        }
	}
?>