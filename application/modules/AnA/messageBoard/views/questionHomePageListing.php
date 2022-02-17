<?php
	$this->csvThreadIds = '';
	$questionList = $topicListings['results'];
	$arrayOfUsers = $topicListings['arrayOfUsers'];
	//Execute a loop to merge the Question and the Category-Country arrays
	if(isset($topicListings['categoryCountry']) && (count($topicListings['categoryCountry']) > 0)){
	    for($i=0;$i<count($questionList);$i++){
		  for($j=0;$j<count($topicListings['categoryCountry']);$j++){
			if($questionList[$i]['msgId'] == $topicListings['categoryCountry'][$j]['msgId']){
			  $questionList[$i]['category'] = $topicListings['categoryCountry'][$j]['category'];
			  $questionList[$i]['country'] = $topicListings['categoryCountry'][$j]['country'];
			  $questionList[$i]['categoryId'] = $topicListings['categoryCountry'][$j]['categoryId'];
			  $questionList[$i]['countryId'] = $topicListings['categoryCountry'][$j]['countryId'];
			}
	      }
	    }
	}
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$userImageURL = isset($validateuser[0]['avtarurl'])?$validateuser[0]['avtarurl']:'/public/images/photoNotAvailable.gif';

?>

<div>
<?php
	for($i=0;$i<count($questionList);$i++)
	{
		//predictor Widget
		if($i == 2 && $tabselected == 1 && ($categoryId == 56 || $categoryId == 2))
		{
			echo Modules::run('RP/RankPredictorController/getCollegePredictorWidget');
		}
		//predictor widget ends
		if($questionList[$i]['userImage']=='')
		  $questionList[$i]['userImage'] = "/public/images/photoNotAvailable.gif";
		if($this->csvThreadIds == '')
			$this->csvThreadIds = $questionList[$i]['threadId'];
		else
			$this->csvThreadIds .= ",".$questionList[$i]['threadId'];
		
		$idOfanswerCountHolder = 'answerCountHolderForQuestion'.$questionList[$i]['threadId'];
		$ansMsg = ($questionList[$i]['answerCount'] > 1)?('<span id="'.$idOfanswerCountHolder.'">'.$questionList[$i]['answerCount'].'</span> answers'):('<span id="'.$idOfanswerCountHolder.'">'.$questionList[$i]['answerCount'].'</span> answer');
		$ansMsg = '<span>'.$ansMsg.'</span>';
		if($questionList[$i]['answerCount'] == 0){
			$ansMsg = '<span id="'.$idOfanswerCountHolder.'">No</span> answer';
		}
		$viewMsg = ($questionList[$i]['viewCount'] > 1)?($questionList[$i]['viewCount'].' Views'):($questionList[$i]['viewCount'].' View');
		if($questionList[$i]['viewCount'] == 0)
			$viewMsg = 'No views';

		$questionUrl = $questionList[$i]['url'];
		$ansNowLink = "";
		$inlineFormHtml = "";
		if($questionList[$i]['status'] == 'closed'){
			$ansNowLink = '<span class="normaltxt_11p_blk yelloBackForAlreadyAnswer">This question has been closed for answering.</span>';
		}
		if(isset($questionList[$i]['flagForAnswer']) && ($questionList[$i]['flagForAnswer'] > 0)){
			$ansNowLink = '<div class="normaltxt_11p_blk yelloBackForAlreadyAnswer"><img src="/public/images/greenChk.gif" align="absmiddle" />&nbsp;<a href="'.$questionUrl.'"  style="color:#000000;">You have already answered to this question</a></div>';
		}elseif($questionList[$i]['status'] != 'closed' && $userId!=$questionList[$i]['userId']){
			$dataArray = array('showMention'=>true,'userId'=>$userId,'userImageURL'=>$userImageURL,'userGroup' =>$userGroup,'threadId' =>$questionList[$i]['threadId'],'ansCount' => $questionList[$i]['answerCount'],'detailPageUrl' =>$questionUrl,'callBackFunction' => 'try{ addMainCommentForQues('.$questionList[$i]['threadId'].',request.responseText,\'-1\',true,true,\'\',\'\',true,\''.$userImageURL.'\'); } catch (e) {}');
			$inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage',$dataArray,true);
		}

		$editorFlagHTML = '';
		$removeEditorFlagHTML = '';
		if(($userGroup === 'cms') && ($questionList[$i]['editorPickFlag'] > 0)){
			$editorFlagHTML = '<div style="padding-bottom:5px;padding-top:5px;"><div style="width:125px;"><div class="dcms_editorial_pick">Editor\'s Pick</div></div></div>';
			$removeEditorFlagHTML = '<div style="float:right;width:162px;cursor:pointer"><div class="dcms_remove_editorial" onClick="updateEditorialBin('.$questionList[$i]['msgId'].',\'delete\',\'redirect\');">Remove From Editorial</div></div>';
		}
		$displayAnswerBlock = false;
		if($tabselected==2){if(trim($inlineFormHtml) != "") $displayAnswerBlock = true;}
		else{if((trim($inlineFormHtml) != "") || ($questionList[$i]['answerCount'] >= 1)) {$displayAnswerBlock = true;}}
?>

	<!-- Main Div Start -->
	<div class="aqAns" style="border-bottom:1px solid #eaeeed;">

		<div class="lineSpace_10">&nbsp;</div>
		<?php echo $editorFlagHTML; ?>

		<!-- Start Question section -->
		<div class="wdh100">
			
			<!-- Block Start to display the User image -->
			<div class="imgBx">
				<?php if($userId == $questionList[$i]['userId']){ ?>
				  <img id="<?php echo 'userProfileImageForAnswer'.$questionList[$i]['msgId'];?>" src="<?php echo getSmallImage($questionList[$i]['userImage']);?>" />
				<?php }else{ ?>
				  <img src="<?php echo getSmallImage($questionList[$i]['userImage']);?>"/>
				<?php } ?>
			</div>
			<!-- Block End to display the User image -->

			<div class="cntBx">
				<div class="wdh100 float_L">

					<!-- Start Question owner, Level and Question Display section -->
                  	<div class="mb5">
						<div class="mb5">
						
						<a href="<?php echo $questionList[$i]['url'];?>" class="Fnt16 bld" >
						<?php $quesLength = strlen(htmlspecialchars_decode($questionList[$i]['msgTxt']));
							  if($quesLength<=140)
							  { 
								echo "<span class=''>".formatQNAforQuestionDetailPageWithoutLink($questionList[$i]['msgTxt'],140)."</span></a></div>";
							  } 
							  else {
								  echo "<span class='' id='previewQues".$questionList[$i]['msgId']."'>".substr($questionList[$i]['msgTxt'], 0, 137)."</span></a>";
								  echo "<span id='relatedQuesDiv".$questionList[$i]['msgId']."'>&nbsp;<FONT>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$questionList[$i]['msgId']."' onClick='showCompleteAnswerHomepage(".$questionList[$i]['msgId'].");'>more</a></span>";
								  echo "<a class='Fnt16 bld' href=".$questionList[$i]['url']."><span class='' id='completeRelatedQuesDiv".$questionList[$i]['msgId']."' style='display:none;'>".formatQNAforQuestionDetailPageWithoutLink($questionList[$i]['msgTxt'],300)."</span></a></div>";
							  }
							  if(isset($questionList[$i]['descriptionD']) && $questionList[$i]['descriptionD']!=''){
							      $descLength = strlen($questionList[$i]['descriptionD']);
							      if($descLength<=100)
							      { 
								    echo "<div class=''>".insertWbr($questionList[$i]['descriptionD'],50)."</div>";
							      } 
							      else {
								      $newDesscriptionDivId = $questionList[$i]['msgId'].'D'; //To display the more link and show complete description on click of 'more'
								      echo "<div class='' id='previewQues".$newDesscriptionDivId."'>".substr($questionList[$i]['descriptionD'], 0, 100);
								      echo "<span id='relatedQuesDiv".$newDesscriptionDivId."'>&nbsp;<FONT>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$newDesscriptionDivId."' onClick='showCompleteAnswerHomepage(\"".$newDesscriptionDivId."\");'>more</a></span></div>";
								      echo "<div class='' id='completeRelatedQuesDiv".$newDesscriptionDivId."' style='display:none;'>".$questionList[$i]['descriptionD']."</div>";
							      }
							  }
							  if(!isset($questionList[$i]['level']))
								$questionList[$i]['level'] = "Beginner-Level 1";
						?>
						<div class="Fnt11" style="margin-top:5px;">
						<?php if($questionList[$i]['userId']==$userId){ ?>
							  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $questionList[$i]['userId']; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $arrayOfUsers[$questionList[$i]['userId']]['userProfile']; ?>"><b><?php echo $questionList[$i]['userName'];?></b></a></span>, <?=$questionList[$i]['level'];?> &nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"><img src="/public/images/fU.png" /></span>
						<?php }else if($questionList[$i]['vcardStatus']==1){ ?>
							  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $questionList[$i]['userId']; ?>');}catch(e){}" style="width:30px;display:inline;"><span><a href="<?php echo $arrayOfUsers[$questionList[$i]['userId']]['userProfile']; ?>"><b><?php echo $questionList[$i]['userName'];?></b></a> </span></span>, <?=$questionList[$i]['level'];?> 
						<?php }else{ ?>
							  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $questionList[$i]['userId']; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $arrayOfUsers[$questionList[$i]['userId']]['userProfile']; ?>"><b><?php echo $questionList[$i]['userName'];?></b></a></span>, <?=$questionList[$i]['level'];?> 
							  <?php } ?><b>asked&nbsp;</b>
						<?php
						    if($questionList[$i]['listingTitle']!=''){
						    echo "<span > about <b><a href='".$questionList[$i]['instituteurl']."'>".$questionList[$i]['listingTitle']."</a></b></span><br/>";
						  }
						?>
						</div>

					</div>
					<!-- End Question owner, Level and Question Display section -->
					<!-- Start Question Date, Category and Country display section -->
					<div>
					<?php if($questionList[$i]['categoryId'] == 1 && $questionList[$i]['category'] = "Miscellaneous") $questionList[$i]['categoryId'] =0; ?>
						<span class="Fnt11"><span class="grayFont"><?php echo $questionList[$i]['creationDate']; ?></span> in <a href="/messageBoard/MsgBoard/discussionHome/<?php echo $questionList[$i]['categoryId']; ?>/1/<?php echo $questionList[$i]['countryId'];?>"><?php echo $questionList[$i]['category']." - ".$questionList[$i]['country']; ?></a></span>
						<!-- Start Answer, Comment and View Count display section -->
						<?php if($tabselected==2 || $tabselected==1) { ?>
						&nbsp;<span class="Fnt11 grayFont">
						  (<?php echo $viewMsg;?>, <?php echo $ansMsg;?>)
						</span>

						<?php } ?>
						<!-- End Answer, Comment and View Count display section -->
					</div>
					<!-- End Question Date, Category and Country display section -->
					<!-- Block to show the user answers -->
					<div style="display:none;margin-top:10px;margin-bottom:5px;" id="yourAnswer<?php  echo $questionList[$i]['msgId']; ?>">&nbsp;
					</div>
					<!-- Start Answer Display section -->
					<?php if($displayAnswerBlock) { ?>
					<div style="margin-top:5px;">
						<div class="pl33" id="arrowIcon<?php echo $questionList[$i]['msgId'];?>"><img src="/public/images/upArw.png" /></div>
						<!-- Block start for View All Comments Link -->
						<?php if(($questionList[$i]['answerCount'] >= 1) && ($tabselected!=2)){ ?>
						<div class="fbkBx">
							<div>
								<div class="float_L wdh100">
										<div class="Fnt11">
										  <a href="<?php echo $questionList[$i]['url'];?>" class="vAns Fnt11">View <?php if($questionList[$i]['answerCount'] == 1)echo "<span>".$questionList[$i]['answerCount']."</span> answer";else echo "all <span>".$questionList[$i]['answerCount']."</span> answers"?></a>
										</div>
								</div>
								<s>&nbsp;</s>
							</div>
						</div>
						<?php }else{ ?>
						<input type="hidden" id="hideArrowIcon<?php echo $questionList[$i]['msgId'];?>" value="true"/>
						<?php } ?>
						<!-- Block End for View All Comments Link -->
						<!-- Block start for Enter Answer form -->
						<?php if($ansNowLink=='' && $userId!=$questionList[$i]['userId']){ ?>
						<div id="answerForm<?php echo $questionList[$i]['msgId'];?>" >
						    <div class="fbkBx">
							<div>
								<div class="float_L wdh100">
									<?php echo $inlineFormHtml; ?>
								</div>
								<s>&nbsp;</s>
							</div>
						    </div>
						</div>
						<?php } ?>
						<!-- Block End for Enter Answer form -->
					</div>
					<?php } ?>
					<!-- End Answer Display section -->
					<!-- Start Message for Already answered or Closed question section -->
					<div>
						<?php if(($ansNowLink != "")){ ?>
						<div style="color:#86878c;padding-top:5px;padding-bottom:5px;height:22px">
							<div style="width:100%">
								<div style="line-height:20px;">
									<div class="fontSize_12p"><?php echo $ansNowLink; ?></div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<!-- End Message for Already answered or Closed question section -->
					<!-- Start Removal from Editorial section -->
					<div>
						<?php if((strcmp($userGroup,'cms') == 0)&&($removeEditorFlagHTML!='')){ ?>
						<div style="color:#86878c;padding-top:5px;padding-bottom:5px;height:22px">
							<div style="width:100%">
								<?php echo $removeEditorFlagHTML; ?>
							</div>
						</div>
						<?php } ?>
					</div>
					<!-- End Removal from Editorial section -->

				</div>
			</div>
			<div class="clear_B"></div>
		</div>
		<!-- End Question section -->

		<div class="lineSpace_10">&nbsp;</div>

	</div>
	<!-- Main Div End -->
<?php
}
?>

<?php
	if(count($questionList) == 0){
		echo "<span style='font-size:14px;'>No Questions found.</span>";
	}
?>
</div>
