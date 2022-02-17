<?php

class Cimobile
{
	private $CI		= null;
	private $browser_type	= null;
	
	/**
	 * Constructor
	 *
	 * Constructor to load the deafult setting, prepare the class to work etc.
	 *
	 * @access	public
	 * @param	mixed $params  browser_type = AUTO/reject_mobile/force_mobile
	 */
	function __construct( $params )
	{
		if( function_exists('get_instance') )
		{
			// Load original CI object to global CI variable
			$this->CI =& get_instance();

			// Loading ci cookie helper to cache user preferences
			$this->CI->load->helper('cookie');

			// Loading mobile configuration
			$this->CI->config->load('mobi_config');
		}
		else
		{
			// Problems...
			$this->CI = null;
			show_error('get_instance function doesnt exist!');
		}


		// Holding the value of the URI requested not the URL though.
		$_SERVER['REQUEST_URI'] = ( isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI']
		:$_SERVER['SCRIPT_NAME'] . (( isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')));


		// Force or not the use of the mobile webiste
		if( $params['browser_type'] != 'AUTO' )
		{
			$domain		= preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/","$1", base_url());
			$domain 	= "." . preg_replace("/^www\./", "", $domain);
			$path		= '/';

			switch( $params['browser_type'] )
			{
				case 'reject_mobile':
					$cookie = array
					(
						'name'   => 'browsertype',
						'value'  => 'reject_mobile',
						'expire' => time() + 300000,
						'domain' => $domain,
						'path'   => $path,
						'prefix' => 'cimobile_',
					);

					set_cookie( $cookie );
					$this->browser_type = $params['browser_type'];
					break;

				case 'force_mobile':
					$cookie = array
					(
						'name'   => 'browsertype',
						'value'  => 'force_mobile',
						'expire' => time() + 300000,
						'domain' => $domain,
						'path'   => $path,
						'prefix' => 'cimobile_',
					);

					set_cookie( $cookie );
					$this->browser_type = $params['browser_type'];
					break;
			}
		}
		else
		{
			// check if cimobile cookie is available
			if( $value = get_cookie('cimobile_browsertype', true))
			$this->browser_type = $value;
		}

	}
	
	/**
	 * Check Mobile
	 *
	 * Function to check if the browser is mobile or not.
	 *
	 * @access	public
	 * @return boolean
	 */

	public function check_mobile()
	{
		// force the use the desktop browser
		if( ! isset($_SERVER["HTTP_USER_AGENT"]) || $this->browser_type == 'reject_mobile' ) return false;

		// check any pages to be excluded from the mobile version
		// needs some improvements for ci
		if( $this->mobile_exclude()) return false;

		// force the use of the mobile desktop
		if( $this->browser_type == 'force_mobile' ) return true;


		/*
		 	List of mobile devices to be excluded like TV or IPAD etc.
		 */
		$whitelist = array
		(
			'iPad'
		);

		foreach( $whitelist as $browser )
		{
			if( strstr($_SERVER["HTTP_USER_AGENT"], $browser) ) return false;
		}
		
		// Getting from config the list of mobile devices to be recognized
		$small_browsers = $this->CI->config->item('small_browsers');

		foreach( $small_browsers as $browser )
		{
			if( strstr($_SERVER["HTTP_USER_AGENT"], $browser) )
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Mobile Exclude
	 *
	 * Check any pages to be excluded from the mobile version
	 * needs some improvements for ci use.
	 *
	 * @access	private
	 * @return	boolean
	 */
	private function mobile_exclude()
	{
		$exclude = false;

		$pages_to_exclude = array
		(
			'admin','crm','sums'
		);


		foreach( $pages_to_exclude as $exclude_page )
		{
			if( strstr( strtolower($_SERVER['REQUEST_URI']), $exclude_page) )
			{
				$exclude = true;
			}
		}
		return $exclude;
	}

	/**
	 * Is Valid IMEI
	 *
	 * Function to check if the IMEI number is valid or not.
	 * The main algorithm was taken from:
	 * http://forum.gsmhosting.com/vbb/archive/index.php/t-224211.html
	 * Contribuição do Website DesbloquearMeuCelular.Com.Br
	 *
	 * @access	public
	 * @param	$cod_imei	The IMEI code if came with other characters thers no problem.
	 *
	 * @return	boolean
	 */
	
	public function is_valid_imei( $cod_imei )
	{
		$error		=   false;
		$cod_imei 	=   preg_replace("/[^0-9]/", "", $cod_imei);
		$error		= ! ctype_digit( $cod_imei )		? true : false;
		$error 		=   strlen($cod_imei) != 15			? true : false;
		$error		=   $cod_imei == '000000000000000'	? true : false;
		$cs 		=   0;

		if( ! $error )
		{
			for( $i = 0; $i < 14; $i += 2 )
			{
				$dodd = $cod_imei[ $i + 1 ] << 1;
				$cs  += $cod_imei[ $i ] + (int)( $dodd / 10 ) + ( $dodd % 10 );
			}

			$cs = ( 10 - ( $cs % 10 ) ) % 10;

			return $cs == $cod_imei[ 14 ] ? true : false;
		}
		else return false;
	}

}



# END Cimobile class