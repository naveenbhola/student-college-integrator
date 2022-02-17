<?php 
class BluespiderScriptsModel extends MY_Model {
    function __construct() {
		parent::__construct('Listing');
		$this->dbHandle = $this->getReadHandle();
		$this->dbWriteHandle = $this->getWriteHandle();
    }

    public function getBucketsByListings($listingIds=array(),$listingType=array()){
		if(empty($listingIds) || empty($listingType)){
			return array();
		}
		$listingIds = array_filter($listingIds);
		
		$this->dbHandle->select('id,criteriaId');
		$this->dbHandle->from('bluespider.buckets');
		$this->dbHandle->where('criteriaId in ('.implode(",", $listingIds).')');
		$this->dbHandle->where("listingType in ('".implode("','", $listingType)."')");
		$this->dbHandle->where("status","live");
		
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		
		$content = array();
		foreach ($result as $value) {
			$content[$value['criteriaId']] = $value['id'];
		}
		return $content;
	}

	public function insertBucketMapping($bucketMapping=array()){
		if(empty($bucketMapping)){
			return array();
		}
		
		$flag=$this->dbWriteHandle->insert_batch('bluespider.bucketMapping',$bucketMapping);
		return $flag;
	}
    
} ?>

