<?php

class reviewRecommendationModel extends MY_Model {
	
	private $dbHandle = '';
	
	function __construct(){
		parent::__construct('ContentRecommendation');
		$this->dbHandle = $this->getReadHandle();
		$this->load->helper('ContentRecommendation/recommend');
	}

	public function getListingReviews($listingId,$listingType,$exclusionList=array(), $selectedRatingFilter = 0,$selectedTagId = 0,$onlyPublished=true){
	
		if(!is_array($listingId) && $listingId != '' && $listingId > 0){
			$listingId = array($listingId);
		}
		$listingId = array_filter($listingId);
		if(count($listingId) <= 0 || !is_array($listingId)){
			return array();
		}
		if(!in_array($listingType, array('institute','course'))){
			return array();
		}
		
		$this->dbHandle->where($listingType.'Id in '. "(".implode(",", $listingId).")");
		if (!$onlyPublished) {
			$this->dbHandle->where('(CollegeReview_MainTable.status = "published" OR CollegeReview_MainTable.status = "unverified")');
		}
		else{
			$this->dbHandle->where('CollegeReview_MainTable.status', 'published');
		}
		
		$exclusionClause = getExclusionClause($exclusionList,'reviewId');
		if($exclusionClause!=''){
			$this->dbHandle->where($exclusionClause);
		}

		$selectClause = '';
		if($listingType=='institute'){
			$selectClause = 'instituteId,';
		}

		$selectClause .= ' reviewId, courseId, yearOfGraduation, creationDate, helpfulFlagCount, notHelpfulFlagCount, reviewQuality, anonymousFlag , CollegeReview_MainTable.status as status';
		$this->dbHandle->select($selectClause);
		
		$this->dbHandle->from('CollegeReview_MappingToShikshaInstitute');
		$this->dbHandle->join('CollegeReview_MainTable','CollegeReview_MappingToShikshaInstitute.reviewId=CollegeReview_MainTable.id');

		if($selectedTagId >0){
			$this->dbHandle->join('reviews_highlighted',
					'CollegeReview_MainTable.id=reviews_highlighted.review_id');
			$this->dbHandle->where('reviews_highlighted.topic_id ='.$selectedTagId);
			$this->dbHandle->where('reviews_highlighted.status', 'live');
		}

		
		if($selectedRatingFilter > 0 && $selectedRatingFilter < 5) {
			$this->dbHandle->where('averageRating <= '.($selectedRatingFilter+1).' AND averageRating > '.$selectedRatingFilter);
		}

		$query = $this->dbHandle->get();
		$result = $query->result_array();
		$reviews = array();
		foreach ($result as $row) {
			$temprow = array();
			$temprow['courseId'] = $row['courseId'];
			$temprow['yearOfGraduation'] = $row['yearOfGraduation'];
			$temprow['creationDate'] = $row['creationDate'];
			$temprow['anonymousFlag'] = $row['anonymousFlag'];
			$temprow['helpfulFlagCount'] = $row['helpfulFlagCount'];
			$temprow['notHelpfulFlagCount'] = $row['notHelpfulFlagCount'];
			$temprow['reviewQuality'] = $row['reviewQuality'];
			$temprow['status'] = $row['status'];
			
			$reviews[$row[$listingType.'Id']][$row['reviewId']] = $temprow;
		}
		return $reviews;
	}

