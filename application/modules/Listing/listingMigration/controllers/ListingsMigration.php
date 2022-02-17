<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ListingsMigration
 *
 * @author abhinav
 */
class ListingsMigration extends MX_Controller{
    public function migrateDataFromExcel(){
        return;
        $this->load->library('common/reader');
        $this->load->library('common/PHPExcel/IOFactory');
        
        $directoryPath  = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/public/listingsMigrationData/';
        $inputFileName  = $directoryPath.'MigrationDataSheet.xlsx';
        $inputFileType  = PHPExcel_IOFactory::identify($inputFileName);  
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);  
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($inputFileName);
        
        $objWorksheet   = $objPHPExcel->setActiveSheetIndex(1);
        $highestRow     = $objWorksheet->getHighestRow();
		$highestColumn  = $objWorksheet->getHighestColumn();
        $headingsArray  = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
        
        $dataFromExcel  =   array();
        foreach ($headingsArray[1] As $excelColumnKey => $excelColumnValue){
            if(in_array($excelColumnValue, array('Field Info','Institute ID')) || $excelColumnValue <= 0){
                continue;
            }
            $institudeId    =   0;
            $instituteInfo  =   array();
            for($row = 1; $row <= $highestRow; $row++){
                $fieldInfo      =   $objWorksheet->getCell('A'.$row)->getValue();
                $fieldInfoType  =   $objWorksheet->getCell('B'.$row)->getValue();
                $value          =   $objWorksheet->getCell($excelColumnKey.$row)->getValue();
                if(empty($value)){
                    continue;
                }
                if($fieldInfo   ==  'Field Info' && $fieldInfoType  ==  'Institute ID'){
                    $institudeId    =   $value;
                }elseif($fieldInfo  ==  'Basic Info'){
                    switch ($fieldInfoType){
                        case 'Type'                 :   $instituteInfo['institute_specification_type']  =   $value;break;
                        case 'Abbreviation'         :   $instituteInfo['abbreviation']                  =   $value;break;
                        case 'Synonyms'             :   $instituteInfo['synonym']                       =   $value;break;
                        case 'Ownership'            :   $instituteInfo['ownership']                     =   $value;break;
                        case 'Students Type'        :   $instituteInfo['student_type']                  =   $value;break;
                        case 'Is Autonomous?'       :   $instituteInfo['is_autonomous']                 =   $value;break;
                        case 'NAAC Accreditation'   :   $instituteInfo['accreditation']                 =   $value;break;
                    }
                }elseif($fieldInfo  ==  'Projects'){
                    if(strpos($fieldInfoType, 'Project') === 0){
                        $instituteInfo['research_project'][]    = $value;
                    }
                }elseif($fieldInfo  ==  'USP'){
                    if(strpos($fieldInfoType, 'USP') === 0){
                        $instituteInfo['usp'][] = $value;
                    }
                }
            }
            
            if($institudeId > 0){
                $instituteInfo['listing_id']    =   $institudeId;
                $instituteInfo['listing_type']  =   'institute';
                $dataFromExcel[$institudeId]    =   $instituteInfo;
            }
        }
        
