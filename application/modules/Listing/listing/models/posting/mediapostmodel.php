<?php

require_once dirname(__FILE__).'/PostingModelAbstract.php';

class MediaPostModel extends PostingModelAbstract
{
    function __construct()
	{
		parent::__construct();
    }
	
	public function addMediaToListing($listingId,$listingType,$mediaData)
	{
		$this->initiateModel('write');
		
		$data = array(
			'listing_type' => $listingType,
			'listing_type_id' => $listingId,
			'institute_location_id' => intval($mediaData['institute_location_id']),
			'media_id' => $mediaData['mediaId'],
			'media_type' => $mediaData['mediaType'],
			'name' => $mediaData['mediaName'],
			'url' => $mediaData['mediaUrl'],
			'thumburl' => $mediaData['mediaThumbUrl'],
			'uploadeddate' => date('Y-m-d'),
			'status' => 'notlinked'
		);
		
		$this->dbHandle->insert('institute_uploaded_media',$data);
	}
	
	public function updateListingMediaAttributes($listingType, $listingId, $fileType, $fileId, $fieldName, $fieldValue)
	{
		$this->initiateModel('write');
		
		$toUpdate = array(
			$fieldName => $fieldValue
		);
		
		$updateWhere = array(
			'media_id' => $fileId,
			'media_type' => $fileType,
			'listing_type' => $listingType,
			'listing_type_id' => $listingId
		);
		
		$this->dbHandle->update('institute_uploaded_media',$toUpdate,$updateWhere);
	}
	
	public function removeMediaForListing($listingType, $listingId, $fileType, $fileId)
	{
		$this->initiateModel('write');
		
		$toUpdate = array(
			'status' => 'deleted'
		);
		
		$updateWhere = array(
			'media_id' => $fileId,
			'media_type' => $fileType,
			'listing_type' => $listingType,
			'listing_type_id' => $listingId
		);
		
		$this->dbHandle->update('institute_uploaded_media',$toUpdate,$updateWhere);
	}
	
	public function mapHeaderImages($thumbURL,$largeURL,$listingType,$listingId,$order,$instituteId,$name)
	{
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		/**
		 * Make previous draft entries history
		 */
		$sql = "UPDATE header_image SET status = 'history' WHERE listing_type = ? AND listing_id = ? AND status = ?";
		$this->dbHandle->query($sql,array($listingType[0],$listingId[0],'draft'));
		
		/**
		 * Make previous entries version 0
		 */
		$sql = "UPDATE header_image SET version = 0 WHERE listing_type = ? AND listing_id = ?";
		$this->dbHandle->query($sql,array($listingType[0],$listingId[0]));
		
		/**
		 * Insert new header images in draft state
		 */ 
		$numHeaderImages = count($thumbURL);
		for($i=0;$i<$numHeaderImages;$i++) {
			
			$data = array(
				'name' => $name[$i],
				'full_url' => $largeURL[$i],
				'thumb_url' => $thumbURL[$i],
				'header_order' => $order[$i],
				'status' => 'draft',
				'listing_id' => $listingId[$i],
				'listing_type' => $listingType[$i],
				'institute_id' => $instituteId[$i],
				'version' => 1,
				'linked' => 'yes'
			);
			
			$this->dbHandle->insert('header_image',$data);
		}
		
		$this->dbHandle->trans_complete();
	}
	
	public function removeHeaderImages($listingType,$listingId)
	{
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		/**
		 * Get current header images
		 */
		$sql = "SELECT * FROM header_image WHERE listing_type = ? AND listing_id = ? AND version = 1"; 
		$query = $this->dbHandle->query($sql,array($listingType,$listingId));
		$headerImages = $query->result_array();
				
		/**
		 * Make previous draft entries history
		 */
		$sql = "UPDATE header_image SET status = 'history' WHERE listing_type = ? AND listing_id = ? AND status = ?";
		$this->dbHandle->query($sql,array($listingType,$listingId,'draft'));
		
		/**
		 * Make previous entries version 0
		 */
		$sql = "UPDATE header_image SET version = 0 WHERE listing_type = ? AND listing_id = ?";
		$this->dbHandle->query($sql,array($listingType,$listingId));
		
		/**
		 * Replicate header images in draft state
		 */ 
		if(count($headerImages) > 0) {
			foreach($headerImages as $headerImage) {
				
				$data = array(
					'name' => $headerImage['name'],
					'full_url' => $headerImage['full_url'],
					'thumb_url' => $headerImage['thumb_url'],
					'header_order' => $headerImage['header_order'],
					'status' => 'draft',
					'listing_id' => $headerImage['listing_id'],
					'listing_type' => $headerImage['listing_type'],
					'institute_id' => $headerImage['institute_id'],
					'version' => 1,
					'linked' => 'no'
				);
				
				$this->dbHandle->insert('header_image',$data);	
			}
		}
		
		$this->dbHandle->trans_complete();
	}
	
