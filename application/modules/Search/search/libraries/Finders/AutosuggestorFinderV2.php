<?php

class AutosuggestorFinderV2 {
	
	private $_ci;
	
	public function __construct(){
		$this->_ci = & get_instance();
		
		$this->_ci->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder 	= new CategoryBuilder;
		$this->categoryRepository = $categoryBuilder->getCategoryRepository();
		
		$this->_ci->load->builder('LDBCourseBuilder','LDB');
		$LDBCourseBuilder 	= new LDBCourseBuilder;
		$this->LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->searchModel = new SearchModel();
	}
	
	public function getData($courseIndexData, $instituteIndexData) {
		if(empty($courseIndexData) || empty($instituteIndexData)){
			return array();
		}
		$autosuggestorIndexData = $this->preprocessRawData($courseIndexData, $instituteIndexData);
		return $autosuggestorIndexData;
	}
	
	public function preprocessRawData($courseData, $instituteData) {
		$autoSuggestorList 	= array();
		$autoSuggestor 		= array();
		//populate institute details
		$autoSuggestor['institute_id'] 			= $instituteData['institute_id'];
		$autoSuggestor['institute_title'] 		= $instituteData['institute_title'];
		$autoSuggestor['institute_view_count'] 	= $instituteData['institute_view_count'];
		
		$relatedKeywords = $this->getInstituteRelatedWords($instituteData['institute_id']);
		if(!empty($relatedKeywords)){
			$autoSuggestor['institute_synonyms'] 	= $relatedKeywords['synonyms'];
			$autoSuggestor['institute_accronyms'] 	= $relatedKeywords['accronyms'];
		}
		
		foreach($courseData as $courseList) {
			/* Looping to get location specific data */
			$courseInstance = NULL;
			foreach($courseList as $course) {
				$courseInstance = $course;
				//populate city ids
				if(!empty($course['course_city_id']) && !empty($course['course_city_name'])) {
					if(!in_array($course['course_city_id'], $autoSuggestor['asv2_city_ids'])) {
						$autoSuggestor['asv2_city_ids'][] 			= $course['course_city_id'];
						$autoSuggestor['asv2_city_name_id_map'][] 	= $course['course_city_name'].":".$course['course_city_id'];
					}
					//populate min fees city wise
					if(empty($autoSuggestor['asv2_fees_city_'.$course['course_city_id']]) || ($course['course_normalised_fees'] < $autoSuggestor['asv2_fees_city_'.$course['course_city_id']])) {
						$autoSuggestor['asv2_fees_city_'.$course['course_city_id']] = $course['course_normalised_fees'];
					}
				}
	
				//populate virtual city ids as city ids
				if(!empty($course['course_virtual_city_id']) && !empty($course['course_virtual_city_name'])) {
					if(!in_array($course['course_virtual_city_id'], $autoSuggestor['asv2_city_ids'])) {
						$autoSuggestor['asv2_city_ids'][] 			= $course['course_virtual_city_id'];
						$autoSuggestor['asv2_city_name_id_map'][] 	= $course['course_virtual_city_name'].":".$course['course_virtual_city_id'];
					}
					//populate min fees virtual city wise
					if(empty($autoSuggestor['asv2_fees_city_'.$course['course_virtual_city_id']]) || ($course['course_normalised_fees'] < $autoSuggestor['asv2_fees_city_'.$course['course_virtual_city_id']])) {
						$autoSuggestor['asv2_fees_city_'.$course['course_virtual_city_id']] = $course['course_normalised_fees'];
					}
				}
				//populate state ids
				if(!empty($course['course_state_id']) && !empty($course['course_state_name'])) {
					if(!in_array($course['course_state_id'], $autoSuggestor['asv2_state_ids'])) {
						$autoSuggestor['asv2_state_ids'][] 			= $course['course_state_id'];
						$autoSuggestor['asv2_state_name_id_map'][] 	= $course['course_state_name'].":".$course['course_state_id'];
					}
					//populate fees state wise
					if(empty($autoSuggestor['asv2_fees_state_'.$course['course_state_id']]) || ($course['course_normalised_fees'] < $autoSuggestor['asv2_fees_state_'.$course['course_state_id']])) {
						$autoSuggestor['asv2_fees_state_'.$course['course_state_id']] = $course['course_normalised_fees'];
					}
				}
				//populate min fees for all india
				if(empty($autoSuggestor['asv2_fees_city_1']) || ($course['course_normalised_fees'] < $autoSuggestor['asv2_fees_city_1'])) {
					$autoSuggestor['asv2_fees_city_1'] = $course['course_normalised_fees'];
				}
			}
			
			/* General information */
			//populate course mode/type
			if(!empty($courseInstance['course_type'])) {
				$autoSuggestor['course_type'] = $courseInstance['course_type'];
			}
			//populate course level
			if(!empty($courseInstance['course_level_cp'])) {
				$autoSuggestor['course_level_cp'] = $courseInstance['course_level_cp'];
			}
			//populate exams
			if(!empty($courseInstance['course_RnR_valid_exams'])) {
				$autoSuggestor['course_RnR_valid_exams'] = $courseInstance['course_RnR_valid_exams'];
			}
			$autoSuggestor['course_id'] 	= $courseInstance['course_id'];
			$autoSuggestor['asv2_course_name_id_map'] 	= trim($courseInstance['course_title']) . ":" . trim($courseInstance['course_id']);
			$autoSuggestor['facetype'] 		= 'autosuggestorv2';
			
			/*Document specific fields:
			* Handling subcategories of course, Here we need one document per sub category id.
			*/
			$count = 1;
			$ldbCourseDetails = $this->_getLDBCourseDetails($courseInstance['course_ldb_id']);
			if(!empty($ldbCourseDetails)) {
				/* Handling subcategories of course, Here we need one document per sub category id.*/
				$subcatid_bucket 	= $ldbCourseDetails['subcatid_bucket'];
				$subcatFields 		= $this->_getSubcategoryFields($subcatid_bucket);
				
				$categoryName = ''; $categoryId = '';
				foreach($subcatFields['asv2_subcat_name_id_map'] as $key => $subcatNameIdValue) {
					$explodedData 		= explode(":", $subcatNameIdValue);
					$subcategoryName 	= $explodedData[0];
					$subcategoryId 		= $explodedData[1];

					$explodedData 		= explode(":", $subcatFields['asv2_cat_name_id_map'][$key]);
					if(!empty($explodedData[0]) && !empty($explodedData[1])) {
						$categoryName 		= $explodedData[0];
						$categoryId 		= $explodedData[1];
					}
					
					/* Handling ldb courses and specialization of course */
					$ldbCourseSpecFields = array();
					$ldbCourseSpecFields = $this->_getLDBAndSpecializationFields($ldbCourseDetails);
					
					if(!empty($ldbCourseSpecFields)) {
						$autoSuggestor = array_merge($autoSuggestor, $ldbCourseSpecFields);
					}
					
					$autoSuggestor['asv2_subcat_name'] 			= trim($subcategoryName);
					$autoSuggestor['asv2_subcat_id'] 			= $subcategoryId;
					$autoSuggestor['asv2_cat_name_id_map'] 		= trim($categoryName) . ":" . trim($categoryId);
					$autoSuggestor['asv2_subcat_name_id_map'] 	= trim($subcategoryName) . ":" . trim($subcategoryId);

					foreach ($ldbCourseDetails['ldb_mixed_bucket'][$subcategoryId] as $key => $value) {
						$specName = trim($value['name']);
						$specId = trim($value['id']);
						$specCustomId = trim($courseData['ldbCourseNameWithCustomIds'][$specName]);
						if(!empty($specName) && !empty($specCustomId)) {
							$autoSuggestor['asv2_spec_ldb_custom_name_id_map'] = $specName.":".$specCustomId;
							$autoSuggestor['asv2_spec_ldb_name'] = $specName;
							$autoSuggestor['asv2_spec_ldb_id'] = $specId;
							$autoSuggestor['unique_id'] = 'autosuggestorv2_' . $courseInstance['course_id'] . "_" . $count;
							$count++;
							$autoSuggestorList[] = $autoSuggestor;
						}
					}
					if(empty($ldbCourseDetails['ldb_mixed_bucket'][$subcategoryId])) {
						//_p('incrementing for subcat');
						$autoSuggestor['unique_id'] = 'autosuggestorv2_' . $courseInstance['course_id'] . "_" . $count;
						$count++;
						$autoSuggestorList[] = $autoSuggestor;
					}
				}
			}
		}
		
		return $autoSuggestorList;
	}
	
