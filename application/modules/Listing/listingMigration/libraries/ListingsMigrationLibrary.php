<?php
    global $suffixVal;
    $suffixVal = "";

    class ListingsMigrationLibrary{
        private $CI;
        public function __construct() {
            $this->CI = & get_instance();
        }
        
        public function migrateDataForListingThroughDatabase($instituteId){
            $this->CI->load->model('listingsmigrationmodel');
            error_log("Step 1::::::::Fetching Institute count");
            $institutes =   $this->CI->listingsmigrationmodel->getInstitutesForMigration();
            
            
            if($instituteId > 0) {
            	$institutes['live']     = array($instituteId);
            	$institutes['draft']    = array($instituteId);
            }

            error_log("Live institutes found === ". count($institutes['live']));            
            error_log("Draft institutes found ==== ". count($institutes['draft']));
            $this->_performMigration($institutes['live'], 'live');
            $this->_performMigration($institutes['draft'], 'draft');
            
            return;
        }

        public function migrateDeletedDataForListingThroughDatabase(){
            global $suffixVal;
            $this->CI->load->model('listingsmigrationmodel');
            error_log("============= Fetching Institute count");

            $instituteIds =   $this->CI->listingsmigrationmodel->getDeletedInstitutesForMigration();
            // _p($instituteIds);die;
            if(empty($instituteIds)){
                return;
            }
            $institutesData     = $this->CI->listingsmigrationmodel->getOldInstituteTablesData($instituteIds, 'deleted');
            // _p($institutesData);die;
            error_log("============= Preparing Data for insert");
            $institutesDataForNewTable = $this->formatInstituteTableData($institutesData,'deleted');
            // _p($institutesDataForNewTable);die;
            error_log("============= Inserting data into tables");
            $this->CI->listingsmigrationmodel->migrateDeletedInstitutesData($institutesDataForNewTable);
            error_log("============= All Done Successfully !!!!!");
        }
        
        private function _performMigration($instituteIds, $status){
            global $suffixVal;
  	    
            error_log("Step 2:::::::::Migrating $status insitutessssss");
            if(!is_array($instituteIds) || empty($instituteIds)){
                return;
            }
            
            $institutesData     = $this->CI->listingsmigrationmodel->getOldInstituteTablesData($instituteIds, $status);
            $institutesDataForNewTable = $this->formatInstituteTableData($institutesData,$status);

            error_log("Step 3:::::::::Insert in New tables");
            error_log("Entries to be made in shiksha_institutes table ==== ".count($institutesDataForNewTable['shiksha_institutes'.$suffixVal]));
            error_log("Entries to be made in shiksha_listings_brochures table ==== ".count($institutesDataForNewTable['shiksha_listings_brochures'.$suffixVal]));
            error_log("Entries to be made in shiksha_institutes_locations table ==== ".count($institutesDataForNewTable['shiksha_institutes_locations'.$suffixVal]));
            error_log("Entries to be made in shiksha_institutes_companies_mapping table ==== ".count($institutesDataForNewTable['shiksha_institutes_companies_mapping'.$suffixVal]));
            
            $this->CI->listingsmigrationmodel->insertDataInNewInstituteTables($institutesDataForNewTable, $instituteIds, $status);
        }
        
        public function formatInstituteTableData($institutesData,$status){
            global $suffixVal;
            $cityStateMapping   = $this->CI->listingsmigrationmodel->getCityStateMapping();
            $institutesDataForNewTable  = array();

            foreach ($institutesData['institute_table'] AS $insId => $tableDataArray){
                foreach ($tableDataArray AS $tableRow){
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['listing_id']                  = $insId;
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['listing_type']                = 'institute';
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['institute_specification_type']= 'college';
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['name']                        = $tableRow['institute_name'];
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['establish_year']              = ($tableRow['establish_year']==0 || $tableRow['establish_year']=="") ? NULL : $tableRow['establish_year'];
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['micro_site_link']             = $tableRow['microsite_link'];
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['logo_url']                    = $tableRow['logo_link'];
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['featured_panel_link']         = $tableRow['featured_panel_link'];
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['status']                      = $tableRow['status'];
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['created_on']                  = $tableRow['created_on'];
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['updated_on']                  = $tableRow['updated_on'];
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['created_by']                  = $tableRow['created_by'];
                    $institutesDataForNewTable['shiksha_institutes'.$suffixVal][$insId]['updated_by']                  = $tableRow['updated_by'];
                    
                    // brochure related data is to be migrated to shiksha_listings_brochures
                    if(!empty($tableRow['institute_request_brochure_link'])){
                        $institutesDataForNewTable['shiksha_listings_brochures'.$suffixVal][$insId]['brochure_url']        = $tableRow['institute_request_brochure_link'];
                        $institutesDataForNewTable['shiksha_listings_brochures'.$suffixVal][$insId]['brochure_year']       = $tableRow['institute_request_brochure_year'];
                        $institutesDataForNewTable['shiksha_listings_brochures'.$suffixVal][$insId]['listing_type']        = 'institute';
                        $institutesDataForNewTable['shiksha_listings_brochures'.$suffixVal][$insId]['listing_id']          = $insId;
                        $institutesDataForNewTable['shiksha_listings_brochures'.$suffixVal][$insId]['is_auto_generated']   = 0;
                        $institutesDataForNewTable['shiksha_listings_brochures'.$suffixVal][$insId]['status']              = $tableRow['status'];
                    }
                }
            }

            foreach ($institutesData['institute_location_table'] AS $insId => $tableDataArray){
                foreach($tableDataArray AS $tableRow){
                    $temp   =   array();
                    $temp['listing_id']         =   $insId;
                    $temp['listing_type']       =   'institute';
                    $temp['listing_location_id']=   $tableRow['institute_location_id'];
                    $temp['city_id']            =   $tableRow['city_id'];
                    $temp['state_id']           =   $cityStateMapping[$tableRow['city_id']];
                    $temp['locality_id']        =   $tableRow['locality_id'];
                    $temp['is_main']            =   $tableRow['head_office'];
                    $temp['status']             =   $tableRow['status'];
                    $institutesDataForNewTable['shiksha_institutes_locations'.$suffixVal][$insId][$tableRow['institute_location_id']]  = $temp;
                }
            }

            if($status == 'live' || $status == 'deleted'){
                foreach ($institutesDataForNewTable['shiksha_institutes_locations'.$suffixVal] AS $insId => $insLocationArray){
                    if(count($insLocationArray) == 1){
                        $locationId = key($insLocationArray);
                        $insLocationArray[$locationId]['is_main']   =   1;
                        $institutesDataForNewTable['shiksha_institutes_locations'.$suffixVal][$insId] = $insLocationArray;
                    }
                }
            }

            foreach($institutesData['company_logo_mapping'] AS $insId => $tableDataArray){
                foreach($tableDataArray AS $tableRow){
                    $temp   =   array();
                    $temp['listing_id']     =   $tableRow['institute_id'];
                    $temp['listing_type']   =   'institute';
                    $temp['company_id']     =   $tableRow['logo_id'];
                    $temp['order']          =   $tableRow['company_order'];
                    $temp['status']         =   $tableRow['status'];
                    $institutesDataForNewTable['shiksha_institutes_companies_mapping'.$suffixVal][$insId][] = $temp;
                
                }
            }

            return $institutesDataForNewTable;
        }
        
        public function migrateExcelDataToDatabase($dataFromExcel){
            global $suffixVal;
            if(!is_array($dataFromExcel) || empty($dataFromExcel)){
                return ;
            }
            
            $data   =   array();
            $erroneousFields   =   array();
            foreach ($dataFromExcel AS $institudeId => $excelData){
                foreach ($excelData AS $field => $fieldData){
                    if(is_array($fieldData)){
                        if(in_array($field, array('usp','research_project'))){
                            foreach ($fieldData AS $value){
                                if(!empty($value)){
                                   $data['shiksha_institutes_additional_attributes'.$suffixVal][$field][$institudeId][] = $value; 
                                }
                            }
                        }
                    }elseif(!empty ($fieldData)){
                        if(in_array($field, array('institute_specification_type','ownership','student_type','is_autonomous','accreditation'))){
                            $fieldData  = strtolower($fieldData);
                        }
                        if($field == 'is_autonomous'){
                            if($fieldData == 'yes'){
                                $fieldData  =   1;
                            }elseif($fieldData == 'no'){
                                $fieldData  =   0;
                            }else{
                                $fieldData  =   NULL;
                                $erroneousFields[$institudeId]  .=   $field.':';
                                continue;
                            }
                        }
                        if($field   == 'institute_specification_type'){
                            if(!in_array($fieldData, array('academy', 'centre', 'college',  'department', 'faculty', 'school'))){
                                $fieldData  =   NULL;
                                $erroneousFields[$institudeId]  .=   $field.':';
                                continue;
                            }
                        }
                        if($field   ==  'ownership'){
                            if(!in_array($fieldData, array('private', 'public', 'partnership','public / government','public/government','public-private partnership'))){
                                $fieldData  =   NULL;
                                $erroneousFields[$institudeId]  .=   $field.':';
                                continue;
                            }
			    else if($fieldData == 'public / government' || $fieldData == 'public/government'){
				$fieldData = 'public';
			    }
			    else if($fieldData == 'public-private partnership'){
				$fieldData = 'partnership';
			    }
                        }
                        if($field   ==  'student_type'){
                            if(!in_array($fieldData, array('co-ed', 'girls', 'boys'))){
                                $fieldData  = NULL;
                                $erroneousFields[$institudeId]  .=   $field.':';
                                continue;
                            }
                        }
                        if($field   ==  'accreditation'){
                            if($fieldData   ==  'grade a'){
                                $fieldData  =   'grade_a';
                            }elseif($fieldData   ==  'grade b'){
                                $fieldData  =  'grade_b';
                            }elseif($fieldData   ==  'grade c'){
                                $fieldData  =  'grade_c';
                            }elseif($fieldData   ==  'grade d'){
                                $fieldData  =  'grade_d';
                            }else{
                                $fieldData  =  NULL;
                                $erroneousFields[$institudeId]  .=   $field.':';
                                continue;
                            }
                        }
                        $data['shiksha_institutes'.$suffixVal][$institudeId][$field]   =   $fieldData;
                    }
                }
            }
            
            $this->CI->load->model('listingsmigrationmodel');
            $this->CI->listingsmigrationmodel->migrateExcelDataToDB($data);
            // /home/vikasa/logs_container/listingsExcelDataMigrationErroneousFields.log
        }
    }
?>
