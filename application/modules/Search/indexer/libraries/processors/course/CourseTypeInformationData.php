<?php 

require_once dirname(__FILE__).'/../DataAbstract.php';

class CourseTypeInformationData extends DataAbstract {
	

	public function __construct()
	{
		$this->_CI = & get_instance();
		$this->_CI->load->builder("listingBase/ListingBaseBuilder");
		$baseCourseBuilder = new ListingBaseBuilder();
		$this->baseCourseRepo = $baseCourseBuilder->getBaseCourseRepository();
		$this->certificateProviderRepo = $baseCourseBuilder->getCertificateProviderRepository();
		$this->popGrpRepo = $baseCourseBuilder->getPopularGroupRepository();
		$this->streamRepo = $baseCourseBuilder->getStreamRepository();
		$this->subStreamRepo = $baseCourseBuilder->getSubstreamRepository();
		$this->specRepo = $baseCourseBuilder->getSpecializationRepository();

		$this->_CI->load->library('listingBase/BaseAttributeLibrary');
		$this->baseAttributeLibrary = new BaseAttributeLibrary;

		$this->_CI->load->config('indexer/nationalIndexerConfig');
	}


	public function _getDataFromObject($courseObject)
	{	
		$courseTypeInformation = $courseObject->getCourseTypeInformation();
		
		if(isset($courseTypeInformation) && !empty($courseTypeInformation)){

			// Fetching Data From Object
			if(isset($courseTypeInformation['entry_course'])){
				$entryCourse = $courseTypeInformation['entry_course'];
			}
			if(isset($courseTypeInformation['exit_course'])){
				$exitCourse = $courseTypeInformation['exit_course'];
			}

			// Check if EXIT COURSES ARE NEEDED OR NOT(BASED ON COURSE LEVEL)
			if(isset($exitCourse) && !empty($exitCourse)){
				$exitCourseLevel = $exitCourse->getCourseLevel();
				$entryCourseLevel = $entryCourse->getCourseLevel();
				if($exitCourseLevel != $entryCourseLevel){
					unset($courseTypeInformation[exit_course]);
				}
			}
			return $courseTypeInformation;

		}else{

			return null;
		}
	}

	public function _processData($courseTypeInformation){

		if(isset($courseTypeInformation) && !empty($courseTypeInformation)){
			if(isset($courseTypeInformation['entry_course']) && !empty($courseTypeInformation['entry_course'])){
				$entryCourseHierarchyData = $this->_processEntryCourseData($courseTypeInformation['entry_course']);
			}
			if(isset($courseTypeInformation['exit_course']) && !empty($courseTypeInformation['exit_course'])){
				$exitCourseHierarchyData = $this->_processExitCourseData($courseTypeInformation['exit_course']);	
			}
			if(isset($exitCourseHierarchyData) && isset($entryCourseHierarchyData)){
				$finalData = array_merge($entryCourseHierarchyData, $exitCourseHierarchyData);	
			}else{
				$finalData = $entryCourseHierarchyData;
			}
			return $finalData;	
		}else{
			return null;
		}
			

		
	}

	public function _processEntryCourseData($courseTypeInformationData){
		$entryCourseHierarchyData =  $this->_processCourseHierarchyData($courseTypeInformationData,'entry');
		return $entryCourseHierarchyData;
	}

	public function _processExitCourseData($courseTypeInformationData){
		if(empty($courseTypeInformationData) || !isset($courseTypeInformationData)){
			return null;
		}
		$exitCourseHierarchyData =  $this->_processCourseHierarchyData($courseTypeInformationData,'exit');
		return $exitCourseHierarchyData;
	}

