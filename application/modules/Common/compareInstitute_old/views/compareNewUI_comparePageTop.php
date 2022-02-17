<div class="cmp-col">
	<div class="cmp-box">
		<h1><?=(isset($seoDetails['heading']) && $seoDetails['heading']!='')?$seoDetails['heading']:"Compare Colleges";?></h1>
	</div>
	<?php $this->load->view('compareNewUI_comparePageShareWidget'); ?>
		<?php if(!($institutes && count($institutes)>0)){ ?>
		<div class="confused-text">
			<p>Confused between colleges? Compare them here to make the right choice.<br/>
			You can compare a maximum of 4 colleges at a time.</p>
		</div>
		<?php } ?>
</div>
<div id="confirmation-box-wrapper" class="reb-msg"></div>