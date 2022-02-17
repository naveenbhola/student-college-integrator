<?php 
if(empty($trackingPageKeyId) || $trackingPageKeyId == 0){
	$trackingPageKeyId = 1263; //default tracking id for online form.
}
$seo_title = "";
$seo_desc = "";
$seo_keyword = "";
if($PBTSEOData['seoTitle']!='') {
	$seo_title = $PBTSEOData['seoTitle'];
}
if($PBTSEOData['seoDesc']!='') {
	$seo_desc  = $PBTSEOData['seoDesc'];
}

				$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
				$criteriaArray = array(
                                    'category' => $categoryIdForBanner,
                                     'country' => '',
                                     'city' => '',
                                     'keyword'=>'');
				   $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);
				   $headerComponents = array(
				      'css'	=>	array('online-styles','header','raised_all','mainStyle'),
				      'js' 	=>	array('common','ana_common','myShiksha','onlinetooltip','CalendarPopup','imageUpload','onlineForms','ajax-api'),
				      'title'	=>	$seo_title,
				      'tabName' =>	'Discussion',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=>$seo_desc,	
				      'metaKeywords'	=>$seo_keyword,
				      'product'	=>'online',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'callShiksha'=>1,
				      'notShowSearch' => true,
                                      'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
                                      'showBottomMargin' => false,
				      'showApplicationFormHeader' => true,
                   'canonicalURL' => $PBTSEOData['canonicalUrl']
				   );

   $this->load->view('common/header', $headerComponents);
   $this->load->view('common/calendardiv');
   global $onlineFormsDepartments;
	$department = $this->courselevelmanager->getCurrentDepartment();
?>   
<script>
var isUserLoggedInSystem = '<?php echo $userId;?>';
var urlToRedirect = '<?php echo $urlToRedirect;?>';
var externalForm = '<?php echo $externalForm;?>';
<?php if($userId != '' && $isValidResponseUser == 'no') { ?>
	var isIncompleteUserOnOF = 'yes';
<?php } ?>

</script>
<div id="_newOverlay" style="display: none;background-color: #000; display: none; height: 2923px; left: 0; opacity: 0.4; position: absolute; top: 0; width: 1296px; z-index: 1000;"></div>
<div id="appsFormWrapper">
<input type="hidden" id="departmentType" value="<?=$departmentType?>">

	   <?php 
	  if($courseId>0):
	  	$this->load->view('Online/externalFormRegistrationContent');
	  else:
	  	$this->load->view('Online/showLoginForm');
	  endif;?>
    
</div>
    <?php  
      $this->load->view('Online/externalFormFooter'); 
    ?>
  
<div class="clearFix"></div>	
<?php 
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('Online/footerOnline',$bannerProperties1);
	$this->load->view('common/footer');
?> 
<?php if($userId == '' || $isValidResponseUser == 'no') { ?>
<script>
   //window.regFormLoadonlineForm();
  if($('userLoginOverlay_online')){
      $('userLoginOverlay_online').style.display = '';
      //$('loginLayer').align = 'center';
      //$('userLoginOverlay_online').style.width = '800px';
  }
</script>
<?php } ?>
<script>
var OnlineForm = {};
OnlineForm.displayAdditionalInfoForInstitute = function (style,divId) {
	if($(divId)) {
		$(divId).style.display = style;
	}
}
</script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlineFormCommon"); ?>"></script>
<script>
	if (window.addEventListener){
                window.addEventListener('click', handleLayerHide1, false); 
	} else if (window.attachEvent){
                document.attachEvent('click', handleLayerHide1, false); 
	}
