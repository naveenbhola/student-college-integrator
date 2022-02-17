<?php

class ExamCache extends Cache {
    private $standardCacheTime = 21600; //6 hours
    
    function __construct() {
		parent::__construct();
        $this->CI->load->config('examPages/examPageConfig');
        $this->_redis_client = PredisLibrary::getInstance();
	}
    
    public function storeExam($exam, $id)
	{    
        if(!empty($exam)) {
            $data = json_encode($exam);
        	$this->store(ExamBaiscKey,$id, $data, $this->standardCacheTime, NULL, 1);
        }
	}
    
    public function getExam($examId) //on whichever is find
    {
        if(!empty($examId)) {
            $data[$examId] = json_decode($this->get(ExamBaiscKey, $examId), true);
        }

        return $data;
    }

    public function getMultiExam($examIds) //on whichever is find
    {
        if(!empty($examIds)) {
            $exams =  $this->multiGet(ExamBaiscKey,$examIds);
            $data = array();
            foreach ($exams as $key => $value) {
                $data[$key] = json_decode($value, true);
            }
        }
        return $data;
    }

    public function storeGroup($group,$id)
    {  
        if(!empty($group)) {
            $data = json_encode($group);
            $this->store(GroupKey,$id, $data, $this->standardCacheTime, NULL, 1);
        }
    }
    
    public function getGroup($groupId) //on whichever is find
    {
        if(!empty($groupId)) {
            $data = json_decode($this->get(GroupKey, $groupId),true);
        }
        return $data;
    }

    public function getMultiGroup($groupIds) //on whichever is find
    {
        if(!empty($groupIds)) {
            $groups =  $this->multiGet(GroupKey,$groupIds);
            $data = array();
            foreach ($groups as $key => $value) {
                $data[$key] = json_decode($value, true);
            }
        }
        return $data;
    }
    
    public function storeContent($groupId, $examContent, $ampFlag){
        $keyidentifier = ExamContentKey;
        if($ampFlag){
            $keyidentifier = ExamAMPContentKey;
        }
        if(!empty($examContent)) {             
            $finalData = array();
            foreach ($examContent as $key => $value) {
                $finalData[$key] = json_encode($value);
            }
            $this->addMembersToHash($keyidentifier,$groupId,$finalData,-1);
        }
    }

    function getSectionData($groupId, &$sectionNames, $ampFlag){
        $keyidentifier = ExamContentKey;
        if($ampFlag){
            $keyidentifier = ExamAMPContentKey;
        }
        if(!empty($sectionNames) && !empty($groupId)) {
            try{
                $dataFromCache = $this->getMembersOfHashByFieldNameWithValue($keyidentifier,$groupId, $sectionNames);
            }
            catch(Exception $e){

            }
           
            if(is_array($dataFromCache)){
                foreach ($dataFromCache as &$value) {
                    $value = json_decode($value,true);
                }
            }
            if(empty($dataFromCache)){
                return false;
            }
        }
        return $dataFromCache;
    }

    function deleteCache($id, $key){
        if(empty($id)){
            return;
        }
        try {
            $this->delete($key,$id);
        }
            catch (Exception $e){
        }
    }

    function clearCacheByKey($key){
        $this->deleteByKey($key);
    }

    public function storeSanitizedPredictorExamName($sanitizedExamNameData) {
        if(empty($sanitizedExamNameData)) {
            return;
        }
        $stringKey = "sanitizedPredictorExamName";
        $this->_redis_client->addMemberToString($stringKey, json_encode($sanitizedExamNameData));
    }

    public function getSanitizedPredictorExamName() {
        $stringKey = "sanitizedPredictorExamName";
        $data = $this->_redis_client->getMemberOfString($stringKey);
        return $data;
    }
}