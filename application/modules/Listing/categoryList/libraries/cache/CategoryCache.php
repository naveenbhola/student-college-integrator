<?php

class CategoryCache extends Cache
{
    function __construct()
	{
		parent::__construct();
	}
    
    /*
	 * Category
	 */ 
	function getCategory($categoryId)
    {
		return unserialize($this->get('Category',$categoryId));
    }
	
	function storeCategory($category)
	{
		$this->store('Category',$category->getId(),serialize($category),-1,CACHEPRODUCT_CATEGORY,0);
	}
    
    /*
	 * Sub-Categories
	 */ 
	function getSubCategories($categoryId)
    {
		return unserialize($this->get('SubCategories',$categoryId));
    }
	
    function getSubCategoriesByCategories($categoryIds)
    {
    	$categories =  $this->multiGet('SubCategories',$categoryIds);
        $categories = array_map('unserialize',$categories);
        return $categories;
    }

	function storeSubCategories($categoryId,$subCategories)
	{
		$this->store('SubCategories',$categoryId,serialize($subCategories),-1,CACHEPRODUCT_CATEGORY,0);
	}
	
	/*
	 * Category By LDB Course
	 */ 
	function getCategoryByLDBCourse($LDBCourseId)
    {
		return unserialize($this->get('CategoryByLDBCourse',$LDBCourseId));
    }
	
	function storeCategoryByLDBCourse($LDBCourseId,$category)
	{
		$this->store('CategoryByLDBCourse',$LDBCourseId,serialize($category),-1,CACHEPRODUCT_CATEGORY,0);
	}
	
	/*
	 * Cross-promotion mapped category
	 */ 
	function getCrossPromotionMappedCategory($categoryId)
    {
		return unserialize($this->get('CrossPromotionMappedCategory',$categoryId));
    }
	
	function storeCrossPromotionMappedCategory($categoryId,$category)
	{
		$this->store('CrossPromotionMappedCategory',$categoryId,serialize($category),-1,CACHEPRODUCT_CATEGORY,0);
	}
	 public function getMultipleCategories($categoryIds)
    	{
                $categories =  $this->multiGet('Category',$categoryIds);
                $categories = array_map('unserialize',$categories);
                return $categories;
    	}

    public function getLDBCourses($clientCourses){
    	return unserialize($this->get('LDBCourses',$clientCourses));
    }

    public function storeLDBCourses($clientCourses, $courseData){
    	$this->store('LDBCourses',$clientCourses,serialize($courseData),-1,CACHEPRODUCT_CATEGORY,0);
    }
}