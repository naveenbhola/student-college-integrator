<?php
    $call = 0;
    $widget = "listingPageRightNational";
    $formCustomData['widget'] = $widget;
    $formCustomData['customCallBack'] = 'showListingPageRecommendationLayer';
    $formCustomData['tracking_keyid'] = $pageType == 'course' ? DESKTOP_NL_LP_COURSE_RIGHT_DEB : DESKTOP_NL_LP_INST_RIGHT_DEB;
?>
<?php if($validateuser == "false"):?>
<div class="brochure-form collapsed" style="margin-bottom : 5px !important;">
	<h5>
		<?php
			$downloadBrochureTitle = "";
			if($pageType == 'course')
			{
				$downloadBrochureTitle = "Download brochure for this course";
			}
			else
			{
				$downloadBrochureTitle = "Download E-Brochure";
			}
		?>
		<div class="icon-wrap"><i class="sprite-bg download-icon"></i></div><p><?=$downloadBrochureTitle?></p>
		<div class="sprite-bg pointer"></div>
	</h5>
	<div class="form-wrap" style="display: none;">
		<p class="font-14">Share your details with the <?php echo ucfirst($collegeOrInstituteRNR);?></p>
                
                <?php echo Modules::run('registration/Forms/LDB',NULL,'registerResponseLPR',$formCustomData); ?>
		
		<div class='clearFix'></div>
	</div>
</div>
<?php 
else:
$firstname = $validateuser[0]['firstname'];
$mobile = $validateuser[0]['mobile'];
$cookiestr = $validateuser[0]['cookiestr'];
?>
<div class="brochure-form <?=$widget?>">		
		<h5 style="cursor: default" id="defaultHeading_<?=$widget?>" style="display:none;" <?php if($pageType == 'course'){echo 'class="defaultHeading_course"';} ?>><div class="icon-wrap"><i class="sprite-bg download-icon"></i></div> <p>Want to know more about <?=html_escape($institute->getName())?>?</p><div class="sprite-bg pointer"></div></h5>
                <h5 style="cursor: default" id="finalHeading_<?=$widget?>" style="display:none;" <?php if($pageType == 'course'){echo 'class="finalHeading_course"';} ?>>
						 <?php if($course_reb_url ||
							  ( $pageType == 'institute')):?>
						 <div class="icon-wrap">
						   <i class="sprite-bg download-icon-done"></i>
						 </div>
						   <p>
							E-Brochure successfully mailed
							</p>
						<?php else:?>
						    <div class="icon-wrap"><i class="sprite-bg download-icon"></i></div>
						    <p>
							Sorry, brochure is currently not available
							</p>
						<?php endif;?>
						<!--<div class="sprite-bg pointer"></div>-->
					</h5>
		
		<div class="form-wrap">
                    <form id="form_<?=$widget?>" onsubmit="processResponseForm('<?=$widget?>','showListingPageRecommendationLayer'); return false;" novalidate>
                        <input type="hidden" value="<?=$institute->getId()?>" id="institute_id_<?=$widget?>">
                        <input type="hidden" value="<?=html_escape($institute->getName())?>" id="institute_name_<?=$widget?>">
                    </form>
                    <ul class="form-list" style="margin-top:0">
                <?php 
                    echo Modules::run('registration/Forms/LDB',NULL,'registerResponseLPR',$formCustomData); 

                    if($responseCount >= 10){
                ?>
                        <li style="color:#444">
                                <?=$responseCount?> people have already applied
                        </li>
                <?php
                    }
                ?>
                    </ul>

                    <div class="clearFix"></div>
		</div>
		<div class="clearFix"></div>
		
		<?php if($widget == 'listingPageRightNational'){ ?> <div class="listing-girl"></div> <?php } ?>
</div>
<?php endif;?>
<script>
var call = <?=$call?>;
if(call){
	recordCallWidgetLoad(<?=$institute->getId()?>,<?=$course?$course->getId():0?>,'<?=$widget?>');
}

var OTPVerification = <?=empty($OTPVerification) ? 0 : $OTPVerification?>;
var ODBVerification = <?=empty($ODBVerification) ? 0 : $ODBVerification?>;

function authenticateUser(widget) {
	if (OTPVerification || ODBVerification) {
		if(validateShortForm(widget, OTPVerification, ODBVerification)) {
			showVerificationLayer(widget, OTPVerification, ODBVerification, 1);
		}
	}
	else {
	    processData(widget);
	}
}
	
function processData(widget) {
	processResponseForm(widget,'showListingPageRecommendationLayer', OTPVerification, ODBVerification);
}

var localityArray = <?=json_encode($localityArray)?>;
var course_reb_url = '<?php echo $course_reb_url;?>';
</script>
