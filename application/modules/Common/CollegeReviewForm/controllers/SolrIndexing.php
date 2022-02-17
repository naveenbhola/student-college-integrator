<?php 

class SolrIndexing extends MX_Controller{
	private $chunkSize = 1000;

	function __construct(){
		$this->CRLib 	= $this->load->library('CollegeReviewForm/CollegeReviewLib');
	}
    function indexAllCollegeReviews($collegeReviewType=''){
        if($collegeReviewType!='' && $collegeReviewType!='CA')
        {
            echo 'Please provide right review type';
            die;
        }

    	//https://localshiksha.com/CollegeReviewForm/SolrIndexing/indexAllCollegeReviews
    	error_log("college review indexing start");
    	ini_set('memory_limit','512M');
    	set_time_limit(0);
    	$this->benchmark->mark('collegereview_indexing_start');

    	// index CR those are mapped to shiksha institute
    	$allCRIds = $this->CRLib->getAllCRIds($collegeReviewType);
    	error_log("Total CR review : ".count($allCRIds));
    	$crCount = 0;
    	$crIds = array();

    	// get rating params master data
        //_p(count($allCRIds));die;
    	foreach ($allCRIds as $allCRId) {
    		$crCount ++;
    		$crIds[] = $allCRId;
    		//if($crCount == $this->chunkSize){
    		if(($crCount % $this->chunkSize) == 0){
    			//echo 'crCount '.$crCount.'  '.print_r($crIds).'<br><br>';
    			$this->benchmark->mark('collegereview_bulk_indexing_start');
    			$response = $this->CRLib->indexCollegeReviews($crIds, $collegeReviewType);
                //_p($response);die;
                
    			$crIds = array();
    			//$crCount = 0;
    			$this->benchmark->mark('collegereview_bulk_indexing_end');
    			error_log("college review indexing done to - ".$crCount.' '.":: time taken - ".$this->benchmark->elapsed_time('collegereview_bulk_indexing_start', 'collegereview_bulk_indexing_end').' ::indexing response - '.$response[0]);
    		}
    	}
    	if(count($crIds) > 0){
    		//echo 'ff  '.$crCount.'  '.count($crIds).'<br>';
    		$this->benchmark->mark('collegereview_bulk_indexing_start');
    		$response = $this->CRLib->indexCollegeReviews($crIds, $collegeReviewType);
    		$this->benchmark->mark('collegereview_bulk_indexing_end');
    		error_log("college review indexing "." time taken - ".$this->benchmark->elapsed_time('collegereview_bulk_indexing_start', 'collegereview_bulk_indexing_end').' ::indexing response - '.$response[0]);
    	}

    	$this->benchmark->mark('collegereview_indexing_end');
    	error_log("college review indexing total time - ".$this->benchmark->elapsed_time('collegereview_indexing_start', 'collegereview_indexing_end'));
    }
	
	function insertReviewForIndex($reviewId){
        if($reviewId<1){
                return ;
        }
        $this->load->model('CollegeReviewForm/collegereviewmodel');
        $this->crmodel = new CollegeReviewModel();

        $insertData['operation'] = 'index';
        $insertData['listing_type'] = 'collegereview';
        $insertData['listing_id'] = $reviewId;

        $this->crmodel->insertReviewForIndex($insertData);

}


	function insertReviewForDelete($reviewId){
	if($reviewId<1){
                	return ;
        }
        $this->load->model('CollegeReviewForm/collegereviewmodel');
        $this->crmodel = new CollegeReviewModel();

        $insertData['operation'] = 'delete';
        $insertData['listing_type'] = 'collegereview';
        $insertData['listing_id'] = $reviewId;

        $this->crmodel->insertReviewForIndex($insertData);

	}

    function insertMultipleReviewsIntoIndexLog($reviewIds, $operation){
        if(empty($reviewIds) || empty($operation)){
            return;
        }

        $this->load->model('CollegeReviewForm/collegereviewmodel');
        $this->crmodel = new CollegeReviewModel();

        $insertData = array();
        foreach ($reviewIds as $reviewId) {
            $temp = array();
            $temp['operation'] = $operation;
            $temp['listing_type'] = 'collegereview';
            $temp['listing_id'] = $reviewId;
            $insertData[] = $temp;
        }
        $this->crmodel->insertMultipleReviewsForIndex($insertData);
    }

    function testSolrApi() {
        $this->CollegeReviewSolrClient = $this->load->library('CollegeReviewForm/solr/CollegeReviewSolrClient');
        // $solrRequestData['instituteId'] = array(24642, 307);
        $solrRequestData['courseId'] = array(1653, 52372);
        _p($solrRequestData);
        $this->CollegeReviewSolrClient->getAggregateReviews($solrRequestData);
    }
}

?>
