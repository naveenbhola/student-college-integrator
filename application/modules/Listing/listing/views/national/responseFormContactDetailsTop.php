<?php
	$data['widget'] = "listingPageTopLinks";
	$data['class'] = "2";
	$data['align'] = "left";
	$data['width'] = '400px';
        $data['customCallBack'] = 'onSubmitResponseFormContactDetails';
    $data['tracking_keyid'] = $pageType == 'course' ? DESKTOP_NL_LP_COURSE_TOP_SEND_CONTCT_DTLS : DESKTOP_NL_LP_INST_TOP_SEND_CONTCT_DTLS;
	$this->load->view('listing/national/widgets/responseFormContactDetails', $data);
?>