<?php
/**
 * API Common Cache Library for fetching/storing/updating/deleting data from cache(Redis/Memcache).
 * @date    2015-07-14
 * @author  Romil Goel
 * @todo    none
*/

class APICommonCacheLib {

    private $CI;
    
    function __construct() {
        $this->CI = &get_instance();
        $this->predisLib = PredisLibrary::getInstance();//$this->CI->load->library('common/PredisLibrary');
    }
	
    /**
     * Method to get the list of people a given user is following
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-09-14
     * @param  [Integer]     $userId [user id for which follow list is to be fetched]
     * @return [array]             [list of user-ids]
     */
    function getUserFollowList($userId){
        return $this->predisLib->getMembersOfSet("userFollows:user:".$userId);
    }

    function getUserFollowerList($userId){
        return $this->predisLib->getMembersOfSet("userFollowers:user:".$userId);
    }

    function getUserFriendList($userId){
        return $this->predisLib->getMembersOfSet("userFreind:user:".$userId);
    }

    function getTopContributorsForTag($tagId){
        return $this->predisLib->getMembersOfSet("tagsTopContributors:".$tagId);
    }

    function getUserActiveTags($userId){
        return $this->predisLib->getMembersOfSet("usersActiveTags:".$userId);
    }

    function getUserNotificationCount($userId){
        $count = $this->predisLib->getMembersOfHashByFieldNameWithValue("notificationsCount:inapp", array($userId));
        if($count[$userId])
            return $count[$userId];
        else
            return 0;
    }

    function insertAPILogDetails($details){
    	if($this->predisLib->isRedisAlive())
        	$this->predisLib->addMembersToSet('appApiTracking', $details);
    }

    function getHighLevelTags(){
        return $this->predisLib->getMembersOfSet("highLevelTagsForPersonalization");
    }

    function insertTagStats($tagId, $details){
       $seconds = 60*60*24;
       if($this->predisLib->isRedisAlive()){
             $redisKey = 'tagStats:'.$tagId;
             $this->predisLib->addMembersToSet($redisKey, $details, FALSE);
             $this->predisLib->expireKey($redisKey, $seconds, FALSE);
       }
    }

    function getTagStats($tagId){
        return $this->predisLib->getMembersOfSet("tagStats:".$tagId);
    }

    function insertAnAStats($details){
       $seconds = 60*60*24*30;
       if($this->predisLib->isRedisAlive()){
             $redisKey = 'ANAOverallStats:1';
             $this->predisLib->addMembersToSet($redisKey, $details, FALSE);
             $this->predisLib->expireKey($redisKey, $seconds, FALSE);
       }
    }

    function getAnAStats(){
        return $this->predisLib->getMembersOfSet('ANAOverallStats:1');
    }

}
?>
