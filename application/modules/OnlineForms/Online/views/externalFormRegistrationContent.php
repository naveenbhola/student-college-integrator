<?php
global $onlineFormsDepartments;
$department = $this->courselevelmanager->getCurrentDepartment();
?>
<div style="width:946px; margin:0 auto; text-align:left">
    <?php if ($courseId > 0): ?>
        <div id="appsFormHeader" style="padding:0">
            <!--Starts: Institute Header -->
            <?php $this->load->view('Online/externalFormLoginHeader'); ?>
            <!--Ends: Institute Header-->
        <?php endif; ?>
    </div>
    <div class="spacer10 clearFix"></div>
    <?php 
        // LF-2986
        global $paymentPaymentExclusionCourses;
        if(!in_array($courseId, $paymentPaymentExclusionCourses)){
            //$this->load->view("payTmOfferMsg");
        }
        
    ?>
    <?php 
        $display = 'none';
        if($userId == '' || $isValidResponseUser == 'no') 
            $display = 'block';
    ?>
    <div style="display:block;" id="userLoginOverlay_online" >
        <div>
            <input type = "hidden" name = "loginaction_ForAnA" id = "loginaction_ForAnA" value = ""/>
            <input type = "hidden" name = "loginflag_ForAnA" id = "loginflag_ForAnA" value = ""/>
            <input type = "hidden" name = "loginflagreg_ForAnA" id = "loginflagreg_ForAnA" value = ""/>
            <input type = "hidden" name = "loginactionreg_ForAnA" id = "loginactionreg_ForAnA" value = ""/>
            <input type = "hidden" name = "tyepeOfRegistration_ForAnA" id = "tyepeOfRegistration_ForAnA" value = ""/>


            <!--Login Layer Starts here-->
            <div class="loginLayer" id="loginLayer">
                <div class="loginLeft2">
                    <?php 
                    $display = 'none';
                    if($userId == '' || $isValidResponseUser == 'no') 
                        $display = 'block';
                    ?>
                        
                    <div class="" style="display:<?php echo $display;?>;">
                        <!-- Top Navigation Start -->
                       
                        <?php if($userId == '' || $isValidResponseUser=='no'){ ?>
                            <script>var showRegFormOnOAFPage = 2;</script>
                            <?php $this->load->view('shortRegistrationWidget');?>
                            
                        <?php }?>
                        <div class="clearFix"></div>
                        <!-- Top Navigation Ends -->

                        <!-- Login form is inside: Start -->
                        <div id="userLoginOverlay_ForAnA_loginform" style ="display:none;">

                            <form id="LoginForm_ForAnA" action="/user/Login/submit" onsubmit="if (validateLoginForAnA(this) != true) {
                                            return false;
                                        }
                                        ;
                                        new Ajax.Request('/user/Login/submit', {onSuccess: function(request) {
                                                javascript:showLoginResponseForOnline(request.responseText);
                                            }, evalScripts: true, parameters: Form.serialize(this)});
                                        return false;" method="post" novalidate="novalidate">

                                <input type="hidden" name="typeOfLoginMade" id="typeOfLoginMade" value="veryQuck" />
                                <input type = "hidden" name = "mpassword_ForAnA" id = "mpassword_ForAnA" value = ""/>
                                <ul>
                                    <li>
                                        <label>Login Email Id:</label>
                                        <div class="fieldBoxLarge">
                                            <input class="textboxLarge" type="text" id = "username_ForAnA" name = "username_ForAnA" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" minlength = "10" />
                                            <div style="display:none"><div class="errorMsg" id= "username_ForAnA_error"></div></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div id="passwordPlaceHolder_ForAnA">
                                            <label>Password:</label>
                                            <div class="fieldBoxLarge">
                                                <input type="password" id = "password_ForAnA" name = "password_ForAnA" validate = "validateStr" minlength = "5" maxlength = "20" required = "true" caption = "password" class="textboxLarge" />
                                                <div style="display:none"><div class="errorMsg" id="password_ForAnA_error"></div></div>
                                            </div>
                                        </div>
                                    </li>

                                    <div id="remembermePlaceHolder_ForAnA" style="display:none;">
                                    </div>

                                    <li>
                                        <div id="loginButtonPlaceHolder_ForAnA" class="paddLeft156">
                                            <input type="submit" value="Login" title="Login" class="attachButton" uniqueattr="LoginButtonOnlineCustompageLayer/<?= $department ?>"/> <br />
                                            <div class="clearFix spacer10"></div>        
                                            <a href="javascript:void(0);" onClick = "return switchForgotPassword('none', '');" title="Forgot Password">Forgot Password</a>
                                        </div>
                                    </li>

                                    <li>
                                        <div id="forgotPasswordButtonPlaceHolder_ForAnA" style="display:none;" class="paddLeft128">
                                            <input  id="forgotPasswordSubmitBtnAnA" type="button" value="Submit" title="Login" class="attachButton" onClick="return sendForgotPasswordMailForAnA();" /> 
                                            &nbsp; <a href="javascript:void(0);"  onClick = "return switchForgotPassword('', 'none');">Login</a>
                                            <div class="clearFix spacer10"></div>        
                                        </div>
                                    </li>

                                    <li>
                                        <div id="forgotPasswordLinkPlaceHolder_ForAnA" style="display:none;">
                                        </div>
                                    </li>
                                </ul>

                            </form>

                        </div>
                        <!-- Login form is inside: Ends -->


                        <!-- Registration form inside Starts-->
                        <div id="userLoginOverlay_ForAnA_registrationform" style="width: 325px; margin-left: 25px;<?php if($userId != '' && $isValidResponseUser == 'no') { echo 'display:none;'; }else{  echo 'display:block;'; } ?>">
                            <?php
                            if($userId == '' || $isValidResponseUser == 'no') {
                                //echo Modules::run('registration/Forms/LDB', NULL, 'registerResponseLPR', array('widget' => 'onlineForm', 'showPassword' => true, 'customCallBack' => 'showExternalFormLayer', 'referrer' => '#externalForm','trackingPageKeyId'=>$trackingPageKeyId));
                            }
                            ?>
                        </div>
                        <!-- Registration form inside Ends-->
                        <div class="clearFix"></div>
                    </div>
                    <div class="clearFix"></div>
                </div>
                <?php if (isset($courseId) && $courseId > 0): ?>
                    <?php if (!empty($instituteList) && is_array($instituteList)): ?>
                        <div class="loginRight2">
                            <div class="shadedBox" style="background-color:#f5f3f4">	 
                                <div class="recommendedItems3" style="margin-top:0">

                                    <?php
                                    $course = $courseObject;
                                    foreach ($instituteList as $inst_id => $instituteList_object):
                                        $inst_id_arry = explode("_", $inst_id);
                                        $inst_id = $inst_id_arry[0];
                                        //$course = $instituteList_object->getFlagshipCourse();
                                        if (!$course || !$instituteList_object->getId()) {
                                            continue;
                                        }

                                        $otherCourses = array();
                                        if(!empty($institute_features[$inst_id]['otherCourses'])) {
                                               $otherCourses = explode(",",$institute_features[$inst_id]['otherCourses']);
                                        }
                                        ?>
                                        <div class="recommendedItemsDetail" style="width:100%">
                                            <?php
                                                    $headerImg = $instituteList_object->getHeaderImage();
                                                    if(is_object($headerImg)){
                                                        $headerImageURL = getImageVariant($instituteList_object->getHeaderImage()->getUrl(), 3); 
                                                    }
                                                    if (!$headerImageURL)
                                                        $headerImageURL = '/public/images/recommendation-default-image.jpg';
                                                    ?>
                                             <div class="collegePic2 shadedBox"><img width="120" height="100" src="<?php echo $headerImageURL; ?>" /></div>
                                            <div class="collegeDtlsBox" style="width:100%; text-align:left">
                                                <div class="collegeDtlsBox" style="margin-top:0" id="otherCourses_<?php echo $institute_features[$inst_id]['id'];?>">
                                                    
                                                   

                                                    <div class="collegeDescription2">
                                                        <h2 style="margin-bottom:0; padding-bottom:0; border:0; font-size:16px"><a target="blank" href="<?php echo $instituteList_object->getURL(); ?>"><?php echo $instituteList_object->getName(); ?>,</a></h2> 
                                                        <span><?php echo $instituteList_object->getMainLocation()->getCityName() . "," . "India"?></span>

                                                        <?php if ($usp = $instituteList_object->getUSP()): ?><em><?php echo $usp; ?></em><?php endif; ?>

                                                        
            <?php if ($course): ?>
                                                            <div id="OF_INST_COURSE_DETAILS">

                                                                <h2 style="font-size:14px; line-height:18px; font-weight:bold; padding-bottom:3px; display:block"><a href="<?php echo $course->getURL(); ?>"><?php echo $course->getName(); ?></a></h2>
                                                                    <div class="clearFix"></div>

                                                                    
                                                                        <?php
                                                            $instInfo = getExtraInfo($course);
                                                            echo $instInfo['extraInfo']['duration']?$instInfo['extraInfo']['duration']:"";
                                                            echo $instInfo['extraInfo']['educationType']?", ".$instInfo['extraInfo']['educationType']:"";
                                                            if ($instInfo['courseLevel'] || $instInfo['courseCredential']){
                                                                echo ", ".$instInfo['courseLevel'];
                                                                if($instInfo['courseCredential']){
                                                                echo ' '.$instInfo['courseCredential'];
                                                                }
                                                            } ?>
                                                                    
                                                                

                                                                <div class="Fnt11 mb6">
                                                                    <?php
                                                                $approvalsArray = array();
                                                                $approvals = $course->getRecognition();
                                                                foreach($approvals as $approval) {
                                                                    if($approval->getName() != 'NBA'){
                                                                       $approvalsArray[] = $approval->getName().' Approved'; 
                                                                    }
                                                                }
                                                                echo implode(', ',$approvalsArray);   ?>
                                                                </div>

                                                                <?php
                                                                $exams = $course->getEligibilityMappedExams();                         
                                                                ?>
                                                                <div class="feeStructure2">
                                                                    <ul>                                                                        
                                                                        <?php if (count($exams) > 0) { ?>
                                                                        <li>
                                                                            <label>Eligibility : </label> 
                                                                            <span>
                                                                            <?php
                                                                            echo implode(',',$exams);
                                                                                ?>	
                                                                            </span>
                                                                        </li>
                                                                        <?php } ?>
                                                                        <?php
                                                                        if ($courseId == 12873) {
                                                                            echo "<li><label>Selection criteria : </label><span>MICAT and GE & PI</span></li>";
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>                                                     
                                                            
                                                        </div>

                                                        <?php endif; ?>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="clearFix"></div>
                                    </div>
                                    <div class="clearFix"></div>
                                </div>


                                <?php if(count($otherCourses) > 0) { ?>
                                 <div class="exapnadBox otherCoursesLink_<?php echo $institute_features[$inst_id][id];?>"><a  href="javascript:void(0);" onclick="getOnlineFormOtherCoursesDetails('<?php echo $institute_features[$inst_id][id];?>', 'externalclientpage')">+<?php echo count($otherCourses);?> More Courses</a></div>
                                <?php } ?>

                                <div class="eligibilityBox" id="eligibilityBox<?php echo $inst_id; ?>">
                                    <a class="viewCriteria" href="javascript:void(0)" onmouseover="if (typeof (OnlineForm != 'undefined')) {
                                    OnlineForm.displayAdditionalInfoForInstitute('block', '<?php echo 'eligibilityLayerWrap' . $inst_id . $inst_id_arry[1]; ?>');
                                    $('eligibilityBox<?php echo $inst_id; ?>').style.zIndex = '2';
                                }" onmouseout="if (typeof (OnlineForm != 'undefined')) {
                                            OnlineForm.displayAdditionalInfoForInstitute('none', '<?php echo 'eligibilityLayerWrap' . $inst_id . $inst_id_arry[1]; ?>');
                                            $('eligibilityBox<?php echo $inst_id; ?>').style.zIndex = '0';
                                        }">View eligibility criteria</a>
                                    <!--Eligibility Layer Starts here-->
                                    <div class="eligibilityLayerWrap" style="display:none; left:-35px" id="eligibilityLayerWrap<?php echo $inst_id . $inst_id_arry[1]; ?>">
                                        <span class="eligibilityPointer"></span>
                                        <div class="applylayerContent">
                                            <ul>
                                                <?php if ($institute_features[$inst_id]['min_qualification']): ?>
                                                    <li>
                                                        <label>Min Qualification:</label>
                                                        <span><?php echo $institute_features[$inst_id]['min_qualification']; ?></span>
                                                    </li>
                                                    <?php endif; ?>
                                                    <?php if ($institute_features[$inst_id]['fees']): ?>
                                                    <li>
                                                        <label>Form Fees:</label>
                                                        <?php if ($institute_features[$inst_id]['instituteId'] == '28230' || $institute_features[$inst_id]['instituteId'] == '36454') { ?>
                                                            <span <?php if ($institute_features[$inst_id]['discount']): ?>class="line-through"<?php endif; ?>>Rs.<?php echo $institute_features[$inst_id]['fees']; ?> till 15<sup>th</sup> Feb 2016 and Rs. 1,500 from 16<sup>th</sup> Feb 2016 till 24<sup>th</sup> Feb 2016.</span>
                                                    <?php } else { ?>
                                                            <span <?php if ($institute_features[$inst_id]['discount']): ?>class="line-through"<?php endif; ?>>Rs.<?php echo $institute_features[$inst_id]['fees']; ?></span>
                                                    <?php } ?>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($institute_features[$inst_id]['discount']): ?>
                                                    <li>
                                                        <label>Pay only:</label>
                                                        <span><strong>Rs.<?php echo ($institute_features[$inst_id]['fees'] - $institute_features[$inst_id]['discount'] * $institute_features[$inst_id]['fees'] / 100) ?></strong></span>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($institute_features[$inst_id]['exams_required']): ?>
                                                    <li>
                                                        <label>Exams Accepted:</label>
                                                        <span><?php echo $institute_features[$inst_id]['exams_required']; ?></span>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($institute_features[$inst_id]['courses_available']): ?>
                                                    <li>
                                                        <label>Courses Available:</label><br />
                                                        <span><?php echo $institute_features[$inst_id]['courses_available'] ?></span>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($institute_features[$inst_id]['last_date']): ?>
                                                    <li><div class="lastDateNotify">Last Date to Apply: <span><?php echo date('d M, Y', strtotime($institute_features[$inst_id]['last_date'])); ?></span></div>

                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                            <div class="clearFix"></div>
                                        </div>
                                    </div>
                                    <!--Eligibility Layer Ends here-->
                                </div>


                            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
                <div class="clearFix"></div>
            </div>
            <!--Login Layer Ends here-->



        </div>
    </div>
    <div class="clearFix"></div>
</div>
<script>
    /*if ('<?php //echo $form_is_expired ?>' == 'expired') {
        alert('This form has been expired');
        var str = window.location.pathname;
        var redirectUrl = '';
        if(str.lastIndexOf('mba') > 0) {
            redirectUrl = '/mba/resources/application-forms';
        }
        else {
            redirectUrl = '/college-admissions-engineering-online-application-forms';
        }
        window.location = '<?php //echo SHIKSHA_HOME; ?>'+'/'+redirectUrl;
    }*/
    //addOnBlurValidate($('RegistrationForm_ForAnA'));
    //addOnBlurValidate($('LoginForm_ForAnA'));
    var OnlineForm = {};
    OnlineForm.displayAdditionalInfoForInstitute = function(style, divId) {
        if ($(divId)) {
            $(divId).style.display = style;
        }
    }


    if (<?php echo (isset($_REQUEST['login'])) ? 'true' : 'false'; ?>)
        showHideLoginOnline('', 'none');
</script>
