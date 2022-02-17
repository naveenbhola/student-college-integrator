<!-- Wiki-Start : Discussions -->
<?php
$discussionIdsFromCMS = $examPageData->getDiscussionsIds();
$validatedDiscussionIdArr = array();
if(count($discussionIdsFromCMS)>0 && $discussionIdsFromCMS[0]!='')
{
	$validatedDiscussionIdArr = Modules::run('ExamPageDiscussion/ExamPageDiscussion/validateDiscussionIds', $discussionIdsFromCMS);
}
$discussionIds = array();
foreach($validatedDiscussionIdArr as $threadId)
{
	$discussionIds[] = $threadId['threadId'];
}
$item_per_page = 3;
?>
<?php $total_pages = ceil(count($discussionIds)/$item_per_page);?>
<script src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('ana_common'); ?>"></script>
<div class="content-tupple" id="wiki-sec-0">
		    <?php
		    if(count($discussionIds) > 0)
		    {
		    ?>
		    <h2 class="exm-sub-hd">Discussion</h2>
		    <ul class="discussion-list clear-width" id="main-discussion-div">
<?php
$dataForWidget = array('discussionsIds'=> $discussionIds, 'item_per_page'=>$item_per_page);
echo Modules::run('ExamPageDiscussion/ExamPageDiscussion/examPageDiscussionWidget', $dataForWidget);
?>
</ul>
<div class="show-more" id="load-more-discussions" onclick="loadMoreDiscussion();" <?php if($total_pages==1) {echo 'style="display:none"';}?>>
	<a href="javascript:void(0);">Show More</a>
</div>
<div class="clearfix"></div>
		    <?
		    }
		    else
		    {
		    ?>
		    <h2 class="exm-sub-hd">Discussion</h2>
		    <p>Nothing interesting here!</p>
		    <p>Go to <a href="<?=$sectionUrl['home']['url']?>"><?=$examPageData->getExamName()?> homepage.</a></p>
		    <?php
		    }
		    ?>
</div>
<!-- Wiki-End : Discussions -->

<?php $this->load->view("widgets/newsArticleSliderWidget"); ?>

<!-- Start : Registration  -->
<?php $tracking_keyid = DESKTOP_NL_EXAM_PAGE_DISCUSSION_REG; ?>
<?php 
$this->load->view('examPages/widgets/registrationWidget',array('tracking_keyid'=>$tracking_keyid)); 
?>    
<!-- End : Registration  -->
<!-- Start : Similar Exam Widget-->
<?php echo Modules::run('examPages/ExamPageMain/similarExamWidgets',$examId,$examPageData->getExamName()); ?>
<!-- End : Similar Exam Widget-->


		<script>
			var total_pages = '<?=$total_pages?>';
			var item_per_page = '<?=$item_per_page?>';
			var track_click = 1;
			function loadMoreDiscussion() {
				$j("#load-more-discussions").hide();
				//$j("#load-more-discussions").css('visibility','hidden');
				//$j("#load-more-discussions").css('padding','0px');
				if (track_click <= total_pages) {
					$j.post('/ExamPageDiscussion/ExamPageDiscussion/examPageDiscussionWidget',{'page': track_click,'discussionsIds':'<?=implode(',', $discussionIds)?>','item_per_page':item_per_page}, function(data)
					{
						if (trim(data) !='')
						{   
						    track_click++;
						    $j("#main-discussion-div").append(data);
						    $j("#load-more-discussions").show();
						    //$j("#load-more-discussions").css('visibility','visible');
						    //$j("#load-more-discussions").css('padding','10px');
						    if (total_pages == track_click) {
							$j("#load-more-discussions").hide();
							$j('#main-discussion-div li:last').addClass('last');
							//$j("#load-more-discussions").css('visibility','hidden');
							//$j("#load-more-discussions").css('padding','0px');
						    }
						}
						else{
							$j("#load-more-discussions").show();
							//$j("#load-more-discussions").css('visibility','visible');
							//$j("#load-more-discussions").css('padding','10px');
						}
					    }).fail(function(xhr, ajaxOptions, thrownError) { 
						    console.log(thrownError);
						    $j("#load-more-discussions").show();
						    //$j("#load-more-discussions").css('visibility','visible');
						    //$j("#load-more-discussions").css('padding','10px');
					    });
				}
			}
			function showCompleteComment(msgId) {
				$('span-half-'+msgId).style.display='none';
				$('span-full-'+msgId).style.display='block';
			}
		</script>