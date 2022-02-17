<?php
$view_mail_in_browser_link = 'Y';
if($mailer_name == 'ForgotPasswordFullTimeMBA' || $mailer_name == 'ForgotPasswordFullTimeMBAMobile') {
	$view_mail_in_browser_link = 'N';
}
?>
<?php $this->load->view('systemMailer/CommonMailers/CommonMailerHeader', array('view_mail_in_browser_link'=>$view_mail_in_browser_link)); ?>
<?php
	switch($mailer_name){
		case 'WelcomeFullTimeMBA':
			$this->load->view("systemMailer/CommonMailers/welcomeMBA");
			break;
		case 'ForgotPasswordFullTimeMBA':
			$this->load->view("systemMailer/CommonMailers/forgotPassword");
			break;
		case 'ForgotPasswordFullTimeMBAMobile':
			$this->load->view("systemMailer/CommonMailers/forgotPassword");
			break;
		case 'NationalCourseDownloadBrochure1':
			$this->load->view("systemMailer/CommonMailers/national_course_download_brochure_1");
			break;
		case 'NationalCourseDownloadBrochure2':
			$this->load->view("systemMailer/CommonMailers/national_course_download_brochure_2");
			break;
		case 'NationalCourseDownloadBrochure3':
			$this->load->view("systemMailer/CommonMailers/national_course_download_brochure_3");
			break;
		case 'NationalCourseDownloadBrochureMobile1':
			$this->load->view("systemMailer/CommonMailers/national_course_download_brochure_1");
			break;
		case 'NationalCourseDownloadBrochureMobile2':
			$this->load->view("systemMailer/CommonMailers/national_course_download_brochure_2");
			break;
	}
?>
<?php $this->load->view('systemMailer/CommonMailers/CommonMailerFooter'); ?>
