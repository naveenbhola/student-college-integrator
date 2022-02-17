</div></div></div></div>

<?php
if($prodId == 901)
{
?>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("bootstrap_min"); ?>"></script>
	<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/validator.js"></script>
	<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery-sortable"); ?>"></script>
<?php
}
else
{
?>
	<?php if(isset($loadUpgradedJQUERY) && $loadUpgradedJQUERY == 'YES'):?>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
	<?php else:?>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<?php endif;?>
<?php
}
?>


<script>
$j = $.noConflict();
// Attach window onclick event handler for GA tracking
function loadNewHomePageWidget(url,widget_id) {
	url = url+'?rnd='+Math.floor((Math.random()*1000000)+1);
	if($(widget_id)){
		new Ajax.Request(url, {
			method : 'get',
			onSuccess : function(response) {
				$(widget_id).innerHTML = response.responseText;
				
				if(widget_id == 'registration_hprdgn') {
					ajax_parseJs($(widget_id));
					evaluateCss($(widget_id));
				}
				
				if(widget_id == 'ranking_page_registration_widget') {
					ajax_parseJs($(widget_id));
					evaluateCss($(widget_id));
				}
				
				if(widget_id == 'hmpgrdsn_tabindia') {
					refreshed_tab_india = true;
				}
				if(widget_id =='careers_widget'){
					$j(".live-tile").liveTile();
				}
				setNameRDGN();
				for ( var i = 0; i < count_of_catgeries_arraya; i++) {
					var element = $('hpgrdgn_left_category'+i);
					var catid = element.getAttribute('catid');
					if(catid !='null'){
						generateOverlayContentOndemand(catid, $(element.id + "_subcat"));
					}
				}
			}
		});
    }
}
loadNewHomePageWidget('/shiksha/loadAjaxForm/abroadtab','hmpgrdsn_tababroad');
loadNewHomePageWidget('/shiksha/loadAjaxForm/abroadmap','study_abroad_rdgn');
</script>

