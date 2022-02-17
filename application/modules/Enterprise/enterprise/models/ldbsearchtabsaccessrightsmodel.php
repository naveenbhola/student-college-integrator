<?php
/**
 * This class returns the required data to the server
 *
 * @author    
 * @version
 */
class ldbsearchtabsaccessrightsmodel extends MY_Model
{
        /**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct(){
		parent::__construct('Listing');
	}
	/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */
	private function _getDbHandle($operation='read'){
		//connect DB
		$appId = 1;
		$this->load->library('listingconfig');
		if($operation=='read'){
			$dbHandle = $this->getReadHandle();
		}
		else{
			$dbHandle = $this->getWriteHandle();
		}
		if($dbHandle == ''){
			error_log('error can not create db handle');
		}
		return $dbHandle;
	}
        
	/**
	 * this method sets the Clients LDB Search Access Tabs
	 * 
	 * @param array
	 */
	
	public function setClientSpecificCategories($clientId,$clientSpecificCategories){
		
		$dbHandle = $this->_getDbHandle('write');		
		foreach($clientSpecificCategories as $category){
			 
			$data =array();
			 list($categoryName,$categoryId) = explode('_',$category);
			 $data = array(						  
						  'ClientId'=>$clientId,
						  'LDBTabsAccess'=>$categoryName,
						  'categoryId' => $categoryId,
						  'status'=>'ACTIVE'
					);
			 
			 $insertQuery = $dbHandle->insert_string('Clients_LDBSearch_Tabs_Access',$data);
			 $dbHandle->query($insertQuery);
		}
		
		$response = "Successfully Submitted";
		return $response;
		
	}
	
	/**
	 * this method takes an array of institues ids and returns information
	 * 
	 * @param array
	 * @return array
	 */
	public function getClientLDBSearchAccessTabs($clientId) {
		$sql = "select LDBTabsAccess,categoryId from  Clients_LDBSearch_Tabs_Access where ClientId = ? and status = 'ACTIVE'";
		error_log('aquery'.$sql);
		$dbHandle = $this->_getDbHandle('read');
		$resultSet = $dbHandle->query($sql,array($clientId));
		$response = array();
		if($resultSet->num_rows > 0){
			foreach($resultSet->result() as $row){
				$response[$row->categoryId][$row->LDBTabsAccess] = TRUE;
			}		
			return $response;
		}else{
			return $response;	
		}
	}
	
	/**
	 * this method updates the already set Clients LDB Search Access Tabs
	 * 
	 * @param array
	 */
	
	public function updateAlreadySetClientSpecificCategories($clientId){
		$dbHandle = $this->_getDbHandle('write');

		$sql = "update Clients_LDBSearch_Tabs_Access set status = 'INACTIVE' where ClientId = ?";
		$query = $dbHandle->query($sql,array($clientId));

		return true;			
	}
}
