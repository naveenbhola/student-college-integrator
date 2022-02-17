<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * mobile Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Ravi Raj
 */
// ------------------------------------------------------------------------

/**
 * Return Base64 Encoding Image
 * see @ http://developer.mozilla.org/en/Creating_OpenSearch_plugins_for_Firefox
 * @param str $url
 * @return  string
 */

//embedding the image source into the html request, returning the data-Uri 
//of image in base64 format.this function just gives the data -uri of image.
if(!function_exists('base64_encode_image')){
  function base64_encode_image($url) {
  /*	
    $im = file_get_contents($url);
    $imdata = base64_encode($im);
    return "data:image/x-icon;base64,$imdata";
  */
    return $url;
  }
}

if(!function_exists('displaySubStringWithStrip')){
  function displaySubStringWithStrip($string, $length=NULL)
  {
      if ($length == NULL) {
  	$length = 15;
      }
  	$stringDisplay = substr(strip_tags($string), 0, $length);
      if (strlen(strip_tags($string)) > $length)
      {
          $stringDisplay .= ' ...';
      }
  	return $stringDisplay;
  }
}

if(!function_exists('googleAnalyticsGetImageUrl')){
  function googleAnalyticsGetImageUrl($str)
  {
      $GA_ACCOUNT = "MO-4454182-1";
      $GA_PIXEL =  "ga.php";
      $url  = $str . $GA_PIXEL . "?";
      $url .= "utmac=" . $GA_ACCOUNT;
      $url .= "&utmn=" . rand(0, 0x7fffffff);

      $referer = $_SERVER["HTTP_REFERER"];
      $query = $_SERVER["QUERY_STRING"];
      $path = $_SERVER["REQUEST_URI"];

      if (empty($referer)) {
        $referer = "-";
      }
      $url .= "&utmr=" . urlencode($referer);

      if (!empty($path)) {
        $url .= "&utmp=" . urlencode($path);
      }

      $url .= "&guid=ON";

      return $url;
  }
}

if(!function_exists('validateMobileUsername')){
  function validateMobileUsername($str,$caption)
  {
         $strToValidate = stripslashes($str);   
         $allowedChars = "/^([A-Za-z0-9\s](,|\.|_|-){0,2})*$/";
         if($strToValidate == '' || $strToValidate == 'Your Name'){
        return "The  $caption field is required.";
      }
      $minLength = 1;
      $maxLength =50;
      if(strlen($strToValidate)< $minLength){
        return "The $caption field should be atleast  $minLength  characters.";
      }
      if(strlen($strToValidate) > $maxLength){
        return "The $caption cannot exceed $maxLength characters.";    
      }
      $result = preg_match($allowedChars,$strToValidate);
      if(!$result){
        return "The $caption field can not contain special characters.";
      }
      return true;
  }
}

if(!function_exists('validateMobilePhone')){
 function validateMobilePhone($str,$caption)
 {
      if ($str == '') {
      return "The $caption field is required.";
    }
    if (!ctype_digit($str)) {
      return "The $caption field must contain digits only.";
    }
    if (strlen($str) != 10) {
      return "The $caption field must contain a 10 digit valid number.";
    }

    $begin = array('9','8','7');
    if (!in_array((substr($str, 0, 1)) , $begin)) {
      return "The $caption field must start with 9 or 8 or 7.";
    }
    return TRUE;
  }
}

if(!function_exists('validateMobileEmailField')){
  function validateMobileEmailField($str,$caption)
   {
      if ($str == '') {
        return "The $caption field is required.";
      }
      $filter ="/^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/";
      if(!preg_match($filter,$str)){
        return "The $caption field must contain valid email address.";
      }   
      return TRUE;  
   }
}

if(!function_exists('secureForm')){
  function secureForm($human_typing_time,$hash)
  {
      $CI = & get_instance();
      $secret_key =  $CI->config->item('secret_key'); 
      $vars = explode(',', $hash);
      //error_log("helper::" . $secret_key);
      //error_log("helper::" . md5($secret_key.$vars[1]));
      //error_log("helper::" . time());
      //error_log("helper::" . $vars[0]);
      //error_log("helper::" . ($vars[1] + $human_typing_time));
      if((md5($secret_key.$vars[1]) != $vars[0] ) || (time()  < ( $var[1] + $human_typing_time)))
      {
        return false;
      }
      else
      {
        return true; 
      }
  }
}

if(!function_exists('MakeLinksFrmString')){
  function MakeLinksFrmString($text)
  {
        // force http: on www.
        $text = ereg_replace( "www\.", "http://www.", $text );
        // eliminate duplicates after force
        $text = ereg_replace( "http://http://www\.", "http://www.", $text );
        $text = ereg_replace( "https://http://www\.", "https://www.", $text );
        // The Regular Expression filter
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        // Check if there is a url in the text
        if(preg_match_all($reg_exUrl, $text, $url)) {
        // make the urls hyper links
        $matches = array_unique($url[0]);
        //_p($matches);
        foreach($matches as $match) {
          if(strpos($match, "shiksha") !== FALSE)
          {
            $replacement = '<a target="_blank" href="'.$match.'">'.displaySubStringWithStrip($match,30).'</a>';
          }
          else
          {
            $replacement = '<a rel="nofollow" target="_blank" href="'.$match.'">'.displaySubStringWithStrip($match,30).'</a>';
          }
            $text = str_replace($match,$replacement,$text);
        }
        return  $text;
        } else {
        // if no urls in the text just return the text
        return  $text;
        }
  }
}

if(!function_exists('filter_item_from_array')){
  function filter_item_from_array($link,$currenturl)
  {
         $a = parse_url($link);
         $b  = parse_url($currenturl);
           if($a['path'] == $b['path']) {
             return true;
          }
          else
          {
          return false;
          }
  }
}

if(!function_exists('display_shiksha_expert_star')){
  function display_shiksha_expert_star($userid,$levelVCard)
  {
    $level = trim($levelVCard[$userid]['level']);
      if($level == "Advisor")
      {
          return "<img  alt='$level' src='public/mobile/images/str_1s.gif' />";
      }elseif ($level == "Senior Advisor") {
        # code...
        return "<img alt='$level' src='public/mobile/images/str_2s.gif'  />";
      }elseif ($level == "Lead Advisor") {
        # code...
        return "<img alt='$level' src='public/mobile/images/str_3s.gif '' />";
      }elseif ($level == "Principal Advisor") {
        # code...
        return "<img alt='$level' src='public/mobile/images/str_4s.gif'  />";
      }elseif ($level == "Chief Advisor") {
        # code...
        return "<img alt='$level' src='public/mobile/images/str_5s.gif'  />";
      }
      else
      {
          return "";
      }
  }
}

  /* End of file mobile/helpers/mobile_helper.php */
