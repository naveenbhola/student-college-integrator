<?php 


class CollegeCutoffControllerMobile extends ShikshaMobileWebSite_Controller{

	function getCutOffDetailPage($listingId, $category){
		$this->load->helper('mcommon5/mobile_html5');
		$this->collegecutofflibrary = $this->load->library('nationalInstitute/CollegeCutoffLibrary');

		$isAjaxRequest = $this->input->is_ajax_request();
		$start = 0;
		if($isAjaxRequest){
			$start = $this->input->post('start', true);
		}
		$displayData = $this->collegecutofflibrary->getDataForCutoffPage($listingId,$category,$start);
		$displayData['boomr_pageid'] = 'college_cutoff_page';
		if($isAjaxRequest){
			$tupleData = $this->load->view('mobile_listing5/CollegeCutoffTuplesMobile',$displayData,true);	
			echo json_encode($tupleData);
			return;
		}

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam =array('parentPage'=>'DFP_CUT_OFF_PAGE','pageType'=>$displayData['instituteType'].'_cutoff','entity_id'=>$listingId,'ownership'=>$displayData['ownership']);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');
        
		$this->load->view('mobile_listing5/CollegeCutoffMain',$displayData);
	}
}
?>