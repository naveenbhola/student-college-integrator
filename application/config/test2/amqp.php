<?php

/**
 * Main payment gateway settings file for CCAvenueINR.
 */

$config['amqp_server'] = '172.16.2.222';

/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| AMQP SERVER PORT
*/


$config['amqp_port'] = '5672';


/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| AMQP USER NAME
*/


$config['amqp_user'] = 'guest';

/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| AMQP USER PASSWORD
*/


$config['amqp_pass'] = 'guest';

/*
|--------------------------------------------------------------------------
|  Settings
|--------------------------------------------------------------------------
|
| Constant needed for running AMQP or not 
| flag value to bypass AMQP
*/

$config['sendmail_via_smtp'] = 'true';

/* End of file amqp.php  */