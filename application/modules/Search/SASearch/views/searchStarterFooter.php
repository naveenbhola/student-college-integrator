<?php 
$footerComponents = array(
    'nonAsyncJSBundle' => 'sa-search-starter-page',
    'hideFooter' => true
);
$this->load->view('studyAbroadCommon/saFooter', $footerComponents);
?>
<script type="text/javascript">
var originalUrl = '<?php echo getCurrentPageURL(); ?>';
var baseUrl = '<?php echo SHIKSHA_STUDYABROAD_HOME ?>';
var prefillData = '<?php echo json_encode($prefillData);?>';
var pageIdentifier = '<?php echo $beaconTrackData['pageIdentifier']; ?>';
</script>