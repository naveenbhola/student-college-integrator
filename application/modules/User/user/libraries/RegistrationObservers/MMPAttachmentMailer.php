<?php
/**
 * Registration observer to send MMP attachment mailer
 */ 
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to send MMP attachment mailer
 */ 
class MMPAttachmentMailer extends AbstractObserver
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
        if(!$data['mmpFormId']) {
            return FALSE;
        }
        
        $this->CI->load->model('customizedmmp/customizemmp_model');
        $mmpModel = new \customizemmp_model;
		
		$this->CI->load->library('Alerts_client');
		$alertClient = new \Alerts_client();
        
        $mmpMailer = $mmpModel->getMMPMailer($data['mmpFormId']);
        if(!$mmpMailer['id']) {
            return FALSE;
        }
        
		$email = $user->getEmail();
        $subject = $mmpMailer['subject'];
        $content = $mmpMailer['content'];
		
		if($mmpMailer['attachment_url'] && $mmpMailer['attachment_name']) {
			$documentURL = $mmpMailer['attachment_url'];
			$documentContent = base64_encode(file_get_contents($documentURL));
			$documentExtension = end(explode(".",$documentURL));
			$documentName = $mmpMailer['attachment_name'];
			
			$this->CI->load->library('Ldbmis_client');
			$misObj = new \Ldbmis_client();
			$uniqueId = $misObj->updateAttachment(1);
			
			$attachmentId = $alertClient->createAttachment("12",$uniqueId,'COURSE','E-Brochure',$documentContent,$documentName,$documentExtension,'true');
			
			$attachment = array($attachmentId);
			$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,"html",'','y',$attachment);
		}
		else {
			$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,"html");
		}
    }
}