<script>
var temp = 'fShare';
</script>
<?php
	//below line is used for conversion tracking purpose
		if(isset($tabselected))
		{
			$selectedTab=$tabselected;
		}
		else
		{
			$selectedTab=$tabSelected;
		}
	
	$this->csvThreadIds = '';
	if(isset($threadIdCsv)) $this->csvThreadIds = $threadIdCsv;
	$questionList = $topicListings['results'];
	$levelVCard = $topicListings['levelVCard'];
	$categoryCountry = $topicListings['categoryCountry'];
	$levelAdvance = isset($topicListings['levelAdvance'])?$topicListings['levelAdvance']:array();
	$answerSuggestions = isset($topicListings['answerSuggestions'])?$topicListings['answerSuggestions']:array();

	//Code Start to create VCard, User level, Category and Country arrays with UserId as Index
	$VCardArray = array();
	$userLevel = array();
	$userLevelParticipate = array();
    for($i=0;$i<count($levelVCard);$i++){
	  $userIdTemp = $levelVCard[$i]['userid'];
	  $VCardArray[$userIdTemp] = $levelVCard[$i]['vcardStatus'];
	  $userLevel[$userIdTemp] = $levelVCard[$i]['ownerLevel'];
	  $userLevelParticipate[$userIdTemp] = $levelVCard[$i]['ownerLevelP'];
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
	uasort($mainArray, 'cmp');

	//Code End to merge the Result and the Level advance arrays
	$x=0;
	foreach($mainArray as $resultBlock){
	  if($x<10)
		$questionList[$x] = $resultBlock;
	  $x++;
	}

	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$userImageURLDisplay = isset($validateuser[0]['avtarurl'])?$validateuser[0]['avtarurl']:'/public/images/photoNotAvailable.gif';

	$userProfile = site_url('getUserProfile').'/';
	$displayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';

	function cmp($a,$b)
	{
	  if ($a['sortingTime'] == $b['sortingTime']) return 0;
	  return ($a['sortingTime'] < $b['sortingTime']) ? 1 : -1;
	}

	function getDisplayNameText($userId, $entityUserId, $displayName, $vcardStatus, $userLevel, $fullName)
	{
		$userProfile = site_url('getUserProfile').'/';
		if($entityUserId==$userId){
			  $userDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$entityUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$displayName.'">'.$fullName.'</a></span><span style="font-weight:normal !important; color:#000">, '.$userLevel.'</span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
		//}else if($vcardStatus==1){
			//  $userDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$entityUserId.');}catch(e){}" style="width:30px;display:inline;" ><span class="flwMeBg"><a href="'.$userProfile.$displayName.'">'.$displayName.'</a> &nbsp; <img src="/public/images/flwMe.png" /></span></span>';
		}else{
			  $userDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$entityUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$displayName.'">'.$fullName.'</a></span><span style="font-weight:normal !important; color:#000">, '.$userLevel.'</span>';
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

	function getIconClass($type)
	{
	    $class = "";
	    if($type!=''){
		switch($type){
		  case 'question': $class = "";break;
		  case 'discussion': $class = "ana_blog";break;
		  case 'announcement': $class = "ana_mike";break;
		  case 'discussioncomment': $class = "ana_blog";break;
		  case 'announcementcomment': $class = "ana_mike";break;
		  default: $class = "ana_q";break;
		}
	    }
	    return $class;
	}

	$commentsDisplayArray = array('comment','discussion','discussioncomment','announcement','announcementcomment','review','reviewcomment','eventAna','eventAnAcomment');
	$newEntityDisplayArray = array('discussion','discussioncomment','announcement','announcementcomment','review','reviewcomment','eventAna','eventAnAcomment');
	$newCommentEntityDisplayArray = array('discussioncomment','announcementcomment','reviewcomment','eventAnAcomment');
	$newEntityArray = array('discussioncomment'=>'discussion','announcementcomment'=>'announcement','discussion'=>'discussion','announcement'=>'announcement','review'=>'review','reviewcomment'=>'review','eventAnA'=>'eventAnA','eventAnAcomment'=>'eventAnA');
?>
<script>
currentPageName = "ANA WALL";
</script>
<div>
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
		$showArr = 'true';
		if($questionList[$i][0]['status'] == 'closed'){
			$ansNowLink = '<span class="normaltxt_11p_blk ">This question has been closed for answering.</span>';
		}
		if(isset($questionList[$i][0]['alreadyAnswered']) && ($questionList[$i][0]['alreadyAnswered'] > 0)){
			$ansNowLink = '<div class="normaltxt_11p_blk "><img src="/public/images/greenChk.gif" align="absmiddle" />&nbsp;<a href="'.$questionUrl.'"  style="color:#000000;">You have already answered this question</a></div>';
		}elseif($questionList[$i][0]['status'] != 'closed' && $userId!=$questionList[$i][0]['userId'] && $questionList[$i]['type']!='comment'){
			$dataArray = array('showMention'=>true,'type'=> $questionList[$i]['type'],'userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$questionList[$i][0]['threadId'],'ansCount' => $questionList[$i][0]['msgCount'],'detailPageUrl' =>$questionUrl,'callBackFunction' => 'try{ addMainCommentForQues('.$questionList[$i][0]['threadId'].',request.responseText,\'-1\',true,true,\'\',\'\',true,\''.$userImageURLDisplay.'\'); } catch (e) {}');
			//below line is used for conversion tracking purpose
			$dataArray['anstrackingPageKeyId']=$anstrackingPageKeyId;
			$inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage',$dataArray,true);
		}
		//if($questionList[$i]['type']=='discussion'){
		//	$dataArray = array('userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$questionList[$i][0]['threadId'],'ansCount' => $questionList[$i][0]['msgCount'],'detailPageUrl' =>$questionUrl,'callBackFunction' => 'try{ addMainCommentForQues('.$questionList[$i][0]['threadId'].',request.responseText,\'-1\',true,true,\'\',\'\',true,\''.$userImageURLDisplay.'\'); } catch (e) {}');
		//	$inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage',$dataArray,true);
		//}

		if($questionList[$i]['type']=='comment'){
			$functionToCall = isset($functionToCall)?$functionToCall:'-1';
			$dataArray = array('showMention'=>true,'type'=> $questionList[$i]['type'],'userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$questionList[$i][0]['threadId'],'ansCount' => $questionList[$i][0]['msgCount'],'detailPageUrl' =>$questionUrl,'functionToCall' => $functionToCall, 'fromOthers' => 'user', 'msgId' => $questionList[$i][1]['msgId'], 'mainAnsId' => $questionList[$i][1]['msgId'], 'dotCount'=>2 , 'displayname'=> $displayName, 'sortFlag'=>2, 'wall'=>1);
			$dataArray['ansctrackingPageKeyId']=$ansctrackingPageKeyId;
			$inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage_Comment',$dataArray,true);
		}else if( in_array($questionList[$i]['type'],$newEntityDisplayArray) ){
			$tempType = $questionList[$i]['type'];
			$functionToCall = isset($functionToCall)?$functionToCall:'-1';
			$showMention = ($questionList[$i]['type']=='discussion' || $questionList[$i]['type']=='discussioncomment')?true:false;
			$dataArray = array('showMention'=>$showMention,'type'=> $questionList[$i]['type'],'userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$questionList[$i][0]['threadId'],'ansCount' => $questionList[$i][0]['msgCount'],'detailPageUrl' =>$questionUrl,'functionToCall' => $functionToCall, 'fromOthers' => $newEntityArray[$tempType], 'msgId' => $questionList[$i][0]['msgId'], 'mainAnsId' => $questionList[$i][0]['msgId'], 'dotCount'=>2 , 'displayname'=> $displayName, 'sortFlag'=>2, 'wall'=>1, 'messageToShow'=>'Write a comment and express your opinion...');

			if(isset($questionList[$i][0]['linkedDiscussion']) && $questionList[$i][0]['linkedDiscussion']=='true'){
			    $inlineFormHtml = '<div class="normaltxt_11p_blk ">&nbsp;This discussion has been closed by the moderator because a similar discussion exist.</div>';
			    $showArr = 'false';
			}
			else{
			    $inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage_Comment',$dataArray,true);
			}
		}
		$questionUserDisplayName = $questionList[$i][0]['displayname'];
		$questionUserDisplayFullName = $questionList[$i][0]['firstname'].' '.$questionList[$i][0]['lastname'];
		$questionUserDisplayLevel = $questionList[$i][0]['level'];
		$questionUserId = $questionList[$i][0]['userId'];
		if( in_array($questionList[$i]['type'],$newEntityDisplayArray)){
		    //$questionUserLevel = getTheRatingStar($userLevelParticipate[$questionUserId]);
		    $questionUserLevel = '';
		}
		else{
		    $questionUserLevel = getTheRatingStar($userLevel[$questionUserId]);
		}
		$questionVCardStatus = $VCardArray[$questionUserId];
		$questionText = $questionList[$i][0]['msgTxt'];
		$questionMsgId = $questionList[$i][0]['msgId'];

		if($CategoryCountryArray[$questionMsgId]['categoryId'] == 1){
			$CategoryCountryArray[$questionMsgId]['categoryId'] = 0;
		}

		if($questionList[$i]['type'] == 'question'){
			$userImageURL = $questionList[$i][0]['userImage'];
			$userImageURLOwnerId = $questionList[$i][0]['userId'];
			$questionFontStyle = "style='color:#000000'";
			$questionFontClass = "";
		}
		else if(in_array($questionList[$i]['type'],$newEntityDisplayArray)){
			$userImageURL = $questionList[$i][0]['userImage'];
			$userImageURLOwnerId = $questionList[$i][0]['userId'];
			$questionFontStyle = "style='color:#000000'";
			$questionFontClass = "";
			$answerMsgId = $questionList[$i][0]['msgId'];
		}
		else
		{
			$userImageURL = $questionList[$i][1]['userImage'];
			$userImageURLOwnerId = $questionList[$i][1]['userId'];
			$answerUserDisplayName = $questionList[$i][1]['displayname'];
			$answerUserDisplayFullName = $questionList[$i][1]['firstname'].' '.$questionList[$i][1]['lastname'];
			$answerUserDisplayLevel = $questionList[$i][1]['level'];
			$answerUserId = $questionList[$i][1]['userId'];
			if( in_array($questionList[$i]['type'],$newEntityDisplayArray)){
			    //$answerUserLevel = getTheRatingStar($userLevelParticipate[$answerUserId],$questionList[$i]['type']);
			    $answerUserLevel = '';
			}
			else{
			    $answerUserLevel = getTheRatingStar($userLevel[$answerUserId],$questionList[$i]['type']);
			}
			$answerVCardStatus = $VCardArray[$answerUserId];
			$answerText = $questionList[$i][1]['msgTxt'];
			$answerMsgId = $questionList[$i][1]['msgId'];
			$questionFontStyle = "style='color:#707070'";
			$questionFontClass = "class='grayFont'";
		}
		$answerFontStyle = "style='color:#000000'";
		$answerFontClass = "";
		if($questionList[$i]['type'] == 'rating'){
			$userImageURL = $questionList[$i][2]['userImage'];
			$userImageURLOwnerId = $questionList[$i][2]['userId'];
			$raterUserDisplayName = $questionList[$i][2]['displayname'];
			$raterUserDisplayFullName = $questionList[$i][2]['firstname'].' '.$questionList[$i][2]['lastname'];
			$raterUserDisplayLevel = $questionList[$i][2]['level'];
			$raterUserId = $questionList[$i][2]['userId'];
			if( in_array($questionList[$i]['type'],$newEntityDisplayArray)){
			    //$raterUserLevel = getTheRatingStar($userLevelParticipate[$raterUserId]);
			    $raterUserLevel = '';
			}
			else{
			    $raterUserLevel = getTheRatingStar($userLevel[$raterUserId]);
			}
			$raterVCardStatus = $VCardArray[$raterUserId];
		}
		else if($questionList[$i]['type'] == 'comment' || in_array($questionList[$i]['type'],$newCommentEntityDisplayArray)){
			$commentNumberToDisplay = count($questionList[$i])-4;
			$userImageURL = $questionList[$i][$commentNumberToDisplay]['userImage'];
			$userImageURLOwnerId = $questionList[$i][$commentNumberToDisplay]['userId'];
			$commenterUserDisplayName = $questionList[$i][$commentNumberToDisplay]['displayname'];
			$commenterUserDisplayFullName = $questionList[$i][$commentNumberToDisplay]['firstname'].' '.$questionList[$i][$commentNumberToDisplay]['lastname'];	
			$commenterUserDisplayLevel = $questionList[$i][$commentNumberToDisplay]['level'];
			$commenterUserId = $questionList[$i][$commentNumberToDisplay]['userId'];
			if( in_array($questionList[$i]['type'],$newEntityDisplayArray)){
			    //$commenterUserLevel = getTheRatingStar($userLevelParticipate[$commenterUserId]);
			    $commenterUserLevel = '';
			}
			else{
			    $commenterUserLevel = getTheRatingStar($userLevel[$commenterUserId]);
			}
			$userCommentTimeDisplayNumber = $commentNumberToDisplay;
			if($pageKeySuffixForDetail=='ANA_USER_PROFILE'){	//In case of Profile Wall
			  if(isset($viewedOwnerDetails) && is_array($viewedOwnerDetails)){
				if( in_array($questionList[$i]['type'],$newEntityDisplayArray)){
				    //$commenterUserLevel = getTheRatingStar($viewedOwnerDetails['viewedUserLevelP']);
				    $commenterUserLevel = '';
				}
				else{
				    $commenterUserLevel = getTheRatingStar($viewedOwnerDetails['viewedUserLevel']);
				}
				$userImageURL = $viewedOwnerDetails['viewedUserImage'];
			  }
			  for($z=0;$z<count($questionList[$i]);$z++){
				if($questionList[$i][$z]['userId']==$viewedUserId)
				  $userCommentTimeDisplayNumber = ($z);
			  }
			}
			$commenterVCardStatus = $VCardArray[$commenterUserId];
			$answerFontStyle = "style='color:#707070'";
			$answerFontClass = "class='grayFont'";
		}

		if($questionUserId==$userId){
			  $questionUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$questionUserDisplayName.'">'.$questionUserDisplayFullName.'\'s</a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
		//}else if($questionVCardStatus==1){
			//  $questionUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionUserId.');}catch(e){}" style="width:30px;display:inline;" ><span class="flwMeBg"><a href="'.$userProfile.$questionUserDisplayName.'">'.$questionUserDisplayName.'\'s</a> &nbsp; <img src="/public/images/flwMe.png" /></span></span>';
		}else{
			  $questionUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$questionUserDisplayName.'">'.$questionUserDisplayFullName.'\'s</a></span>';
		}
		$questionUserDisplayText = getDisplayNameText($userId, $questionUserId, $questionUserDisplayName, $questionVCardStatus, $questionUserDisplayLevel, $questionUserDisplayFullName );
		$questionUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$questionUserLevel.'</a></span>';
		if($answerUserId==$userId){
			  $answerUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$answerUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$answerUserDisplayName.'">'.$answerUserDisplayFullName.'\'s</a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
		//}else if($answerVCardStatus==1){
			//  $answerUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$answerUserId.');}catch(e){}" style="width:30px;display:inline;" ><span class="flwMeBg"><a href="'.$userProfile.$answerUserDisplayName.'">'.$answerUserDisplayName.'\'s</a> &nbsp; <img src="/public/images/flwMe.png" /></span></span>';
		}else{
			  $answerUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$answerUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$answerUserDisplayName.'">'.$answerUserDisplayFullName.'\'s</a></span>';
		}
		$answerUserDisplayText = getDisplayNameText($userId, $answerUserId, $answerUserDisplayName, $answerVCardStatus, $answerUserDisplayLevel, $answerUserDisplayFullName);
		$answerUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$answerUserLevel.'</a></span>';
		$raterUserDisplayText = getDisplayNameText($userId, $raterUserId, $raterUserDisplayName, $raterVCardStatus, $raterUserDisplayLevel, $raterUserDisplayFullName);
		$raterUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$raterUserLevel.'</a></span>';
		$commenterUserDisplayText = getDisplayNameText($userId, $commenterUserId, $commenterUserDisplayName, $commenterVCardStatus, $commenterUserDisplayLevel, $commenterUserDisplayFullName );
		if($pageKeySuffixForDetail=='ANA_USER_PROFILE')	//In case of Profile Wall
		  $commenterUserDisplayText = getDisplayNameText($userId, $viewedUserId, $viewedDisplayName,0, $commenterUserDisplayLevel, $commenterUserDisplayFullName);
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

		<?php if( !($i==0 && $start==0) ){ ?><div class="lineSpace_20">&nbsp;</div><?php } ?>
		<!-- Start Question section -->
		<div class="wdh100">
			<?php
			if($questionList[$i]['type']=='bestanswer'){
			  echo "<div style='padding-bottom:10px;font-size:14px;'><img src='/public/images/congrates.gif' align='absmiddle' />&nbsp;<b>".$answerUserDisplayText."</b>&nbsp;".$answerUserLevelText." your answer is selected as <b>\"The Best Answer\"</b> by ".$questionUserDisplayText."</div>";

                         //echo $questionList[$i][0]['threadId'];
                         if($this->userStatus!='false' && $this->userStatus[0]['userid']== $answerUserId){
                             //setCookie('facebookData','bestanswer##'+ $answerUserId+'##'+$questionList[$i][0]['threadId']+'##'+$questionMsgId+'##'+$commenterUserId);
                            // setcookie('facebookData','bestanswer##'.$questionList[$i][0]['threadId'],time() + 2592000 ,'/',COOKIEDOMAIN);
                            // setcookie('fbcookie','bestanswer',time() + 2592000 ,'/',COOKIEDOMAIN);
                          ?>
                   <!-- <div class="float_R">-->
                        <!--<div id="fb-root"></div>-->
                        <!--<script>
                         var temp = 'fShare';
                        FB.init({
                        appId:<?php //echo FACEBOOK_API_ID; ?>, cookie:true,
                        status:true, xfbml:true
                        });
                        </script> -->
                        <!--<div id="fbButton">
                        <fb:login-button size="small" scope="email,user_checkins,offline_access,read_stream,publish_stream" on-login="callFConnectAndFShare('<?php echo "bestanswer##".$questionList[$i][0]['threadId'].'##'.$answerMsgId;?>');">Share with Friends</fb:login-button>
                        </div>
                    </div>-->
                    <div class="clear_B">&nbsp;</div>
                        <?php
                          }
                        }
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
						switch($questionList[$i]['type']){
							//case 'question':
								  //echo "<div style='padding-bottom:6px;'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>asked</b> a question <span class='Fnt11 grayFont'>".$questionList[$i][0]['creationDate']."</span></div>";
								  //break;
							case 'discussion':
								  echo "<div style='padding-bottom:6px;font-size:14px;'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>started a discussion</b> on <a href='".$questionList[$i][0]['url']."' $questionFontStyle>".formatDiscussionforList($questionList[$i][0]['msgTxt'],600)."</a></div>";
								  break;
							case 'discussioncomment':
								  if($questionUserId==$commenterUserId) $displayNameString = "their"; else $displayNameString = $questionUserDisplayTextForHeading;
								  if($pageKeySuffixForDetail=='ANA_USER_PROFILE'){	//In case of Profile Wall
									if($viewedUserId==$answerUserId) $displayNameString = "their";
								  }
								  if($questionList[$i]['subtype'] == 'reply'){
									$activity = 'replied';
								  }else{
									$activity = 'commented';
								  }
								  echo "<div style='padding-bottom:6px;font-size:14px;'><b>".$commenterUserDisplayText."</b>&nbsp;".$commenterUserLevelText." <b>$activity</b> on ".$displayNameString." discussion post &nbsp;<span class='Fnt11 grayFont'>".makeRelativeTime($questionList[$i][$userCommentTimeDisplayNumber]['creationDate'])."</span></div>";
								  break;
							case 'announcement':
								  //echo "<div style='padding-bottom:6px;'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>posted</b> ".$questionList[$i][0]['msgTxt']."&nbsp;<span class='Fnt11 grayFont'>".$questionList[$i][0]['creationDate']."</span></div>";
								  echo "<div style='padding-bottom:6px;font-size:14px;'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>posted</b> <a href='".$questionList[$i][0]['url']."' $questionFontStyle>".$questionList[$i][0]['msgTxt']."</a></div>";
								  break;
							case 'announcementcomment':
								  if($questionUserId==$commenterUserId) $displayNameString = "their"; else $displayNameString = $questionUserDisplayTextForHeading;
								  if($pageKeySuffixForDetail=='ANA_USER_PROFILE'){	//In case of Profile Wall
									if($viewedUserId==$answerUserId) $displayNameString = "their";
								  }
								  $time = (count($questionList[$i])>4)?makeRelativeTime($questionList[$i][$userCommentTimeDisplayNumber]['creationDate']):$questionList[$i][$userCommentTimeDisplayNumber]['creationDate'];
								  echo "<div style='padding-bottom:6px;font-size:14px;'><b>".$commenterUserDisplayText."</b>&nbsp;".$commenterUserLevelText." <b>commented</b> on ".$displayNameString." post &nbsp;<span class='Fnt11 grayFont'>".$time."</span></div>";
								  break;
							case 'eventAnA':
								  //echo "<div style='padding-bottom:6px;'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>posted</b> ".$questionList[$i][0]['msgTxt']."&nbsp;<span class='Fnt11 grayFont'>".$questionList[$i][0]['creationDate']."</span></div>";
								  echo "<div style='padding-bottom:6px;'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>posted an event</b> ".$questionList[$i][0]['msgTxt']."</div>";
								  break;
							case 'eventAnAcomment':
								  if($questionUserId==$commenterUserId) $displayNameString = "their"; else $displayNameString = $questionUserDisplayTextForHeading;
								  if($pageKeySuffixForDetail=='ANA_USER_PROFILE'){	//In case of Profile Wall
									if($viewedUserId==$answerUserId) $displayNameString = "their";
								  }
								  $time = (count($questionList[$i])>4)?makeRelativeTime($questionList[$i][$userCommentTimeDisplayNumber]['creationDate']):$questionList[$i][$userCommentTimeDisplayNumber]['creationDate'];
								  echo "<div style='padding-bottom:6px;'><b>".$commenterUserDisplayText."</b>&nbsp;".$commenterUserLevelText." <b>commented</b> on ".$displayNameString." event &nbsp;<span class='Fnt11 grayFont'>".$time."</span></div>";
								  break;
							case 'answer':
								  echo "<div style='padding-bottom:6px;font-size:14px;'><b>".$answerUserDisplayText."</b>&nbsp;".$answerUserLevelText." <b>answered</b> ".$questionUserDisplayTextForHeading." question <span class='Fnt11 grayFont'>".$questionList[$i][1]['creationDate']."</span></div>";
								  break;
							case 'rating':
								  $showOtherRating = '';
								  if($questionList[$i][1]['digUp']==2) $showOtherRating = " and <a href='javascript:void(0);' onclick='showOtherRating(".$answerMsgId.",".$raterUserId.");'>".($questionList[$i][1]['digUp']-1)." other</a>";
								  else if($questionList[$i][1]['digUp']>2) $showOtherRating = " and <a href='javascript:void(0);' onclick='showOtherRating(".$answerMsgId.",".$raterUserId.");'>".($questionList[$i][1]['digUp']-1)." others</a>";
								  echo "<div style='padding-bottom:6px;font-size:14px;'><b>".$raterUserDisplayText."</b>&nbsp;".$raterUserLevelText.$showOtherRating." <b>upvoted <img src='/public/images/hUp.gif'/> </b>".$answerUserDisplayTextForHeading." answer</div>";
								  break;
							case 'comment':
								  if($commenterUserId==$answerUserId) $displayNameString = "their"; else $displayNameString = $answerUserDisplayTextForHeading;
								  if($pageKeySuffixForDetail=='ANA_USER_PROFILE'){	//In case of Profile Wall
									if($viewedUserId==$answerUserId) $displayNameString = "their";
									echo "<div style='padding-bottom:6px;font-size:14px;'><b>".$commenterUserDisplayText."</b>&nbsp;".$commenterUserLevelText." <b>commented</b> on ".$displayNameString." answer <span class='Fnt11 grayFont'>".makeRelativeTime($questionList[$i][$userCommentTimeDisplayNumber]['creationDate'])."</span></div>";
								  }
								  else
									echo "<div style='padding-bottom:6px;font-size:14px;'><b>".$commenterUserDisplayText."</b>&nbsp;".$commenterUserLevelText." <b>commented</b> on ".$displayNameString." answer <span class='Fnt11 grayFont'>".makeRelativeTime($questionList[$i][$commentNumberToDisplay]['creationDate'])."</span></div>";
								  break;
						}
					?>
					<!-- End of Question Headline Block -->
					<!-- Start Question owner, Level and Question Display section -->
					<div class="<?php echo getIconClass($questionList[$i]['type']); ?> fs11" style="padding-bottom:5px; <?php if($questionList[$i]['type'] == 'question'){ echo "font-size:14px;";}?>">
						<?php if($questionList[$i]['type'] != 'question') echo $questionUserDisplayText; else echo "<b>".$questionUserDisplayText." asked</b>";?>

						<?php if(in_array($questionList[$i]['type'],$newCommentEntityDisplayArray)) echo "<span><b><a href='".$questionList[$i][0]['url']."' ".$questionFontStyle.">".formatDiscussionforList($questionList[$i][0]['msgTxt'],300)."</a></b></span><br/>";?>
						<a href="<?php echo $questionList[$i][0]['url'];?>" <?php echo $questionFontStyle;?> >
						<?php
							  if(in_array($questionList[$i]['type'],$newEntityDisplayArray)){
							      $discussionText = html_entity_decode(html_entity_decode($questionList[$i][0]['description'],ENT_NOQUOTES,'UTF-8'));
							      $quesLength = strlen($discussionText);
							      if($quesLength<=600){ $discussionText = formatDiscussionforList($discussionText,600);echo "<span ".$questionFontClass.">".$discussionText."</span></a>";}
							      else {
								      echo "<span ".$questionFontClass." id='previewQues".$questionList[$i][0]['msgId']."'>".formatDiscussionforList(substr($discussionText, 0, 597),600)."</span></a>";
								      if($quesLength<=1000){
									echo "<span style='word-wrap:break-word' id='relatedQuesDiv".$questionList[$i][0]['msgId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$questionList[$i][0]['msgId']."' onClick='showCompleteAnswerHomepage(".$questionList[$i][0]['msgId'].");'>more</a></span>";
								      }
								      else{
									echo "<span style='word-wrap:break-word' id='relatedQuesDiv".$questionList[$i][0]['msgId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='".$questionList[$i][0]['url']."' id='relatedQuesHyperDiv".$questionList[$i][0]['msgId']."' >more</a></span>";
								      }
								      $discussionText = formatDiscussionforList($discussionText,600);
								      echo "<a href=".$questionList[$i][0]['url']." ".$questionFontStyle."><span ".$questionFontClass." id='completeRelatedQuesDiv".$questionList[$i][0]['msgId']."' style='display:none;'>".$discussionText."</span></a>";
							      }
							  }
							  else{
							      $questionText = html_entity_decode(html_entity_decode($questionText,ENT_NOQUOTES,'UTF-8'));
							      $quesLength = strlen($questionText);
							      if($quesLength<=140){ $questionText = formatQNAforQuestionDetailPageWithoutLink($questionText,140);echo "<span ".$questionFontClass.">".$questionText."</span></a>";}
							      else {
								      echo "<span ".$questionFontClass." id='previewQues".$questionList[$i][0]['msgId']."'>".substr($questionText, 0, 137)."</span></a>";
								      echo "<span id='relatedQuesDiv".$questionList[$i][0]['msgId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$questionList[$i][0]['msgId']."' onClick='showCompleteAnswerHomepage(".$questionList[$i][0]['msgId'].");'>more</a></span>";
								      $questionText = formatQNAforQuestionDetailPageWithoutLink($questionText,140);
								      echo "<a href=".$questionList[$i][0]['url']." ".$questionFontStyle."><span ".$questionFontClass." id='completeRelatedQuesDiv".$questionList[$i][0]['msgId']."' style='display:none;'>".$questionText."</span></a>";
							      }
							  }
						?>
						<!-- Start Question Date, Category and Country display section -->
						<?php if($questionList[$i]['type'] == 'question'){ ?>
						<div class='mtb5'>
							<?php if(($displayListing)&&($listingTitle!='')){?>
							  <span class="Fnt11 float_L"><?php echo "<span class='Fnt11 grayFont'>".$questionList[$i][0]['creationDate']."</span> ";?>about <span><?php echo $listingTitle;?></span></span>
							<?php }else{ ?>
							  <span class="Fnt11 float_L"><?php if($questionList[$i]['type'] == 'question') echo "<span class='Fnt11 grayFont'>".$questionList[$i][0]['creationDate']."</span> ";?>in <a href="/messageBoard/MsgBoard/discussionHome/<?php echo $CategoryCountryArray[$questionMsgId]['categoryId']; ?>/0/<?php echo $CategoryCountryArray[$questionMsgId]['countryId'];?>" style="color:#707070;"><?php echo $CategoryCountryArray[$questionMsgId]['category']." - ".$CategoryCountryArray[$questionMsgId]['country']; ?></a></span>
							<?php } ?>
							<!-- Block Start for Report Abuse link -->
							<div class="float_R" style="valign:top;">
								<div>
										<?php if($userId!=$questionUserId){ if($questionList[$i][0]['reportedAbuse']==0){
										  if(!(($isCmsUser == 1)&&($questionList[$i][0]['status']=='abused'))){
										  	//report abuse question
										?>

										<span id="abuseLink<?php echo $questionMsgId;?>" class="Fnt11"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $questionMsgId; ?>','<?php echo $questionUserId;?>','<?php echo $questionMsgId; ?>','<?php echo $questionMsgId; ?>','<?php echo "Question"; ?>',0,0,<?=$raqtrackingPageKeyId?>);return false;">Report&nbsp;Abuse</a></span>
										<?php }}else{ ?>
										<span id="abuseLink<?php echo $questionMsgId;?>" class="Fnt11">Reported as inappropriate</span>
										<?php }} ?>
								</div>
							</div>
							<!-- Block End for Report Abuse link -->
						</div>
						<div class="clear_B"></div>
						<!-- End Question Date, Category and Country display section -->
						<!-- Start Discussion Date, Category and Country display section -->
						<?php }else if(in_array($questionList[$i]['type'],$newEntityDisplayArray)){ ?>
						<div class='mtb5'>

							<?php if($questionList[$i]['type'] == 'discussion' || $questionList[$i]['type'] == 'discussioncomment'){ ?>
							<span class="Fnt11 float_L"><?php echo "<span class='Fnt11 grayFont'>".$questionList[$i][0]['creationDate']."</span> ";?>under <span class='Fnt11 grayFont'><a href='/messageBoard/MsgBoard/discussionHome/1/6/1' style='color:#707070;'>Discussions</a></span> in <a href="/messageBoard/MsgBoard/discussionHome/<?php echo $CategoryCountryArray[$questionMsgId]['categoryId']; ?>/6/<?php echo $CategoryCountryArray[$questionMsgId]['countryId'];?>" style="color:#707070;"><?php echo $CategoryCountryArray[$questionMsgId]['category']." - ".$CategoryCountryArray[$questionMsgId]['country']; ?></a></span>
							<?php $abuseTrackingPageKeyID=$radtrackingPageKeyId;}else if($questionList[$i]['type'] == 'announcement' || $questionList[$i]['type'] == 'announcementcomment'){?>
							<span class="Fnt11 float_L"><?php echo "<span class='Fnt11 grayFont'>".$questionList[$i][0]['creationDate']."</span> ";?>under <span class='Fnt11 grayFont'><a href='/messageBoard/MsgBoard/discussionHome/1/7/1' style='color:#707070;'>Announcements</a></span> in <a href="/messageBoard/MsgBoard/discussionHome/<?php echo $CategoryCountryArray[$questionMsgId]['categoryId']; ?>/7/<?php echo $CategoryCountryArray[$questionMsgId]['countryId'];?>" style="color:#707070;"><?php echo $CategoryCountryArray[$questionMsgId]['category']." - ".$CategoryCountryArray[$questionMsgId]['country']; ?></a></span>
							<?php $abuseTrackingPageKeyID=$raatrackingPageKeyId;}else if($questionList[$i]['type'] == 'eventAnA' || $questionList[$i]['type'] == 'eventAnAcomment'){?>
							<span class="Fnt11 float_L"><?php echo "<span class='Fnt11 grayFont'>".$questionList[$i][0]['creationDate']."</span> ";?>under <span class='Fnt11 grayFont'>Events</span>  in <a href="/messageBoard/MsgBoard/discussionHome/<?php echo $CategoryCountryArray[$questionMsgId]['categoryId']; ?>/0/<?php echo $CategoryCountryArray[$questionMsgId]['countryId'];?>" style="color:#707070;"><?php echo $CategoryCountryArray[$questionMsgId]['category']." - ".$CategoryCountryArray[$questionMsgId]['country']; ?></a></span>
							<?php }else if($questionList[$i]['type'] == 'review' || $questionList[$i]['type'] == 'reviewcomment'){?>
							<!-- Block Start for Dig up and Dig down -->
							<div class="float_L" style="width:460px;">
									<table cellspacing='0' cellpadding='0' border='0'>
									<tr>
									  <td colspan='2'>
										<span >
										<?php if(isset($topicListings['ratingStatusOfLoggedInUser'][$answerMsgId]) && $topicListings['ratingStatusOfLoggedInUser'][$answerMsgId] == 0){
											$cssClass1 = 'aqIcn dwnVote-actve-icn Fnt11' ;
											$cssClass2 = 'aqIcn upVote-disable-icn Fnt11';
										}
										else if(isset($topicListings['ratingStatusOfLoggedInUser'][$answerMsgId]) && $topicListings['ratingStatusOfLoggedInUser'][$answerMsgId] == 1){
											$cssClass2 = 'aqIcn upVote-actve-icn Fnt11';
											$cssClass1 = 'aqIcn dwnVote-disable-icn Fnt11';
										}
										else {
											$cssClass2 = 'aqIcn rUp Fnt11';
											$cssClass1 = 'aqIcn rDn Fnt11';

										}
										?>
										  <a href="javascript:void(0);" id="up_<?php echo $answerMsgId; ?>" onMouseOver = "showLikeDislike(0,'<?php echo $answerMsgId; ?>','review');" onMouseOut = "hideLikeDislike(0,'<?php echo $answerMsgId; ?>');" onClick="updateDig(this,'<?php echo $answerMsgId; ?>',1,'review');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="<?=$cssClass2?>" style="color:#000;text-decoration:none"><?php echo $questionList[$i][0]['digUp']; ?></a>
										  <a href="javascript:void(0);" id="down_<?php echo $answerMsgId; ?>" onMouseOver = "showLikeDislike(1,'<?php echo $answerMsgId; ?>','review');" onMouseOut = "hideLikeDislike(1,'<?php echo $answerMsgId; ?>');" onClick="updateDig(this,'<?php echo $answerMsgId; ?>',0,'review');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="<?=$cssClass1?>" style="color:#000;text-decoration:none"><?php echo $questionList[$i][0]['digDown']; ?></a>
										</span>
									  </td>
									</tr>
									<tr>
									  <td width='10%'><div id="likeDiv<?php echo $answerMsgId; ?>" style="display:block;visibility:hidden;"></div></td>
									  <td><div id="dislikeDiv<?php echo $answerMsgId; ?>" style="display:block;visibility:hidden;"></div></td>
									</tr>
									</table>
							</div>
							<!-- Block End for Dig up and Dig down -->
							<?php } ?>


							<!-- Block Start for Report Abuse link -->
							<div class="float_R" style="valign:top;">
								<div>
										<?php if($userId!=$questionUserId){ if($questionList[$i][0]['reportedAbuse']==0){
										  if(!(($isCmsUser == 1)&&($questionList[$i][0]['status']=='abused'))){
										  	//report abuse for discussion and announcement
										  	if(!isset($abuseTrackingPageKeyID)){ $abuseTrackingPageKeyID=0;}
										?>
										<span id="abuseLink<?php echo $questionMsgId;?>" class="Fnt11"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $questionMsgId; ?>','<?php echo $questionUserId;?>','<?php echo $questionList[$i][0]['threadId']; ?>','<?php echo $questionList[$i][0]['threadId']; ?>','<?php echo $newEntityArray[$questionList[$i]['type']]; ?>',0,0,<?=$abuseTrackingPageKeyID?>);return false;">Report&nbsp;Abuse</a></span>
										<?php }}else{ ?>
										<span id="abuseLink<?php echo $questionMsgId;?>" class="Fnt11">Reported as inappropriate</span>
										<?php }} ?>
								</div>
							</div>
							<!-- Block End for Report Abuse link -->
						</div>
						<div class="clear_B"></div>

						<!-- Block Start for Confirm message like for displaying confirmation messge for dig up -->
						<div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?php  echo $answerMsgId; ?>">&nbsp;</div>
						<!-- Block End for Confirm message like for displaying confirmation messge for dig up -->

						<!-- End Discussion Date, Category and Country display section -->
						<?php }else{ ?>
						<div class='mtb5'>
							<?php if(($displayListing)&&($listingTitle!='')){?>
							  <span class="Fnt11">about <span class="grayFont"><span><b><?php echo $listingTitle;?></b></span></span></span>
							<?php }else{ ?>
							  <span class="Fnt11">in <span class="grayFont"><a href="<?php echo $catCountLink;?>" style="color:#707070;"><?php echo $catCountText;?></a></span></span>
							<?php } ?>
						</div>
						<?php } ?>
						<!-- End Question Date, Category and Country display section -->
					</div>
					<!-- End Question owner, Level and Question Display section -->

					<!-- Start: On the user profile page, if the Question contains any answers, we will display the View answer link -->
					<?php if($questionList[$i]['type']=='question' && $questionList[$i][0]['msgCount']>0){
						$arrowIconShown = true;
					?>
					<div class="pl33" style="margin-top:5px;" id="arrowIcon<?php echo $questionList[$i][0]['msgId'];?>"><img src="/public/images/upArw.png" /></div>
					<div class="fbkBx">
					    <div>
						<div class="float_L wdh100">
							<div class="Fnt11">
							  <a href="<?php echo $questionList[$i][0]['url'];?>" class="vAns Fnt11">View <?php if($questionList[$i][0]['msgCount'] == 1)echo "<span>".$questionList[$i][0]['msgCount']."</span> answer";else echo "all <span>".$questionList[$i][0]['msgCount']."</span> answers"?></a>
							</div>
						</div>
						<s>&nbsp;</s>
					    </div>
					</div>					
					<?php } ?>
					<!-- End: On the user profile page, if the Question contains any answers, we will display the View answer link -->
					
					<!-- Start Answer owner, Level and Answer Display section -->
					<?php if($questionList[$i]['type'] != 'question' && (!in_array($questionList[$i]['type'],$newEntityDisplayArray))){ ?>
					<div class="ana_a fs11">
						<?php if($answerUserId==$userId){ ?>
							  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $answerUserId; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $userProfile.$answerUserDisplayName; ?>"><?php echo $answerUserDisplayFullName;?></a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"><img src="/public/images/fU.png" /></span>
						<?php }else{ ?>
							  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $answerUserId; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $userProfile.$answerUserDisplayName; ?>"><?php echo $answerUserDisplayFullName;?></a></span>
						<?php } ?>
						<?php
							  $answerText = html_entity_decode(html_entity_decode($answerText,ENT_NOQUOTES,'UTF-8'));
							  $quesLength = strlen($answerText);
							  if($quesLength<=140){
								$answerText = formatQNAforQuestionDetailPage($answerText,300);
								echo "<span ".$answerFontClass.">".$answerText."</span>";
								$this->load->view('messageBoard/showAnswerSuggestions',array('answerId'=>$questionList[$i][1]['msgId'],'answerSuggestions'=>$answerSuggestions));
							  }
							  else {
								  echo "<span style='word-wrap:break-word' ".$answerFontClass." id='previewQues".$questionList[$i][1]['msgId']."'>".substr($answerText, 0, 297)."</span>";
								  echo "<span id='relatedQuesDiv".$questionList[$i][1]['msgId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$questionList[$i][1]['msgId']."' onClick='showCompleteAnswerHomepage(".$questionList[$i][1]['msgId'].");'>more</a></span>";
								  $answerText = formatQNAforQuestionDetailPage($answerText,300);
								  echo "<span ".$answerFontClass." id='completeRelatedQuesDiv".$questionList[$i][1]['msgId']."' style='display:none;'>".$answerText;
	  							  $this->load->view('messageBoard/showAnswerSuggestions',array('answerId'=>$questionList[$i][1]['msgId'],'answerSuggestions'=>$answerSuggestions));
								  echo "</span>";
							  }
						?>
						<!-- Start Answer Date, Category and Country display section -->
						<?php if($questionList[$i]['type'] != 'question'){ ?>
						<div class="mtb5">
							<span class="Fnt11"><span class="grayFont"><?php echo $questionList[$i][1]['creationDate']; ?></span> </span>
						</div>
						<?php } ?>
						<!-- End Answer Date, Category and Country display section -->

						<!-- Block Start for Bottom navigation links like Digup, dig down, report abuse -->
						<?php if($questionList[$i]['type'] != 'question'){ ?>
						<div class="lineSpace_5">&nbsp;</div>
						<div class="wdh100">
								<!-- Block Start for Dig up and Dig down -->
								<div class="float_L" style="width:460px;">
										<table cellspacing='0' cellpadding='0' border='0'>
										<tr>
										  <td colspan='2'>
											<span >
											<?php if(isset($topicListings['ratingStatusOfLoggedInUser'][$answerMsgId]) && $topicListings['ratingStatusOfLoggedInUser'][$answerMsgId] == 0){
											$cssClass1 = 'aqIcn dwnVote-actve-icn Fnt11' ;
											$cssClass2 = 'aqIcn upVote-disable-icn Fnt11';
										}
										else if(isset($topicListings['ratingStatusOfLoggedInUser'][$answerMsgId]) && $topicListings['ratingStatusOfLoggedInUser'][$answerMsgId] == 1){
											$cssClass2 = 'aqIcn upVote-actve-icn Fnt11';
											$cssClass1 = 'aqIcn dwnVote-disable-icn Fnt11';
										}
										else {
											$cssClass2 = 'aqIcn rUp Fnt11';
											$cssClass1 = 'aqIcn rDn Fnt11';

										}
										?>
											  <a href="javascript:void(0);" id="up_<?php echo $answerMsgId; ?>" onMouseOver = "showLikeDislike(0,'<?php echo $answerMsgId; ?>');" onMouseOut = "hideLikeDislike(0,'<?php echo $answerMsgId; ?>');" onClick="updateDig(this,'<?php echo $answerMsgId; ?>',1,'','','','','',<?=$tupanstrackingPageKeyId?>);trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="<?=$cssClass2?>" style="color:#000;text-decoration:none"><?php echo $questionList[$i][1]['digUp']; ?></a>
											  <a href="javascript:void(0);" id="down_<?php echo $answerMsgId; ?>" onMouseOver = "showLikeDislike(1,'<?php echo $answerMsgId; ?>');" onMouseOut = "hideLikeDislike(1,'<?php echo $answerMsgId; ?>');" onClick="updateDig(this,'<?php echo $answerMsgId; ?>',0,'','','','','',<?=$tdownanstrackingPageKeyId?>);trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="<?=$cssClass1?>" style="color:#000;text-decoration:none"><?php echo $questionList[$i][1]['digDown']; ?></a>
											</span>
										  </td>
										  <td width="82%">
											  <?php if($questionList[$i]['type'] != 'comment'){ ?>
											  <span style="padding-left:10px;" class="Fnt11 quesAnsBullets"><a href="javascript:void(0);" onClick="showCommentSection('<?php echo $answerMsgId; ?>',true,'<?php echo $questionMsgId; ?>',<?=$ansctrackingPageKeyId?>);">Comment</a>&nbsp;</span>
											  <a href="javascript:void(0);"  onClick="showCommentSection('<?php echo $answerMsgId; ?>',false,'<?php echo $questionMsgId; ?>',<?=$ansctrackingPageKeyId?>,'<?=$raansctrackingPageKeyId?>');" style="color:#707070;"><?php echo $commentMsg;?></a>
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
											<span id="abuseLink<?php echo $answerMsgId;?>" class="Fnt11"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $answerMsgId; ?>','<?php echo $answerUserId;?>','<?php echo $questionMsgId; ?>','<?php echo $questionMsgId; ?>','<?php echo "Answer"; ?>',0,0,<?=$raanstrackingPageKeyId?>);return false;">Report&nbsp;Abuse</a></span>
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
					<!-- Block Start for displaying the Comment Section -->
					<div style="display:none;margin-top:5px;margin-bottom:5px;padding-left:33px;" id="commentDisplaySection<?php  echo $answerMsgId; ?>">&nbsp;</div>
					<!-- Block End for displaying the Comment Section -->
					<?php } ?>
					<!-- End Answer owner, Level and Answer Display section -->

					<!-- Block Start for Answer display Section -->
					<?php if(!in_array($questionList[$i]['type'], $commentsDisplayArray)){ ?>
					  <div style="display:none;margin-bottom:5px;" id="answerSection<?php  echo $questionMsgId; ?>"></div>
					<?php } ?>
					<!-- Block End for Answer display Section -->
					<!--Start_AbuseForm-->
					<div style="display:none;" class="formResponseBrder" id="abuseForm<?php if($questionList[$i]['type'] != 'question') echo $answerMsgId;else echo $questionMsgId;?>"></div>
					<!--End_AbuseForm-->

					<!-- Block start for View More Answers -->
					<?php if(!in_array($questionList[$i]['type'], $commentsDisplayArray)){ ?>
					  <?php if(($questionList[$i][0]['msgCount'] > 1)&&(($questionList[$i]['type'] != 'question'))){ ?>
						<div style="margin-top:5px;margin-bottom:5px;" id="extraAnswersDiv<?php echo $questionMsgId;?>">
						  <a href="javascript:void(0);" class="Fnt11" onClick="return getTopAnswersForWall(<?php echo $questionMsgId;?>,<?php echo $answerMsgId;?>,'<?php echo $catCountLink;?>','<?php echo $catCountText;?>','<?php echo $questionList[$i][0]['msgCount']; ?>','start<?php echo $questionMsgId;?>','count<?php echo $questionMsgId;?>','<?=$selectedTab?>');" id="viewallLink<?php echo $questionMsgId;?>"><span id="answerCountHolder<?php echo $questionMsgId;?>"><?php echo ($questionList[$i][0]['msgCount'] - 1); ?></span> More <?php if(($questionList[$i][0]['msgCount'] - 1)==1) echo "Answer";else echo "Answers"; ?> <img border='0' src="/public/images/barw.gif" align="absmiddle"/></a>&nbsp;<span id="viewallLinkImage<?php echo $questionMsgId;?>" style="display:none;"><img src="/public/images/working.gif" align="absmiddle"/></span>
						  <span id="viewallAnswerSpan<?php echo $questionMsgId;?>" class="Fnt11" ><a href="<?php echo $questionUrl;?>" style="display:none" class="Fnt11" id="viewallAnswerLink<?php echo $questionMsgId;?>">View all <?php echo $questionList[$i][0]['msgCount'];?> answers</a></span>
						</div>
					<?php } } ?>
					<!-- Block end for View More Answers -->

					<input type="hidden" id="start<?php echo $questionMsgId;?>" value=0 />
					<input type="hidden" id="count<?php echo $questionMsgId;?>" value=2 />
					
					<!-- Block Start for Answer display Section -->
					<?php if(!in_array($questionList[$i]['type'], $commentsDisplayArray)){ ?>
					  <div style="display:none;margin-top:10px;margin-bottom:5px;" id="yourAnswer<?php  echo $questionMsgId; ?>">&nbsp;
					  </div>
					<?php } ?>
					<!-- Block End for Answer display Section -->

					<!-- Start Answer Display section -->
					<?php if(true) { ?>
					<?php if(!in_array($questionList[$i]['type'], $commentsDisplayArray)) $commentIdName = 'commentSection'; else $commentIdName = 'commentSectionDisplayDiv'; ?>
					<div id="<?php echo $commentIdName.$questionMsgId;?>" style="margin-top:5px;">
						<?php if(in_array($questionList[$i]['type'], $commentsDisplayArray) && ($userGroup!='cms' || (count($questionList[$i])-3)>0 ) && $showArr!='false' && $arrowIconShown!=true){ ?>
						  <div class="pl33" style='padding-left:66px;'><img src="/public/images/upArw.png" /></div>
						<?php }else if(!((($questionList[$i][0]['msgCount']==0) && ($questionUserId==$userId || $inlineFormHtml==''))) && ($ansNowLink=='')){ ?>
						<?php if(!($questionUserId==$userId && $questionList[$i]['type']!='comment') && $showArr!='false' && $arrowIconShown!=true){ ?>
						  <div class="pl33" <?php if(in_array($questionList[$i]['type'], $commentsDisplayArray))echo "style='padding-left:66px;'";?>><img src="/public/images/upArw.png" /></div>
						<?php } }
						if(in_array($questionList[$i]['type'], $commentsDisplayArray)){ ?>
						<!-- Block start for View All Answers/Comments Link -->
						  <div id="<?php echo 'repliesContainer'.$answerMsgId; ?>" style="display:block;padding-left:33px;">
						  <?php
						  if(in_array($questionList[$i]['type'],$newCommentEntityDisplayArray)){
						      $numberOfComments = count($questionList[$i])-3;
						      $commentTemp = 1;
						      $commentSub = 3;
						  }
						  else{
						      $numberOfComments = count($questionList[$i])-4;
						      $commentTemp = 2;
						      $commentSub = 2;
						  }
						  if(($numberOfComments > 2)){
						    if(in_array($questionList[$i]['type'], $newEntityDisplayArray)) $countComments = $questionList[$i][3]['commentCountTotal']; else $countComments = $questionList[$i][1]['commentCount'];
						  ?>
						  <div class="fbkBx" id="viewAllDiv<?php echo $answerMsgId;?>" style="width:507px;">
							  <div>
								  <div class="float_L wdh100">
										  <div class="Fnt11">
											  <?php if(($countComments > 10) && (in_array($questionList[$i]['type'],$newCommentEntityDisplayArray))){ ?>
											  <a href="<?php echo $questionList[$i][0]['url'];?>" class="Fnt11" style="background: url('/public/images/discussion-orangecolor.gif') no-repeat scroll left 0px transparent; padding-left: 25px;">View All <span id="replyAnswerCount<?php echo $answerMsgId;?>"><?php echo $countComments;?></span> Comments</a>
											  <?php }else{ ?>
											  <a href="javascript:void(0)" onClick="javascript:showMyAnswerComments('<?php echo $answerMsgId; ?>');return false;" class="Fnt11" style="background: url('/public/images/discussion-orangecolor.gif') no-repeat scroll left 0px transparent; padding-left: 25px;">View All <span id="replyAnswerCount<?php echo $answerMsgId;?>"><?php echo $countComments;?></span> Comments</a>
											  <?php } ?>
										  </div>
								  </div>
								  <s>&nbsp;</s>
							  </div>
						  </div>
						  <?php }
						  if($numberOfComments>2) echo '<div id="commentDiv'.$answerMsgId.'" style="display:none;">';
						  for($x=$commentTemp;$x<(count($questionList[$i])-3);$x++){
								if(($x==(count($questionList[$i])-($commentTemp+$commentSub)))&&($numberOfComments>2)) echo "</div>";
								//var_dump($questionList[$i][$x]);
								?>
								<!-- Block start for displaying the comments -->
								<div class="fbkBx" style="width:507px;">
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
															  <?php echo $questionList[$i][$x]['firstname'].' '.$questionList[$i][$x]['lastname']; ?></a></span>
															  <?php if(($userId == $questionList[$i][$x]['userId'])){?><span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"> <img src="/public/images/fU.png" /></span>&nbsp; <?php } ?>
															  <?php if($questionList[$i][$x]['parentDisplayName'] != ''){ ?><span><a href="<?php echo $userProfile.$questionList[$i][$x]['parentDisplayName']; ?>">@<?php echo $questionList[$i][$x]['parentDisplayName']; ?></a></span><?php } ?>
															  <?php
																  $commentDisplayText = html_entity_decode(html_entity_decode($questionList[$i][$x]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
																  //Show only the first 500 characters of the comment followed by a More link
																  $commentCharLength = strlen($commentDisplayText);
																  if($commentCharLength<500){
																    $commentDisplayText = formatQNAforQuestionDetailPage($commentDisplayText,500);
																    echo $commentDisplayText;
																  }
																  else{
																    echo "<span id='previewComment".$questionList[$i][$x]['msgId']."'>".formatQNAforQuestionDetailPage(substr($commentDisplayText, 0, 497))."</span></a>";
																    echo "<span id='relatedCommentDiv".$questionList[$i][$x]['msgId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedCommentHyperDiv".$questionList[$i][$x]['msgId']."' onClick='showCompleteCommentHomepage(".$questionList[$i][$x]['msgId'].");'>more</a></span>";
																    $commentDisplayText = formatQNAforQuestionDetailPage($commentDisplayText,2500);
																    echo "<span id='completeRelatedCommentDiv".$questionList[$i][$x]['msgId']."' style='display:none;'>".$commentDisplayText."</span>";
																  }
															  ?>
														  </span>
														</div>
														<div class="Fnt11 fcdGya float_L" style="padding-top:2px;">
														  <span><?php echo makeRelativeTime($questionList[$i][$x]['creationDate']);?></span><?php if($questionList[$i][$x]['parentDisplayName'] != ''){ ?><span> in reply to <?php echo $questionList[$i][$x]['parentDisplayName']; ?></span><?php } ?>
														</div>
														<div class="float_R">
															<?php if($userId!=$questionList[$i][$x]['userId']){
															  if($questionList[$i][$x]['reportedAbuse']==0){
															  if(!(($isCmsUser == 1)&&($questionList[$i][$x]['status']=='abused'))){
															?>
															    <?php if(in_array($questionList[$i]['type'], $newEntityDisplayArray)){
																  if($questionList[$i][$x]['parentDisplayName'] != ''){ $entityTypeComment = $newEntityArray[$questionList[$i]['type']]." Reply"; } else { $entityTypeComment = $newEntityArray[$questionList[$i]['type']]." Comment";}
															    
																      switch($entityTypeComment)
																      {

																      	case 'discussion Reply':
																      				$abuseTrackingpageKeyID=$radrtrackingPageKeyId;
																      				break;
																      	case 'discussion Comment':
																      				$abuseTrackingpageKeyID=$radctrackingPageKeyId;
																      				break;
																      	case 'announcement Reply':
																      				$abuseTrackingpageKeyID=$raartrackingPageKeyId;
																      				break;
																      	case 'announcement Comment':
																      				$abuseTrackingpageKeyID=$raactrackingPageKeyId;
																      				break;
																      }
																     
																 ?>
															    <span id="abuseLink<?php echo $questionList[$i][$x]['msgId'];?>" class="Fnt11"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $questionList[$i][$x]['msgId']; ?>','<?php echo $questionList[$i][$x]['userId'];?>','<?php echo $questionList[$i][$x]['parentId'];?>','<?php echo $questionList[$i][$x]['threadId']; ?>','<?php echo $entityTypeComment;?>','0','0',<?=$abuseTrackingpageKeyID?>);return false;">Report&nbsp;Abuse</a></span>
															    <?php }else{ ?>
															    <span id="abuseLink<?php echo $questionList[$i][$x]['msgId'];?>" class="Fnt11"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $questionList[$i][$x]['msgId']; ?>','<?php echo $questionList[$i][$x]['userId'];?>','<?php echo $questionList[$i][$x]['parentId'];?>','<?php echo $questionList[$i][$x]['threadId']; ?>','','0','0',<?=$raansctrackingPageKeyId?>);return false;">Report&nbsp;Abuse</a></span>
															    <?php } ?>

															<?php }}else{ ?>
															<span id="abuseLink<?php echo $questionList[$i][$x]['msgId'];?>" class="Fnt11">Reported as inappropriate</span>
															<?php }} ?>
														</div>
														<div class="clear_B">&nbsp;</div>

														<!-- Block Start for Rating Display in case of Review comments -->
														<?php if($questionList[$i]['type'] == 'reviewcomment'){ ?>
														<div class="float_L" style="margin-right:15px;">
														      <table cellspacing='0' cellpadding='0' border='0'>
														      <tr>
															<td colspan='2'>
															      <span >
															    <?php if(isset($topicListings['ratingStatusOfLoggedInUser'][$answerMsgId]) && $topicListings['ratingStatusOfLoggedInUser'][$answerMsgId] == 0){
											$cssClass1 = 'aqIcn dwnVote-actve-icn Fnt11' ;
											$cssClass2 = 'aqIcn upVote-disable-icn Fnt11';
										}
										else if(isset($topicListings['ratingStatusOfLoggedInUser'][$answerMsgId]) && $topicListings['ratingStatusOfLoggedInUser'][$answerMsgId] == 1){
											$cssClass2 = 'aqIcn upVote-actve-icn Fnt11';
											$cssClass1 = 'aqIcn dwnVote-disable-icn Fnt11';
										}
										else {
											$cssClass2 = 'aqIcn rUp Fnt11';
											$cssClass1 = 'aqIcn rDn Fnt11';

										}
										?>
																<a href="javascript:void(0);" id="up_<?php echo $questionList[$i][$x]['msgId']; ?>" onMouseOver = "showLikeDislike(0,'<?php echo $questionList[$i][$x]['msgId']; ?>','comment');" onMouseOut = "hideLikeDislike(0,'<?php echo $questionList[$i][$x]['msgId']; ?>');" onClick="updateDig(this,'<?php echo $questionList[$i][$x]['msgId']; ?>',1,'comment');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="<?=$cssClass2?>" style="color:#000;text-decoration:none"><?php echo $questionList[$i][$x]['digUp']; ?></a>
																<a href="javascript:void(0);" id="down_<?php echo $questionList[$i][$x]['msgId']; ?>" onMouseOver = "showLikeDislike(1,'<?php echo $questionList[$i][$x]['msgId']; ?>','comment');" onMouseOut = "hideLikeDislike(1,'<?php echo $questionList[$i][$x]['msgId']; ?>');" onClick="updateDig(this,'<?php echo $questionList[$i][$x]['msgId']; ?>',0,'comment');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="<?=$cssClass1?>" style="color:#000;text-decoration:none"><?php echo $questionList[$i][$x]['digDown']; ?></a>
															      </span>
															</td>
														      </tr>
														      <tr>
															<td width='10%'><div id="likeDiv<?php echo $questionList[$i][$x]['msgId']; ?>" style="display:block;visibility:hidden;"></div></td>
															<td><div id="dislikeDiv<?php echo $questionList[$i][$x]['msgId']; ?>" style="display:block;visibility:hidden;"></div></td>
														      </tr>
														      </table>
														</div>
														<?php } ?>
														<!-- Block End for Rating Display in case of Review comments -->

														<!-- Block start for Reply link in case of discussion/review/announcement for comments -->
														<?php if(in_array($questionList[$i]['type'], $newEntityDisplayArray) && ($userId != $questionList[$i][$x]['userId'])){
														
														switch($questionList[$i]['type'])
														{
															case 'discussioncomment':
																		$replyTrackingPageKeyID=$drtrackingPageKeyId;
																		break;
															case 'announcementcomment':
																		$replyTrackingPageKeyID=$artrackingPageKeyId;
																		break;
															default:
																		$replyTrackingPageKeyID='NULL';
																		break;

														}

														?>
														<div class="Fnt11"><a href="javascript:void(0);" onClick="try{ showAnswerCommentForm('<?php echo $questionList[$i][0]['msgId']; ?>',<?=$replyTrackingPageKeyID?>); $('submitButton<?php echo $questionList[$i][0]['msgId']; ?>').className = 'orange-button'; $('submitButton<?php echo $questionList[$i][0]['msgId']; ?>').value = 'Reply'; $('replyToUser<?php echo $questionList[$i][0]['msgId']; ?>').innerHTML='Reply to <?php echo $questionList[$i][$x]['firstname'].' '.$questionList[$i][$x]['lastname']; ?> '; $('replyToUser<?php echo $questionList[$i][0]['msgId']; ?>').style.display='block'; $('immediateParentId<?php echo $questionList[$i][0]['msgId']; ?>').value='<?php echo $questionList[$i][$x]['msgId'];?>'; $('immediateParentName<?php echo $questionList[$i][0]['msgId']; ?>').value='<?php echo $questionList[$i][$x]['firstname'].' '.$questionList[$i][$x]['lastname'];?>';}catch (e){} return false;">Reply</a></div>
														<?php } ?>
														<!-- Block End for Reply link in case of discussion/review/announcement for comments -->
														<div class="clear_B">&nbsp;</div>
														<!-- Block Start for Confirm message like for displaying confirmation messge for dig up in case of Review comments -->
														<?php if($questionList[$i]['type'] == 'reviewcomment'){ ?>
														<div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?php  echo $questionList[$i][$x]['msgId']; ?>">&nbsp;</div>
														<?php } ?>
														<!-- Block End for Confirm message like for displaying confirmation messge for dig up in case of Review comments -->
													</div>
												</div>
										</div>
										<s>&nbsp;</s>
										<!--Start_AbuseForm-->
										<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $questionList[$i][$x]['msgId'];?>"></div>
										<!--End_AbuseForm-->
									</div>
								</div>
								<!-- Block End for displaying the comments -->
						<?php } ?>
						</div>
						<div id="replyPlace<?php echo $answerMsgId;  ?>" style="margin-left:33px;"></div>
						<?php } ?>
						<!-- Block End for View All Answers/Comments Link -->

						<!-- Block start for Enter Answer form -->
						<?php if($ansNowLink=='' && $inlineFormHtml!='' && (!in_array($questionList[$i]['type'], $commentsDisplayArray))){ ?>
						<div class="fbkBx">
							<div>
								<div class="float_L wdh100">
									<?php echo $inlineFormHtml; ?>
								</div>
								<s>&nbsp;</s>
							</div>
						</div>
						<?php }else if($inlineFormHtml!=''){
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
					<?php if(in_array($questionList[$i]['type'], $commentsDisplayArray)){ ?>
					  <div style="display:none;margin-bottom:5px;" id="answerSection<?php  echo $questionMsgId; ?>"></div>
					<?php } ?>
					<!-- Start Message View Answers in case of comments -->
					<div>
						<?php if(($questionList[$i]['type'] == 'comment') && ($questionList[$i]['type'] != 'discussion') &&(($questionList[$i][0]['msgCount'] > 1)||(($questionList[$i][0]['status'] != 'closed')&&($userId != $questionList[$i][1]['userId'])))){ ?>
						<div style="padding-top:5px;padding-bottom:5px;">
							<div style="width:100%">
								<div>
 									  <span class="Fnt11">
					  					  <?php if(($questionList[$i][0]['msgCount'] > 1)){ ?><a href="javascript:void(0);" class="Fnt11" onClick="return getTopAnswersForWall(<?php echo $questionMsgId;?>,<?php echo $answerMsgId;?>,'<?php echo $catCountLink;?>','<?php echo $catCountText;?>','<?php echo $questionList[$i][0]['msgCount']; ?>','start<?php echo $questionMsgId;?>','count<?php echo $questionMsgId;?>','<?=$selectedTab?>');" id="viewallLink<?php echo $questionMsgId;?>"><span id="answerCountHolder<?php echo $questionMsgId;?>"><?php echo ($questionList[$i][0]['msgCount'] - 1); ?></span> More <?php if(($questionList[$i][0]['msgCount'] - 1)==1) echo "Answer";else echo "Answers"; ?> <img border='0' src="/public/images/barw.gif" align="absmiddle"/></a>&nbsp;<span id="viewallLinkImage<?php echo $questionMsgId;?>" style="display:none;"><img src="/public/images/working.gif" align="absmiddle"/></span><?php } ?>
										  <span id="viewallAnswerSpan<?php echo $questionMsgId;?>" class="Fnt11" ><a href="<?php echo $questionUrl;?>" style="display:none" class="Fnt11" id="viewallAnswerLink<?php echo $questionMsgId;?>">View all <?php echo $questionList[$i][0]['msgCount'];?> answers</a></span>
										  <?php if(($questionList[$i][0]['status'] != 'closed')&&($userId != $questionList[$i][1]['userId'])){ ?><span id="answerQuestion<?php echo $questionMsgId;?>"><?php if(($questionList[$i][0]['msgCount'] > 1)){ ?><span class="grayFont" id="separatorDiv<?php echo $questionMsgId;?>">|&nbsp;</span><?php } ?><a href="javascript:void(0);" onClick="showAnswerSection('<?php echo $questionMsgId;?>',<?=$anstrackingPageKeyId?>);">Answer the question</a></span><?php } ?>
									  </span>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<!-- End Message View Answers in case of comments -->
					<!-- Block Start for Answer display Section -->
					<?php if(in_array($questionList[$i]['type'], $commentsDisplayArray)){ ?>
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
	else	//In Case of Level type
	{
?>
	<!-- Main Div Start -->
	<div class="aqAns" style="border-bottom:1px solid #eaeeed;">
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
			  echo "<div style='margin-left:100px;margin-top:10px;height:".$lineHeight."px;line-height:16px;margin-bottom:15px;'>";
                            echo "<div style='float:right;width:130px'>";
                                echo "<div style='float:right'>";
                        ?>
                        <?php //    echo $this->userStatus[0]['userid'];  echo $questionList[$i]['userId'];
                        if($this->userStatus!='false' && $this->userStatus[0]['userid']== $questionList[$i]['userId']){
                        ?>
                        <!--<div id="fb-root"></div>-->
                       <!-- <script >
                        var temp = 'fShare';
                        FB.init({
                        appId:<?php //echo FACEBOOK_API_ID; ?>, cookie:true,
                        status:true, xfbml:true
                        });
                        </script> -->
                        <!--<div id="fbButton">
                        <fb:login-button size="small" scope="email,user_checkins,offline_access,read_stream,publish_stream" on-login="callFConnectAndFShare('level');">Share with Friends</fb:login-button>
                        </div>-->
                        <?php } ?>
                        <?php
                                echo "</div>";
                                echo "<div class='".$displayStarString['html']." txt_align_c Fnt11 fcdGya' style='float:right;padding-top:65px;width:100px'>".$displayStarString['value']."</div>";
                            echo "</div>";


                            echo "<div style='float:left;width:420px'><b>Congratulation</b>&nbsp;".$UserDisplayText." !<br/>";
			  echo "<b>You have been promoted to ".$UserLevelText."</b> <span class='Fnt11 fcdGya'>".makeRelativeTime($questionList[$i]['sortingTime'])."</span><br/>";
			  echo "<div style='margin-top:5px' class='Fnt11'>Your guidance will go on to help thousands of students. Thank you and keep answering.</div>";
			  
			  echo "</div></div>";
			  echo "<div class='clear_B'></div>";
			?>
		 </div>
		<div class="lineSpace_10">&nbsp;</div>
	</div>
	<!-- Main Div End -->

<?php
	}
	$lastTimeStamp = $questionList[$i]['sortingTime'];
  }
?>
<input type="hidden" id="pageKeyForSubmitComment" value="<?php echo $pageKeySuffixForDetail.'SUBMITCOMMENT'; ?>" />
<input type="hidden" id="pageKeyForReportAbuse" value="<?php echo $pageKeySuffixForDetail.'REPORTABUSE'; ?>" />
<input type="hidden" id="pageKeyForDigVal" value="<?php echo $pageKeySuffixForDetail.'UPDATEDIGVAL'; ?>" />
<?php if(count($questionList)==0 && $pageKeySuffixForDetail != "ANA_USER_PROFILE"){ ?>
  <div style="margin-top:10px;margin-left:5px;">No <?php if($start>0) echo "more";?> activites in this Category and Country</div>
<?php }else if(count($questionList)==0 && $pageKeySuffixForDetail == "ANA_USER_PROFILE"){ ?>
  <div style="margin-top:10px;margin-left:5px;">No <?php if($start>0) echo "more";?> activites for this user</div>
<?php }else{ ?>
<div id="olderPostDiv<?php echo $start;?>"></div>
<div id="olderPostLink<?php echo $start;?>" style="margin:10px 0;background:#ECECEC;padding:7px" >
	<?php if($pageKeySuffixForDetail != "ANA_USER_PROFILE"){ ?>
	<a href="javascript:void(0);" onclick="showOlderPosts('<?php echo $categoryId;?>','<?php echo $countryId;?>','<?php echo $start;?>','<?php echo $count;?>','<?php echo $this->csvThreadIds; ?>','<?php echo $lastTimeStamp;?>','<?php echo $tabselected?>');trackEventByGA('LinkClick','OLDER_ACTIVITIES');return false;">Older Activities <img border='0' src="/public/images/barw.gif" align="absmiddle"/></a>
	<?php }else{ ?>
	<a href="javascript:void(0);" onclick="showOlderProfilePosts('<?php echo $tabSelected;?>','<?php echo $viewedDisplayName;?>','<?php echo $start;?>','<?php echo $count;?>','<?php echo $this->csvThreadIds; ?>','<?php echo $lastTimeStamp;?>');trackEventByGA('LinkClick','USERPROFILE_OLDER_ACTIVITIES');return false;">Older Activities <img border='0' src="/public/images/barw.gif" align="absmiddle"/></a>
	<?php } ?>
</div>
<div id="waitingDiv<?php echo $start;?>" style="margin:10px 0;background:#ECECEC;padding:7px;display:none;" ></div>
<?php } ?>
</div>
<script>
	if((<?php echo $start;?> == 0) && (<?php echo count($questionList);?> > 0) && ('<?php echo $pageKeySuffixForDetail;?>'!='ANA_USER_PROFILE')){
		showOlderPosts('<?php echo $categoryId;?>','<?php echo $countryId;?>','<?php echo $start;?>','<?php echo $count;?>','<?php echo $this->csvThreadIds; ?>','<?php echo $lastTimeStamp;?>','<?php echo $tabselected?>');
	}
	else if((<?php echo $start;?> == 0) && (<?php echo count($questionList);?> > 0) && ('<?php echo $pageKeySuffixForDetail;?>'=='ANA_USER_PROFILE')){
		showOlderProfilePosts('<?php echo $tabSelected;?>','<?php echo $viewedDisplayName;?>','<?php echo $start;?>','<?php echo $count;?>','<?php echo $this->csvThreadIds; ?>','<?php echo $lastTimeStamp;?>');
	}
buttonForFConnectAndFShare();
</script>
