<?php
/**
 * Registration observer to send email verification mailer
 */ 
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to send email verification mailer
 */ 
class EmailVerificationMailer extends AbstractObserver
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
    	// As discussed with karan, No need to send verification mail to the user.
    	return;
    	
		$data = array();
		$data['firstName'] = $user->getFirstName();
		$data['email'] = $user->getEmail();
		$data['password'] = $user->getTextPassword();
		
		$randomKey = $user->getRandomKey();	
		$verifyLink = SHIKSHA_HOME."/shiksha/userresponse/".$randomKey."/verify";
		$data['verifyLink'] = $verifyLink;
		
	 	// \Modules::run('systemMailer/SystemMailer/sendEmailVerificationMailer',$user->getEmail(),$data);
    }
}