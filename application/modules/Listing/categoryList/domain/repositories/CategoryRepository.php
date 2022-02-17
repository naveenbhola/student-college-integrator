<?php

class CategoryRepository extends EntityRepository
{
    function __construct($dao,$cache)
    {
        parent::__construct($dao,$cache);
    
        /*
         * Load entities required
         */ 
        $this->CI->load->entities(array('Category'),'categoryList');
    }
    
    public function find($categoryId)
    {
        Contract::mustBeNumericValueGreaterThanZero($categoryId,'Category ID');
        
        if($this->caching && $category = $this->cache->getCategory($categoryId)) {
            return $category;
        }
        
        $categoryDataResults = $this->dao->getCategory($categoryId);
        $category = $this->_loadOne($categoryDataResults);
        $this->cache->storeCategory($category);
        return $category;
    }
    
    public function findMultiple($categoryIds)
    {
		
		Contract::mustBeNonEmptyArrayOfIntegerValues($categoryIds,'Category IDs');

		$orderOfcategoryIds = $categoryIds;
		$categoriesFromCache = array();

		if($this->caching) {
			
			$categoriesFromCache = $this->cache->getMultipleCategories($categoryIds);
			$foundInCache = array_keys($categoriesFromCache);
			$categoryIds = array_diff($categoryIds,$foundInCache);
			if(count($categoryIds) == 0)
			{
				return $categoriesFromCache;
			}
		}
		
		if(count($categoryIds) > 0) {
			
			$categoriesFromDB = $this->dao->getMultipleCategories($categoryIds);
			
			$temp =array();
			foreach($categoriesFromDB as $categoryData) {
				$category = $this->_loadOne($categoryData);
				$temp[$category->getId()] = $category;
				$this->cache->storeCategory($category);
			}
			$categories = array();
			
			/*
			 *Commented array_merge() function below to preserve original array index value
			 */
			//$categories = array_merge($categoriesFromCache,$temp);
			$categories = $categoriesFromCache+$temp;
			return $categories;	
		}
	}
	
    public function getSubCategories($categoryId,$flag = '')
    {
        Contract::mustBeNumericValueGreaterThanZero($categoryId,'Category ID');
        
        if($this->caching && $subCategories = $this->cache->getSubCategories($categoryId."-".$flag)) {
            return $subCategories;
        }
        
        $categoryDataResults = $this->dao->getSubCategories($categoryId,$flag);
        $subCategories = $this->_load($categoryDataResults);
        $this->cache->storeSubCategories($categoryId."-".$flag,$subCategories);
        return $subCategories;
    }

    public function getSubCategoriesByCategories($categoryIds,$flag = '')
    {
        
        $orderOfCategoryIds = $categoryIds;
        $subCateFromCache = array();

        if($this->caching) {
            $cacheKeys = array();
            foreach ($categoryIds as $key => $categoryId) {
                $cacheKeys[$categoryId] = $categoryId."-".$flag;
            }
            $subCateFromCacheData = $this->cache->getSubCategoriesByCategories($cacheKeys);
            $subCateFromCache = array();
            $categoryIds = array();
            foreach ($cacheKeys as $key => $value) 
            {
               if(isset($subCateFromCacheData[$value])){
                    $subCateFromCache[$key] = $subCateFromCacheData[$value];
               }else{
                    $categoryIds[] = $key;
               }
            }
        }
        $subCatFromDB = array();
        if(count($categoryIds) > 0) 
        {
            foreach ($categoryIds as $key => $categoryId) 
            {
                $categoryDataResults = $this->dao->getSubCategories($categoryId,$flag);
                $subCategories = $this->_load($categoryDataResults);
                $this->cache->storeSubCategories($categoryId."-".$flag,$subCategories);
                $subCatFromDB[$categoryId] = $subCategories;
            }
        }
       
        $subCategories = array();
        foreach($orderOfCategoryIds as $categoryId) {
            if(isset($subCateFromCache[$categoryId])) {
                $subCategories[$categoryId] = $subCateFromCache[$categoryId];
            }
            else if(isset($subCatFromDB[$categoryId])) {
                $subCategories[$categoryId] = $subCatFromDB[$categoryId];
            }
        }
        return $subCategories;
    }
    
    public function getCategoryByLDBCourse($LDBCourseId)
    {
        Contract::mustBeNumericValueGreaterThanZero($LDBCourseId,'LDB Course ID');
        
        if($this->caching && $category = $this->cache->getCategoryByLDBCourse($LDBCourseId)) {
            return $category;
        }
        
        $categoryDataResults = $this->dao->getCategoryByLDBCourse($LDBCourseId);
        $category = $this->_loadOne($categoryDataResults);
        $this->cache->storeCategoryByLDBCourse($LDBCourseId,$category);
        return $category;
    }
    
    public function getCrossPromotionMappedCategory($categoryId)
    {
        Contract::mustBeNumericValueGreaterThanZero($categoryId,'Category ID');
        
        if($this->caching && $category = $this->cache->getCrossPromotionMappedCategory($categoryId)) {
            return $category;
        }
        
        $categoryDataResults = $this->dao->getCrossPromotionMappedCategory($categoryId);
        $category = $this->_loadOne($categoryDataResults);
        $this->cache->storeCrossPromotionMappedCategory($categoryId,$category);
        return $category;
    }
    
    private function _loadOne($results)
    {
        $categories = $this->_load(array($results));
        return $categories[0];
    }
    
    private function _load($results)
    {
        $categories = array();
        
        if(is_array($results) && count($results))
        {
            foreach($results as $result)
            {
                $category = $this->_createCategory($result);
                $categories[] = $category;
            }
        }
        
        return $categories;
    }
    
    private function _createCategory($result)
    {
        $category = new Category;
        $this->fillObjectWithData($category,$result);
        return $category;
    }

    public function courseSubcategoryDesiredCourseMapping($clientCourseId, $flag){
        
        if($this->caching && $LDBCourses = $this->cache->getLDBCourses($clientCourseId."-".$flag)) {
            return $LDBCourses;
        }
        $this->CI->load->model('LDB/ldbcoursemodel');
        $LDBCourses = $this->CI->ldbcoursemodel->getLDBCoursesForClientCourse($clientCourseId);
        $this->cache->storeLDBCourses($clientCourseId."-".$flag, $LDBCourses);
        return $LDBCourses;
    }

    function findAll() {
        $this->CI->load->model('categoryList/categorymodel');
        $categoryIds = $this->CI->categorymodel->getAllCategoryIds();
        foreach($categoryIds as $categoryData) {
            _p($this->find($categoryData['boardId']));
        }
    }
}