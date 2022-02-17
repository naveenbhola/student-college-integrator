<?php
    if($registrationDomain == 'studyAbroad') {
?>
    <div id="twoStepRegistrationLayer" class="abroad-layer" style="width:740px; position: static; left: auto">
        <div class="abroad-layer-head clearfix">
            <div class="abroad-layer-logo flLt"><i class="layer-logo"></i></div>
            <a id="close-two-step-layer" href="javascript:void(0);" onclick="registrationOverlayComponent.hideOverlay(); return false;" class="common-sprite close-icon flRt"></a>
        </div>
        <div class="abroad-layer-content clearfix" style="padding-left:0">
            <div class="abroad-layer-title clearfix" style="margin:0px 0 10px 10px">
                <span class="flLt"></span>
                <?php if(!$userData['userId']) { ?>
                <p class="tar" style="font-size:11px;"><a id="twoStepLoginButton" href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('ABROAD_PAGE', 'existingUserLogin'); registrationOverlayComponent.hideOverlay(); loadStudyAbroadForm({'isStudyAbroadPage':1},'/registration/Forms/showLoginLayer','loginFormContainer'); return false;">Already registered?</a></p>
                <?php } ?>

                <div class="abroad-step-title">
                    <p class="flLt"><?php if(empty($layerHeading)) { echo 'Register to get started'; } else { echo $layerHeading; } ?></p>
                    <div class="signUp-tabs clearfix">
                        <ul>
                            <li id="li_stepOne" class="active"><i class="common-sprite marked-icon"></i><a href="javascript:void(0);" style="cursor: default;">Basic Information</a><i class="common-sprite steps-pointer"></i></li>
                            <li id="li_stepTwo" class=""><a href="javascript:void(0);" style="cursor: default;">Education Interest</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="flLt reg-benefit-col">
                <strong>Benefits of registering</strong>
                <div class="clearwidth">
                    <div class="benefit-list clearwidth">
                        <strong><i class="common-sprite benefit-mark"></i>Free download</strong>
                        <ul>
                            <li>Thousands of course brochures</li>
                            <li>Student guides for countries & exams</li>
                        </ul>
                    </div>
                    <div class="benefit-list clearwidth">
                        <strong><i class="common-sprite benefit-mark"></i>Get personalized help</strong>
                        <ul>
                            <li>To find the right colleges for you</li>
                            <li>By shortlisting and revisiting courses</li>
                        </ul>
                    </div>
                    <div class="benefit-list clearwidth">
                        <strong><i class="common-sprite benefit-mark"></i>Stay up to date with</strong>
                        <ul>
                            <li>Admission deadlines</li>
                            <li>Latest on visa rules & scholarships</li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php echo Modules::run('registration/Forms/LDB',"studyAbroadRevamped",'twoStepRegister'); ?>
        </div>
    </div>
<?php
    }
?>