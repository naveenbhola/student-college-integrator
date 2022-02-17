<?php 
require 'AppMonitorAbstract.php';

class SearchTracking extends AppMonitorAbstract{
	function __construct(){
		parent::__construct();

		$this->trackModel = $this->load->model('searchmatrix/searchmatrixmodel');

		$this->reportLinks = array(
		    'trends' => array('Trends', '/AppMonitor/SearchTracking/trends'),
		    'zrpDetails' => array('ZRP Details','/AppMonitor/SearchTracking/zrpDetailedReport'),
		    'criteriaReduction' => array('Criteria Reduction','/AppMonitor/SearchTracking/criteriaReductionDetailedReport')
		);
	}

	public function trends(){
		$displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SEARCH_TRACKING;
		$displayData['reportType'] = 'trends';

		$toDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d",strtotime("-1 day"));
		$fromDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-15 day"));

		$displayData['trendStartDate'] = date("m/d/Y", strtotime($fromDate));
		$displayData['trendEndDate'] = date("m/d/Y", strtotime($toDate));
		$displayData['device'] = $_REQUEST['device'] ? $_REQUEST['device'] : '';

		$displayData['reportLinks'] = $this->reportLinks;

		$displayData['chartKeys'] = array('searchZrps','getFiltersCounts','getTotalFilterCounts','getTupleClicksAfterFilters','getTotalTupleClickCounts','getAdvancedFilterCounts','getCriteriaCounts');
		// $displayData['chartKeys'] = array('getAdvancedFilterCounts');

		$this->load->view("AppMonitor/searchtracking/trends", $displayData);
	}

	public function zrpDetailedReport(){
		$displayData = array();
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SEARCH_TRACKING;
		$displayData['reportType'] = 'zrpDetails';
		$displayData['reportLinks'] = $this->reportLinks;
		$displayData['ajaxURL'] = "/AppMonitor/SearchTracking/getZRPDetails";
		$displayData['defaultDate'] = $this->detailedReportDate;
		$displayData['sorters'] = array('count' => 'Number of Occurrences','created' => 'Occurence Time');
		$displayData['filters'] = array(array('options'=>array('' => 'Select','desktop' => 'Desktop','mobile' => 'Mobile'),'label'=>'Device','filterKey'=>'device'));
		
		$this->load->view("AppMonitor/common/detailedReport", $displayData);
	}

	public function getZRPDetails(){
		$params = array();
		$fromDate = $this->input->post('fromdate');
		$fromDate = explode('/',$fromDate);
		$params['fromDate'] = $fromDate[2]."-".$fromDate[0]."-".$fromDate[1];
		$toDate = $this->input->post('todate');
		$toDate = explode('/',$toDate);
		$params['toDate'] = $toDate[2]."-".$toDate[0]."-".$toDate[1];
		$params['orderBy'] = $this->input->post("orderby");
		$params['device'] = $this->input->post("device");
		// _p($params);die;

		$data = $this->trackModel->getZRPDetails($params);
		$displayData = array();
		$displayData['data'] = $data;
		$displayData['reportType'] = 'zrpDetails';
		$this->load->view("AppMonitor/searchtracking/detailedReport", $displayData);
	}

	public function criteriaReductionDetailedReport(){
		$displayData = array();
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SEARCH_TRACKING;
		$displayData['reportType'] = 'criteriaReduction';
		$displayData['reportLinks'] = $this->reportLinks;
		$displayData['ajaxURL'] = "/AppMonitor/SearchTracking/getCriteriaReductionDetails";
		$displayData['defaultDate'] = $this->detailedReportDate;
		$displayData['sorters'] = array('' => 'Select','relax' => 'Relax','spellcheck' => 'Spellcheck','relaxandspellcheck' => 'Relax and Spellcheck');
		$displayData['filters'] = array(array('options'=>array('' => 'Select','desktop' => 'Desktop','mobile' => 'Mobile'),'label'=>'Device','filterKey'=>'device'));
		
		$this->load->view("AppMonitor/common/detailedReport", $displayData);
	}

	public function getCriteriaReductionDetails(){
		$params = array();
		$fromDate = $this->input->post('fromdate');
		$fromDate = explode('/',$fromDate);
		$params['fromDate'] = $fromDate[2]."-".$fromDate[0]."-".$fromDate[1];
		$toDate = $this->input->post('todate');
		$toDate = explode('/',$toDate);
		$params['toDate'] = $toDate[2]."-".$toDate[0]."-".$toDate[1];
		$params['criteria'] = $this->input->post("orderby");
		$params['device'] = $this->input->post("device");
		// _p($params);die;

		$data = $this->trackModel->getCriteriaReductionDetails($params);
		$displayData = array();
		$displayData['data'] = $data;
		$displayData['reportType'] = 'criteriaReduction';
		$this->load->view("AppMonitor/searchtracking/detailedReport", $displayData);	
	}

