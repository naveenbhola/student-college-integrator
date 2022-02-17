<div style="display:none">
		<img id='beacon_img' src="<?php echo IMGURL_SECURE ;?>/public/images/blankImg.gif" width=1 height=1 >
</div>
<?php
$footerComponents = array(
    'commonJSV2'=>true,
    'js'=> array('contentSA','examContentPageSA'),
    'trackingPageKeyIdForReg' => 692,
    'pages'=> array('contentUserRegistrationPanel')
);
$this->load->view('commonModule/footerV2', $footerComponents);
?>
<script>
var track_contentType = '<?php echo $contentDetails['type']?>';
var track_contentId   = '<?php echo $contentDetails['content_id']?>';
var track_url         = '<?php echo BEACON_URL; ?>';
</script>
