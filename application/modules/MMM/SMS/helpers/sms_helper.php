<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Shiksha SMS helper
 *
 * @package		SMS
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ankurg
 */

/**
*
* This function makes the CURL call to send the SMS. This is a private function and is only called from sendSingleSms
* Input : xmlMessage to be sent
* Output : response
*/
if (! function_exists('makeSmsCURLcall'))
{
	function makeSmsCURLcall($xmlMessage)
	{
		$time = microtime(true);
	    $data = 'data='.urlencode($xmlMessage).'&action=send';
	    $url = "https://api.myvaluefirst.com/psms/servlet/psms.Eservice2";
	    $process = curl_init($url); 
	    curl_setopt($process, CURLOPT_TIMEOUT, 30); 
	    curl_setopt($process, CURLOPT_POSTFIELDS, $data); 
	    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($process, CURLOPT_POST, 1); 
	    $response = curl_exec($process); 
	    curl_close($process); 
	    $time2 = microtime(true);
	    error_log('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'.($time2 - $time));
	    return $response;
	}
}

/**
*
* The controller makes the CURL call to verify the SMS. This is a private function and is only called from performMobileCheck
* Input : xmlMessage to be sent
* Output : response
*/
if (! function_exists('makeVerificationSmsCurl'))
{
	function makeVerificationSmsCurl($xmlData){
	    $url = "https://api.myvaluefirst.com/psms/servlet/psms.Eservice2";
	    $postdata = "data=".urlencode($xmlData)."&action=status";

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_VERBOSE, 0); // set url to post to
	    curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
	    curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/x-www-form-urlencoded"));
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
	    curl_setopt($ch, CURLOPT_POST, 2);
	    curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
	    // Make the Curl Request
	    $result=curl_exec($ch);
	    return $result;
	}
}

?>
