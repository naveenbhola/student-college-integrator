<?php
/*
 * Code for tracking
 */
date_default_timezone_set('Asia/Kolkata');

global $serverStartTime;
$serverStartTime = microtime(true);

/*
 *---------------------------------------------------------------
 * PHP ERROR REPORTING LEVEL
 *---------------------------------------------------------------
 *
 * By default CI runs with error reporting set to ALL.  For security
 * reasons you are encouraged to change this to 0 when your site goes live.
 * For more info visit:  http://www.php.net/error_reporting
 *
 */
error_reporting(E_ERROR);
/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
$system_path = "system";

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
$application_folder = "application";

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here.  For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT:  If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller.  Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 *
 */
// The directory name, relative to the "controllers" folder.  Leave blank
// if your controller is not in a sub-folder within the "controllers" folder
// $routing['directory'] = '';

// The controller class file name.  Example:  Mycontroller.php
// $routing['controller'] = '';

// The controller function you wish to be called.
// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------


/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */
if (realpath($system_path) !== FALSE)
{
	$system_path = realpath($system_path).'/';
}

// ensure there's a trailing slash
$system_path = rtrim($system_path, '/').'/';

// Is the system path correct?
if ( ! is_dir($system_path))
{
	exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
define('EXT', '.php');

// Path to the system folder
define('BASEPATH', str_replace("\\", "/", $system_path));

// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));

// Name of the "system folder"
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


// The path to the "application" folder
if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ( ! is_dir(BASEPATH.$application_folder.'/'))
	{
		exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
	}

	define('APPPATH', BASEPATH.$application_folder.'/');
}

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */

if(!isset($_COOKIE["tracker"]) || strlen($_COOKIE["tracker"]) == 0) {
	$len = 16;
	$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
	$max=strlen($base)-1;
	$activatecode='';
	mt_srand((double)microtime()*1000000);
	while (strlen($activatecode)<$len+1)
		$activatecode.=$base{mt_rand(0,$max)};
	$arrayTemp = explode(" ",microtime());
	$activatecode = $arrayTemp[1].$activatecode;
	setcookie('tracker',$activatecode,time() + 259200 ,'/',".shiksha.com");
}else {
	$activatecode = $_COOKIE['tracker'];
}

/* apache_note APIs will call only in HTTP Requests.*/
if (isset($_SERVER['REMOTE_ADDR'])) {
	apache_note('cookie', print_r($_COOKIE,true));
	apache_note('session', $activatecode);
	apache_note('post', print_r($_POST,true));
}

global $uniqueVisitorId;
global $visitSessionId;

/*
* Check and set flag if the request is coming for API
*/
global $isMobileApp;
global $isWebAPICall;
global $webAPISource;

$isMobileApp  = 0;
$isWebAPICall = 0;
$webAPISource = "";
if(in_array($_SERVER['HTTP_SOURCE'], array("AndroidShiksha"))){
	$isMobileApp = 1;
}
if($_SERVER['HTTP_SITEIDENTIFIER'] == "WebAPICall"){
	$isWebAPICall = 1;
}
if($_SERVER['HTTP_WEBAPISOURCE']){
	$webAPISource = $_SERVER['HTTP_WEBAPISOURCE'];
}

global $isWebViewCall;
$isWebViewCall = 0;
if(isset($_COOKIE['AndroidSource']) && $_COOKIE['AndroidSource'] == "AndroidWebView"){
	$isWebViewCall = 1;
}

/*
 * ---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */

define('ENVIRONMENT', 'development');

/*
 * ---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 * Enabling E_STRICT during development has some benefits.
 * STRICT messages will help you to use the latest and greatest suggested method of coding,
 * for example warn you about using deprecated functions.
 * Due to these incompatible changes there will also be other type of errors or
 * maybe, even worse, unexpected behaviors occoured like parse error etc.
 * So ignoring deprecated errors will not suffice. BUT ...
 * We know that time is not an infinite resource so disabling E_DEPRECATED error might be worth.
 *
 */

switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(E_ERROR);
		ini_set('display_errors', 1);
		break;

	case 'testing':
		error_reporting(E_ERROR);
        		ini_set('display_errors', 1);
        		break;

	case 'production':
		error_reporting(E_ERROR);
		ini_set('display_errors', 0);
		break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		exit('The application environment is not set correctly.');
}
require 'shikshaConfig.php';
if(!$isMobileApp){
require 'globalconfig/countriesarray.php';
require 'globalconfig/pageKeys.php';
require 'globalconfig/seotabs.php';
require 'globalconfig/Groupcategory.php';
}
require 'globalconfig/categoryMap.php';
require 'globalconfig/citiesforRegis.php';
require 'globalconfig/countryDataMap.php';
if(!$isMobileApp){
require 'globalconfig/homePageData.php';
require 'globalconfig/bannerConfig.php';
}
require 'globalconfig/shikshaConstants.php';
require 'globalconfig/dfpBannerConfig.php';
require 'globalconfig/shikshaUtilities.php';
require 'globalconfig/moduleMapping.php';
require 'globalconfig/localityConfig.php';
if(!$isMobileApp){
require 'globalconfig/categoryPageUrlData.php';
require 'globalconfig/categoryPageUrlConfig.php';
require 'globalconfig/abroadCategoryPageUrlData.php';
}
require 'globalconfig/cacheProducts.php';
require 'globalconfig/locationMap.php';
require 'globalconfig/locationCountryMap.php';
require 'globalconfig/recommendationsConfig.php';
if(!$isMobileApp){
require 'globalconfig/js_revisions.php';
require 'globalconfig/static_revisions.php';
require 'globalconfig/coursePagesSeoData.php';
require 'globalconfig/StudyAbroadConfig.php';
}
require 'globalconfig/userVerification.php';
require_once BASEPATH.'core/CodeIgniter'.EXT;

/* End of file index.php */
/* Location: ./index.php */
