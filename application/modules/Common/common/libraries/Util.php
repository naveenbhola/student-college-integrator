<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Util
{
    public function to_xmlrpc_array($arr)
    {
        $xarr = array();
        foreach($arr as $el)
        {
            array_push($xarr, array($el,'struct'));
        }
        return $xarr;
    }

    public function rotate($arr, $start = -1)
    {
        $len = count($arr);
        if($start == -1) $start = rand(0, $len - 1);
        $rarr = array();
        for($i = 0; $i < $len ;$i++)
        {
            $x = ($i + $start)%$len;
            $rarr[$i] = $arr[$x];
        }
        return $rarr;
    }

    public function change_array($arr, $field){
        $xarr = array();
        error_log("in change array ==========>".print_r($arr, true));
        foreach($arr as $el)
        {
            array_push($xarr, $el[$field]);
            error_log("hiiiiii----".print_r($el,true)."---".$field);
        }
        return $xarr;
    }

    public function print_rf($arr){
        echo "<pre>".print_r($arr, true)."</pre>";
    }

    public function mod_next($x, $n){
        return ($x + 1) % $n;
    }

    public function mod_prev($x, $n){
        return ($x - 1) % $n;
    }

    public function hyphen_to_space($str){
        
    }
    
    public function sendEmailWithAttachement($from,$to,$cc,$subject,$message,$attachement_path,$fileName) {
  
    	$fileatt_type = "application/zip, application/octet-stream"; // File Type
    	$fileatt_name = $fileName;
    
    	$email_message = $message;
    	$headers = "From: ".$from."\r\n"."Cc: ".$cc;
    	$semi_rand = md5(time());
    
    	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
    	$headers .= "\nMIME-Version: 1.0\n" .
    			"Content-Type: multipart/mixed;\n" .
    			" boundary=\"{$mime_boundary}\"";
    
    	$email_message .= "This is a multi-part message in MIME format.\n\n" .
    			"--{$mime_boundary}\n" .
    			"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
    			"Content-Transfer-Encoding: 7bit\n\n" .
    			$email_message .= "\n\n";
    
    	$data = chunk_split(base64_encode(file_get_contents($attachement_path)));
    	$email_message .= "--{$mime_boundary}\n" .
    	"Content-Type: {$fileatt_type};\n" .
    	" name=\"{$fileatt_name}\"\n" .
    	"Content-Transfer-Encoding: base64\n\n" .
    	$data .= "\n\n" .
    	"--{$mime_boundary}--\n";
    	
    	error_log('toId '.$to);
    	error_log('emailmessage '.$email_message);
    	
    	$ok = mail($to,$subject, $email_message, $headers);
    	error_log('mailthesend '.$ok);
    
    }
    

}

?>
