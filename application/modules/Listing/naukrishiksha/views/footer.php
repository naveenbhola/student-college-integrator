
<br clear="all" />
</div>
</div>
<!--End_Center-->
<?php
//	$this->load->view('common/feedback.php');
//	$this->load->view('common/contactUs.php');
?>
<!--Start_Footer-->
<div class="wrapperFxd">
	<div class="lineSpace_10">&nbsp;</div>
<!--	<div align="center"><?php $this->load->view('common/banner.php');?></div>-->
	<div class="lineSpace_10">&nbsp;</div>
<!--	<div align="center" class="footertxt" >
		<a href="<?php echo SHIKSHA_ABOUTUS_HOME; ?>">About Us</a> -
		<a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/privacyPolicy')">Privacy Policy</a> -
		<a href="javascript:void(0);" onclick="return showFeedBack();">Feedback</a> -
		<a href="<?php echo SHIKSHA_FAQ_HOME; ?>">FAQ</a> -
		<a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/contactUs">Contact Us</a> -
		<a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/siteMap">Site Map</a> -
		<a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/browseByCategory/browse-colleges-career-option-listings">Browse: Institutes and Career</a> -
		<a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition');">Terms and Conditions</a>
	</div>
	<div style="line-height:5px">&nbsp;</div>
	<div align="center" class="footertxt"><span style="color:#000">Our Partners:</span>
		<a href="http://www.naukri.com" target="_blank">Jobs</a> -
		<a href="http://www.firstnaukri.com" target="_blank">Jobs for freshers</a> -
		<a href="http://www.99acres.com" target="_blank">Real Estate</a> -
		<a href="http://www.naukrigulf.com" target="_blank">Jobs in Middle East</a> -
		<a href="http://www.allcheckdeals.com" target="_blank">Real Estate Agent</a> -
		<a href="http://www.jeevansathi.com" target="_blank">Matrimonials</a> -
		<a href=" http://www.policybazaar.com" target="_blank">Insurance comparison</a>
	</div>
	<div style="line-height:5px">&nbsp;</div>
	<div align="center"> Trade Marks belong to the respective owners.</div>-->
	<div style="line-height:5px">&nbsp;</div>
	<div id="footerGradient" align="center"> Copyright &copy; 2009 Info Edge India Ltd. All rights reserved.</div>
</div>
<!--End_Footer-->

<iframe src="" width=0 height=0 id="tmp_frm_iframe1" name="tmp_frm_iframe1" style="display:none"></iframe>
<?php $this->load->view('common/ga'); ?>
</div>
</body>
</html>
<script>
try{
publishBanners();
} catch(e) {}
/* UNIFIED REGISTRATION APIs START */
function displayMessageBox(url,w,h) {
     messageObj.setShadowDivVisible(false);
     messageObj.setFlagShowLoaderAjax(false);
     messageObj.setSource(url);
     messageObj.setCssClassMessageBox(false);
     messageObj.setSize(w,h);
     messageObj.display();
     var content_div_obj = $('DHTMLSuite_modalBox_contentDiv');
     content_div_obj.style.background = 'none';
     content_div_obj.style.background = '';
     content_div_obj.style.zIndex = '99970';
     content_div_obj.style.top = '0px';
     $('DHTMLSuite_modalBox_transparentDiv').style.zIndex = "99960";
     return false;
}
/**
 * Method closes unified registration overlay
 */
function closeMessageBox()
{
     messageObj.close();
     return false;
}
/**
 * Method that renders unified overlay
 */

function callUnifiedOverlay(url,width,height,page_identifier,widget_identifier)
{
    if(arr_unified[0] == '2') {
      height = 527;
      if(page_identifier == 'article') {
      	height = 480;	
    }
    }else if(arr_unified[0] == '1'){
      height = 331;
      width = 670; 
    } else if(arr_unified[0] == '3'){
      height = 467;
      width = 670;
    }
    if(typeof(widget_identifier) === 'undefined') {
    	widget_identifier = "homepageregisterbutton";
    }
    page_identifier_unified = page_identifier;
    unified_widget_identifier = widget_identifier;
    if(unified_registration_is_ldb_user == 'false' && (page_identifier_unified == 'homepage' || page_identifier_unified == 'article')) {
        displayMessageBox(url,width,height);
	} else if(unified_registration_is_ldb_user == 'false' && ((arr_unified[0] == '1' && unified_form_overlay1_cancel_clicked != 'true') ||
        (arr_unified[0] == '2' && unified_form_overlay2_cancel_clicked != 'true') || (arr_unified[0] == '3' && unified_form_overlay3_cancel_clicked != 'true'))  ){
		displayMessageBox(url,width,height);
	} else{
	closeMessageBox();
	}
}
/**
 * Method loads all required scripts for Unified Registration
 * callback function is loadRequiredDataForUnifiedRegistrationProcess
 * callback API will be alwys called either file is already loaded or not
 */
function initForUnifiedRegistration()
{
     LazyLoad.loadOnce([
         '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
         '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>',
	'//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("UnifiedRegistration");?>'
         ],loadRequiredDataForUnifiedRegistrationProcess,null,null,true);
}

/*
    Function which put values into global vars. like is_ldb user or cross btn click
*/

function loadRequiredDataForUnifiedRegistrationProcess()
{
        /* overlay global obj is called */
     	messageObj = new DHTML_modalMessage();
     	/* ajax to set if user register or not START */
        checkLdbUser();
        /* ajax to set if user register or not END */
        /* set variable to check whether user has clicked unified overlay or not*/
        unified_form_overlay1_cancel_clicked = getCookie('is_unified_overlay1_clicked');
        unified_form_overlay2_cancel_clicked = getCookie('is_unified_overlay2_clicked');
        unified_form_overlay3_cancel_clicked = getCookie('is_unified_overlay3_clicked');
        /* set Form submit url for diff types of overlays */
        ShikshaUnifiedRegistarion.url_unified = ShikshaUnifiedRegistarion.ajaxUrlHelper(arr_unified);
}
/* UNIFIED REGISTRATION APIs END */
setTimeout(function(){initForUnifiedRegistration();},10);
</script>
