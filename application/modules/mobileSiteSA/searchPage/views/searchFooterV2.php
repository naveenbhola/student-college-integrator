<?php
$footerComponents = array(
    'js'              => array('jquery.ba-bbq.min','searchSAv2'),
    'pages'=>array('widgets/searchPageFiltersV2','widgets/searchPageSorterV2','commonModule/layers/brochureWithRequestCallback'),
    'commonJSV2'=>true,
    'trackingPageKeyIdForReg' => 488,
    'openSansFontFlag'=> false
);
$this->load->view('commonModule/footerV2',$footerComponents);
?>
<?=$trackingId > 0 ? "<script>var trackingId = ".$trackingId.";</script>" :"" ?>
<script>
var searchPageString = '<?php echo base64_encode($_SERVER['REQUEST_URI']);?>';
var perPageResult = '<?php echo SA_SEARCH_PAGE_LIMIT;?>';
var totalResult   = '<?php echo $pageData['totalResultCount']; ?>';
var pageNumber = '<?php echo ($pageData['currentPageNum']>1)?$pageData['currentPageNum']:1; ?>';
var searchTrackingSAId = '<?php echo $searchTrackingId; ?>';
var prefillData = <?php echo json_encode($searchLayerPrefillData);?>;
<?php if(isset($trackingKeyId)){ ?>
var trackingKeyId = '<?php echo $trackingKeyId; ?>';
<?php } ?>
</script>
