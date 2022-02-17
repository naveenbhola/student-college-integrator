	<?php
		/**
		 * MIT License
		 * ===========
		 *
	 * Copyright (c) 2012 [Your name] <[Your email]>
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining
	 * a copy of this software and associated documentation files (the
	 * "Software"), to deal in the Software without restriction, including
	 * without limitation the rights to use, copy, modify, merge, publish,
	 * distribute, sublicense, and/or sell copies of the Software, and to
	 * permit persons to whom the Software is furnished to do so, subject to
	 * the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included
	 * in all copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
	 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
	 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
	 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
	 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
	 *
	 * @category   [ Category ]
	 * @package    [ Package ]
	 * @subpackage [ Subpackage ]
	 * @author     [Your name] <[Your email]>
	 * @copyright  2012 [Your name].
	 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
	 * @version    [ Version ]
	 * @link       http://[ Your website ]
	 */


	// ==================================================================
	//
	// [ Section description goes here ... ]
	//
	// ------------------------------------------------------------------

	class messageBoardProxy
	{
		public $api;

		private $ci_mobile_capbilities = array();

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

		public $getDeviceObj = true;

		public function __construct($wurfl)
		{
			$this->api = & get_instance();
			$this->ci_mobile_capbilities = $wurfl;
		}
		/**
		 * [Description]
		 *
		 * @param [type] $new[ Prop name ] [description]
		 */
			public function getDeviceObj()
	{
		if(($this->get_is_js_support() == 'true') && ($this->get_device_screen_height() >= 240) && ($this->get_device_screen_width() >= 320))
		{
			return $this->getDeviceObj = true;
		}
		else
		{
			return $this->getDeviceObj = false;
		}
	} 

	private function get_is_js_support()
	{
		return $this->is_js_support = $this->ci_mobile_capbilities['ajax_support_javascript'];   
	}


	private function get_device_screen_width()
	{
		return $this->device_screen_width = $this->ci_mobile_capbilities['resolution_width'];  
	}
	/**
	 * [Description]
	 *
	 * @param [type] $new[ Prop name ] [description]
	 */
	private function get_device_screen_height()
	{
		return $this->device_screen_height = $this->ci_mobile_capbilities['resolution_height'];
	}
	/**
	 * [Description]
	 *
	 * @param [type] $new[ Prop name ] [description]
	 */
	private function get_is_nokia()
	{
		/*if( ($this->ci_mobile_capbilities['nokia_series'] !="0") &&  ($this->ci_mobile_capbilities['nokia_edition'] !="0") || ( $this->ci_mobile_capbilities['device_os'] == 'Symbian OS'))
		{
			$this->is_nokia         = 'yes';
		}
		else
		{
			$this->is_nokia         = 'no';
		}*/
		$this->is_nokia         = 'no';
		return $this->is_nokia;  
	}


	private function get_model_name()
	{
		return $this->model_name       = $this->ci_mobile_capbilities['model_name'];
	}

	/*
	public function get_brand_name()
	{
		return $this->brand_name       = $this->ci_mobile_capbilities['brand_name'];   
	}
	*/
	
	private function get_marketing_name()
	{

		//return $this->marketing_name   =  $this->ci_mobile_capbilities['marketing_name'];
		return "";

	}


	private function get_device_os()
	{
		return $this->device_os        = $this->ci_mobile_capbilities['device_os'];

	}

	/*
	private function get_is_wireless()
	{
		return $this->is_wireless  = $this->ci_mobile_capbilities['is_wireless']; 
	}
	*/

	/*
	private function get_is_smarttv()
	{
		return $this->is_smarttv = $this->ci_mobile_capbilities['is_smarttv'];
		return "no";
	} 
	*/      

	/*
	private function get_is_tablet()
	{
		return $this->is_tablet = $this->ci_mobile_capbilities['is_tablet'];
	}
	*/

	/*
	private function get_is_phone()
	{
		return $this->is_phone = $this->ci_mobile_capbilities['is_phone'];   
	}
	*/

	/**
	 * [Description]
	 *
	 * @param [type] $new[ Prop name ] [description]
	 */
	public function renderView($module,$view,$payload)
	{
		if($this->getDeviceObj())
		{
			$this->api->load->view($module. "/smartphones/". $view,$payload);
		}
		else
		{
			$this->api->load->view($module. "/smartphones/". $view,$payload); 
		}
	}
	/**
	 * [Description]
	 *
	 * @param [type] $new[ Prop name ] [description]
	 */
	public function renderCommonView($module,$view,$payload)
	{
		$this->api->load->view($module. "/common/". $view,$payload);
	}

	/**
	 * [Description]
	 *
	 * @param [type] $new[ Prop name ] [description]
	 */
	public function reportStatus($errorObj,$msg)
	{
		$msg = $msg . $errorObj->getMessage() . $errorObj->getTraceAsString();
		$subject = "Error Occoured in MsgBoad API at mobileSite";
		sendMailAlert($msg,$subject,$recipients=array());
	}
}
