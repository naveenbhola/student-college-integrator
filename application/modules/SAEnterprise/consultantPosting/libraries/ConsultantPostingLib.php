<?php

/**
 * Description of ConsultantPostingLib
 *
 * @author abhinav
 */
class ConsultantPostingLib {
    private $CI;
    private $consultantPostingModel;
    
    // constructor defined here just for the reason to get CI instances for loading different classes in Codeigniter to perform business logic
    public function __construct() {
        $this->CI = & get_instance();
    }
    
    // to get model instance.
    private function _setDependencies(){
        $this->CI->load->model('consultantPosting/consultantpostingmodel');
        $this->consultantPostingModel = new consultantpostingmodel();
    }
    
    function getConsultantUniversityMappingData($searchType, $paginatorObj, $searchContentName){
    	$this->_setDependencies();
        return $this->consultantPostingModel->getConsultantUniversityMappingData($searchType, $paginatorObj, $searchContentName);
    }
    
    function getConsultantList(){
        $this->_setDependencies();
        return $this->consultantPostingModel->getConsultantList();
    }
    
    function getUniversityAndCountryByCounsltantId($consultantId=''){
        $this->_setDependencies();
        $resultUniversities = array();
        $universityIds = $this->consultantPostingModel->getUniversityByCounsltant($consultantId);
        if(!empty($universityIds)){
	    $this->CI->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $this->universityRepository = $listingBuilder->getUniversityRepository();
	    $universitiesObj = $this->universityRepository->findMultiple($universityIds);
	    foreach($universitiesObj as $universityObj)
	    {
			if($universityObj->getId()!=''){	
				$record = array();
				$record['universityId']         = $universityObj->getId();
				$record['universityName']       = $universityObj->getName();
				$resultUniversities[$universityObj->getLocation()->getCountry()->getId()]['counId']     = $universityObj->getLocation()->getCountry()->getId();
				$resultUniversities[$universityObj->getLocation()->getCountry()->getId()]['counName']   = $universityObj->getLocation()->getCountry()->getName();
				$resultUniversities[$universityObj->getLocation()->getCountry()->getId()]['uniData'][] = $record;
			}
	    }
	}
        //echo json_encode($resultUniversities);
        return $resultUniversities;
    }
    
    public function saveConsultantFormData($consultantFormData) {
        $this->_setDependencies();
        $this->consultantPostingModel->saveConsultantFormData($consultantFormData);
    }

    public function getConsultantTableData($displayDataStatus ,$paginatorObj ,$searchConsName)
    {
         $this->_setDependencies();
        return $this->consultantPostingModel->getConsultantTableData($displayDataStatus,$paginatorObj,$searchConsName);
    }

	public function getConsultantFormData($consultantId) {
        $this->_setDependencies();
        return $this->consultantPostingModel->getConsultantFormData($consultantId);
    }
	
	public function getConsultantSalesPersons(){
		$this->_setDependencies();
		$rawData = $this->consultantPostingModel->getConsultantSalesPersons();
		$resultData = array();
		foreach($rawData as $data){
			$resultData[$data['id']] = array('name'=>$data['name'],'email'=>$data['email']);
		}
		return $resultData;
	}
        
    public function getStudentProfileMappingData($searchType ,$paginatorObj ,$searchContentName)
    {
        $this->_setDependencies();
        return $this->consultantPostingModel->getStudentProfileMappingData($searchType ,$paginatorObj ,$searchContentName);
    }
    
        public function uploadDocument($proofFieldName) {
                
                extract($this->_prepareUploadedDocumentData($proofFieldName));
                if(isset($errorResponse['uploaderror'])){
                        return $errorResponse;
                }
                $response = array();
                $appId = 1;
                $this->CI->load->library('upload_client');
                $uploadClient = new Upload_client();
                if(count($proofDocuments['imageDocuments']['name'])){
                        $uploadResponse = $uploadClient->uploadFile($appId,'image',$proofDocuments,array(),"-1",'consultantStudentProfile','imageDocuments');

                        if($uploadResponse['status'] == 1){
                                for($i = 0;$i<$uploadResponse['max'];$i++){
                                        $responseData['mediaid'] = $uploadResponse[$i]['mediaid'];
                                        $responseData['url'] = $uploadResponse[$i]['imageurl'];
                                        $response[] = $responseData;
                                }
                        }
                }
                
                if(count($proofDocuments['pdfDocuments']['name'])){
                        $uploadResponse = $uploadClient->uploadFile($appId,'pdf',$proofDocuments,array(),"-1",'consultantStudentProfile','pdfDocuments');


                        if(is_array($uploadResponse) && $uploadResponse['status'] == 1){
                                for($i=0;$i<$uploadResponse['max'];$i++){
                                        $responseData['mediaid'] = $uploadResponse[$i]['mediaid'];
                                        $responseData['url'] = $uploadResponse[$i]['imageurl'];
                                        $response[] = $responseData;
                                }
                        }else{
                                if($upload_array == 'Size limit of 50 Mb exceeded') {
                                        $response['uploaderror'][0] = "Please upload a documents less than 50 MB in size";	
                                }
                                
                        }
                }
                
                if(count($proofDocuments['emailDocuments']['name'])){
                        $uploadResponse = $uploadClient->uploadFile($appId,'email',$proofDocuments,array(),"-1",'consultantStudentProfile','emailDocuments');

                        
                        if(is_array($uploadResponse) && $uploadResponse['status'] == 1){
                                for($i=0;$i<$uploadResponse['max'];$i++){
                                        $responseData['mediaid'] = $uploadResponse[$i]['mediaid'];
                                        $responseData['url'] = $uploadResponse[$i]['imageurl'];
                                        $response[] = $responseData;
                                }
                        }
                }
                
                return $response;


        }
        
