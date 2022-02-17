<div id="<?php echo $myqnaTab == 'answer' ? 'myAnswers' :'myBestAnswers';  ?>">
<div id="<?php echo $myKey; ?>">
<?php
    $x = 0;$y=0;
	$this->csvThreadIds = '';
	$textLength = 1000;
	$questionList = $topicListings['results'];
	$arrayOfUsers = $topicListings['arrayOfUsers'];
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$userProfile = site_url('getUserProfile').'/';
	if(count($questionList) > 0){	
	for($i=0;$i<count($questionList);$i++)
	{
        $question = $questionList[$i]['question'];
        $answer = $questionList[$i]['answer'];
		$comment = is_array($questionList[$i]['comment'])?$questionList[$i]['comment']:array();
        $answerText = $answer['msgTxt'];
		if($this->csvThreadIds == '')
			$this->csvThreadIds = $question['threadId'];
		else
			$this->csvThreadIds .= ",".$question['threadId'];
		
		$ansMsg = ($question['answerCount'] > 1)?($question['answerCount'].' answers'):($question['answerCount'].' answer');
		$ansMsg = '<span class="bld">'.$ansMsg.'</span>';
		if($question['answerCount'] == 0){
			$ansMsg = 'No answer';	
		}
		$questionUrl = base64_encode($question['url']);
		
		if(($answer['bestAnsFlag'] === '1') || ($answer['digUp'] > 0 ) || ($answer['digDown'] > 0) || ($answer['repliesToAnswerCount'] > 0)){
			$ansNowLink = '&nbsp;';
		}else{
			$ansNowLink = '<a href="'.$question['url'].'">Edit your answer</a>';
		}

?>
<!-- Main Div Start -->
<div style="margin-right:10px;">
	<!-- Start of Question Block -->
	<div class="qmarked fontSize_12p" style="padding-bottom:10px">
		<div class="lineSpace_18">
				<?php if($question['userId']==$userId){ ?>
					  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $question['userId']; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $arrayOfUsers[$question['userId']]['userProfile']; ?>"><b><?php echo $question['firstname'].' '.$question['lastname'];?></b></a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"><img src="/public/images/fU.png" /></span>
				<?php }else if($question['vcardStatus']==1){ ?>
					  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $question['userId']; ?>');}catch(e){}" style="width:30px;display:inline;"><span class="flwMeBg"><a href="<?php echo $arrayOfUsers[$question['userId']]['userProfile']; ?>"><b><?php echo $question['firstname'].' '.$question['lastname'];?></b></a> &nbsp; <img src="/public/images/flwMe.png" /></span></span>
				<?php }else{ ?>
					  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $question['userId']; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $arrayOfUsers[$question['userId']]['userProfile']; ?>"><b><?php echo $question['firstname'].' '.$question['lastname'];?></b></a></span>
				<?php } ?>
				  (<span class='forA'><a href="/shikshaHelp/ShikshaHelp/upsInfo"><?php echo $question['level'];?></a></span>) <b>asked&nbsp;</b>
				  <a href="<?php echo $question['url'];?>" style="color:#707070" >
				  <?php $quesLength = strlen($question['msgTxt']);
					  if($quesLength<=140){ echo "<span style='word-wrap:break-word' class='grayFont'>".formatQNAforQuestionDetailPageWithoutLink($question['msgTxt'],500)."</span></a>";}
					  else {
						  echo "<span style='word-wrap:break-word' class='grayFont' id='previewQues".$question['msgId']."'>".substr($question['msgTxt'], 0, 137)."</span></a>";
						  echo "<span id='relatedQuesDiv".$question['msgId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$question['msgId']."' onClick='showCompleteAnswerHomepage(".$question['msgId'].");'>more</a></span>";
						  echo "<a href=".$question['url']." style='color:#707070'><span class='grayFont' id='completeRelatedQuesDiv".$question['msgId']."' style='display:none;'>".formatQNAforQuestionDetailPageWithoutLink($question['msgTxt'],500)."</span></a>";
					  }
				?>

		</div>
    </div>
	<!-- End of Question Block -->
	<!-- Start of Answer Block -->
	<div class="aMarked">
		<div>
			<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $answer['userId']; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $arrayOfUsers[$answer['userId']]['userProfile']; ?>">You</a></span>
			<b>answered&nbsp;</b>
			<span class='' style='word-wrap:break-word'><?php echo $answerText; ?></span>
		</div>
		<!-- Start Question Date, Category and Country display section -->
		<div class="float_L" style="margin-top:5px;">
			<span class="Fnt11"><span class="grayFont"><?php echo $question['creationDate']; ?></span> in <a href="/messageBoard/MsgBoard/discussionHome/<?php echo $question['categoryId']; ?>/1/<?php echo $question['countryId'];?>"><?php echo $question['category']." - ".$question['country']; ?></a></span>
		</div>
		<?php
			if($answer['bestAnsFlag'] === '1') {
		?>
		  <div class="float_R" style="height:21px"><span class="bestAnswerMarked">Chosen as Best Answer</span></div>
		<?php
			}
		?>
		<!-- End Question Date, Category and Country display section -->
		<div style="line-height:1px;clear:both">&nbsp;</div>

		<!-- Block start for View All and Comments Link -->
		<?php if($answer['repliesToAnswerCount']>0){ ?>
		<div style="margin-top:5px;">
			<div class="pl33"><img src="/public/images/upArw.png" /></div>
			<div class="fbkBx" id="viewAllDiv<?php echo $question['msgId'];?>">
				<div>
					<div class="float_L wdh100">
							<div class="Fnt11">
							  <a href="javascript:void(0);" class="fbxVw Fnt11" onClick="showMyAnswerComments('<?php echo $question['msgId'];?>')">View <?php if($answer['repliesToAnswerCount'] == 1)echo "<span>".$answer['repliesToAnswerCount']."</span> comment";else echo "all <span>".$answer['repliesToAnswerCount']."</span> comments"?></a>
							</div>
					</div>
					<s>&nbsp;</s>
				</div>
			</div>
			<div id="commentDiv<?php echo $question['msgId'];?>" style="display:none;">
			<?php for($x=$x;$x<($answer['repliesToAnswerCount']+$y);$x++){ ?>
				<div class="fbkBx">
					<div>
						<div class="float_L wdh100">
								<div class="imgBx">
									<img src="<?php if($comment[$x]['userImage']==''){echo getTinyImage('/public/images/photoNotAvailable.gif'); } else {echo getTinyImage($comment[$x]['userImage']);}  ?>" style="cursor:pointer;" />
								</div>
								<div class="cntBx">
									<div class="wdh100 float_L">
										<div class="Fnt11" style="padding-top:2px;">
										  <span>
											  <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $comment[$x]['userId']; ?>');" ><a href="<?php echo $userProfile.$comment[$x]['displayname']; ?>">
											  <?php echo $comment[$x]['displayname']; ?></a></span>&nbsp;
											  <?php echo formatQNAforQuestionDetailPage($comment[$x]['msgTxt'],500);?>
										  </span>
										</div>
										<div class="Fnt11 fcdGya" style="padding-top:2px;">
										  <span><?php echo makeRelativeTime($comment[$x]['creationDate']);?></span>
										</div>
									</div>
								</div>
						</div>
						<s>&nbsp;</s>
					</div>
				</div>
			<?php } 
            $y = $answer['repliesToAnswerCount']+$y;
            ?>
			</div>

		</div>
		<?php } ?>
		<!-- Block End for View All and Comments Link -->

	</div>
	<!-- End of Answer Block -->

<!-- 	<div style="display:none;" id="newRepliesImage_<?php echo $question['threadId']; ?>"><div class="txt_align_c newReplyCircle"><br style="line-height:12px" /><span id="newRepliesCount_<?php echo $myKey.'_'.$question['threadId']; ?>">0 New<br />Reply</span></div></div> -->

</div>
<!-- Main Div End -->
<div class="grayLine">&nbsp;</div>
<div class="lineSpace_15">&nbsp;</div>

<?php
    }
	}else{
			$categoryForDisplay = json_decode($categoryForLeftPanel,true);
			$countryForDisplay = json_decode($countryList,true);
			if(($categoryId == 1) && ($countryId == 1)){
				echo 'No Questions answered by you for any Category or Country';
			}else if(($categoryId == 1) || ($countryId == 1)){
				$catMsg = ($categoryId != 1)?'category '.$categoryForDisplay[$categoryId][0]:' any Category';
				$countryMsg = ($countryId != 1)?'country '.$countryForDisplay[$countryId]:' any Country';
				echo 'No Questions answered by you for '.$catMsg.' and in '.$countryMsg;
			}else{
        		echo 'No Questions answered by you for category '.$categoryForDisplay[$categoryId][0].' and for country  '.$countryForDisplay[$countryId];
			}	
	
	}
    ?> 
</div> 
</div>
