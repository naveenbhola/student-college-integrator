<?php 

class InstituteDetailsModel extends MY_Model{

	private $dbHandle = null;
	private $cache;
	private $getAllCoursesForInstitutes = array();
    function __construct($cache)
	{
		parent::__construct('Listing');
		$this->cache = $cache;
		$this->load->config('nationalInstitute/instituteStaticAttributeConfig');
		$this->load->config('nationalInstitute/instituteSectionConfig');
		$this->flatTableName = "shiksha_courses_institutes";
    }

	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function getInstituteData($instituteId,$postingListingType='institute'){
		$this->initiateModel('read');

		$instituteData = array();
		$status = 'draft';
		$listingMainStatus= $this->config->item("listingMainStatus");
		// Get institutes basic details
		$basicInfo = array();
		$this->dbHandle->where(array('listing_id'=>$instituteId,'status'=>$status));
		$this->dbHandle->limit(1);
		$query = $this->dbHandle->get('shiksha_institutes');

		if($query->num_rows() <= 0) {
			$status = 'live';
			$this->dbHandle->where(array('listing_id'=>$instituteId,'status'=>$status));
			$this->dbHandle->limit(1);
			$query = $this->dbHandle->get('shiksha_institutes');
			if($query->num_rows() > 0){
				$basicInfo = $query->row_array();
			}
			else{
				return 'NO_SUCH_LISTING_FOUND_IN_DB';
			}
		}
		else{
			$basicInfo = $query->row_array();
		}
		$instituteData['basic_info'] = $basicInfo;
		
		// Get institutes brochure details
		$this->dbHandle->where(array('listing_id'=>$instituteId, 'cta'=>'brochure','listing_type'=>$postingListingType,'status'=>$status));
		$this->dbHandle->select('brochure_year,brochure_url,brochure_size');
		$query = $this->dbHandle->get('shiksha_listings_brochures');

		if($query->num_rows() > 0) {
			$data = $query->row_array();
			$instituteData['basic_info']['brochure_year'] = $data['brochure_year'];
			$instituteData['basic_info']['brochure_url'] = $data['brochure_url'];
			$instituteData['basic_info']['brochure_size'] = $data['brochure_size'];
		}

		// Get institutes academic details
		$academicStaff = array();
		$this->dbHandle->where(array('listing_id'=>$instituteId,'status'=>$status));
		$this->dbHandle->select('name,type_id,current_designation,education_background,professional_highlights,display_order');
		$this->dbHandle->order_by('display_order','asc');
		$query = $this->dbHandle->get('shiksha_institutes_academic_staffs');

		if($query->num_rows() > 0) {
			$academicStaff = $query->result_array();
		}
		$instituteData['academic_staff'] = $academicStaff;

		//academic staff faculty highlights details
		$sql = " SELECT description from shiksha_institutes_additional_attributes where listing_id = ? AND status = ? AND description_type='faculty_highlights' order by updated_on desc limit 1";
		$rs = $this->dbHandle->query($sql,array($instituteId, $status))->result_array();
		
		if(count($rs) > 0)
		{
			$instituteData['academicStaff_faculty_highlights'] = $rs[0]['description'];
		}
		
		//fetching seo details for institute
		$seoDetails = array();

		$listingsMainStatus = $listingMainStatus['draft'];
		if($status == 'live'){
			$listingsMainStatus = $listingMainStatus['live'];
		}

		$listingMainType = $postingListingType;
		if($postingListingType == 'university')
		{
			$listingMainType = 'university_national';
		}
		$sql = "SELECT listing_seo_url,listing_seo_title,listing_seo_description,username,submit_date FROM listings_main where listing_type_id = ? AND listing_type= ? AND status = ? order by submit_date desc limit 1";

		$rs = $this->dbHandle->query($sql,array($instituteId,$listingMainType,$listingsMainStatus))->result_array();

		if(count($rs) > 0)
		{
			$instituteData['listing_seo_title'] 		= $rs[0]['listing_seo_title'];
			$instituteData['listing_seo_description'] = $rs[0]['listing_seo_description'];
			$instituteData['listing_seo_url'] = $rs[0]['listing_seo_url'];
			$instituteData['client_id'] = $rs[0]['username'];
			$instituteData['submit_date'] = $rs[0]['submit_date'];
		}

		// Get institutes event details
		$events = array();
		$this->dbHandle->where(array('listing_id'=>$instituteId,'status'=>$status));
		$this->dbHandle->select('id,event_type_id,name,description,position');
		$this->dbHandle->order_by('position','asc');
		$query = $this->dbHandle->get('shiksha_institutes_events');

		if($query->num_rows() > 0) {
			$events = $query->result_array();
		}
		$instituteData['events'] = $events;

		$scholarships = array();
		$this->dbHandle->where(array('listing_id'=>$instituteId,'status'=>$status));
		$this->dbHandle->select('scholarship_type_id,description');
		$query = $this->dbHandle->get('shiksha_institutes_scholarships');

		if($query->num_rows() > 0) {
			$scholarships = $query->result_array();
		}
		$instituteData['scholarships'] = $scholarships;

		// get institutes additional attributes
		$additional_attributes = array();
		$this->dbHandle->where(array('listing_id'=>$instituteId,'status'=>$status));
		$this->dbHandle->select('description,description_type');
		$query = $this->dbHandle->get('shiksha_institutes_additional_attributes');

		if($query->num_rows() > 0) {
			$additional_attributes = $query->result_array();
		}
		$instituteData['additional_attributes'] = $additional_attributes;

		// get institutes recruiting companies details
		$companies_mapping = array();
		$this->dbHandle->select('icm.company_id,icm.order,cl.company_name',false)->from('shiksha_institutes_companies_mapping as icm');
		$this->dbHandle->join('company_logos as cl','icm.company_id = cl.id');
		$this->dbHandle->where('listing_id',$instituteId)->where("icm.status='".$status."'",NULL,false);
		$this->dbHandle->where_in('listing_type', array('institute', 'university'));
		$query = $this->dbHandle->get();

		if($query->num_rows() > 0) {
			$companies_mapping = $query->result_array();
		}
		$instituteData['companies_mapping'] = $companies_mapping;

		// Get institutes facility details
		$facilitiesData = array();
		$this->dbHandle->select('facility_id,description,additional_info,has_facility');
		$this->dbHandle->where_in('listing_type', array('institute', 'university'));
		$this->dbHandle->where(array('listing_id'=>$instituteId,'status'=>"'".$status."'"), NULL,false);
		$query = $this->dbHandle->get('shiksha_institutes_facilities');

		if($query->num_rows() > 0) {
			$data = $query->result_array();
			foreach ($data as $row) {
				$facilitiesData[$row['facility_id']] = $row;
			}
		}

		$instituteData['facilitiesData'] = $facilitiesData;

		// Get institutes facility mapping details
		$facilityMappings = array();
		$this->dbHandle->where(array('listing_id'=>$instituteId,'status'=>$status));
		$this->dbHandle->select('facility_id,value_id,custom_name');
		$query = $this->dbHandle->get('shiksha_institutes_facilities_mappings');

		if($query->num_rows() > 0) {
			$data = $query->result_array();
			foreach ($data as $row) {
				$facilityMappings[$row['facility_id']][] = $row;
			}
		}
		$instituteData['facilityMappings'] = $facilityMappings;

		// Get institutes location details
		$locations = array();
		$this->dbHandle->where(array('listing_id'=>$instituteId,'listing_type'=>$postingListingType,'status'=>$status));
		$this->dbHandle->select('state_id,city_id,locality_id,is_main,listing_location_id');
		$this->dbHandle->order_by('locality_id','asc');
		// $this->dbHandle->limit(500);
		$query = $this->dbHandle->get('shiksha_institutes_locations');

		if($query->num_rows() > 0) {
			$locations = $query->result_array();
		}
		$instituteData['locations'] = $locations;

		// Get institutes contact details
		$contactDetails = array();
		$this->dbHandle->where(array('listing_id'=>$instituteId,'listing_type'=>$postingListingType,'status'=>$status));
		$this->dbHandle->select('listing_location_id,website_url,address,latitude,longitude,admission_contact_number,admission_email,generic_contact_number,generic_email,google_url');
		$query = $this->dbHandle->get('shiksha_listings_contacts');

		if($query->num_rows() > 0) {
			$contactDetails = $query->result_array();
		}
		$instituteData['contactDetails'] = $contactDetails;

		// Get institutes media details
		$photos = array();
		$mediaStatus = $status;
		$this->dbHandle->select('im.media_id,listing_location_id,media_title,media_url,media_thumb_url,media_order')->from('shiksha_institutes_medias as im');
		$this->dbHandle->join('shiksha_institutes_media_locations_mapping as iml','im.media_id = iml.media_id');
		$this->dbHandle->order_by('media_order','asc');
		$this->dbHandle->where(array('listing_id'=>$instituteId,'im.media_type'=>'photo', 'iml.media_type'=>'photo'))->where("iml.status='".$mediaStatus."'",NULL,false);
		$this->dbHandle->group_by('media_id,listing_location_id');
		$query = $this->dbHandle->get();

		if($query->num_rows() > 0) {
			$data = $query->result_array();
			foreach ($data as $row) {
				
				$photos[$row['media_id']]['locations'][]     = array('listing_location_id'=>$row['listing_location_id']);
				$photos[$row['media_id']]['media_title']     = $row['media_title'];
				$photos[$row['media_id']]['media_url']       = $row['media_url'];
				$photos[$row['media_id']]['media_thumb_url'] = $row['media_thumb_url'];
				$photos[$row['media_id']]['media_order']     = $row['media_order'];
			}

			$query = $this->dbHandle->query("SELECT distinct mt.media_id,mt.tag_id,mt.tag_type from shiksha_institutes_media_tags_mapping mt INNER JOIN shiksha_institutes_media_locations_mapping as iml ON(mt.media_id = iml.media_id AND iml.media_type='photo' AND iml.listing_id = ? AND iml.listing_type IN ('institute', 'university') AND iml.status= ?) where mt.listing_id = ? AND mt.status = ?", array($instituteId, $mediaStatus, $instituteId, $mediaStatus));
			if($query->num_rows() > 0) {
				$tagData = $query->result_array();
				foreach ($tagData as $row) {
					if(!empty($photos[$row['media_id']])){
						$photos[$row['media_id']]['tags'][] = array("id"=>$row['tag_id'],"type"=>$row['tag_type']);
					}
				}
			}
		}
		$instituteData['photos'] = $photos;

		$videos = array();
		$mediaStatus = $status;
		$this->dbHandle->select('im.media_id,listing_location_id,media_title,media_url,media_thumb_url,media_order')->from('shiksha_institutes_medias as im');
		$this->dbHandle->join('shiksha_institutes_media_locations_mapping as iml','im.media_id = iml.media_id');
		$this->dbHandle->order_by('media_order','asc');
		$this->dbHandle->where(array('listing_id'=>$instituteId,'im.media_type'=>'video', 'iml.media_type'=>'video'))->where("iml.status='"."$mediaStatus'",NULL,false);
		$this->dbHandle->group_by('media_id,listing_location_id');
		$query = $this->dbHandle->get();
// _p($this->dbHandle->last_query());
		if($query->num_rows() > 0) {
			$data = $query->result_array();
			// _p($data);die;
			foreach ($data as $row) {
				
				$videos[$row['media_id']]['locations'][]     = array('listing_location_id'=>$row['listing_location_id']);
				$videos[$row['media_id']]['media_title']     = $row['media_title'];
				$videos[$row['media_id']]['media_url']       = $row['media_url'];
				$videos[$row['media_id']]['media_thumb_url'] = $row['media_thumb_url'];
				$videos[$row['media_id']]['media_order']     = $row['media_order'];
			}

			$query = $this->dbHandle->query("SELECT distinct mt.media_id,mt.tag_id,mt.tag_type from shiksha_institutes_media_tags_mapping mt INNER JOIN shiksha_institutes_media_locations_mapping as iml ON(mt.media_id = iml.media_id AND iml.media_type='video' AND iml.listing_id = ? AND iml.listing_type IN ('institute', 'university') AND iml.status= ?) where mt.listing_id = ? AND mt.status = ?", array($instituteId, $mediaStatus, $instituteId, $mediaStatus));
			if($query->num_rows() > 0) {
				$tagData = $query->result_array();
				foreach ($tagData as $row) {
					$videos[$row['media_id']]['tags'][] = array("id"=>$row['tag_id'],
																"type"=>$row['tag_type']);
				}
			}
		}
		$instituteData['videos'] = $videos;
		return $instituteData;
	}

