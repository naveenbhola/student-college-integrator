<?php

require_once dirname(__FILE__).'/PostingModelAbstract.php';

class InstitutePostModel extends PostingModelAbstract
{
	private $subsciptionConsumer;
	
    function __construct($subsciptionConsumer)
	{
		parent::__construct();
		$this->subsciptionConsumer = $subsciptionConsumer;
    }

    function addInstitute($data)
	{
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		if($data['flow'] == 'edit') {
			
			$instituteId = $data['instituteId'];
			$savedInstituteData = $this->_getInstituteData($instituteId);
			$savedListingData = $this->_getListingData($instituteId);
			$savedJoinReason = $this->_getJoinReasonData($instituteId);
			
			/**
			 * Mark previous draft entries as history
			 */
			$this->dbHandle->query("UPDATE institute SET status = 'history' WHERE institute_id = ? AND status = 'draft'",array($instituteId));
			$this->dbHandle->query("UPDATE listings_main SET status = 'history' WHERE listing_type = 'institute' AND listing_type_id = ? AND status = 'draft'",array($instituteId));
			$this->dbHandle->query("UPDATE institute_location_table SET status = 'history' WHERE institute_id = ? AND status = 'draft'",array($instituteId));
			$this->dbHandle->query("UPDATE listing_contact_details SET status = 'history' WHERE listing_type = 'institute' AND listing_type_id = ? AND status = 'draft'",array($instituteId));
			$this->dbHandle->query("UPDATE listing_attributes_table SET status = 'history' WHERE listing_type = 'institute' AND listing_type_id = ? AND status = 'draft'",array($instituteId));
			$this->dbHandle->query("UPDATE institute_join_reason SET status = 'history' WHERE institute_id = ? AND status = 'draft'",array($instituteId));
			
			//mark previous draft as history after updating their timestamp
			$this->dbHandle->query("UPDATE institute_facilities SET `timestamp` = date('Y-m-d H:i:s'),status = 'history' where listing_type = 'institute' AND listing_type_id = ? AND status='draft'",array($instituteId));
			
			/**
			 * Mark all previous entries to version 0
			 */
			$this->dbHandle->query("UPDATE institute SET version = 0 WHERE institute_id = ?",array($instituteId));
			$this->dbHandle->query("UPDATE listings_main SET version = 0 WHERE listing_type = 'institute' AND listing_type_id = ?",array($instituteId));
			$this->dbHandle->query("UPDATE institute_location_table SET version = 0 WHERE institute_id = ?",array($instituteId));
			$this->dbHandle->query("UPDATE listing_contact_details SET version = 0 WHERE listing_type = 'institute' AND listing_type_id = ?",array($instituteId));
			$this->dbHandle->query("UPDATE listing_attributes_table SET version = 0 WHERE listing_type = 'institute' AND listing_type_id = ?",array($instituteId));
			$this->dbHandle->query("UPDATE institute_join_reason SET version = 0 WHERE institute_id = ?",array($instituteId));
			$this->dbHandle->query("UPDATE institute_facilities SET version = 0 WHERE listing_type = 'institute' AND listing_type_id = ?",array($instituteId));
		}
		else {
			$instituteId = $this->_getNewInstituteId();
		}
		
		$status = 'draft';
		$version = 1;
		
		$instituteBrochure = $data['institute_request_brochure_link'] ? $data['institute_request_brochure_link'] : $savedInstituteData['institute_request_brochure_link'];
		$brochureYear = $data['institute_request_brochure_link_year'];
		
		if($data['deleteBrochureFlag'] == 'delete') {
			$instituteBrochure = "";
			$brochureYear = 0;
		}
		
		$instituteData = array(
								'institute_type' => $data['instituteType'],
								'institute_id' => $instituteId,
								'institute_name' => $data['institute_name'],
								'abbreviation' => $data['abbreviation'],
								'usp' => $data['usp'],
								'aima_rating' => $data['aima_rating'],
								'logo_link' => $data['logo_link'] ? $data['logo_link'] : $savedInstituteData['logo_link'],
								'featured_panel_link'=> $data['featured_panel_link'] ? $data['featured_panel_link']: $savedInstituteData['featured_panel_link'],
								'establish_year' => $data['establish_year'],
								'certification' => $data['affiliated_to'],
								'contact_details_id' => $cid,
								'admission_counseling' => $data['admission_counseling'],
								'visa_assistance' => $data['visa_assistance'],
								'status' => $status,
								'version' => $version,
								'institute_request_brochure_link' => $instituteBrochure,
								'institute_request_brochure_year' => $brochureYear,
								'source_type' => $data['source_type'],
								'source_name' => $data['source_name'],
								'profile_percentage_completion' => $savedInstituteData['profile_percentage_completion']
							);
		$this->dbHandle->insert('institute',$instituteData);
		
		if($data['flow'] == 'add') {
			$instituteSeoUrl = $this->_getSEOURL($data,$instituteId);
			$instituteSubmitDate = date('Y-m-d H:i:s');
			$instituteViewCount  = 1;
			$no_Of_Past_Paid_Views = 0;
			$no_Of_Past_Free_Views = 0;
		}
		else {
			$instituteSeoUrl = $savedListingData['listing_seo_url'];
			$instituteSubmitDate = !empty($data['instituteSubmitDate']) ? $data['instituteSubmitDate'] : date('Y-m-d H:i:s');
			$instituteViewCount  = !empty($data["instituteViewCount"]) ? $data["instituteViewCount"] : 1 ;
			$no_Of_Past_Paid_Views = !empty($data["no_Of_Past_Paid_Views"]) ? $data["no_Of_Past_Paid_Views"] : 0 ;
			$no_Of_Past_Free_Views = !empty($data["no_Of_Past_Free_Views"]) ? $data["no_Of_Past_Free_Views"] : 0 ;
		}
		
		$packType = intval($data['packType']);
		if(!$packType) {
			$packType = intval($savedListingData['pack_type']);
		}
		
		$subscriptionId = intval($data['subscriptionId']);
		if(!$subscriptionId) {
			$subscriptionId = intval($savedListingData['subscriptionId']);
		}
		
		$approvedBy = $data['approvedBy'];
		if(!$approvedBy) {
			$approvedBy = $savedListingData['approvedBy'];
		}
		
		$listingsMainData = array(
										'listing_type_id' => $instituteId,
										'listing_title' => $data['institute_name'],
										'username' => $data['username'],
										'threadId' => $data['threadId'] ? $data['threadId'] : $savedListingData['threadId'],
										'hiddenTags' => $data['hiddenTags'],
										'tags' => $data['tags'] ? $data['tags'] : $savedListingData['tags'],
										'listing_type' => 'institute',
										'submit_date' => $instituteSubmitDate,
										'last_modify_date' => date('Y-m-d H:i:s'),
										'moderation_flag' => $data['moderated'],
										'requestIP' => $data['requestIP'],
										'crawled' => $data['crawled'],
										'pack_type' => $packType,
										'viewCount' => $instituteViewCount,
										'subscriptionId' => $subscriptionId,
										'approvedBy' => $approvedBy,
										'contact_details_id' => $data['cid'],
										'status' => $status,
										'version' => $version,
										'editedBy' => $data['editedBy'],
										'showWiki' => $data['showWiki'],
										'showMedia' => $data['showMedia'],
										'no_Of_Past_Free_Views' => $no_Of_Past_Free_Views,
										'no_Of_Past_Paid_Views' => $no_Of_Past_Paid_Views,
										'listing_seo_url' => $instituteSeoUrl,
										'listing_seo_title' => $data['listing_seo_title'],
										'listing_seo_description' => $data['listing_seo_description'],
										'listing_seo_keywords' => $data['listing_seo_keywords']
									);
		$this->dbHandle->insert('listings_main',$listingsMainData);
		$listingsMainId = $this->dbHandle->insert_id();
		
		$locations = $data['locationInfo'];
		$contacts = $data['contactInfo'];
		
		for($i=0;$i<count($locations);$i++) {
			
			$instituteLocationId = $locations[$i]['institute_location_id'];
			if(!$instituteLocationId) {
				$instituteLocationId = $this->_getNewInstituteLocationId();	
			}
			
			$locationData = array(
					'institute_location_id' => $instituteLocationId,
					'institute_id' => $instituteId,
					'address_2' => $locations[$i]['address2'],
					'locality_id' => $locations[$i]['locality_id'],
					'locality_name' => $locations[$i]['locality_name'],
					'city_id' => $locations[$i]['city_id'],
					'country_id' => $locations[$i]['country_id'],
					'address_1' => $locations[$i]['address1'],
					'pincode' => $locations[$i]['pin_code'],
					'zone' => $locations[$i]['zone_id'],
					'status' => $status,
					'version' => $version,
					'city_name' => $locations[$i]['city_name']
			);

			$this->dbHandle->insert('institute_location_table',$locationData);
			
			$contactData = array(
					'contact_person' => $contacts[$i]['contact_person_name'],
					'contact_email' => $contacts[$i]['contact_person_email'],
					'contact_main_phone' => $contacts[$i]['main_phone_number'],
					'contact_cell' => $contacts[$i]['mobile_number'],
					'contact_alternate_phone' => $contacts[$i]['alternate_phone_number'],
					'contact_fax' => $contacts[$i]['fax_number'],
					'website' => $contacts[$i]['website'],
					'listing_type' => 'institute',
					'listing_type_id' => $instituteId,
					'institute_location_id' => $instituteLocationId,
					'status' => $status,
					'version' => $version
			);
			
			$this->dbHandle->insert('listing_contact_details',$contactData);
			$contactDetailsId = $this->dbHandle->insert_id();
        }
		
		if($contactDetailsId) {
			$sql = "UPDATE listings_main SET contact_details_id = ? where listing_id = ? ";
			$this->dbHandle->query($sql, array($contactDetailsId, $listingsMainId));
			
			$sql = "UPDATE institute SET contact_details_id = ? where institute_id = ? AND version = 1";
			$this->dbHandle->query($sql, array($contactDetailsId, $instituteId));
		}
		
		
		$wikiSections = $data['wiki'];
		
		$sql = "SELECT * FROM listing_fields_table WHERE listing_type='institute' ORDER BY formPageOrder";
        $query = $this->dbHandle->query($sql);
        foreach($query->result_array() as $field) {
            if(isset($wikiSections[$field['key_name']]) && strlen(trim($wikiSections[$field['key_name']]))>0) {
                $wikiData = array(
                        'listing_type' => 'institute',
                        'listing_type_id' => $instituteId,
                        'caption' => $field['caption'],
                        'attributeValue' => $wikiSections[$field['key_name']],
                        'isPaid' => $field['isPaid'],
                        'keyId' => $field['keyId'],
                        'status' => $status,
                        'version' => $version
                        );
                $this->dbHandle->insert('listing_attributes_table',$wikiData);
                unset($wikiSections[$field['key_name']]);
            }
        }
		
        if(count($wikiSections['user_fields']) > 0){
            foreach($wikiSections['user_fields'] as $tmpWiki ){
                if(strlen(trim($tmpWiki['caption'])) > 0 && strlen(trim($tmpWiki['value']))>0){
                    $wikiData = array(
                        'listing_type' => 'institute',
                        'listing_type_id' => $instituteId,
                        'caption' => trim($tmpWiki['caption']),
                        'attributeValue' => trim($tmpWiki['value']),
                        'isPaid' => 'yes',
                        'keyId' => '-1',
                        'status' => $status,
                        'version' => $version
                    );
                    $this->dbHandle->insert('listing_attributes_table',$wikiData);
                }
            }
        }
		
		if($data['details'] != '') {
			$joinReasonData = array(
									'institute_id' => $instituteId,
									'photo_url' => $data['photoArr']['url'] ? $data['photoArr']['url'] : $savedJoinReason['photo_url'],
									'details' => $data['details'],
									'status' => $status,
									'version' => $version
								);
			$this->dbHandle->insert('institute_join_reason',$joinReasonData);
		}

		// save institute facilities data
		if(!empty($data['institute_facilities'])){
			$insertarr = array();
			foreach($data['institute_facilities'] as $attrkey => $desc){
				$insertarr[] = array(
					'listing_type' => 'institute',
					'listing_type_id' => $instituteId,
					'facility_id' => $attrkey,
					'description' => trim($desc),
					'status' => $status,
					'version' => $version,
					'timestamp' => date('Y-m-d H:i:s')
					);
			}
			$this->dbHandle->insert_batch('institute_facilities',$insertarr);
		}
		/**
		 * Save edit comments
		 */
		$commentsData = array(
			'userId' => $data['cmsTrackUserId'],
			'listingId' => $instituteId,
			'tabUpdated' => $data['cmsTrackTabUpdated'],
			'comments' => $data['mandatory_comments']
		);
		$this->dbHandle->insert('listingCMSUserTracking',$commentsData);
		
		/**
		 * Consume pseudo subscription
		 */ 
		if($data['flow'] != 'edit' && !($data['group_to_be_checked'] == 'cms' && $data['onBehalfOf'] == "false" )) {
			$this->subsciptionConsumer->consumePseudoSubscription(1,$data['subscriptionId'],'-1',$data['clientId'],$data['editedBy'],'-1',$instituteId,'institute','-1','-1');
		}
		
		$this->dbHandle->trans_complete();
		
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
		}
		
