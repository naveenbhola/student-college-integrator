<?php
/**
 * This library is will be used to manage trending searches in autosuggestor
 * @package     Listing
 * @author      Ankit Garg
 *
 */

class TrendingSearchlib {

	const DAYS_DIFFERENCE_CAREER         = 15;
	const DAYS_DIFFERENCE_EXAM           = 15;
	const DAYS_DIFFERENCE_COLLEGE_COURSE = 15;

	function __construct() {
		$this->CI =& get_instance();
		$this->ListingCommonCache = $this->CI->load->library('listingCommon/cache/ListingCommonCache');
	}

	function init() {
		$this->trendingsearchmodel = $this->CI->load->model('listingCommon/trendingsearchmodel');
		$this->CI->load->builder('CareerBuilder','Careers');
		$careerBuilder             = new CareerBuilder;
		$this->careerRepository    = $careerBuilder->getCareerRepository();
		$this->examLib             = $this->CI->load->library('examPages/ExamMainLib');
	}

	function storeTrendingSearches() {
		$this->init();
		$trendingSearches = $this->prepareTrendingSearchesData();
		//storing into cache
		foreach ($trendingSearches as $key => $value) {
			$this->ListingCommonCache->storeTrendingSearches($key, $value);
		}
	}


	function prepareTrendingSearchesData() {
		$trendingSearches                     = array();
		
		$trendingSearches['career']           = $this->prepareTrendingSearchesForCareer();
		$trendingSearches['exam']             = $this->prepareTrendingSearchesForExam();
		$trendingSearches['collegeAndCourse'] = $this->getTrendingSearchesForCollegeAndCourse();
		return $trendingSearches;
	}

	function createDateInterval($dateInterval) {
		$date = new DateTime();
		$date->sub(new DateInterval('P'.$dateInterval.'D'));
		return $date->format('Y-m-d');
	}

	function prepareTrendingSearchesForCareer() {
		$dateInterval           = $this->createDateInterval(self::DAYS_DIFFERENCE_CAREER); 
		$trendingSearchesFromDb = $this->trendingsearchmodel->getTrendingSearchesForCareer($dateInterval);
		
		$careersList = array();
		foreach ($trendingSearchesFromDb as $key => $value) {
			$careersList[] = $value['careerId'];
		}
		$careerData = $this->careerRepository->findMultiple($careersList);
		$trendingSearches = array();
		foreach ($trendingSearchesFromDb as $career) {
			if($careerData[$career['careerId']]->getName() != '') {
				$trendingSearches['nameIdMap'][$careerData[$career['careerId']]->getName()] = $career['careerId'];
				$trendingSearches['trendingSearches'][$careerData[$career['careerId']]->getName()] = $careerData[$career['careerId']]->getCareerUrl();
			}
		}
		return $trendingSearches;
	}

	function prepareTrendingSearchesForExam() {
		$dateInterval            = $this->createDateInterval(self::DAYS_DIFFERENCE_EXAM);
		$trendingSearchesForExam = $this->trendingsearchmodel->getTrendingSearchesForExam($dateInterval);
		$this->CI->load->model("indexer/NationalIndexingModel");
		$this->nationalIndexingModel = new NationalIndexingModel();
		$examPageList = array();
		$allExamPageList = array();
		foreach ($trendingSearchesForExam as $key => $value) {
			//all exam
			list($examType, $entity) = explode(':',$value['type']);
			if($examType == 'allexam' && !empty($entity)) {
				$trendingSearchesForExam[$key]['type'] = 'allexam';	
				$trendingSearchesForExam[$key]['entity'] = $entity;	
			}
			else {
				$trendingSearchesForExam[$key]['type'] = 'exam';	
				$examPageList[] = $value['examId'];
			}
		}
		
		if(!empty($examPageList)) {
			$examPageDataFromDb = $this->examLib->getExamDataByExamIds($examPageList);
		}

		$trendingSearches = array();
		foreach ($trendingSearchesForExam as $key => $examData) {
			if($examData['type'] == 'exam' && !empty($examPageDataFromDb[$examData['examId']]) && !empty($examPageDataFromDb[$examData['examId']]['name'])) {
				$trendingSearches[$examPageDataFromDb[$examData['examId']]['name']] = array('id' => $examData['examId'],
												'name' => $examPageDataFromDb[$examData['examId']]['name'],
												'type' => $examData['type'],
												'url' =>  $examPageDataFromDb[$examData['examId']]['url']);
			}
			else if($examData['type'] == 'allexam') {
				$url = $this->examLib->getAllExamPageUrlByEntity($examData['entity'], $examData['examId']);
				$anchorText = $this->nationalIndexingModel->fetchAllEntities($examData['entity'],array($examData['examId']));
				if(empty($anchorText[$examData['examId']]['name'])) {
					continue;
				}

				$trendingSearches[$anchorText[$examData['examId']]['name'].' Exams'] = array('id' => $examData['examId'], 
										   'name' => $anchorText[$examData['examId']]['name'].' Exams',
										   'type' => $examData['type'],
										   'subType' => $examData['entity'],
										   'url' => $url);
			}
		}
		
		return $trendingSearches;
	}

