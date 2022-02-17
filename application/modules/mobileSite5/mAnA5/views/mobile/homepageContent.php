<div class="qa-col">
<div class="qa-tab-pane" id="tab_0">
    <div class="qa-data-show">

		<?php $this->load->view('mobile/homepageTabData');?>

</div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#nextPaginationIndex").val("<?php echo $nextPaginationIndex; ?>");
 		$("#nextPageNo").val("<?php echo $nextPageNo; ?>");
	});
</script>