	public function checkIfInstituteLocationsMappedToCourse($instituteLocationIds){
		$this->initiateModel('read');

		if(empty($instituteLocationIds)){
			return false;
		}

		$sql = "SELECT id FROM shiksha_courses_locations WHERE listing_location_id IN (?) AND status IN ('draft', 'live')";

		$query = $this->dbHandle->query($sql,array($instituteLocationIds));
		if($query->num_rows() > 0) {
			return true;
		}
		return false;
	}
        
        public function getNaukriSalaryData($instituteId, $numberOfInstitutes = 'single',$fields = array())
	{
		$selectClause = '*';

		if(is_array($fields) && count($fields) > 0)
		{
			$selectClause = implode(',', $fields);
		}

		if($numberOfInstitutes == 'single') {
			$sql = "SELECT  $selectClause  FROM naukri_salary_data WHERE institute_id = ?";
			$results = $this->db->query($sql, (int) $instituteId)->result_array();
		}
		else if($numberOfInstitutes == 'multiple') {
			$sql = "SELECT  $selectClause  FROM naukri_salary_data WHERE institute_id IN (?)";
			$results = $this->db->query($sql,array($instituteId))->result_array();
		}
		
		return $results;
	}
        
        public function getNaukriEmployeesData ($instituteId,$numberOfInstitutes = 'single',$fields = array()) {

        	$selectClause = '*';

        	if(is_array($fields) && count($fields) > 0)
        	{
        		$selectClause = implode(',', $fields);
        	}
            if($numberOfInstitutes =='single'){
                $sql = "SELECT  $selectClause  FROM naukri_alumni_stats WHERE institute_id = ?";   
                $results = $this->db->query($sql, $instituteId)->result_array();
            }
            else if($numberOfInstitutes =='multiple'){
                $sql = "SELECT  $selectClause  FROM naukri_alumni_stats WHERE institute_id IN (?)";
                $results = $this->db->query($sql,array($instituteId))->result_array();
            }
            
            return $results;
        }
	public function getRecruitingCompanies($instituteId){
		$status = 'live';
		$this->initiateModel('read');
		// get institutes recruiting companies details
		$companies_mapping = array();
		$this->dbHandle->distinct('icm.company_id');
		$this->dbHandle->select('icm.company_id,icm.order,cl.company_name')->from('shiksha_institutes_companies_mapping as icm');
		$this->dbHandle->join('company_logos as cl','icm.company_id = cl.id');
		$this->dbHandle->where('listing_id',$instituteId)->where("icm.status='".$status."'",NULL,false);
		$this->dbHandle->where_in('listing_type', array('institute', 'university'));
		$this->dbHandle->order_by('cl.company_name','asc');
		$query = $this->dbHandle->get();

		if($query->num_rows() > 0) {
			$companies_mapping = $query->result_array();
		}
		return  $companies_mapping;
	}

