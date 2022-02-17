<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Ranking Config, This file contains all the config variables related to search
|--------------------------------------------------------------------------
|
*/

$config['MINIMUM_RANKING_PAGE_RESULTS'] = 3;

$config['MAXIMUM_RESULTS_COUNT_IN_EXTRA_DATA_BLOCK'] = 10;

$config['VALID_SORT_KEYS'] = array('rank', 'fees', 'marks', 'institute_name','salary','examScore');
$config['VALID_SORT_ORDERS'] = array('asc', 'desc');

//Represents: General Management, Finance, HR, Sales & Marketing, Operations, Information technology. 
$config['SPECIALIZATION_ORDER'] = array(4, 6, 7, 5, 3, 8);

//Represents: CAT, XAT, MAT
$config['EXAMS_ORDER'] = array(305, 309, 306);

$config['RBP_TIER_1_CITY_SUBCAT'] = array(1 => 451, 2 => 452, 3 => 453, 4 => 454, 5 => 455);
$config['RBP_TIER_2_CITY_SUBCAT'] = array(1 => 456, 2 => 457, 3 => 458, 4 => 459, 5 => 460);
$config['RBP_TIER_3_CITY_SUBCAT'] = array(1 => 461, 2 => 462, 3 => 463, 4 => 464, 5 => 465);

$config['RBP_TIER_1_STATE_SUBCAT'] = array(1 => 466, 2 => 467, 3 => 468, 4 => 469, 5 => 470);
$config['RBP_TIER_2_STATE_SUBCAT'] = array(1 => 471, 2 => 472, 3 => 473, 4 => 474, 5 => 475);
$config['RBP_TIER_3_STATE_SUBCAT'] = array(1 => 476, 2 => 477, 3 => 478, 4 => 479, 5 => 480);

$config['RBP_ALL_CITY_SUBCAT'] = array(1 => 481, 2 => 482, 3 => 483, 4 => 484, 5 => 485);

$config['MAXIMUM_ALLOWED_BANNERS_FOR_STATE'] 	= 10;
$config['MAXIMUM_ALLOWED_BANNERS_FOR_CITY'] 	= 10;

$config['MAXIMUM_COURSES_IN_RANKING_PAGE'] 	= 2000;

// define('RANKING_PAGE_TABLE','ranking_pages');
// define('RANKING_PAGE_DATA_TABLE','ranking_page_data');