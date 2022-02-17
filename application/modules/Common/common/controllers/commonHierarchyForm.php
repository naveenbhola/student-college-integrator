<?php 
/**
$Author     : Virender (UGC Team)
$Name       : commonForm
Description : Common Forms controller for creating form elements.
 **/

class commonHierarchyForm extends MX_Controller
{
	public function __construct(){
		$this->load->config('common/commonFormDefault');
	}

	private function init(){}

	public function getHierarchyMappingForm($type = 'careerCms',$prefilledData){
		switch ($type) {
			case 'careerCms':
				$this->getCareerCmsFormElements($type,$prefilledData);
				break;
			case 'videoCms':
				$this->getVideoCmsFormElements($type,$prefilledData);
				break;
			case 'articleCMS':
			case 'examCMS':
				$this->getArticleCmsFormElements($type,$prefilledData);
				break;
			default:
				# code...
				break;
		}
	}

//is fired on page load for career to get the form format.

	private function getVideoCmsFormElements($type,$prefilledData){
		$displayData = $this->getConfigData($type);
		if(!empty($prefilledData)){
			$displayData['prefilledData'] = $prefilledData;
			$displayData['actionBasedOnPrefilledData'] = 'edit';
		}
		else{
			$displayData['actionBasedOnPrefilledData'] = 'add';
		}
		//by default redefineAddMorePostion is before course field
		$this->load->view('common/hierarchyForms/formFields', $displayData);
	}

	private function getCareerCmsFormElements($type,$prefilledData){
		$displayData = $this->getConfigData($type);
		if(!empty($prefilledData)){
			$displayData['prefilledData'] = $prefilledData;
			$displayData['actionBasedOnPrefilledData'] = 'edit';
		}
		else{
			$displayData['actionBasedOnPrefilledData'] = 'add';
		}
		//by default redefineAddMorePostion is before course field
		$this->load->view('common/hierarchyForms/formFields', $displayData);
	}

	function getArticleCmsFormElements($type,$prefilledData){
		$displayData = $this->getConfigData($type);
		if(!empty($prefilledData)){
			$displayData['prefilledData'] = $prefilledData;
			$displayData['actionBasedOnPrefilledData'] = 'edit';
		}
		else{
			$displayData['actionBasedOnPrefilledData'] = 'add';
		}
		$this->load->view('common/hierarchyForms/formFields', $displayData);
	}


//is used in edit case to get the data to be prefilled
	function getPrefilledData($type, $params){
		$result = array();
		switch ($type) {
			case 'careerCms':
				$result = $this->getCareerCMSData($params);
				break;

			case 'articleCMS':
				$result = $this->getArticleCMSData($params);
				break;
			case 'examCMS':
				$result = $this->getExamCMSData($params);
				break;
			case 'videoCms':
				$result = $this->getVideoCMSData($params);
				break;
		}
		return $result;
	}

	function getPopularGroupingData($data){
		// if(!empty($data['hierarchyId'])){
		// 	$popularGroups = $this->getPopularGroupsByHierarchyIds($data['hierarchyId']);
		// 	foreach ($popularGroups as $key => $value) {
		// 		foreach ($value as $value => $val) {
		// 			 $res[] = $val;
		// 		}
		// 	}
		// }
		// else{
		// 	$res = $this->getAllPopularGroups();
		// }
		// $finalViewArray['popularGroupView'][0]['popularGroup'] = $res;
		$finalViewArray['popularGroupView'][0]['selections'] = $data['popularGroupingId'];
		return $finalViewArray;
	}

	function getAllPopularGroups(){
		$commonFormLib = $this->load->library('common/commonFormLib');
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$popularGroupRepository = $listingBase->getPopularGroupRepository();
		$fetch = $popularGroupRepository->getAllPopularGroups();
		$fetch =  $commonFormLib->sortEntityByName(array('name'), $fetch);
		return $fetch;
	}

	function getPopularGroupsByHierarchyIds($hierarchyArr){
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$popularGroupRepository = $listingBase->getPopularGroupRepository();
		$fetch = $popularGroupRepository->getPopularGroupsByHierarchyIds($hierarchyArr);
		$groupIdTraversed = array();
		$finalGroups = array();
		//removing duplicate groups		
		foreach ($fetch as $hierarchyId => $groupings) {
			foreach ($groupings as $key => $grouping) {
				if(!in_array($grouping['id'], $groupIdTraversed)){
					$finalGroups[$hierarchyId][] = $grouping;
					$groupIdTraversed[] = $grouping['id'];
				}
			}
		}
		return $finalGroups;
	}

