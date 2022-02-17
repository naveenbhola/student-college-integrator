<?php 
class ExamMigration extends MX_Controller{
	/*
	On production, this script will run only after we have resolved the duplicate exam issue.
	*/
	public function migrateExamsNew(){
		$this->load->model('exammigrationmodel');
		$result = $this->exammigrationmodel->getNewExamsWithMapping();
		$formattedResult = array();
		foreach ($result as $key => $value) {
			if($value['entityType'] == 'primaryHierarchy'){
				$formattedResult[$value['examId']]['primHierarchy'] = $value['entityId'];
			}
			else if($value['entityType'] == 'course'){
				$formattedResult[$value['examId']]['baseCourse'][] = $value['entityId'];
			}
			$formattedResult[$value['examId']]['name'] = trim($value['name']);
		}
		$this->load->library('common/UrlLib');
		foreach ($formattedResult as $examId => $detail) {
			$param = array();
			$param['examName'] = $detail['name'];
			if(isset($detail['baseCourse']) && count($detail['baseCourse']) > 0){
				$param['course'] = $detail['baseCourse'];
			}else{
				$param['course'] = array();
			}
			$param['primaryHierarchy'] = $detail['primHierarchy'];
			$examUrlLib = new UrlLib();
			$examUrl = $examUrlLib->getExamUrl($param);
			if(!empty($examUrl)){
				_p($examUrl);
				$this->exammigrationmodel->updateUrlInMasterTable($examId, $detail['name'], $examUrl);
			}
		}
		echo 'done';
	}


	/*
	1. Get the exams category parents from the blogTable
	2. Get all the childs of these records
	3. Get category, sub-category and name of these exams
	4. Get new mapping from the old ones
	5. Insert exam name into exampage_main table
	6. Insert into examAttributeMapping table
	*/

