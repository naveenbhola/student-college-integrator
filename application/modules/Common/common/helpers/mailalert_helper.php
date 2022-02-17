<?php 

function sendMailAlert($msg, $subject, $recipients=array(), $mentionedRecipientsOnly = FALSE)
{
     /*
      * Message
      */
     $message = '';
     $message .= "\n at " . date('l jS \of F Y h:i:s A');
     $message .= "\n\n Error details ::" . $msg;

     // To send HTML mail, the Content-type header must be set
     $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	 if(!empty($recipients) && is_array($recipients) && $mentionedRecipientsOnly){
		  $to = $recipients[0];
	 } else {
		  // Additional headers
		  $to  	    = 'vikas.k@shiksha.com';
		  $headers .= 'To: Ankur Gupta <ankur.gupta@shiksha.com>' . "\r\n";
		  $headers .= 'To: Amit Kuksal <amit.kuksal@shiksha.com>' . "\r\n";
		  $headers .= 'To: Nikita Jain <nikita.jain@shiksha.com>' . "\r\n";
		  $headers .= 'To: Sukhdeep Kaur <sukhdeep.kaur@99acres.com>' . "\r\n";
	 }
	 if(is_array($recipients) && count($recipients))
	 {
		  foreach($recipients as $recipient)
		  {
			  $headers .= 'Cc: <'.$recipient.'>' . "\r\n";
		  }
	 }
     $headers .= 'From: info <info@shiksha.com>' . "\r\n";
     // Mail it
     @mail($to, $subject, $message, $headers);                        
}

function sendMailAlertDev($msg, $subject, $recipients=array(), $mentionedRecipientsOnly = FALSE)
{
     /*
      * Message
      */
     $message = '';
     $message .= "\n at " . date('l jS \of F Y h:i:s A');
     $message .= "\n\n Error details ::" . $msg;

     // To send HTML mail, the Content-type header must be set
     $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	 if(!empty($recipients) && is_array($recipients) && $mentionedRecipientsOnly){
		  $to = $recipients[0];
	 } else {
		  // Additional headers
		  $to  	    = 'ankit.b@shiksha.com';
		  $headers .= 'To: Nikita Jain <nikita.jain@shiksha.com>' . "\r\n";
	 }
	 if(is_array($recipients) && count($recipients))
	 {
		  foreach($recipients as $recipient)
		  {
			  $headers .= 'Cc: <'.$recipient.'>' . "\r\n";
		  }
	 }
     $headers .= 'From: info <info@shiksha.com>' . "\r\n";
     // Mail it
     @mail($to, $subject, $message, $headers);                        
}