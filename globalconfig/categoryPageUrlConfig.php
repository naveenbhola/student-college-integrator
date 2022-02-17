<?php

$categoryURLPrefixMapping = array(
    2 => SHIKSHA_SCIENCE_HOME_PREFIX,
    3 => SHIKSHA_HOME,
    4 => SHIKSHA_BANKING_HOME_PREFIX,
    5 => SHIKSHA_MEDICINE_HOME_PREFIX,
    6 => SHIKSHA_HOSPITALITY_HOME_PREFIX,
    7 => SHIKSHA_MEDIA_HOME_PREFIX,
    9 => SHIKSHA_ARTS_HOME_PREFIX,
    10 => SHIKSHA_IT_HOME_PREFIX,
    11 => SHIKSHA_RETAIL_HOME_PREFIX,
    12 => SHIKSHA_ANIMATION_HOME_PREFIX,
    13 => SHIKSHA_DESIGN_HOME_PREFIX,
    14 => SHIKSHA_TESTPREP_HOME_PREFIX
);

$countryURLPrefixMapping = array(
    5 => SHIKSHA_AUSTRALIA_HOME_PREFIX,
    7 => SHIKSHA_NEWZEALAND_HOME_PREFIX,
    8 => SHIKSHA_CANADA_HOME_PREFIX,
    9 => SHIKSHA_GERMANY_HOME_PREFIX,
    6 => SHIKSHA_SINGAPORE_HOME_PREFIX,
    4 => SHIKSHA_UK_HOME_PREFIX,
    3 => SHIKSHA_USA_HOME_PREFIX,
    21 => SHIKSHA_IRELAND_HOME_PREFIX,
    18 => SHIKSHA_UAE_HOME_PREFIX,
    19 => SHIKSHA_QATAR_HOME_PREFIX,
    20 => SHIKSHA_SAUDIARABIA_HOME_PREFIX
);

$regionURLPrefixMapping = array(
    1 => SHIKSHA_SOUTHEASTASIA_HOME_PREFIX,
    2 => SHIKSHA_EUROPE_HOME_PREFIX,
    3 => SHIKSHA_MIDDLEEAST_HOME_PREFIX,
    4 => SHIKSHA_UKIRELAND_HOME_PREFIX,
    5 => SHIKSHA_NEWZEALAND_FIJI_HOME_PREFIX,
    6 => SHIKSHA_FAREAST_HOME_PREFIX,
    7 => SHIKSHA_CHINA-HK_TAIWAN_HOME_PREFIX,
    8 => SHIKSHA_AFRICA_HOME_PREFIX,
);

// Introduced with the resolution of Ticket #960..
$categoryPageMappingDataMadeHistory = array('CATEGORYID'=> array(121, 121, 127, 127, 127, 110, 110, 110, 110, 122, 122, 96, 95, 95, 95, 95, 95, 95, 96, 96, 96, 88, 141, 141, 35, 35, 76, 76, 76, 76, 85, 88, 143, 143, 201, 201, 211, 211, 122, 113, 96, 96, 88, 88, 85, 201, 201),
                                     'CATEGORYID_TO_REDIRECT'=> array(121, 121, 127, 127, 127, 110, 110, 110, 110, 122, 122, 96, 95, 95, 95, 95, 95, 95, 96, 96, 96, 88, 141, 141, 35, 35, 76, 76, 76, 76, 85, 88, 143, 143, 201, 201, 211, 211, 122, 113, 92, 91, 87, 87, 19, 153, 153),
                                     'LDBCOURSEID' => array(48, 49, 76, 84, 86, 115, 116, 117, 118, 119, 120, 224, 232, 233, 234, 235, 236, 237, 239, 240, 241, 265, 395, 451, 494, 495, 521, 524, 526, 530, 698, 699, 792, 793, 1217, 1222, 1232, 1239, 1304, 1304, 203, 204, 269, 270, 282, 1217, 1222),
                                     'LDBCOURSEID_TO_REDIRECT' => array(1379, 1380, 1381, 1382, 1383, 1384, 1385, 1386, 1387, 1388, 1389, 1390, 1391, 1392, 1393, 1394, 1395, 1396, 1397, 1398, 1399, 1, 1401, 1402, 1403, 1404, 1405, 1406, 1407, 1408, 1409, 1410, 1411, 1412, 1413, 1414, 1415, 1416, 1417, 1418, 203, 204, 269, 270, 282, 1217, 1222)
                                    );
global $COURSELEVEL_TOBEHIDDEN_CONFIG;
$COURSELEVEL_TOBEHIDDEN_CONFIG = array(
	'topstudyabroadUGcourses'=>
        	array('Under Graduate Degree','Certification','Diploma','Dual Degree'),
        'topstudyabroadPGcourses'=>
                array('Post Graduate Degree','Post Graduate Diploma','Dual Degree'),
        'topstudyabroadPHDcourses'=>
                array('Doctorate Degree','Dual Degree')                       
         );
         
global $COURSE_LEVEL_LEBELS_UG;
$COURSE_LEVEL_LEBELS_UG = array('Under Graduate','Diploma');

global $COURSE_LEVEL_LEBELS_PG;
$COURSE_LEVEL_LEBELS_PG = array('Post Graduate','Post Graduate Diploma');

global $COURSE_LEVEL_LEBELS_PHD;
$COURSE_LEVEL_LEBELS_PHD  = array('Doctorate','Post Doctorate');

global $EXCLUDED_STATES_IN_LOCATION_LAYER;
$EXCLUDED_STATES_IN_LOCATION_LAYER = array(128,129,130,131,134,135);

/* Introduced with the resolution of Ticket #940, Array format should be as:
 *  array('INSTITUTE_ID' => array('SUBCAT_ID_1', 'SUBCAT_ID_2', 'SUBCAT_ID_3'...., 'SUBCAT_ID_N'));
 */
$RANDOM_ROTATE_CATPAGE_COURSES_FOR_INSTITUTEIDS = array(
    '35347' => array(28, 41, 43, 44, 84, 86, 87, 100)
);