<!--Starts: Footer-->
<?php 
$footerhtmlcache = "HomePageRedesignCache/footerNewWithoutAsk.html";
if(file_exists($footerhtmlcache)){
    echo file_get_contents($footerhtmlcache);
}else{
	$footerHtml = $this->load->view('common/footerHtml',array('isAskButton' => false),true); 
	$pageContent = sanitize_output($footerHtml);

  	echo $pageContent;
    $fp=fopen($footerhtmlcache,'w+');
    flock( $fp, LOCK_EX ); // exclusive lock
    fputs($fp,$pageContent);
    flock( $fp, LOCK_UN ); // release the lock
    fclose($fp);
}
?>
<!--Ends: Footer-->
</div>
<div class="clearFix"></div>
</div>
</div>
    

    <div id="onLoadOverlayContainer"></div>
    <?php
    if (!(isset($search) && $search=="false")) {
        if(!is_array($validateuser) || !isset($validateuser[0])) {
            if(!isset($calendarDivLoaded) || $calendarDivLoaded ==0){ ?>
                <script>try{ overlayViewsArray.push(new Array('common/calendardiv','calendardivId')); }catch(e){ }</script>
                    <?php  }
        }

    }
    if(!isset($commonOverlayDivLoaded) || $commonOverlayDivLoaded ==0){ ?>
        <script>try{ overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay')); }catch(e){ }</script>
   <?php } ?>
<?php
$alreadyAddedJsFooter = array('footer','user','lazyload');
if(!isset($jsFooter)){
	$jsFooter = array();
} else {
	foreach ($jsFooter as $foojsfile) {
		if($foojsfile != 'ajax-api') {
			$foojsfilearr[] = $foojsfile;
		}
	}
	$jsFooter = $foojsfilearr;
}

$jsFooter = getJsToInclude(array_unique(array_merge($alreadyAddedJsFooter, $jsFooter)));
    if(isset($jsFooter) && is_array($jsFooter)) {
        foreach($jsFooter as $jsFile) {
?>
                <script language="javascript" src="<?php echo $jsFile;?>"></script>
<?php
        }
    }
?>
<?php
	$cvsJsIncludedOnPage = '';
	if(is_array($js)){
		$cvsJsIncludedOnPage = implode(",",$js);
	}
	if(is_array($jsFooter)){
		if(strlen($cvsJsIncludedOnPage) > 0)
			$cvsJsIncludedOnPage .= ','.implode(",",$jsFooter);
		else
			$cvsJsIncludedOnPage .= implode(",",$jsFooter);
	}
?>
<input type="hidden" name="cvsJsIncludedOnPage" id="cvsJsIncludedOnPage" value="<?php echo $cvsJsIncludedOnPage; ?>" />
<?php global $clientIP;
if(strpos($clientIP,"shiksha")!==false) { ?>
<?php $this->load->view('common/ga'); ?>
<?php } ?>
<?php
/*
//Commented by Ankur Gupta on 28 Nov. The usage of this Tracker is not clear and it is throing 500 error on many pages of Shiksha
if(PAGETRACK_BEACON_FLAG)
{
    $this->load->view('common/pageTrack_beacon.php');
} */
global $serverStartTime;
$trackForPages = isset($trackForPages)?$trackForPages:false;
$endserverTime =  microtime(true);
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
?>

<?php
/**********************************
 *
 * Management remarketing Code
 * for tagging management traffic
 * 
 **********************************/
if($showApplicationFormHomepage || (is_array($mainCategoryIdsOnPage) && in_array(3,$mainCategoryIdsOnPage))) {
        $this->load->view('multipleMarketingPage/managementRemarketingCode');
}
?>

<div id="vcmsPopup" class="vcms-popup"><a id="vcmsClose" class="vms-popup_cls">&times;</a><div id="vcmsPopupContent" class="vcms-popup_contentbox"><div id="vcmsLoader"><img src="https://images.shiksha.ws/public/mobile5/images/ShikshaMobileLoader.gif" /></div></div></div>
</body>
</html>
<?php
    echo getTailTrackJs($tempForTracking,true,$trackForPages,'http://track.99acres.com/images/zero.gif');
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
<img src="http://b.scorecardresearch.com/p?c1=2&c2=6035313&cv=2.0&cj=1" />
</noscript>
<!-- End comScore Tag -->
<script>

// added for switch tracking of search results on and off
var track_search_results_flag = "<?php echo TRACK_SEARCH_RESULTS;?>";

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
	 content_div_obj.style.position = 'absolute';
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
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>',
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
	'//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("UnifiedRegistration");?>'
         ],loadRequiredDataForUnifiedRegistrationProcess,null,null,true);
}


<?php
/*
 * TODO: This is temp FIX !!! Will remove following lines once get update static pages for 404,505 etc.
 * NO NEED TO EXCUTE OVERLAYS AJAX CALL IN 404 ERROR PAGE
 *
 */
if ((!isset($errorPageFlag)) && ($errorPageFlag != 'true')) {
?>
  setTimeout(function(){if(typeof loadViewsOnPageLoad == 'function'){loadViewsOnPageLoad();} },2000);
<?php
}
?>


  

/*
    Function which put values into global vars. like is_ldb user or cross btn click
*/

