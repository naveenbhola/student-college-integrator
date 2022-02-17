<?php

require 'ShikshaSchemaAbstract.php';

class ShikshaSchema extends ShikshaSchemaAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
    function index()
    {
		$this->load->view('shikshaSchema/mainpage');
    }	

    function module($container, $module)
    {
	$data = array('container' => $container, 'module' => $module);
	$app_modules = array('ana_api','user_api','tags_api','common_api','search_api','universal_api');
	if(in_array($module,$app_modules)){
		$this->load->view('shikshaSchema/app/main',$data);
	}
	else if($container == 'projects' && $module == 'shiksha2'){
		$this->load->view('shikshaSchema/projects/shiksha2',$data);
	}
	else{
		$this->load->view('shikshaSchema/module',$data);
	}
    }

    function sequence($id,$container,$module)
    {
	 $data = array('container' => $container, 'module' => $module);
	$this->load->view('shikshaSchema/'.$container.'/sequence',$data);

    }
	
	function usecase($id,$container,$module)
    {
	 $data = array('container' => $container, 'module' => $module);
	$this->load->view('shikshaSchema/'.$container.'/usecase',$data);

    }

    function db($tableGroup,$container,$module)
    {
        $tables = $this->_getTables();
        
        $this->load->config('shikshaSchema/tableMetaData');
        $tableMetaData = $this->config->item('tableMetaData');
        
        $data = array();
        $data['tables'] = $tables;
        $data['tableMeta'] = $tableMetaData;
        
        if(!$tableGroup) {
            $tableGroup = 'user';
        }
	
        $data['tableGroup'] = $tableGroup;
	$data['container'] = $container;
	$data['module'] = $module;
        //echo $tableGroup;exit;
        $this->load->view('shikshaSchema/'.$tableGroup,$data);
    }
    
    function table($tableName)
    {
        $tables = $this->_getTables();
        
        $this->load->config('shikshaSchema/tableMetaData');
        $tableMetaData = $this->config->item('tableMetaData');
        
        $data = array();
        $data['tables'] = $tables;
        $data['tableMeta'] = $tableMetaData;
        $data['smaller'] = TRUE;
        $data['tableName'] = $tableName;
        
        $this->load->view('shikshaSchema/printTable',$data);
    }
    
    private function _getTables()
    {
        $this->dbLibObj = DBLibCommon::getInstance('User');
        $dbHandle = $this->_loadDatabaseHandle();

        $tables = array();
        $query = $dbHandle->query("SELECT * FROM  `information_schema`.`COLUMNS` WHERE  `TABLE_SCHEMA` LIKE  'shiksha'");
        foreach($query->result_array() as $result) {
            $tables[$result['TABLE_NAME']][$result['COLUMN_NAME']] = array('type' => $result['COLUMN_TYPE'],'key' => $result['COLUMN_KEY'],'extra' => $result['EXTRA']);
        }
        return $tables;
    }
	
	function Login()
	{
		$this->load->view('shikshaSchema/login');
	}
	
	function doLogin()
	{
		$this->loginUser();
	}
}
