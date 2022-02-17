<?php
class CaptchaControl extends MX_Controller{
	function showCaptcha(){
		$this->load->library('common/captcha/Captcha');
		$width = isset($_REQUEST['width']) ? $_REQUEST['width'] : '120';
		$height = isset($_REQUEST['height']) ? $_REQUEST['height'] : '40';
		$secvariable = isset($_REQUEST['secvariable'])? $_REQUEST['secvariable']:'security_code';
		$characters = isset($_REQUEST['characters']) && $_REQUEST['characters'] > 1 ? $_REQUEST['characters'] : '6';
		$captcha = new Captcha();
		$captcha->CaptchaSecurityImages($width,$height,$characters,$secvariable);
	}
}