<?php

class TestListing extends MX_Controller
{
    function index($params)
    {
        $this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        
        $categoryRepository = $categoryBuilder->getCategoryRepository();
        $categories = $categoryRepository->getSubCategories(3,'national');
         
        $category = $categoryRepository->find(3); 
        
        _p($category); 
        _p($categories);
        
        $this->load->builder('LocationBuilder','common');
        $locationBuilder = new LocationBuilder;
         
        $locationRepository = $locationBuilder->getLocationRepository();
        
        $city = $locationRepository->findCity(74);
        
        $cities = $locationRepository->getCitiesByMultipleTiers(array(1,2),2);
        
        $states = $locationRepository->getStatesByCountry(2);
        
        $country = $locationRepository->findCountry(10);
        $state = $locationRepository->findState(29);
        $zone = $locationRepository->findZone(3);
        $locality = $locationRepository->findLocality(3);
        
        _p($cities);
        _p($states);
        _p($country);
        _p($state);
        _p($zone);
        _p($locality);
        
        
        
        $this->load->builder('LDBCourseBuilder','LDB');
        $LDBCourseBuilder = new LDBCourseBuilder;
         
        $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
        $LDBCourse = $LDBCourseRepository->find(11);
        
        $LDBCourses = $LDBCourseRepository->getLDBCoursesForSubCategory(105);
        
        
        _p($LDBCourse);
        _p($LDBCourses);
        
        
        
        $this->load->builder('CategoryPageBuilder','categoryList');
        $categoryPageBuilder = new CategoryPageBuilder;
         
        $categoryPage = $categoryPageBuilder->getCategoryPage($params);
        
        $banner = $categoryPage->getBanner();
        $institutes = $categoryPage->getInstitutes();
        
        $category = $categoryPage->getCategory();
        $subCategory = $categoryPage->getSubCategory();
        _p($subCategory);
        
        
        $city = $categoryPage->getCity();
        $country = $categoryPage->getCountry();
        $state = $categoryPage->getState();
        _p($city);
        _p($country);
        _p($state);
        
        
        //$this->load->library('categoryList/CategoryPageRequest');
        //$categoryPageRequest = new CategoryPageRequest($params);
        //
        //$this->load->library('listing/clients/InstituteFinderClient');
        //
        //$d = $this->institutefinderclient->getCategorypageListings($categoryPageRequest);
        
        //_p($d);
        //return;
        //$instituteId = 26465;
        ////$courseId = 90543;
        //$courseId = 129921;
        //
        //$this->load->library('listing/clients/InstituteFinderClient');
        //
        //$data = $this->institutefinderclient->getCategorypageListings(3,1,10223,1,2);
        //
        //_p($data);
        //
        //
        //return;
        //
        //$this->load->repository('InstituteRepository');
        //$instituteRepository = new InstituteRepository;
        //$institute = $instituteRepository->find($instituteId);
        //
        //
        //$this->load->repository('CourseRepository');
        //$courseRepository = new CourseRepository;
        //$course = $courseRepository->find($courseId);
        //
        //_p($course);
        //
        //return;
        foreach($institutes as $institute) {
            $course = $institute->getFlagshipCourse();
?>        
        
        <h1><a href='<?php echo $institute->getURL(); ?>'><?php echo $institute->getName(); ?></a>
        <span style='font:normal 12px arial; color:#666;'>, <?php echo $institute->getMainLocation()->getCity()->getName(); ?>,
        <?php echo $institute->getMainLocation()->getCountry()->getName(); ?>
        </span></h1>    
        <div style="width:900px;">
        <div style="float:left; width:124px; border:1px solid #eee;">
            <?php
            $headerImage = $institute->getMainHeaderImage();
            if($headerImage) {
            ?>  
                <img src='<?php echo $headerImage->getThumbURL(); ?>' />
            <?php
            }
            ?>
        </div>
        <div style="float:left; width:700px; border:1px solid #eee; margin-left:20px;">
            Alumni Rating: <?php echo $institute->getAlumniRating(); ?>/5
            
            <h2><a href='<?php echo $course->getURL(); ?>'><?php echo $course->getName(); ?></a>
            <span style='font:normal 12px arial; color:#666;'>, <?php echo $course->getDuration(); ?>, <?php echo $course->getCourseType(); ?>
            , <?php echo $course->getCourseLevel(); ?></span>
            </h2>
            
            <?php echo implode(', ',$course->getAffiliations()); ?><br />
            <?php echo $course->getFees(); ?>
            
            | Eligibility: 
            
            <?php echo implode(', ',$course->getEligibilityExams()); ?><br /><br />
            <?php echo implode(', ',$course->getSalientFeatures(4)); ?>
        </div>
        
        <div style="clear:both"></div>
        </div>
<?php
        }
    }
    
