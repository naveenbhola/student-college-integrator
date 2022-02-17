<?php
$footerComponents = array('nonAsyncJSBundle' => 'sa-scholarship-home',
						  'asyncJSBundle'    => 'async-sa-scholarship-home'
						  );
$this->load->view('studyAbroadCommon/saFooter', $footerComponents);
?>

<script>
	$j(window).on('load',function(){
		initializers();
	});
</script>