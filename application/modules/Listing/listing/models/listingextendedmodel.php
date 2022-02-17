<?php
/* */
class ListingExtendedModel extends MY_Model {
   
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
	 * @param $from date
	 * @param $to date
	 * @return array of listing_Type and listing_type_id.
	 */
	public function getUpdatedListings($from,$to) {
	if($from>$to) {
			error_log("This Moron wants black day moon.");
			return;
		}
		$sql = "select listing_type,listing_type_id from listings_main where last_modify_date >= ? and last_modify_date <= ? and status ='live'";
		$dbHandle = $this->_getDbHandle('read');
		$query = $dbHandle->query($sql,array($from,$to.'24: 00: 00'));
		
		return $query->result_array();
	}
	
	
	public function getListingEBrochureInfo($listing_type, $listing_type_id) {
		if($listing_type == "" || $listing_type_id == "") {
			error_log("LISTINGS_EBROCHURE_ISSUE: Empty params in getListingEBrochureInfo!");
			return ;
		}
		
		$dbHandle = $this->_getDbHandle();		
		$sql = "select * from `listings_ebrochures` WHERE `listingType` = ? AND `listingTypeId` = ? AND `status` = 'live'";		
		$rs = $dbHandle->query($sql,array($listing_type,$listing_type_id));
		return $rs->result_array();
	}
	
	public function getMultipleListingsEBrochureInfo($listing_type_ids = array(),$listing_type) {
		if(count($listing_type_ids) == 0 || $listing_type == "") {
			error_log("LISTINGS_EBROCHURE_ISSUE: Empty params in getListingEBrochureInfo!");			
			return array();
		}

		$dbHandle = $this->_getDbHandle();
		
		$sql = "select * from `listings_ebrochures` WHERE `listingType` = ? AND `listingTypeId` IN (?) AND `status` = 'live'";
		$rs = $dbHandle->query($sql, array($listing_type, $listing_type_ids));
		$result_array = array();
		
		foreach ($rs->result_array() as $row) {
			$result_array[$row['listingTypeId']] = MEDIAHOSTURL.$row['ebrochureUrl'];			
		}
		
		return $result_array;
	}
	
	public function updateListingEBrochureInfo($listing_type, $listing_type_id, $pdf_file_path, $make_history_flag = FALSE) {
		
		if($listing_type == "" || $listing_type_id == "" || ($pdf_file_path == "" && !$make_history_flag)) {
			error_log("LISTINGS_EBROCHURE_ISSUE: Empty params in updateListingEBrochureInfo!");
			return ;
		}
		
		$dbHandle = $this->_getDbHandle('write');
		
		$updateSql = "UPDATE `listings_ebrochures` SET STATUS = 'history' WHERE `listingType` = ? AND `listingTypeId` = ? AND `status` = 'live'";
		$dbHandle->query($updateSql,array($listing_type,$listing_type_id));

		if(!$make_history_flag){	
			$data = array(
							'ebrochureUrl'	=> $pdf_file_path,
							'listingType'	=> $listing_type,
							'listingTypeId'	=> $listing_type_id,
							'createdAt'		=> date('Y-m-d H:i:s'),
							'status'		=> 'live',
						 );

			$dbHandle->insert('listings_ebrochures', $data);
		}
		
	}

}
?>