	private function _getSubcategoryDetails($mixedCategoryIds = array()) {
		$subCategories = array();
		if(!empty($mixedCategoryIds)){
			$categoryObjectList = $this->categoryRepository->findMultiple($mixedCategoryIds);
			foreach($categoryObjectList as $category){
				$parentId = $category->getParentId();
				if($parentId > 1) {
					$subCategories[$category->getId()] = $category->getName();
					$categoryId = $category->getParentId();
					$categoryObject = $this->categoryRepository->find($categoryId);
					$categories[$category->getId()]['id'] = $categoryId;
					$categories[$category->getId()]['name'] = $categoryObject->getName();
				}
			}
		}
		return array('subCategories'=>$subCategories, 'categories'=>$categories);
	}
	
	private function _getLDBCourseDetails($ldbCourseIds = array()) {
		$ldbCourseDetails = false;
		if(!empty($ldbCourseIds)){
			$ldbCourseBucket 		= array();
			$specializationBucket 	= array();
			$subCategoryIds 		= array();
			$subcatWiseSpecWithCustomIds = array();
			foreach($ldbCourseIds as $ldbCourseId){
				$ldbCourseObj 	= $this->LDBCourseRepository->find($ldbCourseId);
				$ldbcourseName 	= $ldbCourseObj->getCourseName();
				$specialization = $ldbCourseObj->getSpecialization();
				$subCategoryId 	= $ldbCourseObj->getSubCategoryId();
				$subcatObj = $this->categoryRepository->find($subCategoryId);
				$subcatName = $subcatObj->getName();
				if(strtolower($specialization) == 'all') {
					if($subcatName != $ldbcourseName) {
						$ldbCourseBucket[$subCategoryId][] = array("name" => $ldbcourseName, "id" => $ldbCourseId);
					}
				} else {
					$specializationBucket[$subCategoryId][] = array("name" => $specialization, "id" => $ldbCourseId);
				}
				$subCategoryIds[] = $subCategoryId;
			}
			foreach ($subCategoryIds as $subCategoryId) {
				if(!empty($ldbCourseBucket[$subCategoryId]) && !empty($specializationBucket[$subCategoryId])) {
					$subcatWiseSpecWithCustomIds[$subCategoryId] = array_merge($ldbCourseBucket[$subCategoryId], $specializationBucket[$subCategoryId]);
				} elseif(!empty($specializationBucket[$subCategoryId])) {
					$subcatWiseSpecWithCustomIds[$subCategoryId] = $specializationBucket[$subCategoryId];
				} elseif(!empty($ldbCourseBucket[$subCategoryId])) {
					$subcatWiseSpecWithCustomIds[$subCategoryId] = $ldbCourseBucket[$subCategoryId];
				}
			}
			
			$ldbCourseDetails['ldb_course_bucket'] 			= $ldbCourseBucket;
			$ldbCourseDetails['ldb_specialization_bucket'] 	= $specializationBucket;
			$ldbCourseDetails['ldb_mixed_bucket'] 			= $subcatWiseSpecWithCustomIds;
			$ldbCourseDetails['subcatid_bucket'] 			= array_unique($subCategoryIds);
		}
		return $ldbCourseDetails;
	}
	
