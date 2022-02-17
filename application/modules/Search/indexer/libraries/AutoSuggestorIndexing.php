<?php 

class AutoSuggestorIndexing {
	
	private $_CI;

	public function __construct()
	{
		$this->_CI = & get_instance();
		
		$this->_CI->load->model("indexer/NationalIndexingModel");
		$this->nationalIndexingModel = new NationalIndexingModel();

		$this->_CI->load->library('indexer/SolrServerLib');
		$this->solrServerLib = new SolrServerLib;

		$this->_CI->load->library('search/Common/SearchCommon');
		$this->searchCommon = new SearchCommon;

		$this->_CI->load->config('indexer/nationalIndexerConfig');
		$this->_CI->load->config('examPages/examPageConfig');

		$this->_CI->load->library('examPages/ExamPageRequest');
		$this->examRequest = new ExamPageRequest;

		$this->_CI->load->library('nationalCategoryList/AllCoursesPageLib');
		$this->allCoursePageLib = new AllCoursesPageLib;

		$this->_CI->load->library('nationalInstitute/cache/NationalInstituteCache');
		$this->nationalInstituteCache = new NationalInstituteCache;

		$this->_CI->load->builder('LocationBuilder','location');
        $this->locationBuilder = new LocationBuilder();
		$this->locationRepository = $this->locationBuilder->getLocationRepository();

		global $statesToIgnore;
    	$this->statesToIgnore = $statesToIgnore;

		$this->logFilePath = "/tmp/indexLog";

		$this->_CI->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder = new InstituteBuilder();

		// get institute repository with all dependencies loaded
		$this->instituteRepo = $instituteBuilder->getInstituteRepository();
		$this->instituteRepo->disableCaching();

		$this->_CI->load->builder("listingBase/ListingBaseBuilder");
		$listingBaseBuilder = new ListingBaseBuilder();
		$this->baseCourseRepo = $listingBaseBuilder->getBaseCourseRepository();
		$this->streamRepo     = $listingBaseBuilder->getStreamRepository();
	}

	public function autoSuggestorFullIndexing(){

		$entitiesList = array('stream','substream','specialization','base_course','exam','examChildPage','all_exam','question_tag', 'ilpChildPage');
		//$entitiesList = array('base_course');
		$localCount = 0;
		$delimMap = $this->_CI->config->item('entityToDelMap');
		$indexDataList = array();
		foreach ($entitiesList as $entityType) {
			error_log("AutoSuggestor Indexing :  Started for $entityType\n");
			switch($entityType){
				case 'exam':
					$this->indexExamPagesForAutosuggestor();
					break;

				case 'examChildPage':
					$this->indexExamChildPagesForAutosuggestor();
					break;

				case 'all_exam':
					$this->indexAllExamPagesForAutoSuggestor();
					break;

				case 'question_tag':
					$this->autosuggestorQuestionTagIndexing();
					break;

				case 'ilpChildPage' :
					$this->indexILPChildPagesForAutosuggestor();
					break;

				default:
					$entityData = $this->nationalIndexingModel->fetchAllEntities($entityType);
					foreach ($entityData as $entityDataArr) {

						error_log("AutoSuggestor Indexing : Entity  : $entityType ".$entityDataArr['entity_id']);
						$entityName = $entityDataArr['name'];
						$entityId = $entityDataArr['entity_id'];

						$entitySyn = explode($delimMap[$entityType],$entityDataArr['synonym']);
						$entitySyn = array_filter($entitySyn);
					
						$resultCount = $this->nationalIndexingModel->fetchResultCountForEntities($entityType,$entityId);	
						$finalData = array();
						$finalData['entity_id'] = $entityId;
						$finalData['entity_name'] = $entityName;
						$finalData['entity_result_count'] = $resultCount;
						$finalData['entity_type'] = $entityType;
						$finalData['entity_synonym'] = $entitySyn;
						
						$SRP_TO_CTP_MAPPING = $this->_CI->config->item('SRP_TO_CTP_MAPPING');
						$finalData['entity_url'] = $SRP_TO_CTP_MAPPING[$entityType][$entityId];

						if(!empty($finalData['entity_url'])) {
							$indexDataList[] = $this->createAutoSuggestorDocument($finalData);
							$localCount++;
							//$count++;
						}

						if($localCount == (INDEXING_BATCH_SIZE_AUTOSUGGESTOR-1) ){
							$this->solrServerLib->indexFinalData($indexDataList);
							$localCount = -1;
							unset($indexDataList);
							$indexDataList = array();
						}
					}
					break;
			}
			error_log("AutoSuggestor Indexing :  Ended for $entityType\n", 3, "/tmp/my-errors.log");
		}
		if(!empty($indexDataList)){
			$this->solrServerLib->indexFinalData($indexDataList);
		}
		//_p($indexDataList);
	}

