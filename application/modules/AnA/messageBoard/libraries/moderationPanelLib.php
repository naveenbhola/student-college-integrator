<?php
/*

   Copyright 2015 Info Edge India Ltd

   $Author: Virender

   $Id: moderationPanelLib.php

 */
class moderationPanelLib
{
	var $cacheLib;
	var $qnammodel;
	/**
	* default Constructor
	*  @name: init
	*  @param string $userInput: no paramaters
	*/
	function __construct(){
	}

	function init(){
		$this->CI=& get_instance();
		$this->qnammodel = $this->CI->load->model('AnA/qnamoderationmodel');
		$this->cacheLib  = $this->CI->load->library('cacheLib');
	}

	public function getEntityLockedForModerator($msgId, $userId){
		$this->init();
		$key  = 'msgId-'.$msgId;
		$key2 = 'userId-'.$userId;
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE' && $this->cacheLib->get($key2)=='ERROR_READING_CACHE'){
			//store in cache
			$this->cacheLib->store($key, $userId, -1);//untill manually cleared
			$this->cacheLib->store($key2, $msgId, -1);
			//if cache check successful then mark previous entry as history 
			$this->qnammodel->markModeratedEntityAsHistory($msgId);
			//then take fresh lock
			$this->qnammodel->lockEntityForUser($msgId, $userId);
			return 'pass';
		}else if($this->cacheLib->get($key2)!='ERROR_READING_CACHE')
			return 'anotherEntityLocked';
		return 'fail';
	}

	public function getEntityUnlockedForModerator($msgId, $userId){
		$this->init();
		$key  = 'msgId-'.$msgId;
		$key2 = 'userId-'.$userId;
		$res = $this->qnammodel->unlockEntityForUser($msgId, $userId);
		if($res && $this->cacheLib->get($key) != 'ERROR_READING_CACHE')
		{
			$this->cacheLib->clearCacheForKey($key);
			$this->cacheLib->clearCacheForKey($key2);
			return 'unlocked';
		}
		die('error');
	}

	public function getAllEntitiesModeratedByModerator($moderatorId, $start=0, $count=5){
		$this->init();
		return $this->qnammodel->getEntitiesModeratedByModerator($moderatorId, $start, $count);
	}

	public function getFormattedModeratedEntityInfo($filter){
		$this->init();
		$formattedResult = array();
		$result = $this->qnammodel->getModeratedEntityInfo($filter);
		$total = 0;
		foreach ($result as $key => $value) {
			if($value['moderatorId'] == ''){
				$formattedResult[0] = $value['msgCount'];
			}else{
				$formattedResult[$value['moderatorId']] = $value['msgCount'];
			}
			$total += $value['msgCount'];
		}
		$returnArr = array();
		$returnArr['totalSum']      = $total;
		$returnArr['formattedData'] = $formattedResult;
		return $returnArr;
	}
	public function getLockedEntitiesForModerator($moderatorId){
		$this->init();
		return $this->qnammodel->getLockedEntitiesForModerator($moderatorId);
	}
	public function releaseLockOfEntityByCms($moderatorId, $inputLockId, $inputMsgId){
		$this->init();
		$status = $this->qnammodel->expireLockOfEntityByCms($inputLockId);
		if($status){
			$sts = $this->deleteEntityLockFromCache($moderatorId, $inputMsgId);
			if($sts){
				return true;
			}
		}
		return false;
	}

	public function deleteEntityLockFromCache($moderatorId, $inputMsgId){
		$this->init();
		$sts = 0;
		$key = 'userId-'.$moderatorId;
    	if($this->cacheLib->get($key) != 'ERROR_READING_CACHE'){
    		$this->cacheLib->clearCacheForKey($key);
    		$sts++;
    	}
		$key2  = 'msgId-'.$inputMsgId;
		if($this->cacheLib->get($key2) != 'ERROR_READING_CACHE'){
			$this->cacheLib->clearCacheForKey($key2);
			$sts++;
		}
		if($sts > 0)
			return true;
		else
			return false;
	}
}
?>