    /*
	 *Temporary function, should be avoided.
	*/
    public function validateMobileNumbers() {
		ini_set('memory_limit','2048M');
		set_time_limit(864000); //Max execution time limit set for 1 complete day
		$this->load->model("listing/listingmodel", "");
        $maxListings = 1;
		$batchSize = 1;
		$flag = true;
		$start = 0;
        $listingModelObj = new listingmodel();
        $this->load->builder("ListingBuilder", "listing");
		$listingBuilderInstance = new ListingBuilder();
        $courseRepos    = $listingBuilderInstance->getCourseRepository();
        $instituteRepos = $listingBuilderInstance->getInstituteRepository();
        $ivrURL = "http://www.smartivr.in/api/voice/quickCall/?username=Shiksha&password=123456&ivr_id=800060964&format=xml&phone_book=";
		while($flag){
			$data = $listingModelObj->getListingContactNumbersForValidation($start, $batchSize, 'mobile');
			if(is_array($data) && !empty($data) && count($data) > 0){
                foreach($data as $d){
                    $this->printlog("-------------------------------------------------------------------------");
                    $this->printlog($d);
                    $contactDetailsId   = $d['contact_details_id'];
                    $mobileNumber   = $d['contact_cell'];
                    $listingType    = $d['listing_type'];
                    $listingTypeId  = $d['listing_type_id'];
                    if(!empty($mobileNumber) && !empty($listingType) && !empty($listingTypeId)) {
                        $this->printlog("original mobile number: " . $mobileNumber);
                        $sanitizedMobileNumber = $this->sanitizeMobileNumber($mobileNumber);
                        //$sanitizedMobileNumber = '09958992660';
                        $this->printlog("sanitized mobile number: " . $sanitizedMobileNumber);
                        $listingTitleForValidation = "";
                        $listingIdForValidation = "";
                        $parentListingId = "";
                        switch($listingType){
                            case 'institute':
                                $instituteObject = $instituteRepos->find($listingTypeId);
                                if(!empty($instituteObject)){
                                    $instituteTitle = $instituteObject->getName();
                                    $instituteId = $instituteObject->getId();
                                    if(!empty($instituteTitle)  && !empty($instituteId)){
                                        $listingTitleForValidation = $instituteTitle;
                                        $listingIdForValidation = $instituteId;
                                        $parentListingId = $instituteId;
                                    }
                                }
                                break;
                            case 'course':
                                $courseObject = $courseRepos->find($listingTypeId);
                                if(!empty($courseObject)){
                                    $courseId = $courseObject->getId();
                                    $listingTitleForValidation = $courseObject->getInstituteName();
                                    $listingIdForValidation = $courseId;
                                    $parentListingId = $courseObject->getInstId();
                                }
                                break;
                        }
                        $sanitizedMobileNumber = trim($sanitizedMobileNumber);
                        $listingIdForValidation = trim($listingIdForValidation);
                        $listingTitleForValidation = trim($listingTitleForValidation);
                        
                        $this->printlog("original listing id: " . $listingTypeId);
                        $this->printlog("original listing type: " . $listingType);
                        $this->printlog("mobile number: " . $sanitizedMobileNumber);
                        $this->printlog("listing id for validation: " . $listingIdForValidation);
                        $this->printlog("listing title for validation: " . $listingTitleForValidation);
                        
                        if(!empty($listingIdForValidation) && !empty($listingTitleForValidation) && !empty($sanitizedMobileNumber)){
                            $returnValue = $listingModelObj->checkIfValidationAlreadyDone($sanitizedMobileNumber, $parentListingId, 'mobile');
                            if($returnValue === false){
                                $this->printlog("DB error occurred while checking if same number already validated against this institute or not.");
                            } else {
                                $modelParams = array();
                                $modelParams['contact_details_id']  = $contactDetailsId;
                                $modelParams['contact_number']      = $sanitizedMobileNumber;
                                $modelParams['contact_number_type'] = 'mobile';
                                $modelParams['listing_id']          = $listingTypeId;
                                $modelParams['listing_type']        = $listingType;
                                $modelParams['parent_listing_id']   = $parentListingId;
                                $modelParams['parent_listing_type'] = 'institute';
                                if(is_array($returnValue) && empty($returnValue)){

				    $listingTitleForValidation = preg_replace('/[^a-z\d\s+-]/i', ' ', $listingTitleForValidation);
                                    $listingTitleForValidation = preg_replace('/\s+/', ' ', $listingTitleForValidation);

                                    $ivrURLParams = $sanitizedMobileNumber . "," . urlencode($listingTitleForValidation) . "," . $listingTypeId . "," . $listingType;
                                    $curlURL = $ivrURL . $ivrURLParams;
				    $this->printlog("curl url: " . $curlURL);
                                    $curlResponse = $this->curl($curlURL);
                                    $this->printlog("Curl response");
                                    $this->printlog($curlResponse);
                                    $response = "";
                                    if($curlResponse['error'] === false){
                                        $response = $curlResponse['response'];
                                    }
                                    if(!empty($response)){
                                        $modelParams['response']  = $response;
                                        $modelParams['verfied_via_contact_details_id']  = '';
                                        $return = $listingModelObj->insertMobileValidationRecord($modelParams);
                                        if($return){
                                            $this->printlog("New mobile validation record entered");
                                        }    
                                    } else {
                                        $this->printlog("Curl call failed for this contact number");
                                    }
                                } else {
                                    $this->printlog("This mobile number already validated");
                                    $tempContactDetailsId = $returnValue['contact_details_id'];
                                    $modelParams['response']  = '';
                                    $modelParams['verfied_via_contact_details_id']  = $tempContactDetailsId;
                                    $return = $listingModelObj->insertMobileValidationRecord($modelParams);
                                }
                            }
                        }    
                    } else {
                        $this->printlog("Invalid input params(contact no, listing type, listing id)");
                    }
                    $this->printlog("-------------------------------------------------------------------------");
                }
				$start = $start + $batchSize;
			} else {
				$flag = false;
			}
			if($start >= $maxListings && $maxListings != -1){
				$flag = false;
			}
		}
	}
    
