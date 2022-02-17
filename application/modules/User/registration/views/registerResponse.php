<?php
//$data = array();
$formCustomData['widget'] = $widget;
$formCustomData['buttonText'] = $buttonText;
$formCustomData['regFormId']=$regFormId;
$formCustomData['trackingPageKeyId']=$trackingPageKeyId;
$this->load->view('common/userAuthentication', $formCustomData);
$layerHeading = ($multkeyname=="RANKING_PAGE"?"Request":"Download")." E-Brochure";
$formCustomData['tracking_keyid']=$tracking_keyid;
?>
<style type="text/css">
    .modalDialog_contentDiv{left: 383px !important;}
</style>
<div id="reg-layer" class="layer-outer" style="width:610px; padding:0px; position:relative; font-family: Trebuchet MS; <?php if(!(count($instituteCourses) > 1 && $widget == 'listingPageBellyNationalForInstitute')) echo 'display: block;'; ?>">
    <div class="layer-title d-ebroucher">
        <a class="close" title="Close" id="close" href="#" onclick="shikshaUserRegistration.closeRegisterFreeLayer(); return false;"></a>
        <div class="title" id="registrationTitle_<?php echo $regFormId; ?>" ><?php echo $layerTitle ? $layerTitle : ($multkeyname=="RANKING_PAGE"?"Request":"Download")." E-Brochure"; ?></div>
    </div>

    <div class="layer-contents" id="layer-contents" style="padding:10px 15px 15px;">
        <!--<div style="float:left; font-size:14px;"><?php echo $layerHeading ? $layerHeading : "New Users, Register Free!"; ?></div>-->
        <div style="float:left;margin-bottom:15px">
            <?php if($userData == false): ?>
                <a onclick="shikshaUserRegistration.closeRegisterFreeLayer(); shikshaUserRegistration.showLoginLayer(); return false;" href="javascript:void(0);">Existing Users, Sign In</a>
            <?php endif; ?>
        </div>    
        <div class="clearFix"></div>

        <div class="registration-left-col flLt">
                <?php echo Modules::run('registration/Forms/LDB',NULL,'registerResponse',$formCustomData); ?>
        </div>
        <div class="registration-right-col flRt">
            <div class="downld-ebroucher-title">A typical brochure contains </div>
            <div class="shortlist-sec">
                <div class="shortlist-col">
                    <i class="downloadebroucher-sprite course-inf-icon"></i>
                    <div class="shortlist-details">
                        <strong class="broucher-title">Course Information</strong>
                        <p class="broucher-desc">Eligibility admission process, syllabus and more</p>
                    </div>
                </div>
                <div class="shortlist-col">
                    <i class="downloadebroucher-sprite reson-join-icon"></i>
                    <div class="shortlist-details">
                        <strong class="broucher-title">Top reasons to join the college</strong>
                        <p class="broucher-desc">Recognition, placements, USP, etc.</p>
                    </div>
                </div>
                <div class="shortlist-col">
                    <i class="downloadebroucher-sprite college-inst-icon"></i>
                    <div class="shortlist-details">
                        <strong class="broucher-title">College Infrastructure/Facilities </strong>
                        <p class="broucher-desc">Know about facilities like library, hostels and more</p>
                    </div>
                </div>
                <div class="shortlist-col last">
                    <i class="downloadebroucher-sprite faculty-details-icon"></i>
                    <div class="shortlist-details">
                        <strong class="broucher-title">Faculty Details</strong>
                        <p class="broucher-desc">Know about faculty profiles, departments, etc</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearFix"></div>
    </div>
    <div style="padding: 0 0 15px 15px;">
        <?php $this->load->view('registration/common/OTP/userOtpVerification'); ?>
    </div>
</div>

<?php if($hasCallback) { ?>
    <input type="hidden" id="registerFreeHasCallback" value="<?php echo $hasCallback; ?>" />
<?php } ?>

<script type="text/javascript">
var isRankingPage = <?php if($multkeyname=="RANKING_PAGE"){ echo 1; } else { echo 0; } ?>;
var isCategoryPage = <?php if($multkeyname == 'CategoryPageApplyRegisterButton') { echo 1; } else { echo 0; } ?>;
</script>