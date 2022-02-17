<?php
    $headerData['partnerPage'] = 'shiksha';
    $headerData['naukriAssoc'] = "false";
    $headerData['js'] = array('header');
    $headerData['css'] = array('marketing');
    $headerHtml =  $this->load->view('marketing/it_management_headerView',array('TEXT_HEADING'=>$config_data_array['TEXT_HEADING']),true);
    $headerData['headerHtml'] = $headerHtml;
    $headerData['title'] = 'Let us find an institute for you';
    $this->load->view('common/homepage_simple',$headerData);
    $this->load->view('marketing/marketingSignInOverlay');
?>
<style>
.cssSprite{
background:url(/public/images/crossImg_14_12.gif) no-repeat;
}
.quesAnsBullets{
background-image: none;
}
</style>
<script>
var isLogged = '<?php echo $logged; ?>';
var messageObj;
var FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
function loadScript(url, callback){
    var script = document.createElement("script")
    script.type = "text/javascript";
    if (script.readyState){  //IE
        script.onreadystatechange = function(){
            if (script.readyState == "loaded" ||
                    script.readyState == "complete"){
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {  //Others
        script.onload = function(){
            callback();
        };
    }
    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}
loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>', function(){
    //initialization code
});
loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>', function(){
    //initialization code
});
loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>', function(){
    //initialization code
    messageObj = new DHTML_modalMessage();
    messageObj.setShadowDivVisible(false);
    messageObj.setHardCodeHeight(0);
});
loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("tooltip"); ?>', function(){
    //initialization code
});
loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("itmarketingpage"); ?>', function(){
    //initialization code
});
</script>
<div style="position:relative; width:950px; margin:0 auto">
<div>
	<div>
		<div class="float_L lineSpace_25">
		<?php if($backPage!=''){ ?>
		<span><a href="<?php echo base64_decode($backPage);?>">&laquo; Go Back To Previous Page</a></span>
		<?php } else { ?>
		<span>&nbsp;</span>
		<?php } ?>
		</div>
		<?php if($logged=="No") {?>
		<div class="flRt">Already Registered? <a href="javascript:void(0);" onClick="oristate1();">Sign In</a></div>
		<?php } else {?>
		<div class="flRt">Hi <?php echo $userData[0]['displayname']; ?> <a href="#" onClick="SignOutUser();">Sign Out</a></div>
		<?php }?>
		<div class="spacer15 clearFix">&nbsp;</div>
	</div>
    <!--Start_OuterBorder-->
    <div>
        <div style="float:left;width:460px">
                <div id = "gallery_div_script_tpl" style="width:414px;"><?php $this->load->view('marketing/marketing_it_management_left'); ?></div>
        </div>
        <div style="float:right;width:480px;">
            <div style="background:#f4f4f4">
                <?php $this->load->view('marketing/it_management_form');?>
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
    </div>
<script>
function removetip(){
    if (document.getElementById('helpbubble1')) {
        document.getElementById('helpbubble1').style.display='none';
    }
    var other= document.getElementById('mobile').value;
    var objErr = document.getElementById('mobile_error');
    msg = validateMobileInteger(other,'mobile number',10,10,1);
    if(msg!==true)
    {
	objErr.innerHTML = msg;
	objErr.parentNode.style.display = 'inline';
	return false;
    }
    else
    {
	objErr.innerHTML = '';
	objErr.parentNode.style.display = 'none';
	return true;
    }
}
// js var for google event tracking
var currentPageName = '<?php echo $pagename; ?>';
var pageTracker = null;
</script>
<div id="marketingLocationLayer_ajax"></div>
<div id="marketingusersign_ajax"></div>
</div>
<div class="clear_L"></div>
<div class="lineSpace_10">&nbsp;</div>
<div id="emptyDiv" style="display:none;">&nbsp;</div>
<script id="galleryDiv_script_validate">
    function RenderInit() {
        addOnBlurValidate(document.getElementById('frm1'));
        addOnFocusToopTip1(document.getElementById('frm1'));
    }
    window.onload = function () {
        try{
	    RenderInit();
            publishBanners();
	    ajax_loadContent('marketingLocationLayer_ajax','/marketing/Marketing/ajaxform_mba/mr_page');
        } catch (e) {
             //alert(e);
        }
    }
</script>
</div>
<?php $this->load->view('common/ga'); ?>
<script type="text/javascript">
  function trackEventByGA(eventAction,eventLabel) {
	if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
	    pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
	}
	return true;
    }
</script>
<?php
global $serverStartTime;
$trackForPages = isset($trackForPages)?$trackForPages:false;
$endserverTime =  microtime(true);
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
echo getTailTrackJs($tempForTracking,true,$trackForPages,'https://track.99acres.com/images/zero.gif');
?>
<!-- Begin comScore Tag -->
<script>
  var _comscore = _comscore || [];
  _comscore.push({ c1: "2", c2: "6035313" });
  (function() {
    var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
    s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
    el.parentNode.insertBefore(s, el);
  })();
</script>
<noscript>
<img src="https://b.scorecardresearch.com/p?c1=2&c2=6035313&cv=2.0&cj=1" />
</noscript>
<!-- End comScore Tag -->
<div class="clearFix"></div>
</div>
<div class="clearFix"></div>
</div>
<div class="clearFix"></div>
</div>
