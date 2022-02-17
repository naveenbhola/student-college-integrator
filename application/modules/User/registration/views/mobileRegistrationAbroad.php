<?php
$registerTab   = '';
$registerBlock = '';
$loginTab      = '';
$loginBlock    = '';
if($layerShow == 'register'){
    $registerTab = 'active';
    $loginBlock  = 'style="display:none"';
}else{
    $loginTab      = 'active';
    $registerBlock = 'style="display:none"';
}
$conversionType = $MISTrackingDetails['conversionType'];
$keyName = $MISTrackingDetails['keyName'];
     if($conversionType=='downloadGuide'){
        $heading = "Download Guide";
        $text = "35,447 guides were downloaded on Shiksha study abroad last year.<br>Get in-depth details on this topic.";
        $img = "/public/images/SASingleSignup/download-guide-image_03.jpg";
        $alt = "download-guide";
     } else if($conversionType=='Course shortlist'){
        $heading = "Save courses";
        $text = "52,423 courses were saved on Shiksha study abroad last year. Save to create a personalized list to access later from any device.";
        $img = "/public/images/SASingleSignup/Save-Course_03.jpg";
        $alt = "course-shortlist";
     } else if($conversionType=='commentPost' || $conversionType=='replyPost'){
        $heading = "Signup to add a comment";
        $text = "Join the largest community of study abroad aspirants in India. More than 1,45,000 students signed up last year on Shiksha to study abroad.";
        $img = "/public/images/SASingleSignup/Mobile-Comment_03.jpg";
        $alt = "post-comment";
     } else if($conversionType=='questionPost'){
        $heading = "Signup to submit a question";
        $text = "Join the largest community of study abroad aspirants in India. More than 1,45,000 students signed up last year on Shiksha to study abroad.";
        $img = "/public/images/SASingleSignup/Mobile-Comment_03.jpg";
        $alt = "post-question";
     }else if($conversionType=='compare'){
        $heading = "Compare courses";
        $text = "1,45,000+ students & parents signed up on Shiksha study Abroad last year. Make an informed decision to about your abroad education.";
        $img = "/public/images/SASingleSignup/Compare_image_03.jpg";
        $alt = "compare-course";
     } else if($conversionType=='response'){ //currently working for only scholarship response
        if($responseAction==='schr_db'){
            if(empty($userFormData)){
                $heading = "Download Scholarship Brochure";
            }
            $text = "1,42,770 brochures were downloaded on Shiksha study abroad last year. Get details about scholarship eligibility & application details.";
            $img = "/public/images/SASingleSignup/Download-Brochure_03.jpg";
            $alt = "download-brochure";
        }else{
            if(empty($userFormData)){
                $heading = "Signup to apply for Scholarship";
            }

            $text = '1,45,000+ students and parents signed up last year on Shiksha to study abroad. Make an informed decision about your abroad education.';
            $img = "/public/images/SASingleSignup/Apply.png";
            $alt = "apply-now";
        }
     }
     else{
        $text = "1,45,000+ students & parents signed up on Shiksha study Abroad last year. Make an informed decision to about your abroad education.";
        $img = "/public/images/SASingleSignup/Signup-image_03.jpg";
        $alt = "signup";
     }
?>

<div id = "registerInner">
    <div class="layer-header">
            <a id="regn-back-btn" href="javascript:void(0);" data-transition="slide" class="back-box" onclick="goBackToReferrer();"><i class="sprite back-icn"></i></a>
            <?php if(!empty($userFormData)){ ?>
                <p id="registrationLayerHeaderText">Update your profile</p>
            <?php }else { ?>
            <p id="registrationLayerHeaderText"><?php echo (empty($layerHeading))?"Register to get started":$layerHeading?></p>
            <?php } ?>
    </div>
    <section class="content-wrap clearfix" data-enchance="false" >
        <nav class="tabs">
            <ul>
                <?php if(empty($userFormData)){ ?>
                <li class="<?=$registerTab;?> reg-tab" id="reg-tab"><a href="javascript:void(0);" onclick="showRegistrationFormTab(this);">Register To Start</a></li>
                <li id="login-tab" class="login-tab <?=$loginTab;?>"><a href="javascript:void(0);" id="abroadLoginTab" onclick="showLoginFormTab(this);">Already Registered?</a></li>
                <?php } ?>
            </ul>
        </nav>

        <div id="register-form" class = "register-form" <?=$registerBlock;?>>
            <div style="margin:0 5px;">
                <div class="counslor-evaluation-sec" id="joinShikshaLabel">
                <div class="clearfix" style="margin:5px 0 5px 5px;"><strong class="font-13"><?=$heading;?></strong></div>
                    <p class="evaluate-title"><?php echo $text;?></p></br>
                    <div style="width:100%; text-align:center">
                        <img src="<?=$img;?>" auto alt="<?=$alt;?>">
                    </div>
                </div>
            </div>
            <div style="margin:10px 0px 15px 10px; float: left; width:100%; color:#333; padding-bottom:5px; border-bottom:1px solid #ccc; font-size:12px;" class="wrap-title">
                    Tell Us About Yourself
            </div>
            <?php
            if($forShortlist)
            {
                $formCustomData['listingTypeIdForBrochure'] = $listingTypeIdForBrochure;
                $formCustomData['shortlistSource'] = $shortlistSource;
                $formCustomData['forShortlist'] = $forShortlist;
            }
            else if($scholarshipResponse){
                $formCustomData['listingTypeIdForBrochure'] = $listingTypeIdForBrochure;
                $formCustomData['listingTypeForBrochure'] = 'scholarship';
                $formCustomData['scholarshipResponse'] = true;
                $formCustomData['scholarshipResponseSource'] = $scholarshipResponseSource;
                $formCustomData['responseAction'] = $responseAction;
                $formCustomData['userFormData'] = $userFormData;
            }
            $formCustomData['conversionType'] = $conversionType;
            $formCustomData['keyName'] = $keyName;
            $formCustomData['contentId'] = $contentId;
            $formCustomData['contentType'] = $contentType;
            $formCustomData['trackingPageKeyId'] = $trackingPageKeyId;
            $formCustomData['educationDetails'] = $educationDetails;
            echo Modules::run('registration/Forms/LDB',"studyAbroadRevamped",'mobileRegistrationAbroad',$formCustomData); ?>
        </div>
        <?php $this->load->view('registration/common/OTP/abroadMobileOTP'); ?>
        <div <?=$loginBlock;?> id="login-form" class = "login-form">
            <?php //$this->load->view("registration/loginStudyAbroadMobile"); ?>
        </div>
    </section>
</div>
