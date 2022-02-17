<?php
include_once('MailerWidgetAbstract.php');

class AlumniSpeakWidget extends MailerWidgetAbstract
{
	private $instituteRepository;
	
	function __construct(MailerModel $mailerModel,InstituteRepository $instituteRepository)
	{
		parent::__construct($mailerModel);
		$this->instituteRepository = $instituteRepository;
	}
	
	public function getData($userIds, $params = "")
	{
		$alumniReviewsData = $this->getAlumniReviewsData($userIds);
		
		/**
		 * Generate alumni reviews widget
		 */ 
		$widgetData = array();
		
		foreach($userIds as $userId) {
			
			$instituteId = $alumniReviewsData[$userId]['instituteId'];
			$alumniReviews = $alumniReviewsData[$userId]['alumniReviews'];
			$alumniReviewsURL = $alumniReviewsData[$userId]['alumniReviewsURL'];
			
			if($alumniReviews) {
				$displayData = array();
				$displayData['alumniReviews'] = $alumniReviews;
				$displayData['alumniTabUrl'] = $alumniReviewsURL;
				
				$widgetHTML = $this->CI->load->view('MailerWidgets/AlumniSpeakTemplate',$displayData,true);
				$widgetData[$userId]['alumniSpeak'] = $widgetHTML;
			}
			else {
				$widgetData[$userId]['alumniSpeak'] = '';
			}
		}
		return $widgetData;
	}
	
	public function getAlumniReviewsData($userIds)
	{
		$data = array();
		
		$lastAppliedInstitutes = $this->mailerModel->getUsersLastInstituteIds(implode(',',$userIds));
		
		$instituteIds = array();
		foreach($lastAppliedInstitutes as $userId => $userInstituteData) {
			$instituteIds[] = $userInstituteData['institute_id'];
		}
		$instituteIds = array_unique($instituteIds);
		
		if(count($instituteIds)>0) {
			/**
			 * Alumni reviews for all institutes
			 */ 
			$alumniReviews = $this->instituteRepository->findAlumanisReviewsOnInstitutes($instituteIds);
			
			$alumniReviewData = array();
			/**
			 * Prepare alumni review data (for widget) for each institute
			 */ 
			foreach($alumniReviews as $instituteId => $instituteAlumniReviews) {
				$alumniReviewData[$instituteId] = $this->_getAlumniReviewsDataForInstitute($instituteAlumniReviews);
			}
			
			/**
			 * Map alumni review data for each user
			 */
			foreach($lastAppliedInstitutes as $userId => $userInstituteData) {
				if($userInstituteData['subscription_type'] == 'paid' || $this->mailer->getId() != 4407) {
					$instituteId = $userInstituteData['institute_id'];
					$data[$userId]['instituteId'] = $instituteId;
					$data[$userId]['alumniReviews'] = $alumniReviewData[$instituteId];
					$data[$userId]['alumniReviewsURL'] = $this->_getAlumniSpeakURLForInstitute($instituteId);
				}
			}
		}
		
		return $data;
	}
	
	/**
	 * Alumni review data for an institute
	 * Data includes reviews and average rating for each review criteria
	 */ 
	private function _getAlumniReviewsDataForInstitute($instituteAlumniReviews)
	{
		$instituteAlumniReviewsData = array();
				
		foreach($instituteAlumniReviews['alumniReviews'] as $review) {
			
			/**
			 * Review criteria i.e. criteria on which review/feedback was provided
			 * e.g. Placements, Infrastructure / Teaching facilities, Faculty, Overall Feedback
			 */ 
			$reviewCriteria = $review->getCriteriaName();
			
			/**
			 * Rating given for the review
			 */ 
			$rating = $review->getCriteriaRating();
			
			if($rating > 0) {
				$instituteAlumniReviewsData[$reviewCriteria]['reviews'][] = array(
																				  'reviewerName' => $review->getName(),
																				  'courseCompletionYear' => $review->getCourseComplettionYear(),
																				  'courseName' => $review->getCourseName(),
																				  'feedback' => $review->getCriteriaDescription()
																				);
				$instituteAlumniReviewsData[$reviewCriteria]['totalRating'] += $rating;
			}
		}
		
		/**
		 * Average rating for each review criteria
		 * Average rating = Total rating for all reviews for criteria / No. of reviews
		 */
		foreach($instituteAlumniReviewsData as $reviewCriteria => $reviewCriteriaData) {
			
			$totalRating = $reviewCriteriaData['totalRating'];
			$numReviews = count($reviewCriteriaData['reviews']);
			
			$averageRating = ceil($totalRating/$numReviews);
			
			$instituteAlumniReviewsData[$reviewCriteria]['averageRating'] = $averageRating;
			
			$instituteAlumniReviewsData[$reviewCriteria]['reviews'] = array_slice($instituteAlumniReviewsData[$reviewCriteria]['reviews'],0,1);
			unset($instituteAlumniReviewsData[$reviewCriteria]['totalRating']);
		}
		
		return $instituteAlumniReviewsData;	
	}
	
	/**
	 * URL for alumni speak tab for institute
	 * User will be redirected to this link for all alumni reviews
	 */ 
	private function _getAlumniSpeakURLForInstitute($instituteId)
	{
		$institute = $this->instituteRepository->find($instituteId);
	
		$params = array(
						'instituteId' => $institute->getId(),
						'instituteName' => $institute->getName(),
						'type' => 'institute',
						'locality' => "",
						'city' => $institute->getMainLocation()->getCity()->getName()
					);
		return listing_detail_alumni_speak_url($params);
	}
}