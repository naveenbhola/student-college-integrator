<?php
    $headerData['partnerPage'] = 'shiksha';
    $headerData['naukriAssoc'] = "false";
    $headerHtml = $this->load->view('multipleMarketingPage/headerView'.$pageName,array(),true);
    $headerData['headerHtml'] = $headerHtml;
    $headerData['title'] = 'Let us find an institute for you';
	$headerData['css'] = array('common_new');
	$this->load->view('common/homepage_simple',$headerData);
?>
<style>
.cssSprite{
background:url(/public/images/crossImg_14_12.gif) no-repeat;
};
.quesAnsBullets{
background-image: none;
};

</style>
<script>var currentPageName = 'MARKETING_PAGE';</script>

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
	fillProfaneWordsBag();
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
</script>

<div style="width: 950px; margin: 0 auto;">
<div class="spacer5 clearFix"></div>
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
		<div class="flRt">Already Registered? <a href="javascript:void(0);" onClick="shikshaUserRegistration.showLoginLayer(false);">Sign In</a></div>
		<?php }else {?>
		<div class="flRt">Hi <?php echo $userData[0]['displayname']; ?> <a href="#" onClick="SignOutUser();">Sign Out</a></div>
		<?php }?>
		<div class="spacer15 clearFix"></div>
	</div>

	<div>
   		<div class="mmp_banner_container">
	   		<!--Start_Left-->
   			<?php $this->load->view('multipleMarketingPage/'.$pageName);?>
        	<!--End_Left-->
			<!--Start_Right-->
   			<div style="width:480px;float:left">
                <!--Start_Form-->
				<div style="background:#f4f4f4; padding:10px 0px 20px 10px;">
					<?php $this->load->view('multipleMarketingPage/'.$pageName .'FormHeader');?>
					<?php //$this->load->view('multipleMarketingPage/genralMarketingPageForm');?>
					<?php echo Modules::run('registration/Forms/MMP',$pageId); ?>
				</div>
            </div>
        	<!--End_Right-->
            <div class="clearFix"></div>
        </div>
   </div>
                            <?php
								if(isset($pagename) && $pagename=="studyAbroad") {
									$this->load->view('multipleMarketingPage/studyCountryOverlay');
								} else {
									$this->load->view('multipleMarketingPage/marketingCityOverlay');
								}
                            ?>

<?php
		$this->load->view('multipleMarketingPage/multipleMarketingPageSignInOverlay');
		$this->load->view('user/registerConfirmation');
?>

</div>
<div class="clearFix"></div>
<!--<input id="loginflagreg" type="hidden" value="redirect"/>
<input id="loginactionreg" type="hidden" value=""/>
-->
<div class="clearFix spacer10"></div>
<div id="emptyDiv" style="display:none;">&nbsp;</div>
</div>
<div class="clearFix"></div>
    <script>
    var userCity = "";
    <?php if($logged == "Yes") {?>
        userCity = "<?php echo $userData[0]['city']?>";
        <?php }?>
        categoryList = eval(<?php echo json_encode($allCategories);?>);
        var isLogged = '<?php echo $logged; ?>';
        //addOnBlurValidate(document.getElementById("frm1"));
        //addOnFocusToopTip1(document.getElementById("frm1"));
</script>
<?php
/***************************************************************************
 *
 * Management remarketing Code
 * for tagging management traffic
 * Fire only if all the courses on the MMP belongs to management category
 * 
 ***************************************************************************/ 
if(is_array($mainCategoryIdsOnPage) && count($mainCategoryIdsOnPage) == 1 && $mainCategoryIdsOnPage[0] == 3) {
	$this->load->view('multipleMarketingPage/managementRemarketingCode');
}
?>
<?php //$this->load->view('common/ga'); ?>
<script type="text/javascript">
function trackEventByGA(eventAction,eventLabel) {
    if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
        pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
    }
    return true;
}
function OneCategoryForm(){
    var selectObj = $("fieldOfInterest");
    var num = selectObj.options.length;
    if(num == 2){
        selectObj.selectedIndex = 1;
    }
}

window.onload = function () {
        try{
            OneCategoryForm();
	        publishBanners();
	    } catch (e) {
             //alert(e);
        }
    }
</script>
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

<div class="clearFix"></div></div>
<div class="clearFix"></div></div>