        private function _prepareUploadedDocumentData($proofFieldName){
                if($proofFieldName == ''){
                        return array('proofDocuments'    => -1,
                                        'errorResponse'  => 'proofFieldName is blank');
                }
                
                $_FILES[$proofFieldName]['name'] 		= array_values(array_filter($_FILES[$proofFieldName]['name'] 	,"trim"));
                $_FILES[$proofFieldName]['type'] 		= array_values(array_filter($_FILES[$proofFieldName]['type'] 	,"trim"));
                $_FILES[$proofFieldName]['tmp_name']            = array_values(array_filter($_FILES[$proofFieldName]['tmp_name']  ,"trim"));
                $_FILES[$proofFieldName]['error']               = array_values(array_filter($_FILES[$proofFieldName]['error'] 	,"trim"));
                $_FILES[$proofFieldName]['size']		= array_values(array_filter($_FILES[$proofFieldName]['size']	,"trim"));
                
                $proofDocuments = array();
                $documentsTypesAllowed = array('image'  =>      array('image/gif','image/jpeg','image/jpg','image/png'),
                                                'pdf'   =>      array('application/pdf'),
                                                'email' =>      array('message/rfc822','application/x-extension-eml')
                                                );
                for($i=0; $i< count($_FILES[$proofFieldName]['name']); $i++){
                        $type = $_FILES [$proofFieldName]['type'][$i];
                        if(in_array($type, $documentsTypesAllowed['image'])){
                                list ( $width,$height,$type,$attr ) = getimagesize ( $_FILES [$proofFieldName] ['tmp_name'] [$i] );
                                if ($width / $height != 1.5) {
                                       $errorResponse['uploaderror'][$i] = ' Only image in dimension of 3:2 ratio are allowed';
                                }
                                $proofDocuments['imageDocuments']['name'][]         = $_FILES[$proofFieldName]['name'][$i];
                                $proofDocuments['imageDocuments']['type'][]         = $_FILES[$proofFieldName]['type'][$i];
                                $proofDocuments['imageDocuments']['tmp_name'][]     = $_FILES[$proofFieldName]['tmp_name'][$i];
                                $proofDocuments['imageDocuments']['error'][]        = $_FILES[$proofFieldName]['error'][$i];
                                $proofDocuments['imageDocuments']['size'][]         = $_FILES[$proofFieldName]['size'][$i];
                        }elseif(in_array($type, $documentsTypesAllowed['pdf'])){
                                $proofDocuments['pdfDocuments']['name'][]               = $_FILES[$proofFieldName]['name'][$i];
                                $proofDocuments['pdfDocuments']['type'][]               = $_FILES[$proofFieldName]['type'][$i];
                                $proofDocuments['pdfDocuments']['tmp_name'][]           = $_FILES[$proofFieldName]['tmp_name'][$i];
                                $proofDocuments['pdfDocuments']['error'][]              = $_FILES[$proofFieldName]['error'][$i];
                                $proofDocuments['pdfDocuments']['size'][]               = $_FILES[$proofFieldName]['size'][$i];
                        }elseif(in_array($type, $documentsTypesAllowed['email'])) {
                                $proofDocuments['emailDocuments']['name'][]               = $_FILES[$proofFieldName]['name'][$i];
                                $proofDocuments['emailDocuments']['type'][]               = $_FILES[$proofFieldName]['type'][$i];
                                $proofDocuments['emailDocuments']['tmp_name'][]           = $_FILES[$proofFieldName]['tmp_name'][$i];
                                $proofDocuments['emailDocuments']['error'][]              = $_FILES[$proofFieldName]['error'][$i];
                                $proofDocuments['emailDocuments']['size'][]               = $_FILES[$proofFieldName]['size'][$i];
                        }else{
                                $errorResponse['uploaderror'][$i] = ' Proof of type .jpg,.jpeg,.png,.gif,.pdf or .eml are allowed';
                        }
                        
                }
                $returnData = array('proofDocuments'    => $proofDocuments,
                                        'errorResponse' => $errorResponse
                                        );
                return $returnData;
        }


        public function saveStudentProfileForm($studentProfileFormData){
            $this->_setDependencies();
            return $this->consultantPostingModel->saveStudentProfileForm($studentProfileFormData);
        }
	
	public function getConsultantMappedUniversities($consultantId){
		$this->_setDependencies();
		return $this->consultantPostingModel->getConsultantMappedUniversities($consultantId);
	}
	
	public function saveConsultantUniversityMappingFormData($dataArray){
		$this->_setDependencies();
		$this->consultantPostingModel->saveConsultantUniversityMappingFormData($dataArray);
	}

    /*
     * get list of cities allowed for consultant locations
     */
    public function getConsultantLocationCities(){
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        $citiesArray = $locationRepository->getCities(2);
        $citiesData = array();
        foreach($citiesArray as $cityObj){
            $citiesData[]   = array('cityId'   => $cityObj->getId(),
                                    'cityName' => $cityObj->getName()
                                    );
        }
        usort($citiesData,  function($a,$b){
            if(strcmp($a['cityName'] , $b['cityName']) > 0){
                return 1;
            }elseif(strcmp($a['cityName'] , $b['cityName']) < 0){
                return -1;
            }else{
                return 0;
            }
        });
        
        return $citiesData;
        //$this->_setDependencies();
        /*$locationData = $this->consultantPostingModel->getConsultantLocationCities();
        _p($locationData);die;
        return $this->consultantPostingModel->getConsultantLocationCities();*/
    }
    