	public function mapTopRecruitingCompanies($companyId,$listingType,$listingId,$order,$instituteId)
	{
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		/**
		 * Make previous draft entries history
		 */
		$sql = "UPDATE company_logo_mapping SET status = 'history' WHERE institute_id = ? AND status = ?";
		$this->dbHandle->query($sql,array($instituteId[0],'draft'));
		
		/**
		 * Make previous entries version 0
		 */
		$sql = "UPDATE company_logo_mapping SET version = 0 WHERE institute_id = ?";
		$this->dbHandle->query($sql,array($instituteId[0]));
		
		/**
		 * Insert new header images in draft state
		 */ 
		$numCompanies = count($companyId);
		for($i=0;$i<$numCompanies;$i++) {
			
			$data = array(
				'logo_id' => $companyId[$i],
				'company_order' => $order[$i],
				'status' => 'draft',
				'listing_id' => $listingId[$i],
				'listing_type' => $listingType[$i],
				'institute_id' => $instituteId[$i],
				'version' => 1,
				'linked' => 'yes'
			);
			
			$this->dbHandle->insert('company_logo_mapping',$data);
		}
		
		$this->dbHandle->trans_complete();
	}
	
	public function removeTopRecruitingCompanies($instituteId)
	{
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		/**
		 * Get current header images in draft state
		 */
		$sql = "SELECT * FROM company_logo_mapping WHERE institute_id = ? AND version = 1"; 
		$query = $this->dbHandle->query($sql,array($instituteId));
		$recruitingCompanies = $query->result_array();
		
		/**
		 * Make previous draft entries history
		 */
		$sql = "UPDATE company_logo_mapping SET status = 'history' WHERE institute_id = ? AND status = ?";
		$this->dbHandle->query($sql,array($instituteId,'draft'));
		
		/**
		 * Make previous entries version 0
		 */
		$sql = "UPDATE company_logo_mapping SET version = 0 WHERE institute_id = ?";
		$this->dbHandle->query($sql,array($instituteId));
		
		/**
		 * Replicate header images in draft state
		 */ 
		if(count($recruitingCompanies) > 0) {
			foreach($recruitingCompanies as $recruitingCompany) {
				
				$data = array(
					'logo_id' => $recruitingCompany['logo_id'],
					'company_order' => $recruitingCompany['company_order'],
					'status' => 'draft',
					'listing_id' => $recruitingCompany['listing_id'],
					'listing_type' => $recruitingCompany['listing_type'],
					'institute_id' => $recruitingCompany['institute_id'],
					'version' => 1,
					'linked' => 'no'
				);
				
				$this->dbHandle->insert('company_logo_mapping',$data);	
			}
		}
		
		$this->dbHandle->trans_complete();
	}
	
	public function saveMedia($data)
	{
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		/**
		 * Do media association
		 */
		$mediaAssociationData = $data['mediaAssociationData'];
		
		foreach($mediaAssociationData as $listingType => $listingTypeAssociations) {
			foreach($listingTypeAssociations as $listingTypeId => $listingIdAssociations) {
				
				/**
				 * Get current media association for this listing
				 */
				$sql = "SELECT * FROM listing_media_table WHERE type = ? AND type_id = ? AND version = 1"; 
				$query = $this->dbHandle->query($sql,array($listingType,$listingTypeId));
				$currentMediaAssociations = $query->result_array();
				
				$currentMediaAssociationData = array();
				foreach($currentMediaAssociations as $association) {
					$currentMediaAssociationData[$association['media_type']][$association['media_id']] = 'addition';
				}
				
				foreach($listingIdAssociations as $mediaType => $mediaData) {
					foreach($mediaData as $mediaId => $action) {
						if($action == 'removal'){
							unset($currentMediaAssociationData[$mediaType][$mediaId]);
						}
						else if($action == 'addition'){
							$currentMediaAssociationData[$mediaType][$mediaId] ='addition';
						}
					}
				}
				
				/**
				 * Make previous draft entries history
				 */
				$sql = "UPDATE listing_media_table SET status = 'history' WHERE type = ? AND type_id = ? AND status = ?";
				$this->dbHandle->query($sql,array($listingType,$listingTypeId,'draft'));
				
				/**
				 * Make previous entries version 0
				 */
				$sql = "UPDATE listing_media_table SET version = 0 WHERE type = ? AND type_id = ?";
				$this->dbHandle->query($sql,array($listingType,$listingTypeId));
				
				foreach($currentMediaAssociationData as $mediaType => $mediaData) {
					foreach($mediaData as $mediaId => $action) {
						$dbData = array(
										'type' => $listingType,
										'type_id' => $listingTypeId,
										'media_type' => $mediaType,
										'media_id' => $mediaId,
										'version' => 1,
										'status' => 'draft'
									);
						$this->dbHandle->insert('listing_media_table',$dbData);
					}
				}
			}
		}
		
		/**
		 * Save edit comments
		 */
		
		$commentData = $data['commentData'];
		$dbData = array(
						'userId' => $commentData['cmsTrackUserId'],
						'listingId' => $commentData['cmsTrackListingId'],
						'tabUpdated' => $commentData['cmsTrackTabUpdated'],
						'comments' => $commentData['mandatory_comments']
				);
		$this->dbHandle->insert('listingCMSUserTracking',$dbData);
		
		$this->dbHandle->trans_complete();
		
		return TRUE;
	}
}