    private function sanitizeMobileNumber($mobileNumber = NULL) {
        $sanitizedMobileNumber = false;
        if(!empty($mobileNumber)){
            $mobileNumber = trim($mobileNumber);
            $mobileNumber = substr($mobileNumber, -10);
            if(strlen($mobileNumber) == 10){
                $mobileNumber = "0" . $mobileNumber;
                $sanitizedMobileNumber = $mobileNumber;
            }
        }
        return $sanitizedMobileNumber;
    }
    
    private function printlog($data){
        _p($data);
        if(is_array($data)){
            error_log("LISTING_MOBILE_VALIDATION_SCRIPT:: " . print_r($data, true));
        } else {
            error_log("LISTING_MOBILE_VALIDATION_SCRIPT:: " . $data);
        }
    }
    
    private function curl($url, $content = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        $curlHeader = 0;
        if(strlen(trim($content)) > 0){
                $curlHeader = 1;
        }
        curl_setopt($ch, CURLOPT_HEADER, $curlHeader);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        $return = array('error' => false, 'response' => false);
        if($result === false){
            $return['error'] = curl_error($ch);
        } else {
            $return['response'] = $result;
        }
        curl_close($ch);
        return $return;
    }
    
    public function getChildKey( $key, $inputType=1 )
    {
        $this->load->library('categoryList/cache/CategoryPageCache');
        $catPageCache = new CategoryPageCache();

        $this->load->builder('CategoryPageBuilder','categoryList');
        $this->load->library("CategoryPageRequest");

        if( $inputType == 1 )
        {
               $trackData = explode('-',$key);
               $urlData['categoryId']                 = $trackData[0];
               $urlData['subCategoryId']             = $trackData[1];
               $urlData['LDBCourseId']             = $trackData[2];
               $urlData['localityId']                 = $trackData[3];
               $urlData['cityId']                 = $trackData[4];
               $urlData['stateId']                 = $trackData[5];
               $urlData['countryId']                 = $trackData[6];
               $urlData['regionId']                 = $trackData[7];
               $urlData['affiliation']             = $trackData[8];
               $urlData['examName']                 = $trackData[9];
               $urlData['feesValue']                 = $trackData[10];
               $request     = new CategoryPageRequest();
               $request->setNewURLFlag(1);
               $request->setData($urlData);
               _p($urlData);
        }
        else
        {
               $request     = new CategoryPageRequest($key, 'RNRURL');
               //$request->setNewURLFlag(1);
        }

        $childKeys = $catPageCache->getChildPageKeys($request);

        _p("Showing Child Keys of : ".$request->getPageKey());
        _p($childKeys);
    }

