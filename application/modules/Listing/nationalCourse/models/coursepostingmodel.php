<?php

class coursepostingmodel extends MY_Model {
	function __construct() {
		parent::__construct('Listing');
        $this->load->config('nationalCourse/courseConfig');
        $this->instituteFlatTableLib = $this->load->library('nationalInstitute/InstituteFlatTableLibrary');
    }

    private function initiateModel($mode = "write") {
		if($this->dbHandle && $this->dbHandleMode == 'write') {
		    return;
		}
        
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }

    public function saveCourse($courseData){
        $status = $courseData['saveAs'];
        $this->initiateModel('write');
        $this->dbHandle->trans_start();

        $logTraceMessage = "Data : ".json_encode($courseData)."<br/><br/> Current Status : ".$status.". <br/>";

        $listingMainData = array();
        $isFirstPublish = false;

        $courseInfo = $courseData['courseInfo'];

        if(!empty($courseData['isDisabled'])){
            $courseInfo['disabled_url'] = $courseData['isDisabled'];
        }

        if($courseData['mode'] == 'add'){
            // Add Condition Start
            $courseId                          = $this->_getNewCourseId();
            // $listingMainData['username']       = $courseData['userId'];
            // $listingMainData['pack_type']      = 0;
            // $listingMainData['SubscriptionId'] = 0;
            if($status == 'live'){
                $isFirstPublish = true;
            }
            $instituteData = $this->getListingsMainData($courseInfo['primary_id'],$courseInfo['primary_type']);
            if($instituteData['clientData']['usergroup'] == 'cms' || $instituteData['clientData']['usergroup'] == 'listingAdmin'){
                $listingMainData['username'] = $courseData['clientId'];
            }
            else{
                $listingMainData['username'] = $instituteData['username'];
            }

            $listingMainData['pack_type']               = $courseData['packType'];
            $listingMainData['subscriptionId']          = $courseData['subscriptionId'];
            // Add Condition End
        }
        else{
            // Edit Condition Start
            $courseId = $courseData['courseId'];
            if(empty($courseId)){
                return false;
            }

            $currentData = $this->getCurrentCourseData($courseId);
            if(empty($currentData['courseData'])){
                return false;
            }

            $isFirstPublish = $this->isCoursePublishedForFirstTime($courseId);

            $courseInfo['course_order'] = $currentData['courseData']['course_order'];

            $listingMainData['expiry_date']    = $currentData['listingMainData']['expiry_date'];
            $listingMainData['username']       = $currentData['listingMainData']['username'];
            $listingMainData['pack_type']      = $currentData['listingMainData']['pack_type'];
            $listingMainData['viewCount']      = $currentData['listingMainData']['viewCount'];
            $listingMainData['subscriptionId'] = $currentData['listingMainData']['subscriptionId'];
            $listingMainData['submit_date']    = $currentData['listingMainData']['submit_date'];

            if($status == 'draft'){
                if($currentData['courseData']['status'] == 'draft'){
                    $logTraceMessage .= ' Marking draft entries to history';
                    $this->updateListingStatus($courseId,array('draft'),'history');
                }
            }
            else if($status == 'live'){
                $logTraceMessage .= ' Marking draft and live entries to history.';
                $this->updateListingStatus($courseId,array('draft','live'),'history');
            }
            // Edit Condition End
        }

        // Logging for duplicate entries
        $noOfRowsReturn = $this->_logCourseDuplicateLiveEntries($courseId);
        if($noOfRowsReturn > 0 && $status != 'draft'){
            throw new Exception("Duplicate entry for course Id ".$courseId.". ".$logTraceMessage);            
        }
        
        $listingMainData['listing_type_id']         = $courseId;
        $listingMainData['listing_type']            = 'course';
        $listingMainData['listing_title']           = $courseData['courseInfo']['name'];
        $listingMainData['last_modify_date']        = date("Y-m-d H:i:s");
        $listingMainStatus                          = $this->config->item('listingMainStatusForCourse');
        $listingMainData['status']                  = $listingMainStatus[$status];
        $listingMainData['editedBy']                = $courseData['userId'];
        
        if($courseData['mode'] == 'add') {
            $listingMainData['listing_seo_url']         = $courseData['listing_seo_url'].'-'.$courseId;
        }
        else {
            $listingMainData['listing_seo_url']         = $courseData['listing_seo_url'];
        }
        
        $listingMainData['listing_seo_title']       = $courseData['listing_seo_title'];
        $listingMainData['listing_seo_description'] = $courseData['listing_seo_description'];

        $this->dbHandle->insert('listings_main',$listingMainData);

        $courseInfo['course_id']  = $courseId;
        if($courseData['mode'] == 'add'){
            $courseInfo['created_by'] = $courseData['userId'];
            $courseInfo['created_on'] = date("Y-m-d H:i:s");
        }
        else{
            $courseInfo['created_by'] = $currentData['courseData']['created_by'];
            $courseInfo['created_on'] = $currentData['courseData']['created_on'];
        }
        $courseInfo['updated_by'] = $courseData['userId'];
        $courseInfo['updated_on'] = date("Y-m-d H:i:s");
        $courseInfo['status']     = $status;

        $this->dbHandle->insert('shiksha_courses',$courseInfo);


        $courseAdditionalInfo = $courseData['courseAdditionalInfo'];
        foreach ($courseAdditionalInfo as $key => $value) {
            $courseAdditionalInfo[$key]['course_id']  = $courseId;
            $courseAdditionalInfo[$key]['status']     = $status;
            $courseAdditionalInfo[$key]['updated_by'] = $courseData['userId'];
        }

        if(count($courseAdditionalInfo) > 0){
            $this->dbHandle->insert_batch('shiksha_courses_additional_info',$courseAdditionalInfo);
        }

        $courseTypeInfo = $courseData['courseTypeInfo'];
        foreach ($courseTypeInfo as $key => $value) {
            $courseTypeInfo[$key]['course_id']  = $courseId;
            $courseTypeInfo[$key]['status']     = $status;
            $courseTypeInfo[$key]['updated_by'] = $courseData['userId'];
        }
        if(!empty($courseTypeInfo)){
            $this->dbHandle->insert_batch('shiksha_courses_type_information',$courseTypeInfo);
        }

        $eligibility = $courseData['eligibility'];
        foreach ($eligibility['scoreData'] as $key => $value) {
            $eligibility['scoreData'][$key]['course_id']  = $courseId;
            $eligibility['scoreData'][$key]['status']     = $status;
            $eligibility['scoreData'][$key]['updated_by'] = $courseData['userId'];
        }

        if(!empty($eligibility['scoreData'])){
            $this->dbHandle->insert_batch('shiksha_courses_eligibility_score',$eligibility['scoreData']);
        }

        foreach ($eligibility['entityData'] as $key => $value) {
            $eligibility['entityData'][$key]['course_id']  = $courseId;
            $eligibility['entityData'][$key]['status']     = $status;
            $eligibility['entityData'][$key]['updated_by'] = $courseData['userId'];
        }

        if(!empty($eligibility['entityData'])){
            $this->dbHandle->insert_batch('shiksha_courses_eligibility_base_entities',$eligibility['entityData']);
        }

        foreach ($eligibility['examScoreData'] as $key => $value) {
            $eligibility['examScoreData'][$key]['course_id']  = $courseId;
            $eligibility['examScoreData'][$key]['status']     = $status;
            $eligibility['examScoreData'][$key]['updated_by'] = $courseData['userId'];
        }

        if(!empty($eligibility['examScoreData'])){
            $this->dbHandle->insert_batch('shiksha_courses_eligibility_exam_score',$eligibility['examScoreData']);
        }

        if(!empty($eligibility['mainData'])){
            $eligibility['mainData']['course_id']  = $courseId;
            $eligibility['mainData']['status']     = $status;
            $eligibility['mainData']['updated_by'] = $courseData['userId'];

            $this->dbHandle->insert('shiksha_courses_eligibility_main',$eligibility['mainData']);
        }

        $partnerDetails = array();
        $courseData['partnerDetails'];
        $partnerCount = 0;
        foreach ($courseData['partnerDetails']['partnerInstituteFormArr'] as $key => $partner) {
            $partnerDetails[$key] = $partner;
            unset($partnerDetails[$key]['partner_name']);
            $partnerDetails[$key]['course_id']    = $courseId;
            $partnerDetails[$key]['type']         = 'entry';
            $partnerDetails[$key]['status']       = $status;
            $partnerDetails[$key]['updated_by']   = $courseData['userId'];
            $partnerCount++;
        }
        
        // $partnerDetails = $courseData['partnerDetails'];
        foreach ($courseData['partnerDetails']['partnerInstituteFormArrExit'] as $key => $partner) {
            $partnerDetails[$partnerCount] = $partner;
            unset($partnerDetails[$partnerCount]['partner_name']);
            $partnerDetails[$partnerCount]['course_id']    = $courseId;
            $partnerDetails[$partnerCount]['type']         = 'exit';
            $partnerDetails[$partnerCount]['status']       = $status;
            $partnerDetails[$partnerCount]['updated_by']   = $courseData['userId'];
            $partnerCount++;
        }

        if(!empty($partnerDetails)){
            $this->dbHandle->insert_batch('shiksha_courses_partner_details',$partnerDetails);
        }

        $courseFees = $courseData['courseFees'];
        foreach ($courseFees as $key => $fees) {
            $courseFees[$key]['course_id']  = $courseId;
            $courseFees[$key]['status']     = $status;
            $courseFees[$key]['updated_by'] = $courseData['userId'];
        }

        // _p($courseFees); die;
        if(!empty($courseFees)){
            foreach ($courseFees as $key => $feesData) {
                $this->dbHandle->insert('shiksha_courses_fees',$feesData);
            }
        }

        $courseAdmissionProcess = $courseData['courseAdmissionProcess'];
        foreach ($courseAdmissionProcess as $key => $val) {
            $courseAdmissionProcess[$key]['course_id']  = $courseId;
            $courseAdmissionProcess[$key]['status']     = $status;
            $courseAdmissionProcess[$key]['updated_by'] = $courseData['userId'];
        }

        if(!empty($courseAdmissionProcess)){
            $this->dbHandle->insert_batch('shiksha_courses_admission_process',$courseAdmissionProcess);
        }

        $courseExamsCutOff = array();
        foreach ($courseData['examsCutOff'] as $key => $cutOffData) {
            $courseExamsCutOff[$key]               = $cutOffData;
            $courseExamsCutOff[$key]['course_id']  = $courseId;
            $courseExamsCutOff[$key]['status']     = $status;
            $courseExamsCutOff[$key]['updated_by'] = $courseData['userId'];
        }
        foreach ($courseData['course12thCutOff'] as $key => $cutOffData) {
            $tempCutOffData = array();
            $tempCutOffData               = $cutOffData;
            $tempCutOffData['course_id']  = $courseId;
            $tempCutOffData['status']     = $status;
            $tempCutOffData['updated_by'] = $courseData['userId'];
            $courseExamsCutOff[] = $tempCutOffData;
        }
        // _p($courseExamsCutOff); die;
        if(!empty($courseExamsCutOff)) {
            $this->dbHandle->insert_batch('shiksha_courses_exams_cut_off',$courseExamsCutOff);
        }

        $courseUsp = $courseData['courseUsp'];
        foreach ($courseUsp as $key => $val) {
            $courseUsp[$key]['course_id']  = $courseId;
            $courseUsp[$key]['status']     = $status;
            $courseUsp[$key]['updated_by'] = $courseData['userId'];
        }
        if(!empty($courseUsp)){
            $this->dbHandle->insert_batch('shiksha_courses_additional_info',$courseUsp);
        }

        $coursePlacements = $courseData['coursePlacements'];
        if(!empty($coursePlacements)){
            $coursePlacements['course_id']  = $courseId;
            if($coursePlacements['course_type'] == 'clientCourse'){
                $coursePlacements['course'] = $courseId;
            }
            $coursePlacements['status']     = $status;
            $coursePlacements['updated_by'] = $courseData['userId'];              
            $this->dbHandle->insert('shiksha_courses_placements_internships',$coursePlacements);
        }

        $courseInternship = $courseData['courseInternship'];
        if(!empty($courseInternship)){
            $courseInternship['course_id']  = $courseId;
            $courseInternship['status']     = $status;
            $courseInternship['updated_by'] = $courseData['userId'];
            $this->dbHandle->insert('shiksha_courses_placements_internships',$courseInternship);
        }

        $courseStructureInfo = $courseData['courseStructureInfo'];
        if(!empty($courseStructureInfo)){
            foreach ($courseStructureInfo as $index => $row) {
                $courseStructureInfo[$index]['course_id']  = $courseId;
                $courseStructureInfo[$index]['status']     = $status;
                $courseStructureInfo[$index]['updated_by'] = $courseData['userId'];
            }
            $this->dbHandle->insert_batch('shiksha_courses_structure_offered_courses',$courseStructureInfo);
        }

        $importantDates = $courseData['importantDates'];
        if(!empty($importantDates)){
            foreach ($importantDates as $index => $row) {
                $importantDates[$index]['course_id']  = $courseId;
                $importantDates[$index]['status']     = $status;
                $importantDates[$index]['updated_by'] = $courseData['userId'];
            }
            $this->dbHandle->insert_batch('shiksha_courses_important_dates',$importantDates);
        }

        $tableName = 'shiksha_listings_brochures';
        if(!empty($courseData['courseBrochureForm'])){
            $brochureData                  = array();
            $brochureData['listing_id']    = $courseId;
            $brochureData['listing_type']  = 'course';
            $brochureData['cta']           = 'brochure';
            $brochureData['brochure_url']  = $courseData['courseBrochureForm']['brochure_url'];
            $brochureData['brochure_year'] = $courseData['courseBrochureForm']['brochure_year'];
            $brochureData['brochure_size'] = $courseData['courseBrochureForm']['brochure_size']; // LF-4944
            $brochureData['updated_by']    = $courseData['userId'];
            $brochureData['status']        = $status;
            $this->dbHandle->insert($tableName, $brochureData);
        }
     
        $courseSeats = $courseData['courseSeats'];
        if(!empty($courseSeats)){
            foreach ($courseSeats as $index => $row) {
                $courseSeats[$index]['course_id']  = $courseId;
                $courseSeats[$index]['status']     = $status;
                $courseSeats[$index]['updated_by'] = $courseData['userId'];
            }
        }

        if(!empty($courseSeats)){
            foreach ($courseSeats as $key => $seatsData) {
                $this->dbHandle->insert('shiksha_courses_seats_breakup',$seatsData);
            }
        }

        $courseLocation = $courseData['courseLocation']['courseLocationData'];
        if(!empty($courseLocation)){
            foreach ($courseLocation as $index => $row) {
                $courseLocation[$index]['course_id']  = $courseId;
                $courseLocation[$index]['status']     = $status;
                $courseLocation[$index]['updated_by'] = $courseData['userId'];
            }
            $this->dbHandle->insert_batch('shiksha_courses_locations',$courseLocation);
        }

        $courseLocationFees = $courseData['courseLocation']['courseLocationFeesData'];
        if(!empty($courseLocationFees)){
            foreach ($courseLocationFees as $index => $row) {
                $courseLocationFees[$index]['course_id']  = $courseId;
                $courseLocationFees[$index]['status']     = $status;
                $courseLocationFees[$index]['updated_by'] = $courseData['userId'];
            }
            $this->dbHandle->insert_batch('shiksha_courses_fees',$courseLocationFees);
        }

        $courseLocationContactDetails = $courseData['courseLocation']['courseLocationContactDetails'];
        if(!empty($courseLocationContactDetails)){
            foreach ($courseLocationContactDetails as $index => $row) {
                $courseLocationContactDetails[$index]['listing_id'] = $courseId;
                $courseLocationContactDetails[$index]['status']     = $status;
                $courseLocationContactDetails[$index]['updated_by'] = $courseData['userId'];
            }
            $this->dbHandle->insert_batch('shiksha_listings_contacts',$courseLocationContactDetails);
        }

        $courseCompaniesMapping = $courseData['courseCompaniesMapping'];
        if(!empty($courseCompaniesMapping)){
            foreach ($courseCompaniesMapping as $index => $row) {
                $courseCompaniesMapping[$index]['course_id']  = $courseId;
                $courseCompaniesMapping[$index]['status']     = $status;
                $courseCompaniesMapping[$index]['updated_by'] = $courseData['userId'];
            }
            $this->dbHandle->insert_batch('shiksha_courses_companies_mapping',$courseCompaniesMapping);
        }

        $courseMediasMapping = $courseData['courseMediasMapping'];
        if(!empty($courseMediasMapping)){
            foreach ($courseMediasMapping as $index => $row) {
                $courseMediasMapping[$index]['course_id']  = $courseId;
                $courseMediasMapping[$index]['status']     = $status;
                $courseMediasMapping[$index]['updated_by'] = $courseData['userId'];
            }
            $this->dbHandle->insert_batch('shiksha_courses_medias_mapping',$courseMediasMapping);
        }

        if(!empty($courseData['sectionsChanged']) && $courseData['mode'] == 'edit'){
            $this->trackListingPostingChanges(array('sectionsChanged'=>$courseData['sectionsChanged'],'courseId'=>$courseId,'userId'=>$courseData['userId'],'extraData'=>$courseData['sectionsExtraData']));
        }

        // save the posting comments
        $postingCommentData = array();
        $postingCommentData['comments']     = $courseData['posting_comments'];
        $postingCommentData['userId']       = $courseData['userId'];
        $postingCommentData['listingId']    = $courseId;
        $postingCommentData['tabUpdated']   = 'course';
        $this->dbHandle->insert('listingCMSUserTracking',$postingCommentData);
    

        if($status == 'live'){
            if($isFirstPublish){
                // update institute courses if publish for first time or primary parent is changed
                $this->instituteFlatTableLib->flatTableInstituteUpdate($courseInfo['primary_id']);
            }
            if(empty($courseData['isDisabled'])){
                if($isFirstPublish){
                    // full index
                    $this->addCourseToIndexLog(array('courseId'=>$courseId,'indexingType'=>'fullIndex','operation'=>'index'));
                }
                else{
                    // partial index
                    $this->addCourseToIndexLog(array('courseId'=>$courseId,'indexingType'=>'partialIndex','operation'=>'index','sectionData'=>$courseData['sectionsExtraData']));
                }
            }
        }

        //Also, if the Primary Id has been changed, we need to change the Review data
        if($courseData['mode'] != 'add'){
            if($currentData['courseData']['primary_id'] != $courseInfo['primary_id']){
                if(isset($courseData['courseLocation']['courseLocationData'][0]['listing_location_id']) && isset($courseInfo['primary_id'])){
                    $this->CollegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
                    $this->CollegeReviewLib->updateInstituteIdAndLocationForReview($courseId, $courseInfo['primary_id'], $courseData['courseLocation']['courseLocationData'][0]['listing_location_id']);
                }
            }
        }
        
        $this->dbHandle->trans_complete();
    	if ($this->dbHandle->trans_status() === FALSE) {
    		throw new Exception('Transaction Failed');
    	}
    	return $courseId;    	
    }

