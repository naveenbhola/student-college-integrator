<?php if(!empty($questionIds) && isset($topicListings['0']['results']) ){ ?>



<div id ="register_login_overlay" style="display:none">
	<div style="margin-bottom:10px;">
	      <div style="display:none;padding-top:10px" id="userLoginOverlay_ForAnA_widget_login" class="row">
		      <div class="float_R" style="padding-right:10px;">
				      <a href="javascript:void(0);" onClick="javascript:showHideRegisterLoginOverlay('none','inline');" class="bld">New Shiksha User</a>
		      </div>
		      <div class="OrgangeFont bld" style="padding-left:17px;font-size:13px">Existing Shiksha User Sign In</div>
	      </div>
	      <div  id="userLoginOverlay_ForAnA_widget_registration" class="row">
		      <div class="float_R" style="padding-right:10px">
			      <div class="float_L"><a href="javascript:void(0);" onClick="javascript:showHideRegisterLoginOverlay('inline','none');" class="bld">Existing Shiksha User Sign In</a></div>
		      </div>
		      <div class="OrgangeFont bld" style="padding-left:17px;font-size:13px">New Shiksha User</div>
	      </div>
	</div>
        <!--Start_Registrtion_Form-->
		<div id="userLoginOverlay_ForAnA_widget_registrationform" style="display:inline">
			<!--<form method="post" onsubmit="if(validatequickSignUpForAnA(this) != true){return false;} else { disableRegisterButton_ForAnA();}; new Ajax.Request('/user/Userregistration/veryQuickRegistrationForAnA',{onSuccess:function(request){javascript:showQuickSignUpResponse_ForAnA(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="/user/Userregistration/veryQuickRegistrationForAnA" id = "RegistrationForm_ForAnA" name = "RegistrationForm_ForAnA">-->

                        <div class="row">

				<div>
                                        <div class="float_L" style="width:177px;line-height:22px"><div class="txt_align_r">Name:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div>
							<input type="text" id = "quickname_ForAnA_widget" name = "quickname_ForAnA_widget" type = "text" validate = "validateDisplayName" required = "true" maxlength = "25" minlength = "3" caption = "name" style="width:144px" value="" />
							<div id="quickname_ForAnA_widget_error" style="display:none" class="errorMsg" >The name specified is not correct</div>
						</div>
						<!--<div style="display:none"><div class="errorMsg" id="quickname_ForAnA_widget_error"></div></div>-->
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div>
					<div class="float_L" style="width:177px;line-height:22px"><div class="txt_align_r">Login Email:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div>
							<input type="text" id = "quickemail_ForAnA_widget" name = "quickemail_ForAnA_widget" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125"  style="width:144px" value=""/>
							<div id="quickemail_ForAnA_widget_error" style="display:none" class="errorMsg" >The email address specified is not correct</div>
                                                </div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div>
					<div class="float_L" style="width:177px;line-height:22px"><div class="txt_align_r">Mobile Number:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div>
							<input type="text" id = "quickmobile_ForAnA_widget" name = "quickmobile_ForAnA_widget" validate = "MobileInteger" required = "true" caption = "mobile number" minlength = "10" maxlength = "10"  style="width:144px" value=""/>
							<div id="quickmobile_ForAnA_widget_error" style="display:none" class="errorMsg" >The mobile number specified is not correct</div>
                                                </div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>

				<div style="line-height:9px">&nbsp;</div>
				<div style="padding-left:66px">
					<div style="padding-bottom:7px">Type in the characters you see in the picture below </div>
					<div>
						<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeForReg" width="100" height="40" onabort="javascript:reloadCaptcha(this.id);" onClick="javascript:reloadCaptcha(this.id);" id = "registerCaptacha_ForList" align="absmiddle" />&nbsp;&nbsp;&nbsp;
						<input type="text" id = "securityCode_ForList" name = "securityCode_ForList" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" style="width:144px" />
					</div>
					<div><div class="errorMsg" id="securityCode_ForList_error"></div></div>
				</div>

				<div style="line-height:9px">&nbsp;</div>
				<div style="padding-left:66px">
					<input type="checkbox" id="quickagree_list" checked="checked">
					I agree to the <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
					<div><div class="errorMsg" id="quickagree_list_error" style="display:none"></div></div>
				</div>

			</div>


                        <div style="line-height:7px">&nbsp;</div>
			<div align="center"><input type="button"  value="Join now & Continue" onClick ="getUserDetailsForNewUser('newUser');" class="rgsBtnJoin bld whiteColor" id="Register_ForAnA_widget"/><span style="margin-left:5px;display:none;" id="loaderDiv_Registration"><img src="/public/images/working.gif" valign="center"/></span></div>
                        <div style="line-height:27px">&nbsp;</div>

		</div>
        <!--End_Of_Registration_Form-->


        <!--Start_LOGIN_Form-->
                <div  id="msg_user_exists" style="display:none" class="txt_align_c mb10">An account with this email already exists</div>
		<div id="userLoginOverlay_ForAnA_widget_loginform" style ="display:none">

			<div class="row">
				<input type="hidden" name="typeOfLoginMade" id="typeOfLoginMade" value="veryQuick" />
				<input type = "hidden" name = "mpassword_ForAnA_widget" id = "mpassword_ForAnA_widget" value = ""/>
				<div>
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">Login Email Id:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div><input type="text" id = "username_ForAnA_widget" name = "username_ForAnA_widget" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" minlength = "10" style="width:157px" /></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:6px">&nbsp;</div>
				<div style="padding-bottom:1px" id="passwordPlaceHolder_ForAnA_widget">
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">Password:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div><input type="password" id = "password_ForAnA_widget" name = "password_ForAnA_widget" validate = "validateStr" minlength = "5" maxlength = "20" required = "true" caption = "password" style="width:157px" /></div>
						<div id="password_ForAnA_widget_error" style="display:none"><div class="errorMsg" >Invalid Email or Password</div></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div id="remember_ForAnA_widget">
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">&nbsp;</div></div>
					<div class="float_L" style="width:340px;margin-left:-3px"><div style="font-size:11px"><input type="checkbox" value="0" /> Remember me on this computer</div></div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div id="loginButtonPlaceHolder_ForAnA_widget" class="row">
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">&nbsp;</div></div>
					<div class="float_L" style="width:340px">
					<input type="button" value="Login" class="submitGlobal bld" id="Login_ForAnA_widget" onClick="doQuickLogin('quickLogin');"/>
					<span style="margin-left:5px;display:none;" id="loaderDiv_Login"><img src="/public/images/working.gif" valign="center"/></span>
                                        </div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
<!--				<div style="line-height:6px">&nbsp;</div>
				<div id="forgotPasswordButtonPlaceHolder_ForAnA_widget" style="display:none;" class="row">
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<input id="forgotPasswordSubmitBtnAnA_widget" type="button" value="Submit" onClick="return sendForgotPasswordMailForAnA();" class="submitGlobal bld mar_right_20p" /> &nbsp; <a href="javascript:void(0);"  onClick = "return switchForgotPassword('','none');">Login</a></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>

				<div id="forgotPasswordLinkPlaceHolder_ForAnA_widget" class="row">
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">&nbsp;</div></div>
					<div class="float_L" style="width:340px"><a href="javascript:void(0);" onClick = "return switchForgotPassword('none','');">Forgot Password</a></div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>-->

			<div style="line-height:24px">&nbsp;</div>
                        </div>
                            </div>

        <!--End_LOGIN_Form-->


</div>
<h4 class="section-cont-title">Latest Discussion from Q&amp;A</h4>
<div class="pL-7">
<?if($tab == 'overview'){?>
<div id="divForOverall">
<?php }else{?>
<div id = "divForOverall">
<?php }?>
<?php
$params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>$identifier,'locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$details['courseDetails']['0']['title'],'courseId'=>$details['courseDetails']['0']['course_id']);
$askNAnswerTabUrl = listing_detail_ask_answer_url($params);
?>


<?php
$pageKeyForAskQuestion = 'LISTING_INSTITUTEDETAIL_ASK_INSTITUTE_POSTQUESTION';
				$categoryId = 0;
				if(count($details['subCatArray']) == 1){
					$categoryId = $details['subCatArray'][0];
				}
				$locationId = is_array($details['locations'][0])?$details['locations'][0]['country_id']:2;
 				$instituteId = $details['institute_id'];

                                $userStatus = $validateuser;
$NameOfUser = is_array($validateuser[0])?$validateuser[0]['firstname']:"";
$cookiStr =  is_array($validateuser[0])?$validateuser[0]['cookiestr']:"";
$cookiStrArr = explode("|",$cookiStr);
$emailId =     isset($cookiStrArr[0])?$cookiStrArr[0]:"";
$contactNumber = is_array($validateuser[0])?$validateuser[0]['mobile']:"";

$result = $topicListings['0']['results'];

$questionCount = $countData['0']['questionCount'];
$answerCount = $countData['0']['answerCount'];
$commentCount = $countData['0']['commentCount'];

if($questionCount==0){
    $questionCount = " ";
}else{
$questionCount = ($questionCount==1)?$questionCount." question asked":$questionCount." questions asked";
}
if($answerCount==0){
    $answerCount = " ";
}else{
$answerCount = ($answerCount==1)?", ".$answerCount." answer":", ".$answerCount." answers";
}
if($commentCount==0){
    $commentCount = " ";
}else{
$commentCount = ($commentCount==1)?" & ".$commentCount." comment":" & ".$commentCount." comments";
}

$divCount = 0;
?>
<p class="discussion-header">
	<a href="<?php echo $askNAnswerTabUrl?>"><?php echo $questionCount;echo $answerCount;echo $commentCount;?></a>
	<span>About <?php echo $details['title'];?></span>
</p>

<a class="ml46 mr20" href="<?php echo $askNAnswerTabUrl;?>" style="color:#000;text-decoration:none">
<ul class="latest-discussion-list">
<?php
$i=0;
for($count=0;$count<5 && $i<count($result);){
?>
<li style="display:none">
<?php
if($result[$i]['type'] == 'question'){
    $questionDisplayName = $result[$i]['0']['displayname'];
    $questionTxt = $result[$i]['0']['msgTxt'];
    $questionTxt = (strlen($questionTxt)>150)?substr($questionTxt,0,150)."...":$questionTxt;
    $questionUserId = $result[$i]['0']['userId'];
    //$creationDate = $result[$i]['0']['creationDate'];
    $imageURL = ($result[$i]['0']['userImage']=='')?'/public/images/photoNotAvailable.gif':$result[$i]['0']['userImage'];
    //$timeDisplay = makeRelativeTime($creationDate);

  ?>
<?php if($count==0 || $count==1){ ?>
<div id ="askWidgetDiv_<?php echo $count?>">
<?php }else{ ?>
<div id ="askWidgetDiv_<?php echo $count?>">
<?php } ?>

<?php if($tab == 'overview'){?>
<div>
<?php }else{?>
<div>
<?php }?>
<div class="figure"><img src ="<?php echo getTinyImage($imageURL);?>" border="0"></div>
<div class="details"><strong><?php echo $questionDisplayName; ?></strong><strong> asked </strong><?php echo $questionTxt; ?>
</div>
</div>

</div>

<?php $count++; }elseif($result[$i]['type'] == 'answer'){

    $questionDisplayName = $result[$i]['0']['displayname'];
    $questionTxt = $result[$i]['0']['msgTxt'];
    $questionTxt = (strlen($questionTxt)>100)?substr($questionTxt,0,100)."...":$questionTxt;
    $answerTxt = $result[$i]['1']['msgTxt'];
    $answerDisplayName = $result[$i]['1']['displayname'];
    $testString = $answerTxt.$answerDisplayName." answered ";
    $answerTxt = (strlen($testString)>180)?substr($answerTxt,0,130)."...":$answerTxt;
    $questionUserId = $result[$i]['0']['userId'];
    $answerUserId = $result[$i]['1']['userId'];
    //$creationDate = $result[$i]['1']['creationDate'];
    $imageURL = ($result[$i]['1']['userImage']=='')?'/public/images/photoNotAvailable.gif':$result[$i]['1']['userImage'];
    //$timeDisplay = makeRelativeTime($creationDate);

 ?>
 
<?php if($count==0 || $count==1){ ?>
<div id ="askWidgetDiv_<?php echo $count?>" style="" >
<?php }else{ ?>
<div id ="askWidgetDiv_<?php echo $count?>" style="" >
<?php } ?>

<?php if($tab == 'overview'){?>
<div>
<?php }else{?>
<div>
<?php }?>
<p><span class="sprite-bg ques-icn" style="margin:1px 3px 0 0 !important"></span><span style="margin-left:27px; display:block"><?php echo $questionTxt;?></span></p>
<div class="figure"><img src ="<?php echo getTinyImage($imageURL);?>" border="0"></div>
<div class="details"><strong><?php echo $answerDisplayName; ?> answered </strong><?php echo $answerTxt;?></div>
</div>

</div>

<?php $count++;}
$i++;
?>
</li>
<?php
$divCount = $count;
}
if($divCount==1){
$divCount=0;
}
?>
<li><div id ="askWidgetDiv_<?php echo $divCount;?>" style=""></div></li>
</ul>
</a>
</div>
<div class="spacer15 clearFix"></div>
<a href="<?php echo $askNAnswerTabUrl?>" class="see-all-link">Explore more from Q&amp;A</a>
<div class="spacer15 clearFix"></div>

<div class="ask-abt-outer">
<?php
	if($tab == 'overview') {
?>
	<input type="text" name ="questionText" id ="questionText" class="ask-abt-field" style="color: #737373;" value="Ask About <?php echo $details['title'];?>" onfocus ="checkTextElementOnTransition(this,'focus');" onblur="checkTextElementOnTransition(this,'blur'); this.style.color='#737373'; $('questionText_error').style.display = 'none';" default="Ask About <?php echo $details['title'];?>"/>
<?php
	}else{
?>
	<input type="text" name ="questionText" id ="questionText" class="ask-abt-field" style="color: #737373; width:160px; padding-top:7px; font-size:14px" value="Ask About <?php echo $details['title'];?>" onfocus ="checkTextElementOnTransition(this,'focus');" onblur="checkTextElementOnTransition(this,'blur'); this.style.color='#737373'; $('questionText_error').style.display = 'none';" default="Ask About <?php echo $details['title'];?>"/>
<?php
	}
?>	<input id="askInstituteWidgetButton" class="orange-button flRt" title="Ask Now" type="button" uniqueattr="ListingPageOverViewTabAskNow" value="Ask Now" onclick="trackEventByGA('ButtonClick','LISTING_OVERVIEW_ASK_QUESTION_BTN'); validateQuestionText(<?php echo $userStatus?>);" />
</div>

    
<div class="errorMsg" id="questionText_error" style="display:none">Please enter correct question</div>

<input type="hidden" name ="instituteId" id="instituteIdForAskInstitute" value="<?php echo $instituteId; ?>" />
<input type="hidden" name ="categoryId" id="categoryIdForAskInstitute" value="<?php echo $categoryId; ?>" />
<input type="hidden" name ="locationId" id="locationIdForAskInstitute" value="<?php echo $locationId; ?>" />
<input type="hidden" name="secCodeIndex" value="seccodeForAskInstitute" />
<input type="hidden" name="loginproductname_ForAskInstitute" value="<?php echo $pageKeyForAskQuestion; ?>" />
<input type="hidden" name="referer_ForAskInstitute" id="referer_ForAskInstitute" value="" />
<input type="hidden" name="resolution_ForAskInstitute" id="resolution_ForAskInstitute" value="" />
<input type="hidden" name="coordinates_ForAskInstitute" id="coordinates_ForAskInstitute" value="" />
<input type="hidden" name="nameOfUserForAskInstitute" id="nameOfUserForAskInstitute" validate = "validateDisplayName" value="<?php echo $NameOfUser; ?>" />
<input type="hidden" name="emailOfUserForAskInstitute" id="emailOfUserForAskInstitute" validate = "validateEmail" value="<?php echo $emailId; ?>"  />
<input type="hidden" id = "mobileOfUserForAskInstitute" name = "mobileOfUserForAskInstitute" validate = "validateMobileInteger" value="<?php echo $contactNumber; ?>" />
<div id="loginoverlaydiv"></div>
</div>


<script>
 function postQuestion(){
    var questionText = document.getElementById('questionText').value;
    var instituteId = document.getElementById('instituteIdForAskInstitute').value;
    var categoryId = document.getElementById('categoryIdForAskInstitute').value;
    var locationId = document.getElementById('locationIdForAskInstitute').value;
    var referer = location.href;
    var resolution = '276X482';
    var coordinates =  '1280X800';
    var name = document.getElementById('nameOfUserForAskInstitute').value;
    var email = document.getElementById('emailOfUserForAskInstitute').value;
    var mobile = document.getElementById('mobileOfUserForAskInstitute').value;
    documentOverlay.show(true); 
 var url= '/messageBoard/MsgBoard/askQuestionFromListing';
 var data = 'questionText='+questionText+'&instituteId='+instituteId+'&categoryId='+categoryId+'&locationId='+locationId+'&referer_ForAskInstitute='+referer+'&resolution_ForAskInstitute='+resolution+'&coordinates_ForAskInstitute='+coordinates+'&nameOfUserForAskInstitute='+name+'&emailOfUserForAskInstitute='+email+'&mobileOfUserForAskInstitute='+mobile;
  new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function (request) {
				addRecentlyPostedQuestion_Widget(request.responseText);
                                //afterPostingQuestion(request.responseText);
			}
			});
 }

    function addRecentlyPostedQuestion_Widget(responseText){
        var responseObj = eval("eval("+responseText+")");
        var url = '/listing/Listing/addQuestionId';
        var questionArray = new Array();
        questionArray = <?php echo json_encode( $questionIds)?>;
        var instituteId = <?php echo $details['institute_id']?>;
        var data = 'threadId='+responseObj.questionResult+'&questionArray='+questionArray+'&instituteId='+instituteId;
        //alert (responseObj.questionResult);
        new Ajax.Request (url,{method:'post', parameters: data,onSuccess:function(){afterPostingQuestion(responseText);}
			});
        document.getElementById('loaderDiv_Login').style.display = 'none';

}

    function afterPostingQuestion(responseText){
	<!--
	var  url = '/listing/Listing/getListingDetail/<?php echo $instituteId;?>/institute/ana';
    if($("anaTabLink")){
		url = $("anaTabLink").href;
	}
       -->
    documentOverlay.hide();
        var responseObj = eval("eval("+responseText+")");


        if((typeof(Number(responseObj.questionResult)) == 'number') && (Number(responseObj.questionResult) > 0)){


        setCookie('commentContent','',-1);
                document.getElementById('genOverlay').style.display = 'none';

                if(responseObj.alreadyRegistered != 2){
                    commonShowConfirmMessage('<span class="fontSize_14p bld">Your question has been posted successfully.</span>',<?php echo $instituteId;?>);
                    document.location = url;
                }

                if(responseObj.alreadyRegistered == 2){

			populatUserDataForWidget();
			run_click_detailOverlay('showMultipleApplyOverlayForWidget',4);
		}


        }
    }
