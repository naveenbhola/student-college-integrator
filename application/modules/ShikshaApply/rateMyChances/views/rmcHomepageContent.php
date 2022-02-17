<?php
	
?>
<div style="text-align:center;font-size:24px;padding-top:15px;padding-bottom:15px;">
	Rate your chances for admission in <?=htmlentities($courseObj->getUniversityName())?>
</div>
<p style="margin:auto;text-align: center;font-size: 16px;color:#666;line-height: 24px;">
	An expert Shiksha counselor will review your profile and give an<br/> assessment of your admission chances
</p>
<div style="text-align:center;">
	<img src="<?php echo SHIKSHA_HOME."/public/images/shiksha-apply-1.jpg";?>" />
</div>

<?php $this->load->view('rateMyChances/widget/rmcHomepageForm'); ?>