    private function getCurrentCourseData($courseId){

        $currentData['courseData'] = array();
        $currentData['listingMainData'] = array();

        $status = 'draft';

        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
        $this->dbHandle->limit(1);

        $query = $this->dbHandle->get('shiksha_courses');
        if($query->num_rows() > 0){
            $currentData['courseData'] = $query->row_array();
        }
        else{
            $status = 'live';
            $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
            $this->dbHandle->limit(1);

            $query = $this->dbHandle->get('shiksha_courses');
            if($query->num_rows() > 0){
                $currentData['courseData'] = $query->row_array();
            }
        }

        if(!empty($currentData['courseData'])){
            $listingMainStatus = $this->config->item('listingMainStatusForCourse');
            $this->dbHandle->where(array('listing_type_id'=>$courseId,'listing_type'=>'course','status'=>$listingMainStatus[$status]));
            $this->dbHandle->select('listing_title,expiry_date,username,pack_type,viewCount,subscriptionId,listing_seo_title,listing_seo_description,listing_seo_url,submit_date')->limit(1);

            $currentData['listingMainData'] = $this->dbHandle->get('listings_main')->row_array();
        }

        return $currentData;
    }

    private function getListingsMainData($typeId,$type,$getClientData = true){
        $listingMainStatus = $this->config->item('listingMainStatusForCourse');
        $this->dbHandle->where(array('listing_type_id'=>$typeId,'listing_type'=>$type,'status'=>$listingMainStatus['live']));
        $this->dbHandle->select('listing_title,expiry_date,username,pack_type,viewCount,subscriptionId,listing_seo_title,listing_seo_description,listing_seo_url')->limit(1);

        if($getClientData){
            $data = $this->dbHandle->get('listings_main')->row_array();
            $data['clientData'] = $this->dbHandle->where('userid',$data['username'])->select('displayname,email,mobile,avtarimageurl,usergroup')->get('tuser')->row_array();
            return $data;
        }
        else{
            return $this->dbHandle->get('listings_main')->row_array();
        }
    }