    public function getConsultantLocationRegions() {
        $this->_setDependencies();
        $result = $this->consultantPostingModel->getConsultantLocationRegions();
        return $result;
    }
    /*
     * get localities of a city
     */
    public function getConsultantLocalitiesByCity($cityIds = array())
    {
	$this->_setDependencies();
	if(!is_array($cityIds)){
	    $cityIds = array($cityIds);
	}
	$result = $this->consultantPostingModel->getConsultantLocalitiesByCity($cityIds);
	$consultantLocationCitiesWithLocalities =array();
	foreach($result as $row)
	{
	    if(array_key_exists($row['cityId'],$consultantLocationCitiesWithLocalities))
	    {
		$consultantLocationCitiesWithLocalities[$row['cityId']]['localities'][$row['localityId']] = $row['localityName'];
	    }
	    else
	    {
		$consultantLocationCitiesWithLocalities[$row['cityId']] =
		    array(  /*'name'	=> $row['cityName'],*/
			    'localities'=> array($row['localityId'] => $row['localityName'])
			);
	    }
	}
        return $consultantLocationCitiesWithLocalities;
    }
    /*
     * check if branch location of a given consultant,city,locality combination exists
     */
    public function doesGivenConsultantLocationExists($data)
    {
	$this->_setDependencies();
	$result = $this->consultantPostingModel->getLocationByConsultant($data);
	if(count($result) >= 1)
	{
	    return array_map(function($a){ return $a['consultantLocationId']; },$result);
	}
	else{
	    return false;
	}
    }
    /*
     * check if branch location of a given consultant,city,locality combination exists
     */
    public function checkIfDefaultBranchAlreadyChosen($data)
    {
	$this->_setDependencies();
	$result = $this->consultantPostingModel->checkIfDefaultBranchAlreadyChosen($data);
	if(count($result) >= 1)
	{
	    return array_map(function($a){ return $a['consultantLocationId']; },$result);
	}
	else{
	    return false;
	}
    }
    /*
     * save consultantlocation data
     */
    public function saveConsultantLocationFormData($consultantLocationFormData) {
        $this->_setDependencies();
        return $this->consultantPostingModel->saveConsultantLocationFormData($consultantLocationFormData);
    }
    /*
     * function to get data for location table for a given consultant
     */
    public function getLocationsForConsultant($consultantId)
    {
	if(!is_array($consultantId))
	{
	    $consultantId = array($consultantId);
	}
	$this->_setDependencies();
        return $this->consultantPostingModel->getLocationsForConsultant($consultantId);
    }
    /*
     * function to set a location as head office
     */
    public function setLocationAsHeadOffice($data)
    {
	$this->_setDependencies();
        return $this->consultantPostingModel->setLocationAsHeadOffice($data);
    }
    /*
     * to get details of consultant location(s)
     */
    public function getConsultantLocationDetails($consultantLocationId)
    {
		if(!is_array($consultantLocationId))
		{
			$consultantLocationId = array($consultantLocationId);
		}
		$this->_setDependencies();
        return $this->consultantPostingModel->getConsultantLocationDetails($consultantLocationId);
    }
    
    public function getDefaultLocationForConsultant($consultantId,$regionId) {
        $this->_setDependencies();
        $result = $this->consultantPostingModel->getDefaultLocationForConsultant($consultantId,$regionId);
        $regionMapping = array();
        foreach ($result as &$resultData){
            if($resultData['regionId'] == 0){
                if(empty($regionMapping)){
                    $regionMapping = $this->getRegionsMappingData();
                }
                $resultData['regionId'] = $regionMapping[$resultData['cityId']]['regionId'];
            }
        }
        return $result;
    }
    
    public function getPRIRegions($priNumber){
        $this->_setDependencies();
        $result = $this->consultantPostingModel->getPRIRegions($priNumber);
        $regionMapping = array();
        foreach($result as &$resultData){
            if($resultData['regionId'] == 0){
                if(empty($regionMapping)){
                    $regionMapping = $this->getRegionsMappingData();
                }
                $resultData['regionId'] = $regionMapping[$resultData['cityId']]['regionId'];
            }
        }
        return $result;
    }
    
    public function getStudentProfileDataForConsultant($consultantId){
        $this->_setDependencies();
        
        $result  = $this->consultantPostingModel->getStudentProfileDataForConsultant($consultantId);
        $universityIds = array();
        
        foreach($result as $row)
        {
            $universityIds[] = $row['universityId'];
        }
        
        $finalResult = array();
        if(!empty($universityIds))
        {
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;
            $this->universityRepository = $listingBuilder->getUniversityRepository();
            $universitiesObj = $this->universityRepository->findMultiple($universityIds);
            foreach($result as $row)
            {
                if(!empty($universitiesObj[$row['universityId']]) && $universitiesObj[$row['universityId']]->getName() !='')
                {
                    $row['universityName'] = $universitiesObj[$row['universityId']]->getName();
                    $row['countryName'] = $universitiesObj[$row['universityId']]->getLocation()->getCountry()->getName();
                    $finalResult[] = $row;
                }
            }
        }
        return $finalResult;
    }
    
