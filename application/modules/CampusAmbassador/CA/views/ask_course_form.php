<style>
.disabled-button{background:#9f9f9f; color:#cccccc !important; cursor:default;font-size: 14px !important; padding: 4px 8px;font-weight:bold;} 
</style>

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
?>
<script>
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

function focusBlurForWallQuestion(func,obj){
    var id = obj.id;
    if(func == 'focus'){
        var element = document.getElementById(id).value;
        if(element =='Post your questions here and get answers from Shiksha Current Students.'){
            document.getElementById(id).value = "";
        }
        document.getElementById('hiddenFormPartAskInstitute').style.display = 'inline';
    }else{
        document.getElementById('hiddenFormPartAskInstitute').style.display = 'none';
    }
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
		$questionText = (isset($questionText)&&($questionText!=''))?$questionText:'Post your questions here and get answers from Shiksha Current Students.';
		$questionTextLength = strlen($questionText);
		$base64url = base64_encode(site_url("'".$_SERVER['REQUEST_URI']."'"));
		$widget = 'askAQuestion';
?>

<!--Start_Ask_Question-->
<div class="shadow-box ask-ques-box" id="askQuestionFormDiv">
<!--<form id="askQuestionForm" name="askQuestionForm" method="get" action="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/questionPostLandingPage" onsubmit="try{return validateQuestionForm_old(this,'<?php echo $postQuestionKey; ?>','formsubmit','askQuestionForm');}catch (e){ return false;}" style="margin:0;padding:0">-->
	<?php
		$url = site_url("messageBoard/MsgBoard/askQuestionFromListing");
		//echo $this->ajax->form_remote_tag( array('url'=>$url,'before' => 'try{ if(askInstitute.validateAskInstitute(this) != true){return false;} else { disableElement(\'askInstituteButton\'); } }catch (e) { return false; }','success' => $callBackFunction));
		
	?>
	<form id="form_<?=$widget?>" onsubmit="processFloatingForm('<?=$widget?>'); return false;" autocomplete="off"  novalidate>

    <h2><span class="sprite-bg ask-icn"></span>Ask a Question</h2>
    <div id="questionTextDiv">
    	<textarea class="universal-select" autocomplete="off" value="<?php echo $questionText; ?>" onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }"  profanity="true" caption="Question" maxlength="140" minlength="2" required="true" style="height:35px;<?php if ($questionText == '') { echo 'color:#565656;'; } ?>" default="<?php echo $askInstituteHelpTip; ?>" onfocus="trackEventByGA('LinkClick','LISTING_QNATAB_EXPAND_ASK_QUESTION'); focusBlurForWallQuestion('focus',this);" id="ask_question_<?=$widget?>" name="ask_question_<?=$widget?>" validate="validateStr" ><?php if ($questionText != '') { echo $questionText;} else { echo $askInstituteHelpTip; } ?></textarea>
    </div>
<!--Start_Hide/Show_Form--->

<div style="display:none;" id="hiddenFormPartAskInstitute">
	<div class="font_size_11 graycolor"><span id="ask_question_<?=$widget?>_counter">0</span> out of 140 character</div>
    	<div class="errorPlace" style="display:none"><div class="errorMsg" id="ask_question_<?=$widget?>_error"></div></div>
			
            <div class="ask-user-info">
                <label>First Name:<span>*</span></label>
                <input class="universal-txt-field" type="text" id="usr_first_name_<?=$widget?>" name="usr_first_name_<?=$widget?>" validate = "validateDisplayName" required = "true" maxlength = "50" minlength = "1" caption = "First name" value="<?php echo htmlentities($FirstNameOfUser); ?>" />
                <div class="errorPlace" style="display:none">
                    <div class="errorMsg" id="usr_first_name_<?=$widget?>_error"></div>
                </div>
            </div>
            
            <div class="ask-user-info">
                <label>Last Name:<span>*</span></label>
                <input class="universal-txt-field" type="text" id="usr_last_name_<?=$widget?>" name="usr_last_name_<?=$widget?>" validate = "validateDisplayName" required = "true" maxlength = "50" minlength = "1" caption = "Last name" value="<?php echo htmlentities($LastNameOfUser); ?>" />
                <div class="errorPlace" style="display:none">
                    <div class="errorMsg" id="usr_last_name_<?=$widget?>_error"></div>
                </div>
            </div>
            <div class="clearFix"></div>
            <div class="ask-user-info">
                <label>Email Id:<span>*</span></label>
                <input type="text" id="contact_email_<?=$widget?>"  name="contact_email_<?=$widget?>"  validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" class="universal-txt-field" value="<?php echo $emailId; ?>" <?php if($emailId != ""){ echo 'disabled="true"';} ?> />
                <div class="errorPlace" style="display:none" >
                    <div class="errorMsg" id="contact_email_<?=$widget?>_error"></div>
                </div>
            </div>
                        
            <div class="ask-user-info">
                <label>Contact Number:<span>*</span></label>
                    <input type="text" id="mobile_phone_<?=$widget?>"  name="mobile_phone_<?=$widget?>" minlength = "10" maxlength = "10" validate = "validateMobileInteger" required = "true" caption = "mobile number" class="universal-txt-field" value="<?php echo $contactNumber; ?>" />
                <div class="errorPlace" style="display:none">
                    <div class="errorMsg" id="mobile_phone_<?=$widget?>_error"></div>
                </div>
            </div>
            
	                     
            <div class="spacer10 clearFix"></div>
            <?php if(($validateuser == "false")){?>
            <div>Type in the character you see in picture below</div>
            <div class="spacer5 clearFix"></div>
			<div>
                                <img src="/CaptchaControl/showCaptcha?width=100&height=30&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>"  onabort="reloadCaptcha(this.id)"  id = "secureCode_<?=$widget?>" align="absbottom"/>&nbsp;
                                                <input type="text"  style="width:100px;" class="universal-txt-field" name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/></div>
                                                <div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="homesecurityCode_<?=$widget?>_error"></div></div>

            <div class="spacer10 clearFix"></div>
            <?php }?>
            <div><a onClick="trackEventByGA('ASK_NOW_BUTTON','LISTING_DETAIL_PAGE_QNA_TAB_TOP'); processFloatingForm('<?=$widget?>'); return false;" name="submit_<?=$widget?>" id="submit_<?=$widget?>" class="orange-button" />Ask Now</a>&nbsp;&nbsp;&nbsp;<span id="cacelLinkContainer_ForAskInstitute" style="display:none;"><a href="javascript:void(0);" onClick="javascript:askInstitute.callOnFocusOnBlurFunctions('blur');" class="fontSize_12p">Cancel</a></span></div>
            
            <input type="hidden" name ="instituteId" id="instituteIdForAskInstitute" value="<?php echo $instituteId; ?>" />
            <input type="hidden" name ="categoryId" id="categoryIdForAskInstitute" value="<?php echo $categoryId; ?>" />
            <input type="hidden" name ="locationId" id="locationIdForAskInstitute" value="<?php echo $locationId; ?>" />
            <input type="hidden" name="secCodeIndex" value="seccodeForAskInstitute" />
            <input type="hidden" name="loginproductname_ForAskInstitute" value="<?php echo $pageKeyForAskQuestion; ?>" />
            <input type="hidden" name="referer_ForAskInstitute" id="referer_ForAskInstitute" value="" />
            <input type="hidden" name="resolution_ForAskInstitute" id="resolution_ForAskInstitute" value="" />
            <input type="hidden" name="coordinates_ForAskInstitute" id="coordinates_ForAskInstitute" value="" />

   	    <input type="hidden" id="instituteId_<?=$widget?>" name="instituteId_<?=$widget?>" value="<?=$instituteId?>">
	    <input type="hidden" id="locationId_<?=$widget?>" name="locationId_<?=$widget?>" value="<?=$locationId?>">
            <input type="hidden" id="courseId_<?=$widget?>"  name="courseId"  value="<?=$course->getId()?>"/>	    
	    <script>var showAsk='true';</script>
        </div>
        <div class="clearFix"></div>
        <!--Start_Hide/Show_Form--->
        </form>
    </div>

<!--End_Ask_Question-->

