<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');

function rh_buildRecommendationData($recommendations)
{
	$recommendationData = array();
    
	foreach($recommendations as $userId => $userRecommendations) {
		
		$userRecommendationData = array();
		foreach($userRecommendations as $userRecommendation) {
			$algo = $userRecommendation['algo'];
			$userRecommendationData[$algo][] = array(
				'courseId' => $userRecommendation['course_id'],
				'instituteId' => $userRecommendation['institute_id']
			);													
		}	

		$recommendationData[$userId] = $userRecommendationData;
	}
	
	return $recommendationData;
}

function rh_daysSince($datetime)
{
	$today = strtotime(date('Y-m-d'));
	$givenDate = strtotime(date('Y-m-d', strtotime($datetime)));
	
	if($givenDate >= $today) {
		return 0;
	}
	
	return ($today - $givenDate)/86400;
}