    public function getStudentProfileDetails($profileId){
        $this->_setDependencies();
        $result  = $this->consultantPostingModel->getStudentProfileDetails($profileId);
        
        if(count($result['universityMappingResult'])>0){
            
            $universityIds = array();
            foreach($result['universityMappingResult'] as $row){
                $universityIds[] = $row['universityId'];
            }
            
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;
            $this->universityRepository = $listingBuilder->getUniversityRepository();
            $universitiesObj = $this->universityRepository->findMultiple($universityIds);
            $finalUniversityMapping = array();
            foreach($result['universityMappingResult'] as $row)
            {
                $countryId = $universitiesObj[$row['universityId']]->getLocation()->getCountry()->getId();
                $row['countryId'] = $countryId;
                $finalUniversityMapping[] = $row;
            }
        }
        if(!empty($finalUniversityMapping)){
            $result['universityMappingResult'] = $finalUniversityMapping;
        }
        return $result;
    
    }
    
    public function deleteStudentProfile($userId,$studentProfileId,$universityId,$consultantId){
        $this->_setDependencies();
        $requiredData = array(
                              'studentProfileId' =>$studentProfileId,
                              'consultantId'     =>$consultantId,
                              'universityId'     =>$universityId
                              );
        $result  = $this->consultantPostingModel->deleteStudentProfile($userId,$requiredData);
        return $result;
    }
    
        public function deleteConsultant($consultantId,$userId) {
                $this->_setDependencies();
                $subscriptioData = $this->getCompleteConsultantDataForDeletion($consultantId);
                if(count($subscriptioData[$consultantId]['universities']) == 0){
                        $result = $this->consultantPostingModel->deleteConsultant($consultantId,$userId);
                        return $result;
                }else{
                        return 0;
                }
                
        }
	
	function getUniversityMappingDataForConsultant($consultantId){
		$this->_setDependencies();
		$res = $this->consultantPostingModel->getUniversityMappingDataForConsultant($consultantId);
		$result = array();
		foreach($res as $row){
			$data = array();
			$data['universityName'] = $row['UniversityName'];
			$data['validTill'] = empty($row['representativeValidTo'])?"-":$row['representativeValidTo'];
			if($row['isOfficialRepresentative'] == 'yes'){
				$data['proofType'] = ($row['proofType'] == 'name')?'Name on University Site':'Certificate of Affiliation';
			}else{
				$data['proofType'] = 'Not Available';
			}
			$data['id'] = $row['universityId'];
			$result[] = $data;
		}
		//_p($result);die;
		return $result;
	}
	
	public function getDataForConsultantUniversityMapping($consultantId,$universityId){
		$this->_setDependencies();
		$res = $this->consultantPostingModel->getDataForConsultantUniversityMapping($consultantId,$universityId);
		$res[0]['representativeValidFrom'] = $this->cleanupDate($res[0]['representativeValidFrom']);
		$res[0]['representativeValidTo'] = $this->cleanupDate($res[0]['representativeValidTo']);
		return $res;
	}
	
	public function cleanupDate($strDate){
		if(empty($strDate)){
			return '';
		}
		$fDate = '';
		$date = explode(' ',$strDate);
		$date = explode('-',$date[0]);
		$date = $date[2]."/".$date[1]."/".$date[0];
		if($date == "00/00/0000") $date = 'DD/MM/YYYY';
		return $date;
	}
	
	public function updateConsultantUniversityMappingFormData($dataArray,$mappingId){
		$this->_setDependencies();
		$this->consultantPostingModel->updateConsultantUniversityMappingFormData($dataArray,$mappingId);
	}
	
	public function getLastEditorForUniversityConsultantMapping($consultantId,$universityId){
		$this->_setDependencies();
		$res = $this->consultantPostingModel->getLastEditorForUniversityConsultantMapping($consultantId,$universityId);
		$result=array();
		$result['lastModifiedBy'] = $res[0]['modifiedBy'];
		$result['lastModifiedAt'] = $res[0]['modifiedAt'];
		return $result;
	}
	
	// API To get complete consultant Data for use when the consultant is being deleted
	public function getCompleteConsultantDataForDeletion($consultantId){
		$this->_setDependencies();
		$rawdata = $this->consultantPostingModel->getCompleteConsultantDataForDeletion($consultantId);
		$universities = array();
		$subscribedCities = array();
		$locations = array();
		$profiles = array();
		
		foreach($rawdata['subscriptionResult'] as $subscriptionRow){
			$universities[] = $subscriptionRow['universityId'];
			$subscribedCities[$subscriptionRow['regionId']] = $subscriptionRow['regionId'];
		}
		
		foreach($rawdata['profileCountResult'] as $profileRow){
			$profiles[$profileRow['universityId']] = $profileRow['pCount'];
		}
		
        $regionsData = $this->getRegionsMappingData();
        
		foreach($rawdata['locationResult'] as $locationRow){
			$subArray = array();
            $subArray['subscribed'] = in_array($regionsData[$locationRow['cityId']]['regionId'],$subscribedCities)?1:0;
			$subArray['cityId']     = $locationRow['cityId'];
			$subArray['localityId'] = $locationRow['localityId'];
			$subArray['defaultBranch'] = $locationRow['defaultBranch'];
			$subArray['headOffice'] = $locationRow['headOffice'];
			$locations[$locationRow['consultantLocationId']] = $subArray;
		}
		
		return array($consultantId=>array("universities"=>$universities, "locations"=>$locations, "profiles" => $profiles));
	}
	
