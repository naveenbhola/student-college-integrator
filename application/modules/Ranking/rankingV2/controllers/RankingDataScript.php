<?php 
class RankingDataScript extends MX_Controller{
	function __construct(){
		$this->objPHPExcel  = $this->load->library('common/PHPExcel');
		$this->rankingModel = $this->load->model('ranking_model');
		$this->load->builder("nationalCourse/CourseBuilder");
	}
	public function putDefaultRankDataInDB(){
		$this->validateCron();
		$inputFileName = '/var/www/html/shiksha/mediadata/reports/RankingData.xlsx';

		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);
		$objPHPExcel  = $objReader->load($inputFileName);

		$builder = new CourseBuilder();
		$courseRepository = $builder->getCourseRepository();

		$streamArr = array('MBA'=>1, 'Btech'=>2);
		//btech 10
		//mba 101,75

		//Loop over excel sheets
		for($i = 0; $i < 2; $i++) {
			$instIdArr = $instRankArr = array();
			$objWorksheet = $objPHPExcel->getSheet($i);
			$sheetName = $objPHPExcel->getSheet($i)->getTitle();
			$highestRow   = $objWorksheet->getHighestDataRow();
			$streamId = $streamArr[$sheetName];

			//Loop over cells
			for($row = 2; $row <= $highestRow; ++$row) {
				$instId = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
				$instIdArr[$instId]   = $instId;
				$instRankArr[$instId] = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
			}
			$rankingPageData = $this->rankingModel->getRankingPageCourseIds($instIdArr);
			
			$allCourseId = array_map(function($val){return $val['course_id'];}, $rankingPageData);
			$allCourseId = array_unique($allCourseId);

			$courseObjects = $courseRepository->findMultiple($allCourseId);
			$courseStreamMap = $courseStreamMap1 = array();
			foreach ($courseObjects as $cid => $value) {
				$primaryStream = $value->getPrimaryHierarchy();
				$baseCourse = $value->getBaseCourse();
				if($baseCourse['entry'] == 10 && $streamId == 2){
					$courseStreamMap[$cid] = $baseCourse['entry'];
				}else if(($baseCourse['entry'] == 101 || $baseCourse['entry'] == 75) && $streamId == 1){
					$courseStreamMap[$cid] = $baseCourse['entry'];
				}
			}
			$sourceId = 27;
			$insertBatch = array();
			foreach ($rankingPageData as $val) {
				if($courseStreamMap[$val['course_id']] == 10 && $streamId ==2){
					$insertBatch[] = array(
							'ranking_page_course_id' => $val['id'],
							'source_id' => $sourceId,
							'rank' => $instRankArr[$val['institute_id']]
						);
				}else if(($courseStreamMap[$val['course_id']] == 101 || $courseStreamMap[$val['course_id']] == 75) && $streamId ==1){
					$insertBatch[] = array(
							'ranking_page_course_id' => $val['id'],
							'source_id' => $sourceId,
							'rank' => $instRankArr[$val['institute_id']]
						);
				}
			}
			$this->rankingModel->indexCourseForRanking(array_keys($courseStreamMap), 'Update from Default Rank Script');
			$doneFlag = $this->rankingModel->putRankingPageCourseSourceRank($insertBatch);
			if($doneFlag){
				echo 'Done for Sheet titled "' . $sheetName . '"<br/>';
			}
		}
		return;
	}
}
?>