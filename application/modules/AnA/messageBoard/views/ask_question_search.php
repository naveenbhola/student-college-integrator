<li style="padding-bottom:8px; border-bottom:1px solid #c4c4c4; margin-bottom:5px">
	<?php
	if($_REQUEST['debug'] == "general"){
		echo "DS: ". $data->getDocumentScore() . "<br/>";
		echo "pageId: ". $pageId . "<br/>";
		echo "RC: ". $count . "<br/>";
	}
	?>
	<h7><b>
		<a href="<?php echo $data->getUrl();?>" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $data->getUrl();?>', '<?php echo $data->getThreadId(); ?>', 'question-title', 'normal', '<?php echo $pageId; ?>');">
			<?php echo $data->getTitle();?></b>
		</a>
		<?php
		if(trim($data->getQuestionInstituteId()) != ""){
			?>
			<p>
				<em style="font-size:13px;font-family:Tahoma, Geneva, sans-serif;color:#393939;">asked about <a href="<?php echo $data->getQuestionInstituteURL();?>" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $data->getQuestionInstituteURL();?>', '<?php echo $data->getThreadId(); ?>', 'question-insti-title', 'normal', '<?php echo $pageId; ?>');"><?php echo $data->getQuestionInstituteTitle();?></a></em>
			</p>
			<?php
		}
		?>
	</b></h7>
    <div class="spacer10 clearFix"></div>
	<?php
	if($data->getAnswerCount() > 0){
		?>
		<div class="res-figure" style="width:90px">
			<a href="javascript:void(0);">
				<img src="<?php echo getImageUrlBySize($data->getAnswerUserImageUrl(), 'medium');?>" width="75" height="50" alt="answer author image" />
			</a>
		</div>
		<div class="res-details" style="margin-left:90px">
			<em>Answered by
				<a href="<?php echo getUserProfileLink($data->getAnswerUserDisplayName());?>" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo getUserProfileLink($data->getAnswerUserDisplayName());?>', '<?php echo $data->getThreadId(); ?>', 'question-user-profile', 'normal', '<?php echo $pageId; ?>');">
					<?php echo $data->getAnswerUserDisplayName();?>
				</a>
			</em>
			<?php $answerTitle =  $data->getAnswerTitle();
                        if(strlen($answerTitle)>103)
                        {
                             $subAnswer = substr($answerTitle,0,100);
                            echo $subAnswer.'...';
                        }
                        else echo $answerTitle;
                    
            ?></b>
			<a href="<?php echo $data->getUrl();?>" style="text-decoration:none;" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $data->getUrl();?>', '<?php echo $data->getThreadId(); ?>', 'question-answer-button', 'normal', '<?php echo $pageId; ?>');">
			<?php
			if($data->getAnswerCount() > 1){
				?>
				<!--input type="button" value="View All <?php// echo getPlural($data->getAnswerCount(), 'Answer');?>" class="gray-button" /-->
				<?php
			} else if($data->getAnswerCount() == 1){
				?>
				<!--input type="button" value="View 1 Answer" class="gray-button" /-->
				<?php
			} else {
				?>
				<!--input type="button" value="No Answers" class="gray-button" /-->
				<?php
			}
			?>
			</a>
			<span class="view-commt">
				(<?php echo getPlural($data->getViewCount(), 'View');?>,
					<a href="<?php echo $data->getUrl();?>" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $data->getUrl();?>', '<?php echo $data->getThreadId(); ?>', 'question-answer-link', 'normal', '<?php echo $pageId; ?>');"><?php echo getPlural($data->getAnswerCount(), 'Answer');?></a>)
			</span>
		</div>
		<?php
	} else {
		echo "<em>No answers yet</em>";
	}
	?>
</li>
