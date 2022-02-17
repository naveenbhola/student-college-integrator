<?php
/**
 * Created by PhpStorm.
 * User: kushagra
 * Date: 5/7/18
 * Time: 10:33 AM
 */
?>
<?php
    if($rmcState===-1) {
        $rmcDataObj = array(
        'url' => $url,
        'pageTitle' => $pageTitle,
        'courseId' => $courseId,
        'trackingPageKeyId' => $trackingPageKeyId,
        'sourcePage' => $sourcePage,
        'widget' => $widget
    );
    ?>
    <a href="javascript:void(0);" class="rateBtn <?php echo (!is_null($class)?$class:''); ?>" <?php echo 'lid="'.$courseId.'"'; ?> <?php echo (!is_null($loc)?'loc="'.$loc.'" ':''); ?> onclick="studyAbroadTrackEventByGA('RATE_MY_CHANCE','<?=($sourcePage)?>','<?=($widget)?>');loadRMCForm('<?php echo base64_encode(json_encode($rmcDataObj))?>');"><?=$ratingText?></a>
<?php }elseif($rmcState == "0"){?>
    <a href="javascript:void(0);" onmouseover="showHideRMCTooltip(this);" onmouseout="showHideRMCTooltip(this);" <?php echo 'lid="'.$courseId.'"'; ?> class="rateBtn underRvw <?php echo (!is_null($class)?$class:''); ?>" <?php echo (!is_null($loc)?'loc="'.$loc.'" ':''); ?>">
        <span class="<?=$cssIconClass?>"><?=$cssIconText?></span>
        <span class="<?=$cssIconClass?>"><?=$cssIconText?></span>
        &nbsp;<?=$ratingText?>
    </a>
    <div class="tooltip-info" style="display: none; right:11px; top:50px; padding: 8px;left: auto;width: 310px;">
        <i class="common-sprite verified-up-pointer"></i>
        Please be patient! Our expert Shiksha counsellor is reviewing your profile and will get back with your admission chances for this course.
    </div>
<?php }else{ ?>
    <a href="javascript:void(0);" onclick="" class="rateBtn underRvw <?php echo (!is_null($class)?$class:''); ?>" <?php echo 'lid="'.$courseId.'"'; ?> <?php echo (!is_null($loc)?'loc="'.$loc.'" ':''); ?>>
			<span class="<?=$cssIconClass?>">
				<?=$cssIconText?>
			</span>

				<?=$ratingText?>

    </a>
<?php } ?>
