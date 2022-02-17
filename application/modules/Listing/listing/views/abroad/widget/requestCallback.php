<?php
	$brochureDataObj['widget'] = 'request_callback';
	if ($brochureDataObj['sourcePage'] == 'department') {
		$brochureDataObj['trackingPageKeyId'] = 48;
	}elseif ($brochureDataObj['sourcePage'] == 'university') {
		$brochureDataObj['trackingPageKeyId'] = 6;
	}
	
	$listing_type = $listingType;
?>
<div class="other-course-box clearwidth req-callbck-sec" style="border:1px solid #ccc">
        <p class="req-callbck-title"><i class="common-sprite need-info-icon"></i>Need More Information?</p>
	<p>A dedicated Shiksha counselor can provide more details about admissions, eligibility and scholarships for this <?=($listing_type)?></p>
	<a id = "requestCallbackButton" style="padding:6px 20px; display:block; text-align: center" href="Javascript:void(0);" onclick = "loadStudyAbroadForm('<?=base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer');" class="button-style">Request a Call Back</a>
	<?php if($showRequestCallbackHelpText) { ?>	
	<div class="sort-by-help" id="requestCallbackHelptext">
             	<i class="common-sprite help-arrow-3"></i>
		<p>Use this button to request a free call back from a Shiksha counselor</p>  
        </div>
	<?php } ?>
</div>
