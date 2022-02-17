<?php

class RegistrationLib
{

	private $CI;

	public function __construct(){
		$this->CI = & get_instance();
	}

	 public function filterDummyBaseCourses($baseCourses){
        if(empty($baseCourses)){
            return array();
        }

        $returnData = array();
        $this->CI->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new ListingBaseBuilder();

        $BaseCourseRepository = $listingBase->getBaseCourseRepository();

        $baseCoursesObject = $BaseCourseRepository->findMultiple($baseCourses);

        foreach($baseCoursesObject as $row=>$value){
            if($value->isDummy()){
                 $returnData['dummyCourses'][] = $value->getId();
            }else{
                 $returnData['baseCourses'][] = $value->getId();
            }
        }
        return $returnData;
    }

    public function sendReminderToUser($frequency){
        $this->CI->load->model('registration/registrationmodel');
        $registrationmodel = new Registrationmodel();

        $frequency_date = date("Y-m-d", strtotime("- $frequency day"));
        
        $users_for_reminder = $registrationmodel->getUsersToSendReminder($frequency_date);

        foreach ($users_for_reminder as $user_data) {
            if($user_data['isdCode'] == '91'){
                $this->sendSMSReminder($user_data['mobile']);
            }

            $this->sendEmailReminder($user_data['email']);
        }
    }

    public function sendSMSReminder($mobile, $frequency){
        $this->CI->load->config('registration/registrationFormConfig');
        global $reminder_sms;
        global $reminder_sms_link;

        $reminder_sms_link .= $frequency;
        $smsText  = str_replace("<reminder-url>", $reminder_sms_link, $reminder_sms);

        $this->CI->load->model('smsModel');
        $this->CI->smsModel->addSmsQueueRecord('',$mobile,$smsText,'11','0000-00-00 00:00:00',""); 
    }

    public function sendMobVerifySMSReminder($data){
        $smsDetails = $data['smsDetails'];
        if($data['listingType'] =='course'){
            $data['listingType'] = 'c';
        }else if($data['listingType'] =='exam'){
            $data['listingType'] = 'e';
        }
        $urlParameters = $data['frequency'].'_'.$data['listingType'].'_'.$data['listingId'].'_'.$data['action'].'_'.$data['userId'];
        $urlParameters = base64_encode($urlParameters);
        $landingUrl = $data['smsDetails']['landingUrl'].$urlParameters;

        $arrayOfVariables = array('<action>','<landing-url>');
        $arrayOfConstants = array($data['actionMapping'],$landingUrl);
        $smsText = str_replace($arrayOfVariables, $arrayOfConstants, $data['smsDetails']['text']);

        $this->CI->load->model('smsModel');
        $this->CI->smsModel->addSmsQueueRecord('',$data['mobile'],$smsText,$data['userId'],'0000-00-00 00:00:00',""); 
    }
}