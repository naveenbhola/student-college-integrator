<?php
	$footerComponents = array(
		'js' =>array('studyAbroadCommon','studyAbroadShipment')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>

<script type="text/javascript">
	var shipment = new shipmentClass();
	shipment.initializeShipment();
	shipment.isValidStudyAbroadUser = <?php echo $isValidStudyAbroadUser;?>;
</script>