	function getExamData($data){
		// if(!empty($data['hierarchyId'])){
		// 	$exams = $this->getAllMainExamsByHierarchyIds($data['hierarchyId']);
		// }
		// else{
		// 	$exams = $this->getAllExams();
		// }
		// $i = 0;
		// foreach ($exams as $key => $value) {
		// 	$res[$i]['id'] = $key;
		// 	$res[$i]['name'] = $value;
		// 	$i ++;
		// }
		// $finalViewArray['examView'][0]['exam'] = $res;
		$finalViewArray['examView'][0]['selections'] = $data['examId'];
		return $finalViewArray;
	}

	function getAllExams($hierarchyArr){
		$this->load->library("examPages/ExamMainLib");
		$examMainLib = new ExamMainLib();
		$examList = $examMainLib->getExamsList($filter, 'array');
		return $examList;
	}

	function getAllMainExamsByHierarchyIds($hierarchyArr){
		$this->load->library("examPages/ExamMainLib");
		$examMainLib = new ExamMainLib();
		$examList = $examMainLib->getAllMainExamsByHierarchyIds($hierarchyArr);
		return $examList;
	}

	function getHierarchyRelatedData($data){
		if(!empty($data['hierarchyId'])){
			$baseEntitiesData = $this->getBaseEntityIdsByHierarchyId($data['hierarchyId']);
			foreach ($baseEntitiesData as $key => $val) {
				if(!in_array($val['stream_id'], $streamIdArray)){
					$streamIdArray[$key] = $val['stream_id'];
				}
				$streamIdArrayAll[$key] = $val['stream_id'];
			}
			$res = $this->getSubstreamSpecialisation($streamIdArray);
			foreach ($streamIdArray as $key => $streamId) {
				foreach ($res[$streamId]['substreams'] as $subStreamId => $value) {
				 	$substreamIds[$streamId][$subStreamId] = $value['name'];
				 	foreach ($value['specializations'] as $specializationId => $val) {
				 		$specializationsOfSubstreams[$subStreamId][$specializationId] = $val['name'];
				 	}
				 }
				 foreach ($res[$streamId]['specializations'] as $id => $value) {
				 	$specializationsOfStreams[$streamId][$id] = $value['name'];
				 }
			}
			foreach ($data['hierarchyId'] as $index => $hierarchyId) {
				if(!empty($streamIdArrayAll[$hierarchyId])){
					$finalViewArray['hierarchyView'][$hierarchyId]['hierarchyId'] = $hierarchyId;
					$finalViewArray['hierarchyView'][$hierarchyId]['substreams'] = $substreamIds[$baseEntitiesData[$hierarchyId]['stream_id']];
					if(!empty($baseEntitiesData[$hierarchyId]['substream_id'])){
						$finalViewArray['hierarchyView'][$hierarchyId]['specializations'] = $specializationsOfSubstreams[$baseEntitiesData[$hierarchyId]['substream_id']];
					}
					else{
						$finalViewArray['hierarchyView'][$hierarchyId]['specializations'] = $specializationsOfStreams[$baseEntitiesData[$hierarchyId]['stream_id']];
					}
					$finalViewArray['hierarchyView'][$hierarchyId]['selections'] = $baseEntitiesData[$hierarchyId];
				}
			}
		}
		return $finalViewArray;
	}

	function getCourseRelatedDataIndexedOnHierachyId($data){
		if(!empty($data['hierarchyId'])){
			$courses = $this->getBaseCoursesByHierarchyIds($data['hierarchyId']);
			foreach ($data['hierarchyId'] as $index => $hierarchyId) {
				$finalViewArray['courseView'][$hierarchyId]['selections'] = $data['courseId'][$hierarchyId];
				$finalViewArray['courseView'][$hierarchyId]['course'] = $courses[$hierarchyId];
			}
		}
		return $finalViewArray;
	}

	function getCourseCombinedData($data){
		if(!empty($data['hierarchyId'])){
			// $courses = $this->getBaseCoursesByHierarchyIds($data['hierarchyId']);
			// foreach ($courses as $key => $value) {
			// 	foreach ($value as $value => $val) {
			// 		 $res[] = $val;
			// 	}
			// }
			// $finalViewArray['courseView'][0]['course'] = $res;
			$finalViewArray['courseView'][0]['selections'] = $data['courseId'];
		}
		else{
			// $courses = $this->getAllBaseCourses();
			// $finalViewArray['courseView'][0]['course'] = $courses;
			$finalViewArray['courseView'][0]['selections'] = $data['courseId'];
		}
		return $finalViewArray;
	}

	function getAllBaseCourses($includeDummy = 0){
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$baseCourseRepo = $listingBase->getBaseCourseRepository();
		$result = $baseCourseRepo->getAllBaseCourses('array',$includeDummy);
		$commonFormLib = $this->load->library('common/commonFormLib');
		$fetch =  $commonFormLib->sortEntityByName(array('name'), $result);
		return $fetch;
	}