	public function deleteConsultantUniversityMapping($userid,$consultantId,$universityId){
		$consultantData = $this->getCompleteConsultantDataForDeletion($consultantId);
		if(in_array($universityId,$consultantData[$consultantId]['universities'])){
			$ret = "This Consultant-University Mapping has an active subscription and cannot be deleted";
			return array('FAIL'=>$ret);
		}
                if(!empty($consultantData[$consultantId]['profiles'][$universityId]) && ($consultantData[$consultantId]['profiles'][$universityId] >0)){
			$ret = "This Consultant-University Mapping has active student profile and cannot be deleted";
			return array('FAIL'=>$ret);
		}
                
		$result = $this->consultantPostingModel->deleteConsultantUniversityMapping($userid,array("consultantId" => $consultantId,"universityId"=>$universityId),FALSE);
		if(!$result){
			$ret =  "An error occured while attempting to delete. Please try again later";
			return array('FAIL'=>$ret);
		}
		return true;
	}
	
	public function showErrorMessage(){
		show_404();
	}
        
    public function getLocationTableData($searchLocality,$paginatorObject) {
        $this->_setDependencies();
        $result = $this->consultantPostingModel->getLocationTableData($searchLocality,$paginatorObject);
        return $result;
    }
    
    public function checkLocalityExistence($localityArray,$cityArray) {
        if(empty($localityArray) || empty($cityArray)){
            return array();
        }
        $this->_setDependencies();
        $result =   $this->consultantPostingModel->getLocalityCityData($localityArray,$cityArray);
        $returnResult = array();
        foreach($result as $value){
            $key = array_search(strtolower($value['name']), array_map('strtolower', $localityArray));
            if($key !== FALSE && $cityArray[$key] == $value['cityId']){
                $returnResult[$key] = "TRUE";
            }
        }
        
        //_p($result);
        //exit(0);
        return $returnResult;
    }
    
    public function saveLocalityFormData($localityFormData) {
        $this->_setDependencies();
        return $this->consultantPostingModel->saveLocalityFormData($localityFormData);
    }
    
    public function getLocalityData($localityId) {
        $this->_setDependencies();
        $result = $this->consultantPostingModel->getLocalityData($localityId);
        return $result;
    }


   public function deleteLocation($userId,$requiredData)
    {
        $this->_setDependencies();
        $result = $this->consultantPostingModel->deleteConsultantLocations($userId,$requiredData,FALSE);
        
        if(!$result)
        {
            return "Something Went Wrong";     
        }
        return "Successfully Deleted!!";
    }
    
