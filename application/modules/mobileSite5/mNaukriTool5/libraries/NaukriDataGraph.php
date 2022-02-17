<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NaukriDataGraph {

	function __construct()
	{
	    $this->CI=& get_instance();
	}		

	/***
	 * functionName : prepareNaukriDataGraph
	 * functionType : return type
	 * param        : naukriDataIns (array);
	 * desciption   : manage naurki data based on course subcategory allow only 23 (full time mba)
	 * @author      : akhter
	 * @team        : UGC
	***/
	function prepareNaukriDataGraph($naukriDataIns){
		$this->CI->load->model('nationalInstitute/institutedetailsmodel');
		foreach($naukriDataIns as $courseId => $value) {
			$result = $this->CI->institutedetailsmodel->getAllNaukriSalaryData(array($value['institute']));
			$data[$courseId] = $result[$value['institute']];
		}
		return $data;
	}

	/***
	 * functionName : mangeCourseIndex
	 * functionType : return type
	 * param        : finalData (array);
	 * desciption   : maintain index if course does not have any data but we keep index with blank data for view
	 * @author      : akhter
	 * @team        : UGC
	***/
	function manageCourseIndex($naukriDataIns, $data){
		foreach (array_keys($naukriDataIns) as $courseId) {
			if($data[$courseId]['AvgCTC'] !='' || $data[$courseId]['AvgCTC'] ==''){
			    $finalData[$courseId] = ($data[$courseId]['AvgCTC'] !='') ? $data[$courseId] : array('AvgCTC'=>0);
			}
		}
		return $finalData;
	}
}
?>