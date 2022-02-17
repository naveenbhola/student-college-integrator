<?php  $conversionType = $MISTrackingDetails['conversionType'];
       $keyName = $MISTrackingDetails['keyName'];
?>



<div class="sp-blk-c">
    <div class="sp-btn">
      <?php
        $anchorColorClass = '';
        if($keyName == 'rateMyChance'){
          $anchorColorClass  = 'teal';
        }
      ?>
      <a href="javascript:void(0)" id="signup" tabindex=5 class="button-style big-button <?php echo $anchorColorClass;?>" regFormId="<?php echo $regFormId;?>">
      <?php
            if($isUserloggedIn =="yes"){
              echo "Update your profile";
            }else if($conversionType=='response'){
              if($keyName == 'rateMyChance'){
                echo "Rate My Chances";
              }else if($keyName == 'scholarshipApply'){
                echo "Signup and Apply";
              }else{
              echo "Download Brochure";
              }
            } else if($conversionType=='downloadGuide'){
              echo "Download Guide";
            } else if($conversionType=='Course shortlist'){
              echo "Save Course";
            } else{
              echo "Sign Up";
            }
      ?>
      </a>
      <!--<p>By signing up you are agreeing to the <a dataurl="<?php /*echo SHIKSHA_STUDYABROAD_HOME; */?>/shikshaHelp/ShikshaHelp/termCondition" href="javascript:void(0);" class="trm-txt">terms of services</a> and <a dataurl="<?php /*echo SHIKSHA_STUDYABROAD_HOME; */?>/shikshaHelp/ShikshaHelp/privacyPolicy" href="javascript:void(0);" class="trm-txt">privacy policy</a>.</p>-->
    </div>
</div>
