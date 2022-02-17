<?php

class Searchagentsconfig {
	
	var $CI_Instance;

	var $dbLib;

	function init()
	{
		/* CI SUPER Global Var */
		$this->CI_Instance = & get_instance();
		$this->CI_Instance->load->library('dbLib');
		$this->dbLib = new dbLib();
	}

	public function getDbConfig($appID,&$config)
	{	
		$this->init();
		$config['hostname'] = $this->dbLib->getServerIP($appID);
		$config['username'] = $this->dbLib->getUserName($appID);
		$config['password'] = $this->dbLib->getUserPassword($appID);
		$config['database'] = "shiksha";
		$config['dbdriver'] = "mysqli";
		$config['dbprefix'] = "";
		$config['pconnect'] = TRUE;
		$config['db_debug'] = TRUE;
		$config['active_r'] = TRUE;
	}
}
/* End of file searchAgentsConfig.php */ /* Location: ./system/system/application/libraries/searchAgentsConfig.php */