	function getCareerCmsData($params){
		$returnArray = array();
		$data = Modules::run('CareerProductEnterprise/CareerEnterprise/getCareerHierarchyData',$params['careerId']);
		foreach ($data as $key => $value) {
			if(!in_array($value['hierarchyId'], $result)){
				$result['hierarchyId'][$value['id']] = $value['hierarchyId'];
			}
			$result['courseId'][$value['hierarchyId']][] = $value['courseId'];
		}
		$res1 = $this->getHierarchyRelatedData($result);
		if(!empty($res1)){
			$returnArray += $res1;
			$res1 = '';
		}
		$res2 = $this->getCourseRelatedDataIndexedOnHierachyId($result);
		if(!empty($res2)){
			$returnArray += $res2;
			$res2 = '';
		}
		return $returnArray;
	}

	private function getExamCMSData($params){
		$returnArray = array();
		$examId = $params['examId'];
		$groupId = $params['groupId'];
		$this->load->model("examPages/exammainmodel");
		$examMain = new exammainmodel();
		$data = $examMain->getExamHierarchyData($examId, $groupId);
		$result = $this->getFormattedArray($data);
		$res1 = $this->getHierarchyRelatedData($result);
		if(!empty($res1)){
			$returnArray += $res1;
			$res1 = '';
			$returnArray['hierarchyView'][$result['primaryHierarchyId']]['primaryHierarchy'] = 1;
		}
		$res2 = $this->getCourseCombinedData($result);
		if(!empty($res2)){
			$returnArray += $res2;
			$res2 = '';
		}
		$res3 = $this->getInstituteData($result);
		if(!empty($res3)){
			$returnArray += $res3;
			$res3 = '';
		}
		$res4 = $this->getCareersMappedData($result);
		if(!empty($res4)){
			$returnArray += $res4;
			$res4 = '';
		}
		$res5 = $this->getOtherAttributes($result);
		if(!empty($res5)){
			$returnArray += $res5;
			$res5 = '';
		}
		return $returnArray;
	}

	function getArticleCmsData($params){
		$blogId = $params['blogId'];
		$status = $params['status'];
		$returnArray = array();
		$data = Modules::run('enterprise/Enterprise/getArticleHierarchyData',$blogId,$status);
		$result = $this->getFormattedArray($data);
		$res1 = $this->getHierarchyRelatedData($result);
		if(!empty($res1)){
			$returnArray += $res1;
			$res1 = '';
			$returnArray['hierarchyView'][$result['primaryHierarchyId']]['primaryHierarchy'] = 1;
		}
		$res2 = $this->getCourseCombinedData($result);
		if(!empty($res2)){
			$returnArray += $res2;
			$res2 = '';
		}
		$res3 = $this->getExamData($result);
		if(!empty($res3)){
			$returnArray += $res3;
			$res3 = '';
		}
		$res4 = $this->getPopularGroupingData($result);
		if(!empty($res4)){
			$returnArray += $res4;
			$res4 = '';
		}
		$res5 = $this->getInstituteData($result);
		if(!empty($res5)){
			$returnArray += $res5;
			$res5 = '';
		}
		$res6 = $this->getLocationData($result);
		if(!empty($res6)){
			$returnArray += $res6;
			$res6 = '';
		}
		$res7 = $this->getCareersMappedData($result);
		if(!empty($res7)){
			$returnArray += $res7;
			$res7 = '';
		}
		$res8 = $this->getOtherAttributes($result);
		if(!empty($res8)){
			$returnArray += $res8;
			$res8 = '';
		}
		$res9 = $this->getTagData($result);
		if(!empty($res9)){
			$returnArray += $res9;
			$res9 = '';
		}
		
		return $returnArray;
	}

	function getVideoCMSData($params){
		$returnArray = array();
		foreach ($params['videoMappings'] as $key => $value) {
			if(!in_array($value['mappingId'], $result)){
				if($value['mappingType']=='primaryHierarchy'){
                                        $result['primaryHierarchyId'] = $value['mappingId'];
                                }
				$result['hierarchyId'][] = $value['mappingId'];
			}
		}
		$res1 = $this->getHierarchyRelatedData($result);
		if(!empty($res1)){
			$returnArray += $res1;
			$res1 = '';
			$returnArray['hierarchyView'][$result['primaryHierarchyId']]['primaryHierarchy'] = 1;
		}
		foreach ($params['videoLocations'] as $key => $value) {
			if(!in_array($value['locationId'], $result)){
				$result['location'][$value['locationType']][$value['locationId']] = $value['locationId'];
			}
		}
		$res2 = $this->getLocationData($result);
		//_p($result);die;
		if(!empty($res2)){
			$returnArray += $res2;
			$res2 = '';
		}

		foreach ($params['videoTags'] as $key => $value) {
			if(!in_array($value['tagId'], $result)){
				$result['tagId'][$value['tagId']] = $value['tagId'];
			}
		}
		$res3 = $this->getTagData($result);
		if(!empty($res3)){
			$returnArray += $res3;
			$res3 = '';
		}
		return $returnArray;
	}

