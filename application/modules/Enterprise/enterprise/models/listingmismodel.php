<?php
/**
 * This class returns the required data to the server
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
class listingmismodel extends MY_Model
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
	
	
	public function getUpdatedListings($from,$to) {
		
		if($from>$to) {
			error_log("This Moron wants black day moon.");
			return;
		}
		$sql = "select listing_type,listing_type_id from listings_main where last_modify_date >= ? and last_modify_date <= ? and status ='live'";
		error_log("RHODES".$sql);
		$dbHandle = $this->_getDbHandle('read');
		$query = $dbHandle->query($sql, array($from, $to));
		
		return $query->result_array();
	}
}