function run_click_detailOverlay(functionToCall,overlayNumber){
        functionToCall = (typeof(functionToCall) != 'undefined')?functionToCall:'-1';
    LazyLoad.loadOnce([
        '//'+JSURL+'/public/js/<?php echo getJSWithVersion("ajax-api");?>',
        '//'+JSURL+'/public/js/<?php echo getJSWithVersion("ajax");?>',
        '//'+JSURL+'/public/js/<?php echo getJSWithVersion("modal-message");?>',
        '//'+JSURL+'/public/js/<?php echo getJSWithVersion("ajax-dynamic-content");?>',
        '//'+JSURL+'/public/js/<?php echo getJSWithVersion("user");?>'
    ],function(){ initializeMultipleApply(functionToCall,overlayNumber) },null,null,true);
	return;
}

    function registerLoginOverlay(){
        var overlayWidth = 535;
	var overlayHeight = 250;
	var overlayTitle = '<span><b>Sign in to Shiksha</b></span>';
	var overlayDivId = 'register_login_overlay';
	var overLayForm = $(overlayDivId).innerHTML;
	$(overlayDivId).innerHTML = '';
	var overlayContent = overLayForm;
	overlayParent = $(overlayDivId); // Global variable For all the parent overlay contents;
	showOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent,false);

    }

    function showHideRegisterLoginOverlay(display1,display2){

        document.getElementById('userLoginOverlay_ForAnA_widget_registrationform').style.display = display2;
        document.getElementById('userLoginOverlay_ForAnA_widget_registration').style.display = display2;
        document.getElementById('userLoginOverlay_ForAnA_widget_loginform').style.display = display1;
        document.getElementById('userLoginOverlay_ForAnA_widget_login').style.display = display1;
    }

    function  doQuickLogin(type){
        var validate = validateRegisterLoginForm(type);
        if(validate == false){
            return false;
        }
        document.getElementById('loaderDiv_Login').style.display = 'inline';
        var email = document.getElementById('username_ForAnA_widget').value;
        var password = document.getElementById('password_ForAnA_widget').value;
        var typeOfLogin = document.getElementById('typeOfLoginMade').value;
        var remember = document.getElementById('remember_ForAnA_widget').value;
        var mpassword = (password);

        var url = '/user/Login/submit';
        var data = 'username_ForAnA='+email+'&mpassword_ForAnA='+mpassword+'&typeOfLoginMade='+typeOfLogin+'&remember_ForAnA='+remember;

        new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function (request) {
                                if (request.responseText != 0) {
                                    var url = '/user/Login/ajax_user';
                                    var data = 'username_ForAnA='+email+'&mpassword_ForAnA='+mpassword+'&typeOfLoginMade='+typeOfLogin+'&remember_ForAnA='+remember;
                                    new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function (request){getUserDetailsForAlreadyExistingUser(request.responseText); }
                                    });
                                }
                                if(request.responseText == 0){
                                    document.getElementById('password_ForAnA_widget_error').style.display = 'inline';
				    document.getElementById('loaderDiv_Login').style.display = 'none';
                                }
				return;
			}
			});

    }

    function getUserDetailsForAlreadyExistingUser(response){
        document.getElementById('nameOfUserForAskInstitute').value = response.firstname;
        document.getElementById('emailOfUserForAskInstitute').value = document.getElementById('username_ForAnA_widget').value;
        document.getElementById('mobileOfUserForAskInstitute').value = response.mobile;
        postQuestion();
    }

    function getUserDetailsForNewUser(type){
        var validate = validateRegisterLoginForm(type);
        if(validate == false){
            return false;
        }else{
	    return true;
        }

    }

