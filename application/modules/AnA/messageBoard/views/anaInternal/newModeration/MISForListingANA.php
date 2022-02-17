<?php $this->load->view('anaInternal/newModeration/mpHeader'); ?>
<?php $this->load->view('anaInternal/newModeration/moderationPanelTabs'); ?>
<?php $this->load->view('anaInternal/newModeration/listingAnaSearch'); ?>
<?php $this->load->view('anaInternal/newModeration/mpFooter'); ?>
<script type="text/javascript">
$j(document).ready(function(){
	$j('#listingMISSubmit').on('click', function(){
		getListingAnaData($j(this));
	});
});
</script>