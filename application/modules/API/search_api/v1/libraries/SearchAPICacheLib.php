<?php
/**
 * API Search Cache Library for fetching/storing/updating/deleting data from cache(Redis/Memcache).
 * @date    2016-01-27
 * @author  Romil Goel
 * @todo    none
*/

class SearchAPICacheLib {

    private $CI;
    
    function __construct() {
        $this->CI        = &get_instance();
        $this->predisLib = PredisLibrary::getInstance();
    }
	
    function storeRelatedTags($tagId, $relatedData){

        $rediskey = "related:tags:".$tagId;
        $data     = serialize($relatedData);

        $this->predisLib->deleteKey(array($rediskey));
        $this->predisLib->addMemberToString($rediskey, $data, 86400);
    }

    function getRelatedTags($tagId){

        $rediskey = "related:tags:".$tagId;

        $data = $this->predisLib->getMemberOfString($rediskey);;
        if($data)
            $data = unserialize($data);

        return $data;
    }
}
?>