		return $instituteId;
	}
	
	private function _getSEOURL($data,$instituteId)
	{
		if(empty($data['listing_seo_url'])) {
		
			if (!empty($data['abbreviation'])) {
				$title = seo_url(implode("-", array($data['abbreviation'],$data['institute_name'])), "-", 30);
			} else {
				$title = seo_url($data['institute_name'],"-",30);
			}
			//While adding a Single-location listing (institute / course), this will work as it is and we will have the location in the listing URL.
			if(count($data['locationInfo']) == 1) {
				if(!empty($data['locationInfo']['0']['locality_name'])) {
					$location = seo_url(implode("-", array($data['locationInfo']['0']['locality_name'], $data['locationInfo']['0']['city_name'])), "-", 10);
				} else {
					$location = seo_url($data['locationInfo']['0']['city_name'], "-", 10);
				}
				$seoUrl = SHIKSHA_HOME_URL."/".$title . "-" . $location."-institute-college-listingoverviewtab-".$instituteId;
			}
			else {
				$seoUrl = SHIKSHA_HOME_URL."/".$title ."-institute-college-listingoverviewtab-".$instituteId;
			}
		}
		else {
			$seoUrl = SHIKSHA_HOME_URL."/".seo_url($data['listing_seo_url'],"-",30)."-listingoverviewtab-".$instituteId;	
		}
		
		return $seoUrl;
	}
	
	private function _getNewInstituteId()
	{
		return Modules::run('common/IDGenerator/generateId','institute');
	}
	
	private function _getNewInstituteLocationId()
	{
		return Modules::run('common/IDGenerator/generateId','instituteLocation');
	}
	
	private function _getInstituteData($instituteId)
	{
		$sql = "SELECT * FROM institute WHERE institute_id = ? ORDER BY id DESC LIMIT 1";
		$query = $this->dbHandle->query($sql,array($instituteId));
		$row = $query->row_array();
		return $row;
	}
	
	private function _getListingData($instituteId)
	{
		$sql = "SELECT *
				FROM listings_main
				WHERE listing_type = 'institute'
				AND listing_type_id = ?
				AND status IN ('live', 'draft')
				ORDER BY listing_id DESC LIMIT 1";
				
		$query = $this->dbHandle->query($sql,array($instituteId));
		$row = $query->row_array();
		return $row;
	}
	
	private function _getJoinReasonData($instituteId)
	{
		$sql = "SELECT *
				FROM institute_join_reason
				WHERE institute_id = ?
				AND version = 1";
				
		$query = $this->dbHandle->query($sql,array($instituteId));
		$row = $query->row_array();
		return $row;
	}
	
	public function isDuplicateInstitutePosting($data)
	{
		$this->initiateModel('write');
		
		$sql = "SELECT institute.institute_id
				FROM institute,institute_location_table
				WHERE institute.institute_id = institute_location_table.institute_id
				AND pincode = ? 
				AND institute_name = ?
				AND institute.status in ('live','draft','queued')
				AND institute.status = institute_location_table.status";
				
		$query = $this->dbHandle->query($sql,array(trim($data['locationInfo'][0]['pin_code']),$data['institute_name']));
		if($query->num_rows() > 0) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
}