        $this->load->library('ListingsMigrationLibrary');
        $this->listingsmigrationlibrary->migrateExcelDataToDatabase($dataFromExcel);
            
        
        return;
    }

    public function migrateDeletedInstitutes(){
        return;
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->load->library('ListingsMigrationLibrary');
        $this->listingsmigrationlibrary->migrateDeletedDataForListingThroughDatabase();
        _p('Done');
    }
    
    public function migrateDataCron($instituteId){
        return;
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->load->library('ListingsMigrationLibrary');
        $this->listingsmigrationlibrary->migrateDataForListingThroughDatabase($instituteId);
    }

    public function migrateDataCronListingsMain($instituteId){
        return;
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->load->model('listingsmigrationmodel');
        $this->listingsmigrationmodel->migrateListingsMain($instituteId);
	$this->listingsmigrationmodel->migrateListingsMain($instituteId,'draft');
    }

    public function migrateUniversityCron($instituteId){
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->load->model('listingsmigrationmodel');
        $instituteIds = explode(',',$instituteId);

        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $institutePostingLib = $this->load->library('nationalInstitute/InstitutePostingLib');
        $nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');

        /*perform validations first*/
        $errors = array();
        $universityMappings = array();
        $instituteObjs = $instituteRepo->findMultiple($instituteIds,array('location'));
        foreach ($instituteIds as $instituteId) {
            // check if institute has more than one location
            $instituteObj = $instituteObjs[$instituteId];
            // _p($instituteObj);die;
            if(empty($instituteObj) || $instituteObj->getId() == ''){
                $errors[] = 'Institute Object is blank for instituteId: '.$instituteId;
                continue;
            }
            if($instituteObj->getType() == 'university'){
                $errors[] = 'Already University for instituteId: '.$instituteId;
                continue;
            }
            $locations = $instituteObj->getLocations();
            if(count($locations) > 1){
                $errors[] = 'Locations are more than one for instituteId: '.$instituteId;
                continue;
            }
            $isDummy = $instituteObj->isDummy();
            if(!empty($isDummy)){
                $errors[] = 'Cannot convert dummy institute: '.$instituteId.' to university';
            }

            // for each institute check if current parent hierarchy doesnot have any institute or hierarchy already contains two universities
            $instituteHierarchy = $institutePostingLib->getInstituteParentHierarchyFromFlatTale(array($instituteId));
            $instituteHierarchy = $instituteHierarchy[$instituteId];
            // _p($instituteHierarchy);die;
            $universityCount = array();
            foreach ($instituteHierarchy as $row) {
                if($row['listing_id'] != $instituteId){
                    list($listingType) = explode('_',$row['id']);
                    if($listingType == 'institute'){
                        $errors[] = "If we make make $instituteId as university, then it cannot come under institute: ".$row['listing_id'];
                    }
                    else if($listingType == 'university'){
                        $universityCount[] = $row['listing_id'];
                    }
                }
                $universityMappings[$instituteId] = $universityCount;
            }
            if(count($universityCount) > 1){
                $errors[] = "If we make make $instituteId as university, then the hierarchy ".implode(',',$universityCount)." will have more than two universities";
            }

            if(!empty($universityMappings[$instituteId]) && count($universityMappings[$instituteId]) == 1){
                foreach ($universityMappings[$instituteId] as $id) {
                    $courseIds = $this->listingsmigrationmodel->getCourseIdsByParentId($instituteId);
                    if(!empty($courseIds)){
                        $courseIds = $this->listingsmigrationmodel->getCoursesHavingPrimaryId($courseIds,$id);
                        if(empty($courseIds)){
                            continue;
                        }
                        $errors[] = "The following courses: ".implode(',',$courseIds)." are still mapped to a university: $id already present in the hierarchy. So if we make institute: $instituteId as university then those courses cannot be mapped to the first university. Please change the primary mapping using course posting interface.";
                    }
                }
            }
        }
        if(!empty($errors)){
            foreach ($errors as $error) {
                _p($error);
            }
            die('Fix the above errors and try again');
        }

        foreach ($instituteIds as $instituteId) {
            // delete from solr first
            Modules::run('indexer/NationalIndexer/delete','institute',$instituteId);
            // make dbchanges
            $this->listingsmigrationmodel->migrateUniversityListings(array($instituteId));
            // make changes in shiksha_courses table
            if(!empty($universityMappings[$instituteId])){
                foreach ($universityMappings[$instituteId] as $id) {
                    $this->listingsmigrationmodel->updateInstituteTypeInCourseTables($instituteId,$id,'university');
                }
            }
            else{
                $this->listingsmigrationmodel->updateInstituteTypeInCourseTables($instituteId,$instituteId,'university');
            }

            // update seo Details
            $instituteObj = $instituteObjs[$instituteId];
            if(!$instituteObj->isDummy()){
                $instituteNameForUrl = seo_url($instituteObj->getName(),"-","200",true);
                $mainLocation = $instituteObj->getMainLocation();
                $cityNameForUrl = (stripos($instituteObj->getName(), $mainLocation->getCityName()) === FALSE) ? seo_url($mainLocation->getCityName(),"-","150",true) : '';

                $seoUrl = '/university/'.$instituteNameForUrl.'-'.$cityNameForUrl.'-'.$instituteId;
                if(stripos($instituteObj->getName(), $mainLocation->getCityName()) === FALSE){
                    $seoTitle = htmlentities($instituteObj->getName()).', '.$mainLocation->getCityName().' | Shiksha.com';
                    $seoDescription = 'Get complete information about admissions process, courses, colleges at '.htmlentities($instituteObj->getName()).', '.$mainLocation->getCityName().'.';
                }
                else{
                    $seoTitle = htmlentities($instituteObj->getName()).' | Shiksha.com';
                    $seoDescription = 'Get complete information about admissions process, courses, colleges at '.htmlentities($instituteObj->getName()).'.';
                }
                $this->listingsmigrationmodel->updateSEODetailsForInstitute($instituteId,$seoUrl,$seoTitle,$seoDescription);
            }
            // remove institute obj cache
            $nationalinstitutecache->removeInstitutesCache(array($instituteId));
            // index institute as university
            Modules::run('indexer/NationalIndexer/index','university',$instituteId);
        }
        // index tags
        _p('DONE');
    }

    public function checkUniversityCron(){
        return;
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->load->model('listingsmigrationmodel');
        $this->config->load('universityConfig');
        $universityIds = $this->config->item('universityIds');
        $universityIdArray = explode(',',$universityIds);
        echo "Total number of Institutes to be migrated to Universities = ". count($universityIdArray)."<br/>";
        $this->listingsmigrationmodel->checkUniversityListings();
    }

    public function migrateHeadOffice($instituteId){
        return;
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->load->model('listingsmigrationmodel');
        $this->listingsmigrationmodel->migrateHeadOffice('live', $instituteId);
        $this->listingsmigrationmodel->migrateHeadOffice('draft', $instituteId);
    }

    public function migrateSynonyms($instituteId){
        return;
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->load->model('listingsmigrationmodel');
        $this->listingsmigrationmodel->migrateSynonyms($instituteId);
    }

    public function updateSeoDataForOldListing($listingId){
        return;
        if(!empty($listingId)){
            $this->load->builder("nationalInstitute/InstituteBuilder");
            $instituteBuilder = new InstituteBuilder();
            $this->instituteRepo = $instituteBuilder->getInstituteRepository();       
            
            $instituteObj = $this->instituteRepo->find($listingId);

            $this->processDataForSeoDataMigration($instituteObj,$displayData);
            _p($displayData);

            $this->load->model('listingsmigrationmodel');
            $result = $this->listingsmigrationmodel->updateSEODetails($displayData);
            error_log('::::::::processes Institute id:::::::'. $result);

            $this->invalidateAllListingCache(array($listingId));
        }
     
    }

    public function updateSeoDataForAllOldListings(){
        return;
        ini_set('memory_limit','-1');
        set_time_limit(0);
        $this->load->model('listingsmigrationmodel');
        $allListingsId = $this->listingsmigrationmodel->getAllListingsForMigration();
        
        if(!empty($allListingsId)){
            $this->load->builder("nationalInstitute/InstituteBuilder");
            $instituteBuilder = new InstituteBuilder();
            $this->instituteRepo = $instituteBuilder->getInstituteRepository();       
            
            $allListingsId = array_chunk($allListingsId, 1000);

            foreach ($allListingsId as $chunk) {
                
                $this->instituteRepo->disableCaching();
            
                $instituteObj = $this->instituteRepo->findMultiple($chunk);
                $count = 0;
                foreach($instituteObj as $instituteId =>$institute) {
                    $count = $count+1;
                    $displayData = array();
                    $this->processDataForSeoDataMigration($institute,$displayData);
                    $result = $this->listingsmigrationmodel->updateSEODetails($displayData);
                    error_log('::::::::count:::::::'. $count,3,"/tmp/updateSeoDataForAllOldListings.log");
                    error_log('::::::::processes Institute id:::::::'. $result,3,"/tmp/updateSeoDataForAllOldListings.log");
                }

                $this->invalidateAllListingCache($chunk);
            }
            
        }
     
    }

    public function processDataForSeoDataMigration($instituteObj,&$data){
        return;
        $listingId = $instituteObj->getId();
        $listingType = $instituteObj->getType();
        $instituteName = htmlentities($instituteObj->getName());
        $mainLocation = $instituteObj->getMainLocation();        
        if(!empty($mainLocation)){
            $cityName  = $mainLocation->getCityName();
            $localityName = $mainLocation->getLocalityName();
        }
        

         //SEO details
        $listingName = seo_url($instituteName,"-","200",true);
        if(stripos($instituteName, $cityName) === FALSE && $cityName != ''){
            $cityAppend = '-'.seo_url($cityName,"-","150",true);
            $city = ', '.$cityName;
        }
        if(isset($localityName) && $localityName != ''){
            $localityAppend = '-'.seo_url($localityName,"-","150",true);
            $locality = ', '.$localityName;
        }

        $data['listingId'] = $listingId;
        if($listingType == 'university'){
            $data['listing_type'] = 'university_national';
            $data['seoUrl'] = SHIKSHA_HOME.'/university/'.$listingName.$cityAppend.'-'.$listingId;
            $data['seoTitle'] = $instituteName.$city.' | Shiksha.com';
            $data['seoDescription'] = 'Get complete information about admissions process, courses, colleges at '.$instituteName.$city.'.';

        }else{
            $data['listing_type'] = 'institute';
            $data['seoUrl'] = SHIKSHA_HOME.'/college/'.$listingName.$localityAppend.$cityAppend.'-'.$listingId;
            $data['seoTitle'] = $instituteName.$locality.$city.' | Shiksha.com';
            $data['seoDescription'] = 'See available courses at '.$instituteName.$locality.$city.'. Find out details about fees, admissions, courses, placement, faculty and much more only at Shiksha.com';

        }   
    }

    public function invalidateAllListingCache($listingArray){
        return;
        // invalidate institute hierarchy cache
        $this->nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');
        $this->nationalinstitutecache->removeInstitutesCache($listingArray);
    }

    public function fixPhotoTitle($mediaId){
        return;
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->load->model('listingsmigrationmodel');
        $this->listingsmigrationmodel->fixPhotoTitle($mediaId);
    }

    public function getCorruptInstitute(){
        global $TABLES_WITH_I_MAPPING;
        $this->load->model('listingsmigrationmodel');
        foreach($TABLES_WITH_I_MAPPING as $tableData)
        {
            
            $offset = 0;$limit = 500;
            $fileName =  '/tmp/corrupt_'.$tableData['table'].'.log';
            error_log("institute_type_corrupt_data \n",3,$fileName);
            $corrupt_Data = array();
            do{
                $corrupt_Data = $this->listingsmigrationmodel->getCorruptInstitute($tableData,$limit,$offset);
                $offset++;
                
                foreach ($corrupt_Data as $row) {

                    error_log("Corrupt entry for instituteId: ".$row[$tableData['instituteIdColumn']]." with primary key : " .$row[$tableData['uniqueId']]."\n",3,$fileName);
                }
               
            }while(!empty($corrupt_Data));

            _P('Cron complete');        
        } 
    }

    public function getCorruptCourses(){
        global $TABLES_WITH_C_MAPPING;
        $this->load->model('listingsmigrationmodel');
        foreach($TABLES_WITH_C_MAPPING as $tableData)
        {
            $offset = 0;$limit = 500;
            $fileName =  '/tmp/corrupt_'.$tableData['table'].'.log';
            error_log("course_type_corrupt_data \n",3,$fileName);
            $corrupt_Data = array();
             do{
                    $corrupt_Data = $this->listingsmigrationmodel->getCorruptCourses($tableData,$limit,$offset);
                    $offset++;
                    foreach ($corrupt_Data as $row) {
                        error_log("Corrupt entry for CourseId: ".$row[$tableData['courseIdColumn']]." with primary key : " .$row[$tableData['uniqueId']]."\n",3,$fileName);
                    }
                  
                }while(!empty($corrupt_Data));
        }    
        _P('Cron complete');        
    }
    
    public function getCorruptInstituteCourseMappings(){
        $this->load->model('listingsmigrationmodel');
        $this->listingsmigrationmodel->getCorruptInstituteCourseMappings();
    }
}