	public function _processCourseHierarchyData($courseTypeInformationData,$type='entry'){

		$finalCourseTypeInfo = array();
		//Get Base Course Data

		$baseCourseInfo = $this->_getBaseCourseInfo($courseTypeInformationData);

		// Get Credentials
		$credentialInfo = $this->_getCredentialsInfo($courseTypeInformationData);

		// Get Course Levels
		$courseLevelInfo = $this->_getCourseLevelInfo($courseTypeInformationData);

		// Get Certificate Providers
		$certificateProviderInfo = $this->_fetchCertificateProvidersInfo($baseCourseInfo);

		// fetch Popular Groups
		$hierarcies = $courseTypeInformationData->getHierarchies();

		$popularGroupsInfo = $this->_fetchPopularGroupsInfo($hierarcies);

		// Fetch Entities Name,Synonyms(Stream, SUbstream, Specizations);
		$entityData = $this->_fetchEntitiesInfo($hierarcies);

		// Combine All Data
		$hierarchyData = $this->_combineAllHierarchyData($hierarcies,$baseCourseInfo,$credentialInfo,$courseLevelInfo, $certificateProviderInfo, $popularGroupsInfo,$entityData,$type);
		return $hierarchyData;
	}


	// Fetch Base Course Data
	private function _getBaseCourseInfo($courseTypeInformationData){
		$baseCourseInfo['id'] = $courseTypeInformationData->getBaseCourse();
		if($baseCourseInfo['id'] > 0) {
			$baseCourseObj = $this->baseCourseRepo->find($baseCourseInfo['id']);
			if($baseCourseObj == null) return null;
			$baseCourseInfo['name'] = $baseCourseObj->getName();
			$synonms = $baseCourseObj->getSynonym();
			$baseCourseInfo['synonms'] = explode(BASE_COURSE_SYN_DEL, $synonms);
			$baseCourseInfo['synonms'] = array_filter($baseCourseInfo['synonms']);
			return $baseCourseInfo;	
		}else{
			$baseCourseInfo['id'] = 0;
			return $baseCourseInfo;	
		}
		
	}

	// Fetch Credentials Data
	private function _getCredentialsInfo($courseTypeInformationData){
		$credentialInfo['id'] = $courseTypeInformationData->getCredential()->getId();
		$credentialInfo['name'] = $courseTypeInformationData->getCredential()->getName();

		if($credentialInfo['id'] == NONE_CREDENTIAL){
			$credentialInfo['id'] = "";
			$credentialInfo['name'] = "";
		}
		return $credentialInfo;
	}

	// Fetch Course Level Data
	private function _getCourseLevelInfo($courseTypeInformationData){
		$courseLevelInfo['id'] = $courseTypeInformationData->getCourseLevel()->getId();
		$courseLevelInfo['name'] = $courseTypeInformationData->getCourseLevel()->getName();

		if($courseLevelInfo['id'] == NONE_COURSE_LEVEL){
			$courseLevelInfo['id'] = "";
			$courseLevelInfo['name'] = "";
		}
		return $courseLevelInfo;	
	}

	// Fetch Certificate Providers Data
	private function _fetchCertificateProvidersInfo($baseCourseInfo){
		if(isset($baseCourseInfo) && !empty($baseCourseInfo)){
			$baseCourse = $baseCourseInfo['id'];

			// Fetch certificate Providers for base courses
			$certificateProviderIds = $this->certificateProviderRepo->getCertProvidersByBaseCourses(array($baseCourse));
			
			$ids = array();
			foreach ($certificateProviderIds as $key => $value) {
				foreach ($value as $cpId) {
					$ids[] = $cpId['id'];
				}
				
			}
			if(empty($ids)) return null;
			// Fetch The names of al Certificate Providers
			$certificateProviderInfo = $this->certificateProviderRepo->findMultiple($ids);
			
			$certificateProviderInfoFinal = array();

			// Generate Final Data
			foreach ($certificateProviderInfo as $key => $value) {
				$certificateProviderInfoFinal[$key]['id'] = $value->getCertificationProviderId();
				$certificateProviderInfoFinal[$key]['name'] = $value->getName();
				$synonms = $value->getSynonym();
				$synonms = explode(CERT_PROV_SYN_DEL, $synonms);
				$synonms = array_filter($synonms);
				$certificateProviderInfoFinal[$key]['synonms'] = $synonms;
			}
			return $certificateProviderInfoFinal;
		}else{
			return null;
		}
		
		
	}