	private function _getSubcategoryFields($subcatidBucket = array()) {
		if(empty($subcatidBucket)) {
			return false;
		}
		$courseSubCategoryDetails 	= $this->_getSubcategoryDetails($subcatidBucket);
		$subCategoryNameIdMap		= array();
		$subCategoryIdList 			= array();
		foreach($courseSubCategoryDetails['subCategories'] as $key => $value){
			$subCategoryNameIdMap[] = $value . ":" . $key;
			$subCategoryIdList[] 	 = $key;
			$catId = $courseSubCategoryDetails['categories'][$key]['id'];
			$catName = $courseSubCategoryDetails['categories'][$key]['name'];
			$categoryNameIdMap[] = $catName . ":" . $catId;
		}
		$data = array();
		$data['asv2_subcat_name_id_map'] = $subCategoryNameIdMap;
		$data['asv2_subcat_name'] = array_values($courseSubCategoryDetails);
		$data['asv2_subcat_id'] = $subCategoryIdList;
		$data['asv2_cat_name_id_map'] = $categoryNameIdMap;
		return $data;
	}
	
	private function _getLDBAndSpecializationFields($ldbCourseDetails = array()) {
		$fields = array();
		if(empty($ldbCourseDetails)){
			return false;
		}
		
		$ldbCourseBucket 					= $ldbCourseDetails['ldb_course_bucket'];
		$specializationBucket 				= $ldbCourseDetails['ldb_specialization_bucket'];
		$subcategoryLDBCourseMap 			= array();
		$subcategorySpecializationCourseMap = array();
		if(!empty($ldbCourseBucket)){
			foreach($ldbCourseBucket as $subcategoryId => $ldbDetails){
				$map = array();
				foreach($ldbDetails as $detail){
					$map[] = $detail['name'] . ":" . $detail['id'];
				}
				$fieldName = "asv2_ldb_name_id_map_subcat_" . $subcategoryId;
				$fields[$fieldName] = $map;
			}
		}
		if(!empty($specializationBucket)){
			foreach($specializationBucket as $subcategoryId => $specDetails){
				$map = array();
				foreach($specDetails as $detail){
					$map[] = $detail['name'] . ":" . $detail['id'];
				}
				$fieldName = "asv2_spec_name_id_map_subcat_" . $subcategoryId;
				$fields[$fieldName] = $map;
			}
		}
		return $fields;
	}
	
	private function getInstituteRelatedWords($instituteId = NULL){
		if(empty($instituteId)){
			return false;
		}
		$keywords = $this->searchModel->getInstituteRelatedKeyword($instituteId);
		return $keywords;
	}

}
	