	function getLocationData($data){
		$finalViewArray['locationView'][0]['selections'] = $data['location'];
		return $finalViewArray;
	}

	function getInstituteData($data){
		$lib = $this->load->library('listingCommon/NationalListingEnterpriseLib');
		if(!empty($data['institute']['college'])){
			$instiArray['institute'] = $data['institute']['college'];
		}
		if(!empty($data['institute']['university'])){
			$instiArray['university'] = $data['institute']['university'];
		}
		if(!(empty($instiArray['institute']) && empty($instiArray['university']))){
			$res = $lib->getNameIdPair($instiArray);
		}
		$finalViewArray['instituteView'][0]['selections'] = $res;
		return $finalViewArray;
	}

	function getTagData($data){
		$this->load->library('Tagging/TaggingLib');
		$tagLib = new TaggingLib();
		$tagData = $tagLib->getTagNameById(implode(',', $data['tagId']));
		$finalViewArray['tagView'][0]['selections'] = $tagData;
		return $finalViewArray;
	}

	function getOtherAttributes($data){
		// $res = $this->getAllOtherAttributes();
		// $finalViewArray['otherAttributeView'][0]['otherAttribute'] = $res;
		$finalViewArray['otherAttributeView'][0]['selections'] = $data['otherAttributeId'];
		return $finalViewArray;
	}

	function getCareersMappedData($data){
		// $res = $this->getAllCareers();
		// $finalViewArray['careerView'][0]['career'] = $res;
		$finalViewArray['careerView'][0]['selections'] = $data['careerId'];
		return $finalViewArray;
	}


	function getAllCareers(){
		$this->load->builder('Careers/CareerBuilder');
		$careerBuilder = new CareerBuilder();
		$careerRepository = $careerBuilder->getCareerRepository();
		$fetch = $careerRepository->getCareerList('BASIC_INFO','array');
		return $fetch;
	}

	function getBaseCoursesByHierarchyIds($hierarchyId){
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$baseCourseRepo = $listingBase->getBaseCourseRepository();
		$fetch = $baseCourseRepo->getBaseCoursesByHierarchyIds($hierarchyId,1);
		return $fetch;
	}

	function getFormattedArray($data){
		$commonFormLib = $this->load->library('common/commonFormLib');
		$returnArray =  $commonFormLib->getFormattedArray($data);
		return $returnArray;
	}

	function getBaseEntityIdsByHierarchyId($hierarchyId,$getIdName=0){
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$hierarchyRepo = $listingBase->getHierarchyRepository();
		$baseEntities = $hierarchyRepo->getBaseEntitiesByHierarchyId($hierarchyId,$getIdName,'array');
		return $baseEntities;
	}

	function getHierarchiesByMultipleBaseEntities($baseEntities){
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$hierarchyRepo = $listingBase->getHierarchyRepository();
		
		$hierarchyIds = $hierarchyRepo->getHierarchiesByMultipleBaseEntities($baseEntities);
		return $hierarchyIds;
	}

	function getConfigData($configName){
		$common = $this->config->item('commonElements');
		$this->load->config('common/customConfig/'.$configName);
		$custom = $this->config->item('customElements');
		$commonFormLib = $this->load->library('common/commonFormLib');
		$finalConfig = $commonFormLib->getFinalConfig($common,$custom);
		$displayData['formElements'] = $finalConfig;
		$displayData['stream']       = $this->getAllStreams();
		$displayData['countHierarchy'] = 1;
		$displayData['redefineAddMorePostion'] = $finalConfig['redefineAddMorePostion'];
		$displayData['configName'] = $configName;
		return $displayData;
	}