    public function deleteConsultantLocation($consultantLocationId, $consultantId ,$userId)
    {
	
        $subscriptionStatus =0;
        $headOffice         =0;
        $defaultOffice      =0;
        $numberOfLocations  =0;
        $cityId;
        $requiredData       = array();
 
        if(empty($consultantLocationId)|| empty($consultantId))
        {
            //handle this
            //echo "consultantLocationId  ".$consultantLocationId." consultantId  ".$consultantId;
            return 0;
        }
        
        $subscriptionData = $this->getCompleteConsultantDataForDeletion($consultantId);
        //_p($subscriptionData);
        $requiredData['consultantLocationId'] = $consultantLocationId;
                
        /*check city related attributes for this consultant*/
        
        foreach($subscriptionData[$consultantId]['locations'] as $key=>$value)
        {
            
            //check the consultantLocation details
            if($key == $consultantLocationId)
            {
                if($value['subscribed'] == 1)
                {
                    $subscriptionStatus = 1;    
                }
               
                
                if($value['headOffice'] == 'yes')
                {
                    $headOffice =1;
                }
               
                
                if($value['defaultBranch'] == 'yes')
                {
                    $defaultOffice =1;
                }
                
                if(!empty($value['cityId']))
                {
                    $cityId =$value['cityId'];
                }
            }
            
        }
        
        
        //count the number of locations in that including itself
        foreach($subscriptionData[$consultantId]['locations'] as $key=>$value)
        {
            if(!empty($value['cityId']) && $cityId ==$value['cityId'])
                {
                    $numberOfLocations++;
                }
            
        }
        //$numberOfLocations--;
        /*
        _p($subscriptionStatus ."::".
        $headOffice         ."::".
        $defaultOffice      ."::".
        $numberOfLocations  ."::".
        $cityId             ."::"
        );
        
        die;
        */
        //check the validity of deletion
        
         //if its a simple location directly delete it
        if($headOffice ==0 && $defaultOffice==0 )
        {
            $result=$this->deleteLocation($userId,$requiredData);
            return $result;
        }
        
        //if its a headoffice then transfer first and retry
        if($headOffice ==1 )
        {
            $result = "This is a head office for this consultant. Please set a new location as head office first to delete this location.";
            
            return $result;
        }
                       
        //if its a default office in the city 
        else if($defaultOffice==1)
        {
            //then tranfer first and retry
            if( $numberOfLocations >1)
            {
                $result = "This is a default office in the city for this consultant. Please set a new default location for this city then delete this location.";
                return $result;
            }
            //has active subscription then can't be deleted
            elseif(($numberOfLocations==1) && ($subscriptionStatus==1))
            {
                $result = "This is the only consultant office in the city with an active subscription hence it can't be deleted.";
                return $result;
            }
            //inactive then delete
            else if(($numberOfLocations==1) && ($subscriptionStatus==0))
            {
               $result= $this->deleteLocation($userId,$requiredData);
               return $result;
               
            }  
            
        }
        
        //if active subscription
        else if($subscriptionStatus==1 && $numberOfLocations==1)
        {
            //if its the only location in the city
            $result = "This is the only consultant office in the city with an active subscription hence it can't be deleted.";
            return $result;
                       
        }
        //else inactive subscription
        return 1;
        
    }
/*
	 * function to save consultant city subscription
	 */
	public function saveCitySubscriptionFormData($citySubscriptionData)
	{
	    $this->_setDependencies();
	    // format date
	    for ($i=0;$i<count($citySubscriptionData['startDate']);$i++)
	    {
			$dateObj = date_create_from_format('d/m/Y H:i:s',$citySubscriptionData['startDate'][$i]." 00:00:01");
			$citySubscriptionData['startDate'][$i] = date_format($dateObj,'Y-m-d H:i:s');
	    }
	    for ($i=0;$i<count($citySubscriptionData['endDate']);$i++)
	    {
			$dateObj = date_create_from_format('d/m/Y H:i:s',$citySubscriptionData['endDate'][$i]." 23:59:59");
			$citySubscriptionData['endDate'][$i] = date_format($dateObj,'Y-m-d H:i:s');
	    }
	    return $this->consultantPostingModel->saveCitySubscriptionFormData($citySubscriptionData);
	}
	/*
	 * get universities mapped to a consultant
	 */
	function getConsultantUniversities($consultantId){
	    $this->_setDependencies();
	    if(!is_array($consultantId))
	    {
		$consultantId = array($consultantId);
	    }
	    $resultUniversities = array();
	    $universityIds = $this->consultantPostingModel->getConsultantUniversities($consultantId);
	    if(!empty($universityIds)){
			$this->CI->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$this->universityRepository = $listingBuilder->getUniversityRepository();
			$universitiesObj = $this->universityRepository->findMultiple(array_map(function($a){return $a['universityId']; },$universityIds));
			foreach($universitiesObj as $universityObj)
			{
				$record = array();
				$record['universityId']         = $universityObj->getId();
				$record['universityName']       = $universityObj->getName();
				array_push($resultUniversities,$record);
			}
	    }
	    //echo json_encode($resultUniversities);
	    return $resultUniversities;
	}
	/*
	 * check if subscription already given on a combination (consultant,city,univ)
	 */
	public function checkIfSubscriptionAlreadyExists($data = array(),$dateArray,$subscriptionId)
	{
	    if(count($data) == 0){ return false; }
	    foreach($data as $fieldName=>$fieldVal){
			if(!is_array($fieldVal)){
				$data[$fieldName] = array($fieldVal);
			}
	    }
	    $this->_setDependencies();
	    $result = $this->consultantPostingModel->getActiveConsultantCitySubscriptions($data);
		
		$subscriptions = array();
	    foreach($result as $row){
			if($row['id'] == $subscriptionId){	// Don't clash with yourself!
				continue;
			}
			$req = array();
			$req['regionId'] = $row['regionId'];
			$req['startDate'] = strtotime($row['startDate']);
			$req['endDate'] = strtotime($row['endDate']);
			$subscriptions[] = $req;
		}
		
		$incoming = array();
		$cityArray = $data['regionId'];
		$startDates = $dateArray['startDate'];
		$endDates = $dateArray['endDate'];
		$count = count($cityArray);
		for($i = 0; $i < $count; $i++){
			$ar = array();
			$ar['regionId'] = $cityArray[$i];
			$ar['startDate'] = date_create_from_format('d/m/Y',$startDates[$i])->getTimestamp();
			$ar['endDate'] = date_create_from_format('d/m/Y',$endDates[$i])->getTimestamp();
			$incoming[$i] = $ar;
		}
		// Now to ensure that there is no clash between the old and the new
		$clashValues = array();
		foreach($incoming as $income){
			$clashFlag = 0;
			foreach($subscriptions as $subscription){
				if($income['regionId'] == $subscription['regionId']){
				    if(!(
					($income['startDate'] < $subscription['startDate'] && $income['endDate'] < $subscription['startDate'])||
					($income['startDate'] > $subscription['endDate'] && $income['endDate'] > $subscription['endDate'])
				      ))
				    {
					$clashFlag = 1;
				    }
				}
			}
			$clashValues[] = $clashFlag;
		}
	    return $clashValues;
	}
	/*
	 * check if same city-university combination available with different consultants()
	 */
	public function getCityUniversityCombinations($data = array(), $dateArray, $subscriptionId)
	{
	    if(count($data) == 0){
			return false;
	    }
	    foreach($data as $fieldName=>$fieldVal){
			if(!is_array($fieldVal)){
				$data[$fieldName] = array($fieldVal);
			}
	    }
	    $this->_setDependencies();
	    $result = $this->consultantPostingModel->getActiveConsultantCitySubscriptions($data);
		$subscriptions = array();
		foreach($result as $row){
			if($row['id'] == $subscriptionId){	// Don't clash with yourself!
				continue;
			}
			$req = array();
			$req['regionId'] = $row['regionId'];
			$req['startDate'] = strtotime($row['startDate']);
			$req['endDate'] = strtotime($row['endDate']);
			$subscriptions[] = $req;
		}
		$incoming = array();
		$cityArray = $data['regionId'];
		$startDates = $dateArray['startDate'];
		$endDates = $dateArray['endDate'];
		$count = count($cityArray);
		$clashCounter = array();
		$clashValues = array();
		for($i = 0; $i < $count; $i++){
			$ar = array();
			$ar['regionId'] = $cityArray[$i];
			$ar['startDate'] = date_create_from_format('d/m/Y',$startDates[$i])->getTimestamp();
			$ar['endDate'] = date_create_from_format('d/m/Y',$endDates[$i])->getTimestamp();
			$incoming[$i] = $ar;
			$clashCounter[$i] = 0;
			$clashValues[$i] = 0;
		}
		// Now to ensure that there is no clash between the old and the new
		$count = count($incoming);
		for($i = 0; $i < $count; $i++){
			$income = $incoming[$i];
			foreach($subscriptions as $subscription){
				$clashFlag = 0;
				if($income['regionId'] == $subscription['regionId']){
					if($income['startDate'] > $subscription['startDate'] && $income['startDate'] < $subscription['endDate']){
						$clashFlag = 1;
					}
					if($income['endDate'] > $subscription['startDate'] && $income['endDate'] < $subscription['endDate']){
						$clashFlag = 1;
					}
					if($clashFlag == 1){
						$clashCounter[$i]++;
					}
				}
			}
			if($clashCounter[$i] >=3){
				$clashValues[$i] = 1;
			}
			
		}
	    return $clashValues;
	}
    
