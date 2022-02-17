<?php
    $headerData['partnerPage'] = 'shiksha';
    $headerData['naukriAssoc'] = "false";
    $headerData['js'] = array('header');
    //$headerData['css'] = array('mainStyle','modal-message');
	$headerData['css'] = array('marketing');
    $headerHtml = '<div style="width:959px;margin: 0 auto;" align="right"><span><img src="/public/images/naukrilogo_small.gif"/></div>' . $this->load->view('marketing/it_management_headerView',array('TEXT_HEADING'=>$config_data_array['TEXT_HEADING']),true);
    $headerData['headerHtml'] = $headerHtml;
    $headerData['title'] = 'Let us find an institute for you';
    $this->load->view('common/animation_homepage_simple.php',$headerData);
    $this->load->view('marketing/marketingSignInOverlay');
?>
<style>
.cssSprite{background:url(/public/images/crossImg_14_12.gif) no-repeat;}
.quesAnsBullets{background-image: none;}
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
<div class="wrapperFxd" style="position:relative">
<div style="position:absolute;right:50px;top:0;z-index:9999"><img src="/public/images/ani_char.gif" /></div>
</div>
<div class="whiteColor Fnt10 txt_align_r lineSpace_20 ffV" style="padding-right:65px;z-index:2;position:relative">
	<div class="lineSpace_25">
		<?php if($backPage!=''){ ?>
		<span><a href="<?php echo base64_decode($backPage);?>">&laquo; Go Back To Previous Page</a></span>
		<?php } else { ?>
		<span>&nbsp;</span>
		<?php } ?>
	</div>
	<?php if($logged=="No") {?>
	Already Registered? <a href="javascript:void(0);" onClick="oristate1();"><u>Sign In</u></a>
	<?php } else {?>
	Hi <?php echo $userData[0]['displayname']; ?> <a href="javascript:void(0);" onClick="SignOutUser();">Sign Out</a>
	<?php }?>
</div>
<div class="wdh100">
	<div class="float_L" style="width:249px;margin-right:12px">
		<div class="wdh100" id = "gallery_div_script_tpl">
			<div>
				<span class="whiteColor Fnt15"><em>Choose from over</em></span><br />
				<span style="color:#ff9c00">3554 animation courses across 4500 institutes.</span>
			</div>
			<div style="margin:30px 0">
				<div><img src="/public/images/a_lt.gif" /></div>
				<div class="a_lm">
					<div class="mlr5" align="center">
						<img src="/public/images/anis_logo.gif" border="0" />
					</div>
				</div>
				<div><img src="/public/images/a_lb.gif" /></div>
			</div>
			<div>
				<div class="Fnt15 mb10" style="color:#ff9c00">Now, choose Shiksha.com to</div>
				<div class="whiteColor">
					<div class="a_arw">Get admission alerts</div>
					<div class="a_arw">Seek advice from experts, alumni and institutes</div>
					<div class="a_arw">Know about study abroad options and scholarships</div>
				</div>
			</div>
			<div class="lineSpace_30">&nbsp;</div>
			<div class="Fnt11 whiteColor">Just fill in the small form on the right to mention your educational preferences. Shiksha.com will help you select the most suitable course basis this information.</div>
		</div>
	</div>
	<div class="float_L" style="width:490px;background:#FFF">
		<div><img src="/public/images/a_ft.gif" /></div>
		<div class="a_fm">
			<div style="position:relative;z-index:3">
			<!--Start_Form_here-->
			<?php $this->load->view('marketing/it_management_form');?>
			<!--End_Form_here-->
			</div>
		</div>
		<div><img src="/public/images/a_fb.gif" /></div>
	</div>
	<div class="clear_B">&nbsp;</div>
</div>
</div>
</div>
<div><img src="/public/images/a_tb.gif" /></div>
</div>
<div class="lineSpace_15">&nbsp;</div>
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
</div>
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
</body>
</html>
