
	<?php
		// max char limit of news summary
		$newSummaryCharLimit = 120;

		foreach($examPageNews as $key=>$news)
		{
			if($key < 3){
			$summary = html_escape(strip_tags($news['summary']));
			$summary = (strlen($summary) > $newSummaryCharLimit ?  substr($summary,0,$newSummaryCharLimit)."..." : $summary);
	?>
        <li>
            	<label><a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_news_widget_sec', '<?=$news['blogId']?>');" href="<?=html_escape($news['url'])?>"><?=html_escape($news['blogTitle'])?></a></label>
                <span>On <?=date("F d, Y", strtotime($news['lastModifiedDate']))?></span>
                <p title="<?=html_escape(strip_tags($news['summary']))?>"><?=$summary?> <a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_news_widget_sec', '<?=$news['blogId']?>');"  href="<?=html_escape($news['url'])?>">Continue Reading</a></p>
        </li>
	<?php }	} ?>
       


<?php
if($examPageMoreNewsFlag == 1)
		{
			$examPageNewsOffset = $examPageNewsOffset ? $examPageNewsOffset : 0;
	    ?>
	    <div class="show-more" onclick="loadMoreArticles('<?=$params?>',<?=$examPageNewsOffset?>,'<?=$examArticleTags?>', this,'news');">
	        <div style="float: left;visibility: hidden;" id="pagination-loder-news"><img style="height:18px;" src="/public/images/loader_hpg.gif"></div>
        	<a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_article_widget_sec', 'ShowMore');" href="javascript:void(0);">Show More</a>
	    </div>
<?php
		}
?>