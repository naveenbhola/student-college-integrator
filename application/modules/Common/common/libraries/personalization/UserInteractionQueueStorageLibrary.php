<?php
	class UserInteractionQueueStorageLibrary{
		private $CI;
		private $userId;
		private $visitorId;
		private $action;
		private $entityId;
		private $entityType;
		private $actionTime;
		private $optional;
		private $queueData;
		private $JobClient;
		private $allowedEntityForActions = array(	'view'		=> array('discussion', 'question', 'tag'),
													'post'		=> array('discussion', 'question'),
													'updatetag'	=> array('discussion', 'question'),
													'share'		=> array('discussion', 'question'),
													'follow'	=> array('discussion', 'question', 'tag', 'user'),
													'unfollow'	=> array('discussion', 'question', 'tag', 'user'),
													'upvote'	=> array('discussion', 'question'),
													'downvote'	=> array('discussion', 'question'),
													'answer'	=> array('question'),
													'comment'	=> array('discussion'),
													'delete'	=> array('discussion','question')
											);
		
		function __construct($userId, $visitorId, $action, $entityId, $entityType, $actionTime, $optional = array()){
			$this->CI			= & get_instance();
			$this->userId		= $userId;
			$this->visitorId	= $visitorId;
			$this->action		= $action;
			$this->entityId		= $entityId;
			$this->entityType	= $entityType;
			$this->actionTime	= $actionTime;
			$this->optional		= $optional;
			$this->queueData	= array();
		}
		
		public function setData($data = array()){
			foreach ($data as $key => $value){
				if(property_exists($this, $key)){
					$this->$key = $value;
				}
			}
		}
		
		private function _setJobSchedulerLib(){
			$this->CI->load->library('common/jobserver/JobManagerFactory');
			$s = microtime(true);
			global $isMobileApp;
			$this->JobClient = JobManagerFactory::getClientInstance();
			if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Inside _setJobSchedulerLib ::  ".(microtime(true)-$s)." , Mobile : ".$isMobileApp, 3, '/tmp/perfLogs1.log');
		}
		
		private function _prepareDataForQueue(){
			$this->queueData = array();
			
			if(in_array($this->entityType, $this->allowedEntityForActions[$this->action])){
				$this->_setQueueData();
			}
			
			return ;
		}
		
		private function _setQueueData(){
			$this->queueData = array(	'userId'		=> $this->userId,
										'visitorId'		=> $this->visitorId,
										'action'		=> $this->action,
										'entityId'		=> $this->entityId,
										'entityType'	=> $this->entityType,
										'actionTime'	=> $this->actionTime
									);
			foreach ($this->optional as $key => $value){
				$this->queueData[$key] = $value;
			}
			return ;
		}
		
		public function insertInQueue(){
			$this->_prepareDataForQueue();
			if(!empty($this->queueData)){
				$this->_setJobSchedulerLib();
				$s = microtime(true);
				global $isMobileApp;
				$this->JobClient->addBackgroundJob('AppPersonalizationFanOutData',$this->queueData);
				if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Inside addBackgroundJob ::  ".(microtime(true)-$s)." , Mobile : ".$isMobileApp, 3, '/tmp/perfLogs1.log');
				unset($this->JobClient);
				$this->queueData = array();
			}
		}
	}
?>