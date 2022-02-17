<?php 
$newSummaryCharLimit = 255;
$summary = html_escape(strip_tags($prepTip['summary']));
$summary = (strlen($summary) > $newSummaryCharLimit ?  substr($summary,0,$newSummaryCharLimit) : $summary);
$summary = $summary."...";
$imgURL = str_replace("_s", "_m",$prepTip['blogImageURL']);
$imgURL = empty($imgURL) ? "public/images/prep-tip-1.jpg" : $imgURL;

$titleCharLimit = 36;
$testPrepTitle = html_escape($prepTip['blogTitle']);
$testPrepTitle = (strlen($testPrepTitle) > $titleCharLimit ?  substr($testPrepTitle,0,$titleCharLimit)."..." : $testPrepTitle);
?>
<div style="margin-bottom: 15px;">
	
	<img src=<?=MEDIA_SERVER.$imgURL ?>
		width="87" height="55" alt="<?=substr($testPrepTitle,0,20)?>" />
		
	<div class="result-info-title">
		<a style="text-decoration: none;" onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'prep_tips_page_prepTipTupple', '<?=$prepTip['blogId']?>');" href="<?=$prepTip['url']?>"> <strong><?=$testPrepTitle ?></strong></a><br /><span>Posted On : <?php echo date("M d, Y, g.ia", strtotime($prepTip['lastModifiedDate']))?></span>
			
	</div>
</div>
<div >
<?=$summary?>
<a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'prep_tips_page_prepTipTupple', '<?=$prepTip['blogId']?>');" href="<?=$prepTip['url']?>"> More</a>
</div>



