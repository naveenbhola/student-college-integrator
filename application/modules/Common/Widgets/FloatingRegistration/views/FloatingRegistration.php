<div class="find-inst-cont" id="floatingRegistrationOpen" style="display:none;z-index:100;">

    <div style="position:relative">
        <strong><?php echo $headingText;?></strong>
        <?php echo $headingText2;?>
    <a href="javascript:void(0)" onClick="hidePopup();" class="close-widget">&nbsp;</a>
    </div>
    
    <div class="clearFix spacer10"></div>

            <form id="form_<?=$widget?>" onsubmit="processFloatingForm('<?=$widget?>','<?=$trackingPageKeyId?>'); return false;" novalidate>
                        <ul>
                                <li style="margin-bottom:10px">
                                <div>
                                        <input class="universal-txt-field" value="<?php echo $firstname?htmlentities($firstname):"Your First Name";?>" id="usr_first_name_<?=$widget?>"  tip="multipleapply_name" caption="First Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_first_name_<?=$widget?>" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkCaptchaDisplay(); checkTextElementOnTransition(this,'focus')" default="Your First Name"/>
                                        <div style="display:none"><div class="errorMsg" id="usr_first_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div></div>
                                </div>
                                </li>
                                <li style="margin-bottom:10px">
                                <div>
                                        <input class="universal-txt-field" value="<?php echo $lastname?htmlentities($lastname):"Your Last Name";?>" id="usr_last_name_<?=$widget?>"  tip="multipleapply_name" caption="Last Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_last_name_<?=$widget?>" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkCaptchaDisplay(); checkTextElementOnTransition(this,'focus')" default="Your Last Name"/>
                                        <div style="display:none"><div class="errorMsg" id="usr_last_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div></div>
                                </div>
                                </li>
                                <li style="margin-bottom:10px">
                                <div>
                                        <input class="universal-txt-field"  value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; }else{ echo "Email";} ?>" id="contact_email_<?=$widget?>" type="text" validate="validateEmail"  maxlength="100" minlength="10" caption="email"  blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkCaptchaDisplay(); checkTextElementOnTransition(this,'focus')" default="Email" required="true" <?php if(!empty($cookiestr)) { echo "disabled";} ?>/>
                                        <div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="contact_email_<?=$widget?>_error"></div></div>
                                </div>
                                </li>

                                <li style="margin-bottom:10px">
                                <div>
                                        <input class="universal-txt-field"  value="<?php echo $mobile?$mobile:"Mobile";?>" profanity="true" id="mobile_phone_<?=$widget?>" type="text" name="mobile_phone_<?=$widget?>" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" tip="multipleapply_cell" caption="mobile phone" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkCaptchaDisplay(); checkTextElementOnTransition(this,'focus')" default="Mobile" />
                                        <div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="mobile_phone_<?=$widget?>_error"></div></div>
                                </div>
                                </li>
                
				
				<input type="hidden" id="tracking_keyid" name="tracking_keyid" value="<?php echo $trackingPageKeyId;?>">
				
				<?php if($showAsk=='true' ) {
						if( $type=='institute' || $type==''){?>
				
                                <li id="course_floatingWidget" style="margin-bottom:10px">
                                <div>
                                    <select id="courseId_<?=$widget?>"  name="courseId"  required = "true" caption = "course" class="universal-select" style="width: 195px;"  validate = "validateSelect" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkCaptchaDisplay(); checkTextElementOnTransition(this,'focus')" />
                                        <?php if($pageType == "category") { ?>
                                            <option value="Others">Others</option>
                                        <?php } else { ?>
                                            <option value="">Courses</option>
                                            <?php foreach($courses as $course) {?>  
                                                <option value=<?php echo $course->getId(); ?>> <?=html_escape($course->getName())?></option>
                                            <?php } ?>
                                            
                                        <?php } ?>
                                    </select>
                                    <div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="courseId_<?=$widget?>_error"></div></div>
                                </div>
                                </li>
                
                <?php } ?>
                 
                 <?php if($type=='course'){ ?>
                 <input id="courseId_<?=$widget?>" value="<?=$courseId?>" type="hidden"></input> 
                 <?php } ?>
                                <li style="margin-bottom:10px">
                                <div>
                                        <textarea class="universal-select"  value="" profanity="true" id="ask_question_<?=$widget?>" name="ask_question_<?=$widget?>" validate="validateQuestionTextWidget" required="true" maxlength="140" minlength="2" caption="question" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkCaptchaDisplay(); checkTextElementOnTransition(this,'focus')" default="Your Question" style="width:55%;height:20px;" onkeypress=" if(event.keyCode == 13){ return false;} else {  if(this.value.length > 140 && navigator.appName=='Microsoft Internet Explorer'){ this.value = this.value.substring(0,139); }}">Your Question</textarea>
                                        <div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="ask_question_<?=$widget?>_error"></div></div>
                                </div>
                <input type="hidden" id="instituteId_floatingRegistration" name="instituteId_floatingRegistration" value="<?=$instituteId?>">
                <input type="hidden" id="locationId_floatingRegistration" name="locationId_floatingRegistration" value="<?=$locationId?>">
                                <input type="hidden" id="askUrl_floatingRegistration" name="askUrl_floatingRegistration" value="<?=$askUrl?>">
                                </li>
                <?php 
                     } ?>

                                <?php if($validateuser == "false"){ ?>
                                <li style="margin-bottom:10px;display:<?php echo $hideCaptcha;?>" id="captchaDisplayFloatingRegistration">
                                        <p>Type what you see in the picture below</p>
                                        <div class="clearFix spacer10"></div>
                                        <div>
                                                <img class="vam" align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>" width="100" height="34"  id = "secureCode_<?=$widget?>"/>
                                                <input type="text"  style="width:100px;display:<?=$displayForm?>;" class="universal-txt-field" name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
                                                <div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="homesecurityCode_<?=$widget?>_error"></div></div>
                                        </div>
                                </li>
                                <?php } ?>
                                <li style="margin-bottom:0px;">
					<button class="orange-button" onclick="<?php if($showAsk=='true'){ echo "trackEventByGA('ASK_NOW_BUTTON','LISTING_DETAIL_PAGE_OVERVIEW_TAB_FLOATING');";} ?> processFloatingForm('<?=$widget?>','<?=$trackingPageKeyId?>'); return false;" id="submit_<?=$widget?>"><?php echo $buttonText;?> <span class="btn-arrow"></span></button>
                                        <!--<img src= "/public/images/loader_hpg.gif" style="display:none" align="absmiddle" id="loader"/>-->
                                </li>
                        </ul>
                </form>

    
    <div class="clearFix"></div>