    private function updateListingStatus($courseId, $fromStatus = array('draft'), $toStatus = 'history',$extraData = array()) {

        if(empty($fromStatus)){
            throw new Exception('from status cannot be empty');
        }

        $listingMainStatus = $this->config->item('listingMainStatusForCourse');
        $temp = array();
        foreach ($fromStatus as $status) {
            if(!empty($listingMainStatus[$status])){
                $temp[] = $listingMainStatus[$status];
            }
            else{
                $temp[] = $status;
            }
        }
        $temp = array_unique($temp);

        if(!empty($temp)){
            $this->dbHandle->where(array('listing_type'=>'course','listing_type_id'=>$courseId))->where_in('status',$temp);
            $update = array('status'=>$listingMainStatus[$toStatus]);
            if(!empty($extraData['updateLastModified'])){
                $update['last_modify_date'] = date("Y-m-d H:i:s");
            }
            $this->dbHandle->update('listings_main',$update);
        }

        $courseTables = array(
            'shiksha_courses',
            'shiksha_courses_additional_info',
            'shiksha_courses_type_information',
            'shiksha_courses_eligibility_score',
            'shiksha_courses_eligibility_base_entities',
            'shiksha_courses_eligibility_exam_score',
            'shiksha_courses_eligibility_main',
            'shiksha_courses_partner_details',
            'shiksha_courses_fees',
            'shiksha_courses_admission_process',
            'shiksha_courses_structure_offered_courses',
            'shiksha_courses_important_dates',
            'shiksha_courses_placements_internships',
            'shiksha_courses_companies_mapping',
            'shiksha_courses_seats_breakup',
            'shiksha_courses_locations',
            'shiksha_courses_medias_mapping',
            'shiksha_courses_exams_cut_off'
            );
        
        foreach ($courseTables as $table) {
            $this->dbHandle->where(array('course_id'=>$courseId))->where_in('status',$fromStatus);
            $update = array('status'=>$toStatus);
            $tables = array('shiksha_courses');
            foreach ($tables as $tableName) {
                if($tableName == $table && !empty($extraData['updateLastModified'])){
                    if(!empty($extraData['userId'])){
                        $update['updated_by'] = $extraData['userId'];
                    }
                }
            }
            $this->dbHandle->update($table,$update);
        }

        $otherListingTables = array(
            'shiksha_listings_brochures' => array(
                'conditions' => array('listing_type' => 'course','listing_id' => $courseId, 'cta' => 'brochure'),
                'data' => array(
                    'listing_type' => 'course',
                    'status'=>$toStatus
                )
            ),
            'shiksha_listings_contacts' => array(
                'conditions' => array('listing_type' => 'course', 'listing_id' => $courseId),
                'data' => array(
                    'listing_type' => 'course',
                    'status'=>$toStatus
                )
            )
        );
        foreach ($otherListingTables as $tableName => $tableData) {
            $this->dbHandle->where($tableData['conditions'])->where_in('status',$fromStatus);
            $this->dbHandle->update($tableName,$tableData['data']);
        }
    }

