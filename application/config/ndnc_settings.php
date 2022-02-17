<?php
/**
 * Main NDNC settings file for NDNC.
 * @author Ravi Raj <ravi.raj@shiksha.com>
 * @package NDNC_config_package
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Determines how Frequency we will pick rows from user table
*/

$config['log_frequency'] = 5000;

/*
|--------------------------------------------------------------------------
| Log Database Settings
|--------------------------------------------------------------------------
|
| Defines the database Name.
*/

$config['db_name'] = 'DNC';

/* Flag to avoid sms sent for NA ndnc users when you run cron first time */

$config['first_time_run'] = 'false';


/* 
|-------------------------------------------------------------------------- 
| Settings 
|-------------------------------------------------------------------------- 
| false by default 
| Flag that tells state of NDNC either it's on or off 
*/ 
 
$config['is_ndnc_run'] = 'true'; 
 
/* 
|-------------------------------------------------------------------------- 
| Settings 
|-------------------------------------------------------------------------- 
| true by default 
| Flag that tells mobile re-varification process is on or off 
*/ 
 
$config['is_mobile_verification_run'] = 'true'; 
 
 
/* 
|-------------------------------------------------------------------------- 
| Settings 
|-------------------------------------------------------------------------- 
| 
| Flag that tells what value return if NDNC process is off 
| false by default 
*/ 
 
$config['return_ndnc_check_if_cron_off'] = 'false'; 
 
 
/* 
|-------------------------------------------------------------------------- 
| Settings 
|-------------------------------------------------------------------------- 
| Frequency of day on which mobile re varification performed 
| 
*/ 
 
$config['no_of_days_to_be_check'] = '4'; 