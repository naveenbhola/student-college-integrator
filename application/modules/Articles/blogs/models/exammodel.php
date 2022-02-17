<?php
class ExamModel extends MY_Model {
    function __construct(){
        parent::__construct('Blog');
    }

    private function getEntityRelations($entity,$operation='read'){
        $tableName = '';
        $entityFieldName = '';
        $relations = array();
        $this->load->library('listingconfig');
        //$dbConfig = array( 'hostname'=>'localhost');
        //$this->listingconfig->getDbConfig_test($appId,$dbConfig);
        //$this->load->database($dbConfig);
        if($operation=='read'){
	        $this->db = $this->getReadHandle();
	}
	else{
		$this->db = $this->getWriteHandle();
	}
        return $relations;
    }

    function makeEntityExamsMapping($typeId, $exams, $type,$typeOfMap,$valueIfAny = null,$status='live',$version = 1)
    {
        if($version == ''){
            $version = 1;
        }
        $entityRelations = $this->getEntityRelations($type,'write');
        if(count($entityRelations) < 0) {
            return false;
        }
        if(is_array($exams)){
            $queryCmd = 'INSERT INTO listingExamMap (examId,type,typeId,typeOfMap, valueIfAny ,status, version) VALUES ';
            $values = '';
            foreach($exams as $exam){
                if($values != '') {
                    $values .= ',' ;
                }
                $values .= '("'. $exam.'","'. $type.'","'. $typeId.'","'. $typeOfMap.'","'. $valueIfAny.'","'. $status.'",'. $version.')';
            }
            if($values != '') {
                $queryCmd .= $values;
                $queryCmd .= " on duplicate key update typeId = ? ";
                error_log_shiksha("MODEL ::".$queryCmd);
                if(!$this->db->query($queryCmd,array($typeId))){
                    log_message('error', 'function::makeEntityExamsMapping INSERT query cmd failed' . $queryCmd);
                }
            }
        }
    }

    function getExamsForEntity($typeId, $type,$typeOfMap = '',$version=1){
        $entityRelations = $this->getEntityRelations($type);
        if(count($entityRelations) < 0) {
            return false;
        }
     
        if(strlen($typeOfMap) > 0){
            $addWhereClause = " and typeOfMap=".$this->db->escape($typeOfMap);
        }
        if($version == ''){
            $version = 1;
        }

        $queryCmd = "select blogId, blogTitle, url, acronym, valueIfAny FROM listingExamMap, blogTable where blogTable.blogId = listingExamMap.examId and blogTable.blogType = 'exam' and listingExamMap.typeId = ? and listingExamMap.type = ? $addWhereClause and version = ? ";
        error_log_shiksha('MODEL:: ' . $queryCmd);
        $query = $this->db->query($queryCmd, array($typeId,$type,$version) );
        $msgArray = array();
        foreach ($query->result_array() as $row){
            array_push($msgArray,$row);
        }
        $response = json_encode($msgArray);
        return $response;
    }

    function getRelatedProducts($listing_type_id,$listing_type,$relatedprod = 'ask'){
	$appId =1;
        $this->load->library('relatedClient');
        $relatedClientObj = new RelatedClient();
        $relatedQuestions = $relatedClientObj->getrelatedData($appId,$listing_type,$listing_type_id,$relatedprod);
        if(is_array($relatedQuestions) && is_array($relatedQuestions[0]) && is_array($relatedQuestions[0])){
            $data = json_decode($relatedQuestions[0]['relatedData'],true);
            return json_encode($data['resultList']);
        }else{
            return  false;
        }
    }
    function insertOtherExam($formVal,$status="live",$version=1){
        $entityRelations = $this->getEntityRelations('','write');
        if(count($entityRelations) < 0) {
            return false;
        }


        $data =array();
        $data = array(
            'listingTypeId'=>$formVal['listingTypeId'],
            'listingType'=>$formVal['listingType'],
            'typeOfMap'=>$formVal['typeOfMap'],
            'exam_name'=>$formVal['exam_name'],
            'exam_desc'=>$formVal['exam_desc'],
            'exam_date'=>$formVal['exam_date'],
            'exam_duration'=>$formVal['exam_duration'],
            'exam_timings'=>$formVal['exam_timings'],
            'status'=>$status,
            'version'=>$version
        );
        $queryCmd = $this->db->insert_string('othersExamTable',$data);

        error_log_shiksha("ADDNEWEXAM : ".$queryCmd);
        $query = $this->db->query($queryCmd);
        $newExamId = $this->db->insert_id();
        for($i = 0 ; $i < $formVal['numOfCentres']; $i++)
        {
            $data =array();
            $data = array(
                'other_exam_id'=>$newExamId,
                'address_line1'=>$formVal['address_line1'.$i],
                'address_line2'=>$formVal['address_line2'.$i],
                'city_id'=>$formVal['city_id'.$i],
                'country_id'=>$formVal['country_id'.$i],
                'zip'=>$formVal['zip'.$i]
            );
            $queryCmd = $this->db->insert_string('other_exam_centres_table',$data);
            error_log_shiksha("ADDNEWEXAM : ".$queryCmd);
            $query = $this->db->query($queryCmd);
        }
        return $newExamId;
    }

    function getOtherExams($typeId, $type, $typeOfMap,$version = 1)
    {
        $this->load->library('cacheLib');
        $cacheLibObj = new cacheLib();
        
        $entityRelations = $this->getEntityRelations();
        if(count($entityRelations) < 0) {
            return false;
        }
        $dbHandle = $this->db;

        if(strlen($typeOfMap) > 0){
            $addWhereClause = ' and typeOfMap=?';
        }
        $queryCmd = "select * from othersExamTable where listingType=? and listingTypeId=? and version=? $addWhereClause" ;
        error_log_shiksha($queryCmd);
        $queryTemp = $dbHandle->query($queryCmd, array($type,$typeId,$version,$typeOfMap) );
        $examArrayTemp = array();
        foreach ($queryTemp->result() as $rowTemp) {
            $newExamId = $rowTemp->other_exam_id;

            if($newExamId > 0){
                $queryCmdCentres = 'select * from other_exam_centres_table where other_exam_id = ?';
                error_log_shiksha($queryCmdCentres);
                $queryTempCentres = $dbHandle->query($queryCmdCentres, array($newExamId));
                $examcentresArrayTemp = array();
                foreach ($queryTempCentres->result() as $rowTempCentres) {
                    array_push($examcentresArrayTemp,array(
                                'address_line1'=>$rowTempCentres->address_line1,
                                'address_line2'=>$rowTempCentres->address_line2,
                                'zip'=>$rowTempCentres->zip,
                                'country_id'=>$rowTempCentres->country_id,
                                'city_id'=>$rowTempCentres->city_id,
                                'country'=>$cacheLibObj->get("country_".$rowTempCentres->country_id),
                                'city'=>$cacheLibObj->get("city_".$rowTempCentres->city_id)
                                ));//close array_push
                }
            }

            array_push($examArrayTemp,array(
                            'exam_name'=>$rowTemp->exam_name,
                            'exam_desc'=>$rowTemp->exam_desc,
                            'exam_date'=>$rowTemp->exam_date,
                            'exam_timings'=>$rowTemp->exam_timings,
                            'exam_duration'=>$rowTemp->exam_duration,
                            'examcentres' =>$examcentresArrayTemp
                            ));//close array_push
        }

        return json_encode($examArrayTemp);
    }

}
?>
