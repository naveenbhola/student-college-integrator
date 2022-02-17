<div style="display:none">
	<img id='beacon_img' src="<?php echo IMGURL_SECURE; ?>/public/images/blankImg.gif" width=1 height=1 >
</div>
	<?php
    $this->load->view('widgets/examPageDetails');
    if($examPageObj->getDownloadLink()){
		$this->load->view('widgets/guideDownloadSection');
		$this->load->view('widgets/guideDownloadSticky');
	}
    $this->load->view('widgets/exploreMoreSection');
    //$this->load->view('widgets/commentsSection'); 
   ?> 
