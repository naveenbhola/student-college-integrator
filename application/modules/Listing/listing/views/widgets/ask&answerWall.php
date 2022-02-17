<?php if(!$ajaxCall){ ?>
<?php if(!$relatedQuestions){
    $relatedQuestionsInstituteCategories='false';?>
    <?php if(count($topicListings['0']['results'])>0){?>
        <h4 class="section-cont-title">Latest Q&amp;A about <b><?php echo $titleOfInstitute;?></b></h4>    
        <script>var errorMessageHasBeenDisplayed = false;</script>
    <?php }else{?>
        <div class="showMessages mb10">No questions and answers about <b><?php echo $titleOfInstitute;?></b> found.</div>
        <script>var errorMessageHasBeenDisplayed = true;</script>
    <?php }?>
<?php }else{ ?>
    <div style="margin-top:30px">&nbsp;</div>
    <?php if(!empty($questionIds) && isset($questionIds[0]) && $questionIds[0]!=''){ ?>
        <h4 class="section-cont-title">Related Questions</h4>
    <?php }else{
	$relatedQuestionsInstituteCategories ='true';?>
        <div class="showMessages mb10" id="relatedErrorMessage">No Related Questions about <b><?php echo $titleOfInstitute;?></b> found.</div>
        <script>if(errorMessageHasBeenDisplayed){
            if($('relatedErrorMessage')) $('relatedErrorMessage').style.display = 'none';
        } </script>
        <h4 class="section-cont-title">Related Questions in the institute's categories</h4>
    <?php }?>
<?php } ?>
<?php } ?>

<?php
	$this->csvThreadIds = '';
	if(isset($threadIdCsv)) $this->csvThreadIds = $threadIdCsv;
	$questionList = $topicListings['0']['results'];
	//$levelVCard = $topicListings['0']['levelVCard'];
	$categoryCountry = $topicListings['0']['categoryCountry'];
	$levelAdvance = isset($topicListings['0']['levelAdvance'])?$topicListings['0']['levelAdvance']:array();
	//Code Start to create VCard, User level, Category and Country arrays with UserId as Index
	$VCardArray = array();
	$userLevel = array();
    for($i=0;$i<count($levelVCard);$i++){
	  $userIdTemp = $levelVCard[$i]['userid'];
	  $VCardArray[$userIdTemp] = $levelVCard[$i]['vcardStatus'];
	  $userLevel[$userIdTemp] = $levelVCard[$i]['ownerLevel'];
	}
	$CategoryCountryArray = array();
    for($i=0;$i<count($categoryCountry);$i++){
	  $threadIdTemp = $categoryCountry[$i]['msgId'];
	  $CategoryCountryArray[$threadIdTemp]['category'] = $categoryCountry[$i]['category'];
	  $CategoryCountryArray[$threadIdTemp]['categoryId'] = $categoryCountry[$i]['categoryId'];
	  $CategoryCountryArray[$threadIdTemp]['country'] = $categoryCountry[$i]['country'];
	  $CategoryCountryArray[$threadIdTemp]['countryId'] = $categoryCountry[$i]['countryId'];
	}
	//Code End to create VCard, User level, Category and Country arrays with UserId as Index
	//Code Start to merge the Result and the Level advance arrays
        $mainArray = array();
	$x=0;
	for($j=0;$j<count($questionList);$j++){
	  $mainArray[$x] = $questionList[$j];
	  $x++;
	}
	for($j=0;$j<count($levelAdvance);$j++){
	  $mainArray[$x] = $levelAdvance[$j];
	  $x++;
	}
	//uasort($mainArray, 'cmp');
	
        //error_log(print_r($levelAdvance,true),3,'/home/aakash/Desktop/aakash.log');
	//Code End to merge the Result and the Level advance arrays
	$x=0;
	$questionList=array();
	foreach($mainArray as $resultBlock){
	  if($x<10)
		$questionList[$x] = $resultBlock;
	  $x++;
	} 
        //error_log(print_r(count($questionList),true),3,'/home/aakash/Desktop/aakash.log');
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$userImageURLDisplay = isset($validateuser[0]['avtarurl'])?$validateuser[0]['avtarurl']:'/public/images/photoNotAvailable.gif';

	$userProfile = site_url('getUserProfile').'/';
	$displayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';

	//function cmp($a,$b)
	//{
	//  if ($a['sortingTime'] == $b['sortingTime']) return 0;
	//  return ($a['sortingTime'] < $b['sortingTime']) ? 1 : -1;
	//}

	function getDisplayNameText($userId, $entityUserId, $displayName, $vcardStatus)
	{
		$userProfile = site_url('getUserProfile').'/';
		if($entityUserId==$userId){
			  $userDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$entityUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$displayName.'">'.$displayName.'</a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
		//}else if($vcardStatus==1){
			//  $userDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$entityUserId.');}catch(e){}" style="width:30px;display:inline;" ><span class="flwMeBg"><a href="'.$userProfile.$displayName.'">'.$displayName.'</a> &nbsp; <img src="/public/images/flwMe.png" /></span></span>';
		}else{
			  $userDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$entityUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$displayName.'">'.$displayName.'</a></span>';
		}
		return $userDisplayText;
	}

	function getTheRatingStarClass($level)
	{
	  $html = '';
	  $level = trim($level);
	  switch($level){
		case 'Advisor': $html = "str_1s";$value="Advisor";break;
		case 'Senior Advisor': $html = "str_2s";$value="Senior Advisor";break;
		case 'Lead Advisor': $html = "str_3s";$value="Lead Advisor";break;
		case 'Principal Advisor': $html = "str_4s";$value="Principal Advisor";break;
		case 'Chief Advisor': $html = "str_5s";$value="Chief Advisor";break;
	  }
	  return array('html'=>$html,'value'=>$value);
	}

?>
<script>
currentPageName = "ANA WALL";
</script>
<div>

    <?php if(!$relatedQuestions){ ?>
	<div style="display:none;" id="abuseFormText"></div>
	<!-- Start of Report abuse confirmation overlay -->
	<div style="display:none;" id="reportAbuseConfirmationDiv">
		<div>
			<div style="padding:10px 10px 10px 10px">
			<div class="lineSpace_5p">&nbsp;</div>
			<div align="center"><span id="reportAbuseConfirmation" style="font-size:14px;"></span></div>
			<div class="lineSpace_5p">&nbsp;</div>
			<div align="center"><input type="button" value="OK" class="spirit_header RegisterBtn" onClick="javascript:hideOverlay();" /></div>
			<div class="lineSpace_5p">&nbsp;</div>
			</div>
		</div>
	</div>
	<!-- End of Report abuse confirmation overlay -->

    <div id = "digUpDownTooltip" class="blur" style="position:absolute;left:0px;z-index:1000;display:none;top:0px;">
      <div class="shadow">
          <div class="content" id="digTooltipContent"></div>
      </div>
    </div>

    <?php } ?>
    