	// Fetch Popular Groups Data
	private function _fetchPopularGroupsInfo($hierarcies){
		$inputArray = array();
		foreach ($hierarcies as $key => $value) {

			$tempData = array();
			$tempData['streamId'] = $value['stream_id'];
			$tempData['substreamId'] = $value['substream_id'];
			$tempData['specializationId'] = $value['specialization_id'];

			if($tempData['substreamId'] == 0){
				$tempData['substreamId'] = 'none';
			}
			if($tempData['specializationId'] == 0){
				$tempData['specializationId'] = 'none';
			}
			$inputArray[] = $tempData;

		}
		
		// Fetch Popular Groups Based on Hierarchy
		$popGrpData = $this->popGrpRepo->getPopularGroupsByMultipleBaseEntities($inputArray);
		$popGrp = array();

		// Combile All Popular Groups in An Array
		foreach ($popGrpData as $streamSubSpecKey => $popGrpArray) {
			foreach ($popGrpArray as $popGrpArrayInner) {
				$popGrp[] = $popGrpArrayInner['popular_group_id'];
			}
		}
		
		if(!empty($popGrp)){
			$popGrpUpdated = $this->popGrpRepo->findMultiple($popGrp);
		}else{
			$popGrpUpdated = array();
		}
		// Calling The Repo to get the Popular Grp Name / Synonyms
		

		// Populating Name/Syn in the main array
		foreach ($popGrpData as $streamSubSpecKey => $popGrpArray) {
			foreach ($popGrpArray as $popGrpArrayInnerKey=>$popGrpArrayInner) {
				$popularGroupId = $popGrpArrayInner['popular_group_id'];
				$popularGroupName = $popGrpUpdated[$popularGroupId]->getName();				
				$synonms = $popGrpUpdated[$popularGroupId]->getSynonym();
				$synonms = explode(POP_GRP_SYN_DEL, $synonms);
				$synonms = array_filter($synonms);

				$popGrpData[$streamSubSpecKey][$popGrpArrayInnerKey]['popular_group_name'] = $popularGroupName;
				$popGrpData[$streamSubSpecKey][$popGrpArrayInnerKey]['popular_group_synonym'] = $synonms;
			}
		}
		return $popGrpData;
		
	}

	private function _fetchEntitiesInfo($hierarcies = array()){
		$streams = array();
		$substreams = array();
		$specizations = array();

		// STore all Stream, Substream, Specializations whose names to be fetched
		foreach ($hierarcies as $key => $value) {
			$streams[$value['stream_id']] = $value['stream_id'];
			$substreams[$value['substream_id']] = $value['substream_id'];
			$specizations[$value['specialization_id']] = $value['specialization_id'];
		}

		$streams = array_unique(array_filter(array_unique($streams)));
		$substreams = array_unique(array_filter(array_unique($substreams)));
		$specizations = array_unique(array_filter(array_unique($specizations)));

		// Fetch Streams Info
		if(!empty($streams)){
			$streamsInfo = $this->streamRepo->findMultiple($streams);
			foreach ($streamsInfo as $key => $value) {
				$streams[$key] = array();
				$streams[$key]['name'] = $value->getName();
				$synonms = $value->getSynonym();
				$synonms = explode(STREAM_SYN_DEL, $synonms);
				$synonms = array_filter($synonms);
				$streams[$key]['synonms'] = $synonms;
			}
		}

		// Fetch Substreams Info
		if(!empty($substreams)){
			$substreamsInfo = $this->subStreamRepo->findMultiple($substreams);
			foreach ($substreamsInfo as $key => $value) {
				$substreams[$key] = array();
				$substreams[$key]['name'] = $value->getName();
				$synonms = $value->getSynonym();
				$synonms = explode(STREAM_SYN_DEL, $synonms);
				$synonms = array_filter($synonms);
				$substreams[$key]['synonms'] = $synonms;
			}
		}

		// Fetch Specialization Info
		if(!empty($specizations)){
			$specizationsInfo = $this->specRepo->findMultiple($specizations);
			foreach ($specizationsInfo as $key => $value) {
				$specizations[$key] = array();
				$specizations[$key]['name'] = $value->getName();
				$synonms = $value->getSynonym();
				$synonms = explode(STREAM_SYN_DEL, $synonms);
				$synonms = array_filter($synonms);
				$specizations[$key]['synonms'] = $synonms;
			}
		}
		
		// Generate Final Result
		$result =array();
		$result['streams'] = $streams;
		$result['substreams'] = $substreams;
		$result['specizations'] = $specizations;

		return $result;
	}

