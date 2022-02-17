<div id="compareCourseBottomLayer"></div>
<?php   if (empty($_COOKIE['gdpr'])){
?>
            <div class="cokkie-lyr">
                <div class="cokkie-box">
                    <p>We use cookies to improve your experience. By continuing to browse the site, you agree to our <a href="<?=SHIKSHA_STUDYABROAD_HOME."/privacy-policy.html";?>" target="_blank">Privacy Policy</a> and <a href="<?=SHIKSHA_STUDYABROAD_HOME."/terms-conditions.html";?>" target="_blank">Cookie Policy</a>.</p>
                    <div class="tar"><a href="javascript:void(0);" onclick="gdprAgree(this);" class="cookAgr-btn">OK</a></div>
                </div>
            </div>
<?php   }?>
<?php
global $configData;
$footerURLS = $configData['dataForHeaderFooter'];
global $serverStartTime;
$endserverTime =  microtime(true);
global $tempForTracking;
$popularColleges = $configData['dataForHeaderFooter']['popularColleges'];
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
if($hideFooter !=true)
{
?>
<footer class="main-footer" data-role = "footer" data-enhance = "false">
<div class="knob-main">
<a href="Javascript:void(0);" id = "footer-knob" class="sprite footer-knob left50Per" onclick = "toggleFooterLinks();"><i class="sprite knob-dwnarr"></i></a>
</div>
<div class="content-inner2 backgroundfff">
<div id = "footer-links-section">
<div class="content-inner2 backgroundfff">
<ul class="clearfix">
<?php foreach($footerURLS  as $level => $data) {
if(in_array($level,array('bachelors','masters'))) { ?>
<li>
<a class = "footer-accordion-label" href="Javascript:void(0);"><?=(ucfirst($level))?> <i class="sprite footer-arr-dwn"></i></a>
<div class="footer-details footer-accordion-div hide">
<div class="footer-row"><strong>By Course : </strong>
<?php $separator = '';
foreach($data['browseInsByCourse'] as $course) {
echo $separator;  ?>
<a href="<?=($course['url'])?>"><?=($course['title'])?></a>
<?php $separator = ' | '; } ?>
</div>
<div class="footer-row"><strong>By Stream : </strong>
<?php $separator = '';
foreach($data['browseInsByStream'] as $stream) {
echo $separator; ?>
<a href="<?=($stream['url'])?>"><?=($stream['title'])?></a>
<?php $separator = ' | ';
} ?>
</div>
<div class="footer-row"><strong>Student Scholarships : </strong>
<?php $separator = '';
foreach($data['browseScholarships'] as $value) {
echo $separator; ?>
<a href="<?=($value['url'])?>"><?=($value['title'])?></a>
<?php $separator = ' | ';
} ?>
</div>
</div>
</li>
<?php } 
} ?>
<li>
<a class = "footer-accordion-label" href="Javascript:void(0);">Application Process <i class="sprite footer-arr-dwn"></i></a>
<div class="footer-details footer-accordion-div hide">
<div class="footer-row"><strong>Language Exams : </strong>
<a href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/ielts'?>">IELTS</a>
|
<a href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/toefl'?>">TOEFL</a>
|
<a href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/pte'?>">PTE</a>
</div>
<div class="footer-row"><strong>Aptitude Exams : </strong>
<a href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/gre'?>">GRE</a>
|
<a href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/gmat'?>">GMAT</a>
|
<a href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/sat'?>">SAT</a>
</div>
<div class="footer-row"><strong>Application Writing : </strong>
<?php $separator = '';
foreach($footerURLS['applicationProcessPages'] as $key=>$value) {
echo $separator; ?>
<a href="<?=($value['url'])?>"><?=($value['title'])?></a>
<?php $separator = ' | ';  } ?>
</div>
<div class="footer-row"><strong>Application Assistance : </strong>
<a href="<?=SHIKSHA_STUDYABROAD_HOME.'/apply'?>">Shiksha counseling service</a>
|
<a href="<?=SHIKSHA_STUDYABROAD_HOME.'/apply/shipment'?>">DHL Student offer</a>
</div>
<div class="footer-row"><strong>Find best scholarship for you : </strong>
<?php $separator = '';
echo $separator; ?>
<a href="<?=$footerURLS['findScholarships']['url']?>"><?=$footerURLS['findScholarships']['title']?></a>
<?php $separator = ' | '; ?>
</div>
</div>
</li>
<li>
<a class = "footer-accordion-label" href="Javascript:void(0);">Popular Colleges <i class="sprite footer-arr-dwn"></i></a> 
<div class="footer-details footer-accordion-div footer-pop hide">
<div class="footer-row"><strong>US: </strong>
<?php
foreach ($popularColleges['usa'] as $key=>$popularCollege){
?>
<a href="<?php echo $popularCollege['url'];?>"><?php echo $popularCollege['title'];?></a>
<?php 
if($key==count($popularColleges['usa'])-1){
continue;
}
?>
|
<?php }?>
</div>
<div class="footer-row"><strong>UK: </strong>
<?php
foreach ($popularColleges['uk'] as $key=>$popularCollege){
?>
<a href="<?php echo $popularCollege['url'];?>"><?php echo $popularCollege['title'];?></a>
<?php 
if($key==count($popularColleges['uk'])-1){
continue;
}
?>
|
<?php }?>
</div>
<div class="footer-row"><strong>Canada: </strong>
<?php
foreach ($popularColleges['canada'] as $key=>$popularCollege){
?>
<a href="<?php echo $popularCollege['url'];?>"><?php echo $popularCollege['title'];?></a>
<?php 
if($key==count($popularColleges['canada'])-1){
continue;
}
?>
|
<?php }?>
</div>
<div class="footer-row"><strong>Australia: </strong>
<?php
foreach ($popularColleges['australlia'] as $key=>$popularCollege){
?>
<a href="<?php echo $popularCollege['url'];?>"><?php echo $popularCollege['title'];?></a>
<?php 
if($key==count($popularColleges['australlia'])-1){
continue;
}
?>
|
<?php }?>
</div>
</div>
</li>
<li>
<a class = "footer-accordion-label" href="Javascript:void(0);">Contact Us <i class="sprite footer-arr-dwn"></i></a>
<div class="footer-contact-details footer-accordion-div hide">
<div class="footer-row">
<div class="contact-title">Students Helpline</div>
<div class="sprite mob-icn top0"></div>
<div class="helplineNo">
<strong class="fz11">Call : <a href="tel:<?php echo ABROAD_STUDENT_HELPLINE; ?>"><?php echo ABROAD_STUDENT_HELPLINE; ?></a></strong>
<p>(09:30 AM - 06:30PM, Monday - Friday)</p>
</div>
<div class="sprite mob-mail-icn top0"></div>
<div class="helplineEmail">
<strong class="fz11">Email : <a class="color566ec2" href="mailto:studyabroad@shiksha.com">studyabroad@shiksha.com</a></strong>
</div>
</div>               
<div class="footer-row">
<div class="contact-title mb4">Sales Enquiries</div>
<div class="contact-details">
<div class="mb7 lh18">For Sales Enquiries, Contact :<br>
<b>Nandita Bandhopadhyay</b>
</div>
<p><b><i class="sprite mob-mail-icn top-1"></i> Email : <a class="color566ec2" href="mailto:nandita@shiksha.com">nandita@shiksha.com</a></b></p>
</div>
</div>
</div>
</li>
</ul>
</div>
<nav class="switching-opt">
<a href="<?=(SHIKSHA_HOME)?>">Study In India</a> | <a href="Javascript:void(0);" onclick = "sendUserToDesktop();">Switch To Desktop Version</a>
</nav>
</div>
<section class="tac footer-child">
<aside> 
Trade Marks belong to the respective owners.<br >Copyright &copy; <?php echo date('Y'); ?> Infoedge India Ltd. All rights reserved
</aside>
</section>
</div>
<div class="hide">
<a href="#rmcErrorMessageLayer" data-transition="slide" data-rel="dialog" id="rmcErrorLink"></a>
</div>
</footer>
<?php } ?>
</div>
<?php $this->load->view('commonModule/hamburgerMenu');?>
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
<?php if(count($jsRequiredInHeader)== 0){ $this->load->view("commonModule/commonJs"); } ?>
<?php
array_push($js,"commonSA","registrationSA");
$asyncJsList = array("registrationSA");
if(count($asyncJs)>0){
$asyncJsList = array_merge($asyncJsList,$asyncJs);
}
if(count($jsRequiredInHeader)>0){
$js = array_diff($js, $jsRequiredInHeader);
}
if(!empty($js) && count($js)>0) {
foreach($js as $jsFile) {
?>
<script <?php if(in_array($jsFile,$asyncJsList)){ echo 'async';}?> src="//<?php echo JSURL; ?>/public/mobileSA/js/<?php echo getJSWithVersion($jsFile,"abroadMobile"); ?>"></script>
<?php
}
}
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
$j(window).on('load',function(){
mobileSACommonBinding();
initializeFooterAccordion();
getShortlistCourseCount();
bindClickHandlerToHideLayer();
initializeSearchBarV2();
getNewNotificationCount();
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
//array_push($pages,'registration/mobileRegistrationAbroad');
foreach($pages as $page)
{
$data['trackingPageKeyId'] = $trackingPageKeyIdForReg;
$this->load->view($page,$data);
}
$this->load->view("commonModule/layers/searchLayerHome");
$this->load->view("commonModule/layers/examScoreUpdateLayer");
?>
<?php if(!$skipRegistrationLayer){?>
<div id="register" style="width:100% !important;" data-enhance="false"></div>
<?php } ?>
<div>
<?php $this->load->view("commonModule/layers/successMessage"); ?>
<?php $this->load->view("commonModule/layers/errorMessage"); ?>
</div>
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobileSA/css/<?php echo getCSSWithVersion("commonSA",'abroadMobile'); ?>" >
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobileSA/css/<?php echo getCSSWithVersion("jquery.mobile-1.4.5",'abroadMobile'); ?>" >
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobileSA/css/vendor/jqueryUiSliderDatepicker/jquery-ui.min.css" >

<?php
if(!empty($css) && count($css)>0) {
foreach($css as $cssFile) {
?>
<link href="//<?php echo CSSURL; ?>/public/mobileSA/css/<?php echo getCSSWithVersion($cssFile,'abroadMobile'); ?>" type="text/css" rel="stylesheet" />
<?php
}
}
?>
<?php if($openSansFontFlag==true){?>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css' media="none" onload="if(media!='all')media='all'">
<?php }?>

</body>
<?php $this->load->view('commonModule/footerTracking');?>
<?php
$CI = & get_instance();
$CI->load->library('security');
$CI->security->setCSRFToken();
?>
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
<?php // below code is used to verify all URL's from the current page
      // added by - akhter
    $urlPage = isset($_GET['autoUrl']) ? $_GET['autoUrl'] :''; 
    if($urlPage && !is_numeric($urlPage) && ALLOW_AUTOURL_SCRIPT == TRUE){
        $a = $this->load->config('common/urlSelectorConfig',true);
        $cdata  = $this->config->item('urlSelectorConfig');
        $cdata  = $cdata['mobile'][$urlPage];
        if($cdata){
            $selector = json_encode($cdata);
        }echo "<script> var ele = $selector;</script>";
        $br = stristr($_SERVER["HTTP_USER_AGENT"],'Chrome');
        if(isset($_SERVER["HTTP_USER_AGENT"]) && (!empty($br))){
            echo "<script>alert('Your browser is not supported. Please open in Firefox only.');</script>";
        }else{?>
            <script type="text/javascript">
                $j(window).load(function() {
                    automatedUrlScript();
                });
            </script>
    <?php }
}?>
</html>
<script>
var jqMobileFlag = false;
<?php 
if($jqMobileFlag === true){
?>
    jqMobileFlag = true;
<?php 
}
?>
var rmcUserLimitCount = parseInt('<?=ABROADRMCLIMIT?>');
var registrationIntentParams = {};
</script>
<?php 
//Code for popup of exam update - start
if(isset($_COOKIE['examPopup']) && $_COOKIE['examPopup'] == '1'){
?>
    <script type="text/javascript">
    var examMasterList = {}, examScoreLayerData = {};
    examScoreLayerData['pageIdentifier'] = '<?php echo $beaconTrackData["pageIdentifier"]; ?>';
    checkIfExamScoreUpdateLayerToBeShown();
    </script>
<?php 
}
//Code for popup of exam update - end

//Code for BSB - start
$bsbData = Modules::run('commonModule/BSB/getBSBDataAvailableForPage', $beaconTrackData['pageIdentifier']);
if(!empty($bsbData)){
?>
<script type="text/javascript">
var loggedInUser = '<?php echo (isset($validateuser[0]['userid'])) ? $validateuser[0]['userid'] : 0?>';
var bsbParams = '<?php echo json_encode($bsbData); ?>';
initiateBSB();
</script>
<?php 
}
//Code for BSB - end
?>