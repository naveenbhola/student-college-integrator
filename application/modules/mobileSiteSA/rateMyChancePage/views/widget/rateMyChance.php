<?php
if($widget==''){
	$widget = 'widgetNameNotdefined';
}
if($sourcePage==''){
	$sourcePage = 'sourcePageNotDefined';
}
?>
<div class="rateMyChanceButtonDiv rmcButtonDiv" >
	<?php if($rmcState===-1) { ?>
		<a href="javascript:void(0);" onclick="gaTrackEventCustom('RATE_MY_CHANCE','<?=($sourcePage)?>','<?=($widget)?>');rmcButtonSubmit('<?=($url)?>','<?=($pageTitle)?>','<?=$courseId?>','<?=$trackingPageKeyId ?>');" loc="rmcbut" lid="<?=$courseId?>" class="rmcState-1 rate-change-button text-center tl"><?=$ratingText?></a>
<?php }elseif($rmcState == "0"){?>
		<a href="javascript:void(0);" class="rate-change-button-gray text-center rmcState0">
			<span class="<?=$cssIconClass?>"><?=$cssIconText?></span>
			<span class="<?=$cssIconClass?>"><?=$cssIconText?></span>
			&nbsp;<?=$ratingText?>
		</a>
	<?php }else{ ?>
		<a href="javascript:void(0);" onclick="" class="rate-change-button-gray text-center rmcState">
			<span class="<?=$cssIconClass?>">
				<?=$cssIconText?>
			</span>
			<span <?=$rmcState=="4"?' class="rmcState4"':''?>>
				<?=$ratingText?>
			</span>
		</a>
	<?php } ?>
</div>