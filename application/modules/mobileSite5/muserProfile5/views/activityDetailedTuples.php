<div class="qns-card card-padding">
  <div class="fixed-header-col">	
	<h3 class="qns-heading" id="detailed-tuple-heading"><?php echo $typeOfStat; ?></h3>
	<input type="hidden" id="ajaxCallCounterDetailed" value="1"/>
	<input type="hidden" id="iter" value="<?php echo count($activities); ?>"/>
	<input type="hidden" id="remCount" value="<?php echo count($activities); ?>"> 
	<a data-rel="back" class="head-cls flRt" href="#">×</a>
	</div>	
	<div class="data-padding" id="user_detailed_activities">
		<?php 
			if(empty($activities)){
				$viewTupleData = '<p class="private-prf-txt">Nothing to show</p>';
			}
			echo $viewTupleData;
		?>
		<?php
			$this->load->view('userProfileDetailedActivity');
		 ?>
	</div>
</div>
<div style="text-align: center; margin-top: 10px; display: none;" id="loadingNewLayer">
	<img class="small-loader" border="0" alt="" id="loadingImageNewLayer" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif">
</div>