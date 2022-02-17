<?php

class scholarshipCache extends Cache{
    
    private $scholarshipCacheKeyPrefix = "V1Scholarship:";
	
    function getScholarshipData($scholarshipId,&$fields){
            $key = $this->scholarshipCacheKeyPrefix;
            try{
                $dataFromCache = $this->getMembersOfHashByFieldNameWithValue($key,$scholarshipId,$fields);
            }
            catch(Exception $e){
                
            }
            if(is_array($dataFromCache)){
                foreach ($dataFromCache as &$value) {
                    $data = json_decode($value, true);
                    if (json_last_error() == JSON_ERROR_NONE) {
                        $value = $data;
                    }
                }
            }
            if(!$dataFromCache['scholarshipId']){
                return false;
            }
            return $dataFromCache;	
    }

    function storeScholarshipData($scholarshipId,$scholarshipData){
            $key = $this->scholarshipCacheKeyPrefix;
            foreach ($scholarshipData as &$value) {
                if(is_array($value)){
                    $value = json_encode($value);
                }
            }
            try{
                $this->addMembersToHash($key,$scholarshipId,$scholarshipData,-1);
            }
            catch(Exception $e){
            }
    }

    function storeMultipleScholarshipsData($scholarshipIds,$scholarshipsData){
        try{
            $ids = array();
            $key = $this->scholarshipCacheKeyPrefix;
            foreach ($scholarshipIds as $scholarshipId) {
                
                if(!empty($scholarshipsData[$scholarshipId])){
                    $ids[]  = $scholarshipId;
                    foreach ($scholarshipsData[$scholarshipId] as &$value) {
                        if(is_array($value)){
                            $value = json_encode($value);
                        }	
                    }
                }
            }
            $this->addMembersToHashForMultipleKeys($key,$ids,$scholarshipsData,-1);
        }
        catch(Exception $e){

        }
    }
    function getMultipleScholarshipsData($scholarshipIds,$fields){
        try{
            $key = $this->scholarshipCacheKeyPrefix;
            $dataFromCache = $this->getMembersOfHashByFieldNameWithValueForMultipleKeys($key,$scholarshipIds,$fields);
        }
        catch(Exception $e){

        }
        $dataArray   = array();
        if(is_array($dataFromCache)){
            foreach ($dataFromCache as $key1=>&$value) {
                foreach ($value as $fieldName=>&$fieldData) {
                        if(!empty($fieldData)){
                                $data = json_decode($fieldData, true);
                                if (json_last_error() == JSON_ERROR_NONE) {
                                    $fieldData = $data;
                                }	
                                $dataArray[$key1][$fieldName] = $fieldData;	
                        }
                }
            }	
        }
        $returnArray = array();
        foreach ($dataArray as $key => &$value) {
                $returnArray[$value['scholarshipId']] = $value;
        }
        return $returnArray;
    }
    function deleteCache($scholarshipId){
        if(empty($scholarshipId)){
            return;
        }
            try {
                $this->delete($this->scholarshipCacheKeyPrefix,$scholarshipId);
            } 
                catch (Exception $e){
            }       
    }
}
