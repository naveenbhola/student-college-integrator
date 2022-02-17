<?php 
foreach ($customFormData['customFields'] as $key => $value) {
	$customFormData['customFields'][$key]['hidden'] = '0';
	$customFormData['customFields'][$key]['disabled'] = '0';
	if($customFormData['customFields'][$key]['value'] < 0) {
		$customFormData['customFields'][$key]['value'] = '';
	}
}

if (isMobileRequest()) { ?>

	<form id="firstLayer_<?php echo $regFormId;?>" regFormId="<?php echo $regFormId;?>">
		<?php $this->load->view('registration/fields/mobile/basicInterest', array('customFormData'=>$customFormData)); ?>

		<input type="hidden" name="isProfilePage" value="yes" id="isProfilePage_<?php echo $regFormId; ?>">
		<input type="hidden" name="context" value="unifiedProfile" id="context_<?php echo $regFormId; ?>">
	</form>

    <?php  $this->load->view('registration/common/jsObjectInitialization', array('customFormData'=>$customFormData));  ?>

	<script>
    <?php if(!empty($customFormData['customFields']['stream']['value'])){ ?>
		userRegistrationRequest['<?php echo $regFormId; ?>'].getFormByStream($('#stream_<?php echo $regFormId; ?>'));
	<?php } ?>
	</script>

<?php } else { ?>
	
	<div id="firstLayer_<?php echo $regFormId; ?>">
		 <?php $this->load->view('registration/fields/LDB/basicInterest', array('customFormData'=>$customFormData));?>
	</div>

    <?php $this->load->view('registration/common/jsObjectInitialization', array('customFormData'=>$customFormData)); ?>

    <script>
    
	<?php if(!empty($customFormData['customFields']['stream']['value'])){ ?>
		userRegistrationRequest['<?php echo $regFormId; ?>'].getFormByStream($j('#stream_<?php echo $regFormId; ?>'));
	<?php } ?>
	initiateSimpleSlider($j);
	$j("#regSliders").simpleSlider({ speed: 500, regFormId:'<?php echo $regFormId; ?>' });
	
	</script>

<?php }?>
