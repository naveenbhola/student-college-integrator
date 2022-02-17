<div class="layer-outer outLaySt" id="compare_login_layer_<?php echo $regFormId; ?>">
    <div class="layer-title layTitSt" id="layer_title_<?php echo $regFormId; ?>">
      <a class="close closeSt" title="Close" id="close" href="javascript:void(0);" onclick="shikshaUserRegistration.closeRegisterFreeLayer(); return false;"></a>

      <div  class="title" id="registrationTitle_<?php echo $regFormId?>"><?php echo $customData->title; ?></div>
  </div>
  <div class="layer-contents layContSt" id="layer-contents">

     <div style="float:right;"><a onclick="shikshaUserRegistration.closeRegisterFreeLayer(); shikshaUserRegistration.showLoginLayer(true,'','',{'widgetName':'isCustomForm','trackingPageKeyId':'<?php echo $trackingPageKeyId;?>'}); return false;" class="loginSt" href="javascript:void(0);">Existing Users, Sign In</a></div>    

     <div class="clearFix"></div>

     <div id="registerFreeFormIndia" class="registration-left-col rfFormSt">
        <?php echo Modules::run('registration/Forms/LDB',NULL,'registerFree',$formCustomData); ?>
    </div>
    <div class="registration-form">
       <div class="registration-right-col flRt rRCSt">
          <div class="reg-shortlist-title"><?php echo $customData->subTitle; ?></div>
          <ul class="compare-college-list">
             <?php foreach($customData->points as $key=>$value){ ?>
               <li><i class="common-sprite green-tick-mark"></i><?php echo $value; ?></li>
               <?php } ?>
           </ul>
       </div>
       <div class="clearFix"></div>
   </div>
   <input type="hidden" id="registerFreeHasCallback" value="<?php echo $hasCallback; ?>" />
   <div style="clear:both;"></div>
</div>
<div style="padding: 0 0 15px 15px;">
<?php $this->load->view('registration/common/OTP/userOtpVerification'); ?>
</div>
</div>