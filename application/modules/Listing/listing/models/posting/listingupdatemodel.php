<?php

require_once dirname(__FILE__).'/PostingModelAbstract.php';

class ListingUpdateModel extends PostingModelAbstract
{	
    function __construct()
	{
		parent::__construct();
    }

	public function updateContactDetailsForInstitute($instituteId,$instituteLocationIds,$contactDetails)
	{
		$this->initiateModel('write');
		
		foreach($instituteLocationIds as $locationId) {
			$this->_updateContactDetails($instituteId,'institute',$locationId,$contactDetails);
		}
	
		$this->updateLastModifiedDate($instituteId,'institute');
	}
	
	public function updateContactDetailsForCourse($courseLocationIds,$contactDetails)
	{
		$this->initiateModel('write');
		
		foreach($courseLocationIds as $courseLocationId) {
			$courseId = $courseLocationId['courseId'];
			$locationId = $courseLocationId['locationId'];
			
			$sql =	"SELECT contact_details_id ".
					"FROM listing_contact_details ".
					"WHERE listing_type = 'course' ".
					"AND listing_type_id = ? ".
					"AND institute_location_id = ? ".
					"AND status IN ('draft','live')";
			
			$query = $this->dbHandle->query($sql,array($courseId,$locationId));
			
			if($query->num_rows() > 0) {
				$this->_updateContactDetails($courseId,'course',$locationId,$contactDetails);
			}
			else {
				$this->_createContactDetails($courseId,'course',$locationId,$contactDetails);
			}
			
			$this->updateLastModifiedDate($courseId,'course');
		}
	}
	
	private function _createContactDetails($listingId,$listingType,$locationId,$contactDetails)
	{
		$contactDetailsData = array(
										'contact_person' => $contactDetails['name'],
										'contact_email' => $contactDetails['email'],
										'contact_main_phone' => $contactDetails['phone'],
										'contact_cell' => $contactDetails['mobile'],
										'listing_type' => $listingType,
										'listing_type_id' => $listingId,
										'institute_location_id' => $locationId,
										'status' => 'live',
										'version' => 1
									);
		$this->dbHandle->insert('listing_contact_details',$contactDetailsData);
	}
	
	private function _updateContactDetails($listingId,$listingType,$locationId,$contactDetails)
	{
		$sql =  "UPDATE listing_contact_details ".
				"SET contact_person = ?, contact_email = ?, contact_main_phone = ?, contact_cell = ? ".
				"WHERE listing_type = ? ".
				"AND listing_type_id = ? ".
				"AND institute_location_id = ? ".
				"AND status IN ('draft','live')";
						
		$query = $this->dbHandle->query($sql,array(
													$contactDetails['name'],
													$contactDetails['email'],
													$contactDetails['phone'],
													$contactDetails['mobile'],
													$listingType,
													$listingId,
													$locationId
												)
										);	
	}
}