	public function autoSuggestorLocationIndexing() {
		$entitiesList = array('stream', 'substream','specialization','base_course');
		//$entitiesList = array('base_course');

		$locations = $this->fetchAllLocations();
		$delimMap = $this->_CI->config->item('entityToDelMap');

		foreach ($entitiesList as $entityType) {
			$entityData = $this->nationalIndexingModel->fetchAllEntities($entityType);

			foreach ($entityData as $entityDataArr) {
				foreach ($locations as $locationType => $location) {
					foreach ($location as $key => $locationInfo) {
						error_log("AutoSuggestor Indexing : Entity : $entityType : ".$entityDataArr['name']." $locationType : ".$locationInfo['name']);
						$entityName = $entityDataArr['name'].' in '.$locationInfo['name'];
						$entityId = $entityDataArr['entity_id'].'::'.$locationInfo['id'];

						$entitySyn = explode($delimMap[$entityType],$entityDataArr['synonym']);
						foreach ($entitySyn as $key => $value) {
							if(!empty($value)) {
								$entitySyn[] = $value.' in '.$locationInfo['name'];
								$entitySyn[] = $locationInfo['name'].' '.$value;
								$entitySyn[] = $value.' '.$locationInfo['name'];
							}
						}
						$entitySyn[] = $locationInfo['name'].' '.$entityDataArr['name'];
						$entitySyn[] = $entityDataArr['name'].' '.$locationInfo['name'];

						$entitySyn = array_filter($entitySyn);
						
						$resultCount = $this->nationalIndexingModel->fetchResultCountForEntities($entityType, $entityDataArr['entity_id'], $locationType, $locationInfo['id']);
						$finalData = array();
						$finalData['entity_id'] = $entityId;
						$finalData['entity_name'] = $entityName;
						$finalData['entity_result_count'] = $resultCount;
						$finalData['entity_type'] = $entityType.'_'.$locationType;
						$finalData['entity_synonym'] = $entitySyn;
						
						$SRP_TO_CTP_MAPPING = $this->_CI->config->item('SRP_TO_CTP_MAPPING');
						$locationUrlName = seo_url_lowercase($locationInfo['name']);
						$finalData['entity_url'] = $SRP_TO_CTP_MAPPING[$entityType][$entityDataArr['entity_id']];
						$finalData['entity_url'] = str_replace("india", $locationUrlName, $finalData['entity_url']);

						if(!empty($finalData['entity_url'])) {
							$indexDataList[] = $this->createAutoSuggestorDocument($finalData);
							$localCount++;
						}
						
						if($localCount == (INDEXING_BATCH_SIZE_AUTOSUGGESTOR-1) ){
							$this->solrServerLib->indexFinalData($indexDataList);
							$localCount = -1;
							unset($indexDataList);
							$indexDataList = array();
						}
					}
				}
			}
		}
	}

	private function fetchAllLocations() {
		$cities = $this->locationRepository->getCities(2, true);
		$states = $this->locationRepository->getStatesByCountry(2);

		foreach ($cities as $key => $cityObj) {
			$locations['city'][$cityObj->getId()]['id'] = $cityObj->getId();
			$locations['city'][$cityObj->getId()]['name'] = $cityObj->getName();
		}
		
		foreach ($states as $key => $stateObj) {
			if(!in_array($stateObj->getId(), $this->statesToIgnore)) {
				$locations['state'][$stateObj->getId()]['id'] = $stateObj->getId();
				$locations['state'][$stateObj->getId()]['name'] = $stateObj->getName();
			}
		}
		
		return $locations;
	}

