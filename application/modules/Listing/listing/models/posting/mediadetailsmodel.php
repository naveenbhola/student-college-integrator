<?php

class MediaDetailsModel extends MY_Model
{
	private $dbHandle = null;

    function __construct()
	{
		parent::__construct('Listing');
    }

	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}
	
    function getMediaDetails($instituteId)
	{
		$this->initiateModel('write');
		
		$sql = "SELECT *, a.media_type, a.media_id
				FROM institute_uploaded_media a
				LEFT JOIN listing_media_table b ON a.media_id = b.media_id AND a.media_type = b.media_type 
				WHERE (a.status is NULL OR a.status != 'deleted')
				AND b.version = 1
				AND a.listing_type = 'institute'
				AND a.listing_type_id = ?
				ORDER BY a.media_type, a.media_id";
		
		$query = $this->dbHandle->query($sql,array($instituteId));
		
		$mediaData = array();
		
		foreach ($query->result_array() as $result) {
			
			$mediaType = $result['media_type'];
			$mediaId = $result['media_id'];
			$mediaName = $result['name'];
			$mediaUrl = $result['url'];
			$mediaThumbUrl = $result['thumburl'];
			$mediaUploadDate = $result['uploaddate'];
			$mediaAssociationDate = $result['associationdate'];
			$type = $result['type'];
			$typeId = $result['type_id'];
			
			switch($mediaType) {
				case 'doc' : $mediaType = 'documents'; break;
				case 'photo' : $mediaType = 'photos'; break;
				case 'video' : $mediaType = 'videos'; break;
			}

			$mediaData[$mediaType][$mediaId]['mediaCaption'] = $mediaName;
			$mediaData[$mediaType][$mediaId]['mediaUrl'] = $mediaUrl;
			$mediaData[$mediaType][$mediaId]['mediaThumbUrl'] = $mediaThumbUrl;
			$mediaData[$mediaType][$mediaId]['mediaUploadDate'] = $mediaUploadDate;
			$mediaData[$mediaType][$mediaId]['mediaAssociationDate'] = $mediaAssociationDate;
			$mediaData[$mediaType][$mediaId]['mediaAssociation'][] = array($type => $typeId);
		}
		
		return $mediaData;
	}
	
	public function getRecruitingCompanies($instituteId)
	{
		$this->initiateModel('write');
		
		$sql = "SELECT logo_id,company_name,logo_url,company_order, group_concat(listing_id) as listing_ids,
					   group_concat(listing_type) as listing_types
				FROM company_logo_mapping a
				INNER JOIN company_logos ON a.logo_id = id
				WHERE institute_id = ?
				AND a.linked = 'yes'
				AND a.version = 1
				GROUP BY logo_id
				ORDER BY company_order";
				
		$query = $this->dbHandle->query($sql,array($instituteId));
		
		$recruitingCompanies = $query->result_array();
		return $recruitingCompanies;
	}
	
	
	public function getHeaderImages($instituteId)
	{
		$this->initiateModel('write');
		
		$sql = "SELECT name, header_order, full_url, thumb_url, listing_id as listing_ids, listing_type as listing_types
				FROM header_image
				WHERE institute_id = ?
				AND linked = 'yes'
				AND version = 1
				ORDER BY header_order";
				
		$query = $this->dbHandle->query($sql,array($instituteId));

		$headerImages = array();
		
		foreach($query->result_array() as $result) {
			$headerImages[] = array(
				'name' => $result['name'],
				'large_url' => $result['full_url'],
				'thumb_url' => $result['thumb_url'],
				'header_order' => $result['header_order'],
				'listing_ids' => $result['listing_ids'],
				'listing_types' => $result['listing_types'],
			);
		}
		
		return $headerImages;
	}
}