function loadRequiredDataForUnifiedRegistrationProcess()
{
     	/* ajax to set if user register or not START */
	<?php
	/*
	 * TODO: This is temp FIX !!! Will remove following lines once get update static pages for 404,505 etc.
	 * NO NEED TO EXCUTE ISLDB AJAX CALL IN 404 ERROR PAGE
	 * 
	 */
	if ((!isset($errorPageFlag)) && ($errorPageFlag != 'true')) {
	?>
	checkLdbUser();
	<?php
	}
        if(isset($_REQUEST['apply'])){
        ?>
            ApplyNowFromCategory();
        <?php
        }
        ?>
        /* ajax to set if user register or not END */
        /* set variable to check whether user has clicked unified overlay or not*/
        unified_form_overlay1_cancel_clicked = getCookie('is_unified_overlay1_clicked');
        unified_form_overlay2_cancel_clicked = getCookie('is_unified_overlay2_clicked');
        unified_form_overlay3_cancel_clicked = getCookie('is_unified_overlay3_clicked');
        /* set Form submit url for diff types of overlays */
        if(typeof(arr_unified) !== 'undefined') {
        	ShikshaUnifiedRegistarion.url_unified = ShikshaUnifiedRegistarion.ajaxUrlHelper(arr_unified);
        }
         if($('homepagePromotionCarousel')) {
         	//if($('questionText')) { $('questionText').value = 'Make an informed career choice, ask the expert now!';}
         }
}
/* UNIFIED REGISTRATION APIs END */

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}


var facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";
</script>
<script>
addLoadEvent(function() {
	loadNewHomePageWidget('/shiksha/loadAjaxForm/homepageform','registration_hprdgn');
	// loadNewHomePageWidget('/rankingV2/RankingMain/loadRankingRegistrationWidget/','ranking_page_registration_widget');
    if(typeof(evaluateAllHomepageDivs) == 'function') {
    	evaluateAllHomepageDivs();
    }
    if(self.buttonForFConnectAndFShare) {
        buttonForFConnectAndFShare();
    }
    
    initForUnifiedRegistration();
  	//code added for homepage rdgn abroad
	if (typeof(shoshkeleUrl) != 'undefined' && typeof(shoshkeleType) != 'undefined') {
		showShohkele(shoshkeleUrl,shoshkeleType);
	}
	//Code added for showing ranking page banner
	if(typeof($rankingPage) != "undefined"){
		if(typeof($rankingPage.rankingPageBannerURL) != "undefined" && $rankingPage.rankingPageBannerURL != ""){
			var rankingPageBannerURL = $rankingPage.rankingPageBannerURL;
			var rankingPageBannerLandingURL = $rankingPage.rankingPageBannerLandingURL;
			displayRankingPageBanner(rankingPageBannerURL, rankingPageBannerLandingURL);
		}
	}
	 if(typeof(bxsliderListingRecommendation) !='object' && $('bxsliderListingRecommendation')) {
		var settings = 
		{
			auto: false,
			speed:800,
			autoHover:true,
			loop:true,
			controls:false
		};
		
			if(typeof(bxSlider) == 'function') {
				bxsliderListingRecommendation = $j('#bxsliderListingRecommendation').bxSlider(settings);
			}

         }
	// code added for abroad category page
	
		if(typeof(setGATracking) == 'function') {
			setGATracking();
		}
        if(typeof(run_flag_article) !="undefined" && run_flag_article) {
		
                try {
                       
                	if($('royalSlider_new')) {
				runautoCarousle('#royalSlider_new',470,366,'none',true);
                                $j("#royalSlider_new div[uniqueattr=Listing-Photo]").each(function(i,el) {
					$j(this).attr("uniqueattr","Studyabroad-Left-Article");
				});
			}
           
                        if($('bxsliderquicklinks')) {
				know_more_article_slides = $j('#bxsliderquicklinks').bxSlider(defaults_no_knowmore_articles);
                        }
			run_flag_article = false;
                } catch(e) {
			//do nothing
                }
	}
	publishBanners();
});
function proceedToPostQuestionFromHome(objForm,id){
    objForm.setAttribute("method",'get');
    objForm.setAttribute("action",'<?php echo SHIKSHA_ASK_HOME."/messageBoard/MsgBoard/postQuestionFromCafeForm"; ?>');
    objForm.submit();
}
</script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("trackingCode"); ?>">
</script>
<script>
 if(typeof(handleClickForAutoSuggestor) == "function") {
	if (window.addEventListener){
		document.addEventListener('click', handleClickForAutoSuggestor, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', handleClickForAutoSuggestor);
	}
 }
 
 if(typeof(handleClickForAutoSuggestorAlt) == "function") {
	if (window.addEventListener){
		document.addEventListener('click', handleClickForAutoSuggestorAlt, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', handleClickForAutoSuggestorAlt);
	}
 }
 
 
 
 if(typeof(setCustomizedVariableForTheWidget) == "function") {
	if (window.addEventListener){
		window.addEventListener('click', setCustomizedVariableForTheWidget, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', setCustomizedVariableForTheWidget);
	}
 }
 
 if(typeof(logSearchQuery) == "function") {
	logSearchQuery();
 }
 
</script>

<script>

<?php
if($prodId == 901){
?>
var tilesDataJson = '<?=$tilesDataJson?>';
tilesDataJson = JSON.parse(tilesDataJson);
var checkValidation_mobile = true;
var checkValidation_desktop = true;
var toBeStopped = true;
$j(document).ready(function(){
	$j('#addEditSubmitButton').removeAttr('disabled').removeClass('disabled');
	$j('#addEditTile').validator().on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			$j('#addEditSubmitButton').removeAttr('disabled');
		} else {
			$j('#addEditSubmitButton').attr('disabled', 'disabled');
			//add custom validations here, if any
			if (toBeStopped) {
				e.preventDefault();
				var extentions = Array('jpg','jpeg', 'png', 'gif');
				var mobileImage = $j('#mobilePhoto').val();
				var desktopImage = $j('#desktopPhoto').val();
				//for mobile image
				var tempArr = mobileImage.split('.');
				//mobile image was made optional for UGC-2481, so this validation is removed
				if (false && extentions.indexOf(tempArr[tempArr.length-1]) == -1) {
					$j('#mobilePhoto').closest('.form-group').addClass('has-error');
					$j('#mobilePhoto').siblings('div.help-block.with-errors').html('<ul class="list-unstyled"><li>Please upload an image.</li></ul>');
					checkValidation_mobile = false;
				}
				else
				{
					$j('#mobilePhoto').closest('.form-group').removeClass('has-error');
					$j('#mobilePhoto').siblings('div.help-block.with-errors').html('');
					checkValidation_mobile = true;
				}
				//var desktop image
				tempArr = desktopImage.split('.');
				if (extentions.indexOf(tempArr[tempArr.length-1]) == -1) {
					$j('#desktopPhoto').closest('.form-group').addClass('has-error');
					$j('#desktopPhoto').siblings('div.help-block.with-errors').html('<ul class="list-unstyled"><li>Please upload an image.</li></ul>');
					checkValidation_desktop = false;
				}
				else
				{
					$j('#desktopPhoto').closest('.form-group').removeClass('has-error');
					$j('#desktopPhoto').siblings('div.help-block.with-errors').html('');
					checkValidation_desktop = true;
				}
				$j('#addEditSubmitButton').removeAttr('disabled');
				
			}
			if (checkValidation_mobile && checkValidation_desktop) {
				toBeStopped = false;
				submitCollegeReviewTileForm($('addEditTile'));
				//$j('#addEditTile').submit();
			}
		}
        });
	$j('input[name="tileType"]').on('change', function(){
		var linkType = $j(this).val();
		var editTileId = $j('input[name="tileId"]').val();
		if(linkType == 'courseList')
		{
			$j('input[name="tileTypeText"]').attr('pattern','[0-9]+(,[0-9]+)*');
			if (typeof(editTileId)!= 'undefined' && editTileId!='' && $j('input[name="tileSeoUrl"]').val() != '') {
				$j('input[name="tileSeoUrl"]').prop('readonly',true);
			}
			else{
				$j('input[name="tileSeoUrl"]').removeAttr('readonly').prop('required',true);
			}
			$j('input[name="tileSeoTitle"]').removeAttr('readonly').prop('required',true);
			$j('textarea[name="tileSeoDescription"]').removeAttr('readonly').prop('required',true);
		}
		else if (linkType == 'url') {
			$j('input[name="tileTypeText"]').removeAttr('pattern');
			$j('input[name="tileSeoUrl"]').prop('readonly',true).removeAttr('required').siblings('.help-block.with-errors').html('').closest('.form-group').removeClass('has-error');
			$j('input[name="tileSeoTitle"]').prop('readonly',true).removeAttr('required').siblings('.help-block.with-errors').html('').closest('.form-group').removeClass('has-error');
			$j('textarea[name="tileSeoDescription"]').prop('readonly',true).removeAttr('required').siblings('.help-block.with-errors').html('').closest('.form-group').removeClass('has-error');
		}
	});
	$j('#myModal').on('show.bs.modal', function(e) {
		var tileDataId = $j(e.relatedTarget).data('tile-id');
		if (typeof(tileDataId)!= 'undefined' && tileDataId!='') {
			$j(e.currentTarget).find('input[name="tileId"]').val(tileDataId);
			$j(e.currentTarget).find('input[name="cmsAction"]').val('editTile');
			$j('#myModalLabel').html('Update Tile');
			$j('#myModal').find('form')[0].reset();
			$j('textarea[name="tileSeoDescription"]').html('');
			fillCRHTileEditForm(e.currentTarget, tileDataId);
			$j('#mobilePhoto').removeAttr('required');
			$j('#desktopPhoto').removeAttr('required');
			var linkType = $j('input[name="tileType"]:checked').val();
			if(linkType == 'courseList')
			{
				$j('input[name="tileTypeText"]').attr('pattern','[0-9]+(,[0-9]+)*');
				$j('input[name="tileSeoUrl"]').prop('readonly',true);
				$j('input[name="tileSeoTitle"]').removeAttr('readonly').prop('required',true);
				$j('textarea[name="tileSeoDescription"]').removeAttr('readonly').prop('required',true);
			}
			else if (linkType == 'url') {
				$j('input[name="tileTypeText"]').removeAttr('pattern');
				$j('input[name="tileSeoUrl"]').prop('readonly',true).removeAttr('required').siblings('.help-block.with-errors').html('').closest('.form-group').removeClass('has-error');
				$j('input[name="tileSeoTitle"]').prop('readonly',true).removeAttr('required').siblings('.help-block.with-errors').html('').closest('.form-group').removeClass('has-error');
				$j('textarea[name="tileSeoDescription"]').prop('readonly',true).removeAttr('required').siblings('.help-block.with-errors').html('').closest('.form-group').removeClass('has-error');
			}
			toBeStopped = false;
		}
		else{
			toBeStopped = true;
			$j(e.currentTarget).find('input[name="tileId"]').val('');
			$j(e.currentTarget).find('input[name="cmsAction"]').val('addTile');
			$j('#myModalLabel').html('Add New Tile');
			$j('#myModal').find('form')[0].reset();
			//$j('#mobilePhoto').attr('required', 'required');
			$j('#desktopPhoto').attr('required', 'required');
			$j('input[name="tileSeoUrl"]').removeAttr('readonly').val('');
			$j('input[name="tileSeoTitle"]').removeAttr('readonly').val('');
			$j('textarea[name="tileSeoDescription"]').removeAttr('readonly').html('');
			$j('textarea[name="tileDescription"]').html('');
		}
	});
	$j('#myModal').on('hidden.bs.modal', function () {
		
		$j('#addEditTile .form-group').removeClass('has-error');
		$j('#addEditTile div.help-block.with-errors').html('');
	});
	$j('#previewModal').on('show.bs.modal', function(e) {
		var tileDataId = $j(e.relatedTarget).data('tile-id');
		$j('#desktopImagePreview').css('background-image','url("'+tilesDataJson[tileDataId].dImage+'")');
		$j('.mobileImagePreview').css('background-image', 'url("'+tilesDataJson[tileDataId].mImage+'")');
	});
	$j('#topTileTable').sortable({
		containerSelector: 'tbody',
		itemSelector: 'tr',
		placeholder: '<tr class="placeholder"/>',
		distance: 10,
		onDrop: function(item, container){
			$j('.collegeReviewTilesOverlayLoader').show();
			$j(item).removeClass('dragged').removeAttr("style");
			var newOrder = [];
			$j('.topTileOrder').each(function(index){
				newOrder[index] = $j(this).attr('tileId');
				$j(this).html(index+1)
			});
			var ajaxURL = "/enterprise/ReviewHomepageTiles/reOrderCMSTiles";
			$j.ajax({
				type: 'POST',
				url : ajaxURL,
				data: {'newOrderStr':newOrder.join()},
				success :function(response){
					$j('.collegeReviewTilesOverlayLoader').hide();
				}
			});
		}
	});
	$j('#bottomTileTable').sortable({
		containerSelector: 'tbody',
		itemSelector: 'tr',
		placeholder: '<tr class="placeholder"/>',
		distance: 10,
		onDrop: function(item, container){
			$j('.collegeReviewTilesOverlayLoader').show();
			$j(item).removeClass('dragged').removeAttr("style");
			var newOrder = [];
			$j('.bottomTileOrder').each(function(index){
				newOrder[index] = $j(this).attr('tileId');
				$j(this).html(index+1)
			});
			var ajaxURL = "/enterprise/ReviewHomepageTiles/reOrderCMSTiles";
			$j.ajax({
				type: 'POST',
				url : ajaxURL,
				data: {'newOrderStr':newOrder.join()},
				success :function(response){
					$j('.collegeReviewTilesOverlayLoader').hide();
				}
			});
		}
	});
});
<?php
}
?>
</script>
<?php echo Modules::run('tracking/generateTrackingImage',$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],$_SERVER['HTTP_REFERER']); ?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("userRegistration"); ?>"></script>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("cmsForms"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("vcms"); ?>" type="text/css" rel="stylesheet" />
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("cmsForms"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("vcms"); ?>"></script>

<?php $this->load->view('common/googleRemarketing');?>
<?php
if(!empty($zopimInstituteId) && !empty($zopimScriptTag)){
	echo $zopimScriptTag;
}
?>

<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName("script")[0];
a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0019/0281.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>

<script>

	$j(document).ready(function(){
		if(typeof hierarchyMappingForm !='undefined'){
			hierarchyMappingForm.hierarchyMappingFormDOMReadyCalls();	
		}
		if(typeof vcms !='undefined'){
			vcms.vcmsDOMReadyCalls();
		}
	});

    $j(window).load(function(){
    	if(typeof hierarchyMappingForm !='undefined'){
    		hierarchyMappingForm.hierarchyMappingFormDOMLoadCalls();
    	}
    	if(typeof vcms !='undefined'){
			vcms.vcmsDOMLoadCalls();
		}
    });
	
	function getSubscriptionDetails(subscriptionId) {
		//Code here!
		ajaxURL = "/listingPosting/AbroadListingPosting/getSubscriptionDetails/"+subscriptionId;
		var a,b,c;
		$j.ajax({
			type: 'POST',
			url : ajaxURL,
			async : false,
			success :function(response){
				response =  JSON.parse(response);
				if (response[0]) {
					a = response[0].SubscriptionStartDate;
					b = response[0].SubscriptionEndDate;
					c = response[0].BaseProdRemainingQuantity;
				}
			}
		});
		if (a) {
			return [a,b,c];
		}
		else return ;
	}
</script>
<?php //loadBeaconTracker($beaconTrackData); ?>
