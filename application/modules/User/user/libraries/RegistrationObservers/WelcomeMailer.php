<?php
/**
 * Registration observer file to send welcome mailer
 */
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to send welcome mailer
 */ 
class WelcomeMailer extends AbstractObserver
{
	/**
	 * Instance for MMP Model
	 *
	 * @var object
	 *
	 */
	private $mmpModel;
	
	/**
	 * Constructor
	 */
    function __construct()
    {
        parent::__construct();
		
		/**
		 * Load required libraries/models
		 */ 
		$this->CI->load->model('customizedmmp/customizemmp_model');
		$this->mmpModel = new \customizemmp_model;
		$this->CI->load->model('registration/featuredguidemodel');
		$this->featuredguidemodel = new \featuredguidemodel;
    }
    
	/**
	 * Update the observer
	 *
	 * @param object $user \user\Entities\User
	 * @param array $data
	 */ 
    public function update(\user\Entities\User $user,$data = array())
    {
		$fullTimeMBARegistration = FALSE;
		$mmpWithAttachmentRegistration = FALSE;
		$attachment = array();
		$stream_course_array = array();
		$userflag = $user->getPreference()->getExtraFlag();				
		$UserInterest = $user->getUserInterest();		
		$allUserBaseCourses = array();
		if(count($UserInterest)>0) {
			foreach($UserInterest as $interest_object) {
				$course_list = array();
				$stream_course_array[$interest_object->getInterestId()]['stream'] = $interest_object->getStreamId();	
				$userCourseSpecialization = $interest_object->getUserCourseSpecialization();
				foreach($userCourseSpecialization as $userCourseObj) {
					$baseCourseId = '';
					$baseCourseId = $userCourseObj->getBaseCourseId();
					$course_list[] = $baseCourseId;
					$allUserBaseCourses[] = $baseCourseId;
				}
				$stream_course_array[$interest_object->getInterestId()]['course'] = $course_list;
			}
		}
		global $mmpFormsForCaching;

		if(!in_array($data['mmpFormId'],$mmpFormsForCaching)){


		// handle MMP attachment
			if($data['mmpFormId'] && $attachment = $this->_getMMPAttachment($data['mmpFormId'])) {
				$mmpWithAttachmentRegistration = TRUE;
				if($this->_isFullTimeMBARegistration($stream_course_array)) {
					$fullTimeMBARegistration = TRUE;
				}
				// handle normal attachment
			} else if(is_array($stream_course_array) && count($stream_course_array)>0){
				if($attachment = $this->_getCourseAttachment($stream_course_array)) {				
					//do nothing
					if($this->_isFullTimeMBARegistration($stream_course_array)) {
						$fullTimeMBARegistration = TRUE;
					}
				} else if($this->_isFullTimeMBARegistration($stream_course_array)) {
					$fullTimeMBARegistration = TRUE;
					//$attachment = $this->_getFullTimeMBAAttachment();
				}
			}

				
		}
		
		$mailerData = array();
		$mailerData['firstName'] = $user->getFirstName();
		$mailerData['email'] = $user->getEmail();
		$mailerData['password'] = $user->getTextPassword();
		
		if($data['usergroup'] == 'fbuser'){
			$requested_page = 'homepage';

			$mailerData['password'] = \Modules::run('marketing/FBLeadJobs/generatePasswordLink',$requested_page, $mailerData['email']);
		}

		$body_html = $this->generateEmailHtml($data);
		$mailerData['body_html'] = $body_html;

		$mailerData['fullTimeMBARegistration'] = $fullTimeMBARegistration;
		if($fullTimeMBARegistration) {
			$mailerData['stream_id'] = 	MANAGEMENT_STREAM;
		}
		if($this->_isMBARegistration($allUserBaseCourses)){
			$mailerData['baseCourseId'] = MANAGEMENT_COURSE;
		}
		$mailerData['mmpWithAttachmentRegistration'] = $mmpWithAttachmentRegistration;		
		$mailerData['usertype'] = $userflag; 
		
		//error_log('kxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'.print_r($mailerData,true));
		//error_log('kxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'.print_r($attachment,true));
		
		\Modules::run('systemMailer/SystemMailer/sendWelcomeMailer',$user->getEmail(),$mailerData,$attachment);
    }
	
