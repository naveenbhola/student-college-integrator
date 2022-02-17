<?php
    global $suffixVal;
    $suffixVal = '';
    class listingsmigrationmodel extends MY_Model{
        private $dbHandle;
        private $dbHandleMode = 'read';
        private $thresholdCountForDataPicking = 5000;
        private $oldToNewFacilityMapping    =   array(  1 => 1,
                                                        2 => 2,
                                                        3 => 3,
                                                        4 => 9,
                                                        5 => 10,
                                                        6 => 11,
                                                        7 => 12,
                                                        8 => 13,
                                                        9 => 14,
                                                        10 => 15,
                                                        11 => 16,
                                                        12 => -1);
        
        public function __construct() {
            parent::__construct('Listing');
        }
        
        private function initiateModel($mode = 'read'){
            if($this->dbHandle && $this->dbHandleMode == 'write'){
                return;
            }
            $this->dbHandleMode = $mode;
            $this->dbHandle = NULL;
            if($mode == 'write'){
                $this->dbHandle = $this->getWriteHandle();
            }else{
                $this->dbHandle = $this->getWriteHandle(); // replacing it with master's handle as it creates problem during lag
                // $this->dbHandle = $this->getReadHandle();
            }
            return;
        }
        
        public function test1(){
            echo '<br/> IN MODEL';
            return;
        }
        
        public function getInstitutesForMigration($instituteIdsArray = array()){
            return;
            $result = array();
            $this->initiateModel('read');
            
            $sql    =   "SELECT distinct(ins.institute_id) FROM institute ins"
                        ." INNER JOIN institute_location_table ilt ON(ins.institute_id = ilt.institute_id)"
                        ." WHERE  ins.institute_type IN ('Academic_Institute','Multi_Location_Academic_Institute') AND ilt.country_id = 2"
                        ." AND ins.status = 'live' AND ilt.status = 'live'";
            if(is_array($instituteIdsArray) && count($instituteIdsArray) > 0){
                $sql    .=   " AND ins.institute_id IN(".  implode(',', $instituteIdsArray).")";
            }
            $resultSet  = $this->dbHandle->query($sql)->result_array();
            foreach ($resultSet AS $data){
                $result['live'][]   = $data['institute_id'];
            }
            
            $sql    =   "SELECT distinct(ins.institute_id) FROM institute ins"
                        ." INNER JOIN institute_location_table ilt ON(ins.institute_id = ilt.institute_id)"
                        ." WHERE  ins.institute_type IN ('Academic_Institute','Multi_Location_Academic_Institute') AND ilt.country_id = 2"
                        ." AND ins.status = 'draft' AND ilt.status = 'draft'";
            if(is_array($instituteIdsArray) && count($instituteIdsArray) > 0){
                $sql    .=   " AND ins.institute_id IN(".  implode(',', $instituteIdsArray).")";
            }
            $resultSet  = $this->dbHandle->query($sql)->result_array();
            foreach ($resultSet AS $data){
                $result['draft'][]  = $data['institute_id'];
            }
            //die;
            return $result;
        }

        public function getDeletedInstitutesForMigration(){
            return;
            $result = array();
            $this->initiateModel('read');
            $sql = "SELECT distinct(ins.institute_id) FROM institute ins INNER JOIN institute_location_table ilt ON (ins.institute_id = ilt.institute_id)  AND  ins.institute_type IN ('Academic_Institute','Multi_Location_Academic_Institute') AND ilt.country_id = 2  AND ins.status = 'deleted' AND ilt.status = 'deleted' JOIN course_details cd on cd.status='deleted' and cd.institute_id = ins.institute_id LEFT JOIN shiksha_institutes si on ins.institute_id = si.listing_id where si.listing_id IS NULL";
            $query = $this->dbHandle->query($sql)->result_array();
            return $this->getColumnArray($query,'institute_id');
        }
        
        /**
         * 
         */
        public function getOldInstituteTablesData($instituteIds = array(), $status){
            return;
            error_log("Fetching Data for $status Institutes. Count === ". count($instituteIds));	

            if(!is_array($instituteIds) || empty($instituteIds)){
                return array();
            }
            
            $instituteIds = array_values($instituteIds);
            $instituteData = array();
            $sqlForInstituteTable           =   "SELECT i.institute_id, i.institute_name, "/*abbreviation,*/." i.establish_year, microsite_link, logo_link,"
                                                ." featured_panel_link, institute_request_brochure_link, institute_request_brochure_year,i.status, l.submit_date, l.last_modify_date, l.username, l.editedBy "
                                                ." FROM institute i, listings_main l WHERE i.status = '".$status."' AND l.status = '".$status."' AND i.institute_id = l.listing_type_id AND l.listing_type IN ('institute')";
            $sqlForInstituteLocationTable   =   "SELECT institute_location_id, institute_id, locality_id, head_office, city_id, status"
                                                ." FROM institute_location_table WHERE status = '".$status."' ";
            /*
            $sqlForListingContactDetails    =   "SELECT contact_email, contact_main_phone, website, listing_type_id, institute_location_id"
                                                ." FROM listing_contact_details WHERE listing_type = 'institute' AND status = '".$status."' ";
            $sqlForInstituteUploadedMedia   =   "SELECT listing_type, listing_type_id, institute_location_id, media_id, media_type, name, url, description, thumburl"
                                                ." FROM institute_uploaded_media WHERE listing_type = 'institute' AND status = '".$status."' ";
            */
            
            $instituteIdsForQuery   = array();
            $countOfInsttuteIds     = count($instituteIds);
            $offsetForInstituteIds = 0;
            $this->initiateModel('read');

            $instituteData['institute_table']   = array();
            $instituteData['institute_location_table']  = array();
            $instituteData['company_logo_mapping']  = array();

            WHILE(TRUE){
                $instituteIdsForQuery = array_slice($instituteIds, $offsetForInstituteIds, 5000);
                if(!is_array($instituteIdsForQuery) || empty($instituteIdsForQuery)){
                    break;
                }
                $queryToGetInstituteTableData   = $sqlForInstituteTable." AND institute_id IN(".  implode(",", $instituteIdsForQuery).") ";
                // error_log("Query to fetch Institute Data=====".$queryToGetInstituteTableData);
                $resultSet = $this->dbHandle->query($queryToGetInstituteTableData)->result_array();

                foreach($resultSet AS $data){
                    $temp   =   array();
                    $temp['institute_name']                 = $data['institute_name'];
                    $temp['abbreviation']                   = $data['abbreviation'];
                    $temp['establish_year']                 = $data['establish_year'];
                    $temp['microsite_link']                 = $data['microsite_link'];
                    $temp['logo_link']                      = $data['logo_link'];
                    $temp['featured_panel_link']            = $data['featured_panel_link'];
                    $temp['institute_request_brochure_link']= $data['institute_request_brochure_link'];
                    $temp['institute_request_brochure_year']= $data['institute_request_brochure_year'];
                    $temp['status']                         = $data['status'];
                    $temp['created_on']                     = $data['submit_date'];
                    $temp['updated_on']                     = $data['last_modify_date'];
                    $temp['created_by']                     = $data['username'];
                    $temp['updated_by']                     = $data['editedBy'];
                    $instituteData['institute_table'][$data['institute_id']][]  = $temp;
                }
                
                $queryToGetInstituteLocationTableData   =   $sqlForInstituteLocationTable." AND institute_id IN(".implode(",", $instituteIdsForQuery).") ";
                // error_log("Query to fetch Institute Location Data=====".$queryToGetInstituteLocationTableData);
                $resultSet = $this->dbHandle->query($queryToGetInstituteLocationTableData)->result_array();

                foreach($resultSet AS $data){
                    $temp   =   array();
                    $temp['institute_location_id']  = $data['institute_location_id'];
                    $temp['locality_id']            = $data['locality_id'];
                    $temp['head_office']            = $data['head_office'];
                    $temp['city_id']                = $data['city_id'];
                    $temp['status']                 = $data['status'];
                    $instituteData['institute_location_table'][$data['institute_id']][] =   $temp;
                }
                
                /**
                $queryToGetListingContactDetailsTableData   =   $sqlForListingContactDetails." AND listing_type_id IN(".  implode(',', $instituteIdsForQuery).") ";
                $resultSet  = $this->dbHandle->query($queryToGetListingContactDetailsTableData)->result_array();
                //echo 'SQL : '.$this->dbHandle->last_query();
                $instituteData['listing_contact_details']   = array();
                foreach($resultSet AS $data){
                    $temp   = array();
                    $temp['contact_email']        = $data['contact_email'];
                    $temp['contact_main_phone']   = $data['contact_main_phone'];
                    $temp['website']              = $data['website'];
                    $temp['institute_location_id']= $data['institute_location_id'];
                    $instituteData['shiksha_listings_contacts'][$data['listing_type_id']][]   = $temp;
                }
                
                $queryToGetInstituteUploadedMediaData   = $sqlForInstituteUploadedMedia." AND listing_type_id IN(".  implode(',', $instituteIdsForQuery).") ";
                $resultSet  = $this->dbHandle->query($queryToGetInstituteUploadedMediaData)->result_array();
                //echo 'SQL : '.$this->dbHandle->last_query();
                $instituteData['institute_uploaded_media']  = array();
                foreach ($resultSet AS $data){
                    $temp   =   array();
                    $temp['institute_location_id']   = $data['institute_location_id'];
                    $temp['media_id']                = $data['media_id'];
                    $temp['media_type']              = $data['media_type'];
                    $temp['name']                    = $data['name'];
                    $temp['url']                     = $data['url'];
                    $temp['description']             = $data['description'];
                    $temp['thumburl']                = $data['thumburl'];
                    $instituteData['institute_uploaded_media'][$data['listing_type_id']][]  =   $temp;
                }
                **/
                
                $sqlForCompanyLogoMapping       =   "SELECT `institute_id`, `logo_id`, `company_order`, `status`"
                                                    ." FROM `company_logo_mapping` WHERE `status` = '".$status."' AND `linked` = 'yes' AND `logo_id` > 0 ";
                $queryToGetCompanyLogoMappingData   = $sqlForCompanyLogoMapping." AND `institute_id` IN(".  implode(',', $instituteIdsForQuery).") GROUP BY 1,2,3,4 ";
                $resultSet  = $this->dbHandle->query($queryToGetCompanyLogoMappingData)->result_array();
                //echo 'SQL : '.$this->dbHandle->last_query();

                foreach($resultSet AS $data){
                    $temp   =   array();
                    $temp['institute_id']   = $data['institute_id'];
                    $temp['logo_id']        = $data['logo_id'];
                    $temp['company_order']  = $data['company_order'];
                    $temp['status']         = $data['status'];
                    $instituteData['company_logo_mapping'][$data['institute_id']][] =   $temp;
                }
                
                $offsetForInstituteIds += 5000;
                
                if(!($offsetForInstituteIds <= $countOfInsttuteIds)){
                    break;
                }
            }

            error_log("============= Institute data found for ==== ". count($instituteData['institute_table']));            
            error_log("============= Institute location data found for ==== ". count($instituteData['institute_location_table']));
            error_log("============= Institute company data found for ==== ". count($instituteData['company_logo_mapping']));

            return $instituteData;
        }

        public function migrateDeletedInstitutesData($institutesData){
            return;
            global $suffixVal;
            if(!is_array($institutesData) || empty($institutesData)){
                return FALSE;
            }
            $this->initiateModel('read');
            $multiLocationInstitutes = array_filter($institutesData['shiksha_institutes_locations'.$suffixVal],function($ele){if(count($ele)>1){return true;}return false;});
            // _p($multiLocationInstitutes);die;
            foreach ($multiLocationInstitutes as $instituteId => $instData) {
                $sql = "SELECT institute_location_id from course_details cd join course_location_attribute loc on loc.course_id = cd.course_id and cd.status='deleted' and loc.status='deleted' and loc.attribute_type = 'Head Office' AND loc.attribute_value = 'TRUE' where cd.institute_id = ? order by cd.course_order limit 1";
                $query = $this->dbHandle->query($sql, array($instituteId))->row_array();
                if(isset($query['institute_location_id'])){
                    $institutesData['shiksha_institutes_locations'.$suffixVal][$instituteId][$query['institute_location_id']]['is_main'] = 1;
                    // $multiLocationInstitutes[$instituteId][$query['institute_location_id']]['is_main'] = 1;
                }
                else{
                    $locationId = key($institutesData['shiksha_institutes_locations'.$suffixVal][$instituteId]);
                    $institutesData['shiksha_institutes_locations'.$suffixVal][$instituteId][$locationId]['is_main'] = 1;
                    // $multiLocationInstitutes[$instituteId][$locationId]['is_main'] = 1;
                }
                // $sqlForCourse = "select course_id from course_details where institute_id = ? order by course_order limit 1";
                // $resultWithCourseId = $this->dbHandle->query($sqlForCourse, array($instituteId))->result_array();
                // if(isset($resultWithCourseId[0]['course_id'])){
                //     $courseId = $resultWithCourseId[0]['course_id'];
                    
                //     $sqlForLocation = "select institute_location_id from course_location_attribute where course_id IN (?) and status='deleted' and attribute_type = 'Head Office' AND attribute_value = 'TRUE'";
                //     $resultWithId = $this->dbHandle->query($sqlForLocation, array($courseId))->result_array();
                    
                //     if(isset($resultWithId[0]['institute_location_id'])){
                //         //Update this location in the institute location table for this institute
                //         $institutesData['shiksha_institutes_locations'.$suffixVal][$instituteId][$resultWithId[0]['institute_location_id']]['is_main'] = 1;
                //         $multiLocationInstitutes[$instituteId][$resultWithId[0]['institute_location_id']]['is_main'] = 1;
                //     }
                // }
            }
            // _p($multiLocationInstitutes);die;
            // _p($institutesData);die;
            $this->initiateModel('write');
            $this->dbHandle->trans_start();
            $instituteIds = array_keys($institutesData['shiksha_institutes']);
            foreach ($institutesData as $table => $tableData) {
                switch($table){
                    case 'shiksha_institutes'.$suffixVal:
                    case 'shiksha_listings_brochures'.$suffixVal:
                        $shikshaInstituteData   =   array();
                        foreach ($institutesData[$table] as $instId => $instData) {
                            $shikshaInstituteData[] = $instData;
                            if(count($shikshaInstituteData) >= 200){
                                $this->dbHandle->insert_batch($table, $shikshaInstituteData);
                                $shikshaInstituteData   =   array();
                            }
                        }
                        if(!empty($shikshaInstituteData)){
                            $this->dbHandle->insert_batch($table, $shikshaInstituteData);
                            $shikshaInstituteData   =   array();
                        }
                        break;
                    case 'shiksha_institutes_locations'.$suffixVal:
                    case 'shiksha_institutes_companies_mapping'.$suffixVal:
                        $shikshaInstituteData   =   array();
                        $count  =   0;
                        // Insert Data in shiksha_institutes_locations table
                        foreach($institutesData[$table] AS $instId => $instLocationDataArray){
                            foreach ($instLocationDataArray AS $instLocationData){
                                $shikshaInstituteData[] = $instLocationData;
                                if(count($shikshaInstituteData) >= 500){
                                    $this->dbHandle->insert_batch($table, $shikshaInstituteData);
                                    $shikshaInstituteData   =   array();
                                }
                            }
                        }
                        if(count($shikshaInstituteData) > 0){
                            $this->dbHandle->insert_batch($table, $shikshaInstituteData);
                            $shikshaInstituteData   =   array();
                        }
                        break;
                }
            }

            $countOfInstituteIds =   count($instituteIds);
            $offsetForInstituteIds  =   0;
            WHILE(TRUE){
                $instituteIdsForQuery = array_slice($instituteIds, $offsetForInstituteIds, 1000);
                $this->_migrateListingContactDetails($instituteIdsForQuery, 'deleted');
                $this->_migrateDataToMediaTables($instituteIdsForQuery, 'deleted');
                $this->_migrateDataToShikshaInstituteFacilityTable($instituteIdsForQuery, 'deleted');
                $offsetForInstituteIds += 1000;
                if(!($offsetForInstituteIds <= $countOfInstituteIds)){
                    break;
                }
            }

            $this->dbHandle->trans_complete();
            if($this->dbHandle->trans_status() === FALSE){
                throw new Exception('Transaction Failed');
            }
        }
        
        /**
         * 
         * @param array $data 2-D array of data 
         */
        public function insertDataInNewInstituteTables($institutesData = array(), $instituteIdsArray = array(), $status){
            return;
            global $suffixVal;
            if(!is_array($institutesData) || empty($instituteIdsArray)){
                return FALSE;
            }
            //_p($institutesData);die;
            
            $this->initiateModel('write');
            $this->dbHandle->trans_start();
            $shikshaInstituteData   =   array();
            $count  = 0;
            
            // Insert Data in shiksha_institutes table
            foreach($institutesData['shiksha_institutes'.$suffixVal] AS $instId => $instData){
                $shikshaInstituteData[] = $instData;
                if(++$count == 200){
                    $this->dbHandle->insert_batch('shiksha_institutes'.$suffixVal, $shikshaInstituteData);
                    $shikshaInstituteData   =   array();
                }
            }
            if(!empty($shikshaInstituteData)){
                $this->dbHandle->insert_batch('shiksha_institutes'.$suffixVal, $shikshaInstituteData);
                $shikshaInstituteData   =   array();
            }
            
            $shikshaListingsBrochuresData   =   array();
            $count  =   0;
            // Insert Data in shiksha_listings_brochures table
            foreach($institutesData['shiksha_listings_brochures'.$suffixVal] AS $instId => $instData){
                $shikshaListingsBrochuresData[] =   $instData;
                if(++$count == 200){
                    $this->dbHandle->insert_batch('shiksha_listings_brochures'.$suffixVal, $shikshaListingsBrochuresData);
                    $shikshaListingsBrochuresData= array();
                }
            }
            if(count($shikshaListingsBrochuresData) > 0){
                $this->dbHandle->insert_batch('shiksha_listings_brochures'.$suffixVal, $shikshaListingsBrochuresData);
                $shikshaListingsBrochuresData= array();
            }
            
            $shikshaInstituteLocationData   =   array();
            $count  =   0;
            // Insert Data in shiksha_institutes_locations table
            foreach($institutesData['shiksha_institutes_locations'.$suffixVal] AS $instId => $instLocationDataArray){
                foreach ($instLocationDataArray AS $instLocationData){
                    $shikshaInstituteLocationData[] = $instLocationData;
                    if(++$count >= 500){
                        $this->dbHandle->insert_batch('shiksha_institutes_locations'.$suffixVal, $shikshaInstituteLocationData);
                        $shikshaInstituteLocationData   =   array();
                    }
                }
            }
            if(count($shikshaInstituteLocationData) > 0){
                $this->dbHandle->insert_batch('shiksha_institutes_locations'.$suffixVal, $shikshaInstituteLocationData);
                $shikshaInstituteLocationData   =   array();
            }
            
            /*
            $shikshaInstituteLocationContact    =   array();
            $count  =   0;
            // Insert Data in shiksha_listings_contacts table
            foreach($institutesData['shiksha_listings_contacts'] AS $insId  =>  $instLocationContactArray){
                foreach($instLocationContactArray AS $instLocationContactData){
                    $shikshaInstituteLocationContact[]  =   $instLocationContactData;
                    if(++$count == 100){
                        _p($shikshaInstituteLocationContact);die;
                        $this->dbHandle->insert_batch('shiksha_listings_contacts', $shikshaInstituteLocationContact);
                        $shikshaInstituteLocationContact    =   array();
                    }
                }
            }
            if(!empty($shikshaInstituteLocationContact)){
                $this->dbHandle->insert_batch('shiksha_listings_contacts', $shikshaInstituteLocationContact);
                $shikshaInstituteLocationContact    =   array();
            }*/
            //$instituteIdsForListingContacts = array_keys($institutesData['shiksha_listings_contacts']);
            $countOfInstituteIds =   count($instituteIdsArray);
            //_p($instituteIdsForListingContacts);
            $offsetForInstituteIds  =   0;

	    
            WHILE(TRUE){
                $instituteIdsForQuery = array_slice($instituteIdsArray, $offsetForInstituteIds, 1000);
                $this->_migrateListingContactDetails($instituteIdsForQuery, $status);
                $this->_migrateDataToMediaTables($instituteIdsForQuery, $status);
                $this->_migrateDataToShikshaInstituteFacilityTable($instituteIdsForQuery, $status);
                $offsetForInstituteIds += 1000;
                if(!($offsetForInstituteIds <= $countOfInstituteIds)){
                    break;
                }
            }
	    
            
            // Insert Data in shiksha_institutes_companies_mapping
            $shikshaInstitutesCompanyMapping    =   array();
            $count  =   0;
            foreach ($institutesData['shiksha_institutes_companies_mapping'.$suffixVal] AS $instId => $instCompaniesDataArray){
                foreach ($instCompaniesDataArray AS $data){
                    $shikshaInstitutesCompanyMapping[]  =   $data;
                    if(++$count >= 500){
                        $this->dbHandle->insert_batch('shiksha_institutes_companies_mapping'.$suffixVal, $shikshaInstitutesCompanyMapping);
                        $shikshaInstitutesCompanyMapping=   array();
                    }
                }
            }
            if(count($shikshaInstitutesCompanyMapping) > 0){
                $this->dbHandle->insert_batch('shiksha_institutes_companies_mapping'.$suffixVal, $shikshaInstitutesCompanyMapping);
                $shikshaInstitutesCompanyMapping=   array();
            }
            
            $this->dbHandle->trans_complete();
            if($this->dbHandle->trans_status() === FALSE){
                throw new Exception('Transaction Failed');
            }
        }
        
        private function _migrateListingContactDetails($instituteIds = array(), $status){
            return;
            global $suffixVal;
            if(!is_array($instituteIds) || empty($instituteIds)){
                echo '<br/>TEST2';
                return FALSE;
            }
            $sql    =   "INSERT INTO shiksha_listings_contacts$suffixVal(`listing_id`, `listing_type`, `listing_location_id`, `website_url`,"
                        ." `address`, `generic_contact_number`, `generic_email`, `status`)"
                        ." SELECT `lcd`.`listing_type_id`, `lcd`.`listing_type`, `lcd`.`institute_location_id`, `lcd`.`website`, CONCAT_WS('\\n',ilt.address_1,ilt.address_2) AS address, `lcd`.`contact_main_phone`, `lcd`.`contact_email`, `lcd`.`status`"
                        ." FROM `listing_contact_details` `lcd` INNER JOIN `institute_location_table` `ilt` ON(`lcd`.`institute_location_id` = `ilt`.`institute_location_id`) "
                        ." WHERE `lcd`.`status` = '".$status."' AND `ilt`.`status` = '".$status."' AND `lcd`.`listing_type` = 'institute'"
                        ." AND `lcd`.`listing_type_id` IN(".  implode(',', $instituteIds).")";
            //echo '<br/> ECHO : '.$sql.$instituteIdsCheck;die;
            $this->dbHandle->query($sql);
            
            $updateSQL  =   "UPDATE shiksha_listings_contacts$suffixVal SET address = TRIM(BOTH '\\n' FROM address)"
                            ." WHERE listing_type = 'institute' AND listing_id IN(".  implode(',', $instituteIds).")";
            //echo '<br/> ECHO : '.$updateSQL;
            $this->dbHandle->query($updateSQL);
            return;
            
        }
        
        private function _migrateDataToMediaTables($instituteIds = array(), $status){
            return;
            global $suffixVal;
            if(!is_array($instituteIds) || empty($instituteIds)){
                echo '<br/>TEST3';
                return FALSE;
            }
            $sqlForHeaderImage  =   "SELECT `name`, `full_url` AS `url`, `thumb_url`, `header_order`, `status`, `listing_id`, `listing_type`, `institute_id`"
                                    ." FROM `header_image` WHERE `listing_type` = 'institute' AND linked = 'yes' AND `status` = '".$status."'"
                                    ." AND `listing_id` IN(".  implode(',', $instituteIds).") ORDER BY `header_order` ASC";
            $imageUrls          =   array();
            $headerImagesData   =   $this->dbHandle->query($sqlForHeaderImage)->result_array();
            if(!empty($headerImagesData)){
                foreach ($headerImagesData AS $data){
                    $imageUrls[]    =   $data['url'];
                }

                $sqlForMediaIdForHeaderImages   =   "SELECT `mediaid`, `url` FROM `tImageData` WHERE `url` IN('".  implode("','", $imageUrls)."')";
                $tImageDataResultSet            =   $this->dbHandle->query($sqlForMediaIdForHeaderImages)->result_array();
                $tImageData                     =   array();
                foreach($tImageDataResultSet AS $data){
                    $tImageData[$data['mediaid']]   =   $data['url'];
                }

                /*
                $sqlForMainLocationIdsForInstitutes =   "SELECT `listing_id`, `listing_location_id` FROM `shiksha_institutes_locations`"
                                                        ." WHERE `listing_type` = 'institute' AND `is_main` = true AND `listing_id` IN (".  implode(',', $instituteIds).") ";
                $mainLocationsResultSet             =   $this->dbHandle->query($sqlForMainLocationIdsForInstitutes)->result_array();
                $instituteMainLocationMappings      =   array();
                foreach($mainLocationsResultSet AS $data){
                    $instituteMainLocationMappings[$data['listing_id']]   =   $data['listing_location_id'];
                }
                */
                $shikshaInstitutesMediaTableData                =   array();
                $shikshaInstitutesMediaLocationMappingTableData =   array();
                $shikshaInstituteMediaPhotoCount     =   array();
                $shikshaInstituteMediaVideoCount     =   array();
                foreach ($headerImagesData AS $data){
                    $mediaId    = array_search($data['url'], $tImageData);
                    if(!$mediaId){
                        continue;
                    }
                    $temp   =   array();
                    $temp['media_id']           =   $mediaId;
                    $temp['media_title']        =   $data['name'];
                    $temp['media_url']          =   $data['url'];
                    $temp['media_thumb_url']    =   $data['thumb_url'];
                    //$temp['media_description'];
                    $temp['media_type']         =   'photo';
                    $temp['media_order']        =   $data['header_order'];
                    $temp['status']             =   $data['status'];
                    $shikshaInstitutesMediaTableData[]              = $temp;
                    if(!key_exists($data['listing_id'], $shikshaInstituteMediaPhotoCount)){
                        $shikshaInstituteMediaPhotoCount[$data['listing_id']]    =   0;
                    }
                    $shikshaInstituteMediaPhotoCount[$data['listing_id']] += 1;

                    $temp   =   array();
                    $temp['listing_id']         =   $data['listing_id'];
                    $temp['listing_type']       =   'institute';
                    $temp['listing_location_id']=   0;
                    $temp['media_id']           =   $mediaId;
                    $temp['media_type']         =   'photo';
                    $temp['status']             =   $data['status'];
                    $shikshaInstitutesMediaLocationMappingTableData[] =    $temp;
                    if(count($shikshaInstitutesMediaTableData) >= 200){
                        $this->dbHandle->insert_batch('shiksha_institutes_medias'.$suffixVal, $shikshaInstitutesMediaTableData);
                        $shikshaInstitutesMediaTableData    =   array();
                    }
                    if(count($shikshaInstitutesMediaLocationMappingTableData) >= 200){
                        $this->dbHandle->insert_batch('shiksha_institutes_media_locations_mapping'.$suffixVal, $shikshaInstitutesMediaLocationMappingTableData);
                        $shikshaInstitutesMediaLocationMappingTableData = array();
                    }
                }
                if(count($shikshaInstitutesMediaTableData) > 0){
                    $this->dbHandle->insert_batch('shiksha_institutes_medias'.$suffixVal, $shikshaInstitutesMediaTableData);
                    $shikshaInstitutesMediaTableData    =   array();
                }
                if(count($shikshaInstitutesMediaLocationMappingTableData) > 0){
                    $this->dbHandle->insert_batch('shiksha_institutes_media_locations_mapping'.$suffixVal, $shikshaInstitutesMediaLocationMappingTableData);
                    $shikshaInstitutesMediaLocationMappingTableData = array();
                }
            }
            
            $sqlForInstituteUploadedMedia   =   "SELECT `ium`.`listing_type`, `ium`.`listing_type_id`, `ium`.`institute_location_id`, `ium`.`media_id`, `ium`.`media_type`, `ium`.`name`, `ium`.`url`, `ium`.`description`, `ium`.`thumburl`, `lmt`.`status`"
                                                ." FROM `listing_media_table` `lmt` INNER JOIN `institute_uploaded_media` `ium` ON(`ium`.`listing_type` = `lmt`.`type` AND `ium`.`listing_type_id` = `lmt`.`type_id` AND `ium`.`media_type` = `lmt`.`media_type` AND `ium`.`media_id` = `lmt`.`media_id`)"
                                                ." WHERE `ium`.`listing_type` = 'institute' AND `ium`.`listing_type_id` IN(".  implode(',', $instituteIds).") AND `ium`.`media_type` IN('doc','photo','video')"
                                                ." AND `ium`.`status` = 'notlinked' AND `lmt`.`status` = '".$status."' ";
            $instituteUploadedMediaTableData=   $this->dbHandle->query($sqlForInstituteUploadedMedia)->result_array();
            foreach ($instituteUploadedMediaTableData AS $data){
                if($data['media_type'] == 'photo' && !key_exists($data['listing_id'], $shikshaInstituteMediaPhotoCount)){
                    $shikshaInstituteMediaPhotoCount[$data['listing_id']]   =   0;
                }elseif($data['media_type'] == 'videos' && !key_exists($data['listing_id'], $shikshaInstituteMediaVideoCount)){
                    $shikshaInstituteMediaVideoCount[$data['listing_id']]   =   0;
                }
                $temp   =   array();
                $temp['media_id']           =   $data['media_id'];
                $temp['media_title']        =   $data['name'];
                $temp['media_url']          =   $data['url'];
                $temp['media_thumb_url']    =   $data['thumburl'];
                $temp['media_description']  =   $data['description'];
                $temp['media_type']         =   $data['media_type'];
                if($data['media_type'] == 'photo'){
                    $temp['media_order']    =   ++$shikshaInstituteMediaPhotoCount[$data['listing_id']];
                }elseif($data['media_type'] == 'video'){
                    $temp['media_order']    =   ++$shikshaInstituteMediaVideoCount[$data['listing_id']];
                }else{
                    $temp['media_order']    =   0;
                }
                $temp['status']             =   $data['status'];
                $shikshaInstitutesMediaTableData[]              = $temp;
                
                $temp   =   array();
                $temp['listing_id']         =   $data['listing_type_id'];
                $temp['listing_type']       =   $data['listing_type'];
                $temp['listing_location_id']=   $data['institute_location_id'];
                $temp['media_id']           =   $data['media_id'];
                $temp['media_type']         =   $data['media_type'];
                $temp['status']             =   $data['status'];
                $shikshaInstitutesMediaLocationMappingTableData[] =    $temp;
                if(count($shikshaInstitutesMediaTableData) >= 200){
                    $this->dbHandle->insert_batch('shiksha_institutes_medias'.$suffixVal, $shikshaInstitutesMediaTableData);
                    $shikshaInstitutesMediaTableData    =   array();
                }
                if(count($shikshaInstitutesMediaLocationMappingTableData) >= 200){
                    $this->dbHandle->insert_batch('shiksha_institutes_media_locations_mapping'.$suffixVal, $shikshaInstitutesMediaLocationMappingTableData);
                    $shikshaInstitutesMediaLocationMappingTableData = array();
                }
            }
            //_p($shikshaInstitutesMediaTableData);die;
            if(count($shikshaInstitutesMediaTableData) > 0){
                $this->dbHandle->insert_batch('shiksha_institutes_medias'.$suffixVal, $shikshaInstitutesMediaTableData);
                $shikshaInstitutesMediaTableData    =   array();
            }
            if(count($shikshaInstitutesMediaLocationMappingTableData) > 0){
                $this->dbHandle->insert_batch('shiksha_institutes_media_locations_mapping'.$suffixVal, $shikshaInstitutesMediaLocationMappingTableData);
                $shikshaInstitutesMediaLocationMappingTableData = array();
            }
            return;
        }
        
        private function _migrateDataToShikshaInstituteFacilityTable($instituteIds = array(), $status){
            return;
            global $suffixVal;
            if(empty($instituteIds)){
                return;
            }
            $sql    =   "INSERT INTO `shiksha_institutes_facilities$suffixVal`(`listing_id`,`listing_type`,`facility_id`,`description`,`has_facility`,`status`) "
                        ." SELECT `listing_type_id`, `listing_type`,"
                        ." CASE `facility_id` WHEN 1 THEN 1 WHEN 2 THEN 2 WHEN 3 THEN 3 WHEN 4 THEN 9 WHEN 5 THEN 10 WHEN 6 THEN 11 WHEN 7 THEN 12 WHEN 8 THEN 13 WHEN 9 THEN 14 WHEN 10 THEN 15 WHEN 11 THEN 16 ELSE -1 END AS `facility_id`,"
                        ." `description`,1,`status` FROM `institute_facilities` WHERE "
                        ." `listing_type_id` IN(".  implode(',', $instituteIds).") AND `listing_type` = 'institute' AND `status` = '".$status."' AND `facility_id` >= 1 AND `facility_id` <= 11";
            $this->dbHandle->query($sql);
            return;
        }


        public function getCityStateMapping(){
            return;
            $result =   array();
            $sql    =   "SELECT city_id, state_id FROM countryCityTable WHERE countryId = 2";
            $this->initiateModel('read');
            $resultSet  = $this->dbHandle->query($sql)->result_array();
            foreach($resultSet AS $data){
                $result[$data['city_id']]   = $data['state_id'];
            }
            return $result;
        }
       
	public function migrateListingsMain($instituteId, $status = 'live'){
        return;
	    global $suffixVal;
	    $this->initiateModel('write');
	    $sql = "select listing_type_id,listing_title,submit_date,approve_date,last_modify_date, expiry_date,status,listing_type, username,pack_type, viewCount,url, subscriptionId,editedBy,comments, no_Of_Past_Free_Views,no_Of_Past_Paid_Views,listing_seo_url, listing_seo_title, listing_seo_description,listing_seo_keywords,use_stored_seo_data_flag from listings_main$suffixVal where listing_type_id IN (select distinct l.listing_type_id from listings_main$suffixVal l, institute_location_table$suffixVal i, institute$suffixVal n where l.status IN ('$status') and l.listing_type='institute' and l.listing_type_id=i.institute_id and i.status IN ('$status') and i.country_id=2 and l.listing_type_id=n.institute_id and n.status IN ('$status') and n.institute_type IN ('Academic_Institute','Multi_Location_Academic_Institute')) and status IN ('$status') and listing_type='institute'";
            if($instituteId >= 0 && $instituteId!=''){
		$sql = $sql." AND listing_type_id = $instituteId";
            }
	    error_log("Executing Query now for $status...");
	    $listingsMainData   =   $this->dbHandle->query($sql)->result_array();
	    error_log("Rows found for $status === ".count($listingsMainData));
	    foreach ($listingsMainData as $listingData){
		$newStatus = ($listingData['status'] == 'live') ? 'stagging' : 'stagging_draft';
		$checkSQL = "SELECT * from listings_main$suffixVal WHERE listing_type_id = ? AND status = ?";
		$listingCheck   =   $this->dbHandle->query($checkSQL,array($listingData['listing_type_id'],$newStatus))->result_array();
		if(count($listingCheck) <= 0){	//No entry is present in the DB. Hence, insert a new one
			$insertSQL = "INSERT INTO listings_main$suffixVal (listing_type_id,listing_title,submit_date,approve_date,last_modify_date, expiry_date,status,listing_type, username,pack_type, viewCount,url, subscriptionId,editedBy,comments, no_Of_Past_Free_Views,no_Of_Past_Paid_Views,listing_seo_url, listing_seo_title, listing_seo_description,use_stored_seo_data_flag) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$this->dbHandle->query($insertSQL, array($listingData['listing_type_id'],$listingData['listing_title'],$listingData['submit_date'],$listingData['approve_date'],$listingData['last_modify_date'],$listingData['expiry_date'],$newStatus,$listingData['listing_type'],$listingData['username'],$listingData['pack_type'],$listingData['viewCount'],$listingData['url'],$listingData['subscriptionId'],$listingData['editedBy'],$listingData['comments'],$listingData['no_Of_Past_Free_Views'],$listingData['no_Of_Past_Paid_Views'],$listingData['listing_seo_url'],$listingData['listing_seo_title'],$listingData['listing_seo_description'],$listingData['use_stored_seo_data_flag']));
			error_log("Inserting InstituteId = ".$listingData['listing_type_id']." in DB");
		}
	    }
	}

        public function migrateUniversityListings($universityIds) {
            $this->initiateModel('write');
            if(empty($universityIds)){
                return;
            }

            try{
                //Update the type in listings_main table
                $sql = "UPDATE listings_main SET listing_type = 'university_national' WHERE listing_type_id IN (?) AND listing_type = 'institute' AND status IN ('live','draft')";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_institutes table and also change is autonomous flag
                // setting default university type as deemed
                $sql = "UPDATE shiksha_institutes SET listing_type = 'university', is_autonomous = NULL, institute_specification_type = NULL, university_specification_type = 'deemed' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                // also update parent_listing_type in shiksha_institutes
                $sql = "UPDATE shiksha_institutes SET parent_listing_type = 'university' where parent_listing_id in (?)";
                $this->dbHandle->query($sql,array($universityIds));
        
                //Update the type in shiksha_institutes_academic_staffs table
                $sql = "UPDATE shiksha_institutes_academic_staffs SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_institutes_additional_attributes table
                $sql = "UPDATE shiksha_institutes_additional_attributes SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_listings_brochures table
                $sql = "UPDATE shiksha_listings_brochures SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_institutes_companies_mapping table
                $sql = "UPDATE shiksha_institutes_companies_mapping SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_institutes_events table
                $sql = "UPDATE shiksha_institutes_events SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_institutes_facilities table
                $sql = "UPDATE shiksha_institutes_facilities SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_institutes_facilities_mappings table
                $sql = "UPDATE shiksha_institutes_facilities_mappings SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_institutes_locations table
                $sql = "UPDATE shiksha_institutes_locations SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_institutes_media_locations_mapping table
                $sql = "UPDATE shiksha_institutes_media_locations_mapping SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_institutes_media_tags_mapping table
                $sql = "UPDATE shiksha_institutes_media_tags_mapping SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_institutes_scholarships table
                $sql = "UPDATE shiksha_institutes_scholarships SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_listings_contacts table
                $sql = "UPDATE shiksha_listings_contacts SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in shiksha_listings_brochures table
                $sql = "UPDATE shiksha_listings_brochures SET listing_type = 'university' WHERE listing_id IN (?)";
                $this->dbHandle->query($sql,array($universityIds));

                //Update the type in articleAttributeMapping,examAttributeMapping,tags_entity
                $sql = "UPDATE articleAttributeMapping set entityType = 'university' WHERE entityId IN (?) and entityType = 'college'";
                $this->dbHandle->query($sql,array($universityIds));
                $sql = "UPDATE examAttributeMapping set entityType = 'university' WHERE entityId IN (?) and entityType = 'college'";
                $this->dbHandle->query($sql,array($universityIds));
                $sql = "UPDATE tags_entity set entity_type = 'National-University' WHERE entity_id IN (?) and entity_type = 'institute'";
                $this->dbHandle->query($sql,array($universityIds));
            }
            catch(Exception $e){
                _p($e);
            }
        }

        public function updateInstituteTypeInCourseTables($oldId,$newId,$newType){
            if(empty($oldId) || empty($newId) || empty($newType)){
                return;
            }
            $this->initiateModel('write');
            $sql = "UPDATE shiksha_courses set primary_type = ? ,primary_id = ? WHERE primary_id IN (?)";
            $this->dbHandle->query($sql,array($newType,$newId,$oldId));
            $sql = "UPDATE shiksha_courses set parent_type = ? ,parent_id = ? WHERE parent_id IN (?)";
            $this->dbHandle->query($sql,array($newType,$newId,$oldId));
        }

        public function getCourseIdsByParentId($instituteId){
            if(empty($instituteId)){
                return;
            }
            $this->initiateModel('write');
            $courseIds = array();$parentIds = array($instituteId);
            $descendantInst = array($instituteId);

            while(!empty($parentIds)){
                $sql = "SELECT listing_id from shiksha_institutes where parent_listing_id in (?) and status = 'live'";
                $data = $this->dbHandle->query($sql,array($parentIds))->result_array();
                $parentIds = $this->getColumnArray($data,'listing_id');
                $descendantInst = array_merge($descendantInst,$parentIds);
            }
            if(!empty($descendantInst)){
                $sql = "SELECT course_id from shiksha_courses where parent_id in (?) and status='live'";
                $data = $this->dbHandle->query($sql,array($descendantInst))->result_array();
                $courseIds = $this->getColumnArray($data,'course_id');
            }
            return $courseIds;
        }

        public function getCoursesHavingPrimaryId($courseIds,$primaryId){
            if(empty($courseIds) || empty($primaryId)){
                return;
            }
            $this->initiateModel('write');
            $sql = "SELECT course_id from shiksha_courses where status = 'live' and course_id in (?) and primary_id in (?)";
            $data = $this->dbHandle->query($sql,array($courseIds,$primaryId))->result_array();
            $courseIds = $this->getColumnArray($data,'course_id');
            return $courseIds;
        }

        public function updateSEODetailsForInstitute($instituteId,$seoUrl,$seoTitle,$seoDescription){
            $this->initiateModel('write');
            $seoUrl = empty($seoUrl) ? NULL : $seoUrl;
            $seoTitle = empty($seoTitle) ? NULL : $seoTitle;
            $seoDescription = empty($seoDescription) ? NULL : $seoDescription;
            $sql = "UPDATE listings_main SET listing_seo_url = ?,listing_seo_title = ?, listing_seo_description = ? where listing_type_id = ? and listing_type in ('university_national','institute')";
            $this->dbHandle->query($sql,array($seoUrl,$seoTitle,$seoDescription,$instituteId));
        }

        public function checkUniversityListings() {
            return;
	    global $suffixVal;
            $this->initiateModel('read');

            try{
                $sql = "SELECT count(DISTINCT listing_type_id) totalListings FROM listings_main WHERE listing_type = 'university_national'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in listings_main = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes = ". $result[0]['totalListings']."<br/>";

        
                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes_academic_staffs$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes_academic_staffs = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes_additional_attributes$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes_additional_attributes = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_listings_brochures$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_listings_brochures = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes_companies_mapping$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes_companies_mapping = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes_events$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes_events = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes_facilities$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes_facilities = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes_facilities_mappings$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes_facilities_mappings = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes_locations$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes_locations = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes_media_locations_mapping$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes_media_locations_mapping = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes_media_tags_mapping$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes_media_tags_mapping = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_institutes_scholarships$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_institutes_scholarships = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_listings_contacts$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_listings_contacts = ". $result[0]['totalListings']."<br/>";


                $sql = "SELECT count(DISTINCT listing_id) totalListings FROM shiksha_listings_brochures$suffixVal WHERE listing_type = 'university'";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of Institutes migrated to Universities in shiksha_listings_brochures = ". $result[0]['totalListings']."<br/>";

            }
            catch(Exception $e){
                _p($e);
            }
        }

        public function migrateHeadOffice($status, $instituteId = 0){
            return;
		global $suffixVal;
                $this->initiateModel('write');

                //Fetch multi location institutes with Live status. Then, we will do this for Draft ones.
                if($instituteId > 0 && $instituteId != ''){
                        $addedClause = " and listing_id = '$instituteId' ";
                }

                $sql = "select listing_id, count(*) cc from shiksha_institutes_locations$suffixVal where status = '$status' $addedClause group by listing_id having cc>1";
                $result = $this->dbHandle->query($sql)->result_array();
                echo "Total number of multi location with $status status===".count($result)."<br/>";
                foreach ($result as $institute){
                
                        //Now, for these institutes, fetch the Flagship course & for this flagship course, fetch the Head office location id
                        $sqlForCourse = "select course_id from course_details where institute_id = ? and status='live' order by course_order limit 1";
                        $resultWithCourseId = $this->dbHandle->query($sqlForCourse, array($institute['listing_id']))->result_array();
                        if(isset($resultWithCourseId[0]['course_id'])){
                            $courseId = $resultWithCourseId[0]['course_id'];
                            
                            $sqlForLocation = "select institute_location_id from course_location_attribute where course_id IN (?) and status='live' and attribute_type = 'Head Office' AND attribute_value = 'TRUE'";
                            $resultWithId = $this->dbHandle->query($sqlForLocation, array($courseId))->result_array();
                            
                            if(isset($resultWithId[0]['institute_location_id'])){
                                    //Update this location in the institute location table for this institute
                                    $sql = "UPDATE shiksha_institutes_locations$suffixVal set is_main = 1 where listing_location_id = ? and status = '$status'";
                                    echo "UPDATE shiksha_institutes_locations$suffixVal set is_main = 1 where listing_location_id = ".$resultWithId[0]['institute_location_id']." and status = '$status'<br/>";
                                    $this->dbHandle->query($sql, array($resultWithId[0]['institute_location_id']));
                            }
                        }
                }

		error_log("Converting 3 Institutes with Id 37820, 46590 & 47284 into Single location since these need to be converted to Universities");
		$sql = "UPDATE shiksha_institutes_locations$suffixVal SET status = 'deleted' WHERE listing_id = '37820' AND city_id != '12239'";
                $result = $this->dbHandle->query($sql);
                $sql = "UPDATE shiksha_institutes_locations$suffixVal SET status = 'deleted' WHERE listing_id = '46590' AND city_id != '153'";
                $result = $this->dbHandle->query($sql);
                $sql = "UPDATE shiksha_institutes_locations$suffixVal SET status = 'deleted' WHERE listing_id = '47284' AND city_id != '107'";
                $result = $this->dbHandle->query($sql);
                $sql = "UPDATE shiksha_institutes_locations$suffixVal SET is_main = 1 WHERE listing_id = '37820' AND city_id = '12239'";
                $result = $this->dbHandle->query($sql);
                $sql = "UPDATE shiksha_institutes_locations$suffixVal SET is_main = 1 WHERE listing_id = '46590' AND city_id = '153'";
                $result = $this->dbHandle->query($sql);
                $sql = "UPDATE shiksha_institutes_locations$suffixVal SET is_main = 1 WHERE listing_id = '47284' AND city_id = '107'";
                $result = $this->dbHandle->query($sql);

        }
 
        public function migrateExcelDataToDB($data = array()){
            return;
	    global $suffixVal;
            if(!is_array($data) || empty($data)){
                return;
            }
            
            $this->initiateModel('write');
            $DMLSqlsForShikshaInstitutes            =   array();
            $DMLSqlsForShikshaAdditionalAttributes  =   array();
            if(key_exists('shiksha_institutes'.$suffixVal, $data) && !empty($data['shiksha_institutes'.$suffixVal])){
                $insertData =   array();
                foreach($data['shiksha_institutes'.$suffixVal] AS $instituteTableData){
                    if($instituteTableData['listing_id'] > 0 && $instituteTableData['listing_type'] == 'institute'){
                        $setClause  =   "SET ";
                        foreach ($instituteTableData AS $key => $value){
                            switch ($key) {
                                case    'abbreviation'  :   $setClause  .=  "abbreviation = '".$value."',";
                                                            break;
                                case    'synonym'       :   $setClause  .=  "synonym = '".$value."',";
                                                            break;
                                case    'ownership'     :   $setClause  .=  "ownership = '".$value."',";
                                                            break;
                                case    'student_type'  :   $setClause  .=  "student_type = '".$value."',";
                                                            break;
                                case    'is_autonomous' :   $setClause  .=  "is_autonomous = '".$value."',";
                                                            break;
                                case    'accreditation' :   $setClause  .=  "accreditation = '".$value."',";
                                                            break;
				case    'institute_specification_type' :  $setClause  .=  "institute_specification_type = '".$value."',";
							    break;
                                default:break;
                            }
                        }
                        $setClause  =   rtrim($setClause, ",");
                        $whereClause=   " WHERE status IN ('live','draft') AND listing_type = 'institute' AND listing_id = ".$instituteTableData['listing_id'];
                        $DMLSqlsForShikshaInstitutes[]   =   "UPDATE shiksha_institutes$suffixVal ".$setClause.$whereClause;
                    }
                }
            }
            if(key_exists('shiksha_institutes_additional_attributes'.$suffixVal, $data) && !empty($data['shiksha_institutes_additional_attributes'.$suffixVal])){
                if(key_exists('usp', $data['shiksha_institutes_additional_attributes'.$suffixVal]) && !empty($data['shiksha_institutes_additional_attributes'.$suffixVal]['usp'])){
                    foreach ($data['shiksha_institutes_additional_attributes'.$suffixVal]['usp'] AS $instituteId => $uspArray){
			$insertSql = '';
                        $DMLSqlsForShikshaAdditionalAttributes[] = "UPDATE `shiksha_institutes_additional_attributes$suffixVal` SET `status` = 'history' WHERE `description_type` = 'usp' AND `status` = 'live' AND `listing_type` = 'institute' AND `listing_id` = ".$instituteId;
                        $insertSql     = "INSERT INTO `shiksha_institutes_additional_attributes$suffixVal` (`listing_id`,`listing_type`,`description`,`description_type`,`status`) VALUES";
                        foreach ($uspArray AS $usp){
                            $insertSql     .= "(".mysql_escape_string($instituteId).",'institute','".mysql_escape_string($usp)."','usp','status'),";
                        }
                        $insertSql  = rtrim($insertSql, ",");
                        $DMLSqlsForShikshaAdditionalAttributes[]    =   $insertSql;
                    } 
                }
                if(key_exists('research_project', $data['shiksha_institutes_additional_attributes'.$suffixVal]) && !empty($data['shiksha_institutes_additional_attributes'.$suffixVal]['research_project'])){
                    foreach($data['shiksha_institutes_additional_attributes'.$suffixVal]['research_project'] AS $instituteId => $researchProjectArray){
			$insertSql = '';
                        $DMLSqlsForShikshaAdditionalAttributes[] = "UPDATE `shiksha_institutes_additional_attributes$suffixVal` SET `status` = 'history' WHERE `description_type` = 'research_project' AND `status` = 'live' AND `listing_type` = 'institute' AND `listing_id` = ".$instituteId;
                        $insertSql     = "INSERT INTO `shiksha_institutes_additional_attributes$suffixVal` (`listing_id`,`listing_type`,`description`,`description_type`,`status`) VALUES";
                        foreach ($researchProjectArray AS $researchProject){
                            $insertSql     .= "(".mysql_escape_string($instituteId).",'institute','".mysql_escape_string($researchProject)."','research_project','status'),";
                        }
                        $insertSql  = rtrim($insertSql, ",");
                        $DMLSqlsForShikshaAdditionalAttributes[]    =   $insertSql;
                    }
                }
            }
            
            foreach($DMLSqlsForShikshaInstitutes AS $dmlSql){
                $this->dbHandle->query($dmlSql);
            }
            foreach($DMLSqlsForShikshaAdditionalAttributes AS $dmlSql){
                $this->dbHandle->query($dmlSql);
            }
        }

        public function getAllListingsForMigration(){
            return;
            $this->initiateModel('read');
            $sql = "SELECT distinct listing_id FROM shiksha_institutes WHERE listing_type in ('university','institute') AND status in ('live','draft') AND (is_dummy is false OR is_dummy is NULL)";

            $result = $this->dbHandle->query($sql)->result_array();
            foreach($result as $key=>$value){
                $resultArray[] = $value['listing_id'];
            }
            return $resultArray;
        }

        public function updateSEODetails($seoData){
            return;
            $this->initiateModel('write');

            $sql = "UPDATE listings_main 
                    SET listing_seo_url = ?, listing_seo_title = ?, listing_seo_description = ?
                    WHERE listing_type_id = ? AND listing_type =? AND status in ('live','draft')";
            
            $this->dbHandle->query($sql,array($seoData['seoUrl'],$seoData['seoTitle'],$seoData['seoDescription'],$seoData['listingId'],$seoData['listing_type']));
            
            return $seoData['listingId'];
        }

	public function migrateSynonyms($listingId){
        return;
            $this->initiateModel('write');

	    if($listingId != '' && $listingId > 0){
		$check = " AND irk.instituteId = '$listingId' ";
	    }
	    //Fetch all the synonyms from both the tables
            $sql = "SELECT irk.synonyms, irk.acronyms, irk.instituteId, si.synonym FROM shiksha_institutes si, instituteRelatedKeywords irk WHERE listing_type in ('university','institute') AND si.status in ('live','draft') AND irk.status = 'live' AND si.listing_id = irk.instituteId $check";
            $result = $this->dbHandle->query($sql)->result_array();

            foreach($result as $row){
		$listingId = $row['instituteId'];

		$currentSynonym = $row['synonym'];
		$newSynonym = $row['synonyms'];
		$newAcronym = $row['acronyms'];

		//Merge the arrays 
		$currentSynArray = ($currentSynonym != NULL && $currentSynonym != '0' && $currentSynonym != '')?explode(';',$currentSynonym):array();
		$newSynArray = ($newSynonym != NULL && $newSynonym != '0' && $newSynonym != '')?explode(',',$newSynonym):array();
		$newAcrArray = ($newAcronym != NULL && $newAcronym != '0' && $newAcronym != '')?explode(',',$newAcronym):array();
		$allSyn = array_merge($currentSynArray,$newSynArray,$newAcrArray);		

		//Remove duplicates, empty values and trim all values
		$allSyn = array_map('trim', $allSyn);
		$allSyn = array_unique($allSyn);
		$allSyn = array_filter($allSyn, function($value) { return trim($value) !== ''; });
		$allSyn = array_map('trim', $allSyn);

		$synString = implode(';',$allSyn);
		echo "$listingId ::: $currentSynonym (CS) +++ $newSynonym (NS) +++ $newAcronym (NA) === $synString<br/>";
		$sql = "UPDATE shiksha_institutes SET synonym = ? WHERE listing_type IN ('university','institute') AND status IN ('live','draft') AND listing_id = ?";
		$this->dbHandle->query($sql, array($synString,$listingId));
            }

	    return true;
	}

    public function fixPhotoTitle($mediaId){
        return;
        $this->initiateModel('write');

        if($mediaId != '' && $mediaId > 0){
            $check = " AND media_id = '$mediaId' ";
        }

        $sql = "select * from shiksha_institutes_medias where (media_title like '%.png' OR media_title like '%.jpg' OR media_title like '%.jpeg' OR media_title like '%.gif' OR media_title like '%.bmp' OR media_title like '%.pdf' OR media_title like '%.doc' OR media_title like '%.xls' OR media_title like '%.ppt' OR media_title like '%.php' OR media_title like '%.mpg' OR media_title like '%.php3') and status IN ('live','draft') and media_type='photo' $check";
        $result = $this->dbHandle->query($sql)->result_array();
        $i = 1;
        foreach($result as $row){
            $id = $row['id'];
            $title = $row['media_title'];
            $titleArray = explode('.',$title);
            array_pop($titleArray);
            $newTitle = implode('.',$titleArray);
            echo "$i:::Id = <b>$id</b> ::: Old Title = <b>$title</b> ::: New title = <b>$newTitle</b> <br/>"; $i++;
            $sql = "UPDATE shiksha_institutes_medias SET media_title = ? WHERE id = ? AND status IN ('live','draft') AND media_type='photo'";
            $this->dbHandle->query($sql, array($newTitle,$id));
        }

        return true;
    }

    public function getCorruptInstitute($data,$limit,$offset){
        $offset = $offset*500;
        $this->initiateModel('read');       
        $sql = "select ".$data['table'].".".$data['uniqueId'].",".$data['table'].".".$data['instituteIdColumn']." from " .$data['table']." left join shiksha_institutes si ON ".$data['table'].".".$data['instituteIdColumn']."= si.listing_id and si.status = 'live'";
        if($data['status']!='')
        {
            $sql.= "and ".$data['table'].".".$data['status']."='live' where si.listing_id is null LIMIT ".$offset." , ".$limit;
        }  
        else
        {
            $sql.= "where si.listing_id is null LIMIT ".$offset." , ".$limit;
        } 
        $resultData =  $this->dbHandle->query($sql)->result_array();

        return $resultData;
    } 



    public function getCorruptCourses($data,$limit,$offset){
        $offset = $offset*500;
        $this->initiateModel('read');       
        $sql = "select ".$data['table'].".".$data['uniqueId'].",".$data['table'].".".$data['courseIdColumn']." from " .$data['table']." left join shiksha_courses sc ON ".$data['table'].".".$data['courseIdColumn']."= sc.course_id and sc.status = 'live'";
        if($data['status']!='')
        {
            $sql.= "and ".$data['table'].".".$data['status']."='live' where sc.course_id is null LIMIT ".$offset." , ".$limit;
        }  
        else
        {
            $sql.= "where sc.course_id is null LIMIT ".$offset." , ".$limit;
        } 
        $resultData = $this->dbHandle->query($sql)->result_array();
        return $resultData;
    }

    public function getCorruptInstituteCourseMappings(){
        $this->initiateModel('read');

        global $TABLES_WITH_CI_MAPPING;
        foreach ($TABLES_WITH_CI_MAPPING as $tableData) {
            $tableName = $tableData['table'];
            $instituteIdColumn = $tableData['instituteIdColumn'];
            $courseIdColumn = $tableData['courseIdColumn'];
            $statusColumn = $tableData['statusColumn'];
            $limit = 5000;
            $offset = 0;

            $whereStatements = array();
            $sql = "SELECT count(distinct $courseIdColumn) as count from $tableName ";
            if(!empty($statusColumn)){
                $whereStatements[] = "status = 'live'";
            }
            if(!empty($whereStatements)){
                $sql .= " WHERE ".implode(" AND ",$whereStatements);
            }
            $totalCourses = $this->dbHandle->query($sql)->row_array();

            $sql = "SELECT count(*) as count from $tableName ";
            if(!empty($whereStatements)){
                $sql .= " WHERE ".implode(" AND ",$whereStatements);
            }
            $totalEntries = $this->dbHandle->query($sql)->row_array();

            $totalCorruptCourseInstituteEntries[$tableName] = 0;
            do{
                $whereStatements = array();
                $sql = "SELECT distinct $courseIdColumn as content_table_course_id,$instituteIdColumn as content_table_institute_id from $tableName ";
                if(!empty($statusColumn)){
                    $whereStatements[] = "status = 'live'";
                }
                if(!empty($whereStatements)){
                    $sql .= " WHERE ".implode(" AND ",$whereStatements);
                }
                $sql .= "LIMIT $offset,$limit";
                $offset += $limit;
                $contentTableData = $this->dbHandle->query($sql)->result_array();

                $contentData = array();
                foreach ($contentTableData as $row) {
                    $contentData[$row['content_table_course_id']] = $row;
                }
                
                if(!empty($contentData)){
                    $courseIds = $this->getColumnArray($contentData,'content_table_course_id');
                    $instituteIds = $this->getColumnArray($contentData,'content_table_institute_id');

                    $sql = "SELECT distinct listing_type_id from listings_main where listing_type_id in (?) and listing_type='course' and status='live'";
                    $temp = $this->dbHandle->query($sql,array($courseIds))->result_array();
                    $presentCourseIds = $this->getColumnArray($temp,'listing_type_id');

                    $absentCourseIds = array_diff($courseIds,$presentCourseIds);
                    foreach ($absentCourseIds as $courseId) {
                        $corruptCourses[$tableName][] = $courseId;
                        error_log("Course Id: $courseId not present as live \n",3,'/tmp/corrupt_'.$tableName.'.log');
                    }

                    $sql = "SELECT distinct listing_type_id from listings_main where listing_type_id in (?) and listing_type in ('institute','university','university_national') and status='live'";
                    $temp = $this->dbHandle->query($sql,array($instituteIds))->result_array();
                    $presentInstituteIds = $this->getColumnArray($temp,'listing_type_id');

                    $absentInstituteIds = array_diff($instituteIds,$presentInstituteIds);
                    foreach ($absentInstituteIds as $instituteId) {
                        $corruptInstitutes[$tableName][] = $courseId;
                        error_log("Institute Id: $instituteId not present as live \n",3,'/tmp/corrupt_'.$tableName.'.log');
                    }

                    $sql = "SELECT course_id from shiksha_courses where course_id in (?) and status= 'live'";
                    $temp = $this->dbHandle->query($sql,array($presentCourseIds))->result_array();
                    $nationalCourseIds = $this->getColumnArray($temp,'course_id');

                    if($tableData['instituteRelation'] == 'primary'){
                        $sql = "SELECT distinct sc.primary_id as primary_id, tble.{$courseIdColumn} as content_table_course_id, tble.{$instituteIdColumn} as content_table_institute_id from $tableName tble join shiksha_courses sc on sc.status='live' and sc.course_id = tble.{$courseIdColumn} and sc.course_id in (?) and sc.primary_id != tble.{$instituteIdColumn} ";
                        if(!empty($statusColumn)){
                            $sql .= " AND tble.{$statusColumn} = 'live'";
                        }
                        $temp = $this->dbHandle->query($sql,array($nationalCourseIds))->result_array();
                        foreach ($temp as $row) {
                            $corruptCourseInstitutes[$tableName][$row['content_table_course_id']][] = array('instituteId' => $row['content_table_institute_id'], 'primaryId' => $row['primary_id']);
                            $totalCorruptCourseInstituteEntries[$tableName]++;
                            error_log("Institute ids mismatch for course: ".$row['content_table_course_id']." having instituteIds: ".$row['content_table_institute_id']."-".$row['primary_id']." \n",3,'/tmp/corrupt_mapping_'.$tableName.'.log');
                        }
                    }
                    else{
                        $parentData = $this->getParentHierarchyForCourses($nationalCourseIds);

                        foreach ($contentData as $key => $data) {
                            if(!in_array($data['content_table_institute_id'], $parentData[$data['content_table_course_id']])) {
                                $corruptCourseInstitutes[$tableName][$data['content_table_course_id']][] = $data['content_table_institute_id'];
                                $totalCorruptCourseInstituteEntries[$tableName]++;
                                error_log("Course Id: ".$data['content_table_course_id']." ancestor institute ".$data['content_table_institute_id']." not present in parent hierarchy ".implode(',',$parentData[$data['content_table_course_id']])." \n",3,'/tmp/corrupt_mapping_'.$tableName.'.log');
                            }
                        }
                    }
                }
            }while(!empty($contentData));

            _p($tableName);
            _p("Total entries in the table - ".$totalEntries['count']);
            _p("Total courses in the table - ".$totalCourses['count']);
            _p("**** Non live courses - ".count($corruptCourses[$tableName]));
            _p("**** Non live institutes - ".count($corruptInstitutes[$tableName]));

            _p("****** Corrupt course institute mapping - ".count($corruptCourseInstitutes[$tableName]));
            _p("****** Total corrupt course institute entries - ".$totalCorruptCourseInstituteEntries[$tableName]);
            _p("===========================");

            error_log($tableName." \n", 3, '/tmp/corrupt_mappings.log');
            error_log("Total entries in the table - ".$totalEntries['count']." \n", 3, '/tmp/corrupt_mappings.log');
            error_log("Total courses in the table - ".$totalCourses['count']." \n", 3, '/tmp/corrupt_mappings.log');
            error_log("**** Non live courses - ".count($corruptCourses[$tableName])." \n", 3, '/tmp/corrupt_mappings.log');
            error_log("**** Non live institutes - ".count($corruptInstitutes[$tableName])." \n", 3, '/tmp/corrupt_mappings.log');
            error_log("****** Corrupt course institute mapping - ".count($corruptCourseInstitutes[$tableName])." \n", 3, '/tmp/corrupt_mappings.log');
            error_log("****** Total corrupt course institute entries - ".$totalCorruptCourseInstituteEntries[$tableName]." \n", 3, '/tmp/corrupt_mappings.log');
            error_log("=========================== \n", 3, '/tmp/corrupt_mappings.log');
        }
    }

    private function getParentHierarchyForCourses($courseIds){
        if(empty($courseIds)){
            return;
        }
        $this->dbHandle->where_in('course_id',$courseIds);
        $this->dbHandle->where_in('status','live');
        $data = $this->dbHandle->get('shiksha_courses_institutes')->result_array();

        $returnData = array();
        foreach ($data as $row) {
            $returnData[$row['course_id']][] = $row['hierarchy_parent_id'];
        }
        return $returnData;
    }
}
?>