	public function getSortedListingReviewsByFactor($listingId,$listingType,$exclusionList=array(),$count=5,$offset=0,$orderByFactor, $prefetchedReviewCount = 0, $selectedRatingFilter = 0,$selectedTagId = 0,$onlyPublished=true){
		
		if(!is_array($listingId) && $listingId != '' && $listingId > 0){
			$listingId = array($listingId);
		}
		$listingId = array_filter($listingId);
		if(count($listingId) <= 0 || !is_array($listingId)){
			return array();
		}
		if(!in_array($listingType, array('institute','course'))){
			return array();
		}

		$orderByClause = $this->_getOrderbyClause($orderByFactor);
    	if($orderByClause!=''){
    		if($onlyPublished == false){
    			$this->dbHandle->order_by("CollegeReview_MainTable.status,".$orderByClause);
    		}
    		else{
    			$this->dbHandle->order_by($orderByClause);
    		}
    	}
		
		$this->dbHandle->where($listingType.'Id in '. "(".implode(",", $listingId).")");
		
		$exclusionClause = getExclusionClause($exclusionList,'reviewId');
		if($exclusionClause!=''){
			$this->dbHandle->where($exclusionClause);
		}

		$selectClause = 'distinct(reviewId)';
		$this->dbHandle->select($selectClause);
		$this->dbHandle->from('CollegeReview_MappingToShikshaInstitute');
		$this->dbHandle->join('CollegeReview_MainTable','CollegeReview_MappingToShikshaInstitute.reviewId=CollegeReview_MainTable.id');
		if($selectedTagId >0){
			$this->dbHandle->join('reviews_highlighted',
				'CollegeReview_MainTable.id=reviews_highlighted.review_id');
			$this->dbHandle->where('reviews_highlighted.topic_id ='.$selectedTagId);
		$this->dbHandle->where('reviews_highlighted.status', 'live');
		}
		$this->dbHandle->limit($count,$offset);
		
		if($selectedRatingFilter > 0 && $selectedRatingFilter < 5) {
			$this->dbHandle->where('averageRating <= '.($selectedRatingFilter+1).' AND averageRating > '.$selectedRatingFilter);
		}
		if($onlyPublished == false){
			$this->dbHandle->where('(CollegeReview_MainTable.status = "published" OR CollegeReview_MainTable.status = "unverified")');
		}
		else{
			$this->dbHandle->where('CollegeReview_MainTable.status', 'published');
		}
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		$reviews = array();
		foreach ($result as $row) {
			$reviews[]=$row['reviewId'];
		}
		
			
		if($prefetchedReviewCount > 0) {
			$result[0]['cnt'] = $prefetchedReviewCount;	
		}
		else {
			//fetch total count
			$this->dbHandle->where($listingType.'Id in '. "(".implode(",", $listingId).")");
			if($onlyPublished == false){
				$this->dbHandle->where('(CollegeReview_MainTable.status = "published" OR CollegeReview_MainTable.status = "unverified")');
			}
			else{
				$this->dbHandle->where('CollegeReview_MainTable.status', 'published');
			}
			
			$exclusionClause = getExclusionClause($exclusionList,'reviewId');
			if($exclusionClause!=''){
				$this->dbHandle->where($exclusionClause);
			}
			$selectClause = 'count(distinct(reviewId)) as count,CollegeReview_MainTable.status as status';
			$this->dbHandle->select($selectClause);
			
			$this->dbHandle->from('CollegeReview_MappingToShikshaInstitute');
			$this->dbHandle->join('CollegeReview_MainTable','CollegeReview_MappingToShikshaInstitute.reviewId=CollegeReview_MainTable.id');
			if($selectedTagId >0){
				$this->dbHandle->join('reviews_highlighted',
					'CollegeReview_MainTable.id=reviews_highlighted.review_id');
				$this->dbHandle->where('reviews_highlighted.topic_id ='.$selectedTagId);
				$this->dbHandle->where('reviews_highlighted.status', 'live');
			}
			if($onlyPublished == false){
				$this->dbHandle->group_by("CollegeReview_MainTable.status");
			}
			if($selectedRatingFilter > 0 && $selectedRatingFilter < 5) {
				$this->dbHandle->where('averageRating <= '.($selectedRatingFilter+1).' AND averageRating > '.$selectedRatingFilter);
			}
			$query = $this->dbHandle->get();
			$result = $query->result_array();
		}

		$response=array();
		if($reviews!=null && (count($result[0]['count'])>0 || count($result[0]['count'])>0)){
			$numFound = 0;
			$totalNumFound = 0;
			foreach ($result as $key => $count) {
				if ($count['status'] == "published") {
					$numFound = $count['count'];
				}
				$totalNumFound = $totalNumFound + $count['count'];
			}
			$response=array('topContent'=>$reviews,'numFound'=>$numFound,'totalNumFound'=>$totalNumFound);
		}
		
		return $response;
	}
	
