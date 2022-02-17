<div class="content col-lg-9 pR0">
    <?php
    $tuplenumber = 1;
    $rowBannerIndex_1 = 4;
    $rowBannerIndex_2 = 17;
    $rowBannerIndex_3 = 3;
    $rowBannerIndex_4 = 7;
    
    foreach ($institutes['instituteData'] as $instituteId => $instituteObj) {
    	$courseObj = reset($instituteObj->getCourses());
		$data = array();
        $data['course']    = $courseObj;
        if(is_object($data['course'])) {
			$courseId		   = $courseObj->getId();
        }
		$data['institute'] = $instituteObj;
		$data['tuplenumber'] = $tuplenumber;
        $data['brochureText'] = $brochureText;

        $data['reviewCount'] = $courseObj->getReviewCount();
		$data['aggregateReviewInfo'] = $aggregateReviewsData[$courseId];
		//_p($data['aggregateReviewInfo']);die;
		
		if(is_object($instituteObj) && is_object($data['course']) && !empty($courseId)) {
	    	echo "<input type='hidden' id='tuplenum_".$instituteId."' value=".$tuplenumber." />";
		 	$this->load->view('common/gridTuple/tupleContent', $data);
            if($tuplenumber == $rowBannerIndex_1 && $tupleListSource == "categoryPage"){
                /*$bannerZone['pageZone'] = 'SNIPPET_TOP';
                $this->load->view('categoryList/RNR/bms_banner', $bannerZone);*/
                echo "<div class='slotDiv'>";
                $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_BANNER1','bannerType'=>"content"));
                $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_BANNER2','bannerType'=>"content"));
                $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_BANNER3','bannerType'=>"content"));
                echo "</div>";
            }
            if($tuplenumber == $iimPredictorBannerIndex && $tupleListSource == "categoryPage" && $isMBAPGDMPage){
                $this->load->view('common/IIMCallPredictorBanner',array('productType'=>'categoryPage'));
            }
            if($tuplenumber == $rowBannerIndex_2 && $tupleListSource == "categoryPage"){
                /*$bannerZone['pageZone'] = 'SNIPPET_MIDDLE';
                $this->load->view('categoryList/RNR/bms_banner', $bannerZone);*/
                echo "<div class='slotDiv'>";
                $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_BANNER4','bannerType'=>"content"));
                $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_BANNER5','bannerType'=>"content"));
                $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_BANNER6','bannerType'=>"content"));
                echo "</div>";
            }
            if ($tuplenumber == $rowBannerIndex_3 && $tupleListSource == "searchPage"){
                $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'Slot1','bannerType'=>"content"));
            }
            if ($tuplenumber == $rowBannerIndex_4 && $tupleListSource == "searchPage"){
                $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'Slot2','bannerType'=>"content"));
            }
		 	$tuplenumber++;
		}
		else {
	      	error_log('CORRUPT institute id for the tuple: '.$instituteId);
	    }
	}
    if ($tuplenumber < $rowBannerIndex_3 && $tupleListSource == "searchPage"){
        echo "<div class='slotDiv'>";
        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_Slot1','bannerType'=>"content"));
        echo "</div>";
    }

	if(!empty($trackingFilterId)){
		echo "<input type='hidden' id='trackingFilterId' value={$trackingFilterId}>";
	}
?>  