    public function getStudentProfileStatus($profileId){
        $result = $this->consultantPostingModel->getStudentProfileStatus($profileId);
        return $result;
    }
    
    public function getAllUniversitiesForStudentProfile($profileId){
        $result = $this->consultantPostingModel->getAllUniversitiesForStudentProfile($profileId);
        $finalUniversites = array();
        foreach($result as $row){
            $finalUniversites[] = $row['universityId'];
        }
        return $finalUniversites;
    }
    /*get assigned cities*/
    public function getAssignedCitiesData($searchType , $searchContentName, $paginatorObj)
    {
        $this->_setDependencies();
       // _p("search:".$searchType."    content".$searchContentName);
        
        //if no content for search then normal execution on consultants
        if($searchContentName == "")
        {
            return $this->consultantPostingModel->getAllCitiesData($paginatorObj );
        }
        
        //if content for search is there
        else
        {
            return $this->consultantPostingModel->getAssignedCitiesData($searchType ,$searchContentName, $paginatorObj );
        }
        
    }
	
	public function getConsultantUniversityCitySubscriptionDataForConsultant($consultantId){
		$this->_setDependencies();
		$result = $this->consultantPostingModel->getConsultantUniversityCitySubscriptionDataForConsultant($consultantId);
		return $result;
	}
    
	public function getConsultantSubscriptionData($subscriptionId){
		$this->_setDependencies();
		$result = $this->consultantPostingModel->getConsultantSubscriptionData($subscriptionId);
		return $result;
		
	}
	
	public function getBasicIdsForSubscription($subscriptionId){
		$this->_setDependencies();
		return $this->consultantPostingModel->getBasicIdsForSubscription($subscriptionId);
	}
	
	public function checkIfConsultantAllowsUniversityDeletion($universityId){
		$this->_setDependencies();
		$data['universityId'] = $universityId;
		$res = $this->consultantPostingModel->getActiveConsultantCitySubscriptions($data);
		if(count($res) == 0){
			return true;
		}
		return false;
	}

	public function handleUniversityDeletion($universityIds){
		$this->_setDependencies();
		$userData = $this->CI->checkUserValidation();
		$userId = $userData[0]['userid'];
		$flag = true;
		$universityId = reset($universityIds);
		$status = $this->consultantPostingModel->deleteConsultantUniversityMappingsForUniversity($universityId);
		if($status == 0){	// 0 mappings were updated, no need to go forward.
			return 1;
		}
		$data = $this->consultantPostingModel->deleteStudentProfileToUniversityMappingByUniversity($universityId);
		foreach($data as $row){
			if($row['profileCount']==1){	// 1 profile mapping was deleted after query was run.
				$flag = $flag && $this->deleteStudentProfile($userId,$row['profileId'],$universityId,0);	// We don't have/need consultantId
			}
		}
		if($flag){
			return 1;
		}
		return "Error Occured while deleting profiles";
	}
	
    public function getExcludedCoursesForUniversity($consultantId,$univId){
	$this->_setDependencies();
	$totalCourses = $this->consultantPostingModel->getCourseNamesForUniversity($univId);
	$disabledCourses = $this->consultantPostingModel->getExcludedCoursesForUniversity($consultantId,$univId);
	$result = array();
	foreach($totalCourses as $courseId=>$course){
	    if(in_array($courseId,$disabledCourses)){
		$result[] = array("courseId"=>$courseId,"courseName"=>htmlentities($course),"disabled"=>"true");
	    }else{
		$result[] = array("courseId"=>$courseId,"courseName"=>htmlentities($course),"disabled"=>"false");
	    }
	}
	return $result;
    }
        
        public function validateConsultantforClientMapping($data){
            $this->_setDependencies();
	    
            $result= $this->consultantPostingModel->validateConsultantforClientMapping($data);
            return $result;
        }
        
