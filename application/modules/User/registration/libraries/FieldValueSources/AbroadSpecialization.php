<?php
/**
 * File for Value source for exam field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source for exam field
 */ 
class AbroadSpecialization extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
	$abroad_common_lib = $this->CI->load->library('listingPosting/AbroadCommonLib');
	$abroadCategoryPageLib  = $this->CI->load->library('categoryList/AbroadCategoryPageLib');
	$category_client = $this->CI->load->library('categoryList/clients/CategoryClient');	
	$final_category_name= array();
	$category_tree = $abroad_common_lib->getAbroadCategories(1,0,'newStudyAbroad');	 
		
        $main_ldb_courses = $abroad_common_lib->getAbroadMainLDBCourses(); 
	// $main_ldb_courses_ids = array();
	$ldb_course_to_subcat = array();
	foreach($main_ldb_courses as $val) {
		// $main_ldb_courses_ids[] = $val['SpecializationId']; 
		$ldb_course_to_subcat[] = $abroadCategoryPageLib->getSubCatsForDesiredCourses($val['SpecializationId']);	
	}
	
	
	$ldb_course_to_subcat_mapping = array();
	$all_subcat_ids = array();
	foreach($ldb_course_to_subcat as $key => $value) {
		foreach ($value as $key => $val) {
			$ldb_course_to_subcat_mapping[$val['ldb_course_id']][] = $val['sub_category_id']; 
			$all_subcat_ids[] = $val['sub_category_id'];			
		}
	}
      
	$all_sucategory_name_list = $category_client->getMultipleCategories($all_subcat_ids);
	
	foreach($main_ldb_courses as $val) {
		$category_tree[$val['SpecializationId']]['id'] = $val['SpecializationId'];
		$category_tree[$val['SpecializationId']]['name'] = $val['CourseName']; 
		$subcat_ids = 	$ldb_course_to_subcat_mapping[$val['SpecializationId']];		
		foreach($subcat_ids as $id) {
			$tmp = $all_sucategory_name_list[$id];
			$tmp['id'] = $id;
			$category_tree[$val['SpecializationId']]['subcategory'][$id] = $tmp;
		}	
	} 
	
	return $category_tree;	
    }
    
}
