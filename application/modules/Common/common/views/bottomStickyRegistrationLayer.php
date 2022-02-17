<?php
$btmRegLyrTrackingDetails = array(
  'articleDetailPage' =>  array(
    'trackingKeyId'   =>  1258,
    'gaParams'        =>  array(
      "onPageScroll"  => "Article_Registration::Auto_Slide_Up::Desktop_Auto_Slide_Up",
      "onArrowDown"   => "Article_Registration::Click_Down::Desktop_Click_Down",
      "onArrowUp"     => "Article_Registration::Click_Up::Desktop_Click_Up",
      "onRegClick"    => "Article_Registration::Sign_Up::Desktop_Sign_Up"
    )
  ),
  'tagDetailPage' =>  array(
    'trackingKeyId'   =>  1365,
    'gaParams'        =>  array(
      "onPageScroll"  => "TDP_Registration::Auto_Slide_Up::Desktop_Auto_Slide_Up",
      "onArrowDown"   => "TDP_Registration::Click_Down::Desktop_Click_Down",
      "onArrowUp"     => "TDP_Registration::Click_Up::Desktop_Click_Up",
      "onRegClick"    => "TDP_Registration::Sign_Up::Desktop_Sign_Up"
    )
  ),
  'questionDetailPage' =>  array(
    'trackingKeyId'   =>  1363,
    'gaParams'        =>  array(
      "onPageScroll"  => "QDP_Registration::Auto_Slide_Up::Desktop_Auto_Slide_Up",
      "onArrowDown"   => "QDP_Registration::Click_Down::Desktop_Click_Down",
      "onArrowUp"     => "QDP_Registration::Click_Up::Desktop_Click_Up",
      "onRegClick"    => "QDP_Registration::Sign_Up::Desktop_Sign_Up"
    )
  )
);

$trackingKeyId = $btmRegLyrTrackingDetails[$beaconTrackData['pageIdentifier']]['trackingKeyId'];
$trackingKeyId = !empty($trackingKeyId) ? $trackingKeyId :DEFAULT_TRACKING_KEY_DESKTOP;
if(isset($trackingKeyIdForBottomStickyWidget) && !empty($trackingKeyIdForBottomStickyWidget)){
        $trackingKeyId = $trackingKeyIdForBottomStickyWidget;
}
$gaParams = $btmRegLyrTrackingDetails[$beaconTrackData['pageIdentifier']]['gaParams'];
?>
<div class="nav-down btmStickRegLayer">
  <div class="nav__sec">
    <a class="rc__circle regLyrState"
      arrowdowngaparams = "<?php echo $gaParams["onArrowDown"];?>"
      arrowupgaparams = "<?php echo $gaParams["onArrowUp"];?>"
    ><i></i></a>
    <p class="title__txt">
      <?php if($beaconTrackData['pageIdentifier'] == 'articleDetailPage'){ ?>
          Get Admission Updates and Exam Alerts on Shiksha
      <?php } else { ?>
          Get Admission Updates and Exam Alerts on Shiksha
      <?php } ?> 
        <a class="nav__signup btmStickRegLayerSignUp" 
          trackingKeyId = "<?php echo $trackingKeyId;?>"
          gaparams = '<?php echo $gaParams['onRegClick'];?>'
      >Sign Up</a></p>
  </div>  
<!--  <input type='hidden' class="onScrollShowRegLyr" />-->
</div>


<script type="text/javascript">
  var btmRegShowOnScroll = true;
</script>