	public function getHierarcyAndCoursesData($streamId, $subStreamId=0, $specId=0, $changedField='stream', $getCourses = 0, $selectedHierarchies = array(), $configName = ''){
		$data = array();
		if($this->input->is_ajax_request()){
			$streamId = isset($_POST['streamId']) && $_POST['streamId']!='' ? $this->input->post('streamId') : 0;
			$subStreamId = isset($_POST['subStreamId']) && $_POST['subStreamId']!='' ? $this->input->post('subStreamId') : 0;
			$specId = isset($_POST['specId']) && $_POST['specId']!='' ? $this->input->post('specId') : 0;
			$changedField = isset($_POST['changedField']) && $_POST['changedField']!='' ? $this->input->post('changedField') : 'stream';
			$getCourses = isset($_POST['getCourses']) && $_POST['getCourses']!='' ? $this->input->post('getCourses') : 0;
			$configName = isset($_POST['configName']) && $_POST['configName']!='' ? $this->input->post('configName') : '';

			$getDummy = 0;
			if($configName == 'examCMS')
			{
				$getDummy = 1;
			}

			$selectedHierarchies = array();
			if($configName != 'careerCms' && $configName != ''){
				$selectedHierarchies = isset($_POST['selectedHierarchies']) && $_POST['selectedHierarchies']!='' ? $this->input->post('selectedHierarchies') : array();
			}
		}
		if($streamId > 0 ){
			$hierarchyArr = array(array('streamId'=>$streamId, 'substreamId'=>$subStreamId, 'specializationId'=>$specId));
			$selectedHierarchies = $this->createHierarchyArr($selectedHierarchies);
			switch ($changedField) {
				case 'stream':
					$data = $this->getHierarcyDataByStream($streamId);
					if($getCourses==1 && $configName == 'careerCms'){
						$data['course'] = $this->getBaseCoursesByMultipleBaseEntities($hierarchyArr,$getDummy);
					}else if($getCourses==1){
						$data['course'] = $this->getBaseCoursesByMultipleBaseEntities($selectedHierarchies,$getDummy);
					}
					break;
				
				case 'subStream':
					$data = $this->getHierarcyDataByStreamAndSubstream($streamId, $subStreamId);
					if($getCourses==1 && $configName == 'careerCms'){
						$data['course'] = $this->getBaseCoursesByMultipleBaseEntities($hierarchyArr,$getDummy);
					}else if($getCourses==1){
						$data['course'] = $this->getBaseCoursesByMultipleBaseEntities($selectedHierarchies,$getDummy);
					}
					break;

				case 'specialization':
					if($getCourses==1 && $configName == 'careerCms'){
						$data['course'] = $this->getBaseCoursesByMultipleBaseEntities($hierarchyArr,$getDummy);
					}else if($getCourses==1){
						$data['course'] = $this->getBaseCoursesByMultipleBaseEntities($selectedHierarchies,$getDummy);
					}
					break;	
			}
		$data['course'] =  array_flip($data['course']);
		}
		echo json_encode($data);
	}

	private function createHierarchyArr($input){
		$output = array();
		$baseEntities = array('streamId', 'substreamId', 'specializationId');
		foreach ($input as $key => $value) {
			foreach ($value as $k => $val) {
				if($val != '' && $val > 0){
					$output[$k][$baseEntities[$key]] = $val;
				}
			}
		}
		return $output;
	}

	public function getAllStreams(){
		$data = array();
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$hierarchyRepo = $listingBase->getHierarchyRepository();
		$stream = $hierarchyRepo->getAllStreams();
		$data['stream'] = $stream;
		return $data;
	}

	public function getHierarcyDataByStream($streamId){
		$data = array();
		$fetch = $this->getSubstreamSpecialisation($streamId);
		$data['subStream']      = $fetch[$streamId]['substreams'];
		$data['specialization'] = $fetch[$streamId]['specializations'];
		return $data;
	}

	function getSubstreamSpecialisation($streamId){
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$hierarchyRepo = $listingBase->getHierarchyRepository();
		$fetch = $hierarchyRepo->getSubstreamSpecializationByStreamId($streamId, 1);
		return $fetch;
	}

	public function getHierarcyDataByStreamAndSubstream($streamId, $subStreamId){
		$data = array();
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$hierarchyRepo = $listingBase->getHierarchyRepository();
		$fetch = $hierarchyRepo->getSpecializationTreeByStreamSubstreamId($streamId, $subStreamId, 1);
		$data['specialization'] = $fetch[$streamId]['substreams'][$subStreamId]['specializations'];
		return $data;
	}

	public function getBaseCoursesByMultipleBaseEntities($hierarchyArr,$includeDummy = 0){
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$baseCourseRepo = $listingBase->getBaseCourseRepository();
		$fetch = $baseCourseRepo->getBaseCoursesByMultipleBaseEntities($hierarchyArr, 1,'array',$includeDummy);
		return $fetch;
	}

	public function addMoreBlockGenerate($countHierarchy,$configName){
		$countHierarchy = isset($_POST['countHierarchy']) ? $_POST['countHierarchy'] : 1;
		$configName = isset($_POST['configName']) ? $_POST['configName'] : '';
		$displayData = $this->getConfigData($configName);
		$displayData['countHierarchy'] = $countHierarchy;
		$displayData['fromAddMoreButton'] = true;
		echo json_encode(array('html'=>$this->load->view('common/hierarchyForms/formFields',$displayData,true)));
	}

