<?php 
class ExamMain extends MX_Controller {
	private function _init() {
		$this->load->library("examPages/ExamMainLib");
	}

	public function getAllMainExams($returnType = 'array', $examPageExists = ''){
		$this->_init();
		$filter = array();
		$this->ExamMainLib = new ExamMainLib();
		$filter['examPageExists'] = $examPageExists;
		$data = $this->ExamMainLib->getExamsList($filter, $returnType);
		return $data;
	}

	public function getAllMainExamsByBaseEntities($baseEntitiesArr = array()){
		$this->_init();
		$this->ExamMainLib = new ExamMainLib();
		/*$baseEntitiesArr = array(
				array('streamId'=>3, 'substreamId'=>5), 
				array('streamId'=>2, 'substreamId'=>65)
				);*/
		$hasExamPages = '';
		$returnType = 'array';
		$data = $this->ExamMainLib->getAllMainExamsByBaseEntities($baseEntitiesArr, $hasExamPages, $returnType);
		return $data;
	}

	public function getAllMainExamsByHierarchyIds($hierarchyIdArr = array()){
		$this->_init();
		$this->ExamMainLib = new ExamMainLib();
		//$hierarchyIdArr = array(146,147);
		$hasExamPages = '';
		$returnType = 'array';
		$data = $this->ExamMainLib->getAllMainExamsByHierarchyIds($hierarchyIdArr, $hasExamPages, $returnType);
		return $data;
	}

	public function getAllMainExamsByAllCombinations($baseEntityArr = array(), $baseCourses = array()){
		$this->_init();
		$this->ExamMainLib = new ExamMainLib();
		$hasExamPages = '';
		$returnType = 'array';
	/*	$baseEntityArr =  array(
                        0 => array(
                            'streamId' => 2,
                            'substreamId'=>'any',
                            'specializationId'=>1
                        ));
	
		$baseCourses = array(9);
	*/
		$data = $this->ExamMainLib->getAllMainExamsByAllCombinations($baseEntityArr, $baseCourses, $hasExamPages, $returnType);
		return $data;
	}

	public function getExamDetails($examId){
		$this->_init();
		$this->ExamMainLib = new ExamMainLib();
		//$examId = 4;
		//$examId = array(1,0,'',4,8,'sd','9',array(4),6);
		$examId = explode(',', $examId);
		$data = $this->ExamMainLib->getExamDetailsByIds($examId);
		_p($data);
		return $data;
	}

	/*This function is the part of migration script. We will remove this function from this file once migration is done on production.*/

	public function findExamHavingNotMappingCourse(){
		$this->_init();
                $this->ExamPageModel = $this->load->model('examPages/exampagemodel'); die('dada');
                $hiearchyToExamMappingArr = $this->ExamPageModel->getHiearchyWithExams();

	}

        public function migrationScript(){
                $this->_init();
                $this->ExamPageModel = $this->load->model('examPages/exampagemodel');
                $examToBaseCourseMappingArr = $this->ExamPageModel->getExamToBaseCourseMapping();
                $listOfCourseIds = array();

                foreach($examToBaseCourseMappingArr as $key=>$courseIds){
                        $listOfCourseIds[] = $courseIds;
                }
                $this->load->builder('listingBase/ListingBaseBuilder');
                $ListingBaseBuilder = new ListingBaseBuilder();
                $this->basecourseRepo = $ListingBaseBuilder->getBaseCourseRepository();
                $courseObj = $this->basecourseRepo->findMultiple($listOfCourseIds);
                foreach($examToBaseCourseMappingArr as $key=>$value){
                        $examToBaseCourseMappingArr[$key] = $courseObj[$value]->getName();
                }
                $returnData = $this->ExamPageModel->createGroupsAndMappingWithExams($examToBaseCourseMappingArr);
                echo "Base Course Migration ". $returnData;

		$this->ExamPageModel->migrateExamFullName();
		echo "Full Form Exam Name Migration Done";
        }
	
	public function migrationScriptOther(){
                $this->_init();
                $this->ExamPageModel = $this->load->model('examPages/exampagemodel');
                $this->ExamPageModel->updateData();
                echo 'Done';
        }

	public function migrateExamContentUrl(){
    		$this->_init();
        	$this->ExamPageModel = $this->load->model('examPages/exampagemodel');
	        $this->ExamPageModel->migrateExamContentUrl();
        	echo 'Done';	
    }
}
