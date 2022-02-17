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
				      'js' 	=>	array('common','ana_common','myShiksha','onlinetooltip','prototype','CalendarPopup','imageUpload','user','onlineForms','cityList'),
				      'title'	=>	'',
				      'tabName' =>	'Discussion',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=>'',	
				      'metaKeywords'	=>'',
				      'product'	=>'online',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'callShiksha'=>1,
				      'notShowSearch' => true,
                                      'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
                                      'showBottomMargin' => false,
				      'showApplicationFormHeader' => true
				   );

   $this->load->view('common/header', $headerComponents);
   $this->load->view('common/calendardiv');
   global $onlineFormsDepartments;
   $department = $this->courselevelmanager->getCurrentDepartment();
   $inst_id = $instituteInfo[0]['instituteInfo'][0]['institute_id'];
?>
<script>
var isUserLoggedInSystem = '<?php echo $userId;?>';
</script>
<div id="appsFormWrapper">

    <!--Starts: breadcrumb-->
    <?php $this->load->view('Online/showBreadCrumbs'); ?>
    <!--Ends: breadcrumb-->
    
	<?php if($courseId){ ?>
    <div id="appsFormInnerWrapper">

    <div id="appsFormHeader">
	<!--Starts: Institute Header -->
	<?php $this->load->view('Online/instituteHeader'); ?>
	<!--Ends: Institute Header-->

	<?php if(isset($instituteInfo[0]['instituteInfo'][0]['institute_name']) && $instituteInfo[0]['instituteInfo'][0]['institute_name']!=''){ ?>
	<div class="formSubject">
        <?php
        $str = $instituteInfo[0]['instituteInfo'][0]['sessionYear'];
        if($courseId=='12873'){
             $str = '2015-2017';
        }
        ?>
      <h4>&nbsp;You are applying for <span><?php echo $instituteInfo[0]['instituteInfo'][0]['institute_name'];?> - <?php if($instituteInfo[0]['instituteInfo'][0]['institute_id']==52030 || $instituteInfo[0]['instituteInfo'][0]['institute_id']==48024){echo "B.Tech";}elseif($instituteInfo[0]['instituteInfo'][0]['institute_id']==48026){echo "MBA";}else{ echo $instituteInfo[0]['instituteInfo'][0]['courseTitle'];}?> <?=$str; ?><?php //echo date("Y");?></span></h4>
	      <a title="View Sample Form" target="_blank" href="/Online/OnlineForms/displayForm/<?php echo $courseId;?>/1" class="viewSampleForm">View Sample Form</a>
	</div>
	<?php } ?>
    </div>
	<?php } else { ?>
	<div id="appsFormInnerWrapper" style="padding-top : 0">
	<?php } ?>

    <!--Starts: Steps Menu -->
    <?php $this->load->view('Online/showStepsMenu'); ?>
    <!--Ends: Steps Menu-->

    <div class="formContentWrapper">

	<!-- Div start for Student Note. This will be static throughout the form -->
    	<div class="studentsNote">
        	<span class="noteIcon"></span>
		<p>Fill up this form very carefully. Keep your mouse pointer on any textbox to read instructions about how to fill it up. All Fields are mandatory. If you are unsure about any field, please check the Help and <a href="javascript:void(0);" onclick="showFaqLayer();" title="Faqs">FAQ</a> sections or mail your query to <a href='mailto:help@shiksha.com'>help@shiksha.com</a>. If any field is not applicable in your case, please enter NA. <br />
		
		
		
		<strong><?php if(isset($instituteInfo[0]['instituteInfo'][0]['basicInformation'])){ echo $instituteInfo[0]['instituteInfo'][0]['basicInformation'];} ?></strong></p>
		<div class="clearFix"></div>
        </div>
        <div class="clearFix"></div>
	<!-- Div End for Student Note. This will be static throughout the form -->

	<?php
	if($showPaymentPage=='true'){
	    $this->load->view("Online/showPaymentPage"); 
	}
	else{ ?>
	<!-- Form starts -->
        <form action="/Online/OnlineForms/storeApplicationForm/<?php echo $action;?>" id="OnlineForm" accept-charset="utf-8" method="post" enctype="multipart/form-data" onsubmit="disableSubmitButtons(); removeHelpText(this); if(validateFields(this) != true){ validateOnline(this); enableSubmitButtons(); return false;} if( validateOnline(this) != true){enableSubmitButtons();return false;}  storeUserFunc(this); return false;" novalidate="novalidate">

		<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
		<input type="hidden" name="onlineFormId" id="onlineFormId" value="<?php echo $onlineFormId;?>"/>
		<input type="hidden" name="edit" value="<?php echo $edit;?>"/>
		<input type="hidden" name="pageId" value="<?php echo $pageId;?>"/>
		<input type="hidden" name="formId" value="<?php echo $formId;?>"/>
		<input type="hidden" name="saveExit" id="saveExit" value="0"/>
		<input type="hidden" name="courseId" value="<?php echo $courseId;?>"/>
		<?php $this->security->csrf_verify(); ?>
		<input type="hidden" name="<?php echo $this->security->csrf_token_name; ?>" value="<?php echo $this->security->csrf_hash; ?>" />
		
		<!-- Load the template file -->
		<?php 
		if($templatePath!=''){
			$this->load->view("$templatePath"); 
		}
		?>
		
		<?php if($pageType == 'custom' && $action != 'updateScore'){ ?>
                <?php $this->load->view('Online/update_photo_custom_form');?>
		<div class="clearFix"></div>
		<!-- <h3 style="border:none"><a href='javascript:;' onclick="showUploadOverlay('<?=$onlineFormId;?>')">Attach documents (Optional) &raquo;</a><br />
		<span style="font-size:12px; font-weight:normal; color:gray;">
		<strong style="color:red;">Documents Required </strong>: <?php echo $instituteInfo[0]['instituteInfo'][0]['documentsRequired']; ?></span></h3> -->
		
		<!-- <div class="attachMoreDocBlock" id="MySavedDocuments">
		<?php echo $this->load->view('Online/myDocuments');	?>
		</div> -->
		
		<br />
		<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlineFormStudentDashboard"); ?>"></script>
		<?php } ?>
		
		<div class="buttonWrapper">
			<?php if(intval($pageOrder)>1 && $action != 'editProfile' && $action != 'updateScore'){ ?><a title = "Click here to access the previous page." href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/<?php echo intval($pageOrder)-1;?>" class="cancelLink"><span>&laquo;</span> Back</a><?php } ?>
			<div class="buttonsAligner">
				<?php if($action == 'editProfile' || $action == 'updateScore'): ?>
				<input type="submit" title="<?php if($action == 'updateScore') echo "Update Score."; else {"Update profile.";}?>" value="Update" class="saveContButton" />
				<?php else: 
                    $trackEvent = 'SAVE_AND_CONTINUE_PAGE_'.$pageId;
                    $trackEvent1 = 'SAVE_AND_EXIT_PAGE_'.$pageId;
                    if($pageId>3){
                        $trackEvent = 'SAVE_AND_CONTINUE_PAGE_4';
                        $trackEvent1 = 'SAVE_AND_EXIT_PAGE_4';
                    }
                ?>
			    <input id="saveOnlineProceed" type="submit" title="Save information on this page and proceed to next step." value="Save &amp; Continue" class="saveContButton" onClick="trackEventByGA('ONLINE_FORM_BUTTON','<?=$trackEvent?>/<?=$department?>');"/>
			    <input  id="saveOnlineQuit" type="submit" title="Save information on this page and quit online form submission at the moment. You can login again and fill up the remaining information to submit the form." value="Save &amp; Exit" class="saveExitButton" onclick="trackEventByGA('ONLINE_FORM_BUTTON','<?=$trackEvent1?>/<?=$department?>'); saveAndExit();" />
				<?php endif; ?>
			</div>
		</div>

	</form>
	<!-- Form ends -->
	<?php } ?>

	</div>
	<div class="clearFix"></div>

	<div id="footerNavBox">
	    <div><a href="javascript:void(0);" onclick="showHelpLayer();"title="Help">Help</a> &nbsp;|&nbsp; <a href="javascript:void(0);" onclick="showFaqLayer();" title="Faqs">Faqs</a> &nbsp;|&nbsp; <a href="javascript:void(0);"  id="handleLayerHide" title="How it works?">How it works?</a> &nbsp;<?php if($courseId>0){ ?>|&nbsp; <a target="_blank" href="/Online/OnlineForms/displayForm/<?php echo $courseId;?>/1" title="Sample application form">Sample application form</a> &nbsp;<?php } ?>|&nbsp; <a href="javascript:void(0);" onclick="showCustomerSupport();" id="showCustomerSupport" title="Customer Support">Customer Support</a>
	    
	    <div class="customerSupportMain" id="customerSupportMain" style="display: none;">
	    <div class="customerSupport" style="padding-top:8px;">
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
	<div class="clearFix"></div>

   </div>


</div>
<div class="clearFix"></div>
<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('Online/footerOnline',$bannerProperties1);
?> 

<script>
try{
    //var cal = new CalendarPopup("calendardiv");
    addOnFocusToopTipOnline(document.getElementById('OnlineForm'));
    addOnBlurValidate(document.getElementById('OnlineForm'));


} catch (ex) {
    // throw ex;
}    
</script>
<?php
if($action == 'editProfile') {
	$nextPageURL = '/studentFormsDashBoard/StudentDashBoard/myProfile';				
}
else {
	$lastPage = end($pageArray[0]['data']);
	$maxPageOrder = (int) $lastPage['pageOrder'];
	$nextPageURL = '/Online/OnlineForms/showOnlineForms/'.$courseId;
	if(intval($pageOrder) < $maxPageOrder) {
		$nextPageURL .= '/1/'.($pageOrder+1);
	}
	if($action) {
		$nextPageURL .= '/'.$action;
	}
}

$saveAndExitURL = '/studentFormsDashBoard/StudentDashBoard/index';
if($action == 'editForm') {
	$saveAndExitURL = '/studentFormsDashBoard/MyForms/index';
}
$referrerfor_updatescore  = $this->input->server('HTTP_REFERER');
if(empty($referrerfor_updatescore)) {
	$referrerfor_updatescore = '/studentFormsDashBoard/StudentDashBoard/index';
}
?>
<script>
        var referrerfor_updatescore = '<?php echo $referrerfor_updatescore;?>';
	function storeUserFunc(formObj) {
		if ($j('.clearFields :input')){
 			$j('.clearFields :input').val('');
 		}
 		if($j('.clearFields') && $j('.clearFields :input').length==0)
 		{
 			$j('.clearFields').val('');
 		}

 		
	    AIM.submit(formObj, {'onStart' : startCallback, 'onComplete' : showUploadResponse});
	    formObj.submit();
	}
	
	function disableSubmitButtons(){
		if($('saveOnlineProceed'))
			$('saveOnlineProceed').disabled = true;
		if($('saveOnlineQuit'))
			$('saveOnlineQuit').disabled = true;
	}
	
	function enableSubmitButtons(){
		if($('saveOnlineProceed'))
			$('saveOnlineProceed').disabled = false;
		if($('saveOnlineQuit'))
			$('saveOnlineQuit').disabled = false;
	}


	function showUploadResponse(response)
	{
	   //alert(response);
	    if(response!='1'){
			//Something has gone wrong
			enableSubmitButtons();
			var k = 1;
			response = eval('(' + response + ')');
			var errors = response.errors;
			for(var i=0;i<errors.length;i++) {
				if(errors[i][0] == 'invalidToken') {
					alert(errors[i][1]);
				}
				else {
					$(errors[i][0]+'_error').innerHTML = errors[i][1];
					$(errors[i][0]+'_error').parentNode.style.display="";
					if(k == 1){
						$(errors[i][0]).focus();
						k = 2;
					}
				}
			}
	    }
	    else if(getCookie('redirectViewForm')=='true'){	//All WELL
		  setCookie('redirectViewForm','false');
		  window.location.href = '/Online/OnlineForms/displayForm/<?php echo $courseId;?>';
	    }
	    else if(response=='1'){	//All WELL
			
                  if('<?php echo $action?>' == 'updateScore') {
			  showScoreUpdateLayer('<?php echo $onlineFormId;?>', '<?php echo $inst_id;?>', '<?php echo $courseId;?>', '<?php echo $userId;?>');
			  return;
		  }		
		  if(document.getElementById('saveExit').value == '1') {
				window.location.href = '<?php echo $saveAndExitURL; ?>';
		  }
		  else {
				window.location.href = '<?php echo $nextPageURL;?>';
		  }
	    }
	}
	function saveAndExit()
    {
		document.getElementById('saveExit').value = '1';
		return true;
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