	function getTrendingSearchesForCollegeAndCourse() {
		$dateInterval                = $this->createDateInterval(self::DAYS_DIFFERENCE_COLLEGE_COURSE); 
		$closedTrendingSearchFromDb  = $this->trendingsearchmodel->getTrendingSearchesForCourse($dateInterval, 'close');
		//$openTrendingSearchFromDb    = $this->trendingsearchmodel->getTrendingSearchesForCourse($dateInterval, 'open');
		$collegeTrendingSearchFromDb = $this->trendingsearchmodel->getTrendingSearchesForCollege($dateInterval);
		
		$collegeAndCourseSearches    = array_merge($closedTrendingSearchFromDb,  $collegeTrendingSearchFromDb);
		
		$this->CI->load->model("indexer/NationalIndexingModel");
		$this->nationalIndexingModel = new NationalIndexingModel();
		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder    = new InstituteBuilder();
		$this->instituteRepo = $instituteBuilder->getInstituteRepository(); 

        $instituteIds = array();
        foreach ($collegeTrendingSearchFromDb as $collegeSearches) {
        	$instituteIds[] = $collegeSearches['instituteId'];
        }
        $instituteData = $this->instituteRepo->findMultiple($instituteIds);
		usort($collegeAndCourseSearches, function($a, $b) {
			return $a['viewCount'] <= $b['viewCount'] ? 1 : -1;
		});
		
		foreach ($collegeAndCourseSearches as $searchData) {
			// closed search
			if(!empty($searchData['entityId'])) {
				switch ($searchData['entityType']) {
					case 'baseCourse':
						$entity = 'base_course';
						$returnType = 'bc';
						break;
					case 'stream':
						$entity = $searchData['entityType'];
						$returnType = 's';
						break;
					case 'substream':
						$returnType = 'sb';
						$entity = $searchData['entityType'];
						break;
					case 'specialization':
						$returnType = 'sp';
						$entity = $searchData['entityType'];
						break;
				}
				if(empty($entity)) {
					continue;
				}
				//$entityName = $this->nationalIndexingModel->fetchAllEntities($entity,array($searchData['entityId']));
				if($searchData['keyword'] != 'MBA/PGDM') {
					$trendingSearches[$searchData['keyword']] = $returnType.'_'.$searchData['entityId'];
				}
			}
			//institute search
			else if(!empty($searchData['instituteId'])) {
				if(!empty($instituteData[$searchData['instituteId']]) && $instituteData[$searchData['instituteId']]->getId() != '') {
					$trendingSearches[$instituteData[$searchData['instituteId']]->getName()] = 'i_'.$instituteData[$searchData['instituteId']]->getId();
				}
			}
			//open search
			else if(!empty($searchData['keyword'])) {
				if($searchData['keyword'] != 'MBA/PGDM') {
					$trendingSearches[$searchData['keyword']] = "open";
				}
			}
		}
		
		return $trendingSearches;
	}

	function getTrendingSearches($type) {
		return $this->ListingCommonCache->getTrendingSearches($type);
	}

	function validateAndUpdateTrendingSearches($id, $section) {
		$trendingSearches = $this->getTrendingSearches($section);
		if(empty($trendingSearches)) {
			return;
		}
		switch ($section) {
			case 'career':
				$trendingSearches['nameIdMap'] = $this->validateTrendingSearchForSection($trendingSearches['nameIdMap'], $id);
				$trendingSearches['trendingSearches'] = $this->validateTrendingSearchForSection($trendingSearches['trendingSearches'], $id);
				break;
			case 'collegeAndCourse':
				$trendingSearches = $this->validateTrendingSearchForSection($trendingSearches, 'i_'.$id);
				break;
		}
		
		if(!empty($trendingSearches)) {
			$this->ListingCommonCache->storeTrendingSearches($section, $trendingSearches);
		}
	}

	function validateTrendingSearchForSection($data, $id) {
		foreach ($data as $key => $value) {
			if($value == $id) {
				unset($data[$key]);
				break;
			}
		}
		return $data;
	}
}