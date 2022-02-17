<?php if(!empty($qna)):?>
<?php foreach ($qna as $equestionId => $data): ?>

<?php $questionData = $data['data'];?>

	<?php if(!$showLoadMoreLink):?>
	<input id="hide_load_more" value="yes" type="hidden"/>
	<?php endif;?>
	<li>
		<span class="ques-icon">Q</span>
		<div class="ques-details">
        <?php  
	        $questionText = $questionData["title"];
	        $questionText = html_entity_decode(html_entity_decode($questionText,ENT_NOQUOTES,'UTF-8'));
	        $questionText = formatQNAforQuestionDetailPageWithoutLink($questionText,140);
        ?>
				
			<p class="ques-text">
				<a style="color:#121212 !important" target="_blank" href="<?php echo $questionData["q_url"];?>">
					<?php echo $questionText;?>
				</a>
			</p>
		
			<div class="user-details clear-width" style="margin-bottom:0">
			    <p class="flLt">Posted by: <span>
			    	<a style="color:#4F4F4F !important" target="_blank" href="/getUserProfile/<?php echo $questionData['displayname'];?>"><?php echo $questionData["firstname"].' '.$questionData['lastname'];?></a>
			    </span></p>
			    <p class="flRt"><?php echo  makeRelativeTime($questionData["creationDate"]);?></p>
			</div>
			<div class="clearFix"></div>
		</div>
	</li>


<?php endforeach;?>
<?php endif;?>
