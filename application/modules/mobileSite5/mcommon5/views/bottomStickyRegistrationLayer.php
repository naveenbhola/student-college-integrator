<?php

$btmRegLyrTrackingDetails = array(
  'articleDetailPage' =>  array(
    'trackingKeyId'   =>  1259,
    'gaParams'        =>  array(
      "onPageScroll"  => "Article_Registration::Auto_Slide_Up::Mobile_Auto_Slide_Up",
      "onArrowDown"   => "Article_Registration::Click_Down::Mobile_Click_Down",
      "onArrowUp"     => "Article_Registration::Click_Up::Mobile_Click_Up",
      "onRegClick"    => "Article_Registration::Sign_Up::Mobile_Sign_Up"
    )
  ),
  'tagDetailPage' =>  array(
    'trackingKeyId'   =>  1366,
    'gaParams'        =>  array(
      "onPageScroll"  => "TDP_Registration::Auto_Slide_Up::Mobile_Auto_Slide_Up",
      "onArrowDown"   => "TDP_Registration::Click_Down::Mobile_Click_Down",
      "onArrowUp"     => "TDP_Registration::Click_Up::Mobile_Click_Up",
      "onRegClick"    => "TDP_Registration::Sign_Up::Mobile_Sign_Up"
    )
  ),
  'questionDetailPage' =>  array(
    'trackingKeyId'   =>  1364,
    'gaParams'        =>  array(
      "onPageScroll"  => "QDP_Registration::Auto_Slide_Up::Mobile_Auto_Slide_Up",
      "onArrowDown"   => "QDP_Registration::Click_Down::Mobile_Click_Down",
      "onArrowUp"     => "QDP_Registration::Click_Up::Mobile_Click_Up",
      "onRegClick"    => "QDP_Registration::Sign_Up::Mobile_Sign_Up"
    )
  )
);


$trackingKeyId = $btmRegLyrTrackingDetails[$beaconTrackData['pageIdentifier']]['trackingKeyId'];
$trackingKeyId = !empty($trackingKeyId) ? $trackingKeyId :DEFAULT_TRACKING_KEY_MOBILE;
if(isset($trackingKeyIdForBottomStickyWidget) && !empty($trackingKeyIdForBottomStickyWidget)){
        $trackingKeyId = $trackingKeyIdForBottomStickyWidget;
}
$gaParams = $btmRegLyrTrackingDetails[$beaconTrackData['pageIdentifier']]['gaParams'];

global $isWebViewCall;
if(!$isWebViewCall){
 ?>

<div class="nav-down btmStickRegLayer">
    <a class="rc__circle"><i></i></a>
    <div class="nav__sec">
      <p class="title__txt">
        Get Admission Updates and Exam Alerts on Shiksha
       </p>
      <p>
        <a trackingKeyId="<?php echo $trackingKeyId; ?>" 
         gaparams = '<?php echo $gaParams['onRegClick'];?>'
        class="nav__signup btmStickRegLayerSignUp">Sign Up</a>
      </p>
    </div>
       <!--<input type='hidden' class="onScrollShowRegLyr" gaparams = '<?php //echo $gaParams['onPageScroll'];?>'/>-->
  </div>


<?php if(!$noJqueryMobile){?>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion('bottomStickyRegistration','nationalMobile'); ?>"></script>

<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('bottomStickyRegistration','nationalMobile');?>" >
<?php }
} ?>
  
