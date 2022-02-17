<?php
/**
 * Registration observer to save custom MMP data
 */
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to save custom MMP data
 */ 
class UserDataConsolidator extends AbstractObserver
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
	 * @param array $data
	 * @param object $user \user\Entities\User
	 */
    public function update(\user\Entities\User $user,$data = array())
    {
		$this->CI->load->model('user/usermodel');
		$this->CI->usermodel->updateUserData($user->getId());
    }
}