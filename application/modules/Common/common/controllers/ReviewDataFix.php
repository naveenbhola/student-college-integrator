<?php

class ReviewDataFix extends MX_Controller{

	function __construct() {
		$this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
		$this->model = $this->load->model('common/reviewdatafixmodel');
	}

	public function checkAllReviewData(){
		ini_set('memory_limit','2048M');
		$this->model->checkAllReviewData();    
	}
}