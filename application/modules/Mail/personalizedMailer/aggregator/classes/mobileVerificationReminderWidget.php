<?php
include_once '../WidgetsAggregatorInterface.php';

class mobileVerificationReminderWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {

		$customParams = $this->_params['customParams'];
		//_p($customParams);
		$landingUrl = '';
		$queryParameters = '';

		if($customParams['listingType'] == 'course'){
			$this->CI->load->builder("nationalCourse/CourseBuilder");
	        $courseBuilder = new CourseBuilder();
	        $this->courseRepo = $courseBuilder->getCourseRepository();   
			$courseObj = $this->courseRepo->find($customParams['listingId'],array('basic_info'));

			if(is_object($courseObj)){
				$courseURL = $courseObj->getURL();
				if(!empty($courseURL)){
					$landingUrl = $courseObj->getURL();
					$queryParameters = 'action='.$customParams['action'].'&';
	                if($customParams['action'] == 'cd'){
	                    $queryParameters .= 'scrollTo=contact&';
	                }
				}
			}
		}else if($customParams['listingType'] == 'exam'){
			$this->CI->load->builder('ExamBuilder','examPages');
		    $examBuilder          = new ExamBuilder();
		    $examRepository = $examBuilder->getExamRepository();
			$groupObj = $examRepository->findGroup($customParams['listingId']);
			if(is_object($groupObj) &&  $groupObj->getExamId()>0){
				$examId = $groupObj->getExamId();
				$examBasicObj = $examRepository->find($examId);
				if(is_object($examBasicObj)){
					$examURL = $examBasicObj->getUrl();
	                if(!empty($examURL)){
	                	$landingUrl = $examBasicObj->getUrl();
						$queryParameters = 'actionType='.$customParams['action'].'&';	
	                }
				}
			}
		}		

		if(empty($landingUrl)){
			$landingUrl = SHIKSHA_HOME.'/shiksha/index';
		}

		$queryParameters .= 'fromwhere='.$customParams['mailer_name'];
		$customParams['landingUrl'] = $landingUrl.'<!-- #AutoLogin --><!-- AutoLogin# -->'.'?'.$queryParameters;	
		$widgetHTML = '';
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/mobileVerificationReminderWidget", $customParams, true);
		
		return array('key'=>'mobileVerificationReminderWidget','data'=>$widgetHTML);
	}
}