	public function getInstituteMedia($instituteId){
		$status = 'live';
		$this->initiateModel('read');
		// get institutes recruiting companies details
		$media_mapping = array();
		$this->dbHandle->distinct('imlm.media_id');
		$this->dbHandle->select('imlm.media_id,imlm.media_type,im.media_thumb_url,im.media_url,im.media_title')->from('shiksha_institutes_media_locations_mapping as imlm');
		$this->dbHandle->join('shiksha_institutes_medias as im','imlm.media_id = im.media_id');
		$this->dbHandle->where('listing_id',$instituteId)->where("imlm.status='".$status."'",NULL,false)->where("im.status='".$status."'",NULL,false);
		$this->dbHandle->order_by('im.media_title','asc');
		$query = $this->dbHandle->get();

		if($query->num_rows() > 0) {
			$media_mapping = $query->result_array();
		}
		return  $media_mapping;	
	}

	function getInstituteStickyCourses($listingId, $listingType){
		$this->initiateModel('read');

		$sql = "	SELECT entityId,entityType,course_order,type 
					FROM shiksha_listing_contentSticky 
					WHERE status='live' AND listing_id = ? 
					AND listing_type = ? AND (expiry_date >= NOW() OR ISNULL(expiry_date))
					ORDER BY course_order ASC";

		$resultSet = $this->dbHandle->query($sql,array($listingId, $listingType));
		$resultSet = $resultSet->result_array();

		$result = array();
		foreach ($resultSet as $row) {
			$result[$row['type']][] = $row;
		}
		return $result;
	}

	function checkPaidCourses($courseIds){

		$result = array();
		if(empty($courseIds) || !is_array($courseIds))
			return $result;

		$this->initiateModel('read');

		$listingMainStatus = $this->config->item("listingMainStatus");
		$listingMainStatus = $listingMainStatus['live'];
		$sql = "	SELECT listing_type_id, pack_type
					FROM listings_main
					WHERE listing_type =  'course'
					AND STATUS IN ('".$listingMainStatus."')
					AND listing_type_id IN (?) ";

		$resultSet = $this->dbHandle->query($sql,array($courseIds));
		$resultSet = $resultSet->result_array();

		$paidCourses = array();
		foreach ($resultSet as $row) {
			if(in_array($row['pack_type'], array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID, SILVER_LISTINGS_BASE_PRODUCT_ID, GOLD_ML_LISTINGS_BASE_PRODUCT_ID))){
				$paidCourses[] = $row['listing_type_id'];
			}
		}

		foreach ($courseIds as $courseId) {
			if(in_array($courseId, $paidCourses)){
				$result[] = $courseId;
			}
		}
		