    private function updateListingStatusMultiple($courseIds, $fromStatus = array('draft'), $toStatus = 'history'){

        //$courseIdString = implode(',',$courseIds);
        if(empty($fromStatus)){
            throw new Exception('from status cannot be empty');
        }

        $courseIdString = implode(",", $courseIds);
        $listingMainStatus = $this->config->item('listingMainStatusForCourse');
        $temp = array();
        foreach ($fromStatus as $status) {
            if(!empty($listingMainStatus[$status])){
                $temp[] = $listingMainStatus[$status];
            }
            else{
                $temp[] = $status;
            }
        }
        $temp = array_unique($temp);

        if(!empty($temp)){
            $sql = "UPDATE listings_main
                    SET status = ?
                    WHERE listing_type = 'course' 
                    AND listing_type_id in (?)
                    AND status in (?)";

            $this->dbHandle->query($sql,array($toStatus,$courseIds,$temp));  
        }

        $tables = array(
            'shiksha_courses',
            'shiksha_courses_additional_info',
            'shiksha_courses_type_information',
            'shiksha_courses_eligibility_score',
            'shiksha_courses_eligibility_base_entities',
            'shiksha_courses_eligibility_exam_score',
            'shiksha_courses_eligibility_main',
            'shiksha_courses_partner_details',
            'shiksha_courses_fees',
            'shiksha_courses_admission_process',
            'shiksha_courses_structure_offered_courses',
            'shiksha_courses_important_dates',
            'shiksha_courses_placements_internships',
            'shiksha_courses_companies_mapping',
            'shiksha_courses_seats_breakup',
            'shiksha_courses_locations',
            'shiksha_courses_medias_mapping',
            'shiksha_courses_exams_cut_off'
            );
        
        foreach ($tables as $table) {
            $this->dbHandle->where_in('course_id',$courseIds);
            $this->dbHandle->where_in('status',$fromStatus);
            $this->dbHandle->update($table,array('status' => $toStatus));
          }

        $otherListingTables = array(
            'shiksha_listings_brochures' => array(
                'conditions' => array('listing_type' => 'course', 'listing_id' => $courseIds, 'cta' => "brochure"),
                'data' => array(
                    'listing_type' => 'course',
                    'status'=>$toStatus
                )
            ),
            'shiksha_listings_contacts' => array(
                'conditions' => array('listing_id' => $courseIds),
                'data' => array(
                    'listing_type' => 'course',
                    'status'=>$toStatus
                )
            )
        );
        foreach ($otherListingTables as $tableName => $tableData) {
            $this->dbHandle->where_in('status',$fromStatus);
            foreach ($tableData['conditions'] as $key => $value) {
                if(is_array($value)){
                    $this->dbHandle->where_in($key,$value);
                }
                else{
                    $this->dbHandle->where($key,$value);
                }
            }
            // $this->dbHandle->where($tableData['conditions'])->where_in('status',$fromStatus);
            $this->dbHandle->update($tableName,$tableData['data']);
        }
    }

    private function trackListingPostingChanges($data){
        $insertData = array();
        foreach ($data['sectionsChanged'] as $index => $section) {
            $temp = array('listing_id'=>$data['courseId'],'listing_type'=>'course','section_updated'=>$section,'updated_by'=>$data['userId'],'extraData'=>NULL);
            if(!$index && !empty($data['extraData']['sectionsToIndex'])){
                $temp['extraData'] = json_encode($data['extraData']);
            }
            $insertData[] = $temp;
        }
        // _p($insertData);die;
        if(!empty($insertData)){
            $this->dbHandle->insert_batch('shiksha_listing_updation_tracking',$insertData);
        }
    }

    private function isCoursePublishedForFirstTime($courseId){
        $data = $this->dbHandle->where(array('course_id'=>$courseId,'status'=>'live'))->select('course_id')->limit(1)->get('shiksha_courses')->row_array();
        if(!empty($data)){
            return false;
        }
        return true;
    }

    private function addCourseToIndexLog($data){
        //update institute courses
        if($data['operation'] == 'delete'){
            $this->instituteFlatTableLib->flatTableUpdateOnCourseDelete($data['courseId']);
        }

        if($data['indexingType'] == 'fullIndex'){
            $this->dbHandle->insert('indexlog',array('listing_type'=>'course','listing_id'=>$data['courseId'],'operation'=>$data['operation']));
        }
        else if($data['indexingType'] == 'partialIndex'){

            $this->dbHandle->where(array('listing_type'=>'course','listing_id'=>$data['courseId'],'status'=>'draft'));
            $query = $this->dbHandle->select('extraData')->get('shiksha_listing_updation_tracking')->result_array();
            $sectionsData = array();
            foreach($query as $row){
                if(!empty($row['extraData'])){
                    $sectionsData[] = json_decode($row['extraData'],true);
                }
            }
            if($data['operation'] == 'index'){
                $hierarchyChanged = false;
                foreach($sectionsData as $sectionData){
                    if(!empty($sectionData['extraData']['hierarchySection']['oldId'])){
                        $hierarchyChanged = true;
                        break;
                    }
                }
                if($hierarchyChanged){
                    //update institute courses
                    $basicData = $this->getBasicCourseAndParentData($data['courseId'],'live');
                    $this->instituteFlatTableLib->flatTableInstituteUpdateForCoursePPChange($data['courseId']);
                    $this->instituteFlatTableLib->flatTableInstituteUpdate($basicData['primary_id']);

                }
            }
            // if(!empty($data['sectionData'])){
            //     $sectionsData[] = $data['sectionData'];
            // }
            $sectionToIndexMapping = $this->config->item('sectionToIndexMapping');

            $insertData = array();
            foreach ($sectionsData as $sectionData) {
                foreach ($sectionData['sectionsToIndex'] as $key => $value) {
                    $temp = array('listing_type'=>'course','listing_id'=>$data['courseId'],'operation'=>$data['operation']);
                    $temp['section_updated'] = $sectionToIndexMapping[$key];
                    $temp['extraData'] = NULL;
                    if(!empty($sectionData['extraData'][$key])){
                        $temp['extraData'] = json_encode($sectionData['extraData'][$key]);
                    }
                    $insertData[] = $temp;
                }
            }/*_p($insertData);die;*/
            if(!empty($insertData)){
                $this->dbHandle->insert_batch('indexlog',$insertData);
            }
        }
        $this->dbHandle->where(array('listing_type'=>'course','listing_id'=>$data['courseId'],'status'=>'draft'));
        $this->dbHandle->update('shiksha_listing_updation_tracking',array('status'=>'live'));
    }

