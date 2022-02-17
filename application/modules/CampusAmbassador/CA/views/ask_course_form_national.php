<?php
      $pageKeyForAskQuestion = 'LISTING_INSTITUTEDETAIL_ASK_INSTITUTE_POSTQUESTION';
      $categoryId = 0;
      if(count($categories) > 0 && is_array($categories) ){
	    $categoryId = $categories[0];
      }
	
      $FirstNameOfUser = is_array($validateuser[0])?$validateuser[0]['firstname']:"";
      $LastNameOfUser = is_array($validateuser[0])?$validateuser[0]['lastname']:"";
      $cookiStr =  is_array($validateuser[0])?$validateuser[0]['cookiestr']:"";
      $cookiStrArr = explode("|",$cookiStr);
      $emailId =	isset($cookiStrArr[0])?$cookiStrArr[0]:"";
      $contactNumber = is_array($validateuser[0])?$validateuser[0]['mobile']:"";

      $callBackFunction = 'try{ addRecentlyPostedQuestion(request.responseText); } catch (e) {}';
      $questionText = '';
      if(isset($_COOKIE['commentContent']) && ($questionText == '')){
	      $commentContentData = $_COOKIE['commentContent'];
	      if((stripos($commentContentData,'@$#@#$$') !== false) && (stripos($commentContentData,'@#@!@%@') === false)){
		      $questionText = str_replace("@$#@#$$","",$commentContentData);
	      }
      }
	  
	  $questionText = (isset($questionText)&&($questionText!=''))?$questionText:'Post your questions here and get answers from current students of this college.';
	  $questionTextLength = strlen($questionText);
	  $base64url = base64_encode(site_url("'".$_SERVER['REQUEST_URI']."'"));
	  $widget = 'askAQuestion';
	  $formCustomData['widget'] = $widget;
    $formCustomData['trackingPageKeyId'] = $trackingPageKeyId;
	  $formCustomData['buttonText'] = '';
	  $formCustomData['customCallBack'] = '';
?>
<script>
var askquestionwidget = '<?php echo $widget;?>';
function showMultipleApplyOverlay(overlayFlag){
        if(overlayFlag == 1){
                askInstitute.successMessage='showLogin';
                displayMessage('/MultipleApply/MultipleApply/showoverlay/1',500,260);
        }else if(overlayFlag == 4){
                askInstitute.successMessage='showRegister';
                displayMessage('/MultipleApply/MultipleApply/showoverlay/4',665,380);
        }
        return false;
}
function populatUserData(){
        email_id = $('emailOfUserForAskInstitute').value;
        phone_no = $('mobileOfUserForAskInstitute').value;
        display_name = $('nameOfUserForAskInstitute').value;
        return false;
}

function addRecentlyPostedQuestion(responseText){
        var responseObj = eval("eval("+responseText+")");
        var url = '/listing/Listing/addQuestionId';
        var questionArray = new Array();
        questionArray = <?php echo json_encode( $questionIds)?>;
        var instituteId = <?php echo $instituteId?>;
        var data = 'threadId='+responseObj.questionResult+'&questionArray='+questionArray+'&instituteId='+instituteId;
        new Ajax.Request (url,{method:'post', parameters: data
			});
        askInstitute.askInstituteSuccess(responseText);
        
}
</script>
<?php

?>

<!--Start_Ask_Question-->

