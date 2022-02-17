<?php

class MY_Model extends CI_Model
{
    protected $_db;
    protected $dbLibObj;    

    function __construct($module='default')
    {
        parent::__construct();
	$this->load->database();
        $this->_db = $this->db;

        $this->load->library('dbLibCommon');
        $this->dbLibObj = DbLibCommon::getInstance($module);

    }
    
    public function setDB($db)
	{
		$this->_db = $db;
	}
	
	protected function getColumnArray($resultArray,$columnName)
	{
		$columnArray = array();
		foreach($resultArray as $result) {
			$columnArray[] = $result[$columnName];
		}
		return $columnArray;
	}

	protected function getReadHandle()
	{
		return $this->dbLibObj->getReadHandle();
	}

	protected function getWriteHandle()
	{
		return $this->dbLibObj->getWriteHandle();
	}
	
	protected function getReadHandleByModule($module)
	{
		$dbLibObj = DbLibCommon::getInstance($module);
		return $dbLibObj->getReadHandle();
	}

	protected function getWriteHandleByModule($module)
	{
		$dbLibObj = DbLibCommon::getInstance($module);
		return $dbLibObj->getWriteHandle();
	}
}