	public function createAutoSuggestorDocument($data){
		$finalDocument = array();
		$finalDocument['facetype'] = "autosuggestor";
		if(!empty($data['unique_id'])){
			$finalDocument['unique_id'] = $data['unique_id'];
		}
		else{
			$finalDocument['unique_id'] = $data['entity_type']."_".$data['entity_id'];
		}
		$finalDocument['nl_entity_id'] = $data['entity_id'];
		$finalDocument['nl_entity_name'] = $data['entity_name'];
		$finalDocument['nl_entity_type'] = $data['entity_type'];
		if($data['entity_sub_type'])
			$finalDocument['nl_entity_sub_type'] = $data['entity_sub_type'];
		$finalDocument['nl_entity_count_name_id_type_map'] = $data['entity_result_count']."::".$data['entity_name']."::".$data['entity_id']."::".$data['entity_type'];
		$finalDocument['nl_entity_result_count'] = $data['entity_result_count'];
		if(!empty($data['entity_synonym'])){
			$finalDocument['nl_entity_synonyms'] = $data['entity_synonym'];
		}
		if(!empty($data['entity_url'])){
			$finalDocument['nl_entity_url'] = $data['entity_url'];
		}

		return $finalDocument;
	}

	public function autoSuggestorParticularEntityIndexing($entityType, $entityId){
		$entitiesList = array('stream','substream','specialization','base_course','certificate_provider','popular_group','exam','examChildPage','all_exam','question_tag');
		if(empty($entityId) || empty($entityType) || !in_array($entityType, $entitiesList)){
			return;
		}
		switch($entityType){
			case 'exam':
				$this->indexExamPagesForAutosuggestor();
				break;

			case 'examChildPage':
				$this->indexExamChildPagesForAutosuggestor();
				break;

			case 'all_exam':
				$this->indexAllExamPagesForAutoSuggestor();
				break;

			case 'question_tag':
				$this->autosuggestorQuestionTagIndexing();
				break;

			case 'ilpChildPage' :
				$this->indexILPChildPagesForAutosuggestor(array($entityId));
				break;

			default:
				$delimMap = $this->_CI->config->item('entityToDelMap');
				$entityData = $this->nationalIndexingModel->fetchEntity($entityType,$entityId);
				$entityDataArr = reset($entityData);
				$entityName = $entityDataArr['name'];
				$entityId = $entityDataArr['entity_id'];

				$entitySyn = explode($delimMap[$entityType],$entityDataArr['synonym']);
				$entitySyn = array_filter($entitySyn);
				
				$resultCount = $this->nationalIndexingModel->fetchResultCountForEntities($entityType,$entityId);	

				$finalData = array();
				$finalData['entity_id'] = $entityId;
				$finalData['entity_name'] = $entityName;
				$finalData['entity_result_count'] = $resultCount;
				$finalData['entity_type'] = $entityType;
				$finalData['entity_synonym'] = $entitySyn;				
				
				$indexDataList[] = $this->createAutoSuggestorDocument($finalData);
				$this->solrServerLib->indexFinalData($indexDataList);
				break;
		}
	}

	// to reload core after schema changes
	// http://localhost:8983/solr/admin/cores?action=RELOAD&core=collection1

	public function indexExamPagesForAutosuggestor($examIds){
		$localCount = 0;
		$indexDataList = array();
		if(empty($examIds)){
			$exams = $this->nationalIndexingModel->getAllExamsHavingExamPages();
		}
		else{
			$exams = $this->nationalIndexingModel->getExamPagesDataByExamIds($examIds);
		}
		
		foreach ($exams as $exam) {
			if(!empty($exam['url'])) {
				error_log("AutoSuggestor Indexing : Entity  : ExamPages ".$exam['exam_id']);
				$temp = array();
				$temp['entity_type'] = 'exam';
				$temp['entity_id'] = $exam['exam_id'];
				$temp['entity_name'] = $exam['exam_name'];
				$temp['entity_result_count'] = 1;
				$temp['entity_synonym'][] = $exam['exam_full_form'];
				$temp['entity_synonym'][] = $exam['groupName'];
				$temp['entity_url'] = $exam['url'];
				
				$indexDataList[] = $this->createAutoSuggestorDocument($temp);

				if($localCount == (INDEXING_BATCH_SIZE_AUTOSUGGESTOR-1) ){
					$this->solrServerLib->indexFinalData($indexDataList);
					$localCount = -1;
					unset($indexDataList);
					$indexDataList = array();
				}
				$localCount++;
			}
		}
		if(!empty($indexDataList)){
			$this->solrServerLib->indexFinalData($indexDataList);
		}
		$this->solrServerLib->softCommitChanges();
		_p('DONE');
		// _p($indexDataList);die;
	}

