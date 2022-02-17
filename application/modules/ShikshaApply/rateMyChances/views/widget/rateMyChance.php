<div class="btn-rate-change" style="position: relative">
	<?php if($rmcState===-1) {
		$rmcDataObj = array(
				'url' => $url,
				'pageTitle' => $pageTitle,
				'courseId' => $courseId,
				'trackingPageKeyId' => $trackingPageKeyId,
				'sourcePage' => $sourcePage,
				'widget' => $widget
			);
	?>
		<a href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('RATE_MY_CHANCE','<?=($sourcePage)?>','<?=($widget)?>');loadRMCForm('<?php echo base64_encode(json_encode($rmcDataObj))?>');" style="padding:8px !important;" class="rate-change-button text-center"><?=$ratingText?></a>
	<?php }elseif($rmcState == "0"){?>
		<a href="javascript:void(0);" style="padding:8px 9px!important; text-align: left; cursor: auto;" class="rate-change-button-gray text-center" onmouseover="showHideRMCTooltip(this);" onmouseout="showHideRMCTooltip(this);">
			<span class="<?=$cssIconClass?>"><?=$cssIconText?></span>
			<span class="<?=$cssIconClass?>"><?=$cssIconText?></span>
			&nbsp;<?=$ratingText?>
		</a>
		<div class="tooltip-info" style="display: none; right:-108px; top:40px; padding: 8px;width: 310px;">
                <i class="common-sprite verified-up-pointer"></i>
                Please be patient! Our expert Shiksha counsellor is reviewing your profile and will get back with your admission chances for this course.
        </div>
	<?php }else{ ?>
		<a href="javascript:void(0);" onclick="" style="padding:6px 9px!important; text-align: left; cursor: auto;" class="rate-change-button-gray text-center">
			<span class="<?=$cssIconClass?>">
				<?=$cssIconText?>
			</span>
			<span style="font-size:11px !important;<?=$rmcState=="4"?'position:relative;top:-2px;':''?>">
				<?=$ratingText?>
			</span>
		</a>
	<?php } ?>
</div>