	public function getListingsByListingsWithMinCourseReviews($listingIds,$listingType,$returnType='course',$onlyPublished = true){
		if(!is_array($listingIds)){
			return array();
		}
		$listingIds = array_unique($listingIds);
		$sql='';
		if($listingType=='institute'){
			if ($onlyPublished) {
				$sql="SELECT instituteId,courseId FROM
						CollegeReview_MappingToShikshaInstitute a 
						join CollegeReview_MainTable b
						on a.reviewId=b.id
						where b.status='published'
						AND a.instituteId in (?) 
						GROUP BY courseId";
			}
			else{
				$sql="SELECT instituteId,courseId FROM
						CollegeReview_MappingToShikshaInstitute a 
						join CollegeReview_MainTable b
						on a.reviewId=b.id
						where (b.status='published' OR b.status='unverified')
						AND a.instituteId in (?) 
						GROUP BY courseId";
			}

		}
		elseif ($listingType=='course') {
			if ($onlyPublished) {
				$sql="SELECT instituteId,courseId FROM
					CollegeReview_MappingToShikshaInstitute a 
					join CollegeReview_MainTable b
					on a.reviewId=b.id
					where b.status='published'
					AND a.courseId in (?) 
					GROUP BY courseId";
			}
			else{
				$sql="SELECT instituteId,courseId FROM
					CollegeReview_MappingToShikshaInstitute a 
					join CollegeReview_MainTable b
					on a.reviewId=b.id
					where (b.status='published' OR b.status='unverified')
					AND a.courseId in (?) 
					GROUP BY courseId";				
			}
		}
		else {
			return array();
		}

		$query = $this->dbHandle->query($sql, array($listingIds));
		$result = $query->result_array();
		
		$filteredIds = array();
		if($returnType=='institute'){
			foreach ($result as $value) {
				$filteredIds[] = $value['instituteId'];
			}
		}
		elseif ($returnType=='course') {
			foreach ($result as $value) {
				$filteredIds[] = $value['courseId'];
			}
		}

		$filteredIds=array_unique($filteredIds);
		return $filteredIds;
	}

	public function getInstituteReviewCount($courseIds){
		if(!is_array($courseIds)){
			return array();
		}
		$counts = array();
		if(count($courseIds)>0){
			$sql="SELECT sc.primary_id as instituteId,count(*) as cnt FROM
                                                CollegeReview_MappingToShikshaInstitute a 
                                                join CollegeReview_MainTable b
                                                on a.reviewId=b.id
                                                join shiksha_courses sc 
                                                on a.courseId = sc.course_id and sc.status = 'live' 
                                                where b.status='published'
                                                AND a.courseId in (?)
                                                GROUP BY sc.primary_id";
			$query = $this->dbHandle->query($sql, array($courseIds));
			$result = $query->result_array();
			foreach ($result as $value) {
					$counts[$value['instituteId']] = $value['cnt'];
			}
		}
		return $counts;
	}

	private function _getOrderbyClause($orderby){
        switch($orderby) {
            case 'RECENCY':
                return 'creationDate desc ';
            case 'GRADUATION_YEAR':
                return 'yearOfGraduation desc, creationDate desc ';
            case 'HIGHEST_RATING':
                return 'averageRating desc, creationDate desc ';
            case 'LOWEST_RATING':
                return 'averageRating asc, creationDate desc ';
            default:
                return '';
        }
    }

    //gets review count of those reviews for which placement data exists
    public function getInstituteReviewCountForPlacementData($courseIds){
		if(!is_array($courseIds)){
			return array();
		}
		$reviewCount = 0;
		if(count($courseIds) > 0){
			
			$sql = "SELECT course_id FROM 
              							shiksha_courses
              							WHERE status = 'live'
              							AND course_id IN (?)";			

            $query = $this->dbHandle->query($sql, array($courseIds));
			$result = $query->result_array();

			$filteredCourseIds = array();		
			foreach ($result as $key => $value) {
				$filteredCourseIds[] = $value['course_id'];
			}
			if(count($filteredCourseIds) > 0){
				$sql = "SELECT reviewId FROM 
              							CollegeReview_MappingToShikshaInstitute
              							WHERE courseId IN (?)";
				$query = $this->dbHandle->query($sql, array($filteredCourseIds));
				$result = $query->result_array();

				$reviewIds = array();
				foreach ($result as $key => $value) {
					$reviewIds[] = $value['reviewId'];
				}
				if(count($reviewIds) > 0){
					$sql = "SELECT count(*) AS cnt FROM 
														CollegeReview_MainTable 
														WHERE status='published' 
														AND placementDescription > ' ' 
														AND id IN (?)";				
					$query = $this->dbHandle->query($sql, array($reviewIds));
					$result = $query->result_array();
					$reviewCount = $result[0]['cnt'];	
				}
            
			}

		}
		return $reviewCount;
	}

}
?>
