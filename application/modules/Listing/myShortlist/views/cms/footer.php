<div class="spacer20 clearFix"></div>
<?php
	$this->load->view('common/footer');
?>
<script type="text/javascript">
    var formname = "form_<?php echo $formName; ?>";
	initiateTinYMCE(formname, true);
</script>