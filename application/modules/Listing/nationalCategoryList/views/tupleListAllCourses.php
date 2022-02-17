<div class="content col-lg-9 pR0">
    <?php 

    $tuplenumber = 1;
    foreach ($institutes['courseData'] as $courseObj) {

		
      	$data['course']    = $courseObj;
      	$courseId			= $courseObj->getId();
		$data['institute'] = $instituteObj;
		$data['tuplenumber'] = $tuplenumber;

		$data['reviewCount'] = $courseObj->getReviewCount();
		$data['aggregateReviewInfo'] = $aggregateReviewsData[$courseId];
		//_p($data['aggregateReviewInfo']);die;
		
		if(is_object($instituteObj) && is_object($data['course']) && !empty($courseId)) {
	    	echo "<input type='hidden' id='tuplenum_".$instituteObj->getId()."' value=".$tuplenumber." />";
		 	$this->load->view('common/gridTuple/tupleContent', $data);
		 	if($tuplenumber == 3)
		 	{
				$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1','bannerType' => 'content'));	 		
		 	}
		 	if($tuplenumber == 7)
		 	{
				$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1','bannerType' => 'content'));	 		
		 	}
		 	$tuplenumber++;

		} 
		else {
	      	error_log('CORRUPT institute id for the tuple: '.$instituteObj->getId());
	    }
	}
	if($tuplenumber <= 4)
	{
		$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1','bannerType' => 'content'));
	}
?>  