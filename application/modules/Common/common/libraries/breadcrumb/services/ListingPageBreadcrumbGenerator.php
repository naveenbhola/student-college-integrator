<?php
class ListingPageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	private $currentPageName= "Listing";
	
	function __construct($options) {
		
		
		$this->displayData = &$options['displayData'];
		$this->institute = &$options['institute'];
		$this->course = &$options['course'];
		$this->pageType = $options['pageType'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->CI->load->library('categoryList/CategoryPageRequest');
		
		$this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder    = new CategoryBuilder;
        $this->categoryRepository  = $categoryBuilder->getCategoryRepository();
        //$this->CategoryObject = $this->categoryRepository->find($this->subCategoryId);
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');

		$this->CI->load->library('coursepages/coursePagesUrlRequest');
		$this->national_course_lib = $this->CI->load->library('listing/NationalCourseLib');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();

		$currentLocation = $this->displayData['currentLocation'];
		$countryId = $currentLocation->getCountry()->getId();
		$cityId = ($countryId==2)?$currentLocation->getCity()->getId():0;

		$requestURL = new CategoryPageRequest();

		if($this->pageType == 'institute'){
			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
			if($countryId != 2){
			    $requestURL->setData(array('countryId' => $countryId));
			    $this->Breadcrumbs->addItem(htmlspecialchars($this->institute->getMainLocation()->getCountry()->getName()), $requestURL->getURL());
			}

			//check for institute to college story
			$this->displayData['collegeOrInstituteRNR'] = 'institute';
		    
		    $categoryIds = $this->national_course_lib->getCourseInstituteCategoryId($this->institute->getId(),'institute');
			if(count(array_intersect($categoryIds, array("2", "3"))) != 0) { 
				$this->displayData['collegeOrInstituteRNR'] = 'college';
			}

			//check for institute to college story ends
			$this->Breadcrumbs->addItem(htmlspecialchars($this->institute->getName()),'');		
		}
		else{
			/*
			 *	COURSE BREADCRUMBS..
			 */

			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
			/*
			 * Lets get the dominant Subcat and then its Parent Category Object of the Course..
			 */
	        // change for memory optimization : passing course object instead of course id in Dominatnt function  
			$subCategory = $this->national_course_lib->getDominantSubCategoryForCourse($this->course,$this->displayData['categorylistByCourse'][$this->course->getId()]);
			$categoryObj = $this->categoryRepository->find($subCategory['dominant']);
			$parentCatObj = $this->categoryRepository->find($categoryObj->getParentId());	

			$this->displayData['collegeOrInstituteRNR'] = "institute";
			if($categoryObj->getParentId() == 2 || $categoryObj->getParentId() == 3){
			   $requestURL->setNewURLFlag(1);
			   $this->displayData['collegeOrInstituteRNR'] = "college";
			}
			
			if($countryId != 2){		    
			    $this->Breadcrumbs->addItem($currentLocation->getCountry()->getName(), $requestURL->getURL());
			}
			//display different breadcrumb structure for management course listings
			if($categoryObj->getParentId() == 3){
				$this->Breadcrumbs->addItem(htmlspecialchars($this->institute->getName()), $this->institute->getURL());
				$this->Breadcrumbs->addItem(htmlspecialchars($this->course->getName()), '');
				$this->displayData['pageSubcategoryId'] = $categoryObj->getId();
			}
			else{
				/*
				 * Lets see if Course Pages exists for this Subcategory..
				 */
				global $COURSE_PAGES_SUB_CAT_ARRAY;
				if(array_key_exists($categoryObj->getId(), $COURSE_PAGES_SUB_CAT_ARRAY) && $parentCatObj->getId() != 14) {
				    $coursePagesUrlRequest = new CoursePagesUrlRequest();
				    $this->Breadcrumbs->addItem(htmlspecialchars($COURSE_PAGES_SUB_CAT_ARRAY[$categoryObj->getId()]["Name"]), $coursePagesUrlRequest->getHomeTabUrl($categoryObj->getId()));
				}
				else{
				    if($countryId == 2 && $parentCatObj->getId() != 14){
				    	$requestURL->setData(array('countryId' => $countryId,'categoryId' => $parentCatObj->getId()));
				    	$this->Breadcrumbs->addItem(htmlspecialchars($parentCatObj->getShortName()), $requestURL->getURL());
				    }
				}
				
				if($countryId == 2){
				    $requestURL->setData(array('categoryId' => $parentCatObj->getId(),'subCategoryId' => $categoryObj->getId(),'countryId' => $countryId));
				    $this->Breadcrumbs->addItem(htmlspecialchars($categoryObj->getShortName())." ".ucfirst($this->displayData['collegeOrInstituteRNR'])."s", $requestURL->getURL());
				    

				    $requestURL->setData(array('categoryId' => $parentCatObj->getId(),'subCategoryId' => $categoryObj->getId(),'countryId' => $countryId,'cityId' => $cityId));
				    $this->Breadcrumbs->addItem(htmlspecialchars($categoryObj->getShortName())." in ".$currentLocation->getCity()->getName(), $requestURL->getURL());
				    /*
				     * If it is Test Prep Course then show the Locality in the Breadcrumb as well..
				     */
				    if($parentCatObj->getId() == 14 && $currentLocation->getLocality()->getName() != ""){
				    	$requestURL->setData(array('categoryId' => $parentCatObj->getId(),'subCategoryId' => $categoryObj->getId(),'countryId' => $countryId,'cityId' => $cityId,'localityId' => $currentLocation->getLocality()->getId()));
				    	$this->Breadcrumbs->addItem($currentLocation->getLocality()->getName(), $requestURL->getURL());
				    }
				}
				else
				{
				    $requestURL->setData(array('categoryId' => $parentCatObj->getId(),'countryId' => $countryId,));
				    $this->Breadcrumbs->addItem(htmlspecialchars($parentCatObj->getShortName())." ".ucfirst($this->displayData['collegeOrInstituteRNR'])."s", $requestURL->getURL());
				    
				    $requestURL->setData(array('categoryId' => $parentCatObj->getId(),'subCategoryId' => $categoryObj->getId(),'countryId' => $countryId));
				    $this->Breadcrumbs->addItem(htmlspecialchars($categoryObj->getShortName()), $requestURL->getURL());
				}
				
				$this->Breadcrumbs->addItem(htmlspecialchars($this->course->getName()), '');
				$this->displayData['pageSubcategoryId'] = $categoryObj->getId();
			}
		}
		return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
		
	}
}