	public function indexILPChildPagesForAutosuggestor($ilpIds = null){
		if(empty($ilpIds)){
			$ilpIds = $this->nationalIndexingModel->fetchInsIds();
		}
		$bcMap = array();
		$streamMap = array();
		foreach ($ilpIds as $instituteId) {
			error_log("START FOR $instituteId");
			$this->deleteILPChildPageIndex($instituteId);
			$this->indexILPForChildPages($instituteId, $bcMap, $streamMap);
			error_log("END FOR $instituteId");
		}
	}
	
	private function indexILPForChildPages($instituteId, &$bcMap, &$streamMap){
		$ilpData = $this->instituteRepo->find($instituteId, array("childPageExist"));
		$localCount = 0;
		$indexDataList = array();

		if(empty($ilpData) || empty($ilpData->getName()) || empty($ilpData->getId())){
			error_log("Empty institute object/name/id for ". $instituteId);
			return;
		}
		$bipSipCacheString = $this->nationalInstituteCache->getInstituteCourseWidgetNew($instituteId);
		$bipSipCacheObj = json_decode($bipSipCacheString, true);

		if(!empty($bipSipCacheObj["baseCourseIds"])){
			$baseCourseInfo = [];
			$naBc = $this->getEntityQueryArray($bcMap, $bipSipCacheObj["baseCourseIds"]);
			if(!empty($naBc)){
				$baseCourseInfo = $this->baseCourseRepo->findMultiple($naBc);
			}
			$bcMap = $bcMap +  $baseCourseInfo;
			$this->indexBipSip($bipSipCacheObj["baseCourseIds"], $bcMap, $ilpData, true);
		}
		if(!empty($bipSipCacheObj["streamIds"])){
			$streamCourseInfo = [];
			$naStream = $this->getEntityQueryArray($streamMap, $bipSipCacheObj["streamIds"]);
			if(!empty($naStream)){
				$streamCourseInfo = $this->streamRepo->findMultiple($naStream);
			}
			$streamMap = $streamMap +  $streamCourseInfo;
			$this->indexBipSip($bipSipCacheObj["streamIds"], $streamMap, $ilpData, false);
		}
		foreach($ilpData->getChildPages() as $childPage){
			$temp = array();
			$temp['entity_type'] = 'ilpPage';
			$temp['entity_sub_type'] = $childPage;
			$temp['entity_id'] = $instituteId;
			$temp['entity_name'] = $ilpData->getName() . " " . ucfirst($childPage);
			$temp['entity_synonym'][] = $childPage . " " . $ilpData->getName();
			if(!empty($ilpData->getSynonym())) {
				$ilpSynonyms =  explode(";", $ilpData->getSynonym());
				$temp['entity_synonym'] = array_merge($temp['entity_synonym'], $this->prepareSynonyms($ilpSynonyms, array($childPage)));
			}
			$temp['entity_url'] = $ilpData->getRelativeAllContentPageUrl($childPage);
			$temp['entity_result_count'] = 1;
			$temp['unique_id'] = "autosuggestor_" . $childPage . '_' . $instituteId;
			$indexDataList[] = $this->createAutoSuggestorDocument($temp);
			error_log("Adding batch for ". $childPage);
			$localCount++;
			if($localCount == INDEXING_BATCH_SIZE_AUTOSUGGESTOR ){
				$this->solrServerLib->indexFinalData($indexDataList);
				$localCount = 0;
				error_log("batch indexed");
				unset($indexDataList);
				$indexDataList = array();
			}
		}
		if(!empty($indexDataList)){
			$this->solrServerLib->indexFinalData($indexDataList);
		}
		$this->solrServerLib->softCommitChanges();
		error_log("Indexing complete for ".$instituteId);
	}
	private function getEntityQueryArray($entityMap, $entityIds){
		$naEntities = array();
		foreach($entityIds as $entityId){
			if(empty($entityMap[$entityId])){
				$naEntities[] = $entityId;
			}
		}
		return $naEntities;
	}

