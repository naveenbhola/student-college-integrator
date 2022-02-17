
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("studyAbroadCommon"); ?>" type="text/css" rel="stylesheet" />
<?php if(!empty($regFormId)){?>
 <div class="abroad-layer" style="width:595px; position:static; background:#f8f8f8;">
        <div class="abroad-layer-head clearfix">
            <div class="abroad-layer-logo flLt"><i class="layer-logo"></i></div>
            <a id="close-two-step-layer" href="javascript:void(0);" style="display:none" onclick="window.location.reload()" class="common-sprite close-icon flRt"></a>
        </div>
        
        <div class="abroad-layer-content clearfix" style="padding:0">
            <?php $this->load->view('registration/common/OTP/abroadOTPVerification'); ?>
        </div>
 </div>

<?php }
else{
    $this->load->view('registration/common/OTP/singleSignUpOTPVerification');
}
?>