<?php
/**
 * Registration observer to save custom MMP data
 */
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to save custom MMP data
 */ 
class MMPCustomData extends AbstractObserver
{
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
	 * @param array $data
	 */
    public function update(\user\Entities\User $user,$data = array())
    {
		$customData = array();
		$pref = $user->getPreference();
		
		foreach($data as $key => $value) {
			if(strpos($key,'custom_') === 0) { 
				$dataKey = substr($key,7);
				$dataValue = is_array($value) ? implode('|',$value) : $value;
				
				$customData[] = array(
										'userid' => $user->getId(),
										'prefid' => $pref->getPrefId(),
										'formid' => $data['mmpFormId'],
										'datakey' => $dataKey,
										'datavalue' => $dataValue
									);
			}
		}
		if(count($customData) > 0) {
			$this->CI->load->model('user/usermodel');
			$this->CI->usermodel->saveMMPCustomData($customData);
		}
    }
}