	private function indexBipSip($entityIds, $entityMap, $instituteObj, $isBip){
		if(empty($instituteObj)){
			error_log("empty institute object ");
			return;
		}
		$instituteId = $instituteObj->getId();
		if(empty($instituteObj->getName()) || empty($instituteId)){
			error_log("Empty institute name/id ");
			return;
		}
		$indexDataList = array();
		$localCount = 0;
		$entityName = $isBip ? "base_course" : "stream";
		foreach($entityIds as $entityId){
			$entityObj = $entityMap[$entityId];
			if(empty($entityObj))
				continue;
			$childPage = $entityObj->getName();
			$temp = array();
			$temp['entity_type'] = 'ilpPage';
			$temp['entity_sub_type'] = $isBip ? "bip" : "sip";
			$temp['entity_id'] = $instituteId;
			$temp['entity_name'] = $instituteObj->getName() . " " . $childPage;
			$temp['entity_synonym'][] = $childPage . " " . $instituteObj->getName();
			if(!empty($instituteObj->getSynonym()) || !empty($entityObj->getSynonym())) {
				$ilpSynonyms = empty($instituteObj->getSynonym()) ? array($instituteObj->getName())  :explode(";", $instituteObj->getSynonym());
				$entitySynonyms = empty($entityObj->getSynonym()) ? array($childPage) : explode(",", $entityObj->getSynonym());
				$temp['entity_synonym'] = array_merge($temp['entity_synonym'] , $this->prepareSynonyms($ilpSynonyms, $entitySynonyms));
			}
			$appliedFilters = array();
			$appliedFilters[$entityName] = array();
			$appliedFilters[$entityName]["id"] = $entityObj->getId();
			$appliedFilters[$entityName]["name"] = $entityObj->getName();
			$temp['entity_url'] = $this->allCoursePageLib->getUrlForAppliedFilters($appliedFilters, $instituteObj, true);
			$temp['entity_result_count'] = 1;
			$temp['unique_id'] = "autosuggestor_" . $childPage . '_' . $instituteId;
			$indexDataList[] = $this->createAutoSuggestorDocument($temp);
			error_log("Adding batch for ". $childPage);
			if($localCount == INDEXING_BATCH_SIZE_AUTOSUGGESTOR ){
				$this->solrServerLib->indexFinalData($indexDataList);
				$localCount = 0;
				error_log("batch indexed");
				unset($indexDataList);
				$indexDataList = array();
			}
		}
		if(!empty($indexDataList)){
			$this->solrServerLib->indexFinalData($indexDataList);
		}
		return $indexDataList;
	}

	private function prepareSynonyms($synonyms, $seedValues){
		$finalSynonyms = array();
		foreach($synonyms as $synonym){
			foreach ($seedValues as $seedValue) {
				$finalSynonyms[] = $synonym . " " . $seedValue;
				$finalSynonyms[] = $seedValue . " " . $synonym;
			}
		}
		return $finalSynonyms;
	}

	private function deleteILPChildPageIndex($instituteId){
		$this->solrServerLib->deleteAutoSuggestorDocuments("ilpPage", $instituteId);
	}



	public function indexExamChildPagesForAutosuggestor($examIds) {
		$localCount = 0;
		$indexDataList = array();
		if(empty($examIds)){
			$exams = $this->nationalIndexingModel->getAllExamsHavingExamPages();
		}
		else{
			$exams = $this->nationalIndexingModel->getExamPagesDataByExamIds($examIds);
		}
		
		$CHILD_LIST = $this->_CI->config->item('EXAM_CHILD_PAGES');
		$sectionNamesMapping = array_flip($this->_CI->config->item('sectionNamesMapping'));
		foreach ($exams as $exam) {
			$this->examRequest->setExamName($exam['exam_name']);

			foreach ($CHILD_LIST as $key => $value) {
				$sectionName = $sectionNamesMapping[$value];

				error_log("AutoSuggestor Indexing : Entity : ExamChildPages ".$exam['exam_id']."_".$sectionName);
				$temp = array();
				$temp['entity_type'] = 'examChildPage';
				$temp['entity_id'] = $sectionName.'::'.$exam['exam_id'];
				$temp['entity_name'] = $exam['exam_name']." ".$value;
				$temp['entity_synonym'][] = $exam['exam_name'];
				$temp['entity_synonym'][] = $value." ".$exam['exam_name'];

				//here result count is actually simple boost value
				//we want autosuggestor ordering in the way child list is ordered
				//$temp['entity_result_count'] = count($CHILD_LIST)-$key;
				$temp['entity_result_count'] = 1;

				$sectionUrl = $this->examRequest->getUrl($sectionName, true);
				$temp['entity_url'] = str_replace(SHIKSHA_HOME, "", $sectionUrl);

				$temp['unique_id'] = $temp['entity_type'].'_'.$sectionName.'_'.$exam['exam_id'];
				
				$indexDataList[] = $this->createAutoSuggestorDocument($temp);
			
				if($localCount == (INDEXING_BATCH_SIZE_AUTOSUGGESTOR-1) ){
					$this->solrServerLib->indexFinalData($indexDataList);
					$localCount = -1;
					//_p($indexDataList);
					unset($indexDataList);
					$indexDataList = array();
				}
				$localCount++;
			}
		}
		if(!empty($indexDataList)){
			$this->solrServerLib->indexFinalData($indexDataList);
		}
		$this->solrServerLib->softCommitChanges();
		_p('DONE');
	}

