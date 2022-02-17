<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
// Ppal (Paypal IPN Class)
// ------------------------------------------------------------------------

// If (and where) to log ipn to file
$config['paypal_lib_ipn_log_file'] = BASEPATH . 'logs/paypal_ipn.log';
$config['paypal_lib_ipn_log'] = TRUE;

// Where are the buttons located at 
$config['paypal_lib_button_path'] = 'buttons';

// What is the default currency?
$config['paypal_lib_currency_code'] = 'USD';


// Paypal sandbox url
	    
$config['sandbox_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

// Paypal gateway url

$config['gateway_url'] = 'https://www.paypal.com/cgi-bin/webscr';

 
 
 
 
 
?>
