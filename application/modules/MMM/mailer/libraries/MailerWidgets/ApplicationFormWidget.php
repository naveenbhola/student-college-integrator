<?php
include_once('MailerWidgetAbstract.php');

class ApplicationFormWidget extends MailerWidgetAbstract
{
	private $instituteRepository;
	private $courseRepository;
	private $onlineFormModel;
	
	function __construct(MailerModel $mailerModel,InstituteRepository $instituteRepository,CourseRepository $courseRepository,OnlineModel $onlineFormModel)
	{
		parent::__construct($mailerModel);
		$this->instituteRepository = $instituteRepository;
		$this->courseRepository = $courseRepository;
		$this->onlineFormModel = $onlineFormModel;
	}
	
	/**
	 * API for getting application forms widget data
	 */
	public function getData($userIds, $params = "",$processingParams = "")
	{
		$widgetData = array();
		$applicationFormData = $this->getApplicationFormData($userIds,$processingParams);
		
		foreach($userIds as $userId) {
			if($applicationFormData[$userId]) {
				
				$widget = '';
				$applicationFormInstituteList = array();
				
				foreach($applicationFormData[$userId] as $displayData) {
					$widget .= $this->CI->load->view('MailerWidgets/ApplicationFormTemplate',$displayData,true);
					
					$applicationFormInstituteList['InstituteList'][$displayData['courseId']]['name'] = $displayData['instituteName'];
					$applicationFormInstituteList['InstituteList'][$displayData['courseId']]['location'] = $displayData['locality'];
				}
				
				foreach($params as $key => $value) {
					if ($value == 'applicationform') {
						$widgetData[$userId][$value] = $widget;
					}
					else if ($value == 'applicationformtext') {
						$widgetData[$userId][$value] = $this->CI->load->view('MailerWidgets/ApplicationFormText',$applicationFormInstituteList,true);
					}
					else if ($value == 'applicationforminstitutestitle') {
						$widgetData[$userId][$value] = $this->CI->load->view('MailerWidgets/ApplicationFormInstitutesTitle',$applicationFormInstituteList,true);
					}
				}
			}
			else {
				foreach($params as $key => $value){
					$widgetData[$userId][$value] = '';
				}
			}
		}
		
		return $widgetData;
	}
	
	public function getApplicationFormData($userIds,$processingParams)
	{
		list($timeWindowStart,$timeWindowEnd) = explode(';',$processingParams['timeWindow']);	
		$responses = $this->mailerModel->getTotalResponseForUserId(implode(',',$userIds), $timeWindowEnd,7);

		$data = array();
		
		foreach($responses as $userId => $userResponses) {
			
			/**
			 * Find which institutes in user responses have online application forms
			 */ 
			$onlineForms = $this->onlineFormModel->checkOnlineFormForMrecApp($userId,$userResponses);
			
			if(count($onlineForms) > 0) {
				foreach($onlineForms as $courseId => $onlineFormData) {
						
					$ofData = array();
					$ofData['courseId'] = $courseId;
					
					$instituteId = $onlineFormData['instituteId'];
					$institute = $this->instituteRepository->find($instituteId);
					$course = $this->courseRepository->find($courseId);

					if($institute instanceof AbroadInstitute) { continue; }
                                	if($course instanceof AbroadCourse) { continue; }
		
					
					if(($timestamp = strtotime($onlineFormData['last_date'])) !== false){
						$php_date = getdate($timestamp);
						$ofData['OnlineFormLastDay'] = $php_date['mday']." ".$php_date['month']." ".$php_date['year'];	
					}
					
					$ofData['instituteName'] = $institute->getName();
					$ofData['locality'] = $institute->getMainLocation()->getCity()->getName();
					$ofData['HeaderImage'] = $institute->getMainHeaderImage()->getFullURL();
					$ofData['CourseTitle'] = $course->getName();
					$ofData['CourseDuration'] = $course->getDuration()->getDisplayValue();
					$ofData['CourseFees'] = $course->getFees();
					$ofData['ExamsDetails'] = $course->getEligibilityExams();
					$ofData['CourseType'] = $course->getCourseType();
					$ofData['CourseLevel'] = $course->getCourseLevel();						
					$ofData['OnlineFormFees'] = $onlineFormData['fees'];
					$ofData['OFlink'] = $onlineFormData['OFlink'];
					
					$data[$userId][] = $ofData;
				}
			}
		}
		
		return $data;
	}
}