<div class="other-details-wrap clear-width">
	<div class="ask-form" id="askQuestionFormDiv">
	<?php
		$url = site_url("messageBoard/MsgBoard/askQuestionFromListing");
	?>
	<h5><i class="sprite-bg que-icon"></i>Post a question to current students of this college</h5>
	
	  <?php echo Modules::run('registration/Forms/LDB',NULL,'askQuestionBottom',$formCustomData); ?>				

	<!-- <form id="form_<?=$widget?>" onsubmit="processFloatingForm('<?=$widget?>'); return false;" autocomplete="off"  novalidate>

    <ul class="ask-que-list" id="questionTextDiv">
    	<li>
        	<textarea class="universal-select ask-area" autocomplete="off" value="<?php echo $questionText; ?>" onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }"  profanity="true" caption="Question" maxlength="140" minlength="2" required="true" style="height:35px;<?php if ($questionText == '') { echo 'color:#565656;'; } ?>" default="<?php echo $askInstituteHelpTip; ?>" onfocus="trackEventByGA('LinkClick','LISTING_QNATAB_EXPAND_ASK_QUESTION'); focusBlurForWallQuestion('focus',this);" id="ask_question_<?=$widget?>" name="ask_question_<?=$widget?>" validate="validateStr" ><?php if ($questionText != '') { echo $questionText;} else { echo $askInstituteHelpTip; } ?></textarea>
            <p class="char-limit" ><span id="ask_question_<?=$widget?>_counter"> 0 </span> out of 140 character</p>
			<div class="errorPlace" style="display:none"><div class="errorMsg" id="ask_question_<?=$widget?>_error"></div></div>            
        </li>
    </ul>
    
    <ul class="ask-que-list" style="display:none;" id="hiddenFormPartAskInstitute">
    	<li>
    	    <div class="flLt ask-col-1">
            	<label>First Name:<span>*</span></label>
	             <input class="universal-txt-field" type="text" id="usr_first_name_<?=$widget?>" name="usr_first_name_<?=$widget?>" validate = "validateDisplayName" required = "true" maxlength = "50" minlength = "1" caption = "First name" value="<?php echo htmlentities($FirstNameOfUser); ?>" />
	                <div class="errorPlace" style="display:none">
	                    <div class="errorMsg" id="usr_first_name_<?=$widget?>_error"></div>
	                </div>	             
            </div>
            <div class="flLt ask-col-2">
            	<label>Last Name:<span>*</span></label>
            	<input class="universal-txt-field" type="text" id="usr_last_name_<?=$widget?>" name="usr_last_name_<?=$widget?>" validate = "validateDisplayName" required = "true" maxlength = "50" minlength = "1" caption = "Last name" value="<?php echo htmlentities($LastNameOfUser); ?>" />
	                <div class="errorPlace" style="display:none">
	                    <div class="errorMsg" id="usr_last_name_<?=$widget?>_error"></div>
	                </div>            	
            </div>
    	</li>
    	
    	<li>
    	<div class="flLt ask-col-1">
	    	<label>Email Id:<span>*</span></label>
	        <input type="text" id="contact_email_<?=$widget?>"  name="contact_email_<?=$widget?>"  validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" class="universal-txt-field" value="<?php echo $emailId; ?>" <?php if($emailId != ""){ echo 'disabled="true"';} ?> />
	        <div class="errorPlace" style="display:none" >
	        	<div class="errorMsg" id="contact_email_<?=$widget?>_error"></div>
	        </div>
	    </div>

	    <div class="flLt ask-col-2">
	    	<label>Contact Number:<span>*</span></label>
	        <input type="text" id="mobile_phone_<?=$widget?>"  name="mobile_phone_<?=$widget?>" minlength = "10" maxlength = "10" validate = "validateMobileInteger" required = "true" caption = "mobile number" class="universal-txt-field" value="<?php echo $contactNumber; ?>" />
	        <div class="errorPlace" style="display:none" >
	        	<div class="errorMsg" id="mobile_phone_<?=$widget?>_error"></div>
	        </div>
	    </div>
	    
    	</li>
    	
    	<?php if(($validateuser == "false")){?>
        <li>
	        <p>Type in the character you see in the picture below</p>
	        <div class="sec-code-box">
	        	<img src="/CaptchaControl/showCaptcha?width=100&height=30&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>"  onabort="reloadCaptcha(this.id)"  id = "secureCode_<?=$widget?>" align="absbottom"/>&nbsp;
	        	<input type="text"  style="width:100px;" class="universal-txt-field" name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code" style="width:100px"/>
	        </div>
			<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="homesecurityCode_<?=$widget?>_error"></div></div>	        
        </li>
        <?php }?>    	
        
        <li>
        	<a href="#" onClick="trackEventByGA('ASK_NOW_BUTTON','LISTING_DETAIL_PAGE_QNA_TAB_TOP'); processFloatingForm('<?=$widget?>'); return false;" name="submit_<?=$widget?>" id="submit_<?=$widget?>" class="orange-btn2" style="display:inline-block; padding:8px 22px">Ask Now</a>
        	&nbsp;&nbsp;&nbsp;<span id="cacelLinkContainer_ForAskInstitute" style="display:none;"><a href="javascript:void(0);" onClick="javascript:askInstitute.callOnFocusOnBlurFunctions('blur');" class="fontSize_12p">Cancel</a></span>
        		
        		
        		<input type="hidden" name ="instituteId" id="instituteIdForAskInstitute" value="<?php echo $instituteId; ?>" />
	            <input type="hidden" name ="categoryId" id="categoryIdForAskInstitute" value="<?php echo $categoryId; ?>" />
	            <input type="hidden" name ="locationId" id="locationIdForAskInstitute" value="<?php echo $locationId; ?>" />
	            <input type="hidden" name="secCodeIndex" value="seccodeForAskInstitute" />
	            <input type="hidden" name="loginproductname_ForAskInstitute" value="<?php echo $pageKeyForAskQuestion; ?>" />
	            <input type="hidden" name="referer_ForAskInstitute" id="referer_ForAskInstitute" value="" />
	            <input type="hidden" name="resolution_ForAskInstitute" id="resolution_ForAskInstitute" value="" />
	            <input type="hidden" name="coordinates_ForAskInstitute" id="coordinates_ForAskInstitute" value="" />
				<input type="hidden" name="askUrl_floatingRegistration" id="askUrl_floatingRegistration" value="<?php echo $coursUrl?>" />
	            
		   	    <input type="hidden" id="instituteId_<?=$widget?>" name="instituteId_<?=$widget?>" value="<?=$instituteId?>">
			    <input type="hidden" id="locationId_<?=$widget?>" name="locationId_<?=$widget?>" value="<?=$locationId?>">
           		<input type="hidden" id="courseId_<?=$widget?>"  name="courseId"  value="<?=$course->getId()?>"/>	    
           		<input type="hidden" id="categoryId_<?=$widget?>"  name="categoryId"  value="<?=$categoryId?>"/>
           		<input type="hidden" name="getmeCurrentCity" id="getmeCurrentCity" value="<?php echo $currentLocation->getCity()->getId();?>"/>
				<input type="hidden" name="getmeCurrentLocaLity" id="getmeCurrentLocaLity" value="<?php echo $currentLocation->getLocality()->getId();?>"/>
           		
        </li>     
           
    </ul>
      	
       	 </form> -->
			<script>var showAsk='true';</script>
       	 <div class="clearFix"></div>
    </div>
</div>
<!--End_Ask_Question-->

		
