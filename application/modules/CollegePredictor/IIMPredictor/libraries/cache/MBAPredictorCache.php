<?php

class MBAPredictorCache extends Cache {
    private $standardCacheTime = 21600; //6 hours

    private $predictorCacheKey = "MBAPIns:";
    
    function __construct() {
        parent::__construct();
    }

    function storeInstituteTupleInfo($instituteId,$data)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = $this->predictorCacheKey.$instituteId;
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;
    }
    function getInstituteTupleInfo($instituteId)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = $this->predictorCacheKey.$instituteId;
        $data = $redis_client->getMemberOfString($stringKey);
        return $data;
    }
    function storeInstituteNameData($data){
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "predInstNames";
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;
    }
    function getInstituteNameData(){
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = "predInstNames";
        $result = $redis_client->getMemberOfString($stringKey);
        return $result;
    }
}

?>