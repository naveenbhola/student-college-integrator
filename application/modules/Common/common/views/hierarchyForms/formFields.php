<?php 
if($fromAddMoreButton){
	$this->load->view('common/hierarchyForms/hierarchyView');
	if($redefineAddMorePostion){
		$this->load->view('common/hierarchyForms/courseView');
	}
	$this->load->view('common/hierarchyForms/remove');?>
	<script> countHierarchy = '<?php echo ++$countHierarchy; ?>';</script>
<?php } else{
	if(empty($prefilledData['hierarchyView'])){
		$prefilledData = array();
		$prefilledData['hierarchyView'] = array('empty'=>1);
	}
	if($formElements['fromWhere'] == 'videoCms'){
		$this->load->view('common/hierarchyForms/customFields');
	}
	echo "<div id='amc'>";
	if($redefineAddMorePostion){
		foreach ($prefilledData['hierarchyView'] as $key => $value) {
			$dataEdit['existingData'] = $value;
			$this->load->view('common/hierarchyForms/hierarchyView',$dataEdit);
			$this->load->view('common/hierarchyForms/courseView',$dataEdit);
			$this->load->view('common/hierarchyForms/remove',$dataEdit);
			$dataEdit['countHierarchy']  = ++$countHierarchy;
		}
		$this->load->view('common/hierarchyForms/addMoreView');
	}else{
		foreach ($prefilledData['hierarchyView'] as $key => $value) {
			$dataEdit['existingData'] = $value;
			$this->load->view('common/hierarchyForms/hierarchyView',$dataEdit);
			$this->load->view('common/hierarchyForms/remove',$dataEdit);
			$dataEdit['countHierarchy']  = ++$countHierarchy;
		}
		$dataEdit['combinedView'] = 1;
		$this->load->view('common/hierarchyForms/addMoreView');
		$this->load->view('common/hierarchyForms/courseView',$dataEdit);
	}
	if($formElements['fromWhere'] != 'videoCms'){
		$this->load->view('common/hierarchyForms/customFields');
	}
	$this->load->view('common/hierarchyForms/popupErrorLayer');?>
	<script type="text/javascript">
	var hierarchyTuplesCount = '<?php echo $formElements["hierarchyTuplesCount"]?>';
	var otherFieldInputCount = '<?php echo $formElements["otherFieldInputCount"]?>';
	var configName           = '<?php echo $configName?>';
	var entityNameLabel      = '<?php echo $formElements['entityNameLabel']?>';
	var countHierarchy       = '<?php echo $countHierarchy; ?>';
	var actnForm = '<?php echo $actionBasedOnPrefilledData;?>';
	</script>
<?php } ?>