<?php

class AlumniReviewsLibrary {

	private $CI;
	private $institute_repository;
	private $course_repository;
	
	function __construct() {
		$this->CI = &get_instance();
		$this->CI->load->builder('ListingBuilder','listing');
		$this->CI->load->model('alumni/alumni_reviews_model');
		$this->CI->load->library('alumniSpeakClient');
		
		$listingbuilder = new ListingBuilder();
		$this->institute_repository = $listingbuilder->getInstituteRepository();
		$this->course_repository 	= $listingbuilder->getCourseRepository();
		$this->alumni_reviews_model = new alumni_reviews_model();
		$this->alumni_speak_lib		= new alumniSpeakClient();
	}
	
	public function getAlumniRatingsForInstitute($instituteId = array()) {
		if(!empty($instituteId)){
			$alumnusDetailList = $this->alumni_reviews_model->getAlumnusDetaisByListingType($instituteId, 'institute', 'published');
			$ratingList 		= array();
			$reviews 			= array();
			$reviewsByCourse 	= array();
			$courseDetails 		= array();
			foreach($alumnusDetailList as $detail) {
				$criteriaId 	= $detail['criteria_id'];
				$criteriaName 	= $detail['criteria_name'];
				$criteriaRating = $detail['criteria_rating'];
				
				if(!array_key_exists($criteriaId, $ratingList)){
					$ratingList[$criteriaId] = array('votes' => 0, 'sum' => 0, 'total' => 0, 'name' => '');
				}
				
				$ratingList[$criteriaId]['sum'] 	= $ratingList[$criteriaId]['sum'] + $criteriaRating;
				$ratingList[$criteriaId]['total'] 	= $ratingList[$criteriaId]['total'] + 5;
				$ratingList[$criteriaId]['votes'] 	= $ratingList[$criteriaId]['votes'] + 1;
				$ratingList[$criteriaId]['name'] 	= $criteriaName;
				$courseId 	= $detail['course_id'];
				$email 		= $detail['email'];
				
				if(!empty($courseId) && $courseId != -1) {
					if(!array_key_exists($courseId, $courseDetails)) {
						$courseObj 	= $this->course_repository->find($courseId);
						$courseName = $courseObj->getName();
						$courseDetails[$courseId] = $courseName;
						$courseDisplayName = $courseName;
					} else {
						$courseDisplayName = $courseDetails[$courseId];
					}
				} else {
					$courseDisplayName = $detail['course_name'];
				}
				
				$detail['course_display_name'] = $courseDisplayName;
				if(!array_key_exists($email, $reviews)) {
					$reviews[$email]['details'] 	= array();
				}
				
				$reviews[$email]['details'][$detail['criteria_name']] = $detail;
				$time 		= $this->_getStandardTimeForUserReview($detail['time'], $reviews[$email]['details']);
				$reviews[$email]['timestamp'] = $time;
				
				if(!empty($courseId) && $courseId != -1) {
					if(!array_key_exists($courseId, $reviewsByCourse)){
						$reviewsByCourse[$courseId] = array();
						if(!array_key_exists($email, $reviewsByCourse[$courseId])){
							$reviewsByCourse[$courseId][$email]['details'] = array();
							$reviewsByCourse[$courseId][$email]['timestamp'] = '';
						}
					}
					$reviewsByCourse[$courseId][$email]['details'][$detail['criteria_name']] = $detail;
					$time 		= $this->_getStandardTimeForUserReview($detail['time'], $reviewsByCourse[$courseId][$email]['details']);
					$reviewsByCourse[$courseId][$email]['timestamp'] = $time;
				}
			}
			
			uasort($reviews, function($a, $b){
				if(strtotime($a['timestamp']) == strtotime($b['timestamp'])){
					return 0;
				}
				return (strtotime($a['timestamp']) < strtotime($b['timestamp'])) ? 1 : -1;
			});
			foreach($reviewsByCourse as $courseId => $details) {
				uasort($details, function($a, $b){
				if(strtotime($a['timestamp']) == strtotime($b['timestamp'])){
					return 0;
				}
				return (strtotime($a['timestamp']) < strtotime($b['timestamp'])) ? 1 : -1;
				});
				$reviewsByCourse[$courseId] = $details;
			}
			$ratings 		= $this->_calculateAverageRating($ratingList);
			$instituteObj 	= $this->institute_repository->find($instituteId);
			$ratings 		= $this->_arrangeRatingsInOrder($ratings);
			$courseDetails  = $this->_nonEmptyCourseNames($courseDetails);
			$data = array();
			$data['ratings'] 				= $ratings;
			$data['reviews_by_email'] 		= $reviews;
			$data['reviews_by_course'] 		= $reviewsByCourse;
			$data['course_names'] 			= $courseDetails;
			$data['institute_name'] 		= $instituteObj->getName();
			$data['institute_id'] 			= $instituteObj->getId();
		}
		return $data;
	}
	
