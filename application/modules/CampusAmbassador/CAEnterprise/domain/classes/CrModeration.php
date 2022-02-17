<?php
class CrModeration{
	private $solrClient;
	private $request;
	function __construct(
						 CrModetationPageRequest $request,
						 $crModerationSolrClient
						 )
	{
		$this->solrClient = $crModerationSolrClient;
		$this->request    = $request;
	}


	function getCollegeReviewData(){
		$result                                     = array();
		$data                                       = $this->solrClient->getCollegeReviewData();
		$result['collegeReviewListing']             = $this->_prepareCRData($data['documents']);		
		$result['collegeReviewFilters']             = $this->_prepareCRFilters($data['filter']);
		$result['collegeReviewInstituteWiseStatus'] = $this->_prepareCRInstituteWiseStatus($data['instituteWiseStatus']);
		$result['totalDocumentCount']               = $data['totalDocumentCount'];
		return $result;
	}


	private function _prepareCRData($data){
		$formattedData = array();
		foreach ($data as $key => $review) {
			$reviewData                           = new stdClass;
			$reviewData->reviewId                 = $review['reviewId'];
			$reviewData->reviewQuality            = $review['reviewQuality'];
			$reviewData->userId                   = $review['userId'];
			$creationDate                         =  explode('T', $review['creationDate']);
			$reviewData->creationDate             = $creationDate[0]." ".rtrim($creationDate[1],'Z');
			$reviewData->status                   = $review['reviewStatus'];
			$reviewData->anonymousFlag            = ($review['isAnonymous']=='true')?'YES':'NO';
			
			$reviewContent                        = (array)json_decode(html_entity_decode($review['reviewContent']),true);
			

			$reviewData->reviewDescription        = urldecode($reviewContent['reviewDescription']);
			$reviewData->placementDescription     = urldecode($reviewContent['placementDescription']);
			$reviewData->infraDescription         = urldecode($reviewContent['infraDescription']);
			$reviewData->facultyDescription       = urldecode($reviewContent['facultyDescription']);
			$reviewData->reviewTitle              = urldecode($reviewContent['reviewTitle']);
			
			$ratingParams                         = json_decode(html_entity_decode($review['ratingParams']),true);
			$reviewData->moneyRating              = $ratingParams['1'];
			$reviewData->crowdCampusRating        = $ratingParams['2'];
			$reviewData->avgSalaryPlacementRating = $ratingParams['3'];
			$reviewData->campusFacilitiesRating   = $ratingParams['4'];
			$reviewData->facultyRating            = $ratingParams['5'];
			$reviewData->recommendCollegeFlag     = ($review['recommendCollegeFlag']=='true')?'YES':'NO';
			$reviewData->isShikshaInstitute       = ($review['isInstituteMapped']=='true')?'YES':'NO';
			$modificationDate                         =  explode('T', $review['modificationDate']);
			$reviewData->modificationDate             = $modificationDate[0]." ".rtrim($modificationDate[1],'Z');
			// $reviewData->reviewerId               = $review['reviewQuality']; //wrong!! but not needed
			$reviewData->reviewSource             = $review['reviewSource'];
			$reviewData->helpfulFlagCount         = $review['helpfulCount'];
			$reviewData->notHelpfulFlagCount      = $review['notHelpfulCount'];
			$reviewData->reason                   = $review['moderationReason'];
			// $reviewData->review_seo_url           = $review['reviewQuality']; //wrong!! but not needed
			// $reviewData->review_seo_title         = $review['reviewQuality']; //wrong!! but not needed
			$reviewData->averageRating            = $review['averageRating'];
			// $reviewData->visitorSessionId         = $review['reviewQuality']; //wrong!! but not needed
			$reviewData->incentiveFlag            = $review['incentiveFlag'];
			$reviewData->instituteId              = $review['instituteId'];
			$reviewData->courseId                 = $review['courseId'];
			$reviewData->yearOfGraduation         = $review['yearOfGraduation'];
			$reviewData->firstname                = $review['firstname'];
			$reviewData->lastname                 = $review['lastname'];
			$reviewData->email                    = $review['email'];
			$reviewData->isdCode                  = $review['isdCode'];
			$reviewData->mobile                   = $review['mobile'];
			$socialProfile                        = json_decode(html_entity_decode($review['socialProfile']),true);
			$reviewData->linkedInURL              = $socialProfile['linkedInURL'];
			$reviewData->facebookURL              = $socialProfile['facebookURL'];
			$reviewData->main_reviewid            = $review['reviewId'];
			$reviewData->paidStatus               = $review['reviewPackType'];
			$reviewData->qualityScore             = $review['qualityScore'];
			$tempModerator                        = array();
			if(!empty($review['lastModeratorEmail'])){
				$tempModerator['moderatorEmail']  = $review['lastModeratorEmail'];
			}
			if(!empty($review['lastModerateDate'])){
				$lastModerateDate                 = explode('T', $review['lastModerateDate']);
				$tempModerator['moderationTime']  = $lastModerateDate[0]." ".rtrim($lastModerateDate[1],'Z');
			}
			$reviewData->moderationDetail[]       = $tempModerator; 
			$formattedData[]                      = $reviewData;
		}

		unset($data);
		return $formattedData;
	}

	private function _prepareCRFilters($filtersData){
		$formattedFiltersData = array();
		
		foreach ($filtersData['utmSource'] as $utmSourceValue => $val) {
			$formattedFiltersData['utmSource'][] = $utmSourceValue;
		}
		unset($filtersData);

		return $formattedFiltersData;
	}

	private function _prepareCRInstituteWiseStatus($data){		
		$instituteWiseStatus = array();
		foreach ($data as $key => $val) {
			$instituteId = $val['value'];
			foreach ($val['pivot'] as $key => $statusList) {
				$columnVal = '';
				switch ($statusList['value']) {
						case 'published':
							$columnVal = 'totalPublished';
							break;
						case 'draft':
							$columnVal = 'totalPending';
							break;
						case 'rejected':
							$columnVal = 'totalIgnored';
							break;
						default:
							# code...
							break;
					}	
				$instituteWiseStatus[$instituteId][$columnVal] = $statusList['count'];				
			}
		}
		unset($data);		
		return $instituteWiseStatus;
	}	
}