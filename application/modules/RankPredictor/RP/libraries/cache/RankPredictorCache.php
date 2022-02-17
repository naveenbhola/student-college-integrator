<?php

class RankPredictorCache extends Cache {
    private $standardCacheTime = 21600; //6 hours

    private $predictorCacheKey = "jeemaxscore";
    
    function __construct() {
        parent::__construct();
    }

    function storeMaxRank($maxscore)
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = $this->predictorCacheKey;
        $result = $redis_client->addMemberToString($stringKey,$maxscore);
        return $result;
    }
    function getMaxRank()
    {
        $redis_client = PredisLibrary::getInstance();
        $stringKey  = $this->predictorCacheKey;
        $data = $redis_client->getMemberOfString($stringKey);
        return $data;
    }
}

?>