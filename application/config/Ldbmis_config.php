<?php

/**
 * Main mis reports settings file 
 */

/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| 
*/


$config['startdate'] = date("Y-m-d");

/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| 
*/

$date = date("Y-m-d");

$newdate = strtotime ( '-3 day' , strtotime ( $date ) ) ;

$newdate = date ( 'Y-m-j' , $newdate );

$config['enddate'] = $newdate;


/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| 
*/


$config['recipient'] = 'saurabh.gupta@shiksha.com';



/* End of file Ldb_settings.php */