</div>

<div class="closed-wid"  onClick="registrationForm.showRegistrationForm('<?php echo $trackingPageKeyId; ?>');" id="floatingRegistrationClosed" style="display:none;z-index:100; width:300px !important;">
    <div class="flLt0">
        <strong><span id="closeHeading"><?php echo $closeHeading;?></span></strong>
        <p><span id="closeLine"><?php echo $closeLine;?></span></p>
    </div>
    <a href="javascript:void(0);" class="orange-button" style=" margin:10px 0 0 60px; display:inline-block; height:18px;">Register Free</a>
    <!-- <div class="closed-wid-icn"></div> -->
    <div class="clearFix"></div>
</div>

<!-- <div class="closed-wid"  onClick="showFloatingRegistration('','false');" id="floatingRegistrationClosed" style="display:none;z-index:100;">
    <div class="flLt">
        <strong><span id="closeHeading">< ?php echo $closeHeading;?></span></strong>
        <p><span id="closeLine">< ?php echo $closeLine;?></span></p>
    </div>
    <div class="closed-wid-icn"></div>
    <div class="clearFix"></div>
</div> -->


<script>
if(typeof($categorypage) != 'undefined')
{
    $('courseId_floatingRegistration').value = 'Others';
    $('course_floatingWidget').style.display = 'none';
}
    
var showAsk = '<?=$showAsk?>';
addOnBlurValidate($('form_<?=$widget?>'));
var a = $j('#<?php echo $rightColId;?>');
var isLefthandled = true;
if(window.innerWidth){
        if(window.innerWidth<=1024){
        $('floatingRegistrationOpen').style.right = '0px';
        $('floatingRegistrationClosed').style.right = '0px';
        isLefthandled = false;
            //var leftMargin = (a.offset().left-213);
    }
        else{
                var leftMargin = a.offset().left;
        $('floatingRegistrationOpen').style.left = leftMargin+'px';
        $('floatingRegistrationClosed').style.left = leftMargin+'px';
    }
}
else{
        if(screen.availWidth<=1024){
                //var leftMargin = (a.offset().left-213);
        $('floatingRegistrationOpen').style.right = '0px';
        $('floatingRegistrationClosed').style.right = '0px';
        isLefthandled = false;
    }
        else{
                var leftMargin = a.offset().left;
        $('floatingRegistrationOpen').style.left = leftMargin+'px';
        $('floatingRegistrationClosed').style.left = leftMargin+'px';
    }
}