function handleLayerHide1(e) {
	var srcElem = e.target || e.srcElement;
	if(typeof(srcElem.id) !="undefined" && srcElem.id == 'showCustomerSupport') {
		showCustomerSupport();
	} else {
		while(srcElem) {
	        if(srcElem.id == 'customerSupportMain') {
	            return false;
	        }
	        srcElem = srcElem.parentNode;
	    }
		hideCustomerSupport();
	}
}	
</script>
<?php global $clientIP; 
if(strpos($clientIP,"shiksha")!==false) { ?>
<?php $this->load->view('common/ga'); ?>
<?php } ?>
<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams); ?>
<?php //echo TrackingCode::vizury(); ?>
<?php //echo TrackingCode::SCANAudienceBuildingPixel(); ?>
<?php //echo TrackingCode::FBConvertedAudiencePixel(); ?>
<?php //echo TrackingCode::GoogleConvertedAudiencePixel(); ?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("trackingCode"); ?>">
</script>
<script>
var department = '<?=$departmentType?>';
// Attach window onclick event handler for GA tracking
if(typeof(setCustomizedVariableForTheWidget) == "function") {
	if (window.addEventListener){
		window.addEventListener('click', setCustomizedVariableForTheWidget, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', setCustomizedVariableForTheWidget);
	}
}
/*window.onload = function () {
		var isValidResponseUser = '<?php //echo $isValidResponseUser; ?>';
		if(typeof(isValidResponseUser) == 'undefined'){
			var isValidResponseUser = 'yes';
		}
        if(isUserLoggedInSystem > 0 && isValidResponseUser == 'yes') {
            showExternalFormLayer(department,'<?php //echo $trackingPageKeyId;?>');
        }
        else {
            var element = getElementsByClassName('top-signin-col','');
            if(element[0]) {
                element[0].style.display = "none";
            }
        }
        
}*/
function getElementsByClassName(classname, node) {
    if(!node) node = document.getElementsByTagName("body")[0];
    var a = [];
    var re = new RegExp('\\b' + classname + '\\b');
    var els = node.getElementsByTagName("*");
    for(var i=0,j=els.length; i<j; i++)
    if(re.test(els[i].className))a.push(els[i]);
    return a;
}

function showRedirectLayer(customParam){
	showExternalFormOverlayWithPayTm(customParam.courseId ,true, customParam.department, customParam.trackingKeyId);
}

function applyNowCallBackOAF(response, customParam){
	if(response.status == 'SUCCESS'){
		setCookie(response.listingId+"oaf",response.userId,1,"days");
		registrationForm.closeRegistrationForm();
		showRedirectLayer(customParam);
	}
}
function showResponseFormOnOAF(){
	if(typeof showRegFormOnOAFPage != 'undefined' && showRegFormOnOAFPage == 1){ //update case
        var formData = {'trackingKeyId': '<?php echo $trackingPageKeyId;?>', 'callbackFunction': 'applyNowCallBackOAF','callbackFunctionParams': {'courseId':'<?php echo $courseId;?>', 'trackingKeyId':'<?php echo $trackingPageKeyId;?>', 'department':department}};
        responseForm.showResponseForm('<?php echo $courseId ;?>','Online_Application_Started','course',formData,{});
    }else if(typeof showRegFormOnOAFPage != 'undefined' && showRegFormOnOAFPage == 2){ //new user
        var formData = {'trackingKeyId': '<?php echo $trackingPageKeyId;?>', 'callbackFunction': 'applyNowCallBackOAF','callbackFunctionParams': {'courseId':'<?php echo $courseId;?>', 'trackingKeyId':'<?php echo $trackingPageKeyId;?>', 'department':department}};
        responseForm.showResponseForm('<?php echo $courseId ;?>', 'Online_Application_Started', 'course', formData, {});
    }else{
    	var param = {'courseId':'<?php echo $courseId;?>', 'trackingKeyId':'<?php echo $trackingPageKeyId;?>', 'department':department};
    	showRedirectLayer(param);
    }

    $j(document).on('click','.regClose',function(){
    	$j('.searchBtnOvrly').hide();
    });
}
$j('document').ready(function(){
	showResponseFormOnOAF();
});
</script>