	function getFormLocationListForVCMS(){
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder();
		$locationRepository = $locationBuilder->getLocationRepository();
		$html = '';
		$containerId = $this->input->post('containerId');
		// prepare state list
		$states = $locationRepository->getStatesByCountry(2);
		global $EXCLUDED_STATES_IN_LOCATION_LAYER;
		$html = '<div class="loc-title ctNH">Country</div>';
		$data['Id'] = '2';
		$data['Name'] = 'India'; 
		$data['containerId'] = ($containerId) ? $containerId.'-ctN' :'ctN';
		$data['className'] = 'ctN';
		$html .= $this->load->view('common/hierarchyForms/innerLocationList',$data,true);
		foreach($states as $state){
	    	if(in_array($state->getId(),$EXCLUDED_STATES_IN_LOCATION_LAYER)){//Hiding Delhi State
		    continue;
	    	}
		    $stateListArray['stateNames'][$i]['Name'] = $state->getName();
		    $stateListArray['stateNames'][$i]['Id'] = $state->getId();
		    $i++;   
		}
		$html .= '<div class="loc-title sNH">State</div>';
		foreach( $stateListArray['stateNames'] as $key=>$value){ 
			$data['Id'] = $value['Id'];
			$data['Name'] = $value['Name']; 
			$data['containerId'] = ($containerId) ? $containerId.'-sN' :'sN';
			$data['className'] = 'sN';
			$html .= $this->load->view('common/hierarchyForms/innerLocationList',$data,true);
		}
		$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
		foreach($cityList[1] as $city){
		    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
		    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
		    $topCityListArray['cityNames'][$i]['Name'] = $city->getName();
		    $topCityListArray['cityNames'][$i]['Id'] = $city->getId();
		    $i++;
		}
		// prepare popular list
		$html .= '<div class="loc-title pLH">Popular Location</div>';
		foreach( $topCityListArray['cityNames'] as $key=>$value)
		{ 
			$data['Id'] = $value['Id'];
			$data['Name'] = $value['Name'];
			$data['containerId'] = ($containerId) ? $containerId.'-cN' :'cN';
			$data['className'] = 'pL';
			$html .= $this->load->view('common/hierarchyForms/innerLocationList',$data,true);
		}

		foreach($cityList[2] as $city){
		    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
		    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
		    $otherCityListArray[$city->getName()][$i]['Name'] = $city->getName();
		    $otherCityListArray[$city->getName()][$i]['Id'] = $city->getId();
		    $i++;
		}

		foreach($cityList[3] as $city){
		    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
		    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
		    $otherCityListArray[$city->getName()][$i]['Name'] = $city->getName();
		    $otherCityListArray[$city->getName()][$i]['Id'] = $city->getId();
		    $i++;
		} 
		ksort($otherCityListArray);

		// prepare city list
		$html .= '<div class="loc-title cNH">City</div>';
		foreach( $otherCityListArray as $key=>$value){
		 	foreach($value as $value){
		 		$data['Id'] = $value['Id'];
				$data['Name'] = $value['Name']; 
				$data['containerId'] = ($containerId) ? $containerId.'-cN' :'cN';
				$data['className'] = 'cN';
				$html .= $this->load->view('common/hierarchyForms/innerLocationList',$data,true);
			}
		}
		echo json_encode(array('html'=>$html));

	}
    // added by akhter
	function getFormLocationList(){
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder();
		$locationRepository = $locationBuilder->getLocationRepository();
		$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
		$countryList = $locationRepository->getCountries();

		$containerId = $this->input->post('containerId');

	    foreach($cityList[1] as $city){
		    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
		    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
		    $topCityListArray['cityNames'][$i]['Name'] = $city->getName();
		    $topCityListArray['cityNames'][$i]['Id'] = $city->getId();
		    $i++;
		}
		// prepare popular list
		$html = '<div class="loc-title pLH">Popular Location</div>';
		foreach( $topCityListArray['cityNames'] as $key=>$value)
		{ 
			$data['Id'] = $value['Id'];
			$data['Name'] = $value['Name'];
			$data['containerId'] = ($containerId) ? $containerId.'-cN' :'cN';
			$data['className'] = 'pL';
			$html .= $this->load->view('common/hierarchyForms/innerLocationList',$data,true);
		}

		foreach($cityList[2] as $city){
		    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
		    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
		    $otherCityListArray[$city->getName()][$i]['Name'] = $city->getName();
		    $otherCityListArray[$city->getName()][$i]['Id'] = $city->getId();
		    $i++;
		}

		foreach($cityList[3] as $city){
		    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
		    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
		    $otherCityListArray[$city->getName()][$i]['Name'] = $city->getName();
		    $otherCityListArray[$city->getName()][$i]['Id'] = $city->getId();
		    $i++;
		} 
		ksort($otherCityListArray);

		// prepare city list
		$html .= '<div class="loc-title cNH">City</div>';
		foreach( $otherCityListArray as $key=>$value){
		 	foreach($value as $value){
		 		$data['Id'] = $value['Id'];
				$data['Name'] = $value['Name']; 
				$data['containerId'] = ($containerId) ? $containerId.'-cN' :'cN';
				$data['className'] = 'cN';
				$html .= $this->load->view('common/hierarchyForms/innerLocationList',$data,true);
			}
		}
		// prepare state list
		$states = $locationRepository->getStatesByCountry(2);
		global $EXCLUDED_STATES_IN_LOCATION_LAYER;
		foreach($states as $state){
	    	if(in_array($state->getId(),$EXCLUDED_STATES_IN_LOCATION_LAYER)){//Hiding Delhi State
		    continue;
	    	}
		    $stateListArray['stateNames'][$i]['Name'] = $state->getName();
		    $stateListArray['stateNames'][$i]['Id'] = $state->getId();
		    $i++;   
		}
		$html .= '<div class="loc-title sNH">State</div>';
		foreach( $stateListArray['stateNames'] as $key=>$value){ 
			$data['Id'] = $value['Id'];
			$data['Name'] = $value['Name']; 
			$data['containerId'] = ($containerId) ? $containerId.'-sN' :'sN';
			$data['className'] = 'sN';
			$html .= $this->load->view('common/hierarchyForms/innerLocationList',$data,true);
		}

		// prepare country list
		foreach ($countryList as $country) {
			$countryListArr['countryNames'][$i]['Name'] = $country->getName();
		    $countryListArr['countryNames'][$i]['Id'] = $country->getId();
		    $i++;  
		}
		$html .= '<div class="loc-title ctNH">Country</div>';
		foreach( $countryListArr['countryNames'] as $key=>$value){ 
			$data['Id'] = $value['Id'];
			$data['Name'] = $value['Name']; 
			$data['containerId'] = ($containerId) ? $containerId.'-ctN' :'ctN';
			$data['className'] = 'ctN';
			$html .= $this->load->view('common/hierarchyForms/innerLocationList',$data,true);
		}
		echo json_encode(array('html'=>$html));
	}

