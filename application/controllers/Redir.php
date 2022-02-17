<?php
class Redir extends MX_Controller {
	function init() {
		$this->load->helper(array('form', 'url'));
	}

    function redirurl()
    {
        $this->init();
        $appId = 1;
        $redirectUrl = $this->uri->segment(3);
        $url = base64_decode($redirectUrl);
        error_log_shiksha("OUTURL:".$url);
        header("Location: $url");
    }

    function ctrTrack($position,$type,$typeId)
    {
        $this->init();
        $appId = 1;
        error_log_shiksha($position." : ".$typeId." : ".$type);
    }

    function sendMail($email,$password,$displayname,$viewName)
    {
        $data['email']=$email;
        $data['name']=$displayname;
        $data['password']=$password;
        $this->load->library("alerts_client");
        $content = $this->load->view('common/'.$viewName,$data,true);
		$fromAddress=ADMIN_EMAIL;
        $toEmail = $email;
        $subject = "What's new on Shiksha.com - Your partner for higher education";
		$AlertClientObj = new Alerts_client();
		$alertResponse = $AlertClientObj->externalQueueAdd(12,$fromAddress,$toEmail,$subject,$content,"html");
		return($alertResponse);
    }

}
?>