	private function _combineAllHierarchyData($hierarcies,$baseCourseInfo=array(), $credentialInfo=array() ,$courseLevelInfo = array(), $certificateProviderInfo = array(), $popularGroupsInfo = array(), $entityData = array(),$type='entry'){

		foreach ($hierarcies as $key => $hierarchyData) {


			// Add Stream, SUbstream , specilization with nl_ keys
			$hierarcies[$key]['nl_stream_id'] = $streamId = $hierarchyData['stream_id'];
			$hierarcies[$key]['nl_substream_id'] = $substreamId = $hierarchyData['substream_id'];
			$hierarcies[$key]['nl_specialization_id'] = $specializationId = $hierarchyData['specialization_id'];

			
			// Add Entity Names, Synonyms to Main Data
			$streamName = isset($entityData['streams'][$streamId]) ? $entityData['streams'][$streamId]['name'] : "";
			$substreamName = isset($entityData['substreams'][$substreamId]) ? $entityData['substreams'][$substreamId]['name'] : "";
			$specializationName = isset($entityData['specizations'][$specializationId]) ? $entityData['specizations'][$specializationId]['name'] : "";

			$streamSyn = isset($entityData['streams'][$streamId]) ? $entityData['streams'][$streamId]['synonms'] : "";
			$substreamSyn = isset($entityData['substreams'][$substreamId]) ? $entityData['substreams'][$substreamId]['synonms'] : "";
			$specializationSyn = isset($entityData['specizations'][$specializationId]) ? $entityData['specizations'][$specializationId]['synonms'] : "";


			$hierarcies[$key]['nl_stream_name'] = $streamName;
			$hierarcies[$key]['nl_substream_name'] = $substreamName;
			$hierarcies[$key]['nl_specialization_name'] = $specializationName;
			$hierarcies[$key]['nl_stream_synonyms'] = $streamSyn;
			$hierarcies[$key]['nl_substream_synonyms'] = $substreamSyn;
			$hierarcies[$key]['nl_specialization_synonyms'] = $specializationSyn;

			if($streamName != ""){
				$hierarcies[$key]['nl_stream_name_id_map'] = $streamName.":".$streamId;
			}
			if($substreamName != ""){
				$hierarcies[$key]['nl_substream_name_id_map'] = $substreamName.":".$substreamId;
			}
			if($specializationName != ""){
				$hierarcies[$key]['nl_specialization_name_id_map'] = $specializationName.":".$specializationId;
			}

			// Substream + Specualization Maps
			if($substreamName != "" || $specializationName != ""){
				$subSpecMap = "";
				if($substreamName != ""){
					$subSpecMap = $substreamName.":".$substreamId."::";
				}else{
					$subSpecMap = " "."::";
				}

				if($specializationName != ""){
					$subSpecMap .= $specializationName.":".$specializationId;
				}else{
					$subSpecMap .= " ";
				}
				$hierarcies[$key]['nl_substream_specialization_name_id_map'] = $subSpecMap;
			}

			// Add Base Course
			$hierarcies[$key]['nl_base_course_id'] = $baseCourseInfo['id'];
			if($hierarcies[$key]['nl_base_course_id'] > 0){
				$hierarcies[$key]['nl_base_course_name'] = $baseCourseInfo['name'];
				$hierarcies[$key]['nl_base_course_name_id_map'] = $baseCourseInfo['name'].":".$baseCourseInfo['id'];
				$hierarcies[$key]['nl_base_course_synonyms'] = $baseCourseInfo['synonms'];
			}
			

			// Add Credentials
			if(!empty($credentialInfo['id'])){
				$hierarcies[$key]['nl_course_credential_id'] = $credentialInfo['id'];
				$hierarcies[$key]['nl_course_credential_name'] = $credentialInfo['name'];
				$hierarcies[$key]['nl_course_credential_name_id_map'] = $credentialInfo['name'].":".$credentialInfo['id'];	
			}
			
			// Add Course Levels
			if(!empty($courseLevelInfo['id'])){
				$hierarcies[$key]['nl_course_level_id'] = $courseLevelInfo['id'];
				$hierarcies[$key]['nl_course_level_name'] = $courseLevelInfo['name'];
				$hierarcies[$key]['nl_course_level_name_id_map'] = $courseLevelInfo['name'].":".$courseLevelInfo['id'];	
			}

			// Course level & credentials Combine fields
			if(!empty($courseLevelInfo['id']) || !empty($credentialInfo['id'])){
					$hierarcies[$key]['nl_course_level_credential_name'] = trim($courseLevelInfo['name']." ".$credentialInfo['name']);
					$hierarcies[$key]['nl_course_level_credential_id'] = trim($courseLevelInfo['id'].":".$credentialInfo['id']);
					$hierarcies[$key]['nl_course_level_credential_name_id_map'] = trim($courseLevelInfo['name']." ".$credentialInfo['name']."::".$courseLevelInfo['id'].":".$credentialInfo['id']);
			}

			
			// Add Certificate Providers
			$finalCerticateProviders = array();
			$finalCerticateProviders['synonms'] = array();
			foreach ($certificateProviderInfo as $certificateProviderData) {
				$finalCerticateProviders['ids'][] = $certificateProviderData['id'];
				$finalCerticateProviders['name'][] = $certificateProviderData['name'];
				$finalCerticateProviders['nameIdMap'][] = $certificateProviderData['name'].":".$certificateProviderData['id'];
				$finalCerticateProviders['synonms'] = array_unique(array_merge($finalCerticateProviders['synonms'],$certificateProviderData['synonms']));
			}
			$hierarcies[$key]['nl_certificate_provider_id'] = $finalCerticateProviders['ids'];
			$hierarcies[$key]['nl_certificate_provider_name'] = $finalCerticateProviders['name'];
			$hierarcies[$key]['nl_certificate_provider_synonyms'] = $finalCerticateProviders['synonms'];
			$hierarcies[$key]['nl_certificate_provider_name_id_map'] = $finalCerticateProviders['nameIdMap'];

			// Add Popular Groups
			$finalPopularGroups = array();
			$finalPopularGroups['synonms'] = array();

			$hierarchyKey = $streamId."_".$substreamId."_".$specializationId;
			if(isset($popularGroupsInfo[$hierarchyKey])){
				$popularGroupsForCurrentHierarcy = $popularGroupsInfo[$hierarchyKey];
				foreach ($popularGroupsForCurrentHierarcy as $popularGroupsForCurrentHierarcyInner) {
					$finalPopularGroups['ids'][] = $popularGroupsForCurrentHierarcyInner['popular_group_id'];
					$finalPopularGroups['name'][] = $popularGroupsForCurrentHierarcyInner['popular_group_name'];
					$finalPopularGroups['nameIdMap'][] = $popularGroupsForCurrentHierarcyInner['popular_group_name'].":".$popularGroupsForCurrentHierarcyInner['popular_group_id'];
					$finalPopularGroups['synonms'] = array_unique(array_merge($finalPopularGroups['synonms'],$popularGroupsForCurrentHierarcyInner['popular_group_synonym']));
				}

				$hierarcies[$key]['nl_popular_group_id'] = $finalPopularGroups['ids'];
				$hierarcies[$key]['nl_popular_group_name'] = $finalPopularGroups['name'];
				$hierarcies[$key]['nl_popular_group_name_id_map'] = $finalPopularGroups['nameIdMap'];
				$hierarcies[$key]['nl_popular_group_synonyms'] = $finalPopularGroups['synonms'];
			}

			$hierarcies[$key]['nl_course_hierarchy_type'] = $type;

		}
		return $hierarcies;

	}


}

?>

