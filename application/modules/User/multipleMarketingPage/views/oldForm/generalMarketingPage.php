<?php
    $headerData['partnerPage'] = 'shiksha';
    $headerData['naukriAssoc'] = "false";
    $headerHtml = $this->load->view('multipleMarketingPage/headerView'.$pageName,array(),true);
    $headerData['headerHtml'] = $headerHtml;
    $headerData['title'] = 'Let us find an institute for you';
	$this->load->view('common/oldForm/homepage_simple',$headerData);
    ?>

<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("marketing"); ?>" type="text/css" rel="stylesheet" />
<style>
.cssSprite{
background:url(/public/images/crossImg_14_12.gif) no-repeat;
};
.quesAnsBullets{
background-image: none;
};

</style>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>"></script>
<script>var currentPageName = 'MARKETING_PAGE';</script>

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
		<div class="flRt">Already Registered? <a href="javascript:void(0);" onClick="oristate1();">Sign In</a></div>
		<?php }else {?>
		<div class="flRt">Hi <?php echo $userData[0]['displayname']; ?> <a href="#" onClick="SignOutUser();">Sign Out</a></div>
		<?php }?>
		<div class="spacer15 clearFix"></div>
	</div>

	<div style="background:url(/public/images/study_CenterLine.gif) repeat-y left top">
   		<div>
	   		<!--Start_Left-->
   			<?php $this->load->view('multipleMarketingPage/oldForm/'.$pageName);?>
        	<!--End_Left-->
			<!--Start_Right-->
   			<div style="width:480px;float:left">
            	<div style="margin-left:15px">
                	<!--Start_Form-->
                	<div style="width:465px">
   			            <?php $this->load->view('multipleMarketingPage/oldForm/'.$pageName .'FormHeader');?>
						<?php $this->load->view('multipleMarketingPage/oldForm/genralMarketingPageForm');?>
                    </div>
                    <!--End_Form-->
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
        <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>"></script>
        <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("homePage"); ?>"></script>
        <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("multipleMarketingPageStudyAbroad"); ?>"></script>
        <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>"></script>
    <script>
    var userCity = "";
    <?php if($logged == "Yes") {?>
        userCity = "<?php echo $userData[0]['city']?>";
        <?php }?>
        categoryList = eval(<?php echo json_encode($allCategories);?>);

        fillProfaneWordsBag();
        var isLogged = '<?php echo $logged; ?>';
        addOnBlurValidate(document.getElementById("frm1"));
        addOnFocusToopTip1(document.getElementById("frm1"));
</script>

        <script type="text/javascript">
        function trackEventByGA(eventAction,eventLabel) {
            if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
                pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
            }
            return true;
        }
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
<?php $this->load->view('common/ga'); ?>
<script type="text/javascript">
function OneCategoryForm(){
    var selectObj = $("fieldOfInterest");
    var num = selectObj.options.length;
    if(num == 2){
        selectObj.selectedIndex = 1;
        $("fieldOfInterest").style.display = 'none';
        var newdiv = document.createElement('div');
        newdiv.innerHTML = selectObj.options[1].text;
        $("fieldOfInterestFieldContainer").getElementsByTagName("div")[0].appendChild(newdiv);
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

<?php $this->load->view('common/googleRemarketing');?>