	public function indexAllExamPagesByEntity($entityType,$entityId){
		$delimMap = $this->_CI->config->item('entityToDelMap');
		$indexDataList = array();
		$entityData[$entityType] = $this->nationalIndexingModel->fetchAllEntities($entityType,array($entityId));
		$resultCount = $this->nationalIndexingModel->fetchExamCountByEntity($entityType,$entityId);
		if(empty($resultCount)){
			return;
		}
		$examMainLib = $this->_CI->load->library('examPages/ExamMainLib');
		foreach ($entityData[$entityType] as $entityDataArr) {
			$entityName = $entityDataArr['name'].' Exams';
			$entityId = $entityDataArr['entity_id'];
			$entitySyn = explode($delimMap[$entityType],$entityDataArr['synonym']);
			$entitySyn = array_filter($entitySyn);

			$finalData = array();
			$finalData['entity_id'] = $entityType.'::'.$entityId;
			$finalData['entity_name'] = $entityName;
			$finalData['entity_result_count'] = $resultCount;
			$finalData['entity_type'] = 'allexam';
			$finalData['entity_synonym'] = $entitySyn;
			$finalData['entity_url'] = $examMainLib->getAllExamPageUrlByEntity($entityType,$entityId);
			$finalData['unique_id'] = $finalData['entity_type'].'_'.$entityType.'_'.$entityId;
			if(!empty($finalData['entity_url'])){
				$indexDataList[] = $this->createAutoSuggestorDocument($finalData);
			}
		}
		$this->solrServerLib->indexFinalData($indexDataList);
		$this->solrServerLib->softCommitChanges();
		_p('DONE');
	}

	public function indexAllExamPagesForAutoSuggestor(){
		$exams = $this->nationalIndexingModel->getAllExamsHavingExamPages();
		$examMainLib = $this->_CI->load->library('examPages/ExamMainLib');
		$examIds = array_keys($exams);
		$hierarchyMappings = $this->nationalIndexingModel->getExamPageHierarchiesByExamIds($examIds);
		$baseCourseMapping = $this->nationalIndexingModel->getExamPageBaseCoursesByExamIds($examIds);
		// _p($baseCourseMapping);die;
		// _p($hierarchyMappings);die;
		$examMapping['stream'] = array();$examMapping['substream'] = array();$examMapping['base_course'] = array();
		foreach ($hierarchyMappings as $row) {
			if(!empty($row['stream_id'])){
				if(empty($examMapping['stream'][$row['stream_id']])){
					$examMapping['stream'][$row['stream_id']] = array();
				}
				if(!in_array($row['examId'],$examMapping['stream'][$row['stream_id']])){
					$examMapping['stream'][$row['stream_id']][] = $row['examId'];
				}
			}
			if(!empty($row['substream_id'])){
				if(empty($examMapping['substream'][$row['substream_id']])){
					$examMapping['substream'][$row['substream_id']] = array();
				}
				if(!in_array($row['examId'],$examMapping['substream'][$row['substream_id']])){
					$examMapping['substream'][$row['substream_id']][] = $row['examId'];
				}
			}
		}
		foreach ($baseCourseMapping as $row) {
			$examMapping['base_course'][$row['base_course_id']][] = $row['examId'];
		}
		// _p($examMapping);die;
		$delimMap = $this->_CI->config->item('entityToDelMap');
		$temp = array('stream','substream','base_course');
		$localCount = 0;
		$indexDataList = array();
		foreach ($temp as $entityType) {
			$entityData[$entityType] = $this->nationalIndexingModel->fetchAllEntities($entityType,array_keys($examMapping[$entityType]));
			foreach ($entityData[$entityType] as $entityDataArr) {
				if(strtolower(substr($entityDataArr['name'], -5)) != 'exams') {
					$entityName = $entityDataArr['name'].' Exams';
				} else {
					$entityName = $entityDataArr['name'];
				}
				
				$entityId = $entityDataArr['entity_id'];
				$entitySyn = explode($delimMap[$entityType],$entityDataArr['synonym']);
				$entitySyn = array_filter($entitySyn);

				$finalData = array();
				$finalData['entity_id'] = $entityType.'::'.$entityId;
				$finalData['entity_name'] = $entityName;
				$finalData['entity_result_count'] = count($examMapping[$entityType][$entityId]);
				$finalData['entity_type'] = 'allexam';
				$finalData['entity_synonym'] = $entitySyn;
				$finalData['entity_url'] = $examMainLib->getAllExamPageUrlByEntity($entityType,$entityId);
				$finalData['unique_id'] = $finalData['entity_type'].'_'.$entityType.'_'.$entityId;
				if(!empty($finalData['entity_url'])){
					$indexDataList[] = $this->createAutoSuggestorDocument($finalData);
					if($localCount == (INDEXING_BATCH_SIZE_AUTOSUGGESTOR-1) ){
						$this->solrServerLib->indexFinalData($indexDataList);
						$localCount = -1;
						unset($indexDataList);
						$indexDataList = array();
					}
					$localCount++;
				}
			}
		}
		if(!empty($indexDataList)){
			$this->solrServerLib->indexFinalData($indexDataList);
		}
		$this->solrServerLib->softCommitChanges();
		_p('DONE');
		// _p($indexDataList);die;
	}