    public function fetchClientDetailAndSubscriptions($data){
            
            $finalData = array();
            $UserModel = $this->CI->load->model('user/usermodel');
            $userObj = $UserModel->getUserById($data['clientId']);
            if(isset($userObj) && $userObj !='')
            {
                $data['userName'] = $userObj->getDisplayName();
                $data['userGroup'] = $userObj->getUserGroup();
                
                if(strtolower($data['userGroup']) =='enterprise')
                {
                    $this->CI->load->library('sums_product_client');
                    $objSumsProduct =  new Sums_Product_client();
                    $data['SubscriptionArray'] = $objSumsProduct->getAllSubscriptionsForUserLDB(CONSULTANT_CLIENT_APP_ID,array('userId'=>$data['clientId']));
                    $finalData['error'] = false;
                    $finalData['errorMsg'] = "";
                    $finalData['data'] = $data;
                }else{
                    $finalData['error'] = true;
                    $finalData['errorMsg'] = "Please enter valid enterprise Client ID";
                }   
            }else{
                $finalData['error'] = true;
                $finalData['errorMsg'] = "Please enter valid Client ID";
            }
            return $finalData;
        }    
    
    public function saveClientConsultantSubscriptionData($data,$mappingId=0)
    {    
        $this->_setDependencies();
        $validatecheckData['consultantId']   = $data['consultantId'];
        $validatecheckData['mappingId'   ]   = $mappingId;
        $result= $this->consultantPostingModel->validateConsultantforClientMapping($validatecheckData);
        if($result['error']==false){
            $result = $this->consultantPostingModel->saveClientConsultantSubscriptionData($data,$mappingId);
        }
        return $result;
        
    }
        
        public function getClientConsultantSubscription($paginatorObject ,$searchConsId)
        {
            $this->_setDependencies();
            $data = $this->consultantPostingModel->getClientConsultantSubscription($paginatorObject,$searchConsId);
            $subscriptionIds = $data ['subscriptionIds'];
            if(!empty($subscriptionIds))
	    {
                
		$subscriptionData = $this->fetchClientSubscription_SUMS($subscriptionIds);

		foreach($subscriptionData as $value)
		{
		    $data['subscriptionData'][(int)$value['SubscriptionId']]['SubscriptionId'] 		        = $value['SubscriptionId'];
		    $data['subscriptionData'][(int)$value['SubscriptionId']]['TotalBaseProdQuantity'] 	        = $value['TotalBaseProdQuantity'];
		    $data['subscriptionData'][(int)$value['SubscriptionId']]['BaseProdRemainingQuantity'] 	= $value['BaseProdRemainingQuantity'];
		    $data['subscriptionData'][(int)$value['SubscriptionId']]['SubscriptionEndDate'] 		= $value['SubscriptionEndDate'];
		    $data['subscriptionData'][(int)$value['SubscriptionId']]['Status'] 			        = $value['subscriptionStatus'];
		}
	    
	    }        
            return $data;
        }

    
    public function getClientConsultantSubscriptionData($mappingId){
        $this->_setDependencies();
        if($mappingId !=''){
            $result = $this->consultantPostingModel->getClientConsultantSubscriptionData($mappingId);
            $result = reset($result);
            if(count($result)>0)
                {
                    $UserModel = $this->CI->load->model('user/usermodel');
                    $userObj = $UserModel->getUserById($result['modifiedBy']);
                    if(isset($userObj) && $userObj !='')
                    {
                        $result['modifiedByName'] = $userObj->getDisplayName();
                    }    
                    $result['subscriptionData'] = $this->fetchClientDetailAndSubscriptions(array('clientId'=>$result['clientId']));
                }
            }
        return $result;
    }
	/*
	 * this function returns sums subsciption id for a given consultantClientSUbscription id (mapping id)
	 */
	public function getConsultantSubscriptionByClientSubscription($mappingId)
	{
        $this->_setDependencies();
        if($mappingId !=''){
            $result = $this->consultantPostingModel->getClientConsultantSubscriptionData($mappingId, true);
            $result = reset($result);
		}
        return $result;
    }
	/*
	 * function to get consultant region assignment after a given date.
	 */
	public function getConsultantRegionAssignmentAfterDate($afterDate,$consultantId)
	{
        $this->_setDependencies();
        if($afterDate !='' && $consultantId > 0){
            $result = $this->consultantPostingModel->getConsultantRegionAssignmentAfterDate($afterDate,$consultantId);
		}
        return $result;
    }	
	public function getRegionsMappingData(){
        $this->_setDependencies();
        $result =   $this->consultantPostingModel->getRegionsMapping();
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        $citiesArray = $locationRepository->getCities(2,TRUE);
        
        $finalArray =  array();
        foreach($citiesArray as $cityObject){
            foreach($result as $data){
                if($cityObject->getId() == $data['locationId'] && $data['locationType'] == 'city'){
                    $finalArray[$cityObject->getId()] = array(  'regionName'    => $data['regionName'],
                                                                'regionId'      => $data['regionId']
                                                            );
                    break;
                }elseif($cityObject->getStateId() == $data['locationId'] && $data['locationType'] == 'state'){
                    $finalArray[$cityObject->getId()] = array(  'regionName'    => $data['regionName'],
                                                                'regionId'      => $data['regionId']
                                                            );
                }
            }
        }
        return $finalArray;
    }

        public function fetchClientSubscription_SUMS($subscriptionIds)
        {
            //_p($subscriptionIds);
               $this->CI->load->library('subscription_client');
               $objSumsProduct              =  new Subscription_client();
               $data                        = $objSumsProduct->getMultipleSubscriptionDetails(CONSULTANT_CLIENT_APP_ID,$subscriptionIds,true);
               return $data;
        }
}
