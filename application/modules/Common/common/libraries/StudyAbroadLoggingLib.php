<?php

class StudyAbroadLoggingLib {

    private $scriptStartTime;
    private $scriptEndTime;
    private $initialMemoryUsed;
    private $finalMemoryUsed;
    private $entityName;
    private $outputData = array();
    private $keywordSearched;
    private $urlCalled = '';
    private $memoryOccupiedByObject;
    static $searchLoggingObjectsHolder = array();
    static $userValidation;

    public function __construct() {
        
    }
    public function getMemoryOccupiedByObject() {
        return $this->memoryOccupiedByObject;
    }

    public function setMemoryOccupiedByObject($memoryOccupiedByObject) {
        $this->memoryOccupiedByObject = $memoryOccupiedByObject;
    }

    public function getUrlCalled() {
        return $this->urlCalled;
    }

    public function setUrlCalled($urlCalled) {
        $this->urlCalled = $urlCalled;
    }

    public function getScriptStartTime() {
        return $this->scriptStartTime;
    }

    public function getScriptEndTime() {
        return $this->scriptEndTime;
    }

    public function getInitialMemoryUsed() {
        return $this->initialMemoryUsed;
    }

    public function getFinalMemoryUsed() {
        return $this->finalMemoryUsed;
    }

    public function getEntityName() {
        return $this->entityName;
    }

    public function getOutputData() {
        return $this->outputData;
    }

    public function getKeywordSearched() {
        return $this->keywordSearched;
    }

    public function setScriptStartTime($scriptStartTime) {
        $this->scriptStartTime = $scriptStartTime;
    }

    public function setScriptEndTime($scriptEndTime) {
        $this->scriptEndTime = $scriptEndTime;
    }

    public function setInitialMemoryUsed($initialMemoryUsed) {
        $this->initialMemoryUsed = $initialMemoryUsed;
    }

    public function setFinalMemoryUsed($finalMemoryUsed) {
        $this->finalMemoryUsed = $finalMemoryUsed;
    }

    public function setEntityName($entityName) {
        $this->entityName = $entityName;
    }

    public function setOutputData($outputData) {
        $this->outputData = $outputData;
    }

    public function setKeywordSearched($keywordSearched) {
        $this->keywordSearched = $keywordSearched;
    }

    public static function getLoggerInstance($entityName, $keywordSearched) {
        $ciInstance = &get_instance();
        if($ciInstance->security->xss_clean($_GET['enableDebugging'])!=1){
            return 0;
        }
        unset($ciInstance);
        $initialMemoryForCalculatingMemoryOccupiedByObject=memory_get_usage();
        $loggingObject = new studyAbroadLoggingLib();
        $finalMemoryForCalculatingMemoryOccupiedByObject=memory_get_usage();
        $loggingObject->setMemoryOccupiedByObject($finalMemoryForCalculatingMemoryOccupiedByObject-$initialMemoryForCalculatingMemoryOccupiedByObject);
        $loggingObject->setEntityName($entityName);
        $loggingObject->setInitialMemoryUsed(memory_get_usage());
        $loggingObject->setScriptStartTime(microtime(TRUE));
        $loggingObject->setKeywordSearched($keywordSearched);
        return $loggingObject;
    }

    public function completeLogging($outputData, $urlCalled) {
        $ciInstance = &get_instance();
        if($ciInstance->security->xss_clean($_GET['enableDebugging'])!=1){
            return 0;
        }
		unset($ciInstance);
        $this->setFinalMemoryUsed(memory_get_usage());
        $this->setScriptEndTime(microtime(TRUE));
        $this->setOutputData($outputData);
        $this->setUrlCalled($urlCalled);
        $this->addLoggingObjectToHolder();
    }

    public function addLoggingObjectToHolder() {
        array_push(self::$searchLoggingObjectsHolder, $this);
    }
    public function addUserValidationData($userValidation = false)
    {
        self::$userValidation = $userValidation;
    }

