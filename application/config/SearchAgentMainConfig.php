<?php
/**
 * Main config file for Search Agent
 *
 * @author Ravi Raj <ravi.raj@shiksha.com>
 * @package Search Agent Main Config
 * @copyright Copyright 2010,Shiksha.com
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| maximum limit for Auto Download of leads for Search Agent
|--------------------------------------------------------------------------
|
*/

$config['maximum_limit_leads_search_agent'] = 200;

/*
|--------------------------------------------------------------------------
| maximum limit Auto Download  of leads for clients
|--------------------------------------------------------------------------
|
*/

$config['maximum_limit_leads_search_client'] = 500;



/*
|--------------------------------------------------------------------------
| maximum limit for Auto Responder of leads for Search Agent
|--------------------------------------------------------------------------
|
*/

$config['maximum_limit_leads_search_agent'] = 2000;

/*
|--------------------------------------------------------------------------
| maximum limit for Auto Responder of leads for clients
|--------------------------------------------------------------------------
|
*/

$config['maximum_limit_leads_search_client'] = 5000;


/*
|--------------------------------------------------------------------------
| maximum No of sharing of Leads 5
|--------------------------------------------------------------------------
|
*/

$config['maximum_limit_leads_search_client'] = 5000;


/*
|--------------------------------------------------------------------------
| maximum limit for Auto Responder of leads for clients
|--------------------------------------------------------------------------
|
*/

$config['maximum_limit_leads_search_client'] = 5000;




/*
|--------------------------------------------------------------------------
| Sender's email address
|--------------------------------------------------------------------------
|
*/

$config['email_from_address'] = 'info@shiksha.com';

/*
|--------------------------------------------------------------------------
| Sender's Name
|--------------------------------------------------------------------------
|
*/

$config['email_from_name'] = 'Shiksha.com';



/* End of file SearchAgentMainConfig.php */
/* Location: ./system/application/config/SearchAgentMainConfig.php */