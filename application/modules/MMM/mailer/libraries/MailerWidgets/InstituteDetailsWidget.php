<?php
include_once('MailerWidgetAbstract.php');

class InstituteDetailsWidget extends MailerWidgetAbstract
{
	private $instituteRepository;
	private $courseRepository;
	
	function __construct(MailerModel $mailerModel,InstituteRepository $instituteRepository, CourseRepository $courseRepository)
	{
		parent::__construct($mailerModel);
		
		$this->instituteRepository = $instituteRepository;
		$this->courseRepository = $courseRepository;
		$this->CI->load->library('dashboardconfig');
	}
	
	/**
	 * API for getting institute videos data
	 */
	public function getData($userIds, $params)
	{
		$institutesData = $this->getInstitutesData($userIds);
		
		$widgetData = array();
		foreach($userIds as $userId) {
			$data = $institutesData[$userId];
	

			if(is_array($data) && count($data) > 0) {

				
				$tempArrayForLastAppliedInstitute = $params['mailer'] ->getTemplate()-> getTemplateVariables();	//Fix for MRecBNew mailer
				
				$flagForLastAppliedInstitute = false;								//fix for MRecBNew mailer
				foreach($tempArrayForLastAppliedInstitute as $row => $value){
					if($value['varname'] == 'LastAppliedInstitute'){
						$flagForLastAppliedInstitute = true;
					}
				}
				


				foreach($params as $key => $value) {

					if ($value == 'InstituteFormDetails' && $data['showWidgets']){
						$widgetHTML = $this->CI->load->view('MailerWidgets/FormDetails',$data,true);
						$widgetData[$userId][$value] = $widgetHTML;
					}
					else if ($value == 'InstituteNameDetails' && $data['showWidgets']){
						$widgetHTML = $this->CI->load->view('MailerWidgets/NameDetails',$data,true);
						$widgetData[$userId][$value] = $widgetHTML;
					}
					else if ($value == 'PhotoVideos' && $data['showWidgets'] && (!empty($data['photos']) || !empty($data['videos']))){
						$widgetHTML = $this->CI->load->view('MailerWidgets/PhotoVideos',$data,true);
						$widgetData[$userId][$value] = $widgetHTML;
					}
					else if ($value == 'LastAppliedInstitute' || $flagForLastAppliedInstitute){		//fix for SA mailer
						
						if(!empty($data['instituteName'])) {
							$widgetData[$userId][$value] = $data['instituteName'];
							$widgetData[$userId]['instituteName'] = $data['instituteName'];		//fix for SA mailer
	
						}
						else {
							$widgetData[$userId][$value] = $data['courseName'].' at '.$data['universityName'];
							$widgetData[$userId]['instituteName'] = $data['courseName'].' at '.$data['universityName'];		//fix for SA mailer	
						}
					}
				}
			}
			else {
				foreach($params as $key=>$value){
					$widgetData[$userId][$value] = "";
				}
			}
		}
		return $widgetData;
	}
	
