<?php
	class PersonalizedData{
		private $actionByUserId;
		private $actionByUserDisplayName;
		private $action;
		private $threadId;
		private $threadType;
		private $actionItemId;
		private $tagId;
		private $reason;
		private $isFreind;
		private $isFollower;
		private $actionDateTime;
		private $sortingScore;
		private $tagsAttachedToThread = array();
		
		public function setActionByUserId($actionByUserId){
			$this->actionByUserId = $actionByUserId;
		}
		
		public function getActionByUserId(){
			return $this->actionByUserId;
		}
		
		public function setActionByUserDisplayName($actionByUserDisplayName){
			$this->actionByUserDisplayName = $actionByUserDisplayName;
		}
		
		public function getActionByUserDisplayName(){
			return $this->actionByUserDisplayName;
		}
		
		public function setAction($action){
			$this->action = $action;
		}
		
		public function getAction(){
			return $this->action;
		}
		
		public function setThreadId($threadId){
			$this->threadId = $threadId;
		}
		
		public function getThreadId(){
			return $this->threadId;
		}
		
		public function setThreadType($threadType){
			$this->threadType = $threadType;
		}
		
		public function getThreadType(){
			return $this->threadType;
		}
		
		public function setActionItemId($actionItemId){
			$this->actionItemId = $actionItemId;
		}
		
		public function getActionItemId(){
			return $this->actionItemId;
		}
		
		public function setTagId($tagId){
			$this->tagId = $tagId;
		}
		
		public function getTagId(){
			return $this->tagId;
		}
		
		public function setReason($reason){
			$this->reason = $reason;
		}
		
		public function getReason(){
			return $this->reason;
		}
		
		public function setIsFreind($isFreind){
			$this->isFreind = $isFreind;
		}
		
		public function isFreind(){
			return $this->isFreind;
		}
		
		public function setIsFollower($isFollower){
			$this->isFollower = $isFollower;
		}
		
		public function isFollower(){
			return $this->isFollower;
		}
		
		public function setActionDateTime($actionDateTime){
			$this->actionDateTime = $actionDateTime;
		}
		
		public function getActionDateTime(){
			if(!empty($this->actionDateTime)){
				$actionTime = strtotime($this->actionDateTime);
				return date('Y-m-d H:i:s',$actionTime);
			}else {
				return FALSE;
			}
		}
		
		public function setSortingScore($sortingScore){
			$this->sortingScore = $sortingScore;
		}
		
		public function getSortingScore(){
			return $this->sortingScore;
		}
		
		public function setTagsAttachedToThread($tagsAttachedToThread = array()){
			$this->tagsAttachedToThread = $tagsAttachedToThread;
		}
		
		public function getTagsAttachedToThread(){
			return $this->tagsAttachedToThread;
		}
		
		public function getHomePageMessage(){
			$message = "";
			switch ($this->getAction()){
				case "post"		:	if($this->isFollower() || $this->isFreind()){
										if($this->getThreadType() == "discussion"){
											$message = /* $this->getActionByUserDisplayName(). */'started discussion';
										}elseif ($this->getThreadType() == "user"){
											$message = /* $this->getActionByUserDisplayName(). */'asked question';
										}else {
											$message = "";
										}
										if($this->getTagId() > 0){
											$message .= " on <tagName>";
										}
									}else {
										if($this->getThreadType() == "discussion" && $this->getTagId() > 0){
											$message = 'New discussion on <tagName>';
										}elseif ($this->getThreadType() == "user" && $this->getTagId() > 0){
											$message = 'New question on <tagName>';
										}elseif ($this->getThreadType() == "user" && $this->getTagId() <= 0){
											$message = 'Recent question';
										}else {
											$message = "";
										}
									}
									break;
				case "answer"	:	if($this->isFollower() || $this->isFreind()){
										$message = /* $this->getActionByUserDisplayName(). */'answered';
										if ($this->getReason() == "threadowner"){
											$message = " answered on the question you had asked";
										}elseif($this->getReason() == "threadcontributor") {
											$message = " answered on the question you had contributed";
										}elseif ($this->getReason() == "threadfollower"){
											$message = " answered on the question you follow";
										}elseif ($this->getReason() == "tagfollower"){
											$message = " posted answer on <tagName>";
										}
									}elseif ($this->getReason() == "threadowner"){
										$message = "New answer on the question you had asked";
									}elseif($this->getReason() == "threadcontributor") {
										$message = "New answer on the question you had contributed";
									}elseif ($this->getReason() == "threadfollower"){
										$message = "New answer on the question you follow";
									}elseif ($this->getReason() == "tagfollower"){
										$message = "New answer posted on <tagName>";
									}else{
										$message = "";
									}
									break;
				case "comment"	:	if($this->isFollower() || $this->isFreind()){
										$message = /* $this->getActionByUserDisplayName(). */'commented';
										if ($this->getReason() == "threadowner"){
											$message = " commented on the discussion you had started";
										}elseif($this->getReason() == "threadcontributor") {
											$message = " commented on the discussion you had contributed";
										}elseif ($this->getReason() == "threadfollower"){
											$message = " commented on the discussion you follow";
										}elseif ($this->getReason() == "tagfollower"){
											$message = " posted comment on <tagName>";
										}
									}elseif ($this->getReason() == "threadowner"){
										$message = "New comment on the discussion you had started";
									}elseif($this->getReason() == "threadcontributor") {
										$message = "New comment on the discussion you had contributed";
									}elseif ($this->getReason() == "threadfollower"){
										$message = "New comment on the discussion you follow";
									}elseif ($this->getReason() == "tagfollower"){
										$message = "New comment posted on <tagName>";
									}else {
										$message = "";
									}
									break;
				/* case "share"	:	if($this->isFollower() || $this->isFreind()){
										if($this->getThreadType() == "discussion"){
											$message = $this->getActionByUserDisplayName().'shared discussion';
										}elseif ($this->getThreadType() == "user"){
											$message = $this->getActionByUserDisplayName().'shared question';
										}else {
											$message = "";
										}
									}else {
										if($this->getThreadType() == "discussion"){
											$message = 'Discussion sahred';
										}elseif ($this->getThreadType() == "user"){
											$message = 'Question shared';
										}else {
											$message = "";
										}
									}
									break; */
				case "upvote"	:	if($this->isFollower() || $this->isFreind()){
										if($this->getThreadType() == "discussion"){
											$message = /* $this->getActionByUserDisplayName(). */'upvoted this comment';
										}elseif ($this->getThreadType() == "user"){
											$message = /* $this->getActionByUserDisplayName(). */'upvoted this answer';
										}else {
											$message = "";
										}
									}/* else {
										if($this->getThreadType() == "discussion"){
											$message = 'Upvote on comment';
										}elseif ($this->getThreadType() == "user"){
											$message = 'Upvote on answer';
										}else {
											$message = "";
										}
									} */
									break;
				case "follow"	:	if($this->isFollower() || $this->isFreind()){
										if($this->getThreadType() == "discussion"){
											$message = /* $this->getActionByUserDisplayName(). */'followed discussion';
										}elseif ($this->getThreadType() == "user"){
											$message = /* $this->getActionByUserDisplayName(). */'followed question';
										}else {
											$message = "";
										}
									}/* else {
										if($this->getThreadType() == "discussion"){
											$message = 'New follower on discussion';
										}elseif ($this->getThreadType() == "user"){
											$message = 'New follower on question';
										}else {
											$message = "";
										}  
									} */
									break;
				default			:	break;
			}
			
			if($message == ""){
				if($this->getReason() == "topContent"){
					$message = "Top Content";
				}
			}
			
			return $message;
		}
		
		/**
		 * Function to get Home Feed String as it is stored in redis.
		 * Home Feed String Pattern is as below
		 * actionByUser:action:threadId:actionTime:actionItemId:tagId:reason:sortingScore
		 *  
		 */
		public function getHomeFeedString(){
			$homeFeedString = '';
			$homeFeedString .= $this->actionByUserId.':'; // first append userId of user who performed action
			// then append action. if no action, then append blank. But do not leave blank.
			if($this->getAction() == 'post'){
				$homeFeedString .= '3:';
			}elseif ($this->getAction() == 'answer'){
				$homeFeedString .= '1:';
			}elseif ($this->getAction() == 'comment'){
				$homeFeedString .= '2:';
			}else{
				$homeFeedString .= ':';
			}
			$homeFeedString .= $this->threadId.':';
			$homeFeedString .= $this->actionDateTime.':';
			$homeFeedString .= $this->actionItemId.':';
			$homeFeedString .= $this->tagId.':';
			$homeFeedString .= $this->reason.':';
			$homeFeedString .= $this->sortingScore.':';
			return $homeFeedString;
		}
		
	}