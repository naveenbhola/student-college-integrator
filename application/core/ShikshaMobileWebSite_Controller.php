<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User validation & other stuff should move here.
 * 
 */

class ShikshaMobileWebSite_Controller extends MX_Controller
{
	public $data;
	public $ci_mobile_capbilities;
	public $is_js_support;
	public $device_screen_width;
	public $device_screen_height;
	public $is_nokia;
	public $model_name;
    //public $brand_name;
    //public $marketing_name; 
	public $device_os;
	//public $is_wireless;
	//public $is_smarttv;
	//public $is_tablet;
    //public $is_phone;
    public $logged_in_user_array;
    public $shiksha_site_current_url;
    public $shiksha_site_current_refferal;

	public function __construct()
	{
		$this->benchmark->mark('ShikshaMobile_parent_constuctor_start');
		parent::__construct();
		$this->benchmark->mark('ShikshaMobile_parent_constuctor_end');

		$this->benchmark->mark('my_controller_start');

		if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile'))
		{
			/**
			 * SET Vary header for mobile site
			 */
			$this->output->set_header("Vary:Accept-Encoding,User-Agent");
			
			if($this->config->item('mobile_site_open') === FALSE)
			{
				show_error('Sorry the mobile site is down. Please try later.');
			}
			
			// Load the mobile helper
			$this->load->helper('mcommon/mobile');

			// Logged-in User Detail
			$this->data['m_loggedin_userDetail'] = $this->checkUserValidation();

        	###################################################################
			global $user_logged_in;
			global $logged_in_userid;
			global $shiksha_site_current_url;
			global $shiksha_site_current_refferal;
			global $logged_in_usermobile;
			global $logged_in_user_name;
			global $logged_in_first_name;
			global $logged_in_user_email;
			global $shiksha_site_current_url;
			global $shiksha_site_current_refferal;
			global $logged_in_user_avtar_url;

			$logged_in_user_array = $this->data['m_loggedin_userDetail'];

			if (!is_array($logged_in_user_array) && $logged_in_user_array == 'false')
			{
				$logged_in_user_array = array();
			} 
			else
			{
				$logged_in_user_array = $logged_in_user_array[0];
			}

			$logged_in_userid = (!isset($logged_in_user_array['userid'])) ? '-1' : $logged_in_user_array['userid'];

			$shiksha_site_current_url = htmlentities(current_url());
			
			if($_SERVER['HTTP_REFERER'])
			{ 
				$shiksha_site_current_refferal =  htmlentities($_SERVER['HTTP_REFERER']);
			}
			else
			{   
				$shiksha_site_current_refferal = "www.shiksha.com";
			}

			//Pass data to all views
			$this->load->vars($this->data);
			
			$this->logged_in_user_array				= $logged_in_user_array;
    		$this->shiksha_site_current_url			= $shiksha_site_current_url;
    		$this->shiksha_site_current_refferal 	= $shiksha_site_current_refferal;	
			#############################################################
			/**
			 * Assign mobile capibilities into global varible
			 */
			if(empty($_COOKIE['ci_mobile_capbilities']))
			{
				GLOBAL $ci_mobile_capbilities;
				$this->ci_mobile_capbilities = $ci_mobile_capbilities;
			}
			else
			{
				$this->ci_mobile_capbilities = json_decode($_COOKIE['ci_mobile_capbilities'],true);
			}
			
			$this->is_js_support = $this->ci_mobile_capbilities['ajax_support_javascript'];
			$this->device_screen_width = $this->ci_mobile_capbilities['resolution_width'];
			$this->device_screen_height = $this->ci_mobile_capbilities['resolution_height'];
			/*if((($this->ci_mobile_capbilities['nokia_series'] != "0") && ($this->ci_mobile_capbilities['nokia_edition']) != "0") || ( $this->ci_mobile_capbilities['device_os'] == 'Symbian OS'))
			{
				$this->is_nokia 		= 'yes';
			}
			else
			{
				$this->is_nokia 		= 'no';
			}*/
			$this->is_nokia                 = 'no';

			$this->model_name 		= $this->ci_mobile_capbilities['model_name'];
		    //$this->brand_name 		= $this->ci_mobile_capbilities['brand_name'];
		    //$this->marketing_name 	= $this->ci_mobile_capbilities['marketing_name'];
			$this->device_os 		= $this->ci_mobile_capbilities['device_os'];
			//$this->is_wireless 		= $this->ci_mobile_capbilities['is_wireless'];
			//$this->is_smarttv 		= $this->ci_mobile_capbilities['is_smarttv'];
			//$this->is_tablet 		= $this->ci_mobile_capbilities['is_tablet'];
    		//$this->is_phone 		= $this->ci_mobile_capbilities['is_phone'];

			// SHIKSHA_DEVELOPMENT
			if (ENVIRONMENT === SHIKSHA_DEVELOPMENT AND is_array($_REQUEST)	AND array_key_exists('_debug', $_REQUEST))
			{
				unset($_GET['_debug']);
				$this->output->enable_profiler(TRUE);
			}
		}

		$this->benchmark->mark('my_controller_end');
	}

	public function __destruct()
	{

	}
}

/**
 * Returns the CodeIgniter object.
 * Example: ci()->db->get('table');
 * @return \CI_Controller
 */

function ci()
{
	return get_instance();
}


// END MY_Controller class

/* End of file ShikshaMobileWebSite_Controller.php */
/* Location: ./application/core/ShikshaMobileWebSite_Controller.php */
