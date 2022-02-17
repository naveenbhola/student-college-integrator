<div class="exam-wrap clearfix">
    <h1 class="examPage-title">Discussion </h1>
	<ul class="discussion-list" data-enhance="false" id="mainDiscussionDiv">
            <?php
			$discussionIds=$examPageData->getDiscussionsIds();
			$item_per_page=3;
			$dataForWidget = array('discussionsIds'=> $discussionIds, "sectionId" => "wiki-sec-0","context" => "mobile", 'item_per_page' => $item_per_page);
			echo Modules::run('ExamPageDiscussion/ExamPageDiscussion/examPageDiscussionWidget',$dataForWidget);
			
            ?>
			
	</ul>
    <a class="show-more" href="javascript:void(0);" onclick="showMoreDiscussions()";  id="load_more_discussion">Show More</a>
</div>

<script>
	function showWholeComment(commentId){
		document.getElementById('commentDesc_'+commentId).style.display='none';
		document.getElementById('commentFullDesc_'+commentId).style.display='block';
			 
	}
	
	
	<?php $totalDiscussion=count($discussionIds);
	$total_pages = ceil($totalDiscussion/$item_per_page);?>
	var track_click = 1; //track user click on "load more" button, righ now it is 0 click
	
	var total_pages = <?php echo $total_pages; ?>
	
	function showMoreDiscussions(){
		//$("#load_more_discussion").hide();
		$(this).text('Loading...'); 
		if(track_click <= total_pages) 
		{  
			$.post('ExamPageDiscussion/ExamPageDiscussion/examPageDiscussionWidget',{'page': track_click, 'discussionsIds':'<?php echo implode(',',$discussionIds) ?>', 'context':'mobile', 'item_per_page':'<?php echo $item_per_page ?>'}, function(data) {
			    if (data !='')
			    {   
				track_click++;
				 $("#load_more_discussion").text('Show More').show(); 
				$("#mainDiscussionDiv").append(data);
				if (total_pages == track_click) {
				    $("#load_more_discussion").text('Show More').hide(); 
				}
			    }
			}).fail(function(xhr, ajaxOptions, thrownError) { 
				console.log(thrownError);
				$("#load_more_discussion").text('Show More').show(); 
			});
		 }  
	}
	
	
</script>	