<?php
/*
*
* @package listingBase
*/

class TestListingApi extends MX_Controller {
	private $acceptedReturntype = array('object', 'json', 'array');
	function init(){
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->ListingBaseBuilder    = new ListingBaseBuilder();
	}

	function getInstitute() {
		$this->init();
		$keyword = 's';
		$InstituteRepository = $this->ListingBaseBuilder->getInstituteRepository();
		_p($InstituteRepository->getInstituteSuggestions($keyword, 5, 'json'));
		// _p($InstituteRepository->find(1));
	}

	/*
	 * ------------------------ Attribute related APIs ------------------------
	 */
	function test_getAttributeIdByAttributeName($input, $outputFormat = 'json') {
		$this->load->library('BaseAttributeLibrary','listingBase');
		$baseAttributeLibrary = new BaseAttributeLibrary();

		$inputArr = explode(',', $input);
		//$inputArr = array('Course variant', 'Course type');

		$data = $baseAttributeLibrary->getAttributeIdByAttributeName($inputArr, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $data;
		} else {
			_p($data);
		}
	}

	function test_getValuesForAttributeByName($input, $outputFormat = 'json') {
		$this->load->library('BaseAttributeLibrary','listingBase');
		$baseAttributeLibrary = new BaseAttributeLibrary();
		
		$inputArr = explode(',', $input);
		//$inputArr = array('Course variant', 'Course type');
		
		$data = $baseAttributeLibrary->getValuesForAttributeByName($inputArr, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $data;
		} else {
			_p($data);
		}
	}

	function test_getValuesForAttributeById($input, $outputFormat = 'json') {
		$this->load->library('BaseAttributeLibrary','listingBase');
		$baseAttributeLibrary = new BaseAttributeLibrary();
		
		$inputArr = explode(',', $input);
		//$inputArr = array(1,2, 7, 9, 10);
		
		$data = $baseAttributeLibrary->getValuesForAttributeById($inputArr, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $data;
		} else {
			_p($data);
		}
	}

	function test_getValueNameByValueId($input, $outputFormat = 'json') {
		$this->load->library('BaseAttributeLibrary','listingBase');
		$baseAttributeLibrary = new BaseAttributeLibrary();
		
		$inputArr = explode(',', $input);
		//$inputArr = array(1,2, 7, 9, 10);

		$data = $baseAttributeLibrary->getValueNameByValueId($inputArr, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $data;
		} else {
			_p($data);
		}
	}

	function test_getAttributeNameByAttributeId($input, $outputFormat = 'json') {
		$this->load->library('BaseAttributeLibrary','listingBase');
		$baseAttributeLibrary = new BaseAttributeLibrary();
		
		$inputArr = explode(',', $input);
		//$inputArr = array(1,2, 7, 9, 10);
		
		$data = $baseAttributeLibrary->getAttributeNameByAttributeId($inputArr, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $data;
		} else {
			_p($data);
		}
	}

	function test_getDependentAttributesByName($attributeName, $valueString, $outputFormat = 'json') {
		$this->load->library('BaseAttributeLibrary','listingBase');
		$baseAttributeLibrary = new BaseAttributeLibrary();

		$data = $baseAttributeLibrary->getDependentAttributesByName($attributeName, $valueString, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $data;
		} else {
			_p($data);
		}
	}

	function test_getDependentAttributesByValueId($valueId, $outputFormat = 'json') {
		$this->load->library('BaseAttributeLibrary','listingBase');
		$baseAttributeLibrary = new BaseAttributeLibrary();

		$data = $baseAttributeLibrary->getDependentAttributesByValueId($valueId, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $data;
		} else {
			_p($data);
		}
	}

	/*
	function test_getAttributesToReset($attrId, $attrName) {
		$this->load->repository('BaseAttributeLibrary','listingBase');
		$baseAttributeLibrary = new BaseAttributeLibrary();

		$data = $baseAttributeLibrary->getAttributesToReset($attrId, $attrName);
		_p($data);
	} */

	/*
	 * ------------------------ Hierarchy related APIs ------------------------
	 */

	function test_getBaseEntitiesByHierarchyId($input, $getIdName, $outputFormat = 'json') {
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();

		$inputArr = explode(',', $input);
		
		$data = $hierarchyRepository->getBaseEntitiesByHierarchyId($input, $getIdName, $outputFormat);
		if($outputFormat == 'json') {
			echo $data;
		} else {
			_p($data);
		}
	}

	function test_getHierarchyIdsForAllCombinations($streamIds, $substreamIds, $specializationIds, $outputFormat = 'array') {
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();

		$streamIdsArr = explode(',', $streamIds);
		$substreamIdsArr = explode(',', $substreamIds);
		$specializationIdsArr = explode(',', $specializationIds);

		$data = $hierarchyRepository->getHierarchyIdsForAllCombinations($streamIdsArr, $substreamIdsArr, $specializationIdsArr, $outputFormat);
		if($outputFormat == 'json') {
			echo $data;
		} else {
			_p($data);
		}
	}

	function test_getHierarchyIdByBaseEntities($streamId, $substreamId, $specializationId, $outputFormat = 'json') {
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();

		$hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();
		$result = $hierarchyRepository->getHierarchyIdByBaseEntities($streamId, $substreamId, $specializationId, $outputFormat);
		
		if($outputFormat == 'array'){
			_p($result);die;
		}
		else if($outputFormat == 'json'){
			echo $result;
		}
	}