	public function getFiltersListByIds(){
		$searchIds = $this->input->post('searchIds');
		$searchIds = explode(',',$searchIds);
		$params['searchIds'] = $searchIds;
		$params['fromDate'] = $this->input->post('fromDate');
		$params['toDate'] = $this->input->post('toDate');
		$params['orderBy'] = 'created';
		$keyword = $this->input->post('keyword');
		$data = $this->trackModel->getFiltersAppliedWhenSearched($params);
		$this->load->library('search/Searcher/SolrSearcher');
		$solrSearcher = new SolrSearcher;
		$qerFilters = $solrSearcher->getQERFiltersForSearch($keyword);
		$this->load->view("AppMonitor/searchtracking/showFiltersDataTable",array('data'=>$data,'keyword'=>$keyword,'qerFilters'=>$qerFilters));
	}

	public function searchZrpsTrends(){
		$data = array();
		$data['fromDate'] = $this->input->post('fromDate');
		$data['toDate'] = $this->input->post('toDate');
		$data['pageType'] = $this->input->post('pageType');
		$data['device'] = $this->input->post('device');
		// _p($data);die;
		$trendData['total_searches'] = $this->trackModel->getDayWiseUniqueVisits($data);
		$temp = $this->trackModel->getDayWiseZrps($data);
		if(!empty($temp)){
			$trendData['total_zrps'] = $temp;
		}
		$temp = $this->trackModel->getDayWiseFilterAppliedCounts($data);
		if(!empty($temp)){
			$trendData['filters_applied_searches'] = $temp;
		}
		$temp = $this->trackModel->getDayWiseAdvancedOptionsAppliedCounts($data);
		if(!empty($temp)){
			$trendData['advanced_options_applied_searches'] = $temp;
		}
		$data = $this->processMultipleTrendData($trendData,'total_searches');
		// _p($data);die;
		$columns = array_keys($trendData);
		$returnData = array();
		$returnData['data'] = $data;
		$returnData['title'] = 'Graph showing different trends for '.ucfirst($data['pageType']).' search';
		$returnData['columns'] = $this->populateLabelsWithTotalCounts($data,$columns);
		echo json_encode($returnData);die;
	}

	public function getTotalFilterCountsTrends(){
		$params = array();
		$params['fromDate'] = $this->input->post('fromDate');
		$params['toDate'] = $this->input->post('toDate');
		$params['pageType'] = $this->input->post('pageType');
		$params['device'] = $this->input->post('device');

		$data = $this->trackModel->getDayWiseTotalFilterCounts($params);
		// _p($data);die;
		$columns = array_keys($data);
		$trendData = array();
		foreach ($data['overAll'] as $date => $count) {
			$temp = array();
			foreach ($columns as $column) {
				if($column != 'overAll'){
					$temp[] = empty($data[$column][$date]) ? 0 : $data[$column][$date];
				}
			}
			$trendData[$date] = $temp;
		}
		// _p($trendData);die;
		$columns = array_values(array_diff($columns,array('overAll')));
		$returnData = array();
		$returnData['columns'] = $this->populateLabelsWithTotalCounts($trendData,$columns);
		$returnData['title'] = 'Graph showing total number of times a particular filter is used';
		$returnData['data'] = $trendData;
		$returnData['height'] = 650;
		echo json_encode($returnData);die;
	}

	public function getFiltersCountsTrends(){
		$params = array();
		$params['fromDate'] = $this->input->post('fromDate');
		$params['toDate'] = $this->input->post('toDate');
		$params['pageType'] = $this->input->post('pageType');
		$params['device'] = $this->input->post('device');
		
		$data = $this->trackModel->getDayWiseFilterCounts($params);
		// _p($data);die;
		$columns = array_keys($data);
		$trendData = array();
		foreach ($data['overAll'] as $date => $count) {
			$temp = array();
			foreach ($columns as $column) {
				if($column != 'overAll'){
					$temp[] = empty($data[$column][$date]) ? 0 : $data[$column][$date];
				}
			}
			$trendData[$date] = $temp;
		}
		$columns = array_values(array_diff($columns,array('overAll')));
		$returnData = array();
		$returnData['columns'] = $this->populateLabelsWithTotalCounts($trendData,$columns);
		$returnData['title'] = 'Graph showing number of searches where atleast one filter is applied';
		$returnData['data'] = $trendData;
		$returnData['height'] = 650;
		echo json_encode($returnData);die;
	}

