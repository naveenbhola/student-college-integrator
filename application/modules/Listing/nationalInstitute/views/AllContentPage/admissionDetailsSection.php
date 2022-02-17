<div id="allContentTuple" class="admissionpage">
<div class="col-md-8 no-padLft left-widget">
<?php $this->load->view('AllContentPage/widgets/admissionDetails'); ?>
<?php $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2','bannerType'=>"content"));
	$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2_2','bannerType'=>"content"));
 ?>
<div id="admissionLowerLeftSection">
<?php $this->load->view('AllContentPage/widgets/admissionLowerLeftSection'); ?>
</div>
</div>
</div>