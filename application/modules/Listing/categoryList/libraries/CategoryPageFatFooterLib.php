<?php
  /**
    * function : getFatFooterData
    * param: $db (array)
    * desc : prepare data for fat Footer only
    * type : return final url and urltitle
    * added by akhter
    **/ 
class CategoryPageFatFooterLib
{
	private $isNONRNR;
	private $subCategoryName;
	private $categoryName;
	private $LDBCourseName;
	public function __construct() {
 		$this->CI 			   = & get_instance();
 		$this->subCategoryName = array();
 		$this->categoryName    = array();
 		$this->LDBCourseName   = array();
 		$this->isNONRNR        = 1;
	}

	function getFatFooterData($db){
		$this->categorypagemodel 		= $this->CI->load->model("categorypagemodel");
		$db['subCategoryId']            = ($db['entityType'] === 'category') ? $this->getSubCatList($db['categoryId']) : $db['subCategoryId'];
        $db['isNONRNR']                 = $this->isNONRNR;
        $db['limit']                    = 16;

		if($db['cityId'] >1 && $db['cityId'] !='' && $db['entityType'] === 'category'){ // 8 L2 city specific and  top 8 L1 state
			$result = $this->categorypagemodel->getNonZeroCategoryCityState($db);
			$result['entityType'] = $db['entityType'];
		}else if($db['entityType'] == 'subcat'){
			$result = $this->categorypagemodel->getNonZeroCategoryCityState($db);
		}else{
			$result = $this->categorypagemodel->getNonZeroCategoryFatFooter($db); // india and state
		}

		if($db['entityType'] == 'category' && $db['ldbCourseId'] == 1){
			$this->setCategoryName($db['categoryId']);
		}

		if($db['entityType'] != 'category' && $db['ldbCourseId'] == 1){
			$this->setSubCatName($db['subCategoryId']);
		}
		
		if($db['ldbCourseId'] > 1){
			$this->setLdbCourseName($db['ldbCourseId']);	
		}

		foreach ($result['city'] as $value){
			$finalList['city'][] = $this->getFatFooterUrl($value);
		}

		foreach ($result['state'] as $value){
			$finalList['state'][] = $this->getFatFooterUrl($value, $result['entityType']);
		}
		return $finalList;
	}

	private function getSubCatList($categoryid){
		if($categoryid>0 && is_numeric($categoryid)){
			$this->CI->load->builder('CategoryBuilder','categoryList');
			$categoryBuilder = new CategoryBuilder;
			$categoryRepository = $categoryBuilder->getCategoryRepository();
			$subCatObj = $categoryRepository->getSubCategories($categoryid);
			if(is_array($subCatObj)){
				foreach ($subCatObj as $obj) {
					$subCatArr[] = $obj->getId();
					$this->subCategoryName[$obj->getId()] = $obj->getShortName();
				}
			}	
			return implode(',',$subCatArr);
		}
	}

	private function getFatFooterUrl($keys, $entityType='')
	{
		$this->CI->load->library ('categoryList/CategoryPageRelatedLib');
		$categoryPageRelatedLib = new CategoryPageRelatedLib;
		$keys['urlTitle'] = $this->getUrlTitle($entityType,$keys);
		$keys['isNONRNR'] = $this->isNONRNR;
		return $categoryPageRelatedLib->getCategoryPageUrl($keys);
	}

	private function setSubCatName($subCatId){
		if($subCatId>0 && is_numeric($subCatId)){
			$this->CI->load->builder('CategoryBuilder','categoryList');
			$categoryBuilder = new CategoryBuilder;
			$categoryRepository = $categoryBuilder->getCategoryRepository();
			$subCatObj = $categoryRepository->find($subCatId);
			if($subCatObj){
				$this->subCategoryName[$subCatId] = $subCatObj->getShortName();
			}
		}
		return $this->subCategoryName;
	}

	private function setCategoryName($catId){
		if($catId>0 && is_numeric($catId)){
			$this->CI->load->builder('CategoryBuilder','categoryList');
			$categoryBuilder = new CategoryBuilder;
			$categoryRepository = $categoryBuilder->getCategoryRepository();
			$catObj = $categoryRepository->find($catId);
			$this->categoryName[$catId] = $catObj->getShortName();
		}
		return $this->categoryName;
	}

	function setLdbCourseName($desiredCourseId){
		if(is_numeric($desiredCourseId) && $desiredCourseId>1)
		{
			$this->CI->load->builder('LDBCourseBuilder','LDB');
	        $LDBCourseBuilder = new LDBCourseBuilder;
	        $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
	        $LDBCourse = $LDBCourseRepository->find($desiredCourseId);
	        if($LDBCourse){
	        	$this->LDBCourseName[$desiredCourseId] = $LDBCourse->getDisplayName();
	        }
    	}
        return $this->LDBCourseName;
	}

	private function getUrlTitle($entityType,$keys){
		if($keys['ldbCourseId'] == 1){
			$urlTitle = ($entityType == 'category') ? $this->categoryName[$keys['category_id']] : $this->subCategoryName[$keys['sub_category_id']];
		}else{
			$urlTitle = $this->LDBCourseName[$keys['ldbCourseId']];
		}
		return $urlTitle;
	}
}
	