	public function getTupleClicksAfterFiltersTrends(){
		$params = array();
		$params['fromDate'] = $this->input->post('fromDate');
		$params['toDate'] = $this->input->post('toDate');
		$params['pageType'] = $this->input->post('pageType');
		$params['device'] = $this->input->post('device');

		$data = $this->trackModel->getDayWiseTupleClicksAfterFiltersCounts($params);
		$returnData = array();
		$returnData['columns'] = $this->populateLabelsWithTotalCounts($data,'count');
		$returnData['title'] = 'Graph showing number of searches where tupleclicks happened after applying filters';
		$returnData['data'] = $data;
		echo json_encode($returnData);die;
	}

	public function getTotalTupleClickCountsTrends(){
		$params = array();
		$params['fromDate'] = $this->input->post('fromDate');
		$params['toDate'] = $this->input->post('toDate');
		$params['pageType'] = $this->input->post('pageType');
		$params['device'] = $this->input->post('device');
		
		$data = $this->trackModel->getDayWiseTotalTupleClickCounts($params);
		// _p($data);die;
		$columns = array_keys($data);
		$trendData = array();
		foreach ($data['overAll'] as $date => $count) {
			$temp = array();
			foreach ($columns as $column) {
				if($column != 'overAll'){
					$temp[] = empty($data[$column][$date]) ? 0 : $data[$column][$date];
				}
			}
			$trendData[$date] = $temp;
		}
		$columns = array_values(array_diff($columns,array('overAll')));
		$returnData = array();
		$returnData['columns'] = $this->populateLabelsWithTotalCounts($trendData,$columns);
		$returnData['title'] = 'Graph showing number of tuple clicks grouped by action points';
		$returnData['data'] = $trendData;
		echo json_encode($returnData);die;
	}

	public function getAdvancedFilterCountsTrends(){
		$params = array();
		$params['fromDate'] = $this->input->post('fromDate');
		$params['toDate'] = $this->input->post('toDate');
		$params['pageType'] = $this->input->post('pageType') ? $this->input->post('pageType') : 'course';
		$params['device'] = $this->input->post('device');
		
		$data = $this->trackModel->getDayWiseAdvancedFiltersCounts($params);
		// _p($data);die;
		$columns = array_keys($data);
		if(!empty($data['overAll'])){
			$trendData = array();
			foreach ($data['overAll'] as $date => $count) {
				$temp = array();
				foreach ($columns as $column) {
					if($column != 'overAll'){
						$temp[] = empty($data[$column][$date]) ? 0 : $data[$column][$date];
					}
				}
				$trendData[$date] = $temp;
			}
			$columns = array_values(array_diff($columns,array('overAll')));
		}
		else{
			$trendData = $this->processMultipleTrendData($data,'stream');
		}
		$returnData = array();
		$returnData['columns'] = $this->populateLabelsWithTotalCounts($trendData,$columns);
		$returnData['title'] = 'Graph showing number of searches where a particular advanced option is used';
		$returnData['data'] = $trendData;
		$returnData['height'] = 450;
		echo json_encode($returnData);die;
	}

	public function getCriteriaCountsTrends(){
		$params = array();
		$params['fromDate'] = $this->input->post('fromDate');
		$params['toDate'] = $this->input->post('toDate');
		$params['pageType'] = $this->input->post('pageType') ? $this->input->post('pageType') : 'spellcheck';
		$params['device'] = $this->input->post('device');

		$data = $this->trackModel->getDayWiseCriterionUsed($params);
		$columns = array_keys($data);
		$trendData = $this->processMultipleTrendData($data,'criteria');
		$returnData = array();
		$returnData['columns'] = $this->populateLabelsWithTotalCounts($trendData,$columns);
		$returnData['title'] = 'Graph showing how successfull criteria reduction is working';
		$returnData['data'] = $trendData;
		echo json_encode($returnData);die;
	}

	private function processMultipleTrendData($trendData,$keyName){
		$returnData = array();
		foreach ($trendData[$keyName] as $date => $count) {
			$returnData[$date][] = $count;
			foreach ($trendData as $key => $value) {
				if($key != $keyName && !empty($value)){
					$returnData[$date][] = empty($trendData[$key][$date]) ? 0 : $trendData[$key][$date];
				}
			}
		}
		return $returnData;
	}

	private function getPercentageZRPs($searches,$zrps){
		$returnData = array();
		foreach ($searches as $date => $row) {
			if(!empty($row['count'])){
				$returnData[$date] = ((intval($zrps[$date]['count']))/$row['count'])*100;
			}
		}
		return $returnData;
	}

	private function populateLabelsWithTotalCounts($trendData,$columns){
		$returnData = array();
		if(is_array($columns)){
			foreach ($columns as $index => $column) {
				$total = 0;
				foreach ($trendData as $date => $data) {
					$total += $data[$index];
				}
				$returnData[] = $column.' ('.$total.')';
			}
		}
		else{
			$total = 0;
			foreach ($trendData as $date => $data) {
				$total += $data;
			}
			$returnData[] = $columns.' ('.$total.')';
		}
		return $returnData;
	}
}
?>
