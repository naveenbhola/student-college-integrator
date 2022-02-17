<?php

/**
 * Main payment gateway settings file for CCAvenueINR.
 */

//$config['payment_gateway_url'] = 'https://www.ccavenue.com/shopzone/cc_details.jsp';
$config['payment_gateway_url'] = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';

/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines the Merchant_Id to be used for CCAvenue 
*/

$config['Merchant_Id'] = 393;
$config['OF_Merchant_Id'] = 393;
$config['access_code'] = 'VQBE1ZRP31BDEUJG';
$config['working_key'] = '1A86C527414937E60C3938C4F1C18998';
$config['language'] = 'EN';

/*
$config['Merchant_Id'] = M_shikshar_17222;
$config['OF_Merchant_Id'] = M_shikshar_17222;*/


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

$config['redirectURL'] =  $BaseURl.'enterprise/Enterprise/ccavenueindianresponse';
$config['OF_redirectURL'] =  $BaseURl.'Online/OnlineForms/processOnlinePayment';


/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines the WorkingKey to be used for CCAvenue  
*/


$config['WorkingKey'] = '6g2xu1i0tb38xqiwdq';


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


$config['currency'] = 'INR';




/* End of file ccavenue_PaymentGateway_settings.php */