	/**
	 *  Function to check for Management Stream Registration
	 *
	 *  @param object $user
	 *  
	 *  @return boolean
	 *
	 */
	private function _isFullTimeMBARegistration($stream_course_array)
	{		
		global $managementStreamMR;		
		if(is_array($stream_course_array) && count($stream_course_array)>0) {
			foreach($stream_course_array as $stream_course) {
				if($stream_course['stream'] == $managementStreamMR) {
					return TRUE;	
				}
			}
		}
		return FALSE;
	}

	/**
	 *  Function to check for MBA Course Registration
	 *
	 *  @param object $user
	 *  
	 *  @return boolean
	 *
	 */
	private function _isMBARegistration($base_course_array)
	{		
		global $mbaBaseCourse;		
		if(is_array($base_course_array) && count($base_course_array)>0) {
			foreach($base_course_array as $base_course) {
				if($base_course == $mbaBaseCourse) {
					return TRUE;	
				}
			}
		}
		return FALSE;
	}
	
	/**
	 * Function to get the Full time MBA Attachment
	 *
	 * @return array
	 */
	private function _getFullTimeMBAAttachment()
	{
		$attachment = array();
		//$attachment['url'] = MEDIAHOSTURL.'/mediadata/pdf/Management_Guide.pdf';
		
		$attachment[0]['url'] = MEDIAHOSTURL.'/mediadata/pdf/How to crack GD-PI-WAT | Shiksha.com.pdf';
		$attachment[0]['name'] = 'How to crack GD-PI-WAT | Shiksha.com.pdf';

		$attachment[1]['url'] = MEDIAHOSTURL.'/mediadata/pdf/Current Affairs | Shiksha.com.pdf';
		$attachment[1]['name'] = 'Current Affairs | Shiksha.com.pdf';
		
		return $attachment;
	}
	
	/**
	 * Function to get the MMP Attachment
	 *
	 * @param integer $mmpFormId
	 * @return boolean | array 
	 */
	private function _getMMPAttachment($mmpFormId)
	{
		$mmpMailer = $this->mmpModel->getMMPMailer($mmpFormId);
		
		if($mmpMailer['attachment_url'] && $mmpMailer['attachment_name']) {
			$attachment = array();
			$attachment['url'] = MEDIA_SERVER.$mmpMailer['attachment_url'];
			$attachment['name'] = $mmpMailer['attachment_name'];
			return $attachment;
		}
		
		return FALSE;
	}
	
	/**
	 * Function to get the course attachment
	 *
	 * @param integer $desired_course
	 * @return array | boolean
	 *
	 */
	private function _getCourseAttachment($stream_course_array){
		
		foreach($stream_course_array as $stream_course) {
			$welcomeMailer[] = $this->featuredguidemodel->getGuideForAttachment($stream_course['stream'],$stream_course['course']);
		}
		
		if(count($welcomeMailer)>0) {			
			$i=0;
			$attachment = array();
			
			foreach($welcomeMailer as $row){
				foreach($row as $data) {
					if($data['guide_url'] && $data['attachment_name']) {						
						$attachment[$i]['url'] = MEDIA_SERVER.$data['guide_url'];
						$attachment[$i]['name'] = $data['attachment_name'];
						$i++;
					}
				}
			}
			
			return $attachment;
		
		} else {
			
			return false;
		}

	}

	public function generateEmailHtml($data){
		$usergroup = $data['usergroup'];
		$clientCourseName = $data['clientCourseName'];

		if($usergroup == 'fbuser'){
			$html = 'Thank you for showing interest in the '.$clientCourseName.' on Facebook. To connect you with the desired college, your account on Shiksha has also been created. Please find below your username to login to Shiksha. Please generate your password by clicking the link below:';
		}else{
			$html = 'Welcome to shiksha<font style="text-decoration:none;color:#999999">.</font>com! Here are your account details :';
		}

		return $html;
	}

}
