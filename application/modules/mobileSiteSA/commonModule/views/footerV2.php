<div id="compareCourseBottomLayer"></div>
<div class="hide">
    <a href="#rmcErrorMessageLayer" data-transition="slide" data-rel="dialog" id="rmcErrorLink"></a>
</div>
<?php
 if(empty($_COOKIE['gdpr']) && $hideGDPR!==true){?>
    <div class="cokkie-lyr">
        <div class="cokkie-box">
            <p>We use cookies to improve your experience. By continuing to browse the site, you agree to our <a href="<?=SHIKSHA_STUDYABROAD_HOME."/privacy-policy.html";?>" target="_blank">Privacy Policy</a> and <a href="<?=SHIKSHA_STUDYABROAD_HOME."/terms-conditions.html";?>" target="_blank">Cookie Policy</a>.</p>
            <div class="tar"><a href="javascript:void(0);" onclick="gdprAgree(this);" class="cookAgr-btn">OK</a></div>
        </div>
    </div>
<?php   }?>
<?php
global $serverStartTime;
$endserverTime =  microtime(true);
global $tempForTracking;
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
if($hideFooter !=true){
    echo Modules::run('studyAbroadCommon/Navigation/getMainFooter', array());
}
?>
</div>
</div>
<?php
GLOBAL $validateuser, $loggedInUserData, $checkIfLDBUser;
$logged_in_userid       = ($validateuser=='false') ? '-1' : $loggedInUserData['userId'];
$shiksha_site_current_url   = current_url();
if($_SERVER['HTTP_REFERER'])
{
$shiksha_site_current_refferal =  $_SERVER['HTTP_REFERER'];
}
else
{
$shiksha_site_current_refferal = SHIKSHA_STUDYABROAD_HOME;
}
$encoded_current_url        = url_base64_encode($shiksha_site_current_url);
$encoded_current_refferal   =  url_base64_encode($shiksha_site_current_refferal);
?>
<?php
if(count($jsRequiredInHeader)== 0){ $this->load->view("commonModule/commonJsV2"); } 

//JSB9 Tracking
echo getTailTrackJs($tempForTracking,true,$trackForPages,'https://track.99acres.com/images/zero.gif');
?>
<script>
var enableDebugging = <?php echo ($this->security->xss_clean($this->input->get('enableDebugging'))==1?1:0); ?>;
var showCompareLayerPage = <?=($compareLayerTrackingSource=="")?"false":"true"?>;
var compareOverlayTrackingKeyId = '<?=$compareLayerTrackingSource?>';
var compareCookiePageTitle = '<?=base64_encode($compareCookiePageTitle)?>';
var compareButtonTrackingId = '<?php echo $compareButtonTrackingId ?>';
var trackingPageKeyIdForReg = '<?php echo $trackingPageKeyIdForReg ?>';
var layerHeading			= '<?php echo $layerHeading;?>';
var commonJSV2  			= '<?php echo $commonJSV2; ?>';
$j(window).on('load',function(){

<?php if(ABROAD_USER_TRACKING == 1) { ?>    
$j.ajax({
type:"POST",
url: "/common/studyAbroadUserTracking/trackUser",
data : {'pageUrl':window.location.href,'referrer':document.referrer},
success: function(data){}
});
<?php } ?>
});
</script>
<?php
if(empty($pages))
{
$pages = array();   
}
array_push($pages,"commonModule/layers/rmcErrorMessageLayer");
foreach($pages as $page)
{
$data['trackingPageKeyId'] = $trackingPageKeyIdForReg;
$this->load->view($page,$data);
}
if($hideFooter !== true) {
    $this->load->view("commonModule/layers/searchLayerHome");
    $this->load->view("commonModule/layers/examScoreUpdateLayer"); 
}
?>
<?php if(!$skipRegistrationLayer){?>
<div id="register" style="width:100% !important;" data-enhance="false"></div>
    <?php global $invalidEmailDomains; 
        if(is_array($invalidEmailDomains) && count($invalidEmailDomains)>0) { ?>
    <script>var invalidDomains = JSON.parse('<?php echo json_encode($invalidEmailDomains); ?>');</script>
    <?php } ?>
<?php } ?>
<div>
<?php $this->load->view("commonModule/layers/successMessage"); ?>
<?php $this->load->view("commonModule/layers/errorMessage"); ?>
</div>
<?php if($openSansFontFlag==true){?>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css' media="none" onload="if(media!='all')media='all'">
<?php } ?>
<div id="<?php echo ($jqMobileFlag===true)?'myrightpanela':'loggedina'?>" data-position="right" data-display="push" data-role="panel" data-swipe-close="false" ></div>
</body>
<?php $this->load->view('commonModule/footerTracking');?>
<?php
$CI = & get_instance();
$CI->load->library('security');
$CI->security->setCSRFToken();
?>
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
</html>
<script type="text/javascript">
    var jqMobileFlag = true;
    var rmcUserLimitCount = parseInt('<?=ABROADRMCLIMIT?>');
    var registrationIntentParams = {};
    <?php 
        //Code for popup of exam update - start
        if(isset($_COOKIE['examPopup']) && $_COOKIE['examPopup'] == '1'){
    ?>
    var examMasterList = {}, examScoreLayerData = {};
    examScoreLayerData['pageIdentifier'] = '<?php echo $beaconTrackData["pageIdentifier"]; ?>';
    <?php
        }//Code for popup of exam update - end
    ?>
</script>