	function test_getSubstreamSpecializationByStreamId($ids, $getIdName, $outputFormat = 'array') {
		$ids = explode(',', $ids);
		
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();

		$result = $hierarchyRepository->getSubstreamSpecializationByStreamId($ids, $getIdName, $outputFormat);

		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getSpecializationTreeByStreamSubstreamId($streamId, $substreamIds = NULL, $getIdName, $outputFormat = 'json') {
		if($substreamIds == 'NULL') {
			$substreamIds = NULL;
		} else {
			$substreamIds = explode(',', $substreamIds);
		}
		
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();
		
		$result = $hierarchyRepository->getSpecializationTreeByStreamSubstreamId($streamId, $substreamIds, $getIdName, $outputFormat);

		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getSpecializationByStreamSubstreamId($streamId, $substreamIds = NULL, $getIdName, $outputFormat = 'json') {
		if($substreamIds == 'NULL') {
			$substreamIds = NULL;
		} else {
			$substreamIds = explode(',', $substreamIds);
		}
		
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();
		
		$result = $hierarchyRepository->getSpecializationByStreamSubstreamId($streamId, $substreamIds, $getIdName, $outputFormat);

		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getAllStreams($outputFormat = 'json') {
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();

		$result = $hierarchyRepository->getAllStreams($outputFormat);

		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getHierarchiesByMultipleBaseEntities($outputFormat = 'json') {
		$baseEntityArr[0]['streamId'] = 3;
        $baseEntityArr[0]['substreamId'] = 'any';
        $baseEntityArr[0]['specializationId'] = 'none';

        $this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();

		$result = $hierarchyRepository->getHierarchiesByMultipleBaseEntities($baseEntityArr, $outputFormat);
		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	/*
	 * ------------------------ Base Course related APIs ------------------------
	 */

	public function test_getAllBaseCourses($outputFormat = 'json', $includeDummy) {
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$data = $baseCourseRepository->getAllBaseCourses($outputFormat, $includeDummy);
		
		if($outputFormat == 'json') {
			echo $data;
		} else {
			_p($data);
		}
	}

	function test_getBaseCoursesByMultipleBaseEntities($getIdNames, $outputFormat = 'json') {
		$baseEntityArr[0]['streamId'] = 3;
        $baseEntityArr[0]['substreamId'] = 'any';
        $baseEntityArr[0]['specializationId'] = 'any';

        $this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$result = $baseCourseRepository->getBaseCoursesByMultipleBaseEntities($baseEntityArr, $getIdNames, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getBaseCoursesForAllCombinations($streamIds, $substreamIds, $specializationIds, $getIdNames, $outputFormat = 'json') {
		$streamIds = explode(',', $streamIds);
		if(!empty($substreamIds)) {
			$substreamIds = explode(',', $substreamIds);
		}
		if(!empty($specializationIds)) {
			$specializationIds = explode(',', $specializationIds);
		}
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$result = $baseCourseRepository->getBaseCoursesForAllCombinations($streamIds, $substreamIds, $specializationIds, $getIdNames, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getBaseCoursesByHierarchyIds($hierarchyIds, $getIdNames, $outputFormat = 'json') {
		$hierarchyIds = explode(',', $hierarchyIds);

		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$result = $baseCourseRepository->getBaseCoursesByHierarchyIds($hierarchyIds, $getIdNames, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getHierarchyIdsByBaseCourses($baseCourseIds, $outputFormat = 'json') {
		$baseCourseIds = explode(',', $baseCourseIds);

		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$result = $baseCourseRepository->getHierarchyIdsByBaseCourses($baseCourseIds, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getSpecializationsByBaseCourseIds($baseCourseIds, $streamIds, $getIdNames, $outputFormat = 'json') {
		$baseCourseIds = explode(',', $baseCourseIds);
		
		if($streamIds == 'NULL') {
			$streamIds = NULL;
		} else if(!empty($streamIds)) {
			$streamIds = explode(',', $streamIds);
		}

		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$result = $baseCourseRepository->getSpecializationsByBaseCourseIds($baseCourseIds, $streamIds, $getIdNames, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getBaseCoursesByBaseEntities($streamIds, $substreamIds, $specializationIds, $getIdNames, $outputFormat = 'json') {
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$result = $baseCourseRepository->getBaseCoursesByBaseEntities($streamIds, $substreamIds, $specializationIds, $getIdNames, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getBaseEntitiesByBaseCourseIds($baseCourseIds, $streamIds, $specializationIds, $outputFormat = 'json') {
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$result = $baseCourseRepository->getBaseEntitiesByBaseCourseIds($baseCourseIds, $streamIds, $specializationIds, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getBaseEntityTreeByBaseCourseIds($baseCourseIds, $streamIds, $getIdNames, $outputFormat = 'json') {
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$result = $baseCourseRepository->getBaseEntityTreeByBaseCourseIds($baseCourseIds, $streamIds, $getIdNames, $outputFormat);
		
		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}

	function test_getAllPopularCourses($outputFormat) {
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$result = $baseCourseRepository->getAllPopularCourses($outputFormat);
		
		if($outputFormat == 'json') {
			echo $result;
		} else {
			_p($result);
		}
	}
        function test_getCourseHomePageDictionary($useCourseHomeOldFormat,$outputFormat = 'json'){
               $courseCommonLib=$this->load->library('coursepages/CoursePagesCommonLib');
               $courseHomeDictionary=$courseCommonLib->getCourseHomePageDictionary($useCourseHomeOldFormat);
               if($outputFormat=='json'){
                   echo json_encode($courseHomeDictionary);
               }else{
                   _p($courseHomeDictionary);
               }
        }
}