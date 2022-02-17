<?php
    if($registrationDomain == 'studyAbroad') {
?>
<div id="twoStepRegistrationLayer" class="abroad-layer" style="width:710px; position: static; left: auto">
    <div class="abroad-layer-head clearfix">
        <div class="abroad-layer-logo flLt"><i class="layer-logo"></i></div>
        <a id="close-two-step-layer" href="javascript:void(0);" onclick="registrationOverlayComponent.hideOverlay(); return false;" class="common-sprite close-icon flRt"></a>
    </div>
    <div class="abroad-layer-content clearfix">
    <?php if(isset($errorMsg)):?>
        <br><br><br><br>This response is <b><?php echo $errorMsg;?></b>.<br><br>
    <?php else:?>
    	<div class="abroad-layer-title clearfix">
            <span class="flLt"><?php if(empty($layerHeading)) { echo 'Complete registration form'; } else { echo $layerHeading; } ?></span>
	    
	</div>
        <div class="signUp-tabs clearfix">
            <ul>
                <li id="li_stepOne" class="active"><i class="common-sprite marked-icon"></i><a href="javascript:void(0);">Basic Information <span>(Step1)</span></a><i class="common-sprite steps-pointer"></i></li>
                <li id="li_stepTwo" class=""><a href="javascript:void(0);">Education Interest <span>(Step2)</span></a></li>
            </ul>
        </div>
        <?php echo Modules::run('registration/Forms/offlineLDB',$userData,"studyAbroadRevamped",'twoStepRegister'); ?>
    <?php endif;?>
    </div>
    <script>
        if(document.getElementsByName('firstName').length) document.getElementsByName('firstName')[0].setAttribute("disabled","disabled");
        if(document.getElementsByName('lastName').length) document.getElementsByName('lastName')[0].setAttribute("disabled","disabled");
        if(document.getElementsByName('mobile').length) document.getElementsByName('mobile')[0].setAttribute("disabled","disabled");
    </script>
</div>
<?php
    }else{
        ?>
<div class="layer-outer" style="width:420px; font-family: Trebuchet MS;">
    <div class="layer-title">
        <a class="close" title="Close" href="#" onclick="shikshaUserRegistration.closeRegisterFreeLayer(); return false;"></a>
        <div class="title"><?php echo $layerTitle ? $layerTitle : "Add Details"; ?></div>
    </div>
    <div class="layer-contents">
    <?php if(isset($errorMsg)):?>
        <br><br><br><br>This response is <b><?php echo $errorMsg;?></b>.<br><br>
    <?php else:?>
    
        <div style="float:left; font-size:14px;"><?php echo $layerHeading ? $layerHeading : ""; ?></div>
        <div style="float:right;"></div>    
        <div class="clearFix"></div>
    
        <ul class="find-inst-form" style="margin: 10px 0 15px 0;">
        </ul>
    
        <div id="registerFreeFormIndia" style="width:300px; margin-left: 15px;">
		<?php echo Modules::run('registration/Forms/offlineLDB',$userData,NULL,'registerFree',$formCustomData); ?>
	</div>
	<input type="hidden" id="registerFreeHasCallback" value="<?php echo $hasCallback; ?>" />
        <div style="clear:both;"></div>
    
    <?php endif; ?>
    </div>
    <script>
        if(document.getElementsByName('firstName').length) document.getElementsByName('firstName')[0].setAttribute("disabled","disabled");
        if(document.getElementsByName('lastName').length) document.getElementsByName('lastName')[0].setAttribute("disabled","disabled");
        if(document.getElementsByName('mobile').length) document.getElementsByName('mobile')[0].setAttribute("disabled","disabled");
    </script>
</div>

<?php
    }
?>