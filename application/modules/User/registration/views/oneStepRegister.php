<?php if($registrationDomain == 'studyAbroad') {
    
    $showAlreadyRegisterLink = true;
    if($layerHeading=='hideAlreadyRegisterLink'){
        unset($layerHeading);
        $showAlreadyRegisterLink = false;
    }
    ?>
    
    <div class="abroad-layer" id="singleSignUpSaveCourseForm" style="width:620px; background:#f8f8f8 !important">
        <div class="abroad-layer-head clearfix">
            <div class="abroad-layer-logo flLt"><i class="layer-logo"></i></div>
            <a id="close-two-step-layer" href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('ABROAD_PAGE', 'exitSignUp'); <?php echo ($fileName !=''?'removeFile('.$trackingPageKeyId.');return false;':''); ?> registrationOverlayComponent.hideOverlay(); return false;" class="common-sprite close-icon flRt"></a>
        </div>

        <div class="abroad-layer-content clearfix" style="padding:0">

            <div class="abroad-step-title" style="background: none !important;">
                <?php if(in_array($trackingPageKeyId,array(920,921,922,990,991) )){ ?>
                <div class="upload-col">
                    <span class="filename"><?php echo (strlen($fileName)>30 ? (substr($fileName,0,26).'...'):$fileName).' ('.$fileSize.'Kb)'; ?></span> <span class="fileRmv" onclick="removeFile();">x</span>
                    <div>Upload successful</div>
                </div>
                <?php }?>
                <p><?php if(empty($layerHeading)) { echo 'Register to get started'; } else { echo $layerHeading; } ?></p>
            </div>
            <div class="registered-title clearwidth">
                <strong class="flLt">Tell us about yourself</strong>
                <?php if(!$userData['userId'] && $showAlreadyRegisterLink) { ?>
                    <a id="twoStepLoginButton" class="flRt font-11" href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('ABROAD_PAGE', 'existingUserLogin'); registrationOverlayComponent.hideOverlay(); loadStudyAbroadForm({'isStudyAbroadPage':1},'/registration/Forms/showLoginLayer','loginFormContainer',<?php echo $trackingPageKeyId?>); return false;">Already registered?</a></p>
                <?php } ?>
            </div>
            <?php
                $data['trackingPageKeyId'] = $trackingPageKeyId;
            ?>
        <?php echo Modules::run('registration/Forms/LDB',"studyAbroadRevamped",'oneStepRegister',$data); ?>

        </div>
        <div >
            <?php $this->load->view('registration/common/OTP/abroadOTPVerification'); ?>
        </div>
    </div>
<?php } ?>