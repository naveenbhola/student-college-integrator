<?php

class Personalizer{
	
	/* Personalization Config */
	private $enabled = false;
	private $categories = array(23);
	private $categoriesShortNames = array(23=>'MBA');
	private $countries = array(2);
	private $regions = array(0);
	private $abProbabilty = 50;
	
	
	/* Personalized Data */
	private $personalizedData = false;
	
	
	/* Set or Trigger personalization */
	public function triggerPersonalization($subCategoryId, $type, $countryId = 2, $regionId = 0){
		
		if($subCategoryId == 47 || $subCategoryId == 163){//Test Prep & Study Abroad case handling
			$subCategoryId = 23;
			$countryId = 2;
			$regionId = 0;
		}
		$referalParse = parse_url($_SERVER['HTTP_REFERER']);
		
		if($_COOKIE['personalizedAB'] == NULL){
			if($this->abProbabilty < rand(1,100)){
				setcookie('personalizedAB',"0",time() + 2592000,'/',COOKIEDOMAIN);
				$_COOKIE['personalizedAB'] = "0";
			}else{
				setcookie('personalizedAB',"1",time() + 2592000,'/',COOKIEDOMAIN);
				$_COOKIE['personalizedAB'] = "1";
			}
		}
		
		if(!$this->enabled){ 
			
			$this->personalizedData = false;
			setcookie('personalizedData','',0,'/',COOKIEDOMAIN);
			$_COOKIE['personalizedData'] = false;
			
		}elseif($_SERVER['HTTP_REFERER'] && strpos($referalParse['host'],COOKIEDOMAIN) === false){
			
			return false;
		
		}elseif(!in_array($subCategoryId,$this->categories) || !in_array($countryId,$this->countries) || !in_array($regionId,$this->regions) || !$type){ // Check for enabled category
			
			$this->personalizedData = false;
			setcookie('personalizedData','',0,'/',COOKIEDOMAIN);
			$_COOKIE['personalizedData'] = false;
			
		}elseif($_COOKIE['personalizedAB'] == "1" || $_COOKIE['personalizationOff'] == "1"){
			$this->personalizedData['isPersonalized'] = 0;
		
		}else{
			$this->personalizedData['isPersonalized'] = 1;
		}
		
		if($this->personalizedData){
			$this->personalizedData['categoryId'] = $subCategoryId;
			$this->personalizedData['countryId'] = $countryId;
			$this->personalizedData['regionId'] = $regionId;
			$this->personalizedData['type'] = $type;
			$this->_setcookieData();
		}
		
		return $this->personalizedData;
	}
	
	/* Personalization Checking Function */
	public function isPersonalized(){
		if(!$this->enabled || $_COOKIE['personalizationOff'] == "1"){
			return false;
		}
		if($this->personalizedData){
			return $this->processPersonalizedData();
		}
		if(isset($_COOKIE['personalizedData']) && $_COOKIE['personalizedData'] != ''){
			$this->personalizedData = json_decode($_COOKIE['personalizedData'],true);
			return $this->processPersonalizedData();
		}else{
			return false;
		}
	}
	
	
	private function _setcookieData(){
		$time = 0;
		if(isset($_COOKIE['user']) && $_COOKIE['user'] != ''){
			$time = time() + 2592000;
		}
		$encodedData  = json_encode($this->personalizedData);
		setcookie('personalizedData',$encodedData,$time,'/',COOKIEDOMAIN);
		$_COOKIE['personalizedData'] = $encodedData;
	}
	
	/* Get Additional Personalization Data */
	private function processPersonalizedData(){
		
		$CI = & get_instance();
		$CI->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$this->categoryRepository = $categoryBuilder->getCategoryRepository();
		if(!isset($this->personalizedData['isPersonalized'])){
			$this->personalizedData['isPersonalized'] = 1;
		}
		$this->personalizedData['category'] = $this->categoryRepository->find($this->personalizedData['categoryId']);
		$this->personalizedData['shortName'] = $this->categoriesShortNames[$this->personalizedData['categoryId']]?$this->categoriesShortNames[$this->personalizedData['categoryId']]:$this->personalizedData['category']->getName();
		$this->personalizedData['crossCat'] = $this->categoryRepository->getCrossPromotionMappedCategory($this->personalizedData['categoryId']);
		if(in_array($this->personalizedData['category']->getId(),$this->categories)){
			return $this->personalizedData;
		}else{
			return false;
		}
		
	}
	
	/* Reset Personalizations to default */
	public function resetPersonalization(){
		setcookie('personalizationOff',"1",time() + 2592000,'/',COOKIEDOMAIN);
		$_COOKIE['personalizationOff'] = "1";
	}
	
}