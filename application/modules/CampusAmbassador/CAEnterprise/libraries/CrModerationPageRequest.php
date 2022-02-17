<?php

class CrModerationPageRequest{
	private $start = 0;
	private $count = 10;
	private $reviewerUserId;
	private $reviewerUserEmail;
	public  $checkForSearchCall;
	private $filterEmail;
	private $sortCriteria;
	private $statusFilter;
	private $reasonFilter;
	private $instituteId;
	private $typeFilter;
	private $categoryFilter;
	private $moderator_list;
	private $phone_search;
	private $posted_timeRangeFrom;
	private $posted_timeRangeTo;
	private $moderated_timeRangeFrom;
	private $moderated_timeRangeTo;
	private $streamId;
	private $review_filter_postfix;

	public function __construct(){
		$this->CI = & get_instance();
	}

	function setPostData(){		
		$this->checkForSearchCall      = $this->CI->input->post('checkSearchCall',true);
		if($this->checkForSearchCall == 'email'){
			$this->filterEmail             = $this->CI->input->post('email',true);			
			$this->statusFilter            = 'All';
		}else{
			$this->statusFilter            = $this->CI->input->post('statusFilter',true);					
			$this->sortCriteria            = $this->CI->input->post('sortReviews',true);
			$this->reasonFilter            = $this->CI->input->post('reasonFilter',true);
			$this->instituteId             = $this->CI->input->post('instituteName',true);
			$this->typeFilter              = $this->CI->input->post('typeFilter',true);
			$this->categoryFilter          = $this->CI->input->post('categoryFilter',true);
			$this->sourceFilter            = $this->CI->input->post('sourceFilter',true);
			$this->moderator_list          = $this->CI->input->post('moderator_list',true);
			$this->phone_search            = $this->CI->input->post('phone_search',true);
			$this->posted_timeRangeFrom    = $this->CI->input->post('posted_timeRangeFrom',true);
			$this->posted_timeRangeTo      = $this->CI->input->post('posted_timeRangeTo',true);
			$this->moderated_timeRangeFrom = $this->CI->input->post('moderated_timeRangeFrom',true);
			$this->moderated_timeRangeTo   = $this->CI->input->post('moderated_timeRangeTo',true);
			$this->streamId                = $this->CI->input->post('streamId',true);
			$this->all_reject_flag         = $this->CI->input->post('rejectRevId');
		}
		
	}


	function setReviewerInfoData($reviewerInfo){
		$this->reviewerUserId    = $reviewerInfo['userid'];
		
		$validity                = $reviewerInfo['validity'];
		$cookiestr               = explode('|', $validity[0]['cookiestr']);
		$useremail               = $cookiestr[0];
		$this->reviewerUserEmail = $cookiestr[0];		
	}

	function getReviewerUserId(){
		return isset($this->reviewerUserId)?$this->reviewerUserId : 0;
	}

	function getReviewerUserEmail(){
		return $this->reviewerUserEmail;
	}

	function getReasonFilterId(){
		$this->reasonFilter   = !empty($this->reasonFilter)?$this->reasonFilter:'All';
		return $this->reasonFilter;
	}

	function getSource(){
		$this->sourceFilter   = !empty($this->sourceFilter)?$this->sourceFilter:'All';
		return $this->sourceFilter;
	}

	
	function getPhoneNumberFilter(){
		return $this->phone_search;
	}

	function getPostedDateFrom(){
		return $this->posted_timeRangeFrom;
	}

	function getPostedDateTo(){
		return $this->posted_timeRangeTo;	
	}

	function getModeratedDateFrom(){
		return $this->moderated_timeRangeFrom;
	}

	function getModeratedDateTo(){
		return $this->moderated_timeRangeTo;
	}

	function getModeratorsList(){
		return $this->moderator_list;
	}
	
	function getStreamId(){
		$streamId  = !empty($this->streamId)? $this->streamId :'All';
		return $streamId;
	}
	
	function getAllRejectRev(){
		return $this->all_reject_flag;
	}

	function getStatusFilter(){
		$this->statusFilter   = !empty($this->statusFilter)?$this->statusFilter:'Pending';
		return $this->statusFilter;
	}

	function getReviewMappingType(){
		return $this->typeFilter;
	}


	function getResultCount(){
		return $this->count;
	}

	function getFilterByEmail(){
		return $this->filterEmail;
	}

	function getStart(){
		return $this->start;
	}	

	function setStart($start){
		$this->start = $start;
		return $this;
	}	

	function getInstituteId(){
		$instituteId  = !empty($this->instituteId)? $this->instituteId :'';
		return $instituteId;		
	}

	function getTypeFilter(){
		$typeFilter  = !empty($this->typeFilter)? $this->typeFilter :'';
		return $typeFilter;
	}

	function getSortCriteria(){
		$sortCriteria  = !empty($this->sortCriteria)? $this->sortCriteria :'';
		return $sortCriteria;
	}

	function getCategoryFilter(){
		$categoryFilter  = !empty($this->categoryFilter)? $this->categoryFilter :'All';
		return $categoryFilter;	
	}

	// need to set all post data
	// generateReviewsModeratorMap
	// whitelist email for filters
	function generateReviewsModeratorMap(){	
		if($this->checkForSearchCall == 'email'){
			return ;
		}
		$userId = $this->reviewerUserId;	

		if($userId<1){
			return false;
		}

		$this->CI->load->config('CollegeReviewForm/collegeReviewConfig');
		$showAllReviewsUserId = $this->CI->config->item('showAllReviewsUserId');
		if($showAllReviewsUserId[$userId]){
			return '';
		}

		$moderatorMapWithDigit = $this->CI->config->item('moderatorMapWithDigit');		
		$mapVal = $moderatorMapWithDigit[$userId];
		unset($moderatorMapWithDigit);

		foreach ($mapVal['tens'] as $tensDigit) {
			foreach ($mapVal['ones'] as $onesDigit) {
				$userReviewMap[] = $tensDigit.$onesDigit;
			}
		}

		if(empty($userReviewMap)|| count($userReviewMap)<1){
			$userReviewMap[] =-1;
		}
		$this->review_filter_postfix = $userReviewMap;

		return $userReviewMap;
	}

	function isShowModeratedByFilter(){
		$showModeratedByFilter 	 = false;
		$whiteListEmailForFilter = $this->CI->config->item('whiteListEmailForFilter');
		
		if(in_array($this->reviewerUserEmail, $whiteListEmailForFilter)){
			$showModeratedByFilter = true;
		}

		return $showModeratedByFilter;
	}
}