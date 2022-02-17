<?php

class MailerCriteriaEvaluatorService
{
	private $CI;
	private $model;
	private $extraCriteria = array();
	private $countFlag;
	private $MMMUserCount;
	private $totalMailsToBeSent;
	private $profileBasedFlag = true;

	function __construct($model)
	{
		$this->CI = & get_instance();
		$this->model = $model;
	}

	public function setExtraCriteria($extraCriteria)
	{
		$this->extraCriteria = $extraCriteria;
	}

	public function evaluateCriteria($mailerCriteria,$countFlagCheck = false)
	{	
		
		return $this->getUserIdsFromCriteria($mailerCriteria,$countFlagCheck);

		$this->countFlag = $countFlagCheck;

		$userSet = array();
		list($includes, $excludes) = explode('EXP_EXCLUDE', $mailerCriteria);
		$userSet = $this->getExpIntersectSets($includes);
		
		if (!empty($excludes) && !empty($userSet)) {
			$users = $this->getExpIntersectSets($excludes);
			
			foreach ($userSet as $userid=>$flag) {
				if (isset($users[$userid])) {
					unset($userSet[$userid]);
				}
			}
		}
		
		if($countFlagCheck){
				return $this->MMMUserCount;
		}
		unset($users);
		return array_keys($userSet);
	}

	private function getExpIntersectSets($intersectSet)
	{
		$andSets = explode('EXP_AND', $intersectSet);
		
		foreach ($andSets as $andSet) {
			$users = array();
			$users = $this->getExpUnionSets($andSet);
			
			if (!empty($users)) {
				if (isset($userSet)) {
					foreach ($userSet as $userid=>$flag) {
						if (!isset($users[$userid])) {
							unset($userSet[$userid]);
						}
					}
				}
				else {
					$userSet = $users;
				}
			}
			else {
				return array();
			}
		}
		unset($users);
		
		return isset($userSet) ? $userSet : array();
	}

	private function getExpUnionSets($andSet)
	{
		$orSets = explode('EXP_OR', $andSet);
		
		foreach ($orSets as $orSet) {
			$users = array();
			$users = $this->evaluateIndividualExpression($orSet);
			
			if (isset($userSet)) {
				foreach ($users as $userid=>$flag) {
					$userSet[$userid] = TRUE;
				}
			}
			else {
				$userSet = $users;
			}
		}
		return isset($userSet) ? $userSet : array();
	}

	public function evaluateIndividualExpression($individualCriteria)
	{
		$userSet = array();
		list($includes, $excludes) = explode('EXCLUDE', $individualCriteria);
		$userSet = $this->getIntersectSets($includes);
		
		if (!empty($excludes) && !empty($userSet)) {
			$users = $this->getIntersectSets($excludes);
			
			foreach ($userSet as $userid=>$flag) {
				if (isset($users[$userid])) {
					unset($userSet[$userid]);
				}
			}
		}
		return $userSet;
	}

	private function getIntersectSets($intersectSet)
	{
		$andSets = explode('AND', $intersectSet);
		
		foreach ($andSets as $andSet) {
			$users = array();
			$users = $this->getUnionSets($andSet);
			
			if (!empty($users)) {
				if (isset($userSet)) {
					foreach ($userSet as $userid=>$flag) {
						if (!isset($users[$userid])) {
							unset($userSet[$userid]);
						}
					}
				}
				else {
					$userSet = $users;
				}
			}
			else {
				return array();
			}
		}
		return isset($userSet) ? $userSet : array();
	}

	private function getUnionSets($andSet,$excludeSet)
	{
		$orSets = explode('OR', $andSet);		
		$oldFlag = $this->countFlag;

		foreach ($orSets as $orSet) {

			$users = array();$usersCount = 0;
			$userSearchCriteria = $this->model->getUserSearchCriteria(trim($orSet));

			$criteria = json_decode($userSearchCriteria['criteria'], true);
			$country = $criteria['country'];

			if($userSearchCriteria['criteriaType'] == 'Profile' && $oldFlag === false && $country != 'abroad') {
				$this->countFlag = true;				
				$usersCount = $this->getUserListByCriteria($userSearchCriteria['criteria'], $userSearchCriteria['criteriaType'], 0, 'matches');			
				$this->countFlag = false;
			}
			
			$users = $this->getUserListByCriteria($userSearchCriteria['criteria'], $userSearchCriteria['criteriaType'], $usersCount);
			
			foreach ($users as $userid=>$flag) {
				if (!isset($excludeSet[$userid]))
					{
						$userSet[$userid] = TRUE;
					}
			}
		}

		$this->countFlag = $oldFlag;
		return isset($userSet) ? $userSet : array();
	}

	public function getUserListByCriteria($criteria, $criteriaType, $usersCount = 0, $responseField = 'ngroups')
	{
		$criteria = json_decode($criteria, true);
		
		if ($criteriaType == 'Profile') {

			if(!isset($criteria['countFlag']) || !$criteria['countFlag']){
				$criteria['countFlag'] = $this->countFlag;
			}

			if($usersCount > 0) {
				$criteria['totalMailsToBeSent'] = $usersCount;
			}

			$criteria['responseField'] = $responseField;
			
			$this->CI->load->library('mailer/UserSearchCriteriaEvaluators/ProfileBased');
			$criteriaSearcher = new ProfileBased();
			$result = $criteriaSearcher->evaluate($criteria);
			if($criteria['countFlag']){
				$this->MMMUserCount = $result;
			}
			return $result;
		}
		else if ($criteriaType == 'Activity') {
			if(!isset($criteria['countFlag']) || !$criteria['countFlag']){
				$criteria['countFlag'] = $this->countFlag;
			}

			$this->profileBasedFlag = false;
			$this->CI->load->library('mailer/UserSearchCriteriaEvaluators/ActivityBased');
			$criteriaSearcher = new ActivityBased();
			$criteriaSearcher->setExtraCriteria($this->extraCriteria);
			$result = $criteriaSearcher->evaluate($criteria);
			if($criteria['countFlag']){
				$this->MMMUserCount = $result;
			}
			return $result;
		}
	}

	private function getUserIdsFromCriteria($mailCriteria,$countFlagCheck){
		if (empty($mailCriteria)){
			return ;
		}

		list($includes,$excludes) = explode("EXCLUDE",$mailCriteria);
		

		$includes = $this->removeExcludeFromInclude($includes,$excludes);

		if (!empty($excludes) && !empty($includes)){
			$excludeUserSet = $this->getUnionSets($excludes);
		}

		if (!empty($includes)){
			$includeUserSet = $this->getUnionSets($includes,$excludeUserSet);
		}

		/*if (!empty($excludes) && !empty($includeUserSet)){
			$excludeUserSet = $this->getUnionSets($excludes);
			foreach ($excludeUserSet as $userId => $flag) {
					if (isset($includeUserSet[$userId])){
						unset($includeUserSet[$userId]);
					}
			}
		}*/

		if ($countFlagCheck)
			return count($includeUserSet);
		else 
			return array_keys($includeUserSet);
	}

	private function removeExcludeFromInclude($includes,$excludes){
		$includes = array_flip(explode('OR',$includes));
		$excludes = array_flip(explode('OR',$excludes));
		foreach ($excludes as $criteria => $flag) {
			if (isset($includes[$criteria])){
				unset($includes[$criteria]);
			}
		}
		return implode('OR',array_flip($includes));
	}
}