$('floatingRegistrationOpen').style.bottom = '0px';
$('floatingRegistrationClosed').style.bottom = '0px';

if('<?=$showAsk?>'=='true'){
    $('floatingRegistrationClosed').style.width = '272px';
    $('floatingRegistrationOpen').style.width = '330px';
}

//Start: Code for animation on the FLoating registration widget
var animateOnce = true;
function animateWidget(){
    if(animateOnce){
        animateOnce = false;
        if(isLefthandled){
            var leftMarginOfWidget = currentShown.style.left;
            elemLeft = leftMarginOfWidget.split('px');
            finalLeft = elemLeft[0];
            currentShown.style.left = (Number(finalLeft)+Number(widthAnimated)) + 'px';
            marginNow = finalLeft;
            startAnimation();
        }
        else{
            var rightMarginOfWidget = currentShown.style.right;
                        elemRight = rightMarginOfWidget.split('px');
                        finalRight = elemRight[0];
                        currentShown.style.right = (finalRight-widthAnimated) + 'px';
            marginNow = finalRight;
            direction = 'right';
            startAnimation();
        }
    }
}

var widthAnimated = 200;
var totalMargin = 200;
var direction = 'left';
var marginNow = 0;

function startAnimation(){
    if(totalMargin<=0){
        return true;
    }
    if(direction=='left')
        currentShown.style.left = (Number(marginNow) + (Number(totalMargin)-5) ) + 'px';
    else
        currentShown.style.right = (Number(marginNow) - (Number(totalMargin)-5) ) + 'px';
    totalMargin = Number(totalMargin)-5;
    setTimeout('startAnimation()', 1);
}
//End: Code for animation on the FLoating registration widget

//Start: Code to Display the FLoating registration widget after some widget. This top limit will be decided by displayAfter
var displayAfter = <?php echo $displayAfter;?>;
var floatingDivClosed = $('floatingRegistrationClosed');
var floatingDivOpen = $('floatingRegistrationOpen');
var currentShown = floatingDivClosed;
//if('<?=$floatingRegisterWidgetClosed?>'=='true'){
//  var currentShown = floatingDivClosed;
//}
//else{
//  var currentShown = floatingDivOpen;
//}

function isScrolledIntoView() {
    if(typeof($categorypage) != 'undefined')
        displayAfter = $j('#adsense').offset().top;
   var docViewTop = $j(window).scrollTop();
   if(docViewTop<displayAfter){
    return 'none';
   }
   else{
    return '';
   }
}

$j(window).scroll(function() {
    var displayVal = isScrolledIntoView();
    checkFloatingWidgetStatus(displayVal);
});

function checkFloatingWidgetStatus(displayValue){
        currentShown.style.display = displayValue;
        if(displayValue==''){
                animateWidget();
        }
}

if( displayAfter == '' || displayAfter == 0 ){
    currentShown.style.display = '';
    animateWidget();
}
else if( displayAfter == '-1' ){
        currentShown.style.display = 'none';
}

var displayVal = isScrolledIntoView();
checkFloatingWidgetStatus(displayVal);

//End: Code to Display the FLoating registration widget after some widget.

function hidePopup() {
    $('floatingRegistrationOpen').style.display = 'none';
    $('floatingRegistrationClosed').style.display = 'block';
    currentShown = floatingDivClosed;
}

function checkCaptchaDisplay(){
    if($('captchaDisplayFloatingRegistration'))
    $('captchaDisplayFloatingRegistration').style.display = 'block';
    if($('ask_question_<?=$widget?>'))
        $('ask_question_<?=$widget?>').style.height = '40px';
}

var isLDBUserFloating = '<?php echo $isLDBUser;?>';

function registerNow(source,trackingPageKeyId){
    data = {};
    data['referer'] = window.location;
    data['source'] = source;
    data['trackingPageKeyId'] = trackingPageKeyId;
    data['callback'] = function(data) {
        if (data['status'] != 'LayerClosed') { 
            window.location.reload(true); 
        }
    };
    shikshaUserRegistration.showRegisterFreeLayer(data);              
}

</script>
