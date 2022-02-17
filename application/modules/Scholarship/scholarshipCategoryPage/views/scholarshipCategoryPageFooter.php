<?php
	$footerComponents = array('asyncJSBundle'=>'async-sa-scholarship-cat-page',
                                   'nonAsyncJSBundle'=>'sa-scholarship-cat-page'
                                );
	$this->load->view('studyAbroadCommon/saFooter',$footerComponents);

?>
<script>
	var pageDimension = '<?php echo json_encode(array("name"=>$request->getType(),
													  "value"=>($request->getType() == "country"?$request->getCountryName():$request->getLevel()))); ?>';
        var baseUrl     = '<?php echo $request->getPaginatedUrl(1,false);?>';
        var originalUrl     = '<?php echo getCurrentPageURL(); ?>';
	$j(window).on('load',function() {
		initializeSchCategoryPage();
	});
</script>