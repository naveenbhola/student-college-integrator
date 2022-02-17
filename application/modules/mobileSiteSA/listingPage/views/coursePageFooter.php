<?php
    $footerComponents = array(
								'commonJSV2'=>true,
								'loadLazyJSFile'=>true,
								'js'=> array('listingPageSA'),
    							'pages'=>array('commonModule/layers/brochureWithRequestCallback'),
    							'trackingPageKeyIdForReg' => 482
    						);
    $this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
	var pageIdentifier = '<?php echo $beaconTrackData['pageIdentifier']?>';
	var catPageTitle = "<?php echo htmlentities($courseObj->getName())?>";
	var CLPCourseIds = []; 
	CLPCourseIds.push("<?php echo $courseObj->getId()?>");
	var randNum = Math.floor(Math.random()*Math.pow(10,16));
	var beaconSrc = '<?php echo BEACON_URL; ?>/'+randNum+'/0011006/<?=$courseObj->getId()?>+<?=$listingType?>';
	var initiateBrochureDownload = <?php echo ($initiateBrochureDownload == 1? $initiateBrochureDownload : 0); ?>;
	var courseId = <?php echo ($courseObj->getId()>0?$courseObj->getId():0);?>;
	var autoResponseFlag = <?php echo ( $makeAutoResponse && !$reponse_already_created ? 'true':'false'); ?>;
</script>
