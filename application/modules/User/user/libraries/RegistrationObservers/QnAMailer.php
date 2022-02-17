<?php
/**
 * Registration observer to send QnA mailer
 */ 
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to send QnA mailer
 */ 
class QnAMailer extends AbstractObserver
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
		$userId = $user->getId();
		$displayName = $user->getDisplayName();
		$email = $user->getEmail();
		
		$this->CI->load->library('MailerClient');
		$mailerClient = new \MailerClient;
		/*
		if($subCategories!='') {
			$mailerClient->sendRegistrationQuestionMailer(1,$userId,$displayName,$subCategories,REGISTRATION_QUESTION_POOL_DURATION,$email);
		} else {
			$mailerClient->sendRegistrationQuestionMailer(1,$userId,$displayName,$categories,REGISTRATION_QUESTION_POOL_DURATION,$email);
		}
        */
    }
}
