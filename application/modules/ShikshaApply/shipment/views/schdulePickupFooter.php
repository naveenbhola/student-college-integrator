<?php
	$footerComponents = array(
				'hideHTML'			=> "true",
				'js'				=> array('studyAbroadShipment','studyAbroadCommon')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	var cityMap = JSON.parse('<?=json_encode($dhlCityData)?>');
	var shipment = new shipmentClass();
	shipment.initializeShipment();
	var shippingInfomationValidation = new shippingInfomationValidationClass();
	shipment.APIResponse = "<?php echo SHIPMENT_SUCCESS;?>";
	shipment.dhlErrorCode = "<?php echo SHIPMENT_INVALID_DATA_CODE;?>";
</script>