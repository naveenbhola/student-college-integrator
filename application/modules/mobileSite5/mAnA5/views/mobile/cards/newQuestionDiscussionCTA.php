<?php
$typeQoD = "";
$gaAction = "";
if($pageType == "home"){
	$placeholder="What is your question?";
	$stickyTrackingPageKeyId = $qstickyTrackingPageKeyId;
	$typeQoD = "question";
	$gaAction = "QUESTION_CTA_HOMEPAGE_WEBAnA";
}
else if($pageType == "discussion" && $data['canStartDiscussion']){
	$placeholder="Start a new discussion";
	$stickyTrackingPageKeyId = $dstickyTrackingPageKeyId;
	$typeQoD = "discussion";
	$gaAction = "DISCUSSION_CTA_HOMEPAGE_WEBAnA";
}
else{
	return;
}

global $isWebViewCall;
if(!$isWebViewCall){
?>
<div class="input-tab-wrap" data-enhance="false" id="stickyAskCTA" style="display:none;">
	<a class="sticky-ask" href="#questionPostingLayerOneDiv" data-inline="true" data-rel="dialog" data-transition="fade" onclick="sendGAWebAnA('HOMEPAGE_WEBAnA','<?=$gaAction;?>');populateQuesDiscLayer('<?=$typeQoD;?>','<?php echo $stickyTrackingPageKeyId;?>')">
		<span><?php echo $placeholder;?></span>
	</a>
</div>
<?php } ?>