	public function getInstitutesData($userIds)
	{
		$lastAppliedInstitutes = $this->mailerModel->getUsersLastInstituteIds(implode(',',$userIds));
		
		$instituteCourseMapping = array();
		foreach($lastAppliedInstitutes as $userId => $userInstituteData) {
			$instituteCourseMapping[$userInstituteData['institute_id']][] = $userInstituteData['course_id'];
		}
		
		$data = array();
		if(count($instituteCourseMapping)>0) {
			$institutesData = array();
			$coursesData = array();
			
			foreach($instituteCourseMapping as $instituteId => $courses) {
				$dataForInstitute = array();
				
				$institute = $this->instituteRepository->find($instituteId);
				
				if(!is_object($institute)) {
					continue;
				}
				
				if($institute instanceof AbroadInstitute) {
					$courseObjs = $this->courseRepository->findMultiple($courses);
					foreach($courses as $courseId) {
						if(!is_object($courseObjs[$courseId])) {
							continue;
						}
						
						$dataForCourse = array();
						$dataForCourse['location'] = 'abroad';
						$dataForCourse['courseId'] = $courseObjs[$courseId]->getId();
						$dataForCourse['courseName'] = $courseObjs[$courseId]->getName();
						$dataForCourse['universityName'] = $courseObjs[$courseId]->getUniversityName();
						$dataForCourse['country'] = $courseObjs[$courseId]->getMainLocation()->getCountry()->getName();
						
						$coursesData[$courseId] = $dataForCourse;
					}
				}
				else {
					$dataForInstitute['location'] = 'india';
					$dataForInstitute['instituteId'] = $instituteId;
					$dataForInstitute['instituteName'] = $institute->getname();
					$dataForInstitute['instituteLocation'] = $institute->getMainLocation()->getCity()->getName();
					$dataForInstitute['onlineFormLink'] = $this->getOnlineFormLink($instituteId);
					
					$urlParams = array(
								'instituteId' => $institute->getId(),
								'instituteName' => $institute->getName(),
								'type' => 'institute',
								'locality' => "",
								'city' => $institute->getMainLocation()->getCity()->getName()
							);
					
					$dataForInstitute['photoTabUrl'] = listing_detail_media_url($urlParams);
					
					/**
					 * Prepare photos and videos data
					 */ 
					$photoVideoData = $this->getPhotosVideosData($institute);
					
					$dataForInstitute['totalPhotoCount'] = $photoVideoData['totalPhotoCount'];
					$dataForInstitute['totalVideoCount'] = $photoVideoData['totalVideoCount'];
					$dataForInstitute['photos'] = $photoVideoData['photos'];
					$dataForInstitute['videos'] = $photoVideoData['videos'];
					
					$institutesData[$instituteId] = $dataForInstitute;
				}
			}
			
			/**
			 * Map photo video data for each user
			 */
			foreach($lastAppliedInstitutes as $userId => $userInstituteData) {
				$institute_id = $userInstituteData['institute_id'];
				$course_id = $userInstituteData['course_id'];
				
				if(!empty($institutesData[$institute_id])) {
					$data[$userId] = $institutesData[$institute_id];
				}
				else {
					$data[$userId] = $coursesData[$course_id];
				}
				
				if($userInstituteData['subscription_type'] == 'paid' || is_object($this->mailer) && $this->mailer->getId() != 4407) {
					$data[$userId]['showWidgets'] = true;
				}
				else {
					$data[$userId]['showWidgets'] = false;
				}
			}
		}
		
		return $data;
	}
	
	/**
	 * Check whether an online form is live for the institute
	 * if yes, get link to online form
	 */ 
	public function getOnlineFormLink($instituteId)
	{
		$onlineForms = DashboardConfig::$institutes_autorization_details_array;
		$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', array($instituteId));
		$onlineForms += $PBTSeoData;
		return isset($onlineForms[$instituteId]) ? THIS_CLIENT_IP.$onlineForms[$instituteId]['seo_url'] : NULL;
	}
	
	/**
	 * Photos and videos for the institutes
	 */ 
	public function getPhotosVideosData($institute)
	{
		$data = array();
		
		$photos = $institute->getPhotos();
		$videos = $institute->getVideos();

		$data['totalPhotoCount'] = count($photos);
		$data['totalVideoCount'] = count($videos);
		
		$data['photos'] = array();
		$data['videos'] = array();

		$numPhotosToShow = 3;
		$numVideosToShow = 1;
		
		/**
		 * If no videos,show 4 photos
		 */ 
		if(count($videos) == 0) {
			$numPhotosToShow = 4;
		}
		
		/**
		 * If photos are less than 3, add more videos
		 */ 
		if(count($photos) < 3) {
			$numVideosToShow = 4-count($photos);
		}
		
		for($i=0;$i<$numPhotosToShow;$i++) {
			if($photos[$i]) {
				$data['photos'][] = $photos[$i]->getURL();
			}
		}
		
		for($i=0;$i<$numVideosToShow;$i++) {
			if($videos[$i]) {
				$data['videos'][] = $this->getYouTubeVideoThumbnail($videos[$i]->getURL());
			}
		}
		
		return $data;
	}
	
	/**
	 * YouTube thumbnail from video URL
	 */ 
	public function getYouTubeVideoThumbnail($videoURL)
	{
		if (FALSE !== filter_var($videoURL,FILTER_VALIDATE_URL)) {
			// http://www.youtube.com/v/abcxyz123
			if(FALSE !== strpos($videoURL, '/v/')) {
				list( , $videoId ) = explode( '/v/', $videoURL );
			}
			// http://www.youtube.com/watch?v=abcxyz123
			else {
				$videoQuery = parse_url( $videoURL, PHP_URL_QUERY );
				parse_str( $videoQuery, $videoParams );
				$videoId = $videoParams['v'];
			}
		}
		return "https://img.youtube.com/vi/".$videoId."/default.jpg";
	}
}
