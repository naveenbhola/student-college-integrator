<?php
	$this->load->view('listingPosting/abroad/abroadCMSFooter');
?>
<script>
	$j(document).ready(function(){
		<?php if($formName == ENT_SA_CUSTOMER_DELIVERY){ ?>
			initializeCustomerDeliveryDashboard();
		<?php } ?>
	});
</script>