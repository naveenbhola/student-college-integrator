<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$config['seoDetails'] = array(
	'welcomePage' => array(
		'title' 		=> "DHL Student Offer - Get 40% off using Shiksha DHL Express Service",
		'description' 	=> "Send your applications to universities abroad at a discounted price using the Shiksha DHL Express service. Special offer for students!",
		'url' 			=> SHIKSHA_STUDYABROAD_HOME.'/apply/shipment'
		),
	'schedulePickupPage' => array(
		'title' 		=> "Shipping Information - Shiksha DHL Express",
		'description' 	=> "Fill in the sender's and university's details to send the shipments through Shiksha DHL Express service.",
		'url' 			=> SHIKSHA_STUDYABROAD_HOME."/apply/shipment/shipping-information"
		),
	'confirmationPage' => array(
		'title' 		=> array("confirmation" =>"Thank you for your order! - Shiksha DHL Express",
                                 "track"        =>"Track your shipments – Shiksha DHL Express"),
		'description' 	=> array("confirmation" =>"Your order has been successfully placed with DHL. Thank you for your order!",
                                 "track"        =>"Track your DHL shipments placed through Shiksha DHL Express"),
		'url' 			=> SHIKSHA_STUDYABROAD_HOME."/apply/shipment/shipping-confirmation"
		)
	);

$config['shipmentPriceComparision'] = array(
		'originalPrice' => 1426,
		'discountedPrice' => 925
	);

$config['shipmentWeight'] = array(
		"0.5" => "0.5 KG",
		"1.0" => "1.0 KG",
		"1.5" => "1.5 KG",
		"2.0" => "2.0 KG"
	);
$config['pickupTimeToDbEnumMapping']=array(
    '16:00-18:00' =>'1',
    '18:00-20:00' =>'2',
    '16:00-20:00' =>'3'
);

$config["statusMapping"] = array(
		'PB' => "booked",
		'PS' => "pickedup",
		'IT' => "inTransit",
		'DD' => "delivered"
	);
	
//tracking status of shiksha
define('PICKUP_BOOKED','PB');
define('PICKUP_SUCCESS','PS');
define('IN_TRANSIT','IT');
define('DELIVERED','DD');


define('SHIPMENT_ACCOUNT_TYPE','D'); // account type as DHL=> D  other possible value: Credit: C
define('SHIPMENT_PAYMENT_TYPE','S'); // S-> Shipper  R-> Recipient T-> Third Party
define('SHIPMENT_PICKUP_REGION_CODE','AP'); // AP->Asia Pacific + Emerging Market , EU – Europe (EU +Non-EU), AM – Americas (LatAm + US+ CA) 
define('SHIPMENT_PICKUP_LOCATION_TYPE','R'); //  B=Business R=Residence C=Business/Residence
define('SHIPMENT_WEIGHT','1');
define('SHIPMENT_WEIGHT_UNIT','K'); // K-kilogram 
define('SHIPMENT_WEIGHT_UNIT_FOR_QUOTE','KG'); //KG-kilogram
define('SHIPMENT_NUMBER_OF_PIECES','1');


define("SHIPMENT_DOOR_TO",'DD');  // DD- Door to Door
define("SHIPMENT_PRODUCT_CODE",'D'); // D- Document
// <---------- status Codes --------->
// request to dhl service gave a valid response
define('SHIPMENT_SUCCESS_CODE','200OK');  
define("SHIPMENT_SUCCESS",'success');
define("SHIPMENT_FAILURE",'Failure');
//unexpected error occurred in the request to dhl service
define('SHIPMENT_UNEXPECTED_ERROR_CODE','-1'); 
define('SHIPMENT_UNEXPECTED_ERROR',SHIPMENT_FAILURE); 
define('SHIPMENT_UNEXPECTED_ERROR_DESCRPTION','Unexpected error occurred');

// error occurred in xml parsing 
define('DHL_XML_PARSING_ERROR_CODE','111'); 
define('DHL_XML_PARSING_ERROR',SHIPMENT_FAILURE); 

// Invalid Input Data 
define('SHIPMENT_INVALID_INPUT_ERROR_CODE','112I');
define('SHIPMENT_INVALID_INPUT_ERROR',SHIPMENT_FAILURE);

// The API could not generate a valid response for the input. Some data is missing or data is not valid. eg: Invalid pin code or country id
define('SHIPMENT_INVALID_DATA_CODE','302E');
define('SHIPMENT_INVALID_DATA_STATUS',SHIPMENT_FAILURE);


define('SHIPMENT_DHL_FEEDBACK','http://bit.ly/Shiksha-DHL-feedback');


?>

