<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
 */

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb');	// truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Environment
|--------------------------------------------------------------------------
 */


define('SHIKSHA_ENV', 'qa');
define('FACEBOOK_API_ID','470179189680439');
define('FB_CHANNEL_PATH',SHIKSHA_HOME . '/public/channel.html');


// other Fconnect constants
define('FACEBOOK_API_KEY','470179189680439');
define('FACEBOOK_SECRET_KEY','3cdd71ff24bd267b8780b2d803a6e06a');
define('CRON_TIME_VAR','-1');
define('FB_POST_THRESHOLD','3');
define('FB_EXCEPTION_LOG_FILE','/tmp/FacebookExceptionLog.txt');
define('FB_USER_INFO_COOKIE','86400');

/*
|--------------------------------------------------------------------------
| SHIKSHA ENV variable
|--------------------------------------------------------------------------
|
| PHP's ENV variable($_SERVER['REMOTE_ADDR']) return wrong value when we use
| akamai/nginx.Sometimes it returns garbage values like 127.0.0.1 etc.
| Akamai and nginx, both are returns special headers which we need to implement in | out code where we used Remote Address variable.
| This ensures that if the request does not get routed thru akamai,
| then the variable remote_addr will be picked. Variable X_FORWARDED_FOR is getting | preference over remote_addr as the request could be routed to apache thru a proxy | server like nginx/haproxy etc.
|
 */

$s_remote_addr = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));

define('S_REMOTE_ADDR', $s_remote_addr);

/* End of file constants.php */
/* Location: ./system/application/config/constants.php */


define('REDIRECT_URL', 'test');

########################## LDB HARD COADED COURSES ################################

// study Abroad

define('LDB_STUDY_ABROAD','Study Abroad');

// Management
define('LDB_FULL_TIME_MBA_PGDM', 'Full Time MBA/PGDM');
define('LDB_BBA_BBM','BBA/BBM');
define('LDB_DISTANCE_CORRESPONDENCE_MBA','Distance/Correspondence MBA');
define('LDB_EXECUTIVE_MBA','Executive MBA');
define('LDB_FULL_TIME_MBA','Full-time MBA');
define('LDB_INTEGRATED_MBA_COURSES','Integrated MBA Courses');
define('LDB_MANAGEMENT_CERTIFICATIONS','Management Certifications');
define('LDB_ONLINE_MBA','Online MBA');
define('LDB_OTHER_MANAGEMENT_DEGREES','Other Management Degrees');
define('LDB_PART_TIME_MBA','Part-time MBA');

//Science & Engineering
define('LDB_AIRCRAFT_MAINTENANCE_ENGINEERING','Aircraft Maintenance Engineering');
define('LDB_ADVANCE_TECHNICAL_COURSES','Advanced Technical Courses');
define('LDB_BE_BTECH','B.E./B.Tech');
define('LDB_BSC','B.Sc');
define('LDB_DISTANCE_BSC','Distance B.Sc');
define('LDB_DISTANCE_BTECH','Distance B.Tech');
define('LDB_DISTANCE_MSC','Distance M.Sc');
define('LDB_ENGINEERING_DIPLOMA','Engineering Diploma');
define('LDB_ENGINEERING_DISTANCE_DIPLOMA','Engineering Distance Diploma');
define('LDB_SC_INTEGRATED_MBA_COURSES','Integrated MBA Courses');
define('LDB_ME_MTECH','M.E./M.Tech');
define('LDB_MSC','M.Sc');
define('LDB_MARINE_ENGINEERING','Marine Engineering');
define('LDB_MEDICINE_BEAUTY_HEALTH_CARE_DEGREES','Medicine, Beauty & Health Care Degrees');
define('LDB_SCIENCE_ENGINEERING_DEGREES','Science & Engineering Degrees');
define('LDB_SCIENCE_ENGINEERING_PHD','Science & Engineering PHD');

//IT
define('LDB_DISTANCE_BCA_MCA','Distance BCA/MCA');
define('LDB_IT_COURSES','IT Courses');
define('LDB_IT_DEGREES','IT Degrees');

//Animation
define('LDB_ANIMATION_COURSES','Animation Courses');
define('LDB_ANIMATION_DEGREES','Animation Degrees');

//Hospitality
define('LDB_HOSPITALITY_AVIATION_TOURISM_COURSES','Hospitality, Aviation & Tourism Courses');
define('LDB_HOSPITALITY_AVIATION_TOURISM_DEGREES','Hospitality, Aviation & Tourism Degrees');

//Media
define('LDB_MEDIA_FILMS_MASS_COMMUNICATION_COURSES','Media, Films & Mass Communication Courses');
define('LDB_MEDIA_FILMS_MASS_COMMUNICATION_DEGREES','Media, Films & Mass Communication Degrees');

//Test Preparation
define('LDB_TEST_PREP','Test Prep');
define('LDB_TEST_PREP_INTERNATIONAL_EXAMS','Test Prep (International Exams)');

//Others
define('LDB_ARTS_LAW_LANGUAGES_TEACHING_COURSES','Arts, Law, Languages and Teaching Courses');
define('LDB_ARTS_LAW_LANGUAGES_TEACHING_DEGREES','Arts, Law, Languages and Teaching Degrees');
define('LDB_BANKING_FINANCE_COURSES','Banking & Finance Courses');
define('LDB_BANKING_FINANCE_DEGREES','Banking & Finance Degrees');
define('LDB_DESIGN_COURSES','Design Courses');
define('LDB_DESIGN_DEGREES','Design Degrees');
define('LDB_DISTANCE_BA_MA','Distance B.A./M.A.');
define('LDB_FOREIGN_LANGUAGE_COURSES','Foreign Language Courses');
define('LDB_MEDICINE_BEAUTY_HEALTH_CARE_COURSES','Medicine, Beauty & Health Care Courses');
define('LDB_MEDICINE_BEAUTY_HEALTH_CARE_DEGREES','Medicine, Beauty & Health Care Degrees');
define('LDB_RETAIL_DEGREES','Retail Degrees');

//Study Abroad
define('STUDY_ABROAD_POPULAR_MBA','MBA');
define('STUDY_ABROAD_POPULAR_MS','MS');
define('STUDY_ABROAD_POPULAR_BEBTECH','BE/Btech');

define('STUDY_ABROAD_CATEGORY_BUSINESS','Business');
define('STUDY_ABROAD_CATEGORY_ENGINEERING','Engineering');
define('STUDY_ABROAD_CATEGORY_COMPUTERS','Computers');
define('STUDY_ABROAD_CATEGORY_SCIENCE','Science');
define('STUDY_ABROAD_CATEGORY_MEDICINE','Medicine');
define('STUDY_ABROAD_CATEGORY_HUMANITIES','Humanities');
define('STUDY_ABROAD_CATEGORY_LAW','Law');


########################## LDB HARD COADED COURSES ################################
define('SHOW_QUESTION_CAPTCHA',0);
define('SUMS_SERVICE_TAX_VALUE', 10.03);
define('SVN_MAIN_TRUNK', "MAINLINE_LDB_OPT_26Mar");
define('MAX_ALLOWED_SIZE_FOR_IMPORT_EXTERNAL_FORM', "5242880");
