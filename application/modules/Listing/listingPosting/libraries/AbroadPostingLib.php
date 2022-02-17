<?php
class AbroadPostingLib
{
    private $CI;
    private $abroadCMSModelObj;
    
    function __construct()
    {
        $this->CI =& get_instance();
        $this->_setDependecies();
    }

    function _setDependecies()
    {
        $this->CI->load->model('listingPosting/abroadcmsmodel');
        $this->abroadCMSModelObj  = new abroadcmsmodel();
        
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();
    }
    
    private function _prepareListingsMainTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * Preapring listings_main table data..
         */ 
        $listingsMainTableInfo = array();
        $listingsMainTableInfo['listing_type_id']   = $courseData['courseId'];
        $listingsMainTableInfo['listing_title']     = $courseData['courseName'];
        $listingsMainTableInfo['listing_type']      = 'course';
        $listingsMainTableInfo['status']            = $courseData['listingStatus'];
        $listingsMainTableInfo['username']          = ($courseData['username'] == "" ? $params['userInfo']['userid'] : $courseData['username']);
        $listingsMainTableInfo['moderation_flag']   = 'moderated';
        $listingsMainTableInfo['last_modify_date']  = date('Y-m-d H:i:s');
        $listingsMainTableInfo['submit_date']  = ($courseData['submit_date'] == "" ? date('Y-m-d H:i:s') : $courseData['submit_date']);
        $listingsMainTableInfo['version'] = $version;
        $listingsMainTableInfo['pack_type'] = ($courseData['pack_type'] == "" ? 0 : $courseData['pack_type']);
        $listingsMainTableInfo['viewCount'] = $courseData['viewCount'];
        if($courseData['approve_date'] != "") // used as date of subscription consumption(if any)
        {
            $listingsMainTableInfo['approve_date'] = $courseData['approve_date'];
        }
        if($courseData['expiry_date'] != "") // subscription expiry date
        {
            $listingsMainTableInfo['expiry_date'] = $courseData['expiry_date'];
        }
        
        $listingsMainTableInfo['subscriptionId'] = $courseData['subscriptionId'];
        $listingsMainTableInfo['editedBy']  = $params['userInfo']['userid'];        
        $listingsMainTableInfo['listing_seo_url'] = $courseData['courseUrl'];
        $listingsMainTableInfo['listing_seo_title'] = $courseData['seoTitle'];
        $listingsMainTableInfo['listing_seo_keywords'] = $courseData['seoKeywords'];
        $listingsMainTableInfo['listing_seo_description'] = $courseData['seoDescription'];
        
        return $listingsMainTableInfo;
    }
    
    private function _prepareCourseDetailsTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * Preapring course_details table data..
         */ 
        $course_details = array();
        $course_details['course_id'] = $courseData['courseId'];
        $course_details['institute_id'] = $courseData['departmentId'];
        $course_details['courseTitle'] = $courseData['courseName'];
        // $course_details['course_type'] = $courseData['courseId']; // As discussed with Simran, we need not to store this.
        $course_details['course_level'] = $courseData['courseType'];
        $course_details['course_request_brochure_link'] = $courseData['courseBrochureUrl'];        
//        $course_details['version'] = $version;
        $course_details['fees_value'] = $courseData['tutionFee'];
        $course_details['isMealIncluded'] = $courseData['isMealIncluded'];
        $course_details['fees_unit'] = $courseData['tutionFeeCurrency'];        
        $course_details['status'] = $courseData['listingStatus'];
        $course_details['course_level_1'] = $courseData['courseLevel'];
        $course_details['duration_value'] = $courseData['courseDuration'];
        $course_details['duration_unit'] = $courseData['courseDurationUnit'];
        $course_details['profile_percentage_completion'] = $courseData['percentageCompletion'];
        $course_details['roomBoard'] = $courseData['roomBoard'];
        $course_details['insurance'] = $courseData['insurance'];
        $course_details['transportation'] = $courseData['transportation'];
        return $course_details;
    }
    
    private function _prepareCourseScholarshipDetailsTableData($params){
        $courseData = $params['courseData'];
        $scholarshipData = $courseData['scholarship'];
        $scholarshipData['course_id'] = $courseData['courseId'];
        $scholarshipData['status'] = $courseData['listingStatus'];
        return $scholarshipData;
    }
    
    private function _prepareCourseAttributesTableData($params, $version = 1) {
        $courseData = $params['courseData'];
        
        /*
         * Preapring course_attributes table data..
         */
        $key = 0;
        if($courseData['affiliationDetails'] != "") {
            $course_attributes_array[$key]['key'] = 'AffiliatedTo';
            $course_attributes_array[$key]['value'] = $courseData['affiliationDetails'];
            $key++;
        }
        if($courseData['accreditationDetails'] != "") {            
            $course_attributes_array[$key]['key'] = 'courseAccreditation';
            $course_attributes_array[$key]['value'] = $courseData['accreditationDetails'];
            $key++;
        }
        
        if($courseData['examsRequiredFreeText'] != "") {
            $course_attributes_array[$key]['key'] = 'examRequired';
            $course_attributes_array[$key]['value'] = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$courseData['examsRequiredFreeText']));
            $key++;
        }

        if($courseData['nzqfCategorization'] != "") {
            $course_attributes_array[$key]['key'] = 'nzqfCategorization';
            $course_attributes_array[$key]['value'] = $courseData['nzqfCategorization'];
            $key++;
        }

        if($courseData['curriculum'] != "") {
            $course_attributes_array[$key]['key'] = 'curriculum';
            $course_attributes_array[$key]['value'] = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$courseData['curriculum']));
            $key++;
        }

        if($courseData['courseRanking'] != "") {
            $course_attributes_array[$key]['key'] = 'courseRanking';
            $course_attributes_array[$key]['value'] = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$courseData['courseRanking']));
            $key++;
        }
        
        unset($key);
        $course_attributes = array();        
        foreach($course_attributes_array as $key => $attributeArray) {        
            $course_attributes[$key]['course_id'] = $courseData['courseId'];
            $course_attributes[$key]['attribute'] = $attributeArray['key'];
            $course_attributes[$key]['value'] = $attributeArray['value'];
            $course_attributes[$key]['status'] = $courseData['listingStatus'];
//            $course_attributes[$key]['version'] = $version;
        }
        
        return $course_attributes;
    }
    
    private function _prepareListingContactDetailsTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * Preapring listing_contact_details table data..
         */ 
        $listingContactDetailsTableInfo = array();
        $listingContactDetailsTableInfo['institute_location_id']    = $courseData['institute_location_id'];
        $listingContactDetailsTableInfo['listing_type']             = 'course';
        $listingContactDetailsTableInfo['listing_type_id']          = $courseData['courseId'];
        $listingContactDetailsTableInfo['status']                   = $courseData['listingStatus'];
        $listingContactDetailsTableInfo['website']                  = $courseData['courseWebsite'];        
//        $listingContactDetailsTableInfo['version']                  = $version;
        
        return $listingContactDetailsTableInfo;
    }
    
    private function _prepareCourseLocationAttributeTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * Preapring course_location_attribute table data..
         */ 
        $courseLocationAttribute = array();
        $courseLocationAttribute['course_id'] = $courseData['courseId'];
        $courseLocationAttribute['institute_location_id'] = $courseData['institute_location_id'];
        $courseLocationAttribute['attribute_type'] = 'Head Office';
        $courseLocationAttribute['attribute_value'] = 'TRUE';
        $courseLocationAttribute['status'] = $courseData['listingStatus'];
        $courseLocationAttribute['version'] = $version;
        
        return $courseLocationAttribute;
    }
    
    private function _prepareListingExamAbroadTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * Preapring listingExamAbroad table data..
         */
        $listingExamAbroad = array();
        
        // Collecting data for exams selected from the Master list..
        $key = 0;
        foreach($courseData['examsRequiredDataArray'] as $myKey => $examData) {            
            $listingExamAbroad[$key]['listing_type_id']   = $courseData['courseId'];
            $listingExamAbroad[$key]['listing_type']      = 'course';
            $listingExamAbroad[$key]['status']            = $courseData['listingStatus'];
            $listingExamAbroad[$key]['examName'] = '';            
            $listingExamAbroad[$key]['examId'] = $examData['examId'];
            $listingExamAbroad[$key]['cutoff'] = $examData['examCutOff'];
            $listingExamAbroad[$key]['comments'] = $examData['examComments'];
            $key++;
        }
        
        // Collecting data for custom exams entered by the user for this listing..
        foreach($courseData['examsRequiredCustomDataArray'] as $myKey => $examData) {            
            $listingExamAbroad[$key]['listing_type_id']   = $courseData['courseId'];
            $listingExamAbroad[$key]['listing_type']      = 'course';
            $listingExamAbroad[$key]['status']            = $courseData['listingStatus'];
            $listingExamAbroad[$key]['examName'] = $examData['examName'];
            $listingExamAbroad[$key]['examId'] = '-1';
            $listingExamAbroad[$key]['cutoff'] = $examData['examCutOff'];
            $listingExamAbroad[$key]['comments'] = $examData['examComments'];
            $key++;
        }

        return $listingExamAbroad;
    }
    
    
    private function _prepareListingAttributesTableData($params) {
        $courseData = $params['courseData'];        
        
        /*
         * Preapring listing_attributes_table table data..
         */
        $listing_attributes_table = array();
        $key = 0;
        if($courseData['courseDescription'] != "") {                    
            $listing_attributes_table[$key]['listing_type'] = 'course';
            $listing_attributes_table[$key]['listing_type_id'] = $courseData['courseId'];
            $listing_attributes_table[$key]['caption'] = 'Course Description';
            $listing_attributes_table[$key]['attributeValue'] = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$courseData['courseDescription']));//html_entity_decode($courseData['courseDescription']);
            $listing_attributes_table[$key]['status'] = $courseData['listingStatus'];
            $key++;
        }
        
        return $listing_attributes_table;
    }
    
    private function _prepareListingCategoryTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * Preapring listing_category_table table data..
         */
        $listing_category_table = array();
        $listing_category_table['category_id'] = $courseData['subCatId'];
        $listing_category_table['listing_type'] = 'course';
        $listing_category_table['listing_type_id'] = $courseData['courseId'];
