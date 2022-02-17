<div id="myQuestions">
<div id="<?php echo $myKey; ?>">
<?php
	$this->csvThreadIds = '';
	$textLength = 1000;
	$questionList = $topicListings['results'];
	$arrayOfUsers = $topicListings['arrayOfUsers'];
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	if(count($questionList) > 0){	
	for($i=0;$i<count($questionList);$i++)
	{
		if($this->csvThreadIds == '')
			$this->csvThreadIds = $questionList[$i]['threadId'];
		else
			$this->csvThreadIds .= ",".$questionList[$i]['threadId'];
		
		$ansMsg = ($questionList[$i]['answerCount'] > 1)?($questionList[$i]['answerCount'].' answers'):($questionList[$i]['answerCount'].' answer');
		$ansMsg = '<span class="bld">'.$ansMsg.'</span>';
		if($questionList[$i]['answerCount'] == 0){
			$ansMsg = 'No answer';	
		}
		$questionUrl = base64_encode($questionList[$i]['url']);
		if($questionList[$i]['status'] == 'closed')
			$closeQues = '<span class="normaltxt_11p_blk bld">Closed Question</span>';
?>
<div style="margin-right:10px;">
	<div class="qmarked fontSize_12p">
		<!-- Block Start for Owner name, Level and Question text -->
		<div class="lineSpace_18" style='word-wrap:break-word'>
			<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $questionList[$i]['userId']; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $arrayOfUsers[$questionList[$i]['userId']]['userProfile']; ?>">You</a></span>
			<b>asked&nbsp;</b>
			<a href="<?php echo $questionList[$i]['url'];?>" style="color:#000000" >
			<?php $quesLength = strlen($questionList[$i]['msgTxt']);
				  if($quesLength<=140){ echo "<span style='word-wrap:break-word' class=''>".insertWbr($questionList[$i]['msgTxt'],50)."</span></a>";}
				  else {
					  echo "<span class='' id='previewQues".$questionList[$i]['msgId']."'>".substr($questionList[$i]['msgTxt'], 0, 137)."</span></a>";
					  echo "<span id='relatedQuesDiv".$questionList[$i]['msgId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$questionList[$i]['msgId']."' onClick='showCompleteAnswerHomepage(".$questionList[$i]['msgId'].");'>more</a></span>";
					  echo "<a href=".$questionList[$i]['url']." style='color:#000000'><span class='' id='completeRelatedQuesDiv".$questionList[$i]['msgId']."' style='display:none;'>".$questionList[$i]['msgTxt']."</span></a>";
				  }
			?>
		</div>
		<!-- Block End for Owner name, Level and Question text -->
		<!-- Block Start for Creation Date and Category-Country -->
		<div>
			<span class="Fnt11"><span class="grayFont"><?php echo $questionList[$i]['creationDate']; ?></span> in <a href="/messageBoard/MsgBoard/discussionHome/<?php echo $questionList[$i]['categoryId']; ?>/1/<?php echo $questionList[$i]['countryId'];?>"><?php echo $questionList[$i]['category']." - ".$questionList[$i]['country']; ?></a></span>
		</div>
		<!-- Block End for Creation Date and Category-Country -->
		<!-- Block start for View All Answers Link -->
		<?php if($questionList[$i]['answerCount'] >= 1){ ?>
		<div style="margin-top:5px;">
			<div class="pl33"><img src="/public/images/upArw.png" /></div>
			<div class="fbkBx">
				<div>
					<div class="float_L wdh100">
							<div class="Fnt11">
							  <a href="<?php echo $questionList[$i]['url'];?>" class="fbxVw Fnt11">View <?php if($questionList[$i]['answerCount'] == 1)echo "<span>".$questionList[$i]['answerCount']."</span> answer";else echo "all <span>".$questionList[$i]['answerCount']."</span> answers"?></a>
							</div>
					</div>
					<s>&nbsp;</s>
				</div>
			</div>
		  </div>
		<?php } ?>
		<!-- Block End for View All Answers Link -->
		<!-- Block Start for Closed Question text -->
        <div><?php echo $closeQues; ?></div>
		<!-- Block End for Closed Question text -->
    </div>
<!-- 	<div style="display:none;" id="newRepliesImage_<?php echo $myKey.'_'.$questionList[$i]['threadId']; ?>"><div class="txt_align_c newReplyCircle"><br style="line-height:12px" /><span id="newRepliesCount_<?php echo $myKey.'_'.$questionList[$i]['threadId']; ?>">0 New<br />Reply</span></div></div> -->
    <div class="grayLine">&nbsp;</div>
    <div class="lineSpace_10">&nbsp;</div>
</div>
    <?php
    }
	}else{
			$categoryForDisplay = json_decode($categoryForLeftPanel,true);
			$countryForDisplay = json_decode($countryList,true);
			if(($categoryId == 1) && ($countryId == 1)){
				echo 'No Questions asked by you for any Category or Country';
			}else if(($categoryId == 1) || ($countryId == 1)){
				$catMsg = ($categoryId != 1)?'category '.$categoryForDisplay[$categoryId][0]:' any Category';
				$countryMsg = ($countryId != 1)?'country '.$countryForDisplay[$countryId]:' any Country';
				echo 'No Questions asked by you for '.$catMsg.' and in '.$countryMsg;
			}else{
        		echo 'No Questions asked by you for category '.$categoryForDisplay[$categoryId][0].' and for country  '.$countryForDisplay[$countryId];
			}	
	
	}
    ?> 
</div>
</div>