	public function getFormBaseCourseList(){
		$case = isset($_POST['action']) && $_POST['action']!='' ? $this->input->post('action') : 'add';
		$selectedHierarchies = isset($_POST['selectedHierarchies']) && $_POST['selectedHierarchies']!='' ? $this->input->post('selectedHierarchies') : array();

		$configName = isset($_POST['configName']) && $_POST['configName']!='' ? $this->input->post('configName') : '';

		$getDummy = 0;

		if($configName == 'examCMS')
		{
			$getDummy = 1;
		}

		$selectedHierarchies = $this->createHierarchyArr($selectedHierarchies);
		if($case == 'edit' && !empty($selectedHierarchies)){
			$courseData = $this->getBaseCoursesByMultipleBaseEntities($selectedHierarchies,$getDummy);
			$courseData = array_flip($courseData);
			$prefilledData = array();
			foreach ($courseData as $name => $crsId) {
				$prefilledData[] = array('id'=>$crsId, 'name'=>$name);
			}
			$data['prefilledData'] = $prefilledData;
		}else{
			$prefilledData = $this->getAllBaseCourses($getDummy);
			$commonFormLib = $this->load->library('common/commonFormLib');
			$data['prefilledData'] =  $commonFormLib->sortEntityByName(array('name'), $prefilledData);
		}
		$containerId = $this->input->post('containerId');
		$data['containerId'] = $containerId;
		$html = $this->load->view('common/hierarchyForms/searchContainerInner', $data, true);
		echo json_encode(array('html'=>$html));
	}

	public function getFormExamList(){
		$data = array();
		$case = isset($_POST['action']) && $_POST['action']!='' ? $this->input->post('action') : 'add';
		if($case == 'edit'){
			$selectedHierarchies = isset($_POST['selectedHierarchies']) && $_POST['selectedHierarchies']!='' ? $this->input->post('selectedHierarchies') : array();
			$selectedHierarchies = $this->createHierarchyArr($selectedHierarchies);
			$this->load->library("examPages/ExamMainLib");
			$examMainLib = new ExamMainLib();
			$examList = $examMainLib->getAllMainExamsByBaseEntities($selectedHierarchies);
		}else{
			$examList = $this->getAllExams();
		}
		$examList = array_flip($examList);
		foreach ($examList as $name => $id) {
			$data['prefilledData'][] = array('id'=>$id, 'name'=>$name);
		}
		$containerId = $this->input->post('containerId');
		$data['containerId'] = $containerId;
		$html = $this->load->view('common/hierarchyForms/searchContainerInner', $data, true);
		echo json_encode(array('html'=>$html));
	}

	public function getFormCareerList(){
		$data['prefilledData'] = $this->getAllCareers();
		$containerId = $this->input->post('containerId');
		$data['containerId'] = $containerId;
		$html = $this->load->view('common/hierarchyForms/searchContainerInner', $data, true);
		echo json_encode(array('html'=>$html));
	}

