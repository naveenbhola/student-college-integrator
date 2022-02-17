<?php

class CategoryModel extends MY_Model
{
    function __construct()
    {
        parent::__construct('CategoryList');
		$this->_db = $this->getReadHandle();
    }
	
	public function getCategory($categoryId)
	{
		Contract::mustBeNumericValueGreaterThanZero($categoryId,'Category ID');
		
		$sql =  "SELECT * ".
				"FROM categoryBoardTable ".
                "WHERE boardId = '?' ";
		return $this->_db->query($sql, (int) $categoryId)->row_array();
	}
	
	public function getMultipleCategories($categoryIds)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($categoryIds,'Category IDs');
		
		$sql =  "SELECT * ".
				"FROM categoryBoardTable ".
                "WHERE boardId IN (?) ".
				"ORDER BY boardId";
				
			$categories = array();	
			
			$result = $this->_db->query($sql, array($categoryIds))->result_array();
			foreach($result as $category){
				$categories[$category['boardId']] = $category;
			}
			return $categories;
	}
	
	public function getSubCategories($categoryId,$flag="national")
	{
		Contract::mustBeNumericValueGreaterThanZero($categoryId,'Category ID');
		
		switch($flag) {
		    case "abroad" :
			    $clausePart = ' AND flag = "studyabroad" AND isOldCategory = "1" ';
			break;
		    
		    case "newAbroad" :
			    $clausePart = ' AND flag = "studyabroad" AND isOldCategory = "0" ';
			break;
		    
		    default:
			    $clausePart = ' AND (flag = "national" OR flag = "testprep")  ';
			break;
		}
		
		$sql =  "SELECT * ".
				"FROM categoryBoardTable ".
               // "WHERE parentId = '".$categoryId."' ".($flag?" AND flag = '".$flag."' ":"").
				"WHERE parentId = ? ".$clausePart.
				"ORDER BY tier,name";
		
		return $this->_db->query($sql, array($categoryId))->result_array();
	}
	
	public function getCategoryByLDBCourse($LDBCourseId)
	{
		Contract::mustBeNumericValueGreaterThanZero($LDBCourseId,'LDB Course ID');
		
		$sql =  "SELECT c.* ".
				"FROM LDBCoursesToSubcategoryMapping l ".
				"INNER JOIN categoryBoardTable c ON c.boardId = l.categoryID ".
                "WHERE l.ldbCourseID = ? ";
	
		return $this->_db->query($sql, array($LDBCourseId))->row_array();
	}
	
	public function getCrossPromotionMappedCategory($categoryId)
	{
		Contract::mustBeNumericValueGreaterThanZero($categoryId,'Category ID');
		
		$sql =  "SELECT c.* ".
				"FROM india_abroad_category_mapping m ".
				"INNER JOIN categoryBoardTable c ON c.boardId = m.mappedToCategoryID ".
                "WHERE m.categoryID = ? AND m.status = 'live'";
				
		return $this->_db->query($sql, array($categoryId))->row_array();
	}
       /* please pass location as key value format, strictly follow this architecture array('country_id'=>$country_id,
	 'state_id'=>$state_id,'city_id'=>$city_id)*/
public function getClientCoursesForLocationandSubcategory($subcategory = array(),$location = array()) {
        
		$keys_array = array('country_id','state_id','city_id');
		$location_sql = "";
		/*
		if(count($subcategory) == 0 || count($location) == 0  || array_intersect(array_keys($location), $keys_array) == 0) {

			return array();
		}

		foreach($location as $key=>$value) {
			$location_sql = $location_sql."AND $key = $value";
		}

		$sql = "SELECT category_id,course_id ".
		       "FROM categoryPageData ".
		       "WHERE category_id in (".implode(",",$subcategory).")".
		       "AND STATUS = 'live' ".$location_sql; 

		foreach ($result as $row) {
			$result_array[$row['category_id']][] = $row['course_id'];	
		}

                foreach($result_array as $category_id=>$value) {
			$result_array[$category_id] = implode(",",array_unique($value));
		
		}
       	
		*/

		$sql = "SELECT distinct course_id ".
		       "FROM categoryPageData ".
		       "WHERE STATUS = 'live' AND `country_id` !=2"; 

		$result = $this->_db->query($sql)->result_array();
		$result_array = array();
		foreach($result as $row) {
			$result_array[] = $row['course_id'];
		}
		// error_log("Data = ".print_r($result_array, true));
                
                //_P($result_array);exit;
		return $result_array;
	}
	// below function is user to get category wise, location wise courses, primarily used for data generation in csv format
	public function getClientCoursesForLocationandSubcategory2($subcategory = array(),$location = array()) {
        return;
		$keys_array = array('country_id','state_id','city_id');
		$location_sql = "";
		
		if(count($subcategory) == 0 || count($location) == 0  || array_intersect(array_keys($location), $keys_array) == 0) {

			return array();
		}

		foreach($location as $key=>$value) {
			$location_sql = $location_sql."AND $key = $value";
		}

		$sql = "SELECT category_id,course_id ".
		       "FROM categoryPageData ".
		       "WHERE category_id in (".implode(",",$subcategory).")".
		       "AND STATUS = 'live' ".$location_sql; 
		//$result = $this->_db->query($sql)->result_array();
		foreach ($result as $row) {
			$result_array[$row['category_id']][] = $row['course_id'];	
		}

                foreach($result_array as $category_id=>$value) {
			$result_array[$category_id] = implode(",",array_unique($value));
		
		}

		 //error_log("Data = ".print_r($result_array, true));
                
                //_P($result_array);exit;
		return $result_array;
	}

	
	public function getMainCategories($flag = "national")
	{
		if($flag == "national") {
		    $clause = " AND flag in ('national', 'testprep')";
		} else {
		     $clause = ' AND flag = "'.$flag.'"';
		}
		
		$sql = "SELECT boardId as id,name FROM categoryBoardTable WHERE parentId = 1 ".$clause;
		$query = $this->_db->query($sql);		    
		return $query->result_array();
	}
	
	public function getCategoryDataByCourseId($course_id)
    {
		$sql = "SELECT category_id from categoryPageData where course_id = ?";
		$result = $this->_db->query($sql, array($course_id))->result_array();
		$result_array = array();
		foreach($result as $row) {
			$result_array[] = $row;
		}
		return $result_array;
    }

    function getAllCategoryIds() {
    	$sql = 'SELECT boardId FROM categoryBoardTable  WHERE flag = "national" GROUP BY boardId;';
		$result = $this->_db->query($sql)->result_array();
		return $result;
    }
}
