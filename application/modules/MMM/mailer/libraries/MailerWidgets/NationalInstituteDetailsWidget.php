<?php
include_once('MailerWidgetAbstract.php');

class NationalInstituteDetailsWidget extends MailerWidgetAbstract
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
				
				$tempArrayForLastAppliedInstitute = $params['mailer']->getTemplate()->getTemplateVariables();
				
				$flagForLastAppliedInstitute = false;			
				foreach($tempArrayForLastAppliedInstitute as $row => $value){
					if($value['varname'] == 'NationalLastAppliedInstitute'){
						$flagForLastAppliedInstitute = true;
						break;
					}
				}

				if ($flagForLastAppliedInstitute) { 				
					$widgetData[$userId]['NationalLastAppliedInstitute'] = $data['instituteName'];		
				}
			
			}
			else {
				$widgetData[$userId]['NationalLastAppliedInstitute'] = '';
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
			
			foreach($instituteCourseMapping as $instituteId => $courses) {
				$dataForInstitute = array();
				
				$institute = $this->instituteRepository->find($instituteId);
				
				if(!is_object($institute)) {
					continue;
				}				

				$dataForInstitute['instituteName'] = $institute->getname();				
				
				$institutesData[$instituteId] = $dataForInstitute;
		
			}

			foreach($lastAppliedInstitutes as $userId => $userInstituteData) {
				$institute_id = $userInstituteData['institute_id'];
				
				if(!empty($institutesData[$institute_id])) {
					$data[$userId] = $institutesData[$institute_id];
				}
			}
		}
		
		return $data;
	}
	
}