		return $result;
	}

	public function checkForDeletedInstitute($listingId,$listingType){
		$this->initiateModel('read');
		
		$sql = "SELECT status FROM shiksha_institutes WHERE listing_id = ? AND status='deleted'";
		$result = $this->dbHandle->query($sql,array($listingId,$listingType))->result_array();
		
		if(!empty($result) && $result[0]['status'] == 'deleted'){
			$sql = "SELECT replacement_lisiting_type_id,listing_type FROM deleted_listings_mapping_table WHERE listing_type_id = ? AND listing_type in ('institute','university') ORDER BY id desc limit 1";
			$result1 = $this->dbHandle->query($sql,array($listingId))->result_array();

			if(!empty($result1)){
				$listingMainStatus= $this->config->item("listingMainStatus");

				$sql = "SELECT listing_seo_url FROM listings_main WHERE listing_type_id = ? AND listing_type in ('university_national','institute') AND status = ? ";
				$result2 = $this->dbHandle->query($sql,array($result1[0]['replacement_lisiting_type_id'],$listingMainStatus['live']))->result_array();
				
			}
		}

		return $result2[0]['listing_seo_url'];
	}
 
	public function checkForStickyArticle($listingId,$listingType){
		$this->initiateModel('read');
		$sql = "SELECT entityId
				FROM shiksha_listing_contentSticky
				WHERE listing_id = ? 
				AND listing_type = ?
				AND type = 'article'
				AND status = 'live' 
				AND expiry_date >= now()
				limit 1";

		$result = $this->dbHandle->query($sql,array($listingId,$listingType))->result_array();
		return $result[0]['entityId'];

	}
	public function getArticleInfo($articleIds,$limit){
		$this->initiateModel('read');
		$sql = "SELECT blogId,blogTitle,summary,url 
				FROM blogTable
				WHERE status = 'live'
				AND blogId in (?)";

		$result = $this->dbHandle->query($sql,array($articleIds))->result_array();

		return $result;
	}

	public function getCoursesLocations($courseIds){
		$this->initiateModel('read');

		$result = array();
		if(empty($courseIds))
			return $result;

		$sql = "SELECT listing_location_id,course_id FROM shiksha_courses_locations WHERE course_id IN (?) AND status='live'";

		$rs = $this->dbHandle->query($sql,array($courseIds))->result_array();

		foreach ($rs as $value) {
			$result[$value['course_id']][] = $value['listing_location_id'];
		}

		return $result;
	}

	public function getUniqueCoursesLocations($courseIds){
		$this->initiateModel('read');

		$result = array();
		if(empty($courseIds))
			return $result;

		$sql = "SELECT distinct listing_location_id FROM shiksha_courses_locations WHERE course_id IN (?) AND status='live'";

		$rs = $this->dbHandle->query($sql,array($courseIds))->result_array();

		foreach ($rs as $value) {
			$result[] = $value['listing_location_id'];
		}

		return $result;
	}

	function getCoursesHavingLocations($courseIds, $instituteLocationId){
		$this->initiateModel('read');

		$result = array();
		if(empty($courseIds) || empty($instituteLocationId))
			return $result;

		$sql = "SELECT distinct course_id FROM shiksha_courses_locations WHERE course_id IN (?) AND status='live' AND listing_location_id= ?";

		$rs = $this->dbHandle->query($sql, array($courseIds,$instituteLocationId))->result_array();

		foreach ($rs as $value) {
			$result[] = $value['course_id'];
		}

		return $result;
	}

    public function getAllNaukriSalaryData($instituteIds) {
		$sql = "SELECT distinct naukri_salary_data.*, institute.listing_id as instId , institute.name FROM naukri_salary_data right join shiksha_institutes institute on (naukri_salary_data.institute_id = institute.listing_id) WHERE institute.listing_id in (?) and institute.status='live' ORDER BY FIELD(institute.listing_id, ?)";
		$query = $this->db->query($sql,array($instituteIds,$instituteIds));
		$result = $query->result_array();
		$finalArr = array();
		if(is_array($result) && count($result) > 0) {
			foreach($result as $row) {
				$finalArr[$row['instId']][] = $row;
			}
			return $finalArr;
		}else{
			return 	$finalArr;
		}
	}

	function getAverageNaukriSalaryData($range){
		$sql = "select avg(ctc50)  as averageSalary from naukri_salary_data where exp_bucket = ? ";
		$result = $this->db->query($sql,array($range))->result_array();
		return round($result[0]['averageSalary'],2);	
	}
	function getExamsMappedToUniversity($listingId)
	{
		if(empty($listingId))
				return;
		
		$this->initiateModel();
		//$sql = "SELECT exm.name as name,exm.url as url, exm.fullName as fullName, (select entityId from examAttributeMapping where entityType='year' and status='live' and groupId=m.groupId) year from examAttributeMapping m JOIN exampage_groups eg ON ( m.groupId = eg.groupId AND eg.isPrimary =1 ) INNER JOIN exampage_master em  ON em.groupId = eg.groupId JOIN exampage_main  exm ON (eg.examId = exm.id)
		//	where m.entityId = ? AND m.status = 'live' AND em.status ='live'  AND exm.status = 'live' AND eg.status = 'live' AND m.entityType IN ('university','college')";
		$sql = "SELECT name as name, url as url, fullName as fullName, (SELECT m.entityId FROM examAttributeMapping m, exampage_groups eg, exampage_master em WHERE m.entityType='year' AND m.status = 'live' AND m.groupId = eg.groupId AND eg.isPrimary = 1 AND eg.status = 'live' AND em.groupId = eg.groupId AND em.status ='live' AND em.exam_id = exm.id LIMIT 1) year FROM exampage_main exm WHERE status = 'live' AND conductedBy = ?";

		$rs = $this->dbHandle->query($sql,array($listingId))->result_array();
		
		//$result = array();
		return $rs;

	}
	function getUserWorkExp($userId)
	{
		if(empty($userId))
			return;

		$this->initiateModel();

		$sql = "SELECT experience FROM tuser WHERE  userid = ?";

		$rs = $this->dbHandle->query($sql,array($userId))->result_array();

		return $rs[0]['experience'];
	}

	function getAffiatedCoursesOfUniversity($universityId){

		$this->initiateModel('read');

		$result = array();
		if(empty($universityId))
			return $result;

		$sql = "SELECT distinct course_id FROM shiksha_courses WHERE affiliated_university_id = ? and status='live' and affiliated_university_scope='domestic' and primary_id != ?";

		$rs = $this->dbHandle->query($sql, array($universityId,$universityId))->result_array();

		foreach ($rs as $value) {
			$result[] = $value['course_id'];
		}

		return $result;
	}
	function getListingsLatitudeLongitudeValues()
    {
        $this->initiateModel();

        $sql = "SELECT listing_id,listing_location_id,latitude,longitude,status from shiksha_listings_contacts WHERE listing_type IN ('institute','university') AND status IN ('live','draft') AND ( google_url = '' OR ISNULL(google_url) ) AND ((latitude IS NOT NULL AND longitude IS NOT NULL) AND (latitude != '' AND longitude != '') AND (latitude > 8.0000 AND latitude <= 37.6000) AND (longitude >= 68.7000 AND longitude <= 97.2500)) ORDER BY listing_location_id ASC LIMIT 20000";
        $result = $this->dbHandle->query($sql)->result_array();
        return $result;
    }

    function getAffiliatedCoursesForUniversity($universityIds, $universityScope){

    	if(empty($universityIds)) return;
    	if(!is_array($universityIds)) return;

    	$this->initiateModel('read');

    	$result = array();
    	$sql = "SELECT course_id FROM shiksha_courses WHERE affiliated_university_id IN (?) AND affiliated_university_scope = ? AND status = 'live'";
    	$query = $this->dbHandle->query($sql,array($universityIds, $universityScope));
    	$result = $query->result_array();

    	$affiliatedCourseIds = array();
    	foreach ($result as $key => $value) {
    		array_push($affiliatedCourseIds, $value['course_id']);
    	}	
    	return $affiliatedCourseIds;


    }
    
	function getAllCoursesForInstitutesFromFlatTable($instituteIds){

		if(empty($instituteIds)) return;
    	if(!is_array($instituteIds)) return;

    	$finalData = array();

    	$notFoundIds = array();
    	foreach ($instituteIds as $instituteId) {
    		if(empty($this->getAllCoursesForInstitutes[$instituteId])){
    			$notFoundIds[] = $instituteId;
    		}
    		else{
    			$finalData = array_merge($finalData,$this->getAllCoursesForInstitutes[$instituteId]);
    		}
    	}

    	if(!empty($notFoundIds)){
    		$this->initiateModel('read');
    		$sql = "SELECT 
    				sci.course_id,
    				sci.hierarchy_parent_id,
    				sci.primary_parent_id,
    				sci.primary_parent_type,
    				sci.primary_is_satellite,
    				si.listing_type,
    				si.institute_specification_type,
    				si.is_satellite,
    				si.is_dummy
    				FROM shiksha_courses_institutes sci
    				LEFT JOIN shiksha_institutes si 
    				ON si.listing_id = sci.primary_parent_id and si.status = 'live'
    			    WHERE hierarchy_parent_id IN (?) and sci.status='live'";
    		$query = $this->dbHandle->query($sql,array($notFoundIds));
    		$result = $query->result_array();

    		foreach ($result as $row) {
    			$data[$row['hierarchy_parent_id']][] = $row;
    		}
    		foreach ($data as $key => $value) {
    			$this->getAllCoursesForInstitutes[$key] = $value;
    			$finalData = array_merge($finalData,$value);
    		}
    	}

    	return $finalData;
	}

	function getAllInstitutesForInstitutesFromFlatTable($instituteIds, $excludeSatellite=true){
		if(empty($instituteIds)) return;
    	if(!is_array($instituteIds)) return;
    	$this->initiateModel('read');

    	$sql = "SELECT 
    			sci.hierarchy_parent_id,
    			sci.primary_parent_id,
    			si.is_satellite
    			FROM shiksha_courses_institutes sci
    			LEFT JOIN shiksha_institutes si 
    			ON si.listing_id = sci.primary_parent_id and si.status = 'live'
    		    WHERE hierarchy_parent_id IN (?) and sci.status='live' and sci.hierarchy_parent_id<>sci.primary_parent_id";

    	$query = $this->dbHandle->query($sql,array($instituteIds));
    	$result = $query->result_array();
    	return $result;
	}

	function getInstituteTypes($instituteIds){

		$result = array();
		if(empty($instituteIds))
			return $result;
		$this->initiateModel('read');
		$sql = "SELECT listing_id,institute_specification_type FROM `shiksha_institutes` WHERE listing_id IN (?) and status='live'";

		$query = $this->dbHandle->query($sql,array($instituteIds));
    	$result = $query->result_array();

    	foreach ($result as $value) {
    		$result[$value['listing_id']] = $value['institute_specification_type'];
    	}

    	return $result;
	}
	
