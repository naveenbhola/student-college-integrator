<?php
	/**
	 * moving existing config to DB and Uploading IIM Eligibiltiy and Cut-off data to Database
	 */
	class IIMPredictorCron extends MX_Controller {
		
		function __construct(){
			parent::__construct();
			$this->iimpredictormodel = $this->load->model('iimpredictormodel');
            $this->load->library('IIMScoreLib');
			$this->validateCron();
		}
		/***
		*one time cron for moving board data to database 
		*/
		function moveBoardsDataToDB(){
			require_once APPPATH.'modules/CollegePredictor/IIMPredictor/config/ICPConfig.php';

			$boardData = array();
			foreach ($Board as $key => $value) {
				$boardData[] = array('board_name' => $value,'board_alias' => $key);
			}
			$this->iimpredictormodel->insertBoardIntoDataBase($boardData);

			$graduationStreamData = array();

			foreach ($graduationStream as $key => $value) {
				$graduationStreamData[] = array('stream_name' => $value,'stream_alias' => $key);
			}
			$this->iimpredictormodel->insertGraduationStreamsIntoDataBase($graduationStreamData);
		}
		 /**
        * Cron to upload excel in DB
        * @author Satyam Singh
        */
        function uploadExcel(){
            $year = 2018;
            $files  =   array (
                                "article"        => "article.xlsx",
                                "clp_ids"     => "clp_ids_of_institutes.xlsx",
                                "cutoff"=> "cutoff.xlsx",
                                "eligibility"    => "eligibility.xlsx",
                                "formula"  => "formula.xlsx"
                            );

            $excelData = array();

            /**
            * article excel
            */
            $objWorksheet = $this->_getExcelData($files["article"]);
            $articledata = array();
            foreach ($objWorksheet->getRowIterator() AS $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $flag=0;
                foreach ($cellIterator as $cell) {
                    if( $cell->getValue()!=null ){
                        $flag=1;
                    }
                    $cells[] = $cell->getValue();
                }
                if($flag==1){
                    $url = $cells[3];
                    // institute id
                    $id=$this->_getIdFromUrl($url);
                    if($id != null){
                        if(is_numeric($id)){
                            $cells[3] = $id;
                        }
                        $articleId = $this->_getIdFromUrl($cells[1]);
                        if(is_numeric($articleId)){
                            $cells[1] = $articleId;
                        }
                        $articledata[$id] = $cells;
                    }
                    else{
                        $articledata[]=$cells;
                    }
                }
		    }
		    $excelData["article"] = $articledata;


            /**
            * cutoff excel
            */

            $objWorksheet = $this->_getExcelData($files["cutoff"]);
            $cutOffData = array();
            $data = array();
            $prevId = "temp";
            foreach ($objWorksheet->getRowIterator() AS $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $flag=0;
                foreach ($cellIterator as $cell) {
                    if( $cell->getValue()!=null ){
                        $flag=1;
                    }
                    $cells[] = $cell->getValue();
                }
                if($flag==1){
                    $url = $cells[9];
                    // institute id
                    $id=$this->_getIdFromUrl($url);
                    if(is_numeric($id)){
                        $cells[9] = $id;
                    }
                    if($id != $prevId && $id != null){
                        if($prevId != "temp"){
                            $cutOffData[$prevId] = $data;
                            $data = [];
                            $data[] = $cells;
                        }
                        else{
                            $cutOffData["Columns"] = $data;
                            $data = [];
                            $data[] = $cells;

                        }
                        $prevId = $id;
                    }
                    else{
                        $data[] = $cells;
                    }

                }
            }
            if($cutOffData[$prevId] == null){
                $cutOffData[$prevId] = $data;
            }
            $excelData["cutoff"] = $cutOffData;

            /**
            * eligibility excel
            */

            $objWorksheet = $this->_getExcelData($files["eligibility"]);

            $eligibilityData = array();
            $data = array();
            $prevId = "temp";
            foreach ($objWorksheet->getRowIterator() AS $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $flag=0;
                foreach ($cellIterator as $cell) {
                    $columnValue = $cell->getValue();
                    if( $columnValue != null ){
                        $flag=1;
                    }
                    $columnName = $cell->getColumn();
                    /**
                    * If data is given in percent form so we need to change it in numeric form
                    */
                    if($columnName == 'D' || $columnName == 'E' || $columnName == 'F' || $columnName == 'G' || $columnName == 'H' || $columnName == 'I'){
                        /**
                        * If no data for eligibility is given then we will condsider it as 0
                        */
                        if(!is_numeric($columnValue)){
                            $columnValue = 0;
                        }
                        else{
                            if($columnValue < 1){
                                // Converting percentage value
                                $columnValue = $columnValue * 100;
                            }
                        }
                    }
                    $cells[] = $columnValue;
                }
                if($flag==1){
                    $url = $cells[14];
                    // institute id
                    $id=$this->_getIdFromUrl($url);
                    if(is_numeric($id)){
                        $cells[14] = $id;
                    }
                    if($id != $prevId && $id != null){
                        if($prevId != "temp"){
                            $eligibilityData[$prevId] = $data;
                            $data = array();
                            $data[] = $cells;
                        }
                        else{
                            $eligibilityData["Columns"] = $data;
                            $data = [];
                            $data[] = $cells;

                        }
                        $prevId = $id;
                    }
                    else{
                        $data[] = $cells;
                    }

                }
            }
            if( $eligibilityData[$prevId] == null ){
                $eligibilityData[$prevId] = $data;
            }
            $excelData["eligibility"] = $eligibilityData;

            /**
            * formula excel
            */
            //To Be implemented


            /**
            * clps excel
            */

            $objWorksheet = $this->_getExcelData($files["clp_ids"]);
            $clpData = array();
            foreach ($objWorksheet->getRowIterator() AS $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $flag=0;
                foreach ($cellIterator as $cell) {
                    if( $cell->getValue()!=null ){
                        $flag=1;
                    }
                    $cells[] = $cell->getValue();
                }
                if($flag==1){
                    // institute id
                    $id = $cells[0];
                    if($id != null && is_numeric($id)){
                        $clpData[$id] = $cells;
                    }
                    else if($id != null){
                        $clpData["Columns"] = $cells;
                    }
                    else{
                        $clpData[]=$cells;
                    }
                }
            }
            $excelData["clp_ids"] = $clpData;
            $this->iimpredictormodel->insertData($excelData,$year);
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

        private function _getIdFromUrl($url){
            preg_match_all('!\d+!', $url, $matches);
            $id = $matches[0][sizeof($matches[0])-1];
            if($id != null){
                return $id;
            }
            else if($url != null){
                return "Columns";
            }
            else{
                return null;
            }
        }

        /**
         * Update Average Reviews of all institutes in IIM Predictor Module
         * @author Abhinav
         */
        public function updateAverageReviewsForInstitutes() {
            $instituteData  =   $this->iimpredictormodel->getInstitutesMappingData(array(), true);
            if (empty($instituteData)) {
                return;
            }
            $courseIds = array();
            foreach($instituteData as $key=>$value){
                if(!in_array($value['courseId'], $courseIds)){
                    $courseIds[] = $value['courseId'];
                }
            }
            $aggregateReviewResult = $this->iimscorelib->getListingsRating($courseIds, 'course');
            $updateData = array();
            foreach ($instituteData as $key=>$value){
                if (key_exists($value['courseId'], $aggregateReviewResult)
                        && key_exists('aggregateRating', $aggregateReviewResult[$value['courseId']])
                        && key_exists('averageRating', $aggregateReviewResult[$value['courseId']]['aggregateRating'])
                    ){
                    $updateData[$value['instituteId']]  =   array(  'courseId'  =>  $value['courseId'],
                                                                    'avgRating' =>  $aggregateReviewResult[$value['courseId']]['aggregateRating']['averageRating']
                                                                );
                }
            }
            $this->iimpredictormodel->updateAverageReviewOfInstitutes($updateData);
            echo "Execution Done";
            return true;
        }
	}