    public function displayCourse($courseId){
	$this->load->builder('ListingBuilder','listing');
	$listingBuilder = new ListingBuilder;
	$this->courseRepository = $listingBuilder->getCourseRepository();
	$this->courseRepository->disableCaching();
    $course = $this->courseRepository->find($courseId);
    _p($course);
    }

    public function displayInstitute($instituteId){
    $this->load->builder('ListingBuilder','listing');
    $listingBuilder = new ListingBuilder;
    $this->instituteRepo = $listingBuilder->getInstituteRepository();
	$this->instituteRepo->disableCaching();
    $institute = $this->instituteRepo->find($instituteId);
    _p($institute);
    }
   
    public function functionExists($funcName) {

        if(function_exists ($funcName)) {
          echo "function exists";
        } else {
            echo "function not exists";
        }
    }
    
    public function deleteCache($type, $id){
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $this->cache = $listingBuilder->getListingCache();
        switch ($type) {
            case 'course':
                $this->cache->deleteCourse($id);
                break;

            case 'questionCount':
                $this->cache->deleteCourseQuestionCount($id); //course id
            
            case 'reviewCount':
                $this->cache->deleteCourseReviewCount($id); //course id
                
            default:
                $this->cache->deleteInstitute($id);
                break;
        }
    }

    public function getDominantSubCategoryObjForCourse($courseId){
        //$CI = &get_instance();
        $this->load->library('listing/NationalCourseLib');
        $nationallib = new NationalCourseLib();
        $subCategoryInfo = $nationallib->getCourseDominantSubCategoryDB(array($courseId));
        _p($subCategoryInfo);

        $this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder();
        $categoryRepository = $categoryBuilder->getCategoryRepository();
        $dominantCatObj = $categoryRepository->find($subCategoryInfo['subCategoryInfo'][$courseId]['dominant']);
        _p($dominantCatObj);
    }

    /*
     * Nikita Jain, LF-4248
     * --------------------
     * Aim - Built primarily to compress institutes with 1000s of locations to just few locations(head office only), and then restore them back whenever required
     * Params - instituteId, action: compress/restore
     */
    public function changeNonHeadOfficeLocationStatus($instituteId, $action) {
        $this->logFileName = 'log_remove_non_head_office_location_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;

        $this->load->model("listing/listingmodel", "");
        $listingModelObj = new listingmodel();

        //remove(restore) all locations except head office
        $courseIds = $listingModelObj->changeNonHeadOfficeLocationStatus($instituteId, $action);
        error_log("Done with queries.\n", 3, $this->logFilePath);

        //delete cache
        $this->deleteCache('institute', $instituteId);
        foreach ($courseIds as $courseId) {
            $this->deleteCache('course', $courseId);
        }
        error_log("Deleted cache.\n", 3, $this->logFilePath);
        error_log("Cron ended.\n", 3, $this->logFilePath);
    }
}