function fetchPrimaryParent($courseId){
		// WRITE HANDLE TAKEN even in case of select as query on run time
		$this->initiateModel("write");
		$sql = "SELECT primary_id, primary_type FROM shiksha_courses WHERE course_id = ? AND status = 'live'";
		
		$query = $this->dbHandle->query($sql,array($courseId));
		$result = $query->row_array();
		if(!empty($result['primary_id'])){	
			return $result;
		}
		return null;
	}

	function updateFlatTableForRemoveMapping($instId){
		$this->initiateModel('write');
		if(empty($instId)) return;

		$this->dbHandle->trans_start();

		$sql = "UPDATE shiksha_courses_institutes
				set status = 'history'
				WHERE primary_parent_id  = ?
				AND status = 'live';";
		$query = $this->dbHandle->query($sql,array($instId));

		$sql = "SELECT DISTINCT primary_parent_id
				FROM shiksha_courses_institutes
				WHERE hierarchy_parent_id = ?
				AND status = 'live'";

		$query = $this->dbHandle->query($sql,array($instId));
		$result_array = $query->result_array();

		
		$finalResult = array();
       	foreach ($result_array as $key => $value) {
       		$finalResult[] = $value['primary_parent_id'];
       	}

       	if(!empty($finalResult)){
       		
       		$sql = "UPDATE shiksha_courses_institutes
       			SET status = 'history'
       			WHERE hierarchy_parent_id IN (?)
       			AND status = 'live'";
       		$query = $this->dbHandle->query($sql,array($finalResult));
       		
       		$sql = "UPDATE shiksha_courses_institutes
       			SET status = 'history'
       			WHERE primary_parent_id IN (?)
       			AND status = 'live'";
       		$query = $this->dbHandle->query($sql,array($finalResult));

       	}
       	
		
		$sql = "UPDATE shiksha_courses_institutes
				SET status = 'history'
				WHERE hierarchy_parent_id = ?
				AND status = 'live'";
		$query = $this->dbHandle->query($sql,array($instId));

		$this->dbHandle->trans_complete();
			
        if ($this->dbHandle->trans_status() === FALSE) {
         	return false;
       	}
       	 // $finalResult = array();
       	 // foreach ($result_array as $key => $value) {
       	 // 	$finalResult[] = $value['primary_parent_id'];
       	 // }

		return $finalResult;

	}

	function removeCourseMappingsData($courseId){
		if(empty($courseId)) return;
		$this->initiateModel("write");
		$sql = "UPDATE shiksha_courses_institutes SET status = 'history' where course_id = ?";
		$query = $this->dbHandle->query($sql,array($courseId));
	}

	function getListingData($instituteIds=null){
		if(empty($instituteIds)) return;
		$this->initiateModel("write");
		$sql = "SELECT listing_type,listing_id,is_satellite from shiksha_institutes where listing_id IN (?) and status = 'live'";

		$query = $this->dbHandle->query($sql,array($instituteIds));
		$result = $query->result_array();
		$finalResult = array();
		foreach ($result as $key => $value) {
			$finalResult[$value['listing_id']]['type'] = $value['listing_type'];
			$finalResult[$value['listing_id']]['is_satellite'] = $value['is_satellite'];
		}
		return $finalResult;
	}

	function generateDataForFlatTable(){
		$this->initiateModel('read');
		$sql = "SELECT shi.listing_id,shc.course_id,shi.listing_type,shi.name,shi.is_satellite FROM shiksha_institutes shi
				LEFT JOIN shiksha_courses shc 
				ON shi.listing_id = shc.primary_id AND shc.status = 'live'
				WHERE  shi.status = 'live'
				ORDER BY listing_id";

		$query = $this->dbHandle->query($sql);
		$result_array = $query->result_array();
		$finalResult = array();
		foreach ($result_array as $key => $value) {
			$finalResultA[$value['listing_id']."_".$value['listing_type']][] = $value['course_id'];
			$finalResultB[$value['listing_id']] = $value['name'];
			$finalResultC[$value['listing_id']] = $value['is_satellite'];
		}

		$finalResult = array($finalResultA,$finalResultB,$finalResultC);
		return $finalResult;
	}

	function insertIntoFlatTable($data){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch($this->flatTableName,$data);
	}

	function insertIntoFlatTableNew($data){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch("shiksha_courses_institutes_new",$data);
	}


	function updateIsSatelliteInFlatTable($instituteId=0,$is_satellite){
		if(empty($instituteId)) return;
		$this->initiateModel("write");
		if(empty($is_satellite)){
			$is_satellite = 0;
		}
		$sql = "UPDATE shiksha_courses_institutes
		SET primary_is_satellite = ?
		WHERE primary_parent_id = ?
		AND status = 'live'";
		$query = $this->dbHandle->query($sql,array($is_satellite,$instituteId));
	}

	function updateFlatTableForInstDelete($instId = null){
		if(empty($instId)){
			return;
		}
		$this->initiateModel('write');

		$this->dbHandle->trans_start();		
		if(!is_array($instId)){
			$instId = array($instId);
		}
		

		$sql = "UPDATE shiksha_courses_institutes 
				SET status = 'history'
				WHERE hierarchy_parent_id IN (?)
				AND status = 'live'";
		$query = $this->dbHandle->query($sql,array($instId));

		$sql = "UPDATE shiksha_courses_institutes 
				SET status = 'history'
				WHERE primary_parent_id IN (?)
				AND status = 'live'";
		$query = $this->dbHandle->query($sql,array($instId));

		$this->dbHandle->trans_complete();
			
        if ($this->dbHandle->trans_status() === FALSE) {
         	return false;
       	}

       return true;
	}


	function updateFlatTableForCourseDelete($courseId = null){
		if(empty($courseId)){
			return;
		}
		$this->initiateModel('write');
		if(!is_array($courseId)){
			$courseId = array($courseId);
		}
		$sql = "UPDATE shiksha_courses_institutes 
				SET status = 'history'
				WHERE course_id IN (?)
				AND status = 'live'";
				
		$query = $this->dbHandle->query($sql,array($courseId));
	}

	function getMultipleListingLocationData($listing_location_ids){
		if(empty($listing_location_ids))
			return;
		$this->initiateModel('read');

		$sql = "SELECT locality_id,city_id,state_id,listing_location_id FROM shiksha_institutes_locations where status = 'live' AND listing_location_id in (?) AND listing_type IN ('institute','university')";
		$result = $this->dbHandle->query($sql,array($listing_location_ids))->result_array();

		foreach ($result as $resultInfo) {
			$stateIds[$resultInfo['state_id']] = $resultInfo['state_id'];
			$cityIds[$resultInfo['city_id']] = $resultInfo['city_id'];
			if(!empty($resultInfo['locality_id'])){
				$localityIds[$resultInfo['locality_id']] = $resultInfo['locality_id'];
			}
		}

		$this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $this->locationRepo = $locationBuilder->getLocationRepository();

        if(!empty($stateIds)){
        	$stateObjs = $this->locationRepo->findMultipleStates(array_keys($stateIds));
        }
        if(!empty($cityIds)){
        	$cityObjs = $this->locationRepo->findMultipleCities(array_keys($cityIds));
        }
        if(!empty($localityIds)){
        	$localityObjs = $this->locationRepo->findMultipleLocalities($localityIds);
        }
        $returnData = array();
        foreach ($result as $row) {
        	$row['state_name'] = $stateObjs[$row['state_id']]->getName();
        	$row['city_name'] = $cityObjs[$row['city_id']]->getName();
        	if(!empty($row['locality_id'])){
        		$row['locality_name'] = $localityObjs[$row['locality_id']]->getName();
        	}
        	$returnData[$row['listing_location_id']] = $row;
        }
        return $returnData;
	}
	/**
	* @param : listing_location_id : return corresponding locality,city and state names for given listin_location_id
	*/
	function getListingLocationInfo($listing_location_id)
	{
		if(empty($listing_location_id))
			return;

		$this->initiateModel('read');

		$sql = "SELECT locality_id,city_id,state_id FROM shiksha_institutes_locations where status = 'live' AND listing_location_id = ? AND listing_type IN ('institute','university')";
		$result = $this->dbHandle->query($sql,array($listing_location_id))->result_array();
		foreach ($result as $resultInfo) {
			$state_id    = $resultInfo['state_id'];
			$city_id     = $resultInfo['city_id'];
			$locality_id = $resultInfo['locality_id'];
		}

		$returnArray = array();
		$this->load->builder('LocationBuilder','location');
        $locationBuilder                                = new LocationBuilder;
        $this->locationRepo                       = $locationBuilder->getLocationRepository();
		if(!empty($state_id))
		{
			$stateObj = $this->locationRepo->findState($state_id);
			$returnArray['state_name'] =$stateObj->getName();	
			/*$sql = "SELECT state_name FROM stateTable WHERE state_id = ? limit 1";
			$result = $this->dbHandle->query($sql,array($state_id))->result_array();
			$returnArray['state_name'] =$result[0]['state_name'];*/
		}

		if(!empty($city_id))
		{
			$cityObj = $this->locationRepo->findCity($city_id);
			$returnArray['city_name'] =$cityObj->getName();
			/*$sql = "SELECT city_name FROM countryCityTable WHERE city_id = ? limit 1";
			$result = $this->dbHandle->query($sql,array($city_id))->result_array();
			$returnArray['city_name'] =$result[0]['city_name'];*/
		}

		if(!empty($locality_id))
		{
			$localityObj = $this->locationRepo->findLocality($locality_id);
			$returnArray['locality_name'] =$localityObj->getName();
			/*$sql = "SELECT localityName FROM localityCityMapping WHERE localityId = ? limit 1";
			$result = $this->dbHandle->query($sql,array($locality_id))->result_array();
			$returnArray['locality_name'] =$result[0]['localityName'];*/
		}
		return $returnArray;
	}
	function getQuestionCountForListing($listingIds,$contentType = 'question')
	{
		if(empty($listingIds) && count($listingIds) == 0)
		{
			return 0;
		}
		if(!is_array($listingIds) && $listingIds != '' && $listingIds > 0){
			$listingIds = array($listingIds);
		}

		$listingIds = array_filter($listingIds);
		if(count($listingIds) <= 0 || !is_array($listingIds)){
			return 0;
		}

		$this->initiateModel('read');

		$sql = "SELECT count(distinct a.msgId) as c FROM AnARecommendation a INNER JOIN messageTable m ON a.msgId = m.msgId WHERE instituteId IN (?) AND contentType = ? AND m.status in ('live','closed')";

		$result = $this->dbHandle->query($sql,array($listingIds,$contentType))->result_array();
		return $result[0]['c'];
	}
	function getReviewCountForListing($listingIds, $instituteId)
	{

		if(empty($listingIds) && count($listingIds) == 0)
		{
			return 0;
		}
		if(!is_array($listingIds) && $listingIds != '' && $listingIds > 0){
			$listingIds = array($listingIds);
		}

		$listingIds = array_filter($listingIds);
		if(count($listingIds) <= 0 || !is_array($listingIds)){
			return 0;
		}

		$instituteQuery = "";
		$inputSqlArray = array();
		if(!empty($instituteId) && !is_array($instituteId) && $instituteId > 0){
			$instituteQuery = "reviewInstituteId = ? AND";
			$inputSqlArray[] = $instituteId;
		}

		$this->initiateModel('read');
		/**$sql = "SELECT count(reviewId) as c FROM (CollegeReview_MappingToShikshaInstitute) JOIN CollegeReview_MainTable ON CollegeReview_MappingToShikshaInstitute.reviewId=CollegeReview_MainTable.id WHERE courseId in (?) AND status = 'published'";*/
		$sql = "SELECT count(id) as c FROM CollegeReview_MainTable USE INDEX (idx_instituteCourseStatus) WHERE ".$instituteQuery." reviewCourseId in (?) AND status = 'published'";
		$inputSqlArray[] = $listingIds;

		$result = $this->dbHandle->query($sql,$inputSqlArray)->result_array();
		return $result[0]['c'];
	}
	function getArticleCoutForListing($listingIds)
	{
		if(empty($listingIds) && count($listingIds) == 0)
		{
			return 0;
		}
		if(!is_array($listingIds) && $listingIds != '' && $listingIds > 0){
			$listingIds = array($listingIds);
		}

		$listingIds = array_filter($listingIds);
		if(count($listingIds) <= 0 || !is_array($listingIds)){
			return 0;
		}

		$this->initiateModel('read');

		$sql = "SELECT count(`blogTable`.`blogId`) as c FROM (`articleAttributeMapping`) JOIN `blogTable` ON `articleAttributeMapping`.`articleId`=`blogTable`.`blogId` WHERE `entityId` in (?) AND `articleAttributeMapping`.`status` = 'live' AND `entityType` in ('group','college','university') AND `blogTable`.`status` = 'live' AND `blogType` not in ('exam','examstudyabroad')";

		$result = $this->dbHandle->query($sql,array($listingIds))->result_array();
		return $result[0]['c'];
	}

	public function getInstituteCoursesByFilters($instituteIds, $filterEntityIds) {
        if(empty($instituteIds)) {
            return;
        }

        $this->initiateModel('read');

        $this->dbHandle->distinct();

        $this->dbHandle->select('si.hierarchy_parent_id as institute_id, sc.course_id');

        $this->dbHandle->from('shiksha_courses sc');
        $this->dbHandle->join('shiksha_courses_institutes as si', "si.course_id = sc.course_id and si.status = 'live' ");

        if(!empty($filterEntityIds['streamIds']) || 
           !empty($filterEntityIds['substreamIds']) || 
           !empty($filterEntityIds['specializationIds']) || 
           !empty($filterEntityIds['course']) || 
           !empty($filterEntityIds['credential']) ) {
        	$this->dbHandle->join('shiksha_courses_type_information as st', "sc.course_id = st.course_id and st.status = 'live' and st.type = 'entry' ");
        }

        $this->dbHandle->where_in("si.hierarchy_parent_id", $instituteIds);

        $this->dbHandle->where('sc.status', 'live');

        if(!empty($filterEntityIds['streamIds'])) {
        	$this->dbHandle->where_in("st.stream_id", $filterEntityIds['streamIds']);
        }
        if(!empty($filterEntityIds['substreamIds'])) {
        	$this->dbHandle->where_in("st.substream_id", $filterEntityIds['substreamIds']);
        }
        if(!empty($filterEntityIds['specializationIds'])) {
        	$this->dbHandle->where_in("st.specialization_id", $filterEntityIds['specializationIds']);
        }
        if(!empty($filterEntityIds['course'])) {
        	$this->dbHandle->where_in("st.base_course", $filterEntityIds['course']);
        }
        if(!empty($filterEntityIds['credential'])) {
        	$this->dbHandle->where_in("st.credential", $filterEntityIds['credential']);
        }
        if(!empty($filterEntityIds['education_type'])) {
        	$this->dbHandle->where_in("sc.education_type", $filterEntityIds['education_type']);
        }
        if(!empty($filterEntityIds['delivery_method'])) {
        	$this->dbHandle->where_in("sc.delivery_method", $filterEntityIds['delivery_method']);
        }

        $result = $this->dbHandle->get()->result_array();
        
        return $result;
    }

    public function getInstitutesFromBaseCourses($baseCourseIds, $limit) {
    	if(empty($baseCourseIds)) {
            return;
        }

        $this->initiateModel('read');

        $sql = "SELECT DISTINCT institute_id FROM ShikshaPopularity_MainTable p, shiksha_institutes i ".
                           "WHERE attribute_type = 'base_course' AND p.status = 'live' AND attribute_id in (?) ".
			   "AND i.status = 'live' AND p.institute_id = i.listing_id ".
                           "ORDER BY popularity_score DESC ".
                           "LIMIT ?";

		$result = $this->dbHandle->query($sql, array($baseCourseIds, (int) $limit))->result_array();

		return $result;
    }
    public function getCountOfNaukriEmployeesDataForInstitute($instituteId,$numberOfInstitutes)
    {
    	$this->initiateModel('read');
        if($numberOfInstitutes =='single'){
            $sql = "SELECT  institute_id,sum(total_emp) as total_emp  FROM naukri_alumni_stats WHERE institute_id = ?";   
            $results = $this->db->query($sql, $instituteId)->result_array();
        }
        else if($numberOfInstitutes =='multiple'){
            $sql = "SELECT  institute_id,sum(total_emp) as total_emp  FROM naukri_alumni_stats WHERE institute_id IN (?) group by institute_id";
            $results = $this->db->query($sql,array($instituteId))->result_array();
        }
        return $results;
    }

    function getMultipleListingLocationInfo($listing_location_ids = array())
	{
		if(empty($listing_location_ids))
			return;

		$this->initiateModel('read');

		$sql = "SELECT listing_location_id,locality_id,city_id,state_id FROM shiksha_institutes_locations where status = 'live' AND listing_location_id in (?) AND listing_type IN ('institute','university')";
		$result = $this->dbHandle->query($sql,array($listing_location_ids))->result_array();

		return $result;
	}

	public function isPaidInstitute($instituteIds){
		$this->initiateModel('read');

		if(empty($instituteIds)){
			return array();
		}

		$sql = "SELECT distinct sc.primary_id from shiksha_courses sc join listings_main lm on lm.listing_type_id = sc.course_id and sc.status='live' and lm.status='live' and lm.listing_type = 'course' and lm.pack_type in (1, 2, 375) and sc.primary_id in (?)";
		$dbData = $this->dbHandle->query($sql,array($instituteIds))->result_array();
		$paidInstitutes = array();
		foreach ($dbData as $row) {
			$paidInstitutes[$row['primary_id']] = $row['primary_id'];
		}

		$returnData = array();
		foreach ($instituteIds as $instituteId) {
			if(!empty($paidInstitutes[$instituteId])){
				$returnData[$instituteId] = true;
			}
			else{
				$returnData[$instituteId] = false;
			}
		}
		return $returnData;
	}

	public function getAllInstitute(){
		$this->initiateModel('read');
		//$sql = "SELECT listing_id FROM shiksha_institutes WHERE parent_listing_id is null and status = 'live' and is_dummy = 0";
		$sql = "SELECT listing_id FROM shiksha_institutes WHERE status = 'live' and is_dummy = 0";
		$result = $this->dbHandle->query($sql)->result_array();
		$instituteIds = array();
		foreach ($result as $key => $value) {
			$instituteIds[] = $value['listing_id'];
		}
		return $instituteIds;
	}

	public function getAllPrimaryInstitutes(){
		$this->initiateModel('read');
		//$sql = "SELECT listing_id FROM shiksha_institutes WHERE parent_listing_id is null and status = 'live' and is_dummy = 0";
		$sql = "SELECT DISTINCT primary_id FROM shiksha_courses WHERE status = 'live'";
		$result = $this->dbHandle->query($sql)->result_array();
		$instituteIds = array();
		foreach ($result as $key => $value) {
			$instituteIds[] = $value['primary_id'];
		}
		return $instituteIds;
	}

	function getPaidCourses($courseIds){
		if(empty($courseIds)){
			return array();
		}
		$this->initiateModel('read');
		$sql = "SELECT listing_type_id FROM listings_main WHERE listing_type =  'course' AND status = 'live' AND listing_type_id IN (?) AND pack_type in (1, 2, 375)";
		$result = $this->dbHandle->query($sql,array($courseIds))->result_array();
		$courseIds = array();
		foreach ($result as $key => $value) {
			$courseIds[$value['listing_type_id']] = $value['listing_type_id'];
		}
		return $courseIds;
	}

	function updateInstitutePaidStatus($isPaid = 0, $instituteIds){
		if(empty($instituteIds)){
			return array();
		}
		$this->initiateModel('write');
		$sql = "UPDATE shiksha_institutes SET is_hierarchy_paid = ? WHERE listing_id IN (?) and status = 'live'";
		$this->dbHandle->query($sql, array($isPaid, $instituteIds));
	}

	function updateInstituteSubscriptionStatus($isPaid = 0, $instituteIds){
		if(empty($instituteIds)){
			return array();
		}
		$this->initiateModel('write');
		$sql = "UPDATE shiksha_institutes SET is_institute_paid = ? WHERE listing_id IN (?) and status = 'live'";
		$this->dbHandle->query($sql, array($isPaid, $instituteIds));
	}

	function getCourseByCustomExamName($customEPName){
		if(empty($customEPName)){
			return array();
		}
		$this->initiateModel('read');
		$courseIds = array();

		$sql = "SELECT course_id FROM shiksha_courses_exams_cut_off WHERE custom_exam IN (?) AND status = 'live'";
		$result1 = $this->dbHandle->query($sql,array($customEPName))->result_array();
		foreach ($result1 as $key => $value) {
			$courseIds[] = $value['course_id'];
		}

		$sql= "SELECT course_id FROM shiksha_courses_seats_breakup WHERE custom_exam_name IN (?) AND status = 'live'";
		$result2 = $this->dbHandle->query($sql,array($customEPName))->result_array();
		foreach ($result2 as $key => $value) {
			$courseIds[] = $value['course_id'];
		}

	$sql="SELECT course_id FROM shiksha_courses_eligibility_exam_score WHERE exam_name IN (?) AND status = 'live'";
		$result3 = $this->dbHandle->query($sql,array($customEPName))->result_array();
		foreach ($result3 as $key => $value) {
			$courseIds[] = $value['course_id'];
		}

		return $courseIds;
	}

	function updateCustomExamNameWithExamId($examId, $customExamName){
		if(empty($examId) || empty($customExamName)){
			return array();
		}
		$this->initiateModel('write');
		
		$sql = "UPDATE shiksha_courses_exams_cut_off SET exam_id = ?, custom_exam = NULL  WHERE custom_exam = ? and status = 'live'";
		$this->dbHandle->query($sql, array($examId, $customExamName));

		$sql = "UPDATE shiksha_courses_seats_breakup SET exam_id = ?, custom_exam_name = NULL  WHERE custom_exam_name = ? and status = 'live'";
		$this->dbHandle->query($sql, array($examId, $customExamName));

		$sql = "UPDATE shiksha_courses_eligibility_exam_score SET exam_id = ?, exam_name = NULL  WHERE exam_name = ? and status = 'live'";
		$this->dbHandle->query($sql, array($examId, $customExamName));
	}

	public function getAllinstitutes(){
       $sql="Select distinct listing_id, listing_type from shiksha_institutes where is_dummy=0 and status='live'";
       return $this->db->query($sql)->result_array();
	}

	public function getInstituteNameById($instituteId){
		if(empty($instituteId)){
			return;
		}

		$this->initiateModel('read','User');

		$sql = "SELECT name from shiksha_institutes where listing_id = ? and status='live'";
		$results = $this->dbHandle->query($sql, array($instituteId))->result_array();

		return $results;
	}

	public function getAllInstituteBySameName($name){
		if(empty($name)){
			return;
		}

		$this->initiateModel('read','User');

		$sql = "SELECT listing_id from shiksha_institutes WHERE name=? and status = 'live'";
		$result = $this->dbHandle->query($sql,$name)->result_array();

		return $result;
	}

	public function getOrderOneCourses($allCourses){

		$sql = "SELECT distinct course_id  FROM shiksha_courses  WHERE course_order = 1 AND status = 'live' AND course_id IN (?)";
		$result = $this->dbHandle->query($sql,array($allCourses))->result_array();
		$returnData = array();
		foreach ($result as $key => $value) {
			$returnData[]=$value['course_id'];
		}
		return $returnData;
	}

	public function getDirectCoursesForInstitute($instituteId , $listingLocationId){
		$this->initiateModel('read');
		$sql = "SELECT DISTINCT sc.course_id FROM shiksha_courses as sc JOIN shiksha_courses_locations  as scl ON sc.course_id = scl.course_id AND scl.listing_location_id = ? AND sc.primary_id=? AND sc.status= 'live'";

		$result = $this->dbHandle->query($sql,array($listingLocationId,$instituteId))->result_array();
		$returnData = array();
		foreach ($result as $key => $value) {
			$returnData[]=$value['course_id'];
		}
		return $returnData;
	}

	//checks if naukri placement data exists for a listing id
	public function checkIfNaukriDataExistsForInstitute($listingId){
		$this->initiateModel('read');
		$sql = "SELECT COUNT(*) AS c FROM naukri_salary_data_new WHERE shiksha_inst_id = ? GROUP BY shiksha_inst_id";
		$result = $this->dbHandle->query($sql,array($listingId))->result_array();
		return $result[0]['c'];
	}

	function getAffiliatedInstitutesForUniversity($universityIds, $universityScope){

    	if(empty($universityIds)) return;
    	if(!is_array($universityIds)) return;

    	$this->initiateModel('read');

    	$result = array();
    	$sql = "SELECT DISTINCT(primary_id) FROM shiksha_courses WHERE affiliated_university_id IN (?) AND affiliated_university_scope = ? AND status = 'live'";
    	$query = $this->dbHandle->query($sql,array($universityIds, $universityScope));
    	$result = $query->result_array();

    	$affiliatedInstituteIds = array();
    	foreach ($result as $key => $value) {
    		array_push($affiliatedInstituteIds, $value['primary_id']);
    	}	
    	return $affiliatedInstituteIds;
    }

    function getAllInstitutesInHierarchy($universityIds){

    	if(empty($universityIds)) return;
    	if(!is_array($universityIds)) return;

    	$this->initiateModel('read');

    	$result = array();
    	$sql = "SELECT DISTINCT(primary_parent_id) FROM shiksha_courses_institutes WHERE hierarchy_parent_id IN (?) AND status = 'live'";
    	$query = $this->dbHandle->query($sql,array($universityIds));
    	$result = $query->result_array();

    	$allInstituteIds = array();
    	foreach ($result as $key => $value) {
    		array_push($allInstituteIds, $value['primary_parent_id']);
    	}	
    	return $allInstituteIds;
    }
    
    public function getInstitutePaidStatus($instituteId){
        if(empty($instituteId)){
            return false;
        }
        $this->initiateModel('read');
        $sql = "Select is_hierarchy_paid  from shiksha_institutes  where listing_id  = ? and status ='live'";
        return $this->dbHandle->query($sql, array($instituteId))->result_array();
    }

    public function updateShikshaInstitute($data, $listingId){

    	$this->initiateModel('write');
    	$this->db->where('listing_id', $listingId);
        $this->db->where('status', 'live');
        $this->db->update('shiksha_institutes', $data);
    }

} ?>
