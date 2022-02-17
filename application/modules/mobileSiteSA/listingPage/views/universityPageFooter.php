<?php
$footerComponents = array(
    'pages'=>array('commonModule/layers/brochureWithRequestCallback'),
    'commonJSV2'=>true,
    'js'                => array('listingPageSA'),
    'trackingPageKeyIdForReg' => 487,
    // 'openSansFontFlag'=> true
);
$this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
    var pageIdentifier = '<?php echo $beaconTrackData['pageIdentifier']?>';
    var courseLevelData = <?php echo json_encode($findCourseWidgetData['dataByCourseLevel']);?>;
    var courseStreamData = <?php echo json_encode($findCourseWidgetData['dataByStream']);?>;
    var img = document.getElementById('beacon_img');
    var randNum = Math.floor(Math.random()*Math.pow(10,16));
    img.src = '<?php echo SHIKSHA_STUDYABROAD_HOME."/beacon/Beacon/index"; ?>/'+randNum+'/0011006/<?=$universityObj->getId()?>+<?=$listingType?>';
</script>