	public function migrateExams(){
		$this->load->model('exammigrationmodel');
		$exams = $this->exammigrationmodel->getOldExamList();
		$this->load->library('common/UrlLib');
		$subCatArr = array();
		//18,23,33,56,69,75,84
		$allowedSubCatArr = array(18 => array('stream'=>0, 'course'=>0, 'educationType'=>0), 
								  23 => array('stream'=>4, 'course'=>0, 'educationType'=>20), 
								  33 => array('stream'=>0, 'course'=>0, 'educationType'=>0), 
								  56 => array('stream'=>5, 'course'=>3, 'educationType'=>0), 
								  69 => array('stream'=>0, 'course'=>0, 'educationType'=>0), 
								  75 => array('stream'=>0, 'course'=>0, 'educationType'=>0), 
								  84 => array('stream'=>0, 'course'=>0, 'educationType'=>0)
			);
		$mappings = $this->exammigrationmodel->getNewMappings(array_keys($allowedSubCatArr));
		$mappingsBySubCat = array();
		
		$baseEntity = array();
		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$hierarchyRepo = $listingBase->getHierarchyRepository();
		foreach ($mappings as $key => &$value) {
			$baseEntity['streamId'] = ($value['stream_id'] > 0) ? $value['stream_id'] : 0;
			$baseEntity['substreamId'] = ($value['substream_id'] > 0) ? $value['substream_id'] : 'none';
			$baseEntity['specializationId'] = ($value['specialization_id'] > 0) ? $value['specialization_id'] : 'none';
			$hierarchyIdArr = $hierarchyRepo->getHierarchyIdByBaseEntities($baseEntity['streamId'], $baseEntity['substreamId'], $baseEntity['specializationId']);
			$value['primaryHierarchy'] = $hierarchyIdArr[0];
			$subCat = $value['oldsubcategory_id'];
			unset($value['oldcategory_id']); unset($value['oldsubcategory_id']); unset($value['oldspecializationid']); unset($value['stream_id']); unset($value['substream_id']); unset($value['specialization_id']);
			$mappingsBySubCat[$subCat] = $value;
		}
		$batchData = array();
		$allExamNames = array();
		foreach ($exams as $value) {
			$allExamNames[] = $value['acronym'];
		}
		$exampageIdArr = $this->exammigrationmodel->getAllExampageIds($allExamNames);
		//_p($exampageIdArr);die;
		foreach ($exams as $key => $examDetail) {
			$param = array();
			$param['examName'] = $examDetail['acronym'];
			if($mappingsBySubCat[$examDetail['boardId']]['base_course_id'] > 0){
				$param['course'] = $mappingsBySubCat[$examDetail['boardId']]['base_course_id'];
			}else{
				$param['course'] = '';
			}
			$param['primaryHierarchy'] = array($mappingsBySubCat[$examDetail['boardId']]['primaryHierarchy']);
			$examUrlLib = new UrlLib();

			$examMainData = array();
			$examMainData['id']           = $examDetail['blogId'];
			$examMainData['name']         = $examDetail['acronym'];
			$examMainData['url']          = $examUrlLib->getExamUrl($param);
			$examMainData['status']       = 'live';
			$examMainData['creationDate'] = date('Y-m-d H:i:s');
			$examMainData['exampageId']   = ($exampageIdArr[$examDetail['acronym']]!='') ? $exampageIdArr[$examDetail['acronym']] : 0;

			//insert into exampage_main table and get exam Id
			$sts = $this->exammigrationmodel->insertIntoTable('exampage_main', $examMainData);
			if($sts){
				$examId = $examDetail['blogId'];
			}else{
				$examId = 0;
			}
			//echo $examId = rand(1,1000);
			//_p($examMainData);
			echo ' _ ';
			//update exampage_master with new exam_id
			if($examMainData['exampageId'] > 0){
				$this->exammigrationmodel->updateExamIdInExamMaster($examId, $examMainData['exampageId']);
			}
			
			//make new mapping values
			$examMapping  = array();
			$examMapping['examId'] = $examId;
			foreach ($mappingsBySubCat[$examDetail['boardId']] as $key => $value) {
				if($value != '' && $value > 0){
					$examMapping['entityId'] = $value;
					$examMapping['status'] = 'live';
					$examMapping['creationDate'] = date('Y-m-d H:i:s');
					if($key == 'base_course_id'){
						$examMapping['entityType'] = 'course';
					}
					else if($key == 'primaryHierarchy'){
						$examMapping['entityType'] = 'primaryHierarchy';
					}
					else if($key == 'education_type' || $key == 'delivery_method'){
						$examMapping['entityType'] = 'otherAttribute';
					}
					$batchData[] = $examMapping;
				}
			}
		}
		//echo '========';
		$this->exammigrationmodel->insertBatchIntoTable('examAttributeMapping', $batchData);
		_p($batchData);
	}
	/**
	* below function is used to update all exam pages on google cdn cache.
	*/
	function updateAmpExamPageCacheOnGoogleCDN()
	{
		ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);
		$exampagemodel = $this->load->model("examPages/exampagemodel");		
		$examIds = $exampagemodel->getExamIds();
		foreach ($examIds as $examkey => $examId) {
			if(!empty($examId)) 
			{
				$this->load->builder("examPages/ExamBuilder");
		        $examBuilder = new ExamBuilder();
		        $examRepo = $examBuilder->getExamRepository();
		        $examBasicObj = $examRepo->find($examId);
		        if(!empty($examBasicObj))
		        {
		            $ampBasicURL = $examBasicObj->getAmpURL();
		            $groupsMapped = $examBasicObj->getGroupMappedToExam();
		            $primaryGroup = $examBasicObj->getPrimaryGroup();
		            $primaryGroupId = $primaryGroup['id'];

		            foreach ($groupsMapped as $key => $value) {
		            	$ampURL = $ampBasicURL;
		            	if(!empty($primaryGroupId) && $primaryGroupId != $value['id'])
			            {
			                $ampURL .= '?course='.$value['id'];
			            }
			            if(!empty($ampURL))
			            {
			                updateGoogleCDNcacheForAMP($ampURL);
			                error_log('updated Google CDN Cache For Exam ='.$examId.' and GroupId ='.$value['id']);
			            }	
		            }   
		        }
			}
		}
	}

	function migrateEPRedirectdata(){
		$emModel = $this->load->model("examPages/exampagemodel");				
		$data = $emModel->getExamIdByExampageId();
		foreach ($data as $examId => $examPageId) {
			$emModel->updateExamIdByExampageId($examId, $examPageId);
		}
	}

	function migrateEductionMode(){
		$emModel = $this->load->model("examPages/exampagemodel");
		$data = $emModel->getNonEducationModeExam();
		$finalData = array();
		foreach ($data as $examId => $groupId) {
			$rowData = array();
	  		$rowData['examId']   = $examId;
            $rowData['groupId']  = $groupId;
	  		$rowData['entityId'] = 20;
			$rowData['status']   = 'live';
			$rowData['creationDate'] = date('Y-m-d H:i:s');
			$rowData['modificationDate'] = date('Y-m-d H:i:s');
			$rowData['entityType'] = 'otherAttribute';
			$finalData[] = $rowData;
			unset($rowData);
		}
		$emModel->addEductionMode($finalData);
	}

    function removeExtraSpace($examId = 0){
        $emModel = $this->load->model("examPages/exampagemodel");
        $data = $emModel->removeExtraSpace("exampage_content_table", $examId);
        $data = $emModel->removeExtraSpace("exampage_amp_content_table", $examId);

        $libObj = $this->load->library('examPages/cache/ExamPageCache');

        $this->load->config('examPages/examPageConfig');
        $libObj->deleteExamCache(ExamBasicByName);
        $libObj->deleteExamCache('redirectExamsList');
        $libObj->deleteExamCache('listOfExams');

    }

    function convertRelativeURLToAbsoluteURL($examId = 0){
        $emModel = $this->load->model("examPages/exampagemodel");
        $data = $emModel->convertRelativeURLToAbsoluteURL("exampage_content_table", $examId);
        $data = $emModel->convertRelativeURLToAbsoluteURL("exampage_amp_content_table", $examId);

        $this->load->config('examPages/examPageConfig');

        $libObj = $this->load->library('examPages/cache/ExamPageCache');
        $libObj->deleteExamCache(ExamBasicByName);
        $libObj->deleteExamCache('redirectExamsList');
        $libObj->deleteExamCache('listOfExams');
    }

	/*
	@desc : Migrate all exams url from /exams/<examName> to <examName>-exam (except root Url)
	@JIRA : https://infoedge.atlassian.net/browse/MAB-4608
	@author : akhter
	@Live date : 10-10-2018
	*/    
	function migrateExamUrls(){
		$this->validateCron();
        ini_set('memory_limit','1000M');
        set_time_limit(0);

		$urlLibObj   = $this->load->library('common/UrlLib'); 
		$examPageLib = $this->load->library('examPages/ExamPageLib'); 
		$this->migrationModelObj = $this->load->model('exammigrationmodel');
		$examArr = $this->migrationModelObj->getAllExamIds();

		$batchSize = 100;
        $maxCount  = count($examArr);
        error_log("== MAX SIZE == ".$maxCount);
        $currentCount = 0;
        $data = array();
        while ($currentCount < $maxCount) {
            error_log("== CUUERNT COUNT == ".$currentCount);
            $sliceArr = array_slice($examArr, $currentCount, $batchSize);
            $currentCount += $batchSize;
            $examids = array();
            foreach ($sliceArr as $key => $value) {
            	$examids[] = $value['examId'];
            }
            $mappingData = $this->migrationModelObj->getExamMappingForURL($examids);
            $finalUrl  = array();
            foreach ($examids as $key => $examId) {
            	$finalData = array();
            	$finalData[$examId] = array_merge($examArr[$examId], (array)$mappingData[$examId]);	
            	unset($finalData[$examId]['examId']);
            	$url = $urlLibObj->getExamUrl($finalData[$examId]);
            	$url = str_replace('-exam-exam', '-exam', $url);
            	$data['id'] = $examId;
            	$data['newUrl'] = $url;
            	$finalUrl[] = $data;
            	unset($finalData);
            }
            $this->migrationModelObj->addExamUrl($finalUrl);
        }
        $examPageLib->invalidateExamCache();
        error_log("== Exam URL Migration Status :: Done ==");
	}
}