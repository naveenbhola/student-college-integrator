<?php
	$footerComponents = array(
				'hideHTML'		   => "true",
				'hideFeedbackHTML' => true,
				'nonAsyncJSBundle' => 'sa-counselor-review-posting',
				'asyncJSBundle'    => 'async-sa-counselor-review-posting',
			);
	$this->load->view('studyAbroadCommon/saFooter',$footerComponents);
?>
<script>
$j(window).load(function(){
	bindOnloadItems();
});
</script>