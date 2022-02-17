<?php

/**
 * Main payment gateway settings file for CCAvenue.
 */


/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines the Merchant_Id to be used for CCAvenue 
*/


// $config['Merchant_Id'] = shikshad7051;  	
$config['Merchant_Id'] = 63433;
/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines at what URL the values will come from CCAvenue 
*/


$this->CI =& get_instance();

$this->CI->config->load('config',true);

$BaseURl = $this->CI->config->item('base_url', 'config');

$config['redirectURL'] =  $BaseURl.'enterprise/Enterprise/ccavenueresponse';

/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines the WorkingKey to be used for CCAvenue  
*/


// $config['WorkingKey'] = 'ata170rns4fuer38egqrowv1f8bfq99m'; 
$config['WorkingKey'] = '085B4E6D1240F0F09EC35516B4C2FAED'; // For sub-domain url : enterprise.shiksha.com 


/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines the AccessCode to be used for CCAvenue  
*/

$config['Access_Code'] = 'AVYS66DG17BN06SYNB'; // For sub-domain url : enterprise.shiksha.com 


/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Constant needed to be send to CCAvenue
*/



$config['TxnType'] = 'A';



/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Constant needed to be send to CCAvenue
*/



$config['actionID'] = 'txn';



/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines the Currency to be used for CCAvenue 
*/


$config['Currency'] = 'USD';




/* End of file ccavenue_PaymentGateway_settings.php */
