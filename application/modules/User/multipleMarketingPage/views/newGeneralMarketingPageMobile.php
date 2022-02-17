<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<base href="<?php echo base_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name = "format-detection" content = "telephone=no" />
<meta name = "format-detection" content = "address=no" />
<meta name="description" content="Registration"/>
<meta name="keywords" content="Registration"/>
<meta name="copyright" content="2013 Shiksha.com" />
<meta name="content-language" content="EN" />
<meta name="author" content="www.Shiksha.com" />
<meta name="resource-type" content="document" />
<meta name="distribution" content="GLOBAL" />
<meta name="revisit-after" content="1 day" />
<meta name="rating" content="general" />
<meta name="pragma" content="no-cache" />
<meta name="classification" content="Education and Career: education portal, college university directory, career forum" />
<meta name="robots" content="ALL" />
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320"/>
<meta http-equiv="cleartype" content="on">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta http-equiv="x-dns-prefetch-control" content="off">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/public/mobile/images/touch/apple-touch-icon-144x144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/public/mobile/images/touch/apple-touch-icon-114x114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/public/mobile/images/apple-touch-icon-72x72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="/public/mobile/images/apple-touch-icon-57x57-precomposed.png">
<link rel="shortcut icon" href="/public/mobile/images/apple-touch-icon.png">
<link rel="publisher" href="https://plus.google.com/+shiksha"/> 
<link rel="dns-prefetch" href="//ask.shiksha.com"> 
<title>Shiksha.com - India's no. 1 college selection website: Register</title>
<link rel="stylesheet" href="/public/mobile5/css/<?php echo getCSSWithVersion("mobile_mmp",'nationalMobile'); ?>" >
<?php if($pageType == 'abroad') { ?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php } 
    global $invalidEmailDomains; 
?>
<?php echo $MMPHEADER;?>
<script type="text/javascript">
  var invalidDomains = <?php echo json_encode($invalidEmailDomains); ?>;
  var isSecondStepValidation = false;
</script>
</head>

<body id="mob">
<?php
  $this->load->view('common/googleCommon');
?>
<div class="mobViewPrnt" id="wrapper" data-role="page">
  <div class="mHead"> <a class="logo"><?php echo $logoType; ?></a>
  <p class="clr"></p>
  </div>
  <div class="mSubhead"><?php echo $FORM_HEADING;?></div>
  
  <div> <a class="logo"><?php echo $header_image_url; ?></a>

  <div class="mFrmBx" style="margin-top: 10px"><?php echo $MMPFORM;?></div>
  <?php $this->load->view('registration/common/OTP/mobileOTPVerification'); ?>
  <!-- <div class="mCntntBx">
	<div class="fot1">
	</div> 
    <div class="fot2">
	  <div class="fot3">       
	  </div>
    </div>
  </div> -->
</div>
<div data-role="page" id="preferredStudyLocations" data-enhance="false">
	   <?php echo $PREFERREDLOCATIONLAYERMOBILE;?>
 </div>
<div style="display:none" id="newMMpPixelCode">
<?php echo $PIXEL_CODE;?>
</div>
<?php //error_log("==shiksha== pixel code === ".$PIXEL_CODE); ?>
<script type="text/javascript">
  function gaTrackEventCustom(currentPageName, eventAction, opt_eventLabel, event, callbackUrl, opt_value, opt_noninteraction) {
  //set optional variables in case they are undefined
  if(typeof(opt_eventLabel) == 'undefined') {
    opt_eventLabel = "";
  }
  if(typeof(opt_value) == 'undefined') {
    opt_value = 0;
  }
  if(typeof(opt_noninteraction) == 'undefined') {
    opt_noninteraction = true; //by default _trackEvent consider it as false
  }
  if(typeof(pageTracker) != 'undefined') {
    if (typeof(callbackUrl) != 'undefined' && typeof(gaCallbackFunction) == 'function') {
      if(!event){
        event = window.event;
      }
      
      if(event.which == 2) {
        pageTracker._trackEventNonInteractive(currentPageName, eventAction,opt_eventLabel, opt_value, opt_noninteraction);
            return true;
      }
      
      if (typeof event != 'undefined') {
        if (event.cancelBubble) {
          event.cancelBubble = true;
        } else {
          if(event.stopPropagation) {
            event.stopPropagation();
          }
        }
        if(event.preventDefault){
          event.preventDefault();
        }
      }
      
      gaCallbackURL = callbackUrl; //set global variable
      pageTracker._setCallback(gaCallbackFunction);
    }
    pageTracker._trackEventNonInteractive(currentPageName, eventAction, opt_eventLabel, opt_value, opt_noninteraction);
    if(typeof(callbackUrl) != 'undefined') {  
      setTimeout(function() {
        gaCallbackURL = callbackUrl; //set global variable
        gaCallbackFunction();
      }, 3000);
    }
  }
  return true;
}
  
</script>
</body>
</html>
