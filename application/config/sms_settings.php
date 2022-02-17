<?php

/**
 * 
 * Main sms settings file
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines username and password for the sms type
*/


$config['system'] = array(
			'username' => 'shiksha',	
			'PASSWORD' =>  'skh02api08'
                        );

/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines username and password for the sms type 
*/


$config['user_defined'] = array(
			'username' => 'shikshapro',	
			'PASSWORD' =>  'api221211'
                        );

/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines username and password for sending OTP
*/

$config['OTP'] = array(
			'username' => 'shikshaot',	
			'PASSWORD' =>  'shikst02'
                        );