//        $listing_category_table['version'] = $version;
        $listing_category_table['status'] = $courseData['listingStatus'];
        
        return $listing_category_table;
    }
    
    private function _prepareClientCourseToLDBCourseMappingTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
         // Preparing clientCourseToLDBCourseMapping table data..
                 
        $clientCourseToLDBCourseMapping = array();
        $key = 0;
        if($courseData['ldbCourseId'] != "") {
            $clientCourseToLDBCourseMapping[$key]['clientCourseID']            = $courseData['courseId'];
            $clientCourseToLDBCourseMapping[$key]['LDBCourseID']               = $courseData['ldbCourseId'];
            $clientCourseToLDBCourseMapping[$key]['specializationDescription'] = '';
//            $clientCourseToLDBCourseMapping[$key]['version'] = $version;
            $clientCourseToLDBCourseMapping[$key]['status'] = $courseData['listingStatus'];
            
            $LDBCourseDetails['data'][$key]['LDBCourseID']          = $courseData['ldbCourseId'];
            $key++;
        }
        
        //$LDBCourseID = $this->abroadCMSModelObj->getLDBCourseId($courseData['mainCatId'],$courseData['courseType'], $courseData['courseLevel']);

        if(!empty($courseData['courseSpecializationDetails'])) {
            foreach ($courseData['courseSpecializationDetails'] as $courseSpecializationdata) {
                $clientCourseToLDBCourseMapping[$key]['clientCourseID']     = $courseData['courseId'];
                $clientCourseToLDBCourseMapping[$key]['LDBCourseID']        = $courseSpecializationdata['Id'];
                $clientCourseToLDBCourseMapping[$key]['specializationDescription'] = $courseSpecializationdata['desc'];
//                $clientCourseToLDBCourseMapping[$key]['version']            = $version;
                $clientCourseToLDBCourseMapping[$key]['status']             = $courseData['listingStatus'];
                $LDBCourseDetails['data'][$key]['LDBCourseID']          = $courseSpecializationdata['Id'];
                $key++;
            }    

        }
        //Fixed for Null subcategory Issue for AbroadCategoryPageData
        $LDBCourseDetails['SubCategory']                = $courseData['subCatId'];
        $LDBCourseDetails['MainCategory']               = $courseData['mainCatId'];
        return array( "clientCourseToLDBCourseMapping"=> $clientCourseToLDBCourseMapping, "LDBCourseDetails" => $LDBCourseDetails );
    }    
    
    private function _prepareCompanyLogoMappingTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * Preapring company_logo_mapping table data..
         */
        $company_logo_mapping = array();
        $key = 0;
        foreach($courseData['recruitingCompaniesArray'] as $myKey => $recruitingCompanyId) {
            if($recruitingCompanyId == "") {
                continue;
            }
            $company_logo_mapping[$key]['listing_id'] = $courseData['courseId'];
            $company_logo_mapping[$key]['listing_type'] = 'course';
            $company_logo_mapping[$key]['company_order'] = $key;
            $company_logo_mapping[$key]['logo_id'] = $recruitingCompanyId;
            $company_logo_mapping[$key]['status'] = $courseData['listingStatus'];
//            $company_logo_mapping[$key]['version'] = $version;
            $company_logo_mapping[$key]['company_order'] = ($key+1);
            $company_logo_mapping[$key]['institute_id'] = $courseData['departmentId'];
            $company_logo_mapping[$key]['linked'] = 'yes';
            $key++;
        }
        
        return $company_logo_mapping;
    }
    
    private function _prepareAbroadCourseCustomValuesMapping($params) {
        $courseData = $params['courseData'];        
        /*
         * Preparing abroad_course_custom_values_mapping table data..
         */
        $custom_values_mapping = array();
        foreach($courseData['customValuesData'] as $valueType => $customValuesDataSet) {
            foreach ($customValuesDataSet as $customValuesData){
                if(trim($customValuesData['caption'],' ') == '' && trim($customValuesData['value'],' ') == '')
                {
                    continue;
                }
                array_push($custom_values_mapping,  array('course_id' => $courseData['courseId'],
                                                        'valueType' => $valueType,
                                                        'caption'   => $customValuesData['caption'],
                                                        'value'     => $customValuesData['value'],
                                                        'status'    => $courseData['listingStatus'])
                      );
            }
        }
        return $custom_values_mapping;
    }
    
    private function _prepareListingExternalLinksTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * Preapring listing_external_links table data..
         */
        $key = 0;
        if($courseData['courseWebsite'] != "") {
            $listing_external_links_array[$key]['key'] = 'courseWebsite';
            $listing_external_links_array[$key]['value'] = $courseData['courseWebsite'];
            $key++;
        }
        if($courseData['courseDurationLink'] != "") {            
            $listing_external_links_array[$key]['key'] = 'courseDurationLink';
            $listing_external_links_array[$key]['value'] = $courseData['courseDurationLink'];
            $key++;
        }
        
        if($courseData['applicationDeadlineLink'] != "") {
            $listing_external_links_array[$key]['key'] = 'applicationDeadlineLink';
            $listing_external_links_array[$key]['value'] = $courseData['applicationDeadlineLink'];
            $key++;
        }
        if($courseData['admissionWebsiteLink'] != "") {
            $listing_external_links_array[$key]['key'] = 'admissionWebsiteLink';
            $listing_external_links_array[$key]['value'] = $courseData['admissionWebsiteLink'];
            $key++;
        }
        if($courseData['englishProficiencyLink'] != "") {
            $listing_external_links_array[$key]['key'] = 'englishProficiencyLink';
            $listing_external_links_array[$key]['value'] = $courseData['englishProficiencyLink'];
            $key++;
        }
        if($courseData['anyOtherEligibility'] != "") {
            $listing_external_links_array[$key]['key'] = 'anyOtherEligibility';
            $listing_external_links_array[$key]['value'] = $courseData['anyOtherEligibility'];
            $key++;
        }
        if($courseData['feesPageLink'] != "") {
            $listing_external_links_array[$key]['key'] = 'feesPageLink';
            $listing_external_links_array[$key]['value'] = $courseData['feesPageLink'];
            $key++;
        }
        if($courseData['scholarshipLinkCourseLevel'] != "") {
            $listing_external_links_array[$key]['key'] = 'scholarshipLinkCourseLevel';
            $listing_external_links_array[$key]['value'] = $courseData['scholarshipLinkCourseLevel'];
            $key++;
        }
        if($courseData['scholarshipLinkDeptLevel'] != "") {
            $listing_external_links_array[$key]['key'] = 'scholarshipLinkDeptLevel';
            $listing_external_links_array[$key]['value'] = $courseData['scholarshipLinkDeptLevel'];
            $key++;
        }
        if($courseData['scholarshipLinkUniversityLevel'] != "") {
            $listing_external_links_array[$key]['key'] = 'scholarshipLinkUniversityLevel';
            $listing_external_links_array[$key]['value'] = $courseData['scholarshipLinkUniversityLevel'];
            $key++;
        }
        if($courseData['facultyInfoLink'] != "") {
            $listing_external_links_array[$key]['key'] = 'facultyInfoLink';
            $listing_external_links_array[$key]['value'] = $courseData['facultyInfoLink'];
            $key++;
        }
        if($courseData['alumniInfoLink'] != "") {
            $listing_external_links_array[$key]['key'] = 'alumniInfoLink';
            $listing_external_links_array[$key]['value'] = $courseData['alumniInfoLink'];
            $key++;
        }
        if($courseData['faqLink'] != "") {
            $listing_external_links_array[$key]['key'] = 'faqLink';
            $listing_external_links_array[$key]['value'] = $courseData['faqLink'];
            $key++;
        }
        
        unset($key);
        $listing_external_links = array();
        foreach($listing_external_links_array as $key => $attributeArray) {                        
            $listing_external_links[$key]['listing_type_id'] = $courseData['courseId'];
            $listing_external_links[$key]['listing_type'] = 'course';
            $listing_external_links[$key]['link_type'] = $attributeArray['key'];
            $listing_external_links[$key]['link'] = $attributeArray['value'];
            $listing_external_links[$key]['status'] = $courseData['listingStatus'];
        }
        
        return $listing_external_links;
    }
    
    
    private function _prepareCourseClassProfileTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * Preapring course_class_profile table data..
         */
        $course_class_profile = array();
        $course_class_profile['course_id'] = $courseData['courseId'];
        $course_class_profile['average_work_experience'] = $courseData['averageWorkExp'];
        $course_class_profile['average_gpa'] = $courseData['averageBachelorsGPA'];
        $course_class_profile['average_xii_percentage'] = $courseData['averageClass12Percentage'];
        $course_class_profile['average_gmat_score'] = $courseData['averageGMATScore'];
        $course_class_profile['average_age'] = $courseData['averageAge'];
        $course_class_profile['percentage_international_students'] = $courseData['internationalStudentsPercentage'];
        $course_class_profile['status'] = $courseData['listingStatus'];
        
        return $course_class_profile;
    }

    private function _prepareCourseJobProfileTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
         /*
         * Preapring course_job_profile table data..
         */
        $course_job_profile = array();
        $course_job_profile['course_id'] = $courseData['courseId'];
        $course_job_profile['percentage_employed'] = $courseData['percentageEmployed'];
        $course_job_profile['average_salary'] = $courseData['avgSalary'];
        $course_job_profile['average_salary_currency_id'] = $courseData['avgSalaryCurrency'];
        $course_job_profile['isInternshipAvailable'] = $courseData['isInternshipAvailable'];
        $course_job_profile['popular_sectors'] = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$courseData['popularSectors'])); //html_entity_decode($courseData['popularSectors']);
        $course_job_profile['internships'] = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$courseData['internships'])); //html_entity_decode($courseData['internships']);
        $course_job_profile['internships_link'] = $courseData['internshipsLink'];
        $course_job_profile['career_services_link'] = $courseData['careerServiceWebsiteLink'];
        $course_job_profile['status'] = $courseData['listingStatus'];
        
        return $course_job_profile;
    }
    
    private function _prepareCourseStartDateInfoTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * preparing data for course_start_date_info table..
         */
        $course_start_date_info = array();
        $key = 0;
        foreach($courseData['courseStartDateArray'] as $myKey => $month) {
            if($month == "") {
                continue;
            }
            $course_start_date_info[$key]['course_id'] = $courseData['courseId'];
            $course_start_date_info[$key]['start_date_month'] = $month;
            $course_start_date_info[$key]['status'] =  $courseData['listingStatus'];
            $key++;
        }
        
        return $course_start_date_info;
    }
    
    private function _prepareSnapshotCourseToDetailedCourseMappingTableData($params, $version = 1){
         $courseData = $params['courseData'];        
        
        /*
         * preparing data for snapshotCourse mapping table..
         */
           $snapshotCourseMappingTableInfo = array();      
           $snapshotCourseMappingTableInfo['snapshotcourse_id'] = $courseData['snapshotCourseId'];
           $snapshotCourseMappingTableInfo['newcourse_id'] = $courseData['courseId'];
           $snapshotCourseMappingTableInfo['status'] = $courseData['listingStatus'];
          
        return $snapshotCourseMappingTableInfo;
    }


    
    private function _prepareListingCMSUserTrackingTableData($params, $version = 1) {
        $courseData = $params['courseData'];        
        
        /*
         * preparing data for listingCMSUserTracking table..
         */
        $listingCMSUserTrackingTableInfo = array();
        if(trim($courseData['userComments']) != "") {        
            $listingCMSUserTrackingTableInfo['listingId'] = $courseData['courseId'];
            $listingCMSUserTrackingTableInfo['userId'] = $params['userInfo']['userid'];
            $listingCMSUserTrackingTableInfo['tabUpdated'] = 'course';
            $listingCMSUserTrackingTableInfo['comments'] = $courseData['userComments'];
            $listingCMSUserTrackingTableInfo['updatedAt'] = date('Y-m-d H:i:s');
        }
        
        return $listingCMSUserTrackingTableInfo;
    }

    private function _prepareCourseCategoryPageData($params, $LDBCourseDetails, $version = 1) {
        $courseData     = $params['courseData'];

        if( $courseData['listingStatus'] != ENT_SA_PRE_LIVE_STATUS)
            return array();

        $location_data  = $this->abroadCMSModelObj->getlocationDetailsByCityId($courseData['courseCityId']);
        $ldb_course_list = array();

        $courseCategoryPageData                     = array();
        $index = 1;
        foreach($LDBCourseDetails['data'] as $key=>$data)
        {
            $courseCategoryPageData[$index]['course_id']        = $courseData['courseId'];
            $courseCategoryPageData[$index]['course_level']     = $courseData['courseLevel'];

            $courseCategoryPageData[$index]['ldb_course_id']    = $data['LDBCourseID'];
            $courseCategoryPageData[$index]['sub_category_id']  = $LDBCourseDetails['SubCategory'];
            $courseCategoryPageData[$index]['category_id']      = $LDBCourseDetails['MainCategory'];
            $courseCategoryPageData[$index]['pack_type']        = $courseData['pack_type'];

            $courseCategoryPageData[$index]['institute_id']     = $courseData['departmentId'];
            $courseCategoryPageData[$index]['university_id']    = $courseData['universityId'];
            $courseCategoryPageData[$index]['city_id']          = $courseData['courseCityId'];
            $courseCategoryPageData[$index]['state_id']         = $location_data['stateId'];
            $courseCategoryPageData[$index]['country_id']       = $courseData['courseCountryId'];
            $courseCategoryPageData[$index]['status']           = ENT_SA_PRE_LIVE_STATUS;
            
            $index++;
        }

        return $courseCategoryPageData;
    }

    private function _prepareAbroadCourseApplicationDetailMapping($params, $version=1){
        
        $courseData = $params['courseData'];        
        $applicationDetail = array();
        $applicationDetail['courseId']                      = $courseData['courseId'];
        $applicationDetail['universityCourseProfileId']     = $courseData['universityCourseProfileId'];
        $applicationDetail['additionalRequirement']         = $courseData['additionalRequirement'];
        $applicationDetail['isInterviewRequired']           = $courseData['isInterviewRequired'];
        $applicationDetail['interviewMonth']                = $courseData['interviewMonth'];  
        $applicationDetail['interviewYear']                 = $courseData['interviewYear'];
        $applicationDetail['interviewprocessDetail']        = $courseData['interviewprocessDetail'];   
        $applicationDetail['applicationFeeDetail']          = $courseData['applicationFeeDetail'];
        $applicationDetail['feeAmount']                     = $courseData['feeAmount'];
        $applicationDetail['currencyId']                    = $courseData['applicationDetailCurrencyId'];
        $applicationDetail['isCreditCardAccepted']          = $courseData['isCreditCardAccepted'];
        $applicationDetail['isDebitCardAccepted']           = $courseData['isDebitCardAccepted'];
        $applicationDetail['iswiredMoneyTransferAccepted']  = $courseData['iswiredMoneyTransferAccepted'];
        $applicationDetail['isPaypalAccepted']              = $courseData['isPaypalAccepted'];   
        $applicationDetail['feeDetails']                    = $courseData['feeDetails'];
        $applicationDetail['transcriptEvaluationNeeded']              = $courseData['transcriptEvaluationNeeded'];
        $applicationDetail['isApplicationViaCommonApplication']       = $courseData['isApplicationViaCommonApplication'];
        $applicationDetail['commonApplicationDescription']    = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$courseData['commonApplicationDescription']));
        $applicationDetail['internationStudentAddress']    = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$courseData['internationStudentAddress']));
        $applicationDetail['status']                        = $courseData['listingStatus'];
        $applicationDetail['addedOn']                       = ($courseData['shikshaApplyAddedOn']=='')?date('Y-m-d H:i:s'):$courseData['shikshaApplyAddedOn'];
        $applicationDetail['addedBy']                       = ($courseData['shikshaApplyAddedBy']=='')?$params['userInfo']['userid']:$courseData['shikshaApplyAddedBy'];
        $applicationDetail['modifiedOn']                    = date('Y-m-d H:i:s');
        $applicationDetail['modifiedBy']                    = $params['userInfo']['userid'];;

        return $applicationDetail;
        
    }
    public function _prepareAbroadCourseApplicationEligibilityDetails($params){
        
        $courseData = $params['courseData'];        
        $applicationDetail = array();
        $applicationDetail['courseId']                      = $courseData['courseId'];
        $applicationDetail['isWorkExperinceRequired']       = $courseData['isWorkExperinceRequired'];
        $applicationDetail['workExperniceValue']            = $courseData['workExperniceValue'];
        $applicationDetail['workExperinceDescription']      = $courseData['workExperinceDescription'];
        $applicationDetail['12thCutoff']                    = $courseData['12thCutoff'];
        $applicationDetail['12thcomments']                  = $courseData['12thcomments'];    
        $applicationDetail['bachelorScoreUnit']             = $courseData['bachelorScoreUnit'];
        $applicationDetail['bachelorCutoff']                = $courseData['bachelorCutoff'];
        $applicationDetail['bachelorComments']              = $courseData['bachelorComments'];
        $applicationDetail['pgCutoff']                      = $courseData['pgCutoff'];
        $applicationDetail['pgComments']                    = $courseData['pgComments'];
        $applicationDetail['isThreeYearDegreeAccepted']     = $courseData['isThreeYearDegreeAccepted'];
        $applicationDetail['threeYearDegreeDescription']    = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$courseData['threeYearDegreeDescription']));
        $applicationDetail['applyDocumentChecklist']    = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$courseData['applyDocumentChecklist']));
        $applicationDetail['status']                        = $courseData['listingStatus'];
        $applicationDetail['addedOn']                       = ($courseData['applicationEligibilityAddedOn']=='')?date('Y-m-d H:i:s'):$courseData['applicationEligibilityAddedOn'];
        $applicationDetail['addedBy']                       = ($courseData['applicationEligibilityAddedBy']=='')?$params['userInfo']['userid']:$courseData['applicationEligibilityAddedBy'];
        $applicationDetail['modifiedOn']                    = date('Y-m-d H:i:s');
        $applicationDetail['modifiedBy']                    = $params['userInfo']['userid'];;

        return $applicationDetail;
        
    }
            
    function postCourseForm($params) {
        $version = 1;
        
        if($params['courseData']['snapshotCourseId'] != ""){   
           
                $snapshotcourseToDetailedCourseMappingTableInfo = $this->_prepareSnapshotCourseToDetailedCourseMappingTableData($params,$version);
        }
        
        $listingsMainTableInfo = $this->_prepareListingsMainTableData($params, $version);
        
        $course_details = $this->_prepareCourseDetailsTableData($params, $version);
        
        $scholarship_details = array($this->_prepareCourseScholarshipDetailsTableData($params));
        
        $course_attributes = $this->_prepareCourseAttributesTableData($params, $version);
        
        $listingContactDetailsTableInfo = $this->_prepareListingContactDetailsTableData($params, $version);        
        
        $courseLocationAttribute = $this->_prepareCourseLocationAttributeTableData($params, $version);         
        
        $listingExamAbroad = $this->_prepareListingExamAbroadTableData($params, $version);        
        
        $listing_attributes_table = $this->_prepareListingAttributesTableData($params);
        
        $listing_category_table = $this->_prepareListingCategoryTableData($params, $version); 
        
        $LDBCourseDetailsArr = $this->_prepareClientCourseToLDBCourseMappingTableData($params, $version);        
   
        $company_logo_mapping = $this->_prepareCompanyLogoMappingTableData($params, $version); 
        
        $listing_external_links = $this->_prepareListingExternalLinksTableData($params, $version); 
        
        $course_class_profile = $this->_prepareCourseClassProfileTableData($params, $version);        
        
        $course_job_profile = $this->_prepareCourseJobProfileTableData($params, $version);        
        
        $course_start_date_info = $this->_prepareCourseStartDateInfoTableData($params, $version);    
        
        $listingCMSUserTrackingTableInfo = $this->_prepareListingCMSUserTrackingTableData($params, $version);
        
        $abroad_course_custom_values_mapping = $this->_prepareAbroadCourseCustomValuesMapping($params);
        
        if($params['courseData']['shikshaApply']==1){
            $abroad_course_application_detail_mapping = $this->_prepareAbroadCourseApplicationDetailMapping($params);
        }
        $abroadCourseApplicationEligibilityDetails = $this->_prepareAbroadCourseApplicationEligibilityDetails($params);
        $clientCourseToLDBCourseMapping = $LDBCourseDetailsArr["clientCourseToLDBCourseMapping"];
        $LDBCourseDetails               = $LDBCourseDetailsArr["LDBCourseDetails"];
        
        $abroadCategoryPageData = $this->_prepareCourseCategoryPageData($params, $LDBCourseDetails, $version);

        $modelParams = array(
                            'listings_main'                     => $listingsMainTableInfo,
                            'course_details'                    => $course_details,
                        //    'course_attributes'                 => $course_attributes,
                            'listing_contact_details'           => $listingContactDetailsTableInfo,
                            'course_location_attribute'         => $courseLocationAttribute,
                        //    'listingExamAbroad'                 => $listingExamAbroad,
                        //    'listing_attributes_table'          => $listing_attributes_table,
                        //    'listing_category_table'            => $listing_category_table,
                        //    'clientCourseToLDBCourseMapping'    => $clientCourseToLDBCourseMapping,
                        //    'company_logo_mapping'              => $company_logo_mapping,
                        //    'listing_external_links'            => $listing_external_links,
                            'course_class_profile'              => $course_class_profile,
                            'course_job_profile'                => $course_job_profile,
                            'abroad_course_scholarship_mapping' => $scholarship_details
                            );
        
        /*
         *  In "draft" state, there may be scenario where we will not have Data for these tables..
         */
        if(count($snapshotcourseToDetailedCourseMappingTableInfo)){
            $modelParams['snapshot_course_mapping'] = $snapshotcourseToDetailedCourseMappingTableInfo;
        }
        if(count($course_attributes)) {
            $modelParams['course_attributes'] = $course_attributes;
        }
        if(count($listingExamAbroad)) {
            $modelParams['listingExamAbroad'] = $listingExamAbroad;
        }
        if(count($listing_attributes_table)) {
            $modelParams['listing_attributes_table'] = $listing_attributes_table;
        }
        if(count($clientCourseToLDBCourseMapping)) {
            $modelParams['clientCourseToLDBCourseMapping'] = $clientCourseToLDBCourseMapping;
        }
        if(count($company_logo_mapping)) {
            $modelParams['company_logo_mapping'] = $company_logo_mapping;
        }
        if(count($listing_external_links)) {
            $modelParams['listing_external_links'] = $listing_external_links;
        }
        if(count($course_start_date_info)) {
            $modelParams['course_start_date_info'] = $course_start_date_info;
        }
        if($listing_category_table['category_id'] != "") {            
            $modelParams['listing_category_table'] = $listing_category_table;
        }
        if(count($listingCMSUserTrackingTableInfo)) {
            $modelParams['listingCMSUserTracking'] = $listingCMSUserTrackingTableInfo;
        }
        if(count($abroadCategoryPageData)) {
            $modelParams['abroadCategoryPageData'] = $abroadCategoryPageData;
        }
        if(count($abroad_course_custom_values_mapping)) {
            $modelParams['abroad_course_custom_values_mapping'] = $abroad_course_custom_values_mapping;
        }
        
        if(count($abroad_course_application_detail_mapping)){
            $modelParams['courseApplicationDetails'] = $abroad_course_application_detail_mapping;
        }
        if(count($abroadCourseApplicationEligibilityDetails)){
            $modelParams['abroadCourseApplicationEligibiltyDetails']=$abroadCourseApplicationEligibilityDetails;
        }
        $flagParams = array();        
        $flagParams['status'] = $params['courseData']['listingStatus'];
        $flagParams['flow'] = $params['courseData']['flow'];
        
        $postingFlag = $this->abroadCMSModelObj->addCourse($modelParams, $flagParams);
        return $postingFlag;
    }
    

    function postDepartmentForm($params =  array()) {
        $instituteTableInfo = array();
        $instituteTableInfo['institute_id']     = $params['department_id'];
        $instituteTableInfo['institute_name']   = $params['schoolName'];
        $instituteTableInfo['abbreviation']     = $params['schoolAcronym'];
        $instituteTableInfo['status']           = 'draft';
        //$instituteTableInfo['version']          = 1;
        $instituteTableInfo['profile_percentage_completion'] = $params['percentageCompletion'];
        $instituteTableInfo['institute_type'] = "Department";
        
        
        $listingsMainTableInfo = array();
        $listingsMainTableInfo['listing_type_id']   = $params['department_id'];
        $listingsMainTableInfo['listing_title']     = $params['schoolName'];
        $listingsMainTableInfo['listing_type']      = 'institute';
        $listingsMainTableInfo['status']            = 'draft';
        if($params['action'] == "ADD"){
            // client id/username should be same as that of its university
            $result = $this->abroadCMSModelObj->getListingMainData('university',$params['universityId']);
            $listingsMainTableInfo['username']          = $result[0]['username'];
            // editedBy the current user
            $listingsMainTableInfo['editedBy']          = $params['userId'];
            // ViewCount should be same as last time
            $listingsMainTableInfo['viewCount']         = 1;
        }
        else{
            // client id/username should be same as last time
            $result = $this->abroadCMSModelObj->getListingMainData('institute',$params['department_id']);
            $listingsMainTableInfo['username']          = $result[0]['username'];
            // editedBy the current user
            $listingsMainTableInfo['editedBy']          = $params['userId'];
            // ViewCount should be same as last time
            $listingsMainTableInfo['viewCount']         = $result[0]['viewCount'];
        }
        $listingsMainTableInfo['moderation_flag']   = 'moderated';
        $listingsMainTableInfo['last_modify_date']  = date('Y-m-d H:i:s');
        $listingsMainTableInfo['version']           = 1;
        $listingsMainTableInfo['listing_seo_title']         = $params['seo_title'];
        $listingsMainTableInfo['listing_seo_keywords']      = $params['seo_keywords'];
        $listingsMainTableInfo['listing_seo_description']   = $params['seo_description'];
        $listingsMainTableInfo['listing_seo_url']           = $params['seo_url'];
        
        if($params['action'] == 'EDIT') {
            $listingsMainTableInfo['submit_date']  = $params['departmentSubmitDate'];
        }
        
        $instituteUniversityMappingTableInfo = array();
        $instituteUniversityMappingTableInfo['university_id']   = $params['universityId'];
        $instituteUniversityMappingTableInfo['institute_id']    = $params['department_id'];
        $instituteUniversityMappingTableInfo['status']          = 'draft';
        
        $universityLocationInfo = $this->abroadCMSModelObj->getUniversityLocationInfo($params['universityId'],ENT_SA_PRE_LIVE_STATUS);
        $instituteLocationTableInfo = array();
        if($params['action'] == 'EDIT')
        {
            $instituteLocationTableInfo['institute_location_id'] = $params['old_institute_location_id'];
        }
        else{
            $instituteLocationTableInfo['institute_location_id'] = Modules::run('common/IDGenerator/generateId', 'instituteLocation');
        }
        $instituteLocationTableInfo['institute_id']          = $params['department_id'];
        $instituteLocationTableInfo['city_id']               = $universityLocationInfo['city_id'];
        $instituteLocationTableInfo['country_id']            = $universityLocationInfo['country_id'];
        $instituteLocationTableInfo['address_1']             = $universityLocationInfo['address'];
        $instituteLocationTableInfo['status']                = 'draft';
        $city = $this->abroadCMSModelObj->getCityById($universityLocationInfo['city_id']);
        $instituteLocationTableInfo['city_name']             = empty($city['city_name']) ? "":$city['city_name'];
//        $instituteLocationTableInfo['version']               = 1;
        
        $listingContactDetailsTableInfo = array();
        $listingContactDetailsTableInfo['institute_location_id']    = $instituteLocationTableInfo['institute_location_id'];
        $listingContactDetailsTableInfo['listing_type']             = 'institute';
        $listingContactDetailsTableInfo['listing_type_id']          = $params['department_id'];
        $listingContactDetailsTableInfo['status']                   = 'draft';
        $listingContactDetailsTableInfo['website']                  = $params['departmentWebsite'];
        $listingContactDetailsTableInfo['contact_main_phone']       = $params['contactPhoneNo'];
        $listingContactDetailsTableInfo['contact_email']            = $params['contactEmail'];
        $listingContactDetailsTableInfo['contact_person']           = $params['contactPersonName'];
//        $listingContactDetailsTableInfo['version']                  = 1;
        
        $listingExternalLinksTableInfo = array();
        foreach(array('facultyPageUrl', 'alumniPageUrl', 'fbPageUrl') as $linkType) {
            if(!empty($params[$linkType])) {
                $type = false;
                switch($linkType){
                    case 'facultyPageUrl':
                        $type = 'FACULTY_PAGE';
                        break;
                    case 'alumniPageUrl':
                        $type = 'ALUMNI_PAGE';
                        break;
                    case 'fbPageUrl':
                        $type = 'FB_PAGE';
                        break;
                    default:
                        break;
                }
                if(!empty($type)){
                    $listingExternalLinksTableInfo[$type]['listing_type_id']    = $params['department_id'];
                    $listingExternalLinksTableInfo[$type]['listing_type']       = 'institute';
                    $listingExternalLinksTableInfo[$type]['link_type']          = $type;
                    $listingExternalLinksTableInfo[$type]['link']               = $params[$linkType];
                    $listingExternalLinksTableInfo[$type]['status']             = 'draft';
                }
            }
        }
        
        $listingAttributesTableInfo = array();
        foreach(array('accreditationDetails', 'schoolDescription') as $attributeType) {
            if(!empty($params[$attributeType])) {
                $type = false;
                switch($attributeType){
                    case 'accreditationDetails':
                        $type = 'DEPT_ACCREDITATION_DETAILS';
                        break;
                    case 'schoolDescription':
                        $type = 'DEPT_DESCRIPTION';
                        break;
                    default:
                        break;
                }
                if(!empty($type)){
                    $listingAttributesTableInfo[$type]['listing_type_id']       = $params['department_id'];
                    $listingAttributesTableInfo[$type]['listing_type']          = 'institute';
                    $listingAttributesTableInfo[$type]['caption']               = $type;
                    $listingAttributesTableInfo[$type]['attributeValue']        = $params[$attributeType];
                    $listingAttributesTableInfo[$type]['status']                = 'draft';
                    //$listingAttributesTableInfo[$type]['version']               = 1;
                }
            }
        }
        
        $listingCMSUserTrackingTableInfo = array();
        $listingCMSUserTrackingTableInfo['userId']      = $params['userId'];
        $listingCMSUserTrackingTableInfo['tabUpdated']  = 'institute';
        $listingCMSUserTrackingTableInfo['listingId']   = $params['department_id'];
        $listingCMSUserTrackingTableInfo['comments']    = $params['userComments'];
        
        $flagParams = array();
        $flagParams['action'] = $params['action'];
        $flagParams['status'] = $params['status'];
        
        $modelParams = array(
                            'institute'                     => $instituteTableInfo,
                            'listings_main'                 => $listingsMainTableInfo,
                            'listing_attributes_table'      => $listingAttributesTableInfo,
                            'institute_university_mapping'  => $instituteUniversityMappingTableInfo,
                            'listing_external_links'        => $listingExternalLinksTableInfo,
                            'institute_location_table'      => $instituteLocationTableInfo,
                            'listing_contact_details'       => $listingContactDetailsTableInfo,
                            'listingCMSUserTracking'        => $listingCMSUserTrackingTableInfo
                            );
        
        $postingFlag = $this->abroadCMSModelObj->addDepartment($modelParams, $flagParams);
        return $postingFlag;
    }

    function addDummyDepartmentForUniversity($dbHandle = NULL, $universityDetails = array()) {
        if(empty($universityDetails)) {
            return FALSE;
        }
        $abroadCommonLib    = $this->CI->load->library('listingPosting/AbroadCommonLib');
        //$universityDetails  = $abroadCommonLib->getUniversityDetails($universityId, ENT_SA_PRE_LIVE_STATUS);
        // get the name to append with DUMMYDEPARTMENT
        $universityName     = $universityDetails['name'];
        
        $departmentId       = Modules::run('common/IDGenerator/generateId', 'institute');
        $departmentName     = $universityName . "_DUMMYDEPARTMENT";
        
        $instituteTableInfo = array();
        $instituteTableInfo['institute_id']                     = $departmentId;
        $instituteTableInfo['institute_name']                   = $departmentName;
        $instituteTableInfo['abbreviation']                     = "";
        $instituteTableInfo['status']                           = ENT_SA_PRE_LIVE_STATUS;
//        $instituteTableInfo['version']                          = 1;
        $instituteTableInfo['profile_percentage_completion']    = 0;
        $instituteTableInfo['institute_type']                   = "Department_Virtual";
        
        $listingsMainTableInfo = array();
        $listingsMainTableInfo['listing_type_id']   = $departmentId;
        $listingsMainTableInfo['listing_title']     = $departmentName;
        $listingsMainTableInfo['listing_type']      = 'institute';
        $listingsMainTableInfo['status']            = ENT_SA_PRE_LIVE_STATUS;
        $listingsMainTableInfo['username']          = $universityDetails['createdBy'];
        // editedBy the current user
        $listingsMainTableInfo['editedBy']          = $universityDetails['createdBy'];
        
        $listingsMainTableInfo['moderation_flag']   = 'moderated';
        $listingsMainTableInfo['last_modify_date']  = date('Y-m-d H:i:s');
        $listingsMainTableInfo['version']           = 1;
            
        
        $instituteUniversityMappingTableInfo = array();
        $instituteUniversityMappingTableInfo['university_id']   = $universityDetails['univId'];
        $instituteUniversityMappingTableInfo['institute_id']    = $departmentId;
        $instituteUniversityMappingTableInfo['status']          = ENT_SA_PRE_LIVE_STATUS;
        
        //$universityLocationInfo = $this->abroadCMSModelObj->getUniversityLocationInfo($universityId);
        //get location based data for university
        $universityLocationInfo  = array('city_id'      =>$universityDetails['city_id'   ],
                                         'country_id'   =>$universityDetails['country_id'],    
                                         'city'         =>$universityDetails['city'      ],    
                                         'address'      =>$universityDetails['address'   ]);
        
        $instituteLocationTableInfo = array();
        $instituteLocationTableInfo['institute_location_id'] = Modules::run('common/IDGenerator/generateId', 'instituteLocation');
        $instituteLocationTableInfo['institute_id']          = $departmentId;
        $instituteLocationTableInfo['city_id']               = $universityLocationInfo['city_id'];
        $instituteLocationTableInfo['country_id']            = $universityLocationInfo['country_id'];
        $instituteLocationTableInfo['address_1']             = $universityLocationInfo['address'];
        $instituteLocationTableInfo['status']                = ENT_SA_PRE_LIVE_STATUS;
        //$city = $this->abroadCMSModelObj->getCityById($universityLocationInfo['city_id']);
        $city = $universityLocationInfo['city'];
        $instituteLocationTableInfo['city_name']             = $city['city_name'];
//        $instituteLocationTableInfo['version']               = 1;
        
        $listingContactDetailsTableInfo = array();
        $listingContactDetailsTableInfo['institute_location_id']    = $instituteLocationTableInfo['institute_location_id'];
        $listingContactDetailsTableInfo['listing_type']             = 'institute';
        $listingContactDetailsTableInfo['listing_type_id']          = $departmentId;
        $listingContactDetailsTableInfo['status']                   = ENT_SA_PRE_LIVE_STATUS;
//        $listingContactDetailsTableInfo['version']                  = 1;
        
        $flagParams = array();
        $flagParams['action']   = 'ADD';
        $flagParams['status']   = 'live';
        $flagParams['dbHandle'] = $dbHandle;
        $flagParams['isTransactionActive'] = true;
        
        $modelParams = array(
                            'institute'                     => $instituteTableInfo,
                            'listings_main'                 => $listingsMainTableInfo,
                            'institute_university_mapping'  => $instituteUniversityMappingTableInfo,
                            'institute_location_table'      => $instituteLocationTableInfo,
                            'listing_contact_details'       => $listingContactDetailsTableInfo,
                            );
        $postingFlag = $this->abroadCMSModelObj->addDepartment($modelParams, $flagParams);
        //_p($postingFlag);
        //_p($departmentId);
        return $departmentId;
    }
    
    
    function addEditSnapshotCourse($snapshotData)
    {
        die;
        $result = $this->abroadCMSModelObj->addEditSnapshotCourse($snapshotData);
        return $result;
    }

    public function getDepartmentEditInformation($departmentId = NULL) {
        $result = array();
        if(empty($departmentId)){
            show_404();
            return $result;
        }
        $departmentInfo = $this->abroadCMSModelObj->getDepartmentEditInformation($departmentId);
        return $departmentInfo;
    }
    
    function getSnapshotLastAddedOnDate()
    {
        $result = $this->abroadCMSModelObj->getSnapshotLastAddedOnDate();
        return $result;
    }
    
    function checkAvailabilitySnapshotCourse($checkData)
    {
        $result = $this->abroadCMSModelObj->checkAvailabilitySnapshotCourse($checkData);
        return $result;
    }

    
    function getSnapshotCourseDataForEdit($courseId)
    {
        $result = $this->abroadCMSModelObj->getSnapshotCourseDataForEdit($courseId);
        return $result;
    }

    private function  getUniversityIdIfPresentInACountry($universitiesInACountry,$universityName)
    {
    	$universityId =  false;
    	foreach($universitiesInACountry as $key => $university)
    	{
    		if(strtoupper(trim($university['university_name'])) == strtoupper(trim($universityName))){
    			 
    			$universityId =  $university['university_id'];
    			break;
    		}
    
    	}
    	return $universityId;
    	 
    }
    
    private function checkIfSnapshotCourseWithSameCourseTypeExists($alreadyPresentsnapshotCoursesArray,$snapshotCourse){
    	$coursePresent = false; 
    	if(strtoupper($snapshotCourse['Course_Type']) == "BACHELORS")
    	{
    		$snapshotCourse['Course_Type'] = "Bachelors";
    	}else if(strtoupper($snapshotCourse['Course_Type']) == "MASTERS"){
    		$snapshotCourse['Course_Type'] = "Masters";
    	}else if(strtoupper($snapshotCourse['Course_Type']) == "PHD"){
    		$snapshotCourse['Course_Type'] = "PhD";
    	}else if(strtoupper($snapshotCourse['Course_Type']) == "CERTIFICATE - DIPLOMA"){
    		$snapshotCourse['Course_Type'] = "Certificate - Diploma";
    	}
    	foreach($alreadyPresentsnapshotCoursesArray as $snapshotData)
    	{
    		if((strtoupper($snapshotCourse['Course_Exact_Name']) == strtoupper($snapshotData['course_name'])) && strtoupper($snapshotCourse['Course_Type']) == strtoupper($snapshotData['course_type']) )
    		{
    			$coursePresent = true;
    			break;
    		}
    	}
    	return $coursePresent;
    }
    
    function checkDuplicateEntryInCSV($validSnapshotCourseList,$snapshotCourse){
    	$isDuplicateEntry = false;
    	foreach ($validSnapshotCourseList as $validSnapshotCourse){
    		if($validSnapshotCourse['Course_Exact_Name'] == $snapshotCourse['Course_Exact_Name'] 
    			&& $validSnapshotCourse['Course_Type'] == $snapshotCourse['Course_Type']){
    			$isDuplicateEntry = true;
    			break;
    		}
    	}
    	return $isDuplicateEntry;
   }
   
      function addBulkSnapshotCourse($uploadedData,$UserId,$comments){
    	$errorEntries;
    	$abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
    	$abroadCategories = $abroadCommonLib->getAbroadCategories();
    	$urlRegx =   "/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w!#]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w\[\]\|`<>:{}!\^\*\(\)\/]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/";
      	$noOfSnapshotCoursesInserted = 0;
    	foreach($uploadedData as $countryId => $snapshotCoursesInACountry){
    		$snapshotCourseDataToInsertInDB;
     		$universitiesInACountry = $this->abroadCMSModelObj->getUniversitiesForCountry($countryId);
    		foreach($snapshotCoursesInACountry as $universityName => $snapShotCoursesInAUniversity)
    		{   
    			$universityId = $this->getUniversityIdIfPresentInACountry($universitiesInACountry,$universityName);
    			if(!empty($universityId) && $universityId > 0){
    				$snapshotRelatedData['universityId'] = $universityId;
    				$snapshotRelatedData['countryId'] = $countryId;
    				// $alreadyPresentsnapshotCoursesInAUniversity = $this->abroadCMSModelObj->getAllSnapshotCoursesOfAUniversity($snapshotRelatedData);
    			   unset($snapshotRelatedData);
    			}
    			
    			$validSnapshotCourseList;
    			foreach ($snapShotCoursesInAUniversity as $index=>$snapshotCourse )
    			 {
    			 	
    			 	if(!empty($universityId) && $universityId > 0)//present
    			 	{   //further validation on snapshotcourse
                       //check if courese already exists in university or not
                     $isCourseExists =  $this->checkIfSnapshotCourseWithSameCourseTypeExists($alreadyPresentsnapshotCoursesInAUniversity, $snapshotCourse);
    			 	if($isCourseExists){
    			 		//Snashot Already Present --raise Error
    			 		$errorEntries[$index]["error_msg"] = "University :".$universityName." already has a course with name :".$snapshotCourse['Course_Exact_Name']." ";
    			 		$errorEntries[$index]["data"] = $snapshotCourse;
    			 	}
    			 	else {
    			 		//Check Further Validation
    			 		
    			 	        if(array_key_exists($snapshotCourse['Parent_Category'], $abroadCategories))	{
    			 	        	//$snapshotCourse['Child_Category']
    			 	        	if(array_key_exists($snapshotCourse['Child_Category'], $abroadCategories[$snapshotCourse['Parent_Category']]['subcategory']))
    			 	        	{  
    			 	        		
    			 	        		//Check For Course Type Exists
    			 	        		 if(strtoupper($snapshotCourse['Course_Type']) == "BACHELORS" 
    			 	        		 	|| strtoupper($snapshotCourse['Course_Type']) == "MASTERS" 
    			 	        		 	|| strtoupper($snapshotCourse['Course_Type']) == "PHD" 
                                        || strtoupper($snapshotCourse['Course_Type']) == "CERTIFICATE - DIPLOMA") {
    			 	        		     if(strtoupper($snapshotCourse['Course_Type']) == "BACHELORS")
    			 	        		     {
    			 	        		     	$snapshotCourse['Course_Type'] = "Bachelors";
    			 	        		     }else if(strtoupper($snapshotCourse['Course_Type']) == "MASTERS"){
    			 	        		     	$snapshotCourse['Course_Type'] = "Masters";
    			 	        		     }else if(strtoupper($snapshotCourse['Course_Type']) == "PHD"){
    			 	        		     	$snapshotCourse['Course_Type'] = "PhD";
    			 	        		     }else if(strtoupper($snapshotCourse['Course_Type']) == "CERTIFICATE - DIPLOMA"){
    			 	        		     	$snapshotCourse['Course_Type'] = "Certificate - Diploma";
    			 	        		     }
    			 	        		     
    			 	        		    /**** REMOVING URL VALIDATION FROM BULK UPLOAD ****/
    			 	        		 	/**if(true || empty($snapshotCourse['Course_website_link']) || !preg_match($urlRegx,preg_replace("/&/", "",preg_replace("/,/", "", preg_replace("/\((.*)\)/", "$1", $snapshotCourse['Course_website_link'])))))
    			 	        		 	***/
    			 	        		 	if(false)
    			 	        		 	{
    			 	        		 		$errorEntries[$index]["error_msg"] = "Course Wesite Link is not valid ";
    			 	        		 		$errorEntries[$index]["data"] = $snapshotCourse;
    			 	        		 	}
    			 	        		 	else{
    			 	        		 		 $isDuplicateCourse = $this->checkDuplicateEntryInCSV($validSnapshotCourseList,$snapshotCourse);
    			 	        		 		if(!$isDuplicateCourse)
    			 	        		 		{
    			 	        		 		$validSnapshotCourseList[] = array('Course_Exact_Name' => $snapshotCourse['Course_Exact_Name'],'Course_Type' => $snapshotCourse['Course_Type']);
    			 	        		 		$snapshotCourseDataToInsertInDB[] =  array(
    			 	        		 				'course_name' => $snapshotCourse['Course_Exact_Name'],
    			 	        		 				'course_type' => $snapshotCourse['Course_Type'],
    			 	        		 				'university_id' => $universityId,
    			 	        		 				'country_id' => trim($countryId), 
    			 	        		 				'category_id' => $snapshotCourse['Child_Category'],
    			 	        		 				'website_link' => $snapshotCourse['Course_website_link'],
    			 	        		 				'status' => 'live',
    			 	        		 				'created' => date('Y-m-d H:i:s'),
    			 	        		 				'last_modified' => date('Y-m-d H:i:s'),
    			 	        		 				'createdBy' => $UserId, 
    			 	        		 		 		'lastModifiedBy' => $UserId);
                                                                        $universityToUpdate[] = $universityId;
    			 	        		 		
    			 	        		 		}
    			 	        		 		else{
    			 	        		 			$errorEntries[$index]["error_msg"] = "Couse Cann't be added twice";
    			 	        		 			$errorEntries[$index]["data"] = $snapshotCourse;
    			 	        		 		}
    			 	        		 		
    			 	        		 	}
    			 	        		 	
    			 	        		 }
    			 	        		 else {
    			 	        		 	$errorEntries[$index]["error_msg"] = "In valid course type : ".$snapshotCourse['Course_Type']." ";
    			 	        		 	$errorEntries[$index]["data"] = $snapshotCourse;
    			 	        		 }
    			 	        		
    			 	        		//Make Insert Statements
    			 	        		
    			 	        	}
    			 	        	else {
    			 	        		$errorEntries[$index]["error_msg"] = "Parent Category ".$snapshotCourse['Parent_Category']." doesn't have Child Category  :".$snapshotCourse['Child_Category']." ";
    			 	        		$errorEntries[$index]["data"] = $snapshotCourse;
    			 	        	}	
    			 	          }
    			 	        else {
    			 	        	   $errorEntries[$index]["error_msg"] = "Parent Category doesn't exists";
    			 		           $errorEntries[$index]["data"] = $snapshotCourse;
    			 	        }
    			 		
    				 	}
    			 	}
    			 	else //not Present
    			 	{  //make entry in ErrorArray
    			 			
    			 		$errorEntries[$index]["error_msg"] = "University does not exist in the specified country.";
    			 		$errorEntries[$index]["data"] = $snapshotCourse;
    			 	}
    			 	
    			 }
    		unset($validSnapshotCourseList);	 
    		unset($universityId);	 
    		}
    		unset($universitiesInACountry);
    		if(count($snapshotCourseDataToInsertInDB)>=1)
    		{	
    		// $result = $this->abroadCMSModelObj->addBulkSnapshotCourses($snapshotCourseDataToInsertInDB,$comments);
    		$noOfSnapshotCoursesInserted = $noOfSnapshotCoursesInserted + count($snapshotCourseDataToInsertInDB);
    		}
    		unset($snapshotCourseDataToInsertInDB);
    	 }
         
         // Code to refresh Cache of Universities of which Snapshot courses are added
         array_unique($universityToUpdate);
         $this->CI->load->builder('ListingBuilder','listing');
         $listingBuilder = new ListingBuilder();
         $universityRepository = $listingBuilder->getUniversityRepository();
         $universityRepository->disableCaching();
         $universityRepository->findMultiple($universityToUpdate);
         
    	 $this->abroadCMSModelObj->addbulkSnapshotAdditionTrackingEntry($UserId,$noOfSnapshotCoursesInserted,$comments);
    	 $result ['errorEntries'] =  $errorEntries;
    	 $result ['noOfSnapshotCoursesInserted'] = $noOfSnapshotCoursesInserted;
    	return $result;
    
    	
    }
   
    
    function addEditUniversityData($formData)
    {
        // collect data for listing_attributes_table
        $this->_collectUniversityListingAttributes($formData);
        // collect data for universityScoreReporting
        $this->_collectUniversityScoreReporting($formData);
        $result = $this->abroadCMSModelObj->addEditUniversityData($formData);
        return $result;
    }
    private function _collectUniversityListingAttributes(& $univFormData)
    {
        $univFormData['listingAttributes'] = array();
        // combine all university listing attributes as a batch
        $listingType='university';
        // 1. univ wiki
        if($univFormData['univWiki'] != ""){
            $data = array(
                'listing_type'=>   $listingType,
                //'listing_type_id'=>$univFormData['universityId'], //we add this later for all attributes
                'caption'=>    "univ_wiki",
                'attributeValue'=> $univFormData['univWiki'],
                'status'=>     $univFormData['univSaveMode']
            );
            $univFormData['listingAttributes'][] = $data;
        }

        // 1. why join
        if($univFormData['univUSP'] != ""){
            $data = array(
                'listing_type'=>   $listingType,
                //'listing_type_id'=>$univFormData['universityId'], //we add this later for all attributes
                'caption'=>    "why_join",
                'attributeValue'=> $univFormData['univUSP'],
                'status'=>     $univFormData['univSaveMode']
            );
            $univFormData['listingAttributes'][] = $data;
        }
        // 2. lat long
        if($univFormData["latitude"] !="" && $univFormData["longitude"] != "")
        {
            foreach(array('latitude','longitude','latitudeDir','longitudeDir') as $caption){
                $data = array(
                    'listing_type'=>   $listingType,
                    'caption'=>    $caption,
                    'attributeValue'=> $univFormData[$caption],
                    'status'=>     $univFormData['univSaveMode']
                );
                $univFormData['listingAttributes'][] = $data;
            }
        }
        // 3. university stats
        foreach(array('percentIntlStud','totalIntlStud','campusSize','genderRatio','ugPgRatio','studentFacultyRatio','endowmentsCurr','endowmentsVal','campusCount','univRanking') as $caption){
            if($univFormData['univStatsData'][$caption]!=''){
                $data = array(
                    'listing_type'=>   $listingType,
                    'caption'=>    $caption,
                    'attributeValue'=> $univFormData['univStatsData'][$caption],
                    'status'=>     $univFormData['univSaveMode']
                );
                $univFormData['listingAttributes'][] = $data;
            }
        }
        //adding new custom kb fields
        $customKbFieldName = $univFormData['univStatsData']['customKbName'];
        $customKbFieldValue = $univFormData['univStatsData']['customKbValue'];
        foreach ($customKbFieldName as $key=>$value) {
            $data = array(
                'listing_type'=>   $listingType,
                'caption'=>    $value,
                'attributeValue'=> $customKbFieldValue[$key],
                'status'=>     $univFormData['univSaveMode']
            );
            $univFormData['listingAttributes'][] = $data;
        }

    }
    private function _collectUniversityScoreReporting(& $univFormData)
    {

        for($i=0;$i<count($univFormData['scoreReportingExam']);$i++)
        {
            if($univFormData['scoreReportingExam'][$i] !='' && $univFormData['scoreReportingCode'][$i] != ''){
                $univFormData['scoreReporting'][] = array(
                                                'scoreReportingExam'=>$univFormData['scoreReportingExam'][$i],
                                                'scoreReportingCode'=>$univFormData['scoreReportingCode'][$i],
                                                'status'=>$univFormData['univSaveMode']
                                            );
            }
        }
    }
    function getUniversityTableData($displayDataStatus, $paginatorObj, $searchUnivName)
    {
        return $this->abroadCMSModelObj->getUniversityTableData($displayDataStatus, $paginatorObj, $searchUnivName);
    }
	
    function getDepartmentTableData($displayDataStatus, $paginatorObj, $searchUnivName)
    {
        return $this->abroadCMSModelObj->getDepartmentTableData($displayDataStatus, $paginatorObj, $searchUnivName);
    }
    
	function deleteCourse($courseIds, $userId)
    {
        return $this->abroadCMSModelObj->deleteCourse($courseIds, $userId);
    }
    
    function deleteUniversity($univIds, $userId)
    {
        return $this->abroadCMSModelObj->deleteUniversity($univIds, $userId);
    }
     
    function deleteDepartment($departmentIds, $userId) {
    	return $this->abroadCMSModelObj->deleteDepartment($departmentIds, $userId);
    }

    function deleteSnapshotCourse($snapShotCourseIds, $userId)
    {
        return $this->abroadCMSModelObj->deleteSnapshotCourse($snapShotCourseIds, $userId);
    }
    
     function viewCourseTable($courseName,$status,$lowerLimit,$maxRows)
    {
        return $this->abroadCMSModelObj->getCourseDetails($courseName,$status,$lowerLimit,$maxRows);
    }
    function getInstituteLocation($instIds){
        return $this->abroadCMSModelObj->getInstituteLocation($instIds);
    }
    function getRankingDetails($rankName,$status,$lowerLimit,$maxRows){
        return $this->abroadCMSModelObj->getRankingDetails($rankName,$status,$lowerLimit,$maxRows);
    }
    function deleteRank($rankIds, $userId) {
    	$result  =  $this->abroadCMSModelObj->deleteRank($rankIds, $userId);
        if($result){
            $rankingCache = $this->CI->load->library('abroadRanking/cache/RankingCache');
            foreach($rankIds as $rankingPageId){
                $rankingCache->deleteRankingPage($rankingPageId);
            }
        }
        return $result;
    }
    
    public function postRankingPage($formData)
    {
        // derive the LDB course id from the given values
        if(!empty($formData['desiredCourse']))
            $ldbCourseId = $formData['desiredCourse'];
        else if(!empty($formData['couresType']) && !empty($formData['parentCategory']))
        {
            // get the ldb id from tCourseSpecializationMapping
            $ldbCourseId = $this->abroadCMSModelObj->fetchAbroadLDBCourse($formData['couresType'], $formData['parentCategory']);
        }
        else
            $ldbCourseId = 0;
            
        $formData['rankingLDBCourseId'] = $ldbCourseId;
        
        $formData['submit_date']        = empty($formData['submit_date'])       ? date('Y-m-d H:i:s')                   : $formData['submit_date'];
        $formData['rankingSeoTitle'] 	= $formData['rankingSeoTitle']          ? $formData['rankingSeoTitle']          : "";
	$formData['rankingDesc'] 	= $formData['rankingDesc']              ? $formData['rankingDesc']              : "";
	$formData['rankingKeywords'] 	= $formData['rankingKeywords']          ? $formData['rankingKeywords']          : "";
        $formData['rankingCreatedBy'] 	= empty($formData['rankingCreatedBy'])  ? $formData['rankingLastModifiedBy']    : $formData['rankingCreatedBy'];
        
        return $this->abroadCMSModelObj->saveRankingPage($formData);
    }
    
    public function getRankingDataForEditMode($rankingId)
    {
        return $this->abroadCMSModelObj->getRankingDataForEditMode($rankingId);
    }
	function addContent($contentData) {
    	return $this->abroadCMSModelObj->addContent($contentData);
    }
     function getContentData($content_id){
        return $this->abroadCMSModelObj->getContentData($content_id);
    }
   
     function getContentTableData($searchType,$displayDataStatus, $paginatorObj, $searchContentName){
    	return $this->abroadCMSModelObj->getContentTableData($searchType,$displayDataStatus, $paginatorObj, $searchContentName);
    }
    
    public function getUniversityUrl($universityId, $universityTitle, $countryName) {
        $url = "/".seo_url_lowercase($countryName).'/universities/'.seo_url_lowercase($universityTitle);
        return $url;
    }
    
    public function getDepartmentUrl($deptId, $deptTitle, $universityTitle, $countryName) {
        $url = $deptTitle."-".$universityTitle."-".$countryName;
        $url = "/".seo_url_lowercase($url);
        $url .= '-deptlisting-'.$deptId;
        return $url;
    }

    public function getCourseUrl($courseId, $courseTitle, $universityTitle, $countryName) {
        $url = seo_url_lowercase($countryName).'/universities/'.seo_url_lowercase($universityTitle)."/".seo_url_lowercase($courseTitle);
        $url = "/".$url;
        return $url;
    }
    
    
    public function getSnapshotCourseUrl($courseId, $courseTitle, $universityTitle, $countryName) {
        $url = $courseTitle."-in-".$universityTitle."-".$countryName;
        $url = SHIKSHA_STUDYABROAD_HOME."/".seo_url_lowercase($url);
        $url .= '-snaplisting-'.$courseId;
        return $url;
    }
    
    public function getExamUrl($examName , $examId) {
        $url = $examName;
        $url = "/".seo_url_lowercase($url);
        $url .= "-abroadexam".$examId;
        
        return $url;
    }
    /*
     * loads a subscription_client & passes to model along with the data for adding paid client
     */
    public function addPaidClientToCourse($data)
    {
        $subscriptionClient = $this->CI->load->library('Subscription_client');
        if($data['courseCountryFlag']){
            return $this->abroadCMSModelObj->addPaidClientToCourse($subscriptionClient,$data,$data['courseCountryFlag']);
        }else{
            return $this->abroadCMSModelObj->addPaidClientToCourse($subscriptionClient,$data);
        }
        
    }
    public function getPaidCoursesWithClient( $paginatorObj, $searchUnivName)
    {
        return $this->abroadCMSModelObj->getPaidCoursesWithClient( $paginatorObj, $searchUnivName);
    }
    
    public function checkListingExistinGivenState($listingId,$listingType,$status){
        return $this->abroadCMSModelObj->checkListingExistinGivenState($listingId,$listingType,$status);
    }
    
    public function getAdmissionGuideTableData($countryId,$lowerLimit,$rowsPerPage){
        return $this->abroadCMSModelObj->getAdmissionGuideTableData($countryId,$lowerLimit,$rowsPerPage);
    }
    
    public function deleteAdmissionGuide($guideId,$userId){
        return $this->abroadCMSModelObj->deleteAdmissionGuide($guideId,$userId);
    }
    
    public function getRMSResponseCount($mappingData)  
        {
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;
            $universityArray = array();
            foreach($mappingData as $tuple)
            {
               $universityArray[] = $tuple['university_id']; 
            }
            $this->universityRepository = $listingBuilder->getUniversityRepository();
            $course_ids = $this->universityRepository->getCoursesOfUniversities($universityArray);
            $universityWithCount = array();
            foreach($mappingData as $tuple)
            {
               $startDate = $tuple['created'];
               $endDate = ($tuple['status']=='deleted') ? $tuple['last_modified'] : date('Y-m-d H:i:s');
               $count = $this->abroadCMSModelObj->getRMSResponseCount($course_ids[$tuple['university_id']]['courseList'],$startDate,$endDate);
               $tuple['count'] = $count['response_count'];
               $universityWithCount[] = $tuple;
            }
		
	    return $universityWithCount;		
        }
        
    public function matchContentTitleAndSummary($contentId,$contentData)
    {
        $changedFlag = false;
        $result = $this->abroadCMSModelObj->fetchContentTitleAndSummary($contentId);
        if($result['title'] != $contentData['content_title']){
            $changedFlag = true;
        }elseif($result['summary'] != $contentData['content_summary']){
            $changedFlag = true;
        }
        return $changedFlag;
    }
    
    public function matchContentSections($contentId,$contentData)
    {
        $changedFlag = false;
        $results = $this->abroadCMSModelObj->fetchContentSections($contentId);
        $rowcount = count($contentData['content_details']);
        $oldRowCount = count($results);        
        if($rowcount != $oldRowCount){
          return $changedFlag = true;  
        }else{
            for($i=0;$i<$rowcount;$i++)
            {
               // _p($results[$i]['details']);
                //_p($contentData['content_details'][$i]);
                if(($results[$i]['details'] != $contentData['content_details'][$i]) || ($results[$i]['heading'] != $contentData['content_heading'][$i]))
                {
                     return $changedFlag = true;
                }
            }
        }
    }
    
    public function getRmsDataForExport($mappingData)
    {
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        
        $universityArray = array();
        foreach($mappingData as $tuple)
        {
           $universityArray[] = $tuple['university_id']; 
        }
        $this->universityRepository = $listingBuilder->getUniversityRepository();
        $course_ids = $this->universityRepository->getCoursesOfUniversities($universityArray);
        //_p($course_ids);
        $courseIdArr = array();
        foreach($course_ids as $courseIDs)
        {
            foreach($courseIDs['course_title_list'] as $key=>$value)
            {
            $courseIdArr[] = $key;
            }
        }
        $this->courseRepository 	= $listingBuilder->getAbroadCourseRepository();
        $courseDataObj = $this->courseRepository->findMultiple($courseIdArr);
        
        $responseDetails = array();
        $userIDs = array();
        $categoryIDs = array();
        $current_date= date('Y-m-d')." 23:59:59";
        foreach($mappingData as $tuple)
           {
              $result = $this->abroadCMSModelObj->getRMSResponseDetails($course_ids[$tuple['university_id']]['courseList'],$tuple['created'],$current_date);
              if(count($result)==0){
                continue;
              }
              foreach($result as &$res)
              {
                
                $userIDs[] = $res['userId'];
                //$categoryIDs[] = $res['category_id'];
                $categoryIDs[] = $courseDataObj[$res['listing_type_id']]->getCourseSubCategory();
                //_p($courseDataObj[$res['listing_type_id']]->getCourseSubCategory());
                $res['category_id'] = $courseDataObj[$res['listing_type_id']]->getCourseSubCategory();
                $responseDetails[] = $res;
              }
           }
          $userIDs = array_unique($userIDs);
          $categoryIDs = array_unique($categoryIDs);
          $this->categoryRepository = $categoryBuilder->getCategoryRepository();
          if(count($categoryIDs)>0 && $categoryIDs[0]>0){
            $catgoryDataObj = $this->categoryRepository->findMultiple($categoryIDs);
            }
          
          //_p($catgoryDataObj);
          $parentCategoryIds = array();
          foreach($catgoryDataObj as $categoryDataTuple)
          {
            $parentCategoryIds[] = $categoryDataTuple->getParentId();
          }
          $parentCategoryIds = array_unique($parentCategoryIds);
          if(count($parentCategoryIds)>0 && $parentCategoryIds[0]>0){
            $parentCatgoryDataObj = $this->categoryRepository->findMultiple($parentCategoryIds);
          }
          $examData =  $this->abroadCMSModelObj->getUsersExamTaken($userIDs);
          $locationData =  $this->abroadCMSModelObj->getUsersLocationPref($userIDs);
          $prefData =  $this->abroadCMSModelObj->getUsersPrefForTime($userIDs);
           //_p($responseDetails);
           //_p($prefData);
           $responseWithOtherData = array();
           foreach($responseDetails as $response){
            if(!key_exists($response['category_id'],$catgoryDataObj))
            {
                continue;
            }
            $response['examdata'] = $examData[$response['userId']];
            $response['locationData'] = $locationData[$response['userId']];
            $response['prefData'] = $prefData[$response['userId']];
            $response['courseData'] = $courseDataObj[$response['listing_type_id']];
            $response['categoryData'] = $catgoryDataObj[$response['category_id']]->getName();
            $response['parentCategoryData'] = $parentCatgoryDataObj[$catgoryDataObj[$response['category_id']]->getParentId()]->getName();
            //_p($response);
            $responseWithOtherData[] = $response;
           }
           return $responseWithOtherData;
        
    }
    
    /*public function getNotFoundExternalURL($tableName,$columnName,$entityFieldName,$whereClause,$lowerLimit,$upperLimit) {
        if(empty($tableName) || empty($columnName) || empty($entityFieldName)){
            return array();
        }
        return $this->abroadCMSModelObj->getNotFoundExternalURL($tableName,$columnName,$entityFieldName,$whereClause,$lowerLimit,$upperLimit);
        
    }*/
    
    public function deleteRMSUniversityCounsellorMapping($mappingId, $userId){
        return $this->abroadCMSModelObj->deleteRMSUniversityCounsellorMapping($mappingId, $userId);
    }
    /*
     * to create url for apply content pages
     * @param: array having $ContentSeoUrl(form value),$applyContentType,$contentId
     */
    public function getApplyContentSeoUrl($contentData)
    {
        $applyContentSeoUrl = "/".seo_url_lowercase($contentData['content_contentURL'])."-applycontent".$contentData['applyContentType'].$contentData['content_contentId'];
        return $applyContentSeoUrl;
    }
    
    public function getExamContentSeoUrl($examContentSeoUrl,$contentData)
    {
        $examContentSeoUrl = SHIKSHA_STUDYABROAD_HOME."/exams/".seo_url_lowercase($contentData['content_exam_name'])."/".seo_url_lowercase($examContentSeoUrl);
        return $examContentSeoUrl;
    }

    public function getSpecializationData($id){
        return $this->abroadCMSModelObj->getSpecializationData($id);
    }

    public function getCategorySubcategoryMappingData(){
        return $this->abroadCMSModelObj->getCategorySubcategoryMappingData();
    }

    public function getSpecializationIdsByNameAndCategory($oldData)
    {
        $ids = $this->abroadCMSModelObj->getSpecializationIdsByNameAndCategory($oldData);
        return $ids;
    }

    public function getCourseIdsMappedToSpecializations($specializationIDs,$mode='read')
    {
        $ids = $this->abroadCMSModelObj->getCourseIdsMappedToSpecializations($specializationIDs,$mode);
        return $ids;
    }

    function transactionStart()
    {
        $this->abroadCMSModelObj->transactionStart();
    }

    function transactionEnd()
    {
        $this->abroadCMSModelObj->transactionEnd();
    }

    public function deleteSpecializations($ids)
    {
        $ids = $this->abroadCMSModelObj->deleteSpecializations($ids);
    }

    public function addNewSpecialization($commonData){
        $check = $this->abroadCMSModelObj->confirmUniqueAddSpecialization($commonData);
        if(!$check){
            return array('error'=>1,'errorMessage'=>'This specialization already exists under the same subcategory. Please change the name to continue.');
        }
        $parentData = $this->abroadCMSModelObj->getParentDataByCategoryId($commonData['categoryId']);
        $fullData = array();
        foreach($parentData as $courseName => $courseLevelData){
            $fullData[] = array(
                'SpecializationName' => $commonData['name'],
                'CourseName' => $courseName,
                'CourseLevel' => $courseLevelData['CourseLevel'],
                'CourseLevel1' => $courseLevelData['CourseLevel1'],
                'CategoryId' => $commonData['categoryId'],
                'ParentId' => $courseLevelData['ParentId'],
                'SubmitDate' => date('Y-m-d H:i:s'),
                'Status' => 'live',
                'CourseReach' => 'national',
                'CourseDetail' => $commonData['description'],
                'scope' => 'abroad',
                'isEnabled' => '1'
            );
        }
        $this->abroadCMSModelObj->insertNewSpecialization($fullData,$commonData['subcategoryId']);
        return array('error'=>0);
    }

    public function editSpecialization($newData,$oldData){
        $idsToEdit = $this->abroadCMSModelObj->getSpecializationIdsByNameAndCategory($oldData);
        $check = $this->abroadCMSModelObj->confirmUniqueEditSpecialization($newData,$oldData,$idsToEdit);
        if(!$check){
            return array('error'=>1,'errorMessage'=>'This specialization already exists under the same subcategory. Please change the name to continue.');
        }
        $newData['SubmitDate'] = date('Y-m-d H:i:s');
        $this->abroadCMSModelObj->editExistingSpecialization($idsToEdit,$newData);
        return array('error'=>0);
    }
    
    public function validateExamContentRedirection($url){
        $url = base64_decode($url);
        $return = array('error'=>true,'errorMsg'=>'invalid url');
        if($url !=''){
            $return = $this->abroadCMSModelObj->validateExamContentRedirection($url);
            if($return['error']!=true){
                $sectionId = 0;
                $contentId = 0;
                $contentType = '';
                if(strpos($url,'-articlepage-') >0){
                    $urlDetailArr = explode('-articlepage-',$url);
                    if($urlDetailArr[1]>0){
                        $contentId = $urlDetailArr[1];
                        $contentType = 'article';
                    }
                    
                }elseif(strpos($url,'-abroadexam') >0){
                    $sectionId =1;
                    $urlDetailArr = explode('-abroadexam',$url);
                    if($urlDetailArr[1]>0){
                        $this->CI->config->load('abroadExamPageConfig');
                        $examPageConfig = $this->CI->config->item('abroad_exam_page_section_details');
                        foreach($examPageConfig as $key=>$data){
                            if(strpos($urlDetailArr[0],$data['urlPattern'])>0)
                            {
                            $sectionId = $key;
                            break;
                            }
                        }
                        if($sectionId <1){
                           $return = array('error'=>true,'errorMsg'=>'Please enter valid url');
                           return $return;
                        }
                        $contentId = $urlDetailArr[1];
                        $contentType = 'examPage';
                    }else{
                        $return = array('error'=>true,'errorMsg'=>'Please enter valid url');
                    }
                }else{
                    $return = array('error'=>true,'errorMsg'=>'Please enter valid url');
                }
                
                if($contentId >0 && $contentType!=''){
                    if($sectionId>1){
                       $url=''; 
                    }
                    $return = $this->abroadCMSModelObj->verifyContentDetails($contentId,$contentType,$url);
                    $return['sectionId'] = $sectionId;
                    $return['contentType'] = $contentType;
                }else{
                    $return = array('error'=>true,'errorMsg'=>'Please enter valid url');
                }
            }
        }
        return $return;
    }
    /*
	 * get course ids for a univ & add to abroadIndexLog table
	 */
	public function addUnivCoursesToAbroadIndexLog($univId=NULL)
	{
		if(is_null($univId))
		{
			return false;
		}
		$lib = $this->CI->load->library('listing/AbroadListingCommonLib');
		$courses = $lib->getUniversityCoursesGroupByStream($univId);
		$abroadCMSModel = $this->CI->load->model('listingPosting/abroadcmsmodel');
		$abroadCMSModel->checkAndAddCourseToIndexLog('course',$courses['stream']['course_ids'],'index');
	}

    public function generateCounsellorPageURL($name){
        $counsellorURLIds = $this->abroadCMSModelObj->getCounsellorURLIds();
        $randomNum = rand(100,999);
        while(in_array($randomNum, $counsellorURLIds)){
            $randomNum = rand(100,999);
        }
        $name = explode(" ",$name)[0];
        $data = array();
        $data['seoUrl'] = "/apply/counselors/".strtolower(trim($name))."-".$randomNum;
        return $data;
    }

    public function prepareExamApplyContentNavbarLinksDataToFill(&$data,$contentTypeId)
    {
        $abroadExamPageModel = $this->CI->load->model('abroadExamPages/abroadexampagemodel');
        $examContentDataForExamType = $abroadExamPageModel->getContentNavigationLinks($contentTypeId,$data['contentType']);
        if(!empty($examContentDataForExamType))
        {
            $data['examTitle'] = $examContentDataForExamType['content_type_title'];
            $data['linksData'] = $examContentDataForExamType['links_data'];
            $data['linksCount'] = count($data['linksData']);
            $data['examApplyContentTupleCount'] = $data['linksCount']>$data['examApplyContentTupleCount']?$data['linksCount']:$data['examApplyContentTupleCount'];
        }
    }

    public function postExamApplyContentNavbarLinksData($formData)
    {
        $abroadExamPageModel = $this->CI->load->model('abroadExamPages/abroadexampagemodel');
        $abroadCMSModel = $this->CI->load->model('listingPosting/abroadcmsmodel');
        $examContentDataForExamType = $abroadExamPageModel->getContentNavigationLinks($formData['content_type_id'],$formData['content_type']);
        if(empty($examContentDataForExamType))
        {

            $formData['updateFlag'] 	= false;
        }
        else
        {
            $formData['added_by'] = $examContentDataForExamType['added_by'];
            $formData['added_at'] = $examContentDataForExamType['added_at'];
            $formData['updateFlag'] 	= true;
        }
        $abroadCMSModel->saveExamApplyContentNavbarLinksData($formData);
        $saContentCache = $this->CI->load->library('blogs/cache/saContentCache');
        $saContentCache->deleteContentPageNavLinks($formData['content_type_id'],$formData['content_type']);
    }

    function restoreCourse($courseId) {
        return $this->abroadCMSModelObj->updateCourseEntriesStatus($courseId, ENT_SA_DELETED_STATUS, 'draft');;
    }

    function getDeletedCourse($courseId) {
	    return $this->abroadCMSModelObj->getDeletedCourse($courseId);
    }

    function isPaidCourse($courseId) {
        return $this->abroadCMSModelObj->isPaidCourse($courseId);
    }
}