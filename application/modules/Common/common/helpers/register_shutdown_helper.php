<?php 

/*
* Register shutdown
*/
function registerShutdown($identifier,$send_mail=true,$mail_to=array())
{
	register_shutdown_function('shutdown_handler',$identifier,$send_mail=true,$mail_to=array());
}
	    
function shutdown_handler($identifier,$send_mail=true,$mail_to=array())
{
	$error = error_get_last();
    
    if($error && $error['type'] == E_ERROR)
	{		
		$error_message = $error['message']." in file ".$error['file']." at line ".$error['line'];

		error_log($identifier."_fatal_error: ".$error_message);
	
		if($send_mail)
		{
			sendMailAlert("A fatal error has occured in $identifier\n\n".$error_message,"$identifier fatal error",$mail_to);
		}
	}
}