<?php
$footerComponents = array(
    'nonAsyncJSBundle'  => 'sa-article-page',
    'asyncJSBundle'     => 'async-sa-article-page'
);
$this->load->view('studyAbroadCommon/saFooter',$footerComponents);
?>
<script type="text/javascript">
$j(window).on('load', function(){
	articleGuidePageOnloadItems();
});
</script>