	public function getBaseEntitiesMappedToExamForIndexing($examId){
		$returnData = array();
		$hierarchyMappings = $this->nationalIndexingModel->getExamPageHierarchiesByExamIds(array($examId));
		$returnData['stream'] = array_unique(array_filter(array_map(function($ele){return $ele['stream_id'];},$hierarchyMappings)));
		$returnData['substream'] = array_unique(array_filter(array_map(function($ele){return $ele['substream_id'];},$hierarchyMappings)));
		$baseCourseMapping = $this->nationalIndexingModel->getExamPageBaseCoursesByExamIds(array($examId));
		$returnData['base_course'] = array_unique(array_filter(array_map(function($ele){return $ele['base_course_id'];},$baseCourseMapping)));

		return $returnData;
	}

	public function autosuggestorQuestionTagIndexing($tagId) {
        $topicData = $this->nationalIndexingModel->getQuestionTagsToIndex($tagId);
        foreach ($topicData as $key => $value) {
            $tagIds[] = $value['id'];
        }
        $tagSynonyms = $this->nationalIndexingModel->getTagSynonyms($tagIds);
        
        $indexData = array();
        foreach ($topicData as $key => $value) {
            $qualityFactor = $this->searchCommon->getTagQualityFactor($value['id']);
            
            $indexData[$key]['nl_entity_id']   = $value['id'];
            $indexData[$key]['nl_entity_name'] = $value['tags'];
            $indexData[$key]['nl_entity_synonyms'] = $tagSynonyms[$value['id']];
            $indexData[$key]['nl_entity_type'] = 'question_tag';

            $indexData[$key]['nl_entity_count_name_id_type_map'] = $value['questionCount']."::".$indexData[$key]['nl_entity_name']."::".$indexData[$key]['nl_entity_id']."::question_tag";
            $indexData[$key]['nl_entity_quality_name_id_type_map'] = $qualityFactor."::".$indexData[$key]['nl_entity_name']."::".$indexData[$key]['nl_entity_id']."::question_tag";
            $indexData[$key]['nl_entity_tag_qna_count'] = 'q:'.$value['questionCount'].'_a:'.$value['answerCount'];
            $indexData[$key]['nl_entity_quality_factor'] = $qualityFactor;
            //$indexData[$key]['nl_entity_result_count'] = $value['count'];
            $indexData[$key]['nl_entity_result_count'] = (int) ($qualityFactor * 10000);
            $indexData[$key]['nl_entity_url'] = '/tags/-tdp-'.$value['id'];
            
            $indexData[$key]['facetype'] = 'autosuggestor';
            $indexData[$key]['unique_id'] = 'question_tag_'.$indexData[$key]['nl_entity_id'];
        }

        if(!empty($indexData)) {
            $indexResponse = $this->solrServerLib->indexFinalData($indexData);
            if($indexResponse[0] == 1) {
                $status = 'Success';
            } else {
                $status = 'Failed';
            }
        }
        _p("Done");
    }
} ?>
