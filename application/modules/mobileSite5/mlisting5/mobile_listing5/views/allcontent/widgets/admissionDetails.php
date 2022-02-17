<?php
$GA_Tap_On_ViIEW_MORE = 'VIEW_MORE_ADMISSION';
 if($instituteObj->getAdmissionDetails() != ''){ ?>
        <div class="gap admissionTuple" id='about-admission'>
            <h2 class="head-L2 head-gap">About Admissions</h2>
            <div class="lcard">
                <div class="rich-txt-container" id="admissionDetails">
			<?=$instituteObj->getAdmissionDetails()?>
                </div>
		<div style="width:100%;text-align:center">
			<a class="link-blue-medium" style="display:none;left:0;" id="admissionViewMore" href="javascript:void(0)" onClick="showAdmissionDetails();" ga-attr="<?=$GA_Tap_On_ViIEW_MORE;?>">View more</a>
		</div>
            </div>
        </div>
<?php } ?>