    public static function downloadLogFile() {
        // userValidation to prevent unauthorised users from downloading the file
        if(self::$userValidation == false || self::$userValidation[0]['usergroup']!='saAdmin')
        {
            return false;
        }
        $ciInstance = &get_instance();
        if($ciInstance->security->xss_clean($_GET['enableDebugging'])!=1){
            return 0;
        }
		unset($ciInstance);
        $fileName="log_Report".date('l jS \of F Y h:i:s A');
        $ciInstance = &get_instance();
        $ciInstance->load->library('common/PHPExcel');
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$fileName.'.xls"');
        $objPHPExcel=self::_getPhpExcelSheetObject();
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    private static function _getPhpExcelSheetObject() {
        $objPHPExcel = new PHPExcel();
        $totalProcessingTimeTaken=0;
        $totalMemoryUsed=0;
        foreach (self::$searchLoggingObjectsHolder as $key => $singleObject) {
            $objWorkSheet = $objPHPExcel->createSheet($key);
            $objPHPExcel->setActiveSheetIndex($key);
            $memoryUsedExcludingThisObject=$singleObject->getFinalMemoryUsed() - 
                    ($singleObject->getInitialMemoryUsed()+$singleObject->getMemoryOccupiedByObject());
            $nearestLargestMemoryConsumed=  self::_convertMemoryUnitToMB($memoryUsedExcludingThisObject);
            $totalMemoryUsed+=$nearestLargestMemoryConsumed;
            $processingTimeTaken = $singleObject->getScriptEndTime() - $singleObject->getScriptStartTime();
            $totalProcessingTimeTaken+=$processingTimeTaken;
            $searchEntityName = $singleObject->getEntityName();
            $searchResult = $singleObject->getOutputData();
            $urlCalled = $singleObject->getUrlCalled();
            $keyWordSearched = $singleObject->getKeywordSearched();
            if (is_array($searchResult)) {
                $searchResult = serialize($searchResult);
            }
            $objWorkSheet->setTitle($searchEntityName);
            $headers = array('Entity Name', 'KeywordSearched', 'Memory Used(In Mb)', 'Url Called', 'Processing Time Taken', 'Result');
            $rowNumber = 1;
            foreach ($headers as $columnNumber => $headerValue) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnNumber, $rowNumber, $headerValue);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnNumber,$rowNumber)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnNumber,$rowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'EEEEEE') )));
                $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($columnNumber)->setWidth(20);
                $objPHPExcel->getActiveSheet()->getRowDimension($rowNumber)->setRowHeight(40);
               // $objPHPExcel->getActiveSheet()->getStyle($columnNumber.$rowNumber)->getFont()->setBold(true);
            }
            $rowNumber++;
            $columnValues = array($searchEntityName, $keyWordSearched, $nearestLargestMemoryConsumed, $urlCalled, $processingTimeTaken, $searchResult);
            foreach ($columnValues as $columnNumber => $columnValue) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnNumber, $rowNumber, $columnValue);
                $objPHPExcel->getActiveSheet()->getRowDimension($rowNumber)->setRowHeight(25);
            }
        }
        self::addTotalDataConsumedInSheet($objPHPExcel, $key,$totalMemoryUsed,$totalProcessingTimeTaken);
        return $objPHPExcel;
    }
    private static function addTotalDataConsumedInSheet(&$objPHPExcel, $key,
                                                            $totalMemoryUsed,$totalProcessingTimeTaken) {
        $objWorkSheet = $objPHPExcel->createSheet($key + 1);
        $objPHPExcel->setActiveSheetIndex($key + 1);
        $objWorkSheet->setTitle('Total Data Consumed');
        $headers = array('Total Processing Time taken', 'Total Memory Consumed');
        $rowNumber = 1;
        foreach ($headers as $columnNumber => $headerValue) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnNumber, $rowNumber, $headerValue);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnNumber, $rowNumber)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnNumber, $rowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'EEEEEE'))));
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($columnNumber)->setWidth(20);
            $objPHPExcel->getActiveSheet()->getRowDimension($rowNumber)->setRowHeight(40);
        }
        $rowNumber++;
        $columnValues = array($totalProcessingTimeTaken, $totalMemoryUsed);
        foreach ($columnValues as $columnNumber => $columnValue) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnNumber, $rowNumber, $columnValue);
            $objPHPExcel->getActiveSheet()->getRowDimension($rowNumber)->setRowHeight(25);
        }
        $objPHPExcel->setActiveSheetIndex(0);
    }

    private static function _convertMemoryUnitToMB($size) {
        return round($size/1048576, 4);
    }
    
    public static function insertQERExecutionData($qerExecutionLoggingData) {
        $ciInstance = &get_instance();
        $saSearchModel = $ciInstance->load->model('SASearch/sasearchmodel');
        $saSearchModel->trackSearchExecutionData($qerExecutionLoggingData);
		unset($ciInstance);
    }
    
    public static function logExecutionDataForQER($tid,$userValidation = false){
         $ciInstance = &get_instance();
        if($ciInstance->security->xss_clean($_GET['enableDebugging'])!=1){
            return 0;
        }
        $saSearchModel = $ciInstance->load->model('SASearch/sasearchmodel');
        $qerExecData = $saSearchModel->getQERExecutionData($tid);
        $qerExecData = reset($qerExecData);
        $loggingObject = new studyAbroadLoggingLib();
        $loggingObject->setEntityName("QER");
        $loggingObject->setScriptStartTime(0);
        $loggingObject->setScriptEndTime($qerExecData['timeTaken']);
        $loggingObject->setKeywordSearched($qerExecData['keyword']);
        $loggingObject->setInitialMemoryUsed(0);
        $loggingObject->setFinalMemoryUsed($qerExecData['memoryUsed']);
        $loggingObject->setOutputData($qerExecData['response']);
        $loggingObject->setUrlCalled($qerExecData['url']);
        $loggingObject->addUserValidationData($userValidation);
        $loggingObject->addLoggingObjectToHolder();
        return $loggingObject;
    }
}
