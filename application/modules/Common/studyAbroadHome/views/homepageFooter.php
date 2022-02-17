<?php
$footerComponents = array('nonAsyncJSBundle' => 'sa-home-page',
						  'asyncJSBundle'    => 'async-sa-home-page'
						  );
$this->load->view('studyAbroadCommon/saFooter', $footerComponents);
?>
<script>
	$j(window).on('load',function(){
		initializers();
	});
	var rmcPageTitle = "Home Page";
</script>