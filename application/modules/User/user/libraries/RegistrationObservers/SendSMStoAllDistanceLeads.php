<?php

/**
 * Registration observer to send mobile verification SMS
 */ 
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to send mobile verification SMS
 */ 
class SendSMStoAllDistanceLeads extends AbstractObserver
{
	
	/**
	 * Array for storing cities
	 * @var array
	 */
	private $cityIds = array('1113', '10644', '10648', '10649', '10645', '74', '10653', '84', '87', '1616', '95', 
							'10646', '10647', '10632', '146', '10650', '161', '166', '181',  '2631', '130', '106', 
							'55', '171', '180', '12263', '138', '122', '150');
	
	/**
	 * Constructor
	 */
    function __construct()
    {
        parent::__construct();
    }
    
	/**
	 * Update the observer
	 *
	 * @param object $user \user\Entities\User
	 */ 
    public function update(\user\Entities\User $user)
    {
    	$userId = $user->getId();
		$mobile = $user->getMobile();
		$email  = $user->getEmail();
		$desired_course = $user->getPreference()->getDesiredCourse();
		$extra_flag = $user->getPreference()->getExtraFlag();
		$city = $user->getCity();
		

		$data = array();
		if($desired_course == 2 && $extra_flag != 'studyabroad' && in_array($city, $this->cityIds)){

			/*Code to check subscription end date */
			$diff = strtotime('2016-07-13') - strtotime('now');
			if($diff < 0){
				return;
			}

			if($mobile){
				$data['userId'] = $userId;
				$data['mobile'] = $mobile;
			}

			\Modules::run('systemMailer/SystemMailer/processIBAMailer', $email, $data);

		}

    }
}
