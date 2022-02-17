<?php 

class SASearchPageUrlGenerator{

	private $CI;

	public function __construct()
	{
		$this->CI = & get_instance();
	}

	public function createClosedSearchUrl($postData){
		//_p($postData);
		$this->CI->load->library("SASearch/SASearchPageRequest");
		$request = new SASearchPageRequest();
		
		$keyword        = $postData['keyword'];
		$city      		= $postData['city'];
		$state      	= $postData['state'];
		$country     	= $postData['country'];
		$continent     	= $postData['continent'];
		$universities   = $postData['universities'];
		$institute   	= $postData['institute'];
		$exams          = $postData['exams'];
		$examScore      = $postData['examScore'];
		$courseFee      = $postData['courseFee'];
		$categoryIds    = array_map(function($a){return $a['id'];},$postData['categoryIds']);
		$subCategoryIds = array_map(function($a){return $a['id'];},$postData['subCategoryIds']);
		$level    		= $postData['level'];
		$desiredCourse  = $postData['desiredCourse'];
		$specialization = $postData['specialization'];
		$specializationIds = $postData['specializationIds'];
		$trackingId = $postData['trackingId'];

		if($postData['textSearchFlag']==true && trim($postData['remainingKeyword'])!='')
		{
           $data['textSearchFlag']     = $postData['textSearchFlag'];
           $data['remainingKeyword']   = $postData['remainingKeyword']; 
        }
		
		if(empty($categoryIds) && !empty($subCategoryIds)){
			//get Category Id
			$this->CI->load->builder('CategoryBuilder','categoryList');
	        $categoryBuilder = new CategoryBuilder;
	        $categoryRepository = $categoryBuilder->getCategoryRepository();
			foreach($subCategoryIds as $key=>$subCategoryId){
				$subCatObj = $categoryRepository->find($subCategoryId);
				$categoryIds[] = $subCatObj->getParentId();
			}
			
		}
		$data['categoryIds']   = $categoryIds;

		$data['searchKeyword'] = $keyword;
		
		$data['subCategoryIds'] = $subCategoryIds;

		//$noLimitText = "No limit";
		if(is_array($desiredCourse) && !empty($desiredCourse)){
			foreach ($desiredCourse as $key => $value) {
					$data['desiredCourse'][] = $value['id'];
			}	
		}
		
		if(is_array($specialization) && !empty($specialization)){
			foreach ($specialization as $key => $value) {
					$postData['specializationIds'][] = array('id'=>$value['id']);
					$specializationIds[] = array('id'=>$value['id']);
			}	
		}
		
		
		// when searched using autosuggestor these specializationIds are available directly:
		if(is_array($specializationIds) && !empty($specializationIds)){
			foreach ($specializationIds as $key => $value) {
					$data['specializationIds'][] = (int)$value['id'];
			}
			$data['specializationIds'] = array_unique($data['specializationIds']);	
		}
		
		$subCategoryIds = array_map(function($a){return $a['id'];},$postData['subCategoryIds']);
		
		//exams
		//hard coding search suffix in case of search
		//$suffix = "_|";
		if(is_array($exams) && !empty($exams)){
			foreach($exams as $examName){
					$data['exams'][]=  strtoupper($examName['name']);
			}
		}
		if(is_array($examScore) && !empty($examScore)){
			foreach($examScore as $examScoreVal){
					$data['examScore'][]=$examScoreVal;
			}
		}
		if(is_array($courseFee) && !empty($courseFee)){
			foreach($courseFee as $courseFeeVal){
					$data['courseFee'][]=$courseFeeVal;
			}
		}

		// courselevel
		if(is_array($level) && !empty($level)){
                 	foreach ($level as $key => $value) {
					$data['level'][] = ucwords($value['name']);	
			}
		}

		//Location (Continent Country State City)
		if(is_array($continent) && !empty($continent)){
			foreach ($continent as $key => $value) {
					$data['continent'][] = $value['id'];	
			}
		}
		
		if(is_array($country) && !empty($country)){
			foreach ($country as $key => $value) {
					$data['country'][] = $value['id'];	
			}
		}
		
		if(is_array($state) && !empty($state)){
			foreach ($state as $key => $value) {
					$data['state'][] = $value['id'];	
			}
		}
		
		if(is_array($city) && !empty($city)){
			foreach ($city as $key => $value) {
					$data['city'][] = $value['id'];	
			}
		}
		
		//Universities
		if(is_array($universities) && !empty($universities)){
			foreach ($universities as $key => $value) {
					$data['universities'][] = $value['id'];	
			}
		}
		
		//institute
		if(is_array($institute) && !empty($institute)){
			foreach ($institute as $key => $value) {
					$data['institute'][] = $value['id'];	
			}
		}
		//$data['requestFrom'] = $postData['requestFrom'];
		if(!empty($postData)){
			$data['qerResults'] = 'yes';
		}
		if(!empty($trackingId)){
			$data['trackingId'] = $trackingId;
		}
		
		//_p($data);die;
		$request->setData($data);
		$closeSearchUrl = $request->getUrl();
		if(empty($closeSearchUrl))
			$closeSearchUrl = 'No Url Found';

		return $closeSearchUrl;
	}
}