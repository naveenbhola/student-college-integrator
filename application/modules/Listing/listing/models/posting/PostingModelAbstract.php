<?php

abstract class PostingModelAbstract extends MY_Model
{
    protected $dbHandle = null;
    
    function __construct()
    {
        parent::__construct('Listing');
    }
    
    protected function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}
    
	protected function indexListing($listingType,$listingId,$operation)
	{
		$sql = "SELECT id FROM indexlog WHERE listing_type = ? AND listing_id = ? AND operation = ? AND status = 'pending'";
		$query = $this->dbHandle->query($sql,array($listingType,$listingId,$operation));
		$numRows = $query->num_rows();
		if($numRows <= 0) {
			$data = array(
				'listing_type' => $listingType,
				'listing_id' => $listingId,
				'operation' => $operation,
				'status' => 'pending'
			);
			$this->dbHandle->insert('indexlog',$data);
		}
	}
	
	protected function updateLastModifiedDate($listingId,$listingType)
	{
		$sql =  "UPDATE listings_main ".
				"SET last_modify_date = '".date('Y-m-d H:i:s')."' ".
				"WHERE listing_type = ? ".
				"AND listing_type_id = ? ".
				"AND status IN ('draft','live')";
				
		$query = $this->dbHandle->query($sql,array($listingType,$listingId));	
	}
}