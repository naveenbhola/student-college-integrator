	<div class="header-unfixed">
		<div class="layer-header">
			<a href="<?=($returnUrl)?>" class="back-box"><i class="sprite back-icn"></i></a> <p style="text-align:center"><?=($returnPageTitle)?></p>
		</div>
	</div>
	<div class="rmc-form-submit-sec">
		<i style="top:2px;" class="sprite sent-bro-icon flLt"></i>
		<div class="rmc-form-submit-info">
			<p>A shiksha counselor will get back to you in 5 working days</p>
			<?php
				$remainingCourseCount = ABROADRMCLIMIT - $courseCount;
				if($remainingCourseCount >0 && $remainingCourseCount <=2){?>
			<p>You can rate your chances on <?= $remainingCourseCount;?> more  <?= ($remainingCourseCount>1)?"colleges":"college" ?></p>
			<?php }else if($remainingCourseCount === 0){ ?>
			<p>You cannot check your chances on more colleges. To check your chances on more colleges, contact our counselling team at  <a href="mailto:applyabroad@shiksha.com">applyabroad@shiksha.com</a></p>
			<?php }?>
		</div>
	</div>
	<div class="clearfix"></div>
       <?php echo Modules::run('applyHomePage/ApplyHomePage/applyHomePage',true); ?> 
		<div style="width:100%; float:left; padding:10px" class="clearfix">
			<a id="rmcBackLink" href="<?=$returnUrl?>" style="display: inline-block;">
				<i class="sprite back-icn" style="float: left;"></i>
				<div style="margin-left: 20px;">Return to <?=($returnPageTitle)?></div>
			</a>
		</div>
</div>
<script>

 var rmcShowSuccessPageCookieValue = '<?php echo $rmcShowSuccessPageCookieValue; ?>'
 var rmcRedirect = '<?php echo $rmcRedirect; ?>'
 
 function showExamEMailDetails(obj){
       $j(obj).hide();
       var userId = '<?php echo $userObj->getId(); ?>';
       var courseId = '<?php echo $courseId; ?>';
       var ajaxURL = "/rateMyChancePage/rateMyChance/saveUserEnrolmentForCounseling";
        $j.ajax({
            type: 'POST',
            url : ajaxURL,
            data:{'userId':userId,'courseId':courseId}
        });
       $j('.score-details').show();
   }
</script>