    public function getCourseData($courseId){
        $this->initiateModel('write');
        $courseData = array();
        $status = 'draft';


        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
        $this->dbHandle->limit(1);
        $basicInfoResult = $this->dbHandle->get('shiksha_courses');

        if($basicInfoResult->num_rows() <= 0){

            $status = 'live';

            $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
            $this->dbHandle->limit(1);
            $basicInfoResult = $this->dbHandle->get('shiksha_courses');

            if($basicInfoResult->num_rows() <= 0){
                return array();
            }
        }

        $courseInfo = array();
        $courseData['course_info'] = $basicInfoResult->row_array();

        
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
        $this->dbHandle->select('info_type,attribute_value_id,description');
        $courseAdditionalInfoResult = $this->dbHandle->get('shiksha_courses_additional_info');

        $courseAdditionalInfo = array();
        if($courseAdditionalInfoResult->num_rows() > 0) {
            $courseAdditionalInfo = $courseAdditionalInfoResult->result_array();
        }

        $courseData['course_addditional_info'] = $courseAdditionalInfo;

        $courseTypeInfo = array();
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
        $this->dbHandle->select('type,credential,course_level,base_course,stream_id,substream_id,specialization_id,primary_hierarchy');
        $query = $this->dbHandle->get('shiksha_courses_type_information');

        if($query->num_rows() > 0){
            $courseTypeInfo = $query->result_array();
        }

        $courseData['courseTypeInfo'] = $courseTypeInfo;

        $eligibility['mainData'] = array();
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
        $this->dbHandle->select('batch_year,work-ex_min,work-ex_max,work-ex_unit,age_min,age_max,international_students_desc,description,subjects');

        $query = $this->dbHandle->get('shiksha_courses_eligibility_main');

        if($query->num_rows() > 0){
            $eligibility['mainData'] = $query->row_array();
        }

        $eligibility['entityData'] = array();
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
        $this->dbHandle->select('base_course,specialization,type');

        $query = $this->dbHandle->get('shiksha_courses_eligibility_base_entities');

        if($query->num_rows() > 0){
            $eligibility['entityData'] = $query->result_array();
        }

        $eligibility['scoreData'] = array();
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
        $this->dbHandle->select('standard,category,value,unit,max_value,specific_requirement');

        $query = $this->dbHandle->get('shiksha_courses_eligibility_score');

        if($query->num_rows() > 0){
            $eligibility['scoreData'] = $query->result_array();
        }

        $eligibility['examScoreData'] = array();
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
        $this->dbHandle->select('exam_id,exam_name,category,value,unit,max_value');

        $query = $this->dbHandle->get('shiksha_courses_eligibility_exam_score');

        if($query->num_rows() > 0){
            $eligibility['examScoreData'] = $query->result_array();
        }

        $courseData['eligibility'] = $eligibility;


        $placements = array();
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status,'type'=>'placements'));
        $this->dbHandle->limit(1);

        $query = $this->dbHandle->get('shiksha_courses_placements_internships');

        if($query->num_rows() > 0){
            $placementData = $query->result_array();
            $placements = $placementData[0];
        }

        $courseData['placements'] = $placements;

        $internship = array();
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status,'type'=>'internship'));
        $this->dbHandle->limit(1);

        $query = $this->dbHandle->get('shiksha_courses_placements_internships');

        if($query->num_rows() > 0){
            $internshipData = $query->result_array();
            $internship = $internshipData[0];
        }
        $courseData['internship'] = $internship;

        $seats = array();
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status))->select('breakup_by,category,exam_id,custom_exam_name,related_state_list,seats');
        $query = $this->dbHandle->get('shiksha_courses_seats_breakup');
        if($query->num_rows() > 0){
            $seats = $query->result_array();
        }
        $courseData['seats'] = $seats;

        $courseStructureInfo = array();
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
        $this->dbHandle->order_by("period_value asc,id asc");
        $query = $this->dbHandle->select('period,period_value,courses_offered')->get('shiksha_courses_structure_offered_courses');

        // _p($this->dbHandle->_compile_select());die;
        // _p($this->dbHandle->last_query());die;

        if($query->num_rows() > 0){
            $courseStructureInfo = $query->result_array();
        }
        $courseData['courseStructureInfo'] = $courseStructureInfo;

        $importantDates = array();
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status));
        $query = $this->dbHandle->select('event_name,start_date,start_month,start_year,end_date,end_month,end_year')->get('shiksha_courses_important_dates');

        if($query->num_rows() > 0){
            $importantDates = $query->result_array();
        }
        $courseData['importantDates'] = $importantDates;

        //not fetching auto-generated brochure in this flow
        $brochureData = array();
        $query = $this->dbHandle->where(array('listing_id'=>$courseId,'listing_type'=>'course', 'status' => $status))->where('(is_auto_generated != 1 OR is_auto_generated is NULL)')->select('brochure_url,brochure_year,brochure_size')->get('shiksha_listings_brochures');
        
        if($query->num_rows() > 0){
            $brochureData = $query->row_array();
        }
        $courseData['brochureData'] = $brochureData;

        $contactDetails = array();
        $query = $this->dbHandle->where(array('listing_id'=>$courseId,'listing_type'=>'course','status'=>$status))->select()->get('shiksha_listings_contacts');
        if($query->num_rows() > 0){
            $contactDetails = $query->result_array();
        }

        $courseData['contactDetails'] = $contactDetails;


        // $courseLocations = array();
        $sql = "SELECT DISTINCT scl.listing_location_id,scl.is_main from shiksha_courses_locations scl join shiksha_institutes_locations sil 
        on scl.listing_location_id = sil.listing_location_id where sil.listing_id = ? 
        and sil.listing_type = ? and sil.status = 'live' 
        and scl.course_id = ? and scl.status = ? ";

        $courseLocations = $this->dbHandle->query($sql,array($courseData['course_info']['primary_id'],$courseData['course_info']['primary_type'],$courseId,$status))->result_array();
        // $query = $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status))->select()->get('shiksha_courses_locations');
        // if($query->num_rows() > 0){
        //     $courseLocations = $query->result_array();
        // }

        $courseData['courseLocations'] = $courseLocations;


        $courseFees = array();
        $this->dbHandle->where("listing_location_id != ''");
        $query = $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status,'fees_type'=>'total','period'=>'overall'))->select()->get('shiksha_courses_fees');
        if($query->num_rows() > 0){
            $courseFees = $query->result_array();
        }
        $courseData['courseFees'] = $courseFees;

        //getting course admission process
        $courseAdmissionProcess = array();
        $query = $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status))->select()->order_by('stage_order', 'asc')->get('shiksha_courses_admission_process');
        if($query->num_rows() > 0){
            $courseAdmissionProcess = $query->result_array();
        }
        $courseData['courseAdmissionProcess'] = $courseAdmissionProcess;

        //getting course usp
        // $courseUsp = array();
        // $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status,'info_type'=>'usp'));
        // $query = $this->dbHandle->get('shiksha_courses_additional_info');
        // if($query->num_rows() > 0){
        //     $courseUsp = $query->result_array();
        // }
        // $courseData['courseUsp'] = $courseUsp;

        $query = "SELECT DISTINCT scm.company_id from shiksha_courses_companies_mapping scm join shiksha_institutes_companies_mapping sim on sim.listing_id = ? and sim.company_id = scm.company_id and sim.listing_type in ('institute', 'university') and sim.status = 'live' where scm.course_id = ? and scm.status = ?";
        $query = $this->dbHandle->query($query,array($courseData['course_info']['primary_id'],$courseId,$status));

        // $courseCompaniesMapping = array();
        // $query = $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status))->select()->get('shiksha_courses_companies_mapping');
        if($query->num_rows() > 0){
            $courseCompaniesMapping = $query->result_array();
        }

        $courseData['courseCompaniesMapping'] = $courseCompaniesMapping;

        $courseMediasMapping = array();
        $query = "SELECT iml.media_id from shiksha_courses_medias_mapping cm join shiksha_institutes_medias im 
        on (cm.media_id = im.media_id or  cm.media_id = -1) join shiksha_institutes_media_locations_mapping iml 
        on iml.media_id = im.media_id where im.status = 'live' and iml.status = 'live' and cm.course_id = ? and cm.status = ? and iml.listing_id = ?";

        $courseMediasMapping = $this->dbHandle->query($query,array($courseId,$status,$courseData['course_info']['primary_id']))->result_array();

        $courseData['courseMediasMapping'] = $courseMediasMapping;

        //getting exams and 12th cut off data
        $courseCutOff = array();
        $query = $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status))->select()->get('shiksha_courses_exams_cut_off');
        if($query->num_rows() > 0){
            $courseCutOff = $query->result_array();
        }

        $courseData['courseCutOff'] = $courseCutOff;

        $coursePartnerForm = array();
        $query = $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status))->select()->get('shiksha_courses_partner_details');
        if($query->num_rows() > 0){
            $coursePartnerForm = $query->result_array();
        }
        $courseData['coursePartnerForm'] = $coursePartnerForm;

        $courseFeesForm = array();
        $query = $this->dbHandle->where(array('course_id'=>$courseId,'status'=>$status))->select()->get('shiksha_courses_fees');
        if($query->num_rows() > 0){
            $courseFeesForm = $query->result_array();
        }

        $courseData['courseFeesForm'] = $courseFeesForm;

        //fetching data from listings_main
        $currentData = $this->getCurrentCourseData($courseId);    
        $courseData['listingMainData'] = $currentData['listingMainData'];
        


        return $courseData;
    }

    public function getAllAttributesHavingDependencies(){
        $this->initiateModel('write');
        $query = $this->dbHandle->select('value_id,value_name,attribute_name')->where('status','live')->get('base_attribute_list')->result_array();
        foreach ($query as $row) {
            $mapping[$row['value_id']] = $row;
        }

        $query = $this->dbHandle->select('parent_value_id,value_id,attribute_id')->where('status','live')->get('attribute_parent_child_mapping')->result_array();
        foreach ($query as $row) {
            $attributeData[$row['value_id']]['value_id'] = $row['value_id'];
            $attributeData[$row['value_id']]['attribute_id'] = $row['attribute_id'];
            $attributeData[$row['value_id']]['value_name'] = $mapping[$row['value_id']]['value_name'];
            $attributeData[$row['value_id']]['attribute_name'] = $mapping[$row['value_id']]['attribute_name'];
            $attributeData[$row['value_id']]['parent_value_id'][] = $row['parent_value_id'];
            $attributeData[$row['value_id']]['parent_value_name'][] = $mapping[$row['parent_value_id']]['value_name'];
        }
        return $attributeData;
    }

    public function _getNewCourseId(){
    	return Modules::run('common/IDGenerator/generateId','course');
    }

    public function getCurrency(){
        $this->initiateModel('write');
        $this->dbHandle->select();

        $query = $this->dbHandle->get('currency');
        $data = array();
        if($query->num_rows() > 0){
            $data = $query->result_array();
        }

        return $data;
    }

    function getListingIdsBasedOnText($searchText){
        $this->initiateModel('read');

        $sqlInstitute = " SELECT listing_id as institute_id, listing_type ".
                        " FROM shiksha_institutes ".   
                        " WHERE name like ? ".
                        " AND status NOT IN ('history','deleted')";

        $instituteSearchInfo = $this->dbHandle->query($sqlInstitute,array("%".$searchText."%"))->result_array();
        $instituteSearchIds  = array ();
        foreach ($instituteSearchInfo as $key => $value) {
            $instituteSearchIds[$value['institute_id']]['id'] = $value['institute_id'];
            $instituteSearchIds[$value['institute_id']]['type'] = $value['listing_type'];
        }
        return $instituteSearchIds;
    }

    function getSearchResultsForTable($courseIds='',$status='',$start=0){
        $result = array();
 
        return $this->getCourseList($courseIds,$status,$start);

    }

    function getCourseList($courseIds,$status='',$start=0){
        $this->initiateModel('read');

        $whereANDCondition = array();
        $whereORCondition = array();

        $start = (int) $start;

        $whereANDCondition[] = "sc.status IN ('live','draft')"; // In order to make use of the status index (LF-4965)
        $whereANDCondition[] = "si.status NOT IN ('history','deleted')";
        $whereANDCondition[] = "lm.status NOT IN ('history','deleted')";
        $whereANDCondition[] = "lm.listing_type = 'course'";


        if(!empty($status)){
            if($status == 'Live'){
                $whereANDCondition[] = " sc.status = 'live' ";
                $whereANDCondition[] = " lm.status = 'live' ";    
                // $whereANDCondition[] = " isnull(sc.disabled_url) ";
            }elseif ($status == 'Draft') {
                $whereANDCondition[] = " sc.status = 'draft' ";    
                $whereANDCondition[] = " lm.status = 'draft' ";    
            }elseif ($status == 'Disabled') {
                // $whereANDCondition[] = " sc.status = 'live' ";    
                $whereANDCondition[] = " !isnull(sc.disabled_url) ";    
            }
        }

       $emptyCourseIds = false;

        // _p($courseIds); die;
        if(empty($courseIds)) {
            $emptyCourseIds = true;
            $statusArr = array('live','draft');
            if($status == 'Live'){
                $statusArr = array('live');
            }
            else if($status == 'Draft'){
                $statusArr = array('draft');
            }
            $disableCondition = '';
            if($status == 'Disabled'){
                $disableCondition = " AND !isnull(disabled_url) ";
            }

            $courseIdsSql = "SELECT DISTINCT course_id  from shiksha_courses where status in (?) $disableCondition ORDER BY updated_on DESC LIMIT $start,25";
            $sqlCount = "SELECT count(DISTINCT course_id) as count  from shiksha_courses where status in (?) $disableCondition";
            $courseIdsResult =  $this->dbHandle->query($courseIdsSql,array($statusArr))->result_array();
            //complete course ids set
            $courseIdsComplete = $this->getColumnArray($courseIdsResult, 'course_id');
            
            //merging two course ids arays i.e. 1 from main table and another from filters

            $courseIds = array_filter( array_unique( array_merge( $courseIdsComplete, (array) $courseIds ) ) );
            // $courseCount =  $this->dbHandle->query($sqlCount,array($statusArr))->row_array();
            // $courseCount = $courseCount['count'];
            $courseCount = 1000; //removed slow query and added hardcoded pagination
        }
        // echo $courseIdsString; die;
        $params = array();
        if(!empty($courseIds)) {
            $whereANDCondition[] = " sc.course_id in (?) ";
            $params[] = $courseIds;
        }

        $sql = " SELECT DISTINCT sc.course_id, sc.name,si.name as institute_name, ".
               " sc.status,lm.pack_type,sc.disabled_url ".
               " FROM ".
               " shiksha_courses sc JOIN shiksha_institutes si ON sc.primary_id = si.listing_id and si.status='live'". 
               " JOIN listings_main lm ON sc.course_id = lm.listing_type_id and lm.status in ('live','draft') ". 
               " WHERE ".implode(' AND ', $whereANDCondition)." ".
               " order by sc.updated_on desc";

        if(!$emptyCourseIds){
            $sql .= " limit $start,25";

            $sqlCount = " SELECT count(distinct sc.course_id) as count ".               
                   " FROM ".
                   " shiksha_courses sc JOIN shiksha_institutes si ON sc.primary_id = si.listing_id and si.status='live' ". 
                   " JOIN listings_main lm ON sc.course_id = lm.listing_type_id and lm.status in ('live','draft') ".
                   " WHERE ".implode(' AND ', $whereANDCondition)." ";

            $courseCount =  $this->dbHandle->query($sqlCount,$params)->result_array();
            $courseCount = $courseCount[0]['count'];
        }        
        
        $rsInfo =  $this->dbHandle->query($sql,$params)->result_array();
        
        $data               = array();
        $data['totalCount'] = $courseCount;
        $data['data']       = $rsInfo;
       
       return $data;

    }

    public function checkListingStatus($courseId) {
         $this->initiateModel('read');
        // error handling
        if(empty($courseId)) {
            return array();
        }
        // check for listing status in shiksha_institutes table 
        $sql = "select course_id, name from shiksha_courses where status='live' and course_id = ?";
        $query = $this->dbHandle->query($sql,array($courseId));
        $result_array = array();

        // create result array
        foreach($query->result() as $row) {
            $result_array['courseId'] = $row->course_id;
            $result_array['courseName'] = $row->name;
        }

        return $result_array;
    }

    public function deleteCourse($courseId, $instituteId, $newCourseId, $courseOrderUpdateCourseId, $newInstituteId, $migrationCriteria,$notificationMailerData =array()) {
        $this->initiateModel();
        $this->dbHandle->trans_start();
        $this->updateListingStatus($courseId, array('draft','live'),'deleted', $migrationCriteria);
        
        //change course order
        if($courseOrderUpdateCourseId) {
            $this->updateCourseOrderAfterCourseDeletion($courseOrderUpdateCourseId);
        }

        $oldCourseId = $courseId;
// _p($migrationCriteria); die;
        //migrate CR
        $response = 'success';
        $responseMsg = array();
        $this->campusConnectBaseLib = $this->load->library('CA/ccBaseLib');
        if($newCourseId && $migrationCriteria['crs'] && $newInstituteId) {
            $responseMsg[] = "Campus Connect: ".$this->campusConnectBaseLib->updateCRCourse($oldCourseId, $newCourseId, $newInstituteId, $this->dbHandle);
        }
        else {
            $responseMsg[] = "Campus Connect: ".$this->campusConnectBaseLib->updateCRCourse($oldCourseId, null, null, $this->dbHandle);
        }

        //migrate reviews if checked by user
        if($newCourseId && $migrationCriteria['reviews']){
            $this->migrateCourseReviews($oldCourseId, $newCourseId, $this->dbHandle);
            $responseMsg[] = "Reviews: Migrated successfully";
        }
        else { //otherwise mark its dependency as deleted from reviews
            $this->deleteCourseReviews(array($oldCourseId));
        }

        //migrate questions
        if($newCourseId && $migrationCriteria['questions']){
            $this->migrateQuestionsToNewListing($oldCourseId,$instituteId,$newCourseId,$newInstituteId);
            $responseMsg[] = "Questions: Migrated successfully";
        }
        else { //otherwise mark its dependency as deleted from questions
            $this->migrateQuestionsToNewListing($oldCourseId,$instituteId,null);
            $responseMsg[] = "Questions: Deleted successfully";
        }

        //deleting course from shortlist table
        $this->MyShortlistLib = $this->load->library('myShortlist/MyShortlistLib');
        $responseMsg[] = "Shortlisted: ".$this->MyShortlistLib->migrateOrDeleteShortlistMappingForCourse($oldCourseId, $newCourseId, $this->dbHandle);

        //deleting course from College Predictors
        $this->CollegePredictorLibrary = $this->load->library('CP/CollegePredictorLibrary');
        $responseMsg[] = "Predictor Tool: ".$this->CollegePredictorLibrary->migrateOrDeletePredictorMappingForCourse($oldCourseId, $newCourseId, $this->dbHandle);

        //deleting course from Compare
        $this->comparePageLib = $this->load->library('comparePage/comparePageLib');
        $responseMsg[] = $this->comparePageLib->migrateOrDeletePopularCourseComparisionMappingForCourse($oldCourseId, $newCourseId, $this->dbHandle);

        //deleting course from Ranking
        $this->RankingCommonLibv2 = $this->load->library('rankingV2/RankingCommonLibv2');
        $responseMsg[] = "Ranking Page: ".$this->RankingCommonLibv2->markCoursesDeletedInRankingPages(array($oldCourseId));

        $this->InstitutePostingLib = $this->load->library('nationalInstitute/InstitutePostingLib');
        $this->InstitutePostingLib->migrateDeletedInstituteData($oldCourseId, $newCourseId,'course', $this->dbHandle);

        $this->addCourseToIndexLog(array('courseId'=>$courseId,'indexingType'=>'fullIndex','operation'=>'delete'));

        if(!empty($notificationMailerData)){
            $notificationMailerData['status'] ='pending';
            $this->dbHandle->insert('notification_mailer',$notificationMailerData);
        }
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }

        //migrate responses - not maintaining DB transaction here since this will be a common flow across many places
        $responseMigrationStatus = true;
        $this->responseLib = $this->load->library('response/responseLib');
        if($newCourseId && $migrationCriteria['responses']) {
            $responseMsg[] = $this->responseLib->responseMigration(array($oldCourseId => $newCourseId));
        }

        return array('status' => 'success', 'msg' => $responseMsg);             
    }

    public function deleteMultipleCourses($courseIds,$status=array('draft','live')){
        $this->initiateModel();
        $this->updateListingStatusMultiple($courseIds, $status,'deleted');
        // $insertData = array();
        $this->coursecache = $this->load->library('nationalCourse/cache/NationalCourseCache');
        foreach ($courseIds as $courseId) {
            $this->addCourseToIndexLog(array('courseId'=>$courseId,'indexingType'=>'fullIndex','operation'=>'delete'));

            //Remove course cache
            $this->coursecache->removeCoursesCache(array($courseId));
        }
        // if(!empty($insertData)){
        //     $this->dbHandle->insert_batch('indexlog',$insertData);
        // }
        $this->dbHandle->where(array('listing_type'=>'course','status'=>'draft'))->where_in('listing_id',$courseIds);
        $this->dbHandle->update('shiksha_listing_updation_tracking',array('status'=>'live'));
        return true;             
    }


    public function makeCourseLive($courseId,$userId = NULL){
        $this->initiateModel();
        $this->dbHandle->trans_start();
        $isFirstPublish = $this->isCoursePublishedForFirstTime($courseId);
        $this->updateListingStatus($courseId, array('live'),'history',array('updateLastModified'=>true,'userId'=>$userId));
        $this->updateListingStatus($courseId, array('draft'),'live',array('updateLastModified'=>true,'userId'=>$userId));

        if($isFirstPublish){
            $basicData = $this->getBasicCourseAndParentData($courseId,'live');
            $this->instituteFlatTableLib->flatTableInstituteUpdate($basicData['primary_id']);
            $this->addCourseToIndexLog(array('courseId'=>$courseId,'indexingType'=>'fullIndex','operation'=>'index'));
        }
        else{
            $this->addCourseToIndexLog(array('courseId'=>$courseId,'indexingType'=>'partialIndex','operation'=>'index'));
        }

        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            return false;
        }
        return true;             
    }

    public function updateCourseDisableUrl($listingIds,$url=NULL,$type='course'){
       $this->initiateModel('write');
       
       if(!empty($listingIds)){
            $urlString = ($url != '')?$url:null;
        
            if($type == 'course'){
                $courseIds = $listingIds;
            }else{
                $query = $this->dbHandle->where_in('primary_id',(array)$listingIds)->where_in('status',array('live','draft'))->get('shiksha_courses')->result_array();
                $courseIds = $this->getColumnArray($query,'course_id');
                $courseIds = array_unique($courseIds);
            }

            if($type == 'course'){
                $this->dbHandle->where_in('course_id',$listingIds);
            }
            else{
                $this->dbHandle->where_in('primary_id',$listingIds);
            }
            $this->dbHandle->where_in('status',array('live','draft'));
            $this->dbHandle->update('shiksha_courses',array('disabled_url' => $urlString));

            $operation = 'index';
            if(!empty($url)){
                $operation = 'delete';
            }
            $insertData = array();
            foreach ($courseIds as $courseId) {
                $insertData[] = array('listing_type'=>'course','listing_id'=>$courseId,'operation'=>$operation);
            }
            if(!empty($insertData)){
                $this->dbHandle->insert_batch('indexlog',$insertData);
            }
            if(count($courseIds) > 0 && !empty($courseIds)){
                $this->dbHandle->where(array('listing_type'=>'course','status'=>'draft'))->where_in('listing_id',$courseIds);
                $this->dbHandle->update('shiksha_listing_updation_tracking',array('status'=>'live'));    
            }
            
       }
    }

    

    function getCoursePostingComments($courseId,$limit = 20){
        $listingType = 'course';
        if(empty($courseId))
            return array();

        $this->initiateModel('read');

        $limit = (int)$limit;

        $query = "SELECT t.userId,t.firstname,t.lastname,track.comments, track.updatedAt FROM listingCMSUserTracking track left join tuser t ON(track.userId = t.userId) WHERE track.listingId = ? AND track.tabUpdated = ? ORDER BY track.id desc LIMIT ".$limit;

        $rsInfo =  $this->dbHandle->query($query, array($courseId,$listingType))->result_array();

        return $rsInfo;
    }

    function getCourseInfoForCourseOrder($courseId){
        if(empty($courseId))
            return array();

        $this->initiateModel('read');
        $sql = "select primary_id, course_order from shiksha_courses where status = 'live' and course_id = ?";
        $data = $this->dbHandle->query($sql,array($courseId))->row_array();
        $courseIdsToUpdate = array();
        if($data['course_order'] > 0){
            $primaryId = $data['primary_id'];
            $courseOrder = $data['course_order'];
            $sql1 = "select course_id from shiksha_courses where status = 'live' and primary_id = ? and course_id != ? and course_order > ?";
            $result = $this->dbHandle->query($sql1,array($primaryId, $courseId, $courseOrder))->result_array();
            if($result){
                foreach ($result as $key => $value) {
                    $courseIdsToUpdate[] = $value['course_id'];
                }                
            }
        }      
        
        return $courseIdsToUpdate;

    }

    function updateCourseOrderAfterCourseDeletion($courseIds){
        $this->initiateModel('write');

        if(!empty($courseIds)){
            $sql = "UPDATE shiksha_courses SET course_order = course_order - 1 WHERE course_id in (?) and status in ('live')";
            $this->dbHandle->query($sql,array($courseIds));
        }
    }

    function migrateQuestionsToNewListing($courseId,$instituteId,$newCourseId,$newInstituteId){
        $this->initiateModel('write');
        $msgArray = array();
        if(empty($newCourseId)) {            
            $sql = "SELECT messageId FROM questions_listing_response WHERE courseId = ? AND instituteId = ? AND status ='live'";
            $msgArray = $this->dbHandle->query($sql,array($courseId,$instituteId))->result_array();
            foreach($msgArray as $message){
                $msgIdArray[]=$message['messageId'];
            }
            
            if(!empty($msgIdArray)) {
                $sql = "UPDATE messageTable SET status = 'deleted' WHERE listingTypeId = ? AND status = 'live' AND msgId in (?)";
                $this->dbHandle->query($sql,array($instituteId,$msgIdArray));

            }

            $sql = "UPDATE questions_listing_response 
                SET status = 'deleted'
                WHERE courseId = ? AND status='live' ";
            $this->dbHandle->query($sql,array($courseId));
        }
        else {
            $sql = "SELECT messageId FROM questions_listing_response WHERE courseId = ? AND instituteId = ? AND status ='live'";
            $msgArray = $this->dbHandle->query($sql,array($courseId,$instituteId))->result_array();
            foreach($msgArray as $message){
                $msgIdArray[]=$message['messageId'];
            }

            if(!empty($msgIdArray)) {
                $sql = "UPDATE messageTable SET listingTypeId = ? WHERE listingTypeId = ? AND status = 'live' AND msgId in (?)";
                $this->dbHandle->query($sql,array($newInstituteId,$instituteId,$msgIdArray));
            }

            $sql = "UPDATE questions_listing_response 
                    SET courseId = ?, instituteId = ?
                    WHERE courseId = ? AND status='live' ";
            $this->dbHandle->query($sql,array($newCourseId,$newInstituteId,$courseId));
        }
        return;
    }

    function getAbroadUniversityNamesById($universityIds,$returnData = array('name'),$returnNormal=false,$statusCheck = true){
        Contract::mustBeNonEmptyArrayOfIntegerValues($universityIds, 'University Ids');
        Contract::mustBeNonEmptyArray($returnData, 'return array');
        $this->initiateModel();
        if($statusCheck)
            $status = array('live');
        else 
            $status = array('live','draft');

        $this->dbHandle->select('university_id,'.implode(', ', $returnData))->where_in('university_id', $universityIds)->where_in('status',$status);
        $universityData = $this->dbHandle->get('university')->result_array();

        if($returnNormal)
            return $universityData;

        foreach($universityData as $university) {
            foreach($returnData as $val) {
                $variableName = 'id'.ucfirst($val).'Arr';
                $variableNameArr[$variableName] = $variableName;
                ${$variableName}["university_".$university['university_id']] = $university[$val];
            }
        }
        $returnArr = array();
        foreach ($variableNameArr as $key => $variableName) {
            $returnArr[$key] = ${$variableName};
        }
        
        return $returnArr;
    }

    function migrateCourseReviews($oldCourseId, $newCourseId) {
        $this->CollegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $this->CollegeReviewLib->updateCourseIdForReview(array($oldCourseId => $newCourseId));
    }

    function deleteCourseReviews($oldCourseId){
        $this->CollegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $this->CollegeReviewLib->deleteCourseIdForReview($oldCourseId);
    }

    public function getBasicCourseAndParentData($courseId,$status = array('live','draft')){
        $this->initiateModel("write");
        if(!is_array($status)){
            $status = array($status);
        }
        $data = $this->dbHandle->where(array('course_id'=>$courseId))->where_in('status',$status)->limit(1)->get('shiksha_courses')->row_array();
        if(!empty($data)){
            $temp = $this->dbHandle->where_in('listing_id',array($data['parent_id'],$data['primary_id']))->where_in('status',$status)->get('shiksha_institutes')->result_array();
            foreach ($temp as $row) {
                if($data['primary_id'] == $row['listing_id']){
                    $data['primary_data'] = $row;
                }
                if($data['parent_id'] == $row['listing_id']){
                    $data['parent_data'] = $row;
                }
            }
        }
        return $data;
    }

    public function getAllCoursesOfInstitutes($instituteIds,$status = array('live')){
        $this->initiateModel("write");
        if(!is_array($status)){
            $status = array($status);
        }

        $query = "SELECT course_id from shiksha_courses where status IN (?) AND (primary_id in (?) or parent_id in (?))";
        $query = $this->dbHandle->query($query,array($status,$instituteIds,$instituteIds))->result_array();
        return $this->getColumnArray($query,'course_id');
    }

    function updateCourseBrochure($brochureArr) {
        if(empty($brochureArr) || empty($brochureArr['listingId'])) {
            return false;
        }
        $this->initiateModel("write");

        //transaction starts
        $this->dbHandle->trans_start();
        
        //update status
        $this->dbHandle->where(array('listing_type'=>$brochureArr['listingType'], 'listing_id'=>$brochureArr['listingId'], 'cta' => $brochureArr['cta'], 'status' => 'live'));
        $this->dbHandle->update('shiksha_listings_brochures',array('status' => 'history'));
        
        //insert
        $brochureData                      = array();
        $brochureData['listing_id']        = $brochureArr['listingId'];
        $brochureData['listing_type']      = $brochureArr['listingType'];
        $brochureData['cta']               = $brochureArr['cta'];
        $brochureData['brochure_url']      = $brochureArr['brochure_url'];
        $brochureData['brochure_year']     = $brochureArr['brochure_year'];
        $brochureData['brochure_size']     = $brochureArr['brochure_size'];
        $brochureData['is_auto_generated'] = $brochureArr['is_auto_generated'];;
        $brochureData['updated_by']        = $brochureArr['userId'];
        $brochureData['status']            = $brochureArr['status'];
        
        $this->dbHandle->insert('shiksha_listings_brochures', $brochureData);
        // _p($brochureData);
        
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
        
        return true;
    }

    function updateListingCtaPdf($brochureArr) {
        if(empty($brochureArr) || empty($brochureArr['listingId'])) {
            return false;
        }
        $this->initiateModel("write");

        //transaction starts
        $this->dbHandle->trans_start();

        //update status
        $this->dbHandle->where(array('listing_type'=>$brochureArr['listingType'], 'listing_id'=>$brochureArr['listingId'], 'cta'=>$brochureArr['cta'], 'status' => 'live'));
        $this->dbHandle->update('shiksha_listings_cta_pdf',array('status' => 'history'));
        
        //insert
        $brochureData                      = array();
        $brochureData['listing_id']        = $brochureArr['listingId'];
        $brochureData['listing_type']      = $brochureArr['listingType'];
        $brochureData['cta']               = $brochureArr['cta'];
        $brochureData['pdf_url']           = $brochureArr['pdf_url'];
        $brochureData['pdf_size']          = $brochureArr['pdf_size'];
        $brochureData['updated_by']        = $brochureArr['userId'];
        $brochureData['added_on']          = date("Y-m-d H:i:s");
        $brochureData['status']            = $brochureArr['status'];
        
        $this->dbHandle->insert('shiksha_listings_cta_pdf', $brochureData);

        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }

        return true;
    }

    function _logCourseDuplicateLiveEntries($courseId){
        $this->dbHandle->where(array('course_id'=>$courseId,'status'=>'live'));        
        $query         = $this->dbHandle->get('shiksha_courses');
        $noOfRowReturn = $query->num_rows();
        return $noOfRowReturn;
    }

    function saveDataForNotificationMailer($data){
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        $saveData = array();
        $saveData['listing_id'] = $data['courseId'];
        $saveData['listing_name'] = $data['courseName'];
        $saveData['listing_type'] = $data['listingType'];
        $saveData['user_id'] = $data['userId'];
        $saveData['user_name'] = $data['userName'];
        $saveData['status'] = 'pending';
        $saveData['action_type'] = $data['action_type'];
        foreach ($data['section'] as $sectionName => $sectionData) {
            $saveData['old_value'] = $sectionData['oldValue'];
            $saveData['field'] = $sectionData['field'];
            $saveData['new_value'] = $sectionData['newValue'];
            $saveDataArr[] = $saveData; 
        }
        $this->dbHandle->insert_batch('notification_mailer', $saveDataArr);
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            return false;
        }
        return true;
    }    
    
    function getDataForNotificationMailer(){
        $this->initiateModel('read');
        $sql  = "select * from notification_mailer where status ='pending' order by updated_on";
        $data = $this->dbHandle->query($sql)->result_array();
        return $data;
    }
    function updateNotificationMailerTable(){
        $this->initiateModel('write');
        $sql  = "update  notification_mailer set status ='sent' where status ='pending' ";
        $data = $this->dbHandle->query($sql);
        return;
    }
}   
