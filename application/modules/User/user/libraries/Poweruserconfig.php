<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
/**
 * File for power user config
 */

/**
 * class for power user config
 */
class Poweruserconfig{
	
	/**
	 * Variable for CodeIgnitor Instance
	 * @var object
	 */
	var $CI_Instance;
	
	/**
	 * Variable for Database Instance
	 * @var object
	 */
        var $dbLib;
	
	/**
	 * Init Function to load the library
	 */
	function init(){
        	$this->CI_Instance = & get_instance();
                $this->CI_Instance->load->library('dbLib');
                $this->dbLib = new dbLib();
    	}
	
	/**
	 * Function to get DB Config
	 *
	 * @param string $appID
	 * @param array $config
	 */
	public function getDbConfig($appID,&$config){ error_log("poweruserconfig".print_r($appID,true));
		$this->init();
		$config['hostname'] = $this->dbLib->getServerIP($appID);
		$config['username'] = $this->dbLib->getUserName($appID);
		$config['password'] = $this->dbLib->getUserPassword($appID);
		$config['database'] = $this->dbLib->getDbName($appID);
		$config['dbdriver'] = "mysqli";
		$config['dbprefix'] = "";
		$config['pconnect'] = TRUE;
		$config['db_debug'] = TRUE;
		$config['active_r'] = TRUE;
	}


}

?>