function checkIfUserExists(email){
   var url = '/listing/Listing/checkIfUserExistsForListingAnA';
   var data = 'email='+email;
   document.getElementById('loaderDiv_Registration').style.display = 'inline';
   new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function (request) {caseWhenUserExists(request.responseText)}});

}

function caseWhenUserExists(responseText){
    var response = eval(responseText);
    if(response == 0){
        document.getElementById('quickemail_ForAnA_widget_error').style.display = 'none';
        document.getElementById('quickmobile_ForAnA_widget_error').style.display = 'none';

        document.getElementById('nameOfUserForAskInstitute').value = document.getElementById('quickname_ForAnA_widget').value
        document.getElementById('emailOfUserForAskInstitute').value = document.getElementById('quickemail_ForAnA_widget').value
        document.getElementById('mobileOfUserForAskInstitute').value = document.getElementById('quickmobile_ForAnA_widget').value
        postQuestion();
    }else{
        showHideRegisterLoginOverlay('inline','none');
        document.getElementById('msg_user_exists').style.display = 'inline';
        document.getElementById('username_ForAnA_widget').value = response;
    }
}

    function validateQuestionText(validateUser){
        checkTextElementOnTransition(document.getElementById('questionText'),'focus');
        var questionText = document.getElementById('questionText').value;
        var check = validateStr(questionText,"Question",140,2);
        if(check!=true){
        $('questionText').focus();
            document.getElementById("questionText_error").innerHTML= check;
        document.getElementById("questionText_error").style.display = '';
        }else{
	     //disableElement('askInstituteWidgetButton');
             if(validateUser !== false){
                     document.getElementById('questionText_error').style.display = 'none';
                     postQuestion();
                  }else{
                    document.getElementById('questionText_error').style.display = 'none';
                    //registerLoginOverlay();

                loadShortRegistationForm("Sign in to Shiksha",
                                                                 "Join now and Continue",
                                                                 "Your question has been successfully posted.",
                                                                 true,false,
			                function(){
						postQuestion();
			                });
                         }
        }
    }
