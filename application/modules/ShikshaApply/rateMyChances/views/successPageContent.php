<div class="content-wrap clearfix" >
    <div class="shiksha-apply-box">
        <div class="shiksha-apply-section clearfix">
            <p class="submitted-head"><i class="apply-sprite apply-submitted-icon"></i> Your profile has been sucessfully submitted</p>
            <p>A shiksha counsellor will get back to you within 5 working days. 
            <?php
            $remainingCourseCount = ABROADRMCLIMIT - $courseCount;
            if($remainingCourseCount >0){?>
            You can rate your chances at <?= $remainingCourseCount;?> more  <?= ($remainingCourseCount>1)?"colleges":"college" ?>
            <?php }else{?>
            You cannot check your chances on more colleges.To check your chances on more colleges, contact our counselling team at  <a href="mailto:applyabroad@shiksha.com">applyabroad@shiksha.com</a>
            <?php }?>
            </p>
        </div>
    </div>
</div>
<?php echo Modules::run('applyHome/ApplyHome/applyHomePage',true); ?>
<div class="back-link clearfix" style="padding-bottom:50px;">
<a style="margin:10px 0 0 20px; display:inline-block;" href="<?=$returnUrl?>">&lt; Go Back to <?=($returnPageTitle)?></a>
<br/><br/><br/><br/><br/><br/><br/><br/>
</div>
<script>

 var rmcShowSuccessPageCookieValue = '<?php echo $rmcShowSuccessPageCookieValue; ?>'
 var rmcRedirect = '<?php echo $rmcRedirect; ?>'
 var skipReloadFlag = 'rmcSuccessPageSkipReload';
 var pageIdentifier = 'rmcSuccessPage';
</script>