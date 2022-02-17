<?php

class CategoryClient extends XmlRpcClient
{
    function __construct()
	{
		parent::__construct();
		
		$this->server = 'categoryList';
		$this->serverFile = 'CategoryServer';
	}
	
	function getCategory($categoryId)
	{
		$request = array($categoryId); 
		return $this->executeRequest('getCategory',$request);
	}
	
	function getMultipleCategories($categoryIds)
	{
		$request = array(array($categoryIds,'struct')); 
		return $this->executeRequest('getMultipleCategories',$request);
	}
	
	function getSubCategories($categoryId,$flag)
	{
		$request = array($categoryId,$flag); 
		return $this->executeRequest('getSubCategories',$request);
	}
	
	function getCategoryByLDBCourse($LDBCourseId)
	{
		$request = array($LDBCourseId); 
		return $this->executeRequest('getCategoryByLDBCourse',$request);
	}
	
	function getCrossPromotionMappedCategory($categoryId)
	{
		$request = array($categoryId); 
		return $this->executeRequest('getCrossPromotionMappedCategory',$request);
	}
}

