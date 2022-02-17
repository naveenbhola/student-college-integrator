<?php
$articleTitleLimit = 30;
$articleSummaryLimit = 90;
if($examPageArticles)
{
	    if($examPageArticles[0])
	    {
?>
            <li>
	    <?php
	        $articleData = $examPageArticles[0];
	        if($articleData)
		{
			$title = (strlen(strip_tags($articleData['blogTitle'])) > $articleTitleLimit ) ? substr(strip_tags($articleData['blogTitle']), 0, $articleTitleLimit)." ..." : $articleData['blogTitle'];
			$summary = (strlen(strip_tags($articleData['summary'])) > $articleSummaryLimit ) ? substr(strip_tags($articleData['summary']), 0, $articleSummaryLimit)."..." : $articleData['summary'];
			if(!empty($articleData['blogImageURL'])){
				$articleData['blogImageURL'] = str_replace("_s","",$articleData['blogImageURL']);
				$imgUrl = MEDIA_SERVER.$articleData['blogImageURL'];
			}else{
				$imgUrl = '/public/images/article_widget_image.jpg';
			}
			
	    ?>
                <div class="article-info flLt">
                  <div class="article-img">
                  	<img src="<?=$imgUrl?>" width="214" height="134" alt="<?=html_escape($articleData['blogTitle'])?>" />
                  </div>
                  <div class="article-detail">
                  	<a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_article_widget_sec', '<?=$articleData['blogId']?>');"  href="<?=html_escape($articleData['url'])?>" class="article-title" title="<?=html_escape(strip_tags($articleData['blogTitle']))?>"><?=html_escape($title)?></a>
                    <span>On <?=date("F d, Y", strtotime($articleData['lastModifiedDate']))?></span>
                    <p class="article-summary" title="<?=html_escape(strip_tags($articleData['summary']))?>"><?=html_escape($summary)?></p>
		    <a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_article_widget_sec', '<?=$articleData['blogId']?>');" href="<?=html_escape($articleData['url'])?>">Continue Reading</a>
                  </div>
                </div>
	    <?php
		}
	    
	        $articleData = $examPageArticles[1];
	        if($articleData)
		{
			$title = (strlen(strip_tags($articleData['blogTitle'])) > $articleTitleLimit ) ? substr(strip_tags($articleData['blogTitle']), 0, $articleTitleLimit)." ..." : $articleData['blogTitle'];
			$summary = (strlen(strip_tags($articleData['summary'])) > $articleSummaryLimit ) ? substr(strip_tags($articleData['summary']), 0, $articleSummaryLimit)."..." : $articleData['summary'];
			if(!empty($articleData['blogImageURL'])){
				$articleData['blogImageURL'] = str_replace("_s","",$articleData['blogImageURL']);
				$imgUrl = MEDIA_SERVER.$articleData['blogImageURL'];
			}else{
				$imgUrl = '/public/images/article_widget_image.jpg';
			}
	    ?>
                <div class="article-info flRt">
                  <div class="article-img">
                  	<img src="<?=$imgUrl?>" width="214" height="134" alt="<?=html_escape($articleData['blogTitle'])?>" />
                  </div>
                  <div class="article-detail">
                  	<a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_article_widget_sec', '<?=$articleData['blogId']?>');" href="<?=html_escape($articleData['url'])?>" class="article-title" title="<?=html_escape(strip_tags($articleData['blogTitle']))?>"><?=html_escape($title)?></a>
                    <span>On <?=date("F d, Y", strtotime($articleData['lastModifiedDate']))?></span>
                    <p class="article-summary" title="<?=html_escape(strip_tags($articleData['summary']))?>"><?=html_escape($summary)?></p>
		    <a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_article_widget_sec', '<?=$articleData['blogId']?>');" href="<?=html_escape($articleData['url'])?>">Continue Reading</a>
                  </div>
                </div>
	    <?php
		}
	    ?>
	    <div class="clearfix"></div>
	    </li>
	    <?php
		}
	    if($examPageArticles[2])
	    {
?>
            <li>
	    <?php
	        $articleData = $examPageArticles[2];
	        if($articleData)
		{
			$title = (strlen(strip_tags($articleData['blogTitle'])) > $articleTitleLimit ) ? substr(strip_tags($articleData['blogTitle']), 0, $articleTitleLimit)." ..." : $articleData['blogTitle'];
			$summary = (strlen(strip_tags($articleData['summary'])) > $articleSummaryLimit ) ? substr(strip_tags($articleData['summary']), 0, $articleSummaryLimit)."..." : $articleData['summary'];
			if(!empty($articleData['blogImageURL'])){
				$articleData['blogImageURL'] = str_replace("_s","",$articleData['blogImageURL']);
				$imgUrl = MEDIA_SERVER.$articleData['blogImageURL'];
			}else{
				$imgUrl = '/public/images/article_widget_image.jpg';
			}
	    ?>
                <div class="article-info flLt">
                  <div class="article-img">
                  	<img src="<?=$imgUrl?>" width="214" height="134" alt="<?=html_escape($articleData['blogTitle'])?>" />
                  </div>
                  <div class="article-detail">
                  	<a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_article_widget_sec', '<?=$articleData['blogId']?>');" href="<?=html_escape($articleData['url'])?>" class="article-title" title="<?=html_escape(strip_tags($articleData['blogTitle']))?>"><?=html_escape($title)?></a>
                    <span>On <?=date("F d, Y", strtotime($articleData['lastModifiedDate']))?></span>
                    <p class="article-summary" title="<?=html_escape(strip_tags($articleData['summary']))?>"><?=html_escape($summary)?></p>
		    <a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_article_widget_sec', '<?=$articleData['blogId']?>');" href="<?=html_escape($articleData['url'])?>">Continue Reading</a>
                  </div>
                </div>
	    <?php
		}
	    
	        $articleData = $examPageArticles[3];
	        if($articleData)
		{
			$title = (strlen(strip_tags($articleData['blogTitle'])) > $articleTitleLimit ) ? substr(strip_tags($articleData['blogTitle']), 0, $articleTitleLimit)." ..." : $articleData['blogTitle'];
			$summary = (strlen(strip_tags($articleData['summary'])) > $articleSummaryLimit ) ? substr(strip_tags($articleData['summary']), 0, $articleSummaryLimit)."..." : $articleData['summary'];
			if(!empty($articleData['blogImageURL'])){
				$articleData['blogImageURL'] = str_replace("_s","",$articleData['blogImageURL']);
				$imgUrl = MEDIA_SERVER.$articleData['blogImageURL'];
			}else{
				$imgUrl = '/public/images/article_widget_image.jpg';
			}

	    ?>
                <div class="article-info flRt">
                  <div class="article-img">
                  	<img src="<?=$imgUrl?>" width="214" height="134" alt="<?=html_escape($articleData['blogTitle'])?>" />
                  </div>
                  <div class="article-detail">
                  	<a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_article_widget_sec', '<?=$articleData['blogId']?>');" href="<?=html_escape($articleData['url'])?>" class="article-title" title="<?=html_escape(strip_tags($articleData['blogTitle']))?>"><?=html_escape($title)?></a>
                    <span>On <?=date("F d, Y", strtotime($articleData['lastModifiedDate']))?></span>
                    <p class="article-summary" title="<?=html_escape(strip_tags($articleData['summary']))?>"><?=html_escape($summary)?></p>
		    <a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_article_widget_sec', '<?=$articleData['blogId']?>');" href="<?=html_escape($articleData['url'])?>">Continue Reading</a>
                  </div>
                </div>
	    <?php
		}
	    ?>
	    <div class="clearfix"></div>
            </li>
	    <?php
		}
		
		if($examPageMoreArticleFlag == 1)
		{
			$examPageArticlesOffset = $examPageArticlesOffset ? $examPageArticlesOffset : 0;
	    ?>
	    <div class="show-more" onclick="loadMoreArticles('<?=$params?>',<?=$examPageArticlesOffset?>,'<?=$examId?>', this,'article');">
	        <div style="float: left;visibility: hidden;" id="pagination-loder"><img style="height:18px;" src="/public/images/loader_hpg.gif"></div>
        	<a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'new_and_article_page_article_widget_sec', 'ShowMore');" href="javascript:void(0);">Show More</a>
	    </div>
<?php
		}
}
?>
