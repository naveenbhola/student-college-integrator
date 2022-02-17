<?php $this->load->view('anaInternal/newModeration/moderationPanelTabs');?>
<?php $this->load->view('anaInternal/newModeration/moderationSearchPanel');?>
<?php 
/*if($hasModeratorAccess == 1 || $hasModeratorAccess == 2){
	$this->load->view('anaInternal/newModeration/moderatedEntityGraph');
}*/
?>
<div class="row top-bottom-padding" id="myPanelLoader" style="<?=($hasModeratorAccess == 3)?'display:none;':''?>">
	<div class="col-md-12 text-center">
		<img src="<?=SHIKSHA_HOME?>/public/mobile5/images/ajax-loader.gif">
	</div>
</div>
<div class="row" id="dataInfoPanel">
	<?php $this->load->view('anaInternal/newModeration/moderationDataInfoPanel')?>
</div>
<?php
if($hasModeratorAccess == 3){
	$this->load->view('anaInternal/newModeration/myModerationInfoPanel');
}
?>
