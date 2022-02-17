<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Captcha {
	
	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->library('session');
		$this->font = APPPATH.'modules/Common/common/libraries/captcha/monofont.ttf';
	}

	private function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	public function CaptchaSecurityImages($width='120',$height='40',$characters='6',$secvariable) {
		$code = $this->generateCode($characters);
		if(defined('ESCAPE_CAPTCHA') && (ESCAPE_CAPTCHA == TRUE))
		{
		   $code = 'TESTS';
		}        
		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 20, 40, 100);
		$noise_color = imagecolorallocate($image, 100, 120, 180);
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		} 
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $this->font,$code) or die('Error in imagettfbbox function');
		
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) or die('Error in imagettftext function');
		/* output captcha image to browser */
		$this->CI->session->set_userdata($secvariable,$code);
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
	}
	
	
	
	private function _getCaptcha($secvariable){
		return $this->CI->session->userdata($secvariable);
	}
	
	public function verify($secvariable,$value,$reset = 0){
		$returnVal = ($this->_getCaptcha($secvariable) == $value);
		if($reset){
			$this->clearCaptcha($secvariable);
		}
		return $returnVal;
	}
	
	public function clearCaptcha($secvariable){
		$this->CI->session->unset_userdata($secvariable);
	}

}
