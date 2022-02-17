<?php
	
	/**
	 * Library to store actions performed by user in Cache System.
	 * Only those actions will be stored in Cache which are to be considered for personalization.
	 * 
	 * @author Abhinav Pandey
	 */
	class UserInteractionCacheStorageLibrary{
		private $CI;
		private $predisLib;
		private $threadQualityScoreParametersWithValue;
		private $threadQualityScoreParametersWithKeyName;
		private $userInteractionQueueStorageLib;
		private $allowedEntityForActions = array(	'view'		=> array('discussion', 'question', 'tag'),
													'post'		=> array('discussion', 'question'),
													'share'		=> array('discussion', 'question'),
													'follow'	=> array('discussion', 'question', 'tag', 'user'),
													'unfollow'	=> array('discussion', 'question', 'tag', 'user'),
													'upvote'	=> array('discussion', 'question'),
													'downvote'	=> array('discussion', 'question'),
													'answer'	=> array('question'),
													'comment'	=> array('discussion'),
													'delete'	=> array('discussion','question','tag')
												);
		private $topTagsForUserCount;
		/**
		 * Initialize UserInteractionCacheStorageLib. Loads redis library and some basic configuration. 
		 */
		function __construct(){
			$this->CI = & get_instance();
			// $this->CI->load->library('common/PredisLibrary');
			$this->predisLib = PredisLibrary::getInstance();//new PredisLibrary();
			$this->CI->config->load('personalizationConfig');
			$this->threadQualityScoreParametersWithValue	= $this->CI->config->item('threadQualityScoreParametersWithValue');
			$this->threadQualityScoreParametersWithKeyName	= $this->CI->config->item('threadQualityScoreParametersWithKeyName');
			$this->topTagsForUserCount						= $this->CI->config->item('topTagsForUserCount');
		}
		
		/**
		 * Loads UserInteractionQueueStorageLibrary for storing actions in queue.
		 * Data from queue will be picked up by worker threads to carry out further processes.
		 */
		private function _setUserInteractionQueueStorageLib(){
			if(! $this->userInteractionQueueStorageLib instanceof UserInteractionQueueStorageLibrary){
				$this->userInteractionQueueStorageLib = $this->CI->load->library('common/personalization/UserInteractionQueueStorageLibrary');
			}
		}
		
		/**
		 * 
		 * @param	userId of User who performed action
		 * @param	action which user performed
		 * @param	entityId of entity on which action was performed
		 * @param	entityType of entity on which action was performed
		 * @param	optionalData : any optional data that is required to capture other than mandatory ones
		 */
		private function _insertDataInQueue($userId, $action, $entityId, $entityType, $optionalData = array()){
			$this->_setUserInteractionQueueStorageLib();
			$actionTime = date('YmdHis');
			$visitorId	= getVisitorId();
			$this->userInteractionQueueStorageLib->setData(array(	'userId'		=> $userId,
																	'visitorId'		=> $visitorId,
																	'action'		=> $action,
																	'entityId'		=> $entityId,
																	'entityType'	=> $entityType,
																	'actionTime'	=> $actionTime,
																	'optional'		=> $optionalData
																)
															);
			
			$this->userInteractionQueueStorageLib->insertInQueue();
		}
		
		/**
		 * Update thread quality property in cache. These properties includes like view, share, comment, upvote, etc.
		 * @param int threadId	: Id of thread whose quality property is to be updated.
		 * @param string action	: action which is performed on thread. Like view, share, upvote, etc.
		 * @param boolean insertInPipeLine	: whether this updation is to be performed in pipeline or not. Default is FALSE
		 */
		private function _updateThreadQualityParameter($threadId, $action, $insertInPipeLine = FALSE){
			// not implementing checks for emptiness or null values in threadId or action because calling function should check for that
			$this->predisLib->incrementByFloatFieldValueOfHash('threadQualityProperty:thread:'.$threadId, $this->threadQualityScoreParametersWithKeyName[$action], $this->threadQualityScoreParametersWithValue[$action], $insertInPipeLine);
			
			// adding threadId into set queue so that its quality score can be updated
			$this->predisLib->addMembersToSet('computeThreadQualityQueue',array($threadId),$insertInPipeLine);

		}
		
		/**
		 *	@param		int	userId		: userId of user whose freind list is to be updated
		 *	@param		array freindList	: array of userIds of freinds
		 */
		public function updateFreindList($userId, $newFreindList){
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNonEmptyArrayOfIntegerValues($newFreindList, 'Freind List');
				$oldFreindList = $this->predisLib->getMembersOfSet('userFreind:user:'.$userId);
				$this->predisLib->setPipeLine();
				$this->predisLib->deleteKey(array('userFreind:user:'.$userId), TRUE);
				foreach ($oldFreindList as $key => $value){
					$this->predisLib->removeMembersOfSet('userFreind:user:'.$value,array($userId),TRUE);
				}
				$this->predisLib->addMembersToSet('userFreind:user:'.$userId, $newFreindList, TRUE);
				foreach ($newFreindList as $key => $value){
					$this->predisLib->addMembersToSet('userFreind:user:'.$value, array($userId), TRUE);
				}
				$this->predisLib->executePipeLine();
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			
		}
		
		/**
		 * Function to check if user is in-Active. If yes, then restore data for such user.
		 */
		private function checkIfInActiveUser($userId){
			// this return call is placed as this piece of code will go live in next sprint of SPRINT_10MAY_23MAY
			//return;
			if($userId > 0 && $this->predisLib->getBit('inActiveUserForHomeFeed',$userId) == 1){
				$this->CI->load->library('common/personalization/PersonalizationLibrary');
				$this->CI->personalizationlibrary->setUserId($userId);
				$this->CI->personalizationlibrary->setVisitorId('');
				$backFillThreadFlag = FALSE;
				/*global $isMobileApp;
				if($isMobileApp == 1){
					$backFillThreadFlag = TRUE;
				}
				$this->CI->personalizationlibrary->restorePersonalizationDataForReturningUser($backFillThreadFlag);
				*/
                $this->CI->personalizationlibrary->restorePersonalizationDataForReturningUser(TRUE);
			}
			return TRUE;
		}
		/**
		 * @param	int userId	: userId of user who performed action
		 * @param	int entityId
		 * @param	string entityType	: any one of (question/discussion/tag)
		 * @example	entityId = 1234, entityType = 'discussion'
		 */
		public function storeUserActionView($userId, $entityId=0, $entityType=''){
			try {
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Entity ID');
				Contract::mustBeNonEmptyVariable($entityType, 'Entity Type');
				if(!in_array($entityType, $this->allowedEntityForActions['view'])){
					return ;
				}
				$this->CI->benchmark->mark('check_active_user_start');
				$this->checkIfInActiveUser($userId);
				$this->CI->benchmark->mark('check_active_user_end');
				if($entityType == 'tag'){
					Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
					$this->_insertDataInQueue($userId, 'view', $entityId, $entityType);
				}else{
					// set pipeLine for all redis actions to be performed.
					$this->CI->benchmark->mark('set_pipeline_start');
					$this->predisLib->setPipeLine();
					$this->CI->benchmark->mark('set_pipeline_end');
					$this->CI->benchmark->mark('update_thread_quality_start');
					$this->_updateThreadQualityParameter($entityId, 'threadView', TRUE);
					$this->CI->benchmark->mark('update_thread_quality_end');
					// execute all pipelined redis commands
					$this->CI->benchmark->mark('execute_pipeline_start');
					$this->predisLib->executePipeLine();
					$this->CI->benchmark->mark('execute_pipeline_end');
					
					//global $isWebAPICall;
					//if($userId > 0){
					//if($userId > 0 || ($userId == 0 && $isWebAPICall == 1)){ // check to ensure that entry for desktop non-logged in user is not captured
					$this->CI->benchmark->mark('insert_data_queue_start');
						$this->_insertDataInQueue($userId, 'view', $entityId, $entityType);
					$this->CI->benchmark->mark('insert_data_queue_end');
					//}
					//}
				}
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			
			return ;
		}
		
		/**
		 *	@param		int userId : UserId of User who performed action
		 *	@param		int entityId
		 *	@param		string entityType : any one of (question/discussion)
		 *	@example	entityId = 21212, entityType = question
		 */
		public function storeUserActionPost( $userId, $entityId, $entityType){
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNonEmptyVariable($entityType, 'Thread Type');
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Thread ID');
				
				if(!in_array($entityType, $this->allowedEntityForActions['post'])){
					return ;
				}
				
				$this->checkIfInActiveUser($userId);
				
				$this->predisLib->setPipeLine();
				$this->predisLib->addMembersToSet('threadContributors:thread:'.$entityId, array($userId), TRUE);
				//$this->_updateThreadQualityParameter($entityId, 'threadPost', TRUE);
					
				// execute all pipelined redis commands
				$this->predisLib->executePipeLine();
				
				//$this->_insertDataInQueue($userId, 'post', $entityId, $entityType);
				
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			return ;
		}
		
		/**
		 *	@param		int userId	: UserId of user who performed action
		 *	@param		int entityId
		 *	@param		string entityType	: any one of (question/discussion)
		 *	@example	userId = 1287123, entityId = 8912, entityType = discussion
		 */
		public function storeUserActionShare($userId, $entityId, $entityType){
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Share Thread ID');
				Contract::mustBeNonEmptyVariable($entityType, 'Thread Type');
				
				if(!in_array($entityType, $this->allowedEntityForActions['share'])){
					return ;
				}
				
				$this->checkIfInActiveUser($userId);
				
				$this->predisLib->setPipeLine();
				
				$this->_updateThreadQualityParameter($entityId, 'threadShare', TRUE);
				
				$this->predisLib->executePipeLine();
				
				$this->_insertDataInQueue($userId, 'share', $entityId, $entityType);
				
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			
			return ;
		}
		
		/**
		 *	@param		int userId	: UserId of user who performed action
		 *	@param		int entityId
		 *	@param		string entityType	: any one of (discussion/question/tag)
		 *	@example	userId = 8912898, entityId = 6712677, entityType = 'user' 
		 *	@example	userId = 8912898, entityId = 1267, entityType = 'discussion'
		 */
		public function storeUserActionFollow($userId, $entityId, $entityType) {
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Entity ID');
				Contract::mustBeNonEmptyVariable($entityType, 'Entity Type');
				
				if(!in_array($entityType, $this->allowedEntityForActions['follow'])){
					return ;
				}
				
				$this->checkIfInActiveUser($userId);
				
				if(in_array($entityType,array('discussion','question'))){
					
					$this->predisLib->setPipeLine();
					$this->predisLib->addMembersToSet('threadFollowers:thread:'.$entityId,array($userId), TRUE);
					$this->predisLib->removeMembersOfSet('threadUnfollowers:thread:'.$entityId,array($userId), TRUE);
					$this->_updateThreadQualityParameter($entityId, 'threadFollow', TRUE);
					
					// execute all pipelined redis commands
					$this->predisLib->executePipeLine();
					
					$this->_insertDataInQueue($userId, 'follow', $entityId, $entityType);
					
				}elseif ($entityType == 'tag'){
					
					$topTagsForUser = $this->predisLib->getMembersInSortedSet('userFollowsTag:user:'.$userId, 0, $this->topTagsForUserCount, TRUE, TRUE);
					$isHighLevelTag	= $this->predisLib->checkIfMemberOfSet("highLevelTagsForPersonalization",$entityId);
					// set pointer to last element of array
					end($topTagsForUser);
					$lastKey = key($topTagsForUser);
					
					// set pointer to first element of array
					reset($topTagsForUser);
					
					// this is added because this tag will no longer be in top active tag of user once newly followed tag is added to sorted set. 
					$topTagsForUserKeys = array_keys($topTagsForUser);
                    			$tagFromWhereUserIdToBeRemoved = $topTagsForUserKeys[$this->topTagsForUserCount];
					$firstKey = key($topTagsForUser);
					$medianValueForTopTagsForUser = (($topTagsForUser[$firstKey] + $topTagsForUser[$lastKey]) / 2) + 1;
					$medianValueForTopTagsForUser = $medianValueForTopTagsForUser ? $medianValueForTopTagsForUser : 0;
					
					$this->predisLib->setPipeLine();
					$this->predisLib->addMembersToSortedSet('userFollowsTag:user:'.$userId, array($entityId => $medianValueForTopTagsForUser), TRUE);
					if(!$isHighLevelTag){ // user explicitly follows high level tag need not to be added in this key, as threads will not be fan-out for these tags
						$this->predisLib->addMembersToSet('tagFollowers:tag:'.$entityId,array($userId), TRUE);
					}
					$this->predisLib->addMembersToSet('userFollowsTagExplicitly:user:'.$userId,array($entityId), TRUE);
					$this->predisLib->removeMembersOfSet('tagUnfollowers:tag:'.$entityId,array($userId), TRUE);
					if(isset($tagFromWhereUserIdToBeRemoved) && !key_exists($entityId, $topTagsForUser)){
						// when followed tag was not in top active tag, then last element of top active tag will automatically be removed after addition of this newly added tag to sorted set
						$this->predisLib->removeMembersOfSet('tagFollowers:tag:'.$tagFromWhereUserIdToBeRemoved,array($userId), TRUE);
					}
					$this->predisLib->executePipeLine();
					$this->_insertDataInQueue($userId, 'follow', $entityId, $entityType);
				
				}elseif ($entityType == 'user'){
					$this->predisLib->setPipeLine();
					$this->predisLib->addMembersToSet('userFollowers:user:'.$entityId, array($userId), TRUE);
					$this->predisLib->addMembersToSet('userFollows:user:'.$userId, array($entityId), TRUE);
					$this->predisLib->executePipeLine();
				}
				
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			
			return ;
		}
		
		/**
		 *	@param		int userId	: UserId of user who performed action
		 *	@param		int entityId
		 *	@param		string entityType	: any one of (discussion,question,tag,user)
		 *	@example	userId = 3286336, entityId = 2378, entityType = discussion
		 *	@example	userId = 3467723, entityId = 2367233, entityType = 'user'
		 */
		public function storeUserActionUnfollow( $userId, $entityId, $entityType) {
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Entity ID');
				Contract::mustBeNonEmptyVariable($entityType, 'Entity Type');
				if(!in_array($entityType, $this->allowedEntityForActions['unfollow'])){
					return ;
				}
				
				$this->checkIfInActiveUser($userId);
				
				if(in_array($entityType,array('discussion','question'))){
					$this->predisLib->setPipeLine();
					$this->predisLib->removeMembersOfSet('threadFollowers:thread:'.$entityId,array($userId),TRUE);
					$this->predisLib->addMembersToSet('threadUnfollowers:thread:'.$entityId,array($userId), TRUE);
					$this->_updateThreadQualityParameter($entityId, 'threadUnfollow', TRUE);
					$this->predisLib->executePipeLine();
				}elseif ($entityType == 'tag'){
					$this->predisLib->setPipeLine();
					$this->predisLib->removeMembersOfSet('userFollowsTagExplicitly:user:'.$userId, array($entityId), TRUE);
					$this->predisLib->addMembersToSet('tagUnfollowers:tag:'.$entityId, array($userId), TRUE);
					$this->predisLib->removeMembersInSortedSet('userFollowsTag:user:'.$userId, array($entityId), TRUE);
					$this->predisLib->removeMembersOfSet('tagFollowers:tag:'.$entityId, array($userId), TRUE);
					$this->predisLib->executePipeLine();
					$this->_insertDataInQueue($userId, 'unfollow', $entityId, $entityType);
				}elseif ($entityType == 'user'){
					$this->predisLib->setPipeLine();
					$this->predisLib->removeMembersOfSet('userFollowers:user:'.$entityId, array($userId), TRUE);
					$this->predisLib->removeMembersOfSet('userFollows:user:'.$userId, array($entityId), TRUE);
					$this->predisLib->executePipeLine();
				}
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			
			return ;
		}
		
		/**
		 *	@param		int userId	: UserId of user who performed action
		 *	@param		int entityId
		 *	@param		string entityType	: any one of (discussion/question)
		 *	@param		int upvoteId	: Id on which upvote is performed
		 *	@example	userId = 9823892, entityId = 34892, entityType = 'discussion', upvoteId => 34
		 */
		public function storeUserActionUpvote( $userId, $entityId, $entityType, $upvoteId) {
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Entity ID');
				Contract::mustBeNonEmptyVariable($entityType, 'Entity Type');
				Contract::mustBeNumericValueGreaterThanZero($upvoteId, 'Upvote ID');
				if(!in_array($entityType, $this->allowedEntityForActions['upvote'])){
					return ;
				}
				
				$this->checkIfInActiveUser($userId);
				
				$this->predisLib->setPipeLine();
				$this->_updateThreadQualityParameter($entityId, 'threadUpvote', TRUE);
				$this->predisLib->executePipeLine();
				$this->_insertDataInQueue($userId, 'upvote', $entityId, $entityType, array('upvoteId' => $upvoteId));
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			
			return ;
		}
		
		/**
		 *	@param		int userId : UserId of user who performed action
		 *	@param		int entityId
		 *	@param		string entityType	: any one of (discussion/question)
		 *	@param		int downvoteId	: downvote id on this entity
		 *	@example	userId = 1289122, entityId = 2378, entityType = question, downvoteId = 12
		 */
		public function sotreUserActionDownvote($userId, $entityId, $entityType, $downvoteId) {
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Entity ID');
				Contract::mustBeNonEmptyVariable($entityType, 'Entity Type');
				Contract::mustBeNumericValueGreaterThanZero($downvoteId, 'Down Vote ID');
				if(!in_array($entityType, $this->allowedEntityForActions['downvote'])){
					return ;
				}
				
				$this->checkIfInActiveUser($userId);
				
				$this->predisLib->setPipeLine();
				$this->_updateThreadQualityParameter($entityId, 'threadDownvote', TRUE);
				$this->predisLib->executePipeLine();
				$this->_insertDataInQueue($userId, 'downvote', $entityId, $entityType, array('downvoteId' => $downvoteId));
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			
			return ;
		}
		
		/**
		 *	@param		int userId	: UserId of User who performed action
		 *	@param		int entityId
		 *	@param		string entityType	: entityType can only be 'question'
		 *	@param		int answerId
		 *	@param		int userLevel	: level of user who answered
		 *	@example	userId = 2389238, entityId = 2331, entityType = question, answerId = 12
		 */
		public function storeUserActionAnswer($userId, $entityId, $entityType, $answerId, $userLevel) {
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Entity ID');
				Contract::mustBeNonEmptyVariable($entityType, 'Entity Type');
				Contract::mustBeNumericValueGreaterThanZero($answerId, 'Answer ID');
				
				if(!in_array($entityType, $this->allowedEntityForActions['answer'])){
					return ;
				}
				
				$this->checkIfInActiveUser($userId);
				
				$this->predisLib->setPipeLine();
				$this->predisLib->addMembersToSet('threadContributors:thread:'.$entityId, array($userId), TRUE);
				$this->_updateThreadQualityParameter($entityId, 'threadAnswer', TRUE);
				$this->predisLib->executePipeLine();
				$this->_insertDataInQueue($userId, 'answer', $entityId, $entityType, array('answerId' => $answerId));
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			
			return ;
		}
		
		/**
		 *	@param		int userId	: UserId of user who performed action
		 *	@param		int entityId
		 *	@param		string entityType : can only be 'discussion'
		 *	@param		int commentId	: id comment performed on this entity
		 *	@param		int userLevel	: level of user who commented
		 *	@example	userId = 2388912, entityId = 246, entityType = 'question', commentId = 11
		 */
		public function storeUserActionComment($userId, $entityId, $entityType, $commentId, $userLevel) {
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Entity ID');
				Contract::mustBeNonEmptyVariable($entityType, 'Entity Type');
				Contract::mustBeNumericValueGreaterThanZero($commentId, 'Comment ID');
				
				if(!in_array($entityType, $this->allowedEntityForActions['comment'])){
					return ;
				}
				
				$this->checkIfInActiveUser($userId);
				
				$this->predisLib->setPipeLine();
				$this->predisLib->addMembersToSet('threadContributors:thread:'.$entityId, array($userId), TRUE);
				$this->_updateThreadQualityParameter($entityId, 'threadComment', TRUE);
				$this->predisLib->executePipeLine();
				$this->_insertDataInQueue($userId, 'comment', $entityId, $entityType, array( 'commentId' => $commentId));
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			
			return ;
		}
		
		/**
		 *	@param		int userId	: UserId of User who performed action
		 *	@param		int entityId
		 *	@param		string entityType	: any one of (question,discussion)
		 *	@param		int deletedChild	: any one of (answer/comment)
		 *	@example	userId = 1289812, entityId = 564, entityType = question, deleteChild = answer
		 */
		public function deleteEntity($userId, $entityId, $entityType, $deletedChild='none'){
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Entity ID');
				Contract::mustBeNonEmptyVariable($entityType, 'Entity Type');
				//Contract::mustBeNonEmptyVariable($deletedChild, 'Deleted Child');
				
				if(!in_array($entityType, $this->allowedEntityForActions['delete'])){
					return ;
				}
				
				
				$threadQualityParam = "";
				if($deletedChild == 'comment'){
					$threadQualityParam = 'deleteComment';
					$this->predisLib->setPipeLine();
					$this->_updateThreadQualityParameter($entityId, $threadQualityParam, TRUE);
					$this->predisLib->executePipeLine();
				}elseif ($deletedChild == 'answer'){
					$threadQualityParam = 'deleteAnswer';
					$this->predisLib->setPipeLine();
					$this->_updateThreadQualityParameter($entityId, $threadQualityParam, TRUE);
					$this->predisLib->executePipeLine();
				}elseif (in_array($entityType, array('discussion', 'question'))){
					$this->predisLib->setPipeLine();
					$this->predisLib->removeMembersFromListByValue('userHomeFeedStaticCuratedBucket', 0, $entityType.':thread:'.$entityId, TRUE);
					$this->predisLib->removeMembersOfSet('computeThreadQualityQueue', array($entityId), TRUE);
					$threadRelatedKeysToDelete = array(	'threadQualityProperty:thread:'.$entityId,
														'threadContributors:thread:'.$entityId,
														'threadTags:thread:'.$entityId
					);
					$this->predisLib->deleteKey($threadRelatedKeysToDelete, TRUE);
					$this->predisLib->executePipeLine();
				}elseif ($entityType == 'tag'){
					$this->CI->load->model('common/personalizationmodel');
					$personalizationmodel = new personalizationmodel();
					// get all threads which has tags attached to this tag
					$tagsMappedToThreads = $personalizationmodel->getThreadsAttachedToTag(array($entityId));
					// get all users who follow this tag explicitly
					$usersFollowTagExplicitly = $personalizationmodel->getUsersFollowingTags(array($entityId));
					$this->predisLib->setPipeLine();
					// remove this tag from all threads which are attached to this tag from Redis
					foreach ($tagsMappedToThreads[$entityId] as $value){
						$this->predisLib->removeMembersInSortedSet('threadTags:thread:'.$value, array($entityId), TRUE);
					}
					// get all active users on this tag from redis
					$tagActiveFollowers = $activeUsersForTag = $this->predisLib->getMembersOfSet('tagFollowers:tag:'.$entityId);
					// from all active users of this tag remove this tag in redis
					foreach ($tagActiveFollowers as $value){
						$this->predisLib->removeMembersInSortedSet('userFollowsTag:user:'.$value, array($entityId), TRUE);
					}
					// also remove this tag from all users who follows explicitly in Redis
					foreach ($usersFollowTagExplicitly[$entityId] as $value){
						$this->predisLib->removeMembersOfSet('userFollowsTagExplicitly:user:'.$value, array($entityId), TRUE);
					}
					// get top contibutors on this tag in Redis
					$topContributorsOnTag = $this->predisLib->getMembersOfSet('tagsTopContributors:'.$entityId);
					// from all top contributors of this tag remove this tag in Redis
					foreach ($topContributorsOnTag as $value){
						$this->predisLib->removeMembersOfSet('usersActiveTags:'.$value, array($entityId), TRUE);
					}
					
					// Now at last delete all keys on this tag
					$tagRelatedKeys = array('tagFollowers:tag:'.$entityId, 'tagUnfollowers:tag:'.$entityId, 'tagsTopContributors:'.$entityId);
					$this->predisLib->deleteKey($tagRelatedKeys, TRUE);
					$this->predisLib->executePipeLine();
				}
				
				/* if(!empty($threadQualityParam)){
					$this->_updateThreadQualityParameter($entityId, $threadQualityParam, TRUE);
				} */
				
				
			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
			
			return ;
		}
		
		/**
		 * 
		 * We will store tags for threads in redis. This will be stored in sorted set. tags with value 0 are directly attached to thread.
		 * While tags with value 1 are parent tags.
		 * 
		 * @param	int userId : ID of user who owns the thread
		 * @param	int $entityId : Id of question/discussion
		 * @param	string $entityType : either of question/discussion
		 * @param	array $tagIds : 2-D array of tag ids attached to thread(discussion/question). Array('direct' => array(tagIds),'parent' => array(tagIds))
		 * @param	string $action : action which is performed on thread related to tag. ('threadpost','updatetag','insert_specific')
		 * @example	entityId=12454, entityType='question', tagIds=array('direct' => array(123,242,124), 'parent' => array(267,281,981))
		 * 
		 */
		public function updateTagOfThreads($userId=0, $entityId, $entityType, $tagIdsCategorizedArray = array(),$action="updatetag"){
			try {
				Contract::mustBeNumericValueGreaterThanZero($userId, 'User ID');
				Contract::mustBeNumericValueGreaterThanZero($entityId, 'Entity ID');
				if(!in_array($entityType, array('discussion','question'))){
					throw new Exception('Entity Type not allowed to update tag IDs in Redis');
				}
				
				$this->checkIfInActiveUser($userId);
				
				$tagIds = array();
				foreach ($tagIdsCategorizedArray['parent'] as $value){
					if(isset($value) && $value > 0){
						$tagIds[$value] = 1;
					}
				}
				foreach ($tagIdsCategorizedArray['direct'] as $value){
					if(isset($value) && $value > 0){
						$tagIds[$value] = 0;
					}
				}
				
				//Contract::mustBeNonEmptyArrayOfIntegerValues($tagIds, 'Tag IDS Array');

				//if(count($tagIds) > 0){
					$this->predisLib->setPipeLine();
	
					if($action == 'updatetag'){
						$threadTagsBefore = $this->predisLib->getMembersInSortedSet('threadTags:thread:'.$entityId, 0, -1, FALSE,TRUE);
						$newTagIdsAddedOnThread = array_keys(array_diff_key($tagIds, $threadTagsBefore));
						$this->predisLib->deleteKey(array('threadTags:thread:'.$entityId),TRUE);
						if(count($tagIds) > 0){
							$this->predisLib->addMembersToSortedSet('threadTags:thread:'.$entityId, $tagIds, TRUE);
						}
					}elseif(in_array($action, array('threadpost','insert_specific'))){
						if($action == 'insert_specific'){
							$newTagIdsAddedOnThread = array_keys($tagIds);
						}
						if(count($tagIds) > 0){
							$this->predisLib->addMembersToSortedSet('threadTags:thread:'.$entityId, $tagIds, TRUE);
						}
					}else {
						return ;
					}
					
					$this->predisLib->executePipeLine();
				//}
				
				$optionalArray = array();
				if(is_array($newTagIdsAddedOnThread) && count($newTagIdsAddedOnThread) > 0){
					$optionalArray['tagIds'] = $newTagIdsAddedOnThread;
				}
				
				// TO DO 
				/*else if ($action == "delete_specific") {
					$this->predisLib->removeMembersOfSet('threadTags:thread:'.$entityId, $tagIds, TRUE);	
				}*/
				
				$actionForQueue = ($action == 'threadpost')?'post':'updatetag';
				
				// insert data in rabbitMQ to compute users active tags
				$this->_insertDataInQueue($userId, $actionForQueue, $entityId, $entityType, $optionalArray);


			} catch (Exception $e) {
				error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
			}
		}
	}
?>
