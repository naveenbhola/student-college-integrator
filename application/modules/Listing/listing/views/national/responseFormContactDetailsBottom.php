<?php
	$data['widget'] = "listingPageBottomNew";
	$data['class'] = "2";
	$data['align'] = "left";
	$data['width'] = '500px';
        $data['customCallBack'] = 'onSubmitResponseFormContactDetails';
        $data['tracking_keyid'] = $pageType == 'course' ? DESKTOP_NL_LP_COURSE_BOTTOM_SEND_CONTCT_DTLS : DESKTOP_NL_LP_INST_BOTTOM_SEND_CONTCT_DTLS;
	$this->load->view('listing/national/widgets/responseFormContactDetails', $data);
?>