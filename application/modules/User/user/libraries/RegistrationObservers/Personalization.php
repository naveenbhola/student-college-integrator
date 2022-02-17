<?php
/**
 * Registration observer to send email verification mailer
 */
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to send email verification mailer
 */ 
class Personalization extends AbstractObserver
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
	 */ 
    public function update(\user\Entities\User $user)
    {
        $this->CI->load->library('common/Personalizer');
		$userPref = $user->getPreference();
		if($course = $userPref->getDesiredCourse()){
			$this->CI->load->builder('CategoryBuilder','categoryList');
			$categoryBuilder = new \CategoryBuilder();
			$categoryRepository = $categoryBuilder->getCategoryRepository();
			$catId = $categoryRepository->getCategoryByLDBCourse($course)->getId();
			$this->CI->personalizer->triggerPersonalization($catId,'registration');
			
		}
    }
}