<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class BannerModel extends ListingModelAbstract
{
    function __construct()
    {
        parent::__construct();
		$this->db = $this->getWriteHandle();
    }
	
	/*
	 * Update status to 'deleted' of all expired banners
	 */ 
	public function unpublishExpiredBanners()
	{
		$today = date('Y-m-d 00:00:00');
		
		$sql =  "UPDATE tbannerlinks ".
				"SET status = 'deleted' ".
				"WHERE enddate < ? ".
				"AND status = 'live' ".
				"AND countryid = 2 ";
		
		$this->db->query($sql, array($today));
	}
}