<?php $questionIds = implode(",",$questionIds);
      $categoryId = (is_array($categoryId))?implode(",",$categoryId):$categoryId;
?>
<?php

	for($i=0;$i<count($questionList);$i++)
	{
		if($questionList[$i]['type']!='level'){	//If for Level type
		if($this->csvThreadIds == '')
			$this->csvThreadIds = $questionList[$i][0]['threadId'];
		else
			$this->csvThreadIds .= ",".$questionList[$i][0]['threadId'];

		$idOfanswerCountHolder = 'answerCountHolderForQuestion'.$questionList[$i]['threadId'];
		$ansMsg = ($questionList[$i][0]['msgCount'] > 1)?('<span id="'.$idOfanswerCountHolder.'">'.$questionList[$i][0]['msgCount'].'</span> answers'):('<span id="'.$idOfanswerCountHolder.'">'.$questionList[$i]['answerCount'].'</span> answer');
		$ansMsg = '<span>'.$ansMsg.'</span>';
		if($questionList[$i][0]['msgCount'] == 0){
			$ansMsg = '<span id="'.$idOfanswerCountHolder.'">No</span> answer';
		}
		$commentMsg = '';
		if(isset($questionList[$i][1]['commentCount'])){
		  $commentMsg = ($questionList[$i][1]['commentCount'] > 0)?'('.($questionList[$i][1]['commentCount'].' people commented on this answer)'):'';
		  if($questionList[$i][1]['commentCount'] == 1)
			$commentMsg = '('.$questionList[$i][1]['commentCount'].' person commented on this answer)';
		}

		$questionUrl = $questionList[$i][0]['url'];
		$ansNowLink = "";
		$inlineFormHtml = "";
		if($questionList[$i][0]['status'] == 'closed'){
			$ansNowLink = '<span class="normaltxt_11p_blk ">This question has been closed for answering.</span>';
		}
        if(!empty($questionIds))
          $textareaHeight = '18px';
        else
          $textareaHeight = '24px';

		if(isset($questionList[$i][0]['alreadyAnswered']) && ($questionList[$i][0]['alreadyAnswered'] > 0)){
			$ansNowLink = '<div class="normaltxt_11p_blk "><img src="/public/images/greenChk.gif" align="absmiddle" />&nbsp;<a href="'.$questionUrl.'"  style="color:#000000;">You have already answered this question</a></div>';
		}elseif($questionList[$i][0]['status'] != 'closed' && $userId!=$questionList[$i][0]['userId'] && $questionList[$i]['type']!='comment'){
			$dataArray = array('userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$questionList[$i][0]['threadId'],'ansCount' => $questionList[$i][0]['msgCount'],'detailPageUrl' =>$questionUrl,'callBackFunction' => 'try{ addMainCommentForQues('.$questionList[$i][0]['threadId'].',request.responseText,\'-1\',true,true,\'\',\'\',true,\''.$userImageURLDisplay.'\',0,false,false,true); } catch (e) {}','tHeight' => $textareaHeight);
			$inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage',$dataArray,true);
		}
		if($questionList[$i]['type']=='comment'){
			$functionToCall = isset($functionToCall)?$functionToCall:'-1';
			$dataArray = array('userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$questionList[$i][0]['threadId'],'ansCount' => $questionList[$i][0]['msgCount'],'detailPageUrl' =>$questionUrl,'functionToCall' => $functionToCall, 'fromOthers' => 'user', 'msgId' => $questionList[$i][1]['msgId'], 'mainAnsId' => $questionList[$i][1]['msgId'], 'dotCount'=>2 , 'displayname'=> $displayName, 'sortFlag'=>2, 'wall'=>1,'tHeight' => $textareaHeight);
			$inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage_Comment',$dataArray,true);
		}
		$questionUserDisplayName = $questionList[$i][0]['displayname'];
		$questionUserId = $questionList[$i][0]['userId'];
		$questionUserLevel = getTheRatingStar($userLevel[$questionUserId]);
		$questionVCardStatus = $VCardArray[$questionUserId];
		$questionText = $questionList[$i][0]['msgTxt'];
		$questionMsgId = $questionList[$i][0]['msgId'];
		if($questionList[$i]['type'] == 'question' || ($questionList[$i]['type'] == 'answer' && $relatedQuestionsInstituteCategories!='true')){
			$userImageURL = $questionList[$i][0]['userImage'];
			$userImageURLOwnerId = $questionList[$i][0]['userId'];
			$questionFontStyle = "style='color:#000000'";
			$questionFontClass = "";
		}
		if($relatedQuestionsInstituteCategories=='true' && $questionList[$i]['type']!= 'question'){
		     $userImageURL = $questionList[$i][1]['userImage'];
		     $userImageURLOwnerId = $questionList[$i][1]['userId'];
		     $answerUserDisplayName = $questionList[$i][1]['displayname'];
		    $answerUserId = $questionList[$i][1]['userId'];
		     $answerText = $questionList[$i][1]['msgTxt'];
			$answerMsgId = $questionList[$i][1]['msgId'];
			$answerUserLevel = getTheRatingStar($userLevel[$answerUserId],$questionList[$i]['type']);
		}
		if(($questionList[$i]['type'] == 'answer')){	
			$answerUserDisplayName = $questionList[$i][1]['displayname'];
			$answerUserId = $questionList[$i][1]['userId'];
			$answerUserLevel = getTheRatingStar($userLevel[$answerUserId],$questionList[$i]['type']);
			$answerVCardStatus = $VCardArray[$answerUserId];
			$answerText = $questionList[$i][1]['msgTxt'];
			$answerMsgId = $questionList[$i][1]['msgId'];
			$questionFontStyle = "style='color:#000000'";
			//$questionFontClass = "class='grayFont'";
		}

		$answerFontStyle = "style='color:#000000'";
		$answerFontClass = "";
		if($questionList[$i]['type'] == 'rating' && $relatedQuestionsInstituteCategories=='true'){
			$userImageURL = $questionList[$i][2]['userImage'];
			$userImageURLOwnerId = $questionList[$i][2]['userId'];
			$raterUserDisplayName = $questionList[$i][2]['displayname'];
			$raterUserId = $questionList[$i][2]['userId'];
			$raterUserLevel = getTheRatingStar($userLevel[$raterUserId]);
			$raterVCardStatus = $VCardArray[$raterUserId];
		}
		else if($questionList[$i]['type'] == 'comment' && $relatedQuestionsInstituteCategories=='true'){
			$commentNumberToDisplay = count($questionList[$i])-3;
			$userImageURL = $questionList[$i][$commentNumberToDisplay]['userImage'];
			$userImageURLOwnerId = $questionList[$i][$commentNumberToDisplay]['userId'];
			$commenterUserDisplayName = $questionList[$i][$commentNumberToDisplay]['displayname'];
			$commenterUserId = $questionList[$i][$commentNumberToDisplay]['userId'];
			$commenterUserLevel = getTheRatingStar($userLevel[$commenterUserId]);
			if($pageKeySuffixForDetail=='ANA_USER_PROFILE'){	//In case of Profile Wall
			  if(isset($viewedOwnerDetails) && is_array($viewedOwnerDetails)){
				$commenterUserLevel = getTheRatingStar($viewedOwnerDetails['viewedUserLevel']);
				$userImageURL = $viewedOwnerDetails['viewedUserImage'];
			  }
			  for($z=0;$z<count($questionList[$i]);$z++){
				if($questionList[$i][$z]['userId']==$viewedUserId)
				  $userCommentTimeDisplayNumber = ($z);
			  }
			}
			$commenterVCardStatus = $VCardArray[$commenterUserId];
			$answerFontStyle = "style='color:#707070'";
			//$answerFontClass = "class='grayFont'";
		}

		if($questionUserId==$userId){
			  $questionUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$questionUserDisplayName.'">'.$questionUserDisplayName.'\'s</a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
		//}else if($questionVCardStatus==1){
			//  $questionUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionUserId.');}catch(e){}" style="width:30px;display:inline;" ><span class="flwMeBg"><a href="'.$userProfile.$questionUserDisplayName.'">'.$questionUserDisplayName.'\'s</a> &nbsp; <img src="/public/images/flwMe.png" /></span></span>';
		}else{
			  $questionUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$questionUserDisplayName.'">'.$questionUserDisplayName.'\'s</a></span>';
		}
		$questionUserDisplayText = getDisplayNameText($userId, $questionUserId, $questionUserDisplayName, $questionVCardStatus);
		$questionUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$questionUserLevel.'</a></span>';
		if($answerUserId==$userId){
			  $answerUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$answerUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$answerUserDisplayName.'">'.$answerUserDisplayName.'\'s</a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
		//}else if($answerVCardStatus==1){
			//  $answerUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$answerUserId.');}catch(e){}" style="width:30px;display:inline;" ><span class="flwMeBg"><a href="'.$userProfile.$answerUserDisplayName.'">'.$answerUserDisplayName.'\'s</a> &nbsp; <img src="/public/images/flwMe.png" /></span></span>';
		}else{
			  $answerUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$answerUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$answerUserDisplayName.'">'.$answerUserDisplayName.'\'s</a></span>';
		}
		$answerUserDisplayText = getDisplayNameText($userId, $answerUserId, $answerUserDisplayName, $answerVCardStatus);
		$answerUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$answerUserLevel.'</a></span>';
		$raterUserDisplayText = getDisplayNameText($userId, $raterUserId, $raterUserDisplayName, $raterVCardStatus);
		$raterUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$raterUserLevel.'</a></span>';
		$commenterUserDisplayText = getDisplayNameText($userId, $commenterUserId, $commenterUserDisplayName, $commenterVCardStatus);
		if($pageKeySuffixForDetail=='ANA_USER_PROFILE')	//In case of Profile Wall
		  $commenterUserDisplayText = getDisplayNameText($userId, $viewedUserId, $viewedDisplayName,0);
		$commenterUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$commenterUserLevel.'</a></span>';
		$catCountLink = '/messageBoard/MsgBoard/discussionHome/'.$CategoryCountryArray[$questionMsgId]['categoryId'].'/0/'.$CategoryCountryArray[$questionMsgId]['countryId'];
		$catCountText = $CategoryCountryArray[$questionMsgId]['category'].' - '.$CategoryCountryArray[$questionMsgId]['country'];
		$userImageURL = ($userImageURL=='')?'/public/images/photoNotAvailable.gif':$userImageURL;

		$displayListing = false;
		$listingTitle='';
		if($pageKeySuffixForDetail=='ANA_USER_PROFILE'){
		  $displayListing = true;
		  $listingTitle = $questionList[$i][0]['listingTitle'];
		}
?>
	<!-- Main Div Start -->
	<div class="aqAns" style="border-bottom:1px solid #eaeeed;">

		<div class="spacer10 clearFix"></div>
		<!-- Start Question section -->
		<div class="wdh100">
			<?php
			if($questionList[$i]['type']=='bestanswer')
			  echo "<div style='padding-bottom:10px;'><img src='/public/images/congrates.gif' align='absmiddle' />&nbsp;<b>".$answerUserDisplayText."</b>&nbsp;".$answerUserLevelText." your answer is selected as <b>\"The Best Answer\"</b> by ".$questionUserDisplayText."</div>";
			?>

			<!-- Block Start to display the User image -->
			<div class="imgBx">
				<?php if($userId == $userImageURLOwnerId){ ?>
				  <img id="<?php echo 'userProfileImageForAnswer'.$questionList[$i][0]['msgId'];?>" src="<?php echo getSmallImage($userImageURL);?>" />
				<?php }else{ ?>
				  <img src="<?php echo getSmallImage($userImageURL);?>"/>
				<?php } ?>
			</div>
			<!-- Block End to display the User image -->

			<div class="cntBx">
				<div class="wdh100 float_L">
					<!-- Start of Question Headline Block -->
					<?php
					    if($relatedQuestionsInstituteCategories =='true'){
						switch($questionList[$i]['type']){
							//case 'question':
							//	  echo "<div style='padding-bottom:6px;'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>asked</b> a question <span class='Fnt11 grayFont'>".$questionList[$i][0]['creationDate']."</span></div>";
							//	  break;
							case 'answer':
								  echo "<div style='padding-bottom:6px;'><b>".$answerUserDisplayText."</b>&nbsp;".$answerUserLevelText." <b>answered</b> ".$questionUserDisplayTextForHeading." question <span class='Fnt11 grayFont'>".makeRelativeTime($questionList[$i][1]['creationDate'])."</span></div>";
								  break;
							case 'rating':
								  $showOtherRating = '';
								  if($questionList[$i][1]['digUp']==2) $showOtherRating = " and <a href='javascript:void(0);' onclick='showOtherRating(".$answerMsgId.",".$raterUserId.");'>".($questionList[$i][1]['digUp']-1)." other</a>";
								  else if($questionList[$i][1]['digUp']>2) $showOtherRating = " and <a href='javascript:void(0);' onclick='showOtherRating(".$answerMsgId.",".$raterUserId.");'>".($questionList[$i][1]['digUp']-1)." others</a>";
								  echo "<div style='padding-bottom:6px;'><b>".$raterUserDisplayText."</b>&nbsp;".$raterUserLevelText.$showOtherRating." gave <b>thumbs up <img src='/public/images/hUp.gif'/></b> to ".$answerUserDisplayTextForHeading." answer</div>";
								  break;
							case 'comment':
								  if($commenterUserId==$answerUserId) $displayNameString = "his/her own"; else $displayNameString = $answerUserDisplayTextForHeading;
								  if($pageKeySuffixForDetail=='ANA_USER_PROFILE'){	//In case of Profile Wall
									if($viewedUserId==$answerUserId) $displayNameString = "his/her own";
									echo "<div style='padding-bottom:6px;'><b>".$commenterUserDisplayText."</b>&nbsp;".$commenterUserLevelText." <b>commented</b> on ".$displayNameString." answer <span class='Fnt11 grayFont'>".makeRelativeTime($questionList[$i][$userCommentTimeDisplayNumber]['creationDate'])."</span></div>";
								  }
								  else
									echo "<div style='padding-bottom:6px;'><b>".$commenterUserDisplayText."</b>&nbsp;".$commenterUserLevelText." <b>commented</b> on ".$displayNameString." answer <span class='Fnt11 grayFont'>".makeRelativeTime($questionList[$i][$commentNumberToDisplay]['creationDate'])."</span></div>";
								  break;
						}
					    }
					?>
					<!-- End of Question Headline Block -->
					<!-- Start Question owner, Level and Question Display section -->
                  	<div class="ana-box">
                    	<span class="<?php if($questionList[$i]['type'] != 'question')echo "sprite-bg ques-icn"; ?>"></span>
                        <div class="ques-cont">
						<?php if($questionList[$i]['type'] != 'question') echo $questionUserDisplayText; else echo "<b>".$questionUserDisplayText." asked</b>";?>
						<a href="<?php echo $questionList[$i][0]['url'];?>" <?php echo $questionFontStyle;?>
						<?php
							  $questionText = html_entity_decode(html_entity_decode($questionText,ENT_NOQUOTES,'UTF-8'));
							  $quesLength = strlen($questionText);
							  if($quesLength<=300){ $questionText = formatQNAforQuestionDetailPageWithoutLink($questionText,300);echo "<span ".$questionFontClass.">".$questionText."</span></a>";}
							  else {
								  echo "<span ".$questionFontClass." id='previewQues".$questionList[$i][0]['msgId']."'>".substr($questionText, 0, 297)."</span></a>";
								  echo "<span id='relatedQuesDiv".$questionList[$i][0]['msgId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$questionList[$i][0]['msgId']."' onClick='showCompleteAnswerHomepage(".$questionList[$i][0]['msgId'].");'>more</a></span>";
								  $questionText = formatQNAforQuestionDetailPageWithoutLink($questionText,300);
								  echo "<a href=".$questionList[$i][0]['url']." ".$questionFontStyle."><span ".$questionFontClass." id='completeRelatedQuesDiv".$questionList[$i][0]['msgId']."' style='display:none;'>".$questionText."</span></a>";
							  }
						?>
						<!-- Start Question Date, Category and Country display section -->
						<?php if($questionList[$i]['type'] == 'question'){ ?>
						<div class='mtb5'>
							<?php if(($displayListing)&&($listingTitle!='' )){?>
							  <span class="Fnt11 float_L"><?php if($relatedQuestionsInstituteCategories == 'true')echo "<span class='Fnt11 grayFont'>".makeRelativeTime($questionList[$i][0]['creationDate'])."</span> ";?>about <span><?php echo $listingTitle;?></span></span>
							<?php }else{ ?>
							  <span class="Fnt11 float_L"><?php if($questionList[$i]['type'] == 'question')  if($relatedQuestionsInstituteCategories == 'true'){echo "<span class='Fnt11 grayFont'>".makeRelativeTime($questionList[$i][0]['creationDate'])."</span> ";}?>in <a href="/messageBoard/MsgBoard/discussionHome/<?php echo $CategoryCountryArray[$questionMsgId]['categoryId']; ?>/0/<?php echo $CategoryCountryArray[$questionMsgId]['countryId'];?>" style="color:#707070;"><?php echo $CategoryCountryArray[$questionMsgId]['category']." - ".$CategoryCountryArray[$questionMsgId]['country']; ?></a></span>
							<?php } ?>
							<!-- Block Start for Report Abuse link -->
							<div class="float_R" style="valign:top;">
								<div>
										<?php if($userId!=$questionUserId){ if($questionList[$i][0]['reportedAbuse']==0){
										  if(!(($isCmsUser == 1)&&($questionList[$i][0]['status']=='abused'))){
										?>
										<span id="abuseLink<?php echo $questionMsgId;?>"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $questionMsgId; ?>','<?php echo $questionUserId;?>','<?php echo $questionMsgId; ?>','<?php echo $questionMsgId; ?>','<?php echo "Question"; ?>',0,0);return false;">Report&nbsp;Abuse</a></span>
										<?php }}else{ ?>
										<span id="abuseLink<?php echo $questionMsgId;?>">Reported as inappropriate</span>
										<?php }} ?>
								</div>
							</div>
						</div>
						<div class="clear_B"></div>
						<!-- Block End for Report Abuse link -->
						<?php }else{ ?>
						<div class='mtb5'>
							<?php if(($displayListing)&&($listingTitle!='')){?>
							  <span>about <span class="grayFont"><span><b><?php echo $listingTitle;?></b></span></span></span>
							<?php }else{ ?>
							  <span>in <span class="grayFont"><a href="<?php echo $catCountLink;?>" style="color:#707070;"><?php echo $catCountText;?></a></span></span>
							<?php } ?>
						</div>
						<?php } ?>
                        </div>
						<!-- End Question Date, Category and Country display section -->
					</div>
					<!-- End Question owner, Level and Question Display section -->

                    <div class="clear_B"></div>
					<!-- Start Answer owner, Level and Answer Display section -->
					<?php if($questionList[$i]['type'] != 'question'){ ?>
                  	<div class="ana-box">
                    	<span class="sprite-bg ans-icn"></span>
                        <div class="ans-cont">
						<?php if($answerUserId==$userId){ ?>
							  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $answerUserId; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $userProfile.$answerUserDisplayName; ?>"><?php echo $answerUserDisplayName;?></a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"><img src="/public/images/fU.png" /></span>
						<?php }else{ ?>
							  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $answerUserId; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $userProfile.$answerUserDisplayName; ?>"><?php echo $answerUserDisplayName;?></a></span>
						<?php } ?>
						<?php
							  $answerText = html_entity_decode(html_entity_decode($answerText,ENT_NOQUOTES,'UTF-8'));
							  $quesLength = strlen($answerText);
							  if($quesLength<=300){ $answerText = formatQNAforQuestionDetailPage($answerText,300); echo "<span ".$answerFontClass.">".$answerText."</span>";}
							  else {
								  echo "<span style='word-wrap:break-word' ".$answerFontClass." id='previewQues".$questionList[$i][1]['msgId']."'>".substr($answerText, 0, 297)."</span>";
								  echo "<span id='relatedQuesDiv".$questionList[$i][1]['msgId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$questionList[$i][1]['msgId']."' onClick='showCompleteAnswerHomepage(".$questionList[$i][1]['msgId'].");'>more</a></span>";
								  $answerText = formatQNAforQuestionDetailPage($answerText,300);
								  echo "<span ".$answerFontClass." id='completeRelatedQuesDiv".$questionList[$i][1]['msgId']."' style='display:none;'>".$answerText."</span>";
							  }
						?>
						<!-- Start Answer Date, Category and Country display section -->
						<?php if($questionList[$i]['type'] != 'question'){ ?>
						<div class="mtb5">
                                                    <span class="grayFont"><?php if($relatedQuestionsInstituteCategories == 'true')echo makeRelativeTime($questionList[$i][1]['creationDate']); ?></span>
						</div>
						<?php } ?>
						<!-- End Answer Date, Category and Country display section -->

						<!-- Block Start for Bottom navigation links like Digup, dig down, report abuse -->
						<?php if($questionList[$i]['type'] != 'question'){ ?>
						<div class="lineSpace_5">&nbsp;</div>
						<div class="wdh100">
								<!-- Block Start for Dig up and Dig down -->
								<div class="float_L" style="width:400px;">
										<table cellspacing='0' cellpadding='0' border='0'>
										<tr>
										  <td colspan='2'>
											<div style="margin-top:10px">
											  <a href="javascript:void(0);" onMouseOver = "showLikeDislike(0,'<?php echo $answerMsgId; ?>');" onMouseOut = "hideLikeDislike(0,'<?php echo $answerMsgId; ?>');" onClick="updateDig(this,'<?php echo $answerMsgId; ?>',1);trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="rUp" style="color:#000;text-decoration:none"><?php echo $questionList[$i][1]['digUp']; ?></a>
											  <a href="javascript:void(0);" onMouseOver = "showLikeDislike(1,'<?php echo $answerMsgId; ?>');" onMouseOut = "hideLikeDislike(1,'<?php echo $answerMsgId; ?>');" onClick="updateDig(this,'<?php echo $answerMsgId; ?>',0);trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="rDn" style="color:#000;text-decoration:none"><?php echo $questionList[$i][1]['digDown']; ?></a>
											</div>
										  </td>
										  <td width="80%">
											  <?php if($questionList[$i]['type'] != 'comment'){ ?>
											  <span style="padding-left:10px;" class="quesAnsBullets"><a href="javascript:void(0);" onClick="showCommentSection('<?php echo $answerMsgId; ?>',true,'<?php echo $questionMsgId; ?>');">Comment</a>&nbsp;</span>
											  <a href="javascript:void(0);"  onClick="showCommentSection('<?php echo $answerMsgId; ?>',false,'<?php echo $questionMsgId; ?>');" style="color:#707070;"><?php echo $commentMsg;?></a>
											  <?php } ?>
										  </td>
										</tr>
										<tr>
										  <td width='10%'>
											<div id="likeDiv<?php echo $answerMsgId; ?>" style="display:block;visibility:hidden;"></div>
										  </td>
										  <td>
											<div id="dislikeDiv<?php echo $answerMsgId; ?>" style="display:block;visibility:hidden;"></div>
										  </td>
										</tr>
										</table>
								</div>
								<!-- Block End for Dig up and Dig down -->
								<!-- Block Start for Report Abuse link -->
								<div class="float_R" style="valign:top;">
									<div>
											<?php if($userId!=$answerUserId){ if($questionList[$i][1]['reportedAbuse']==0){
											  if(!(($isCmsUser == 1)&&($questionList[$i][1]['status']=='abused'))){
											?>
											<span id="abuseLink<?php echo $answerMsgId;?>"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $answerMsgId; ?>','<?php echo $answerUserId;?>','<?php echo $questionMsgId; ?>','<?php echo $questionMsgId; ?>','<?php echo "Answer"; ?>',0,0);return false;">Report&nbsp;Abuse</a></span>
											<?php }}else{ ?>
											<span id="abuseLink<?php echo $answerMsgId;?>" class="Fnt11">Reported as inappropriate</span>
											<?php }} ?>
									</div>
								</div>
								<!-- Block End for Report Abuse link -->

							<div class="clear_B">&nbsp;</div>
						</div>
						<?php } ?>
						<!-- Block End for Bottom navigation links like Digup, dig down, report abuse -->

						<!-- Block Start for Confirm message like for displaying confirmation messge for dig up -->
						<div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?php  echo $answerMsgId; ?>">&nbsp;</div>
						<!-- Block End for Confirm message like for displaying confirmation messge for dig up -->
                        </div>
					</div>
					<!-- Block Start for displaying the Comment Section -->
					<div style="display:none;margin-top:5px;margin-bottom:5px;padding-left:33px;" id="commentDisplaySection<?php  echo $answerMsgId; ?>">&nbsp;</div>
					<!-- Block End for displaying the Comment Section -->
					<?php } ?>
					<!-- End Answer owner, Level and Answer Display section -->

                    <div class="clear_B"></div>
					<!-- Block Start for Answer display Section -->
					<?php if(($questionList[$i]['type'] != 'comment')){ ?>
					  <div style="display:none;margin-bottom:5px;" id="answerSection<?php  echo $questionMsgId; ?>"></div>
					<?php } ?>
					<!-- Block End for Answer display Section -->
					<!--Start_AbuseForm-->
					<div style="display:none;" class="formResponseBrder" id="abuseForm<?php if($questionList[$i]['type'] != 'question') echo $answerMsgId;else echo $questionMsgId;?>"></div>
					<!--End_AbuseForm-->

                    <script>
                         var start<?php echo $questionMsgId;?> = 0;
                    </script>
					<!-- Block start for View More Answers -->
					<?php if(($questionList[$i]['type']!='comment')){ ?>
					  <?php if(($questionList[$i][0]['msgCount'] > 1)&&(($questionList[$i]['type'] != 'question'))){ ?>
						<div style="margin-top:5px;margin-bottom:5px;" id="extraAnswersDiv<?php echo $questionMsgId;?>">
						  <a href="javascript:void(0);" class="Fnt11" onClick="return getTopAnswersForWall(<?php echo $questionMsgId;?>,<?php echo $answerMsgId;?>,'<?php echo $catCountLink;?>','<?php echo $catCountText;?>','<?php echo $questionList[$i][0]['msgCount']; ?>',start<?php echo $questionMsgId;?>,'count<?php echo $questionMsgId;?>');" id="viewallLink<?php echo $questionMsgId;?>"><span id="answerCountHolder<?php echo $questionMsgId;?>"><?php echo ($questionList[$i][0]['msgCount'] - 1); ?></span> More <?php if(($questionList[$i][0]['msgCount'] - 1)==1) echo "Answer";else echo "Answers"; ?> <img border='0' src="/public/images/barw.gif" align="absmiddle"/></a>&nbsp;<span id="viewallLinkImage<?php echo $questionMsgId;?>" style="display:none;"><img src="/public/images/working.gif" align="absmiddle"/></span>
						  <span id="viewallAnswerSpan<?php echo $questionMsgId;?>" class="Fnt11" ><a href="<?php echo $questionUrl;?>" style="display:none" class="Fnt11" id="viewallAnswerLink<?php echo $questionMsgId;?>">View all <?php echo $questionList[$i][0]['msgCount'];?> answers</a></span>
						</div>
					<?php } } ?>
					<!-- Block end for View More Answers -->
					<input type="hidden" id="start<?php echo $questionMsgId;?>" value=0 />
					<input type="hidden" id="count<?php echo $questionMsgId;?>" value=2 />
					<input type="hidden" id="showDate<?php echo $questionMsgId;?>" value="<?php echo $relatedQuestionsInstituteCategories ?>" />
					<!-- Block Start for Answer display Section -->
					<?php if(($questionList[$i]['type'] != 'comment')){ ?>
					  <div style="display:none;margin-top:10px;margin-bottom:5px;" id="yourAnswer<?php  echo $questionMsgId; ?>">&nbsp;
					  </div>
					<?php } ?>
					<!-- Block End for Answer display Section -->

					<!-- Start Answer Display section -->
					<?php if(true) { ?>
					<?php if($questionList[$i]['type']!='comment') $commentIdName = 'commentSection'; else $commentIdName = 'commentSectionDisplayDiv'; ?>
					<div id="<?php echo $commentIdName.$questionMsgId;?>" style="margin-top:5px;">
						<?php if($questionList[$i]['type']=='comment'){ ?>
						  <div class="pl33" style='padding-left:66px;'><img src="/public/images/upArw.png" /></div>
						<?php }else if(!((($questionList[$i][0]['msgCount']==0) && ($questionUserId==$userId || $inlineFormHtml==''))) && ($ansNowLink=='')){ ?>
						<?php if(!($questionUserId==$userId && $questionList[$i]['type']!='comment')){ ?>
						  <div class="pl33" <?php if($questionList[$i]['type']=='comment')echo "style='padding-left:66px;'";?>><img src="/public/images/upArw.png" /></div>
						<?php } }
						if($questionList[$i]['type']=='comment'){ ?>
						<!-- Block start for View All Answers/Comments Link -->
						  <div id="<?php echo 'repliesContainer'.$answerMsgId; ?>" style="display:block;padding-left:33px;">
						  <?php
						  $numberOfComments = count($questionList[$i])-4;
						  if(($numberOfComments > 1)){ ?>
						  <div class="fbkBx" id="viewAllDiv<?php echo $answerMsgId;?>">
							  <div>
								  <div class="float_L wdh100">
										  <div class="Fnt11">
											  <a href="javascript:void(0)" onClick="javascript:showMyAnswerComments('<?php echo $answerMsgId; ?>');return false;" class="fbxVw Fnt11">View All <span id="replyAnswerCount<?php echo $answerMsgId;?>"><?php echo $questionList[$i][1]['commentCount'];?></span> Comments</a>
										  </div>
								  </div>
								  <s>&nbsp;</s>
							  </div>
						  </div>
						  <?php }
						  if((count($questionList[$i])-4)>1) echo '<div id="commentDiv'.$answerMsgId.'" style="display:none;">';
						  for($x=2;$x<(count($questionList[$i])-2);$x++){
								if(($x==(count($questionList[$i])-3))&&((count($questionList[$i])-4)>1)) echo "</div>";
								?>
								<div class="fbkBx">
									<div>
										<div class="float_L wdh100">
												<div class="imgBx">
													<img src="<?php if($questionList[$i][$x]['userImage']=='') echo getTinyImage('/public/images/photoNotAvailable.gif'); else echo getTinyImage($questionList[$i][$x]['userImage']); ?>" id="<?php if($userId==$questionList[$i][$x]['userId']) echo "userProfileImageForComment".$questionList[$i][$x]['msgId'];?>"/>
												</div>
												<div class="cntBx">
													<div class="wdh100 float_L">
														<div class="Fnt11" style="padding-top:2px;">
														  <span>
															  <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $questionList[$i][$x]['userId']; ?>');" ><a href="<?php echo $userProfile.$questionList[$i][$x]['displayname']; ?>">
															  <?php echo $questionList[$i][$x]['displayname']; ?></a></span>
															  <?php if(($userId == $questionList[$i][$x]['userId'])){?><span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"> <img src="/public/images/fU.png" /></span>&nbsp; <?php } ?>
															  <?php
																  $commentDisplayText = html_entity_decode(html_entity_decode($questionList[$i][$x]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
																  $commentDisplayText = formatQNAforQuestionDetailPage($commentDisplayText,300);
																  echo $commentDisplayText;?>
														  </span>
														</div>
														<div class="Fnt11 fcdGya float_L" style="padding-top:2px;">
														  <span><?php echo makeRelativeTime($questionList[$i][$x]['creationDate']);?></span>
														</div>
														<div class="float_R">
															<?php if($userId!=$questionList[$i][$x]['userId']){
															  if($questionList[$i][$x]['reportedAbuse']==0){
															  if(!(($isCmsUser == 1)&&($questionList[$i][$x]['status']=='abused'))){
															?>
															<span id="abuseLink<?php echo $questionList[$i][$x]['msgId'];?>"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $questionList[$i][$x]['msgId']; ?>','<?php echo $questionList[$i][$x]['userId'];?>','<?php echo $questionList[$i][$x]['parentId'];?>','<?php echo $questionList[$i][$x]['threadId']; ?>','Reply','0','0');return false;">Report&nbsp;Abuse</a></span>
															<?php }}else{ ?>
															<span id="abuseLink<?php echo $questionList[$i][$x]['msgId'];?>" class="Fnt11">Reported as inappropriate</span>
															<?php }} ?>
														</div>
														<div class="clear_B">&nbsp;</div>
													</div>
												</div>
										</div>
										<s>&nbsp;</s>
										<!--Start_AbuseForm-->
										<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $questionList[$i][$x]['msgId'];?>"></div>
										<!--End_AbuseForm-->
									</div>
								</div>
						<?php } ?>
						</div>
						<div id="replyPlace<?php echo $answerMsgId;  ?>" style="margin-left:33px;"></div>
						<?php } ?>
						<!-- Block End for View All Answers/Comments Link -->
						<!-- Block start for Enter Answer form -->
						<?php if($ansNowLink=='' && $userGroup != 'cms' && $userId!=$questionUserId && $inlineFormHtml!='' && $questionList[$i]['type']!='comment'){ ?>
						<div class="fbkBx">
							<div>
								<div class="float_L wdh100">
									<?php echo $inlineFormHtml; ?>
								</div>
								<s>&nbsp;</s>
							</div>
						</div>
						<?php }else if($userGroup != 'cms' && $inlineFormHtml!=''){
								echo "<div style='padding-left:33px;'>".$inlineFormHtml."</div>";
							} ?>
						<!-- Block End for Enter Answer form -->
					</div>
					<?php } ?>
					<!-- End Answer Display section -->
					<!-- Start Message for Already answered or Closed question section -->
					<div>
						<?php if(($ansNowLink != "")){ ?>
						<div style="color:#86878c;padding-top:5px;padding-bottom:5px;">
							<div style="width:100%">
								<div>
									<div class="fontSize_12p"><?php echo $ansNowLink; ?></div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<!-- End Message for Already answered or Closed question section -->
					<?php if(($questionList[$i]['type'] == 'comment')){ ?>
					  <div style="display:none;margin-bottom:5px;" id="answerSection<?php  echo $questionMsgId; ?>"></div>
					<?php } ?>
					<!-- Start Message View Answers in case of comments -->
					<div>
						<?php if(($questionList[$i]['type'] == 'comment')&&(($questionList[$i][0]['msgCount'] > 1)||(($questionList[$i][0]['status'] != 'closed')&&($userId != $questionList[$i][1]['userId'])))){ ?>
						<div style="padding-top:5px;padding-bottom:5px;">
							<div style="width:100%">
								<div>
 									  <?php if(($questionList[$i][0]['msgCount'] > 1)){ ?><a href="javascript:void(0);" class="Fnt11" onClick="return getTopAnswersForWall(<?php echo $questionMsgId;?>,<?php echo $answerMsgId;?>,'<?php echo $catCountLink;?>','<?php echo $catCountText;?>','<?php echo $questionList[$i][0]['msgCount']; ?>',start<?php echo $questionMsgId;?>,'count<?php echo $questionMsgId;?>');" id="viewallLink<?php echo $questionMsgId;?>"><span id="answerCountHolder<?php echo $questionMsgId;?>"><?php echo ($questionList[$i][0]['msgCount'] - 1); ?></span> More <?php if(($questionList[$i][0]['msgCount'] - 1)==1) echo "Answer";else echo "Answers"; ?> <img border='0' src="/public/images/barw.gif" align="absmiddle"/></a>&nbsp;<span id="viewallLinkImage<?php echo $questionMsgId;?>" style="display:none;"><img src="/public/images/working.gif" align="absmiddle"/></span><?php } ?>
										  <span id="viewallAnswerSpan<?php echo $questionMsgId;?>" class="Fnt11" ><a href="<?php echo $questionUrl;?>" style="display:none" class="Fnt11" id="viewallAnswerLink<?php echo $questionMsgId;?>">View all <?php echo $questionList[$i][0]['msgCount'];?> answers</a></span>
										  <?php if(($questionList[$i][0]['status'] != 'closed')&&($userId != $questionList[$i][1]['userId'])){ ?><span id="answerQuestion<?php echo $questionMsgId;?>"><?php if(($questionList[$i][0]['msgCount'] > 1)){ ?><span class="grayFont" id="separatorDiv<?php echo $questionMsgId;?>">|&nbsp;</span><?php } ?><a href="javascript:void(0);" onClick="showAnswerSection('<?php echo $questionMsgId;?>');">Answer the question</a></span><?php } ?>

								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<!-- End Message View Answers in case of comments -->
					<!-- Block Start for Answer display Section -->
					<?php if(($questionList[$i]['type'] == 'comment')){ ?>
					  <div style="display:none;margin-top:10px;margin-bottom:5px;" id="yourAnswer<?php  echo $questionMsgId; ?>">&nbsp;
					  </div>
					  <div id="answerForm<?php echo $questionMsgId;?>">
					  </div>
					<?php } ?>
					<!-- Block End for Answer display Section -->

				</div>
			</div>
			<div class="clear_B"></div>
		</div>
		<!-- End Question section -->
		<div class="lineSpace_20">&nbsp;</div>

	</div>
	<!-- Main Div End -->
<?php
	  echo "<script>";
	  echo "var threadId = '".$questionList[$i][0]['threadId']."'; \n";
	  echo "</script>";
	}	//End of If for Level type
	//else	//In Case of Level type
	//{
?>
	<!-- Main Div Start -->
<!--	<div class="aqAns" style="border-bottom:1px solid #eaeeed;">
		<div class="lineSpace_5">&nbsp;</div>
		<div class="wdh100">
			<?php

			  $levelUserId = $questionList[$i]['userId'];
			  if($questionList[$i]['userId']==$userId){
					$UserDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionList[$i]['userId'].');}catch(e){}" style="width:30px;display:inline;"><a href="'.$userProfile.$questionList[$i]['displayname'].'"><b>'.$questionList[$i]['displayname'].'</b></a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
			  //}else if($VCardArray[$levelUserId]==1){
				//	$UserDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionList[$i]['userId'].');}catch(e){}" style="width:30px;display:inline;"><span class="flwMeBg"><a href="'.$userProfile.$questionList[$i]['displayname'].'"><b>'.$questionList[$i]['displayname'].'</b></a> &nbsp; <img src="/public/images/flwMe.png" /></span></span>';
			  }else{
					$UserDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionList[$i]['userId'].');}catch(e){}" style="width:30px;display:inline;"><a href="'.$userProfile.$questionList[$i]['displayname'].'"><b>'.$questionList[$i]['displayname'].'</b></a></span>';
			  }
			  $UserLevelText = '&nbsp;<span class=\'forA\'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$questionList[$i]['level'].'</a></span>';
			  $displayStarString = array();

			  if($questionList[$i]['level']!='Beginner' && $questionList[$i]['level']!='Trainee') $displayStarString = getTheRatingStarClass($questionList[$i]['level']);
			  $lineHeight = '100';
			  if($questionList[$i]['level']=='Chief Advisor') $lineHeight = '120';
			  echo "<div class='float_L' style='width:100px'><img src='/public/images/communityAwards.jpg' border='0' align='absmiddle'></img></div>";
			  echo "<div style='margin-left:100px;margin-top:10px;height:".$lineHeight."px;line-height:16px;margin-bottom:15px;'><div class='".$displayStarString['html']." txt_align_c Fnt11 fcdGya' style='float:right;padding-top:65px;width:100px'>".$displayStarString['value']."</div><div style='float:left;width:490px'><b>Congratulation</b>&nbsp;".$UserDisplayText." !<br/>";
			  if($questionList[$i]['level']=='Trainee') echo "<b>You have reached the ".$UserLevelText." level</b> <span class='Fnt11 fcdGya'>".makeRelativeTime($questionList[$i]['sortingTime'])."</span><br/><div style='margin-top:5px' class='Fnt11'>As you keep contributing on Ask and Answer, you will soon reach the Advisor level and secure a position in our Panel of Experts. Keep up the good work!</div>";
			  else if($questionList[$i]['level']=='Chief Advisor') echo "<b>The community is proud to hail you as our ".$UserLevelText."</b> <span class='Fnt11 fcdGya'>".makeRelativeTime($questionList[$i]['sortingTime'])."</span><br/><div style='margin-top:5px' class='Fnt11'>The community has awarded you the precious “Orange Star” and the top most place in our <a href='/messageBoard/MsgBoard/advisoryBoard'>Panel of Experts</a>.<br/>We congratulate you on this remarkable achievement and thank you for your generous contribution in making this such a successful community. We look forward to even greater support from you so that many more students can benefit from our efforts.</div>";
			  else{
				if($questionList[$i]['level']=='Advisor') echo "<b>The community is proud to recognize you as an ".$UserLevelText."</b> <span class='Fnt11 fcdGya'>".makeRelativeTime($questionList[$i]['sortingTime'])."</span><br/><div style='margin-top:5px' class='Fnt11'>The community has awarded you a “Grey Star” and a position in our <a href='/messageBoard/MsgBoard/advisoryBoard'>Panel of Experts</a>.<br/>We applaud your achievement and hope that many more students would benefit from your expert guidance.</div>";
				else if($questionList[$i]['level']=='Senior Advisor') echo "<b>The community is proud to recognize you as a ".$UserLevelText."</b> <span class='Fnt11 fcdGya'>".makeRelativeTime($questionList[$i]['sortingTime'])."</span><br/><div style='margin-top:5px' class='Fnt11'>The community has awarded you a “Blue Star” and a higher rank in our <a href='/messageBoard/MsgBoard/advisoryBoard'>Panel of Experts</a>.<br/>We appreciate your invaluable contribution to this community and look forward to more of it. </div>";
				else if($questionList[$i]['level']=='Lead Advisor')  echo "<b>The community is proud to recognize you as a ".$UserLevelText."</b> <span class='Fnt11 fcdGya'>".makeRelativeTime($questionList[$i]['sortingTime'])."</span><br/><div style='margin-top:5px' class='Fnt11'>The community has awarded you a “Pink Star” and a senior position in our <a href='/messageBoard/MsgBoard/advisoryBoard'>Panel of Experts</a>.<br/>We appreciate your invaluable contribution to this community and look forward to more of it. </div>";
				else if($questionList[$i]['level']=='Principal Advisor') echo "<b>The community is proud to recognize you as a ".$UserLevelText."</b> <span class='Fnt11 fcdGya'>".makeRelativeTime($questionList[$i]['sortingTime'])."</span><br/><div style='margin-top:5px' class='Fnt11'>The community has awarded you a “Green Star” and a leading position in our <a href='/messageBoard/MsgBoard/advisoryBoard'>Panel of Experts</a>.<br/>We appreciate your invaluable contribution to this community and look forward to more of it. </div>";
			  }
			  echo "</div></div>";
			  echo "<div class='clear_B'></div>";
			?>
		 </div>
		<div class="lineSpace_10">&nbsp;</div>
	</div>-->
	<!-- Main Div End -->

<?php
	//}
	$lastTimeStamp = $questionList[$i]['sortingTime'];
  }
?>
        
<input type="hidden" id="pageKeyForSubmitComment" value="<?php echo $pageKeySuffixForDetail.'SUBMITCOMMENT'; ?>" />
<input type="hidden" id="pageKeyForReportAbuse" value="<?php echo $pageKeySuffixForDetail.'REPORTABUSE'; ?>" />
<input type="hidden" id="pageKeyForDigVal" value="<?php echo $pageKeySuffixForDetail.'UPDATEDIGVAL'; ?>" />


<?php if (count($questionList) == 0 && $ajaxCall && !$relatedQuestions ){ ?>
<div style="margin-top:10px;margin-left:5px;">Please check the Related questions below</div>
<?php } else if (count($questionList) == 0 && $ajaxCall){?>
<div style="margin-top:10px;margin-left:5px;">No more activities in this Institute</div>
<?php } else{?>

    <?php if($relatedQuestions){ ?>
        <div id="olderPostDiv<?php echo $start;?>R"></div>
	    <?php if(count($topicListings['0']['results'])<='10' && count($topicListings['0']['results'])>'0' && $relatedQuestionsInstituteCategories!='true'){ ?>
		<div id="olderPostLink<?php echo $start;?>R" style="margin:10px 0;background:#ECECEC;padding:7px" >No More activities in this Institute</div>
        <?php
	    }
	    else if(count($topicListings['0']['results'])<'10' && count($topicListings['0']['results'])>'0' && $relatedQuestionsInstituteCategories=='true'){
        ?>    
	    <div id="olderPostLink<?php echo $start;?>R" style="margin:10px 0;background:#ECECEC;padding:7px" >No More activities in this Institute</div>
	    <?php
	    }
	    else if(count($topicListings['0']['results'])>'10' && $relatedQuestionsInstituteCategories!='true'){
        ?>    
	    <div id="olderPostLink<?php echo $start;?>R" style="margin:10px 0;background:#ECECEC;padding:7px" ><a href="javascript:void(0);" onclick="showOlderPostsForListingsR('<?php echo $categoryId;?>','<?php echo $countryId;?>','<?php echo $start;?>','<?php echo $count;?>','<?php echo $this->csvThreadIds; ?>','<?php echo $lastTimeStamp;?>','<?php echo $questionIds;?>','<?php echo $instituteId;?>');trackEventByGA('LinkClick','OLDER_ACTIVITIES');return false;">View More <img border='0' src="/public/images/barw.gif" align="absmiddle"/></a></div>
	    <?php
	    } 
	    else if(count($topicListings['0']['results'])>='10' && $relatedQuestionsInstituteCategories=='true'){
        ?>    
	    <div id="olderPostLink<?php echo $start;?>R" style="margin:10px 0;background:#ECECEC;padding:7px" ><a href="javascript:void(0);" onclick="showOlderPostsForListingsR('<?php echo $categoryId;?>','<?php echo $countryId;?>','<?php echo $start;?>','<?php echo $count;?>','<?php echo $this->csvThreadIds; ?>','<?php echo $lastTimeStamp;?>','<?php echo $questionIds;?>','<?php echo $instituteId;?>');trackEventByGA('LinkClick','OLDER_ACTIVITIES');return false;">View More <img border='0' src="/public/images/barw.gif" align="absmiddle"/></a></div>
	    <?php
	    }?>
        <div id="waitingDiv<?php echo $start;?>R" style="margin:10px 0;background:#ECECEC;padding:7px;display:none;" ></div>    
    <?php }else{ ?>
        <div id="olderPostDiv<?php echo $start;?>"></div>
	    <?php if( count($topicListings['0']['results'])<='10' && count($topicListings['0']['results'])>'0' ){ ?>
		<div id="olderPostLink<?php echo $start;?>" style="margin:10px 0;background:#ECECEC;padding:7px;display:none;" >No More activities in this Institute</div>
        <?php
	    }else if(count($topicListings['0']['results'])>'10'){
		?>    
	    <div id="olderPostLink<?php echo $start;?>" style="margin:10px 0;background:#ECECEC;padding:7px" ><a href="javascript:void(0);" onclick="showOlderPostsForListings('<?php echo $categoryId;?>','<?php echo $countryId;?>','<?php echo $start;?>','<?php echo $count;?>','<?php echo $this->csvThreadIds; ?>','<?php echo $lastTimeStamp;?>','<?php echo $questionIds;?>','<?php echo $instituteId;?>');trackEventByGA('LinkClick','OLDER_ACTIVITIES');return false;">View More <img border='0' src="/public/images/barw.gif" align="absmiddle"/></a></div>
	    <?php }?>
        <div id="waitingDiv<?php echo $start;?>" style="margin:10px 0;background:#ECECEC;padding:7px;display:none;" ></div>    
    <?php } ?>
<?php }?>


</div>


