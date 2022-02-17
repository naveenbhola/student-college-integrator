<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH."third_party/MX/Router.php";

class MY_Router extends MX_Router {

	function _set_routing()
	{
		// Are query strings enabled in the config file?  Normally CI doesn't utilize query strings
		// since URI segments are more search-engine friendly, but they can optionally be used.
		// If this feature is enabled, we will gather the directory/class/method a little differently
		$segments = array();
		if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
		{
			if (isset($_GET[$this->config->item('directory_trigger')]))
			{
				$this->set_directory(trim($this->uri->_filter_uri($_GET[$this->config->item('directory_trigger')])));
				$segments[] = $this->fetch_directory();
			}

			if (isset($_GET[$this->config->item('controller_trigger')]))
			{
				$this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));
				$segments[] = $this->fetch_class();
			}

			if (isset($_GET[$this->config->item('function_trigger')]))
			{
				$this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
				$segments[] = $this->fetch_method();
			}
		}

		global $isMobileApp;
		if(((($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')) && (($_COOKIE['ci_mobile_js_support'] == 'yes') || ($GLOBALS['flag_mobile_js_support_user_agent'] == 'yes'))) && !isset($_COOKIE['showAnAQDPOnMobile']))
                {
                        // Load the Mobile routes.php file if it is Mobile user agent and it has JS support.
                        @include(APPPATH.'config/routes_mobile5'.EXT);
                }
                //else if((($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile'))  && !isset($_COOKIE['showAnAQDPOnMobile']))
                //{
                        // Load the routes.php file.
                //        @include(APPPATH.'config/routes_mobile'.EXT);
                //}
                else if($isMobileApp == 1)
                {
                		// Load the routes_api.php file.
                        @include(APPPATH.'config/routes_api'.EXT);	
                }
                else
                {
                        // Load the routes.php file.
                        @include(APPPATH.'config/routes'.EXT);
                }
		$this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
		unset($route);

		// Set the default controller so we can display it in the event
		// the URI doesn't correlated to a valid controller.
		$this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);

		// Were there any query string segments?  If so, we'll validate them and bail out since we're done.
		if (count($segments) > 0)
		{
			return $this->_validate_request($segments);
		}

		// Fetch the complete URI string
		$this->uri->_fetch_uri_string();
		if($_REQUEST['debug_abroad'] == 1)
		{
			$this->uri->uri_string = "/studyAbroadHome/studyAbroadHome/homepage";
		}
		// check if this is a case of abroad mobile site user
		if(
		   ($this->uri->uri_string == "/studyAbroadHome/studyAbroadHome/homepage") &&
		   ($_COOKIE['mobile_site_user'] == 'abroad') &&
		   ($_COOKIE['user_force_cookie'] != 'YES')
		)
		{
			$this->uri->uri_string = ''; // we dont need to reach desktop page for mobile
			$this->default_controller = 'homePage/HomePage/renderHome';
			return $this->_set_default_controller();
		}
		if(
		   ($this->uri->uri_string == "/messageBoard/AnADesktop/getHomePage") &&
		   ($_COOKIE['mobile_site_user'] == 'national') &&
		   ($_COOKIE['user_force_cookie'] != 'YES')
		)
		{
			$this->uri->uri_string = ''; // we dont need to reach desktop page for mobile
			$this->default_controller = 'mAnA5/AnAMobile/getHomepage';
			return $this->_set_default_controller();
		}
		
		// Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
		if ($this->uri->uri_string == '')
		{
			return $this->_set_default_controller();
		}

		// Do we need to remove the URL suffix?
		$this->uri->_remove_url_suffix();

		// Compile the segments into an array
		$this->uri->_explode_segments();

		// Parse any custom routing that may exist
		$this->_parse_routes();

		// Re-index the segment array so that it starts with 1 rather than 0
		$this->uri->_reindex_segments();
	}
	
	/*
	 This function is used in case someone opens Mobile site URL on Desktop. In that case, we will redirect the user to the correct page
	*/
	function _validate_reverse_request($segments)
	{
		//print_r($_COOKIE);echo "<pre>";print_r($segments);echo"</pre>";exit;
		
		if(count($segments) >= 5 and is_numeric($segments[3]) and ( $segments[2] == 'listingDetailWap'))
		{
			return array('listing', 'Listing', 'getDetailsForListingNew', $segments[3], $segments[4] );
		}

		if(count($segments) == 4 and ($segments[2] == 'categoryPage'))
		{
			return array('categoryList', 'CategoryList', 'categoryPage', $segments[3]);
		}

		if(count($segments) == 4 and ($segments[2] == 'renderHomePage' || $segments[2] == 'showSubCategoriesHome'))
		{
			return array('shiksha', 'index');
		}
		
		if(count($segments) == 3 and ($segments[1] == 'MobileSiteStatic') and ($segments[2] == 'aboutus'))
		{
			return array('shikshaHelp','ShikshaHelp', 'aboutus');
		}

		if(count($segments) == 3 and ($segments[1] == 'MobileSiteStatic') and ($segments[2] == 'contactUs'))
		{
			return array('shikshaHelp','ShikshaHelp', 'contactUs');
		}
		
		if(count($segments) == 3 and ($segments[2] == 'privacy') and ($segments[1] == 'MobileSiteStatic'))
		{
			return array('shikshaHelp','ShikshaHelp', 'privacyPolicy');
		}

		if(count($segments) == 3 and ($segments[2] == 'students') and ($segments[1] == 'MobileSiteStatic'))
		{
			return array('shiksha','index');
		}

		if(count($segments) == 3 and ($segments[2] == 'terms') and ($segments[1] == 'MobileSiteStatic'))
		{
			return array('shikshaHelp','ShikshaHelp', 'termCondition');
		}
		
	    return FALSE;
	}

	function _validate_mobile_request($segments)
	{
		//echo "<pre>";print_r($_COOKIE); echo "</pre>"; echo "<pre>";print_r($segments);echo"</pre>";exit;

		if ($_COOKIE['user_force_cookie'] != 'YES')
		{
			if(($segments[0]) == 'shiksha' and ($segments[1] == 'index'))
			{	
				//In case it has JS support, redirect it to HTML5 site
				if($_COOKIE['ci_mobile_js_support'] == 'yes' || $GLOBALS['flag_mobile_js_support_user_agent'] == 'yes'){
					return array('mcommon5', 'MobileSiteHome', 'renderHomePage', 'india');
				}
				else{
					//Instead of Mobile HTML4 Homepage, load the Desktop homepage
					//return array('mcommon', 'MobileSiteHome', 'renderHomePage', 'india');
					return array('shiksha','index');
				}
			}
		}
		else
		{
			if ($_COOKIE['user_force_cookie']  == 'YES')
			{
				//echo "<pre>";print_r($_COOKIE); echo "</pre>"; echo "<pre>";print_r($segments);echo"</pre>";exit;
				// when user click on main site header link thn set cookie and redirect user to home page
				
				$result_array = $this->_validate_reverse_request($segments);
				return $result_array;
			}
		}
		return FALSE;
	}
	
	function _validate_request($segments)
	{
		//print_r($_COOKIE);echo "<pre>";print_r($segments);echo"</pre>";exit;
		
		if(($_COOKIE['ci_mobile'] != 'mobile') and ($_COOKIE['user_force_cookie'] != 'YES') and ($GLOBALS['flag_mobile_user_agent'] != 'mobile'))
		{
			$reverse_request = $this->_validate_reverse_request($segments);
			if($reverse_request !== FALSE)
			{
				$segments  = $reverse_request;
			}
		}
		else
		{
			$reverse_request = $this->_validate_mobile_request($segments);
			if($reverse_request !== FALSE)
			{
				$segments  = $reverse_request;
			}
		}

		if (count($segments) == 0) return $segments;		
		/* locate module controller */
		if ($located = $this->locate($segments)) { return $located; }
		
		/* use a default 404_override controller */
		if (isset($this->routes['404_override']) AND $this->routes['404_override']) {
			$segments = explode('/', $this->routes['404_override']);
			if ($located = $this->locate($segments)) return $located;
		}
		/* no controller found */
		show_404();
	}
}
