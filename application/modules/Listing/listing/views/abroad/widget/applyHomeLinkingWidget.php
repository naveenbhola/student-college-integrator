<div class="help-widget" style="<?php if($listingType=='university'){ echo "margin-top:0px;margin-bottom:15px;"; }?>">
    <strong><?php echo ($applyLinkWidgetTitle=='')?'Need help in Applying?':$applyLinkWidgetTitle;?></strong>
    <p><?php echo ($applyLinkWidgetDesc=='')?'Get end to end help from shiksha counselors to apply to colleges as per your profile.':$applyLinkWidgetDesc;?></p>
    <a class="butn gaTrack" gaparams="<?php echo $gaParams;?>" href="<?php echo SHIKSHA_STUDYABROAD_HOME.'/apply'; ?>">Find out more <span>></span></a>
</div>