	private function _getStandardTimeForUserReview($time, $details) {
		$defaultTimeStamp = '0000-00-00 00:00:00';
		$t = FALSE;
		if(count($details) == 1){
			$keys = array_keys($details);
			$maxTime = $details[$keys[0]]['time'];
		} else {
			$maxTime = $d['time'];
			foreach($details as $d) {
				if(strtotime($d['time']) > strtotime($maxTime)) {
					$maxTime = $d['time'];
				}
			}
		}
		return $maxTime;
	}
	
	private function _calculateAverageRating($data) {
		$ratings = array();
		foreach($data as $criteriaId => $details) {
			if(!empty($details['total']) && !empty($details['sum'])) {
				$avg = round($details['sum']/$details['votes'], 1);
			}
			$ratings[$details['name']] 				= array();
			$ratings[$details['name']]['id'] 		= $criteriaId;
			$ratings[$details['name']]['ratings'] 	= $avg;
			$ratings[$details['name']]['votes'] 	= $details['votes'];
			$ratings[$details['name']]['name'] 		= $details['name'];
		}
		return $ratings;
	}
	
	private function _arrangeRatingsInOrder($ratings = array()) {
		$list = array();
		if(array_key_exists('Placements', $ratings)){
			$list['Placements'] = $ratings['Placements'];
		}
		if(array_key_exists('Infrastructure / Teaching facilities', $ratings)){
			$list['Infrastructure / Teaching facilities'] = $ratings['Infrastructure / Teaching facilities'];
		}
		if(array_key_exists('Faculty', $ratings)){
			$list['Faculty'] = $ratings['Faculty'];
		}
		if(array_key_exists('Overall Feedback', $ratings)){
			$list['Overall Feedback'] = $ratings['Overall Feedback'];
		}
		return $list;
	}
	
	private function _nonEmptyCourseNames($courseDetails = array()) {
		$list = array();
		foreach($courseDetails as $courseId => $value){
			if(!empty($value)){
				$list[$courseId] = $value;
			}
		}
		return $list;
	}
	
	public function test123($instituteId){
		$this->alumni_reviews_model->test123($instituteId);
	}
	
	public function getAlumnusRatingsForInstitutes($instituteIds = array()) {
		if(empty($instituteIds)) {
			return array();
		}
		
		$alumnusRatings = array();
		$alumnusRatingsData = $this->alumni_reviews_model->getAlumnusRatingsForInstitutes($instituteIds);
		
		foreach($alumnusRatingsData as $rating) {
			$instituteId = $rating['institute_id'];
			$email = $rating['email'];
			$criteria_name = $rating['criteria_name'];
			$criteria_rating = $rating['criteria_rating'];
			
			$alumnusRatings[$instituteId]['alumniReviewers'][$email] = TRUE;
			
			if($criteria_name == 'Overall Feedback') {
				$alumnusRatings[$instituteId]['OverallVotes']++;
				$alumnusRatings[$instituteId]['OverallRatings'] += $criteria_rating;
			}
		}
		
		$alumnusRatings[$instituteId]['totalAlumniReviewers'] = count($alumnusRatings[$instituteId]['alumniReviewers']);
		$alumnusRatings[$instituteId]['averageRating'] = round($alumnusRatings[$instituteId]['OverallRatings']/$alumnusRatings[$instituteId]['OverallVotes'], 1);
		
		foreach($alumnusRatings as $instituteId => $rating) {
			unset($alumnusRatings[$instituteId]['alumniReviewers']);
			unset($alumnusRatings[$instituteId]['OverallVotes']);
			unset($alumnusRatings[$instituteId]['OverallRatings']);
		}
		
		return $alumnusRatings;
	}
}