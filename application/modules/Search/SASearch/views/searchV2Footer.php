<?php 
$footerComponents = array(
	'asyncJSBundle'    => 'async-sa-search-page',
    'nonAsyncJSBundle' => 'sa-search-page'
);
$this->load->view('studyAbroadCommon/saFooter', $footerComponents);
?>
<script type="text/javascript">
var originalUrl = '<?php echo getCurrentPageURL(); ?>';
var baseUrl = '<?php echo SHIKSHA_STUDYABROAD_HOME ?>';
var searchTrackingSAId = '<?php echo $trackingKeyId ?>';
var SASearchV2Obj = {}, SASearchV2ApplyFilterObj = {};
var prefillData = <?php echo json_encode($searchLayerPrefillData);?>;
var pageIdentifier = '<?php echo $beaconTrackData['pageIdentifier']; ?>';
<?php if(isset($trackingKeyId)){ ?>
	var trackingKeyId = <?php echo $trackingKeyId; ?>;
<?php } ?>
$j(window).load(function(){
	SASearchV2Obj = new SASearchV2Class();
	SASearchV2Obj.pageOnloadTasks('<?php echo $searchTupleType;?>');
});
var pageNum = <?php echo ($pageData['currentPageNum']); ?>
</script>