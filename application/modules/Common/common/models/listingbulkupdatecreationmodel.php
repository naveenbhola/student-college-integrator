<?php
class listingbulkupdatecreationmodel extends MY_Model {

        var $updateComment = "Listing data updated by Script for Seats/Fees/Eligibility";
        var $udpatedById = "11";

        function __construct() {
                parent::__construct('default');
        }

        private function initiateModel($mode = "write", $module = ''){
                if($mode == 'read') {
                    $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
                } else {
                    $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
                }
        }

        public function checkValidCity($cityId){
                $this->initiateModel('read');

                $city = array();
                $sql =  "SELECT * FROM countryCityTable WHERE city_id = ?";
                $city = $this->dbHandle->query($sql, array($cityId))->result_array();

                return $city;
        }

        public function checkValidCourse($courseId){
                $this->initiateModel('read');

                $course = array();
                $sql =  "SELECT course_id FROM shiksha_courses WHERE course_id = ? AND status = 'live'";
                $course = $this->dbHandle->query($sql, array($courseId))->result_array();

                return $course;
        }

	public function checkValidInstitute($instituteId){
                $this->initiateModel('read');

                $institute = array();
                $sql =  "SELECT listing_id, institute_specification_type as type FROM shiksha_institutes WHERE listing_id = ? AND status = 'live'";
                $institute = $this->dbHandle->query($sql, array($instituteId))->result_array();

                return $institute;
	}

	public function checkValidUniversity($universityId){
                $this->initiateModel('read');

                $university = array();
                $sql =  "SELECT listing_id, institute_specification_type as type FROM shiksha_institutes WHERE listing_id = ? AND status = 'live' AND listing_type = 'university'";
                $university = $this->dbHandle->query($sql, array($universityId))->result_array();

                return $university;
	}

	public function checkStream($fieldValue){
                $this->initiateModel('read');

                $rows = array();
                $sql =  "SELECT * FROM streams WHERE stream_id = ? AND status = 'live'";
                $rows = $this->dbHandle->query($sql, array($fieldValue))->result_array();

                return $rows;
	}

	public function checkSubStream($fieldValue){
                $this->initiateModel('read');

                $rows = array();
                $sql =  "SELECT * FROM substreams WHERE substream_id = ? AND status = 'live'";
                $rows = $this->dbHandle->query($sql, array($fieldValue))->result_array();

                return $rows;
	}

	public function checkSpecialization($fieldValue){
                $this->initiateModel('read');

                $rows = array();
                $sql =  "SELECT * FROM specializations WHERE specialization_id = ? AND status = 'live'";
                $rows = $this->dbHandle->query($sql, array($fieldValue))->result_array();

                return $rows;
	}

	public function checkBaseCourse($fieldValue){
                $this->initiateModel('read');

                $rows = array();
                $sql =  "SELECT * FROM base_courses WHERE base_course_id = ? AND status = 'live'";
                $rows = $this->dbHandle->query($sql, array($fieldValue))->result_array();

                return $rows;
	}

	public function checkSubStreamMapping($substream, $streamId){
                $this->initiateModel('read');

                $rows = array();
                $sql =  "SELECT * FROM base_hierarchies WHERE substream_id = ? AND stream_id = ? AND status = 'live'";
                $rows = $this->dbHandle->query($sql, array($substream,$streamId))->result_array();

                return $rows;
	}

	public function checkSpecializationStreamMapping($fieldValue, $streamId){
                $this->initiateModel('read');

                $rows = array();
                $sql =  "SELECT * FROM base_hierarchies WHERE specialization_id = ? AND stream_id = ? AND status = 'live'";
                $rows = $this->dbHandle->query($sql, array($fieldValue,$streamId))->result_array();

                return $rows;
	}

	public function checkSpecializationSubStreamMapping($fieldValue, $substreamId){
                $this->initiateModel('read');

                $rows = array();
                $sql =  "SELECT * FROM base_hierarchies WHERE specialization_id = ? AND substream_id = ? AND status = 'live'";
                $rows = $this->dbHandle->query($sql, array($fieldValue,$substreamId))->result_array();

                return $rows;
	}

	public function checkBCStreamMapping($fieldValue, $streamId){
                $this->initiateModel('read');

                $rows = array();
                $sql =  "SELECT * FROM entity_hierarchy_mapping WHERE entity_id = ? AND stream_id = ? AND status = 'live'";
                $rows = $this->dbHandle->query($sql, array($fieldValue,$streamId))->result_array();

                return $rows;
	}

	public function getStateId($cityId){
                $this->initiateModel('read');

                $city = array();
                $sql =  "SELECT state_id FROM countryCityTable WHERE city_id = ?";
                $state = $this->dbHandle->query($sql, array($cityId))->result_array();

                return $state[0]['state_id'];
	}

	public function getParentDetails($parentId){
                $this->initiateModel('read');

                $university = array();
                $sql =  "SELECT listing_type as type, name FROM shiksha_institutes WHERE listing_id = ? AND status = 'live' LIMIT 1";
                $university = $this->dbHandle->query($sql, array($parentId))->result_array();

                return $university[0];
	}

	public function getLocationId($parentId){
                $this->initiateModel('read');

                $location = array();
                $sql =  "SELECT listing_location_id as locationId FROM shiksha_institutes_locations WHERE listing_id = ? AND status = 'live' AND is_main = 1 LIMIT 1";
                $location = $this->dbHandle->query($sql, array($parentId))->result_array();

                return $location[0]['locationId'];
	}
}
?>