	public function getFormPopularGroupingList(){
		$case = isset($_POST['action']) && $_POST['action']!='' ? $this->input->post('action') : 'add';
		if($case == 'edit'){
			$selectedHierarchies = isset($_POST['selectedHierarchies']) && $_POST['selectedHierarchies']!='' ? $this->input->post('selectedHierarchies') : array();
			$selectedHierarchies = $this->createHierarchyArr($selectedHierarchies);
			$hierarchyIds = array();
			$hierarchyIdArr = $this->getHierarchiesByMultipleBaseEntities($selectedHierarchies);
			foreach ($hierarchyIdArr as $value) {
				$hierarchyIds[] = $value['hierarchy_id'];
			}
			$data['prefilledData'] = array();
			$popGrps = $this->getPopularGroupsByHierarchyIds($hierarchyIds);
			foreach ($popGrps as $hierId => $groupings) {
				foreach ($groupings as $key => $grouping) {
					$data['prefilledData'][] = array(
						'name' =>  $grouping['name'],
						'id' => $grouping['id']
						);
				}
			}
			//ksort($data['prefilledData']);
			$commonFormLib = $this->load->library('common/commonFormLib');
			$data['prefilledData'] =  $commonFormLib->sortEntityByName(array('name'), $data['prefilledData']);
		}else{
			$data['prefilledData'] = $this->getAllPopularGroups();
		}
		$containerId = $this->input->post('containerId');
		$data['containerId'] = $containerId;
		$html = $this->load->view('common/hierarchyForms/searchContainerInner', $data, true);
		echo json_encode(array('html'=>$html));
	}

	public function getFormOtherAttrList(){
		$containerId = $this->input->post('containerId');
		$data['containerId'] = $containerId;
		$this->load->config('common/hierarchyElementMapping');
		$otherAttributes = $this->config->item('otherAttributes');
		$html = '';
		foreach ($otherAttributes as $key => $attrData) {
			if(!empty($attrData['value'])){
				$html .= '<div class="loc-title '.$key.'H">'.$attrData['label'].'</div>';
				$data['prefilledData'] = $attrData['value'];
				$data['liClassName'] = $key;
				$html .= $this->load->view('common/hierarchyForms/searchContainerInner', $data, true);
			}
		}
		echo json_encode(array('html'=>$html));
	}

	public function getHierarcyBasedAtrributes(){
		$data = array();
		$configName = isset($_POST['configName']) && $_POST['configName']!='' ? $this->input->post('configName') : '';
		$selectedHierarchies = array();
		if($configName != 'careerCms' && $configName != ''){
			$selectedHierarchies = isset($_POST['selectedHierarchies']) && $_POST['selectedHierarchies']!='' ? $this->input->post('selectedHierarchies') : array();
			$attrArr = isset($_POST['attrArr']) && $_POST['attrArr']!='' ? $this->input->post('attrArr') : '';
			$selectedHierarchies = $this->createHierarchyArr($selectedHierarchies);
			if(!empty($selectedHierarchies)){
				if(in_array('baseCourse', $attrArr)){

					$getDummy = 0;
					if($configName == 'examCMS')
					{
						$getDummy = 1;
					}

					$data['course'] = $this->getBaseCoursesByMultipleBaseEntities($selectedHierarchies,$getDummy);
					$data['course'] = array_flip($data['course']);
				}
				if(in_array('exam', $attrArr)){
					$this->load->library("examPages/ExamMainLib");
					$examMainLib = new ExamMainLib();
					$data['exam'] = $examMainLib->getAllMainExamsByBaseEntities($selectedHierarchies);
					$data['exam'] = array_flip($data['exam']);
				}
				if(in_array('popularGrouping', $attrArr)){
					$hierarchyIds = array();
					$hierarchyIdArr = $this->getHierarchiesByMultipleBaseEntities($selectedHierarchies);
					foreach ($hierarchyIdArr as $value) {
						$hierarchyIds[] = $value['hierarchy_id'];
					}
					$popGrps = $this->getPopularGroupsByHierarchyIds($hierarchyIds);
					foreach ($popGrps as $hierId => $groupings) {
						foreach ($groupings as $key => $grouping) {
							$data['popularGrouping'][$grouping['name']] = $grouping['id'];
						}
					}
					ksort($data['popularGrouping']);
				}
			}else{
				//get all courses, exams and popular groupings
				if(in_array('baseCourse', $attrArr)){
					$getDummy = 0;
					if($configName == 'examCMS')
					{
						$getDummy = 1;
					}
					$courses = $this->getAllBaseCourses($getDummy);
					foreach ($courses as $key => $value) {
						$data['course'][$value['name']] = $value['id'];
					}
				}
				if(in_array('exam', $attrArr)){
					$examList = $this->getAllExams();
					$data['exam'] = array_flip($examList);
				}
				if(in_array('popularGrouping', $attrArr)){
					$popGrps = $this->getAllPopularGroups();
					foreach ($popGrps as $key => $value) {
						$data['popularGrouping'][$value['name']] = $value['id'];
					}
				}
			}
		}
		echo json_encode($data);
	}
}
