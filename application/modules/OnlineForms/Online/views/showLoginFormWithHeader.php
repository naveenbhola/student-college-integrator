<?php 
$seo_title = "";
$seo_desc = "";
$seo_keyword = "";
if(array_key_exists('seo_title', $config_array[$courseId])) {
	$seo_title = $config_array[$courseId]['seo_title'];	
}
if(array_key_exists('seo_description', $config_array[$courseId])) {
	$seo_desc = $config_array[$courseId]['seo_description'];	
}
if(array_key_exists('seo_keywords', $config_array[$courseId])) {
	$seo_keyword = $config_array[$courseId]['seo_keywords'];	
}
?>
<?php
				$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
				$criteriaArray = array(
                                    'category' => $categoryIdForBanner,
                                     'country' => '',
                                     'city' => '',
                                     'keyword'=>'');
				   $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);
				   $headerComponents = array(
				      'css'	=>	array('online-styles','header','raised_all','mainStyle'),
				      'js' 	=>	array('common','ana_common','myShiksha','onlinetooltip','CalendarPopup','imageUpload','user','onlineForms','processForm','ajax-api'),
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
<?php if($userId != '' && $isValidResponseUser == 'no') { ?>
	var isIncompleteUserOnOF = 'yes';
<?php } ?>
</script>
<div id="appsFormWrapper">
    
	   <?php 
	  if($courseId>0):
	  	$this->load->view('Online/showCusTomLoginForm');
	  else:
	  	$this->load->view('Online/showLoginForm');
	  endif;?>
    
</div>
  <?php 
	  if($courseId>0):
?>	  
<div id="footerNavBox">
	    <div><a href="javascript:void(0);" onclick="showHelpLayer();"title="Help">Help</a> &nbsp;|&nbsp; <a href="javascript:void(0);" onclick="showFaqLayer();" title="Faqs">Faqs</a> &nbsp;|&nbsp; <a href="javascript:void(0);"  id="handleLayerHide" title="How it works?">How it works?</a> &nbsp;<?php if($courseId>0){ ?>|&nbsp; <a target="_blank" href="/Online/OnlineForms/displayForm/<?php echo $courseId;?>/1" title="Sample application form">Sample application form</a> &nbsp;<?php } ?>|&nbsp; <a href="javascript:void(0);" onclick="showCustomerSupport();" id="showCustomerSupport" title="Customer Support">Customer Support</a>
	    
	    <div class="customerSupportMain" id="customerSupportMain" style="display: none;">
	    <div class="customerSupport">
		<div class="figure"></div>
		<div class="details">
		    <p>
			    For online form Assistance<br />
				<span>Call : 011-4046-9621</span>
                (between 09:30 AM to 06:30 PM, Monday to Friday)
		    </p>
		</div>
	    </div>
	    </div>
	    
	    
	    <!--How it Works Layer Starts here-->
                    <div id="howitWorksLayerDiv" class="howitWorksLayerWrap2" style="display:none">
                        <div class="howitWorksLayerContent" id="howitWorksLayerContent">
                        	<div>
                                <div class="selectCollege selectCollegeAlign"></div>
                                <div class="horArrow1"></div>
                                <div class="submitForm submitFormAligner"></div>
                                <div class="horArrow2"></div>
                                <div class="receiveForm receiveFormAligner"></div>
                                <div class="horArrow1"></div>
                                <div class="getUpdates getUpdatesAligner"></div>
                                <div class="horArrow2"></div>
                                <div class="onlineResult"></div>
                            </div>    
                                <ul class="howWorksLayerDetail">
                                    <li class="firstItem">
                                        <strong>Select Colleges </strong>
                                        <p>Compare and shortlist colleges that you wish to apply</p>
                                        
                                    </li>
                                    
                                    <li class="secItem">
                                        <strong>Submit form</strong>
                                        <p>Fill the application form once and use for multiple college applications. Also, attach documents and make online payment</p>
                                        
                                    </li>
                                    
                                    <li class="thirdItem">
                                        <strong>Institute receives form</strong>
                                        <p>Institute receives and reviews your form. You get instant update as soon as institute reviews the form</p>
                                        
                                    </li>
                                    
                                    <li class="fourthItem">
                                        <strong>Get <?=$onlineFormsDepartments[$department]['gdPiName']?> Updates</strong>
                                        <p>Institute sends the <?=$onlineFormsDepartments[$department]['gdPiName']?> updates. You also track your application status at all the stages of admission process</p>
                                        
                                    </li>
                                    
                                    <li class="fifthItem">
                                        <strong>Know your result online</strong>
                                        <p>Get updated about the final decision of the institute towards your admission application</p>
                                    </li>
                                </ul>
				<div class="clearFix"></div>
				<div class="studentNotice">Shiksha.com facilitates application form submission and tracking throughout online process. It does not, however, guarantees admissions. The final decision lies with the <br />institute itself.</div>
				
			
                                
                        </div>
                        <span id="howitWorksPointer" <?php if($courseId>0) {echo "class='howitWorksPointer3'";} else {echo "class='howitWorksPointer2'";}?>></span>
                    </div>
                    <!--How it Works Layer Ends here-->
	    </div>
	    <br />
	    <?php if(isset($instituteInfo[0]['instituteInfo'][0]['institute_name']) && $instituteInfo[0]['instituteInfo'][0]['institute_name']!=''){ ?>
		    <div class="footerApproval">
		    <p>This application process is <strong><a href="javascript:void(0);" onmouseover="if(typeof(OnlineForm!='undefined')) {hideCustomerSupport(); OnlineForm.displayAdditionalInfoForInstitute('block','authorisedLayerWrap')}" onmouseout="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('none','authorisedLayerWrap')}">approved by <?php echo $instituteInfo[0]['instituteInfo'][0]['institute_name'];?></a></strong></p>
		    <!--Authorised Layer Starts here-->
		    <div class="authorisedLayerWrap2" style="display:none;" id="authorisedLayerWrap">
			<div class="authorisedContent">
			    <div class="universityAvatar"><img src="<?php echo $config_array[$courseId]['auth_image'];?>" /></div>
			    <div class="universityDescription">
				<em><?php echo $config_array[$courseId]['auth_text'];?></em>
				
			    <div class="signRow">
				<p class="signBlock"><a href="#"><?php echo $config_array[$courseId]['auth_sign_name'];?></a><br />
			      <span><?php echo $config_array[$courseId]['auth_sign_post'];?></span></p>
			      
			      <p style="float:right;"><img src="<?php echo $config_array[$courseId]['auth_sign_image']?>" alt="<?php echo $config_array[$courseId]['auth_sign_name'];?>" /></p>
			    </div>
				
			    </div>
			    <div class="clearFix"></div>
			</div>
			<span class="authorisedPointer2"></span>
		    </div>
		    <!--Authorised Layer Ends here-->
		    </div>

	    <?php } ?>
	</div>
	<?php endif;?>
<div class="clearFix"></div>	
<?php
	//$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	//$this->load->view('Online/footerOnline',$bannerProperties1);
	$this->load->view('common/footer');
?> 
<script>
function applyNowCallBack(response, customParam){
	setCookie(response.listingId+"oaf",response.userId,1,"days");
	window.location.reload();
}
	//window.regFormLoadonlineForm();
  if($('userLoginOverlay_online')){
      $('userLoginOverlay_online').style.display = '';
      //$('loginLayer').align = 'center';
      //$('userLoginOverlay_online').style.width = '800px';
  }
</script>
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
		window.addEventListener('click', handleLayerHide, false); 
                window.addEventListener('click', handleLayerHide1, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', handleLayerHide);
                document.attachEvent('click', handleLayerHide1, false); 
	}
function handleLayerHide(e) {
	var srcElem = e.target || e.srcElement;
	if(typeof(srcElem.id) !="undefined" && srcElem.id == 'handleLayerHide') {
		showHowItWorksLayer();
	} else {
		while(srcElem) {
	        if(srcElem.id == 'howitWorksLayerContent' || srcElem.id == "howitWorksPointer") {
	            return false;
	        }
	        srcElem = srcElem.parentNode;
	    }
		hideHowItWorksLayer();
	}
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
// Attach window onclick event handler for GA tracking
if(typeof(setCustomizedVariableForTheWidget) == "function") {
	if (window.addEventListener){
		window.addEventListener('click', setCustomizedVariableForTheWidget, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', setCustomizedVariableForTheWidget);
	}
}
window.onload = function () {
	var element = getElementsByClassName('top-signin-col','');
        if(element[0]) {
             //alert(1);
			element[0].style.display = "none";
             	//$('top-bar').style.paddingTop = '4px';
				//$('top-bar').style.paddingBottom = '4px';
             	//$('top-bar').style.backgroundPosition= '0px -56px';
        }
}
function getElementsByClassName(classname, node) {
    if(!node) node = document.getElementsByTagName("body")[0];
    var a = [];
    var re = new RegExp('\\b' + classname + '\\b');
    var els = node.getElementsByTagName("*");
    for(var i=0,j=els.length; i<j; i++)
    if(re.test(els[i].className))a.push(els[i]);
    return a;
}
function pushConversionCodeForOnlineForm() {
/* Google conversion code start */
	var ifm = document.createElement("iframe");
        ifm.setAttribute("src", "/public/conversion/conversionforOnlineForm.html");
        ifm.setAttribute("height",0);
        ifm.setAttribute("width",0);
        ifm.setAttribute("border",0);
        document.body.appendChild(ifm);
/* Google conversion code end */
}
$j('document').ready(function(){
	if($j('#autostart-my-application').length > 0){
		$j('#autostart-my-application').click();
	}
});
</script>