var askInstitute = new Object();
askInstitute.successMessage=null;

function showMultipleApplyOverlayForWidget(overlayFlag){
        if(overlayFlag == 1){
                askInstitute.successMessage='showLogin';
                displayMessage('/MultipleApply/MultipleApply/showoverlay/1/MULTIPLE_APPLY_INSTITUTE_LIST_REQUESTE_BROCHURE_OVERLAY_CLICK/<?php echo $instituteId;?>/<?php echo addslashes(str_replace("\n",' ',$details['title']));?>',500,260);
        }else if(overlayFlag == 4){
                askInstitute.successMessage='showRegister';
                displayMessage('/MultipleApply/MultipleApply/showoverlay/4/MULTIPLE_APPLY_INSTITUTE_LIST_REQUESTE_BROCHURE_OVERLAY_CLICK/<?php echo $instituteId;?>/<?php echo addslashes(str_replace("\n",' ',$details['title']));?>',665,380);
        }
        return false;
}
function populatUserDataForWidget(){
        email_id = $('emailOfUserForAskInstitute').value;
        phone_no = $('mobileOfUserForAskInstitute').value;
        display_name = $('nameOfUserForAskInstitute').value;
        return false;
}

    function focusFunc(obj){
        var id = obj.id;
        var element = document.getElementById(id).value;
        if(element =='Ask About <?php echo addslashes(str_replace("\n",' ',$details['title']));?>'){
            document.getElementById(id).value = "";
        }


    }

    function redirectToAnATab(){
    var  url = '/listing/ListingPage/listingAnATab/<?php echo $instituteId;?>';
    document.location = url;
    }

    function validateRegisterLoginForm(type){
	var email =true;
	var mobile =true;
	var nameC = true;
	var termsC = true;
	document.getElementById('quickagree_list_error').style.display = 'none';
	document.getElementById('quickname_ForAnA_widget_error').style.display = 'none';
	document.getElementById('quickemail_ForAnA_widget_error').style.display = 'none';
	document.getElementById('quickmobile_ForAnA_widget_error').style.display = 'none';
	if(type == 'newUser'){
		var nameCheck  = validateDisplayName(document.getElementById('quickname_ForAnA_widget').value, "name", 25, 3);
		if(nameCheck!=true){
		    document.getElementById('quickname_ForAnA_widget_error').innerHTML = nameCheck;
		    document.getElementById('quickname_ForAnA_widget_error').style.display = '';
		    nameC = false;
		}
		var emailCheck  = validateEmail(document.getElementById('quickemail_ForAnA_widget').value, "email", 125, 5);
		if(emailCheck!=true){
		    document.getElementById('quickemail_ForAnA_widget_error').innerHTML = emailCheck;
		    document.getElementById('quickemail_ForAnA_widget_error').style.display = '';
		    email = false;
		}
		var mobileCheck = validateMobileInteger(document.getElementById('quickmobile_ForAnA_widget').value,"mobile",10,10,true);
		if(mobileCheck!=true){
		    document.getElementById('quickmobile_ForAnA_widget_error').innerHTML = mobileCheck;
		    document.getElementById('quickmobile_ForAnA_widget_error').style.display = '';
		    mobile = false;
		}
		if($('quickagree_list').checked != true){
		    document.getElementById('quickagree_list_error').innerHTML = 'Please accept the terms and conditions to proceed';
		    document.getElementById('quickagree_list_error').style.display = '';
		    termsC = false;
		}
		if(!checkCaptcha(email,mobile,nameC,termsC)){
		    return false;
		}

	}
	return true;
    }


    function checkCaptcha(email,mobile,nameC,termsC){
	if($('securityCode_ForList')){
	    var ObjectOfSecCode = $('securityCode_ForList'); 
	    var paraString = ""; 
	    var caption = ObjectOfSecCode.getAttribute('caption');  
	    var url = "/listing/Listing/validateCaptcha/"+ObjectOfSecCode.value+"/secCodeForReg";  
	    new Ajax.Request(url, { method:'post', parameters: (paraString), onSuccess:function (request){  
			if(trim(request.responseText)=='true' || trim(request.responseText)=='1'){  
			    document.getElementById('securityCode_ForList_error').style.display = 'none';
			    if(!(email && mobile && nameC && termsC)){
				return false;
			    }
			    checkIfUserExists(document.getElementById('quickemail_ForAnA_widget').value);
			    return true;  
			}        
			else{   
			    reloadCaptcha($('registerCaptacha_ForList'),'secCodeForReg');           
			    document.getElementById('securityCode_ForList_error').innerHTML = 'Please enter the '+caption+' as shown in the image.';
			    document.getElementById('securityCode_ForList_error').style.display = '';
			    return false;        
			}        
		    }    
		});      
	    return false;    
	} 
    }

    function echeck(str) {
		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){

		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){

		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){

		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){

		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){

		    return false
		 }

		 if (str.indexOf(" ")!=-1){

		    return false
		 }

 		 return true
	}

        function copyingTopDiv(){
            var total = <?php echo $divCount;?>;
            var divId = 'askWidgetDiv_'+ total;
            document.getElementById(divId).innerHTML = document.getElementById('askWidgetDiv_0').innerHTML;
            //rotateWidgetDivs();
        }

    function rotateWidgetDivs(){
    var total = <?php echo $divCount;?>;

    var maxDivId = '#askWidgetDiv_'+ total;
     if(typeof rotateWidgetDivs.counter == 'undefined'){
         rotateWidgetDivs.counter=0;
       }
       if(typeof rotateWidgetDivs.divCounter == 'undefined'){
         rotateWidgetDivs.divCounter = '#askWidgetDiv_0';
       }
       if(rotateWidgetDivs.counter == '0'){
           $j(maxDivId).parent().fadeOut();
       }
       $j(rotateWidgetDivs.divCounter).parent().fadeOut();

       var div1 = '#askWidgetDiv_'+ parseInt((rotateWidgetDivs.counter)%(total+1));
       var div2 = '#askWidgetDiv_'+ parseInt((rotateWidgetDivs.counter+1)%(total+1));
       rotateWidgetDivs.divCounter = div1;
       $j(div1).parent().fadeIn();
       $j(div2).parent().fadeIn();
       if(rotateWidgetDivs.counter==total-1){
           rotateWidgetDivs.counter = 0;
       }else{
           rotateWidgetDivs.counter ++;
       }
    setTimeout(function () { rotateWidgetDivs() },8000);

}
 //window.onload = function (){
     try{
         rotateWidgetDivs();
     }catch(e){

  //   }
 }


document.getElementById('askWidget').style.background = "none";
    </script>

<?php
if($searchAgainFlag == 1){?>
<script>
var url = "/listing/Listing/searchAgainListingQuestion";
var instituteId = <?php echo $instituteId;?>;
new Ajax.Request(url, { method:'post', parameters: ('instituteId='+instituteId) });
</script>
<?php
}
?>


<?php
echo "<script>";
echo "copyingTopDiv();";
if($divCount == 0){
if($tab == 'overview')
echo "document.getElementById('divForOverall').style.height= '127px'";
else
echo "document.getElementById('divForOverall').style.height= '155px'";
}
echo "</script>";
} else { ?>
<script>
    if(document.getElementById('askWidget')){
    document.getElementById('askWidget').style.display = "none";
    }
</script>
<?php } ?>
