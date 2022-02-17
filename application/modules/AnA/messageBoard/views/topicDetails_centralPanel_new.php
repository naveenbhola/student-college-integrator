<?php


$topicUrl = site_url('messageBoard/MsgBoard/topicDetails').'/'.$topicId;

$answerCountForQuestion = $mainAnsCount;
$answerText = ($answerCountForQuestion <= 1)?' answer':' answers';
$viewsCountForQuestion = isset($main_message['viewCount'])?$main_message['viewCount']:0;
$viewsText = ($viewsCountForQuestion <= 1)?'<span class="blackFont">'.$viewsCountForQuestion.'</span> view':'<span class="blackFont">'.$viewsCountForQuestion.'</span> views';

$mainTextBoxWidth = "width:930px;";
if($_COOKIE['client']<=1024){
	$mainTextBoxWidth = "width:674px;";
}

if($_COOKIE['client']<=800){
	$mainTextBoxWidth = "width:450px;";
}
$isCmsUser =0;
if((is_array($validateuser))&&(strcmp($validateuser[0]['usergroup'],'cms') == 0)){
	$isCmsUser = 1;
}
$lnUserDisplayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
$userProfile = site_url('getUserProfile').'/';
$userImage =  isset($main_message['userImage'])?getSmallImage($main_message['userImage']):'';
$threadId = $main_message['msgId'];
$url = site_url("messageBoard/MsgBoard/replyMsg");
$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
$isAskQuestion = false;
//if($srcPage=='askQuestion'){
//	$isAskQuestion = true;
//}
if($userId == 0){
	$isAskQuestion = false;
}
$isQuestionUser = false;
if($userId == $main_message['userId']){
	$isQuestionUser = true;
}
$editorPickFlag = isset($main_message['editorPickFlag'])?$main_message['editorPickFlag']:0;
//$this->load->view('common/userCommonOverlay');
//$this->load->view('network/mailOverlay',$data);
$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
echo "<script language=\"javascript\"> ";
echo "var commentCount  = ".$answerCountForQuestion.";";
echo "var topicUrl  = '".$topicUrl."';";
echo "var BASE_URL = '';";
echo "var COMPLETE_INFO = ".$quickSignUser.";";
$requestUri = $this->security->xss_clean($_SERVER['REQUEST_URI']);
echo "var URLFORREDIRECT = '".base64_encode($requestUri)."';";
echo "var loggedInUserId = '".$userId."';";
echo "var isCmsUser = '".$isCmsUser."';";
echo "var hasModeratorAccess = '".$hasModeratorAccess."';";
echo "</script> ";

$paginationHTML = doPagination($totalNumOfRows-1,$paginationURL,$start,$count,10);

$courseQuestion = ( isset($courseObj) && !empty($courseObj) ) ? 1 : 0 ;

if($courseQuestion) {
	$currentLocation = $courseObj->getMainLocation();
}else {
	$currentLocation = $insObj->getMainLocation();
}

$instituteDisplayName = html_escape($insObj->getName()) .' ,' .
		(  ($currentLocation->getLocality() && $currentLocation->getLocality()->getName())? $currentLocation->getLocality()->getName()
				.", ": " ") .  $currentLocation->getCity()->getName();

$questionText = html_entity_decode(html_entity_decode($main_message['msgTxt'],ENT_NOQUOTES,'UTF-8'));

$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
//$text = "The text you want to filter goes here. http://google.com";
if(preg_match($reg_exUrl, $questionText, $url)) {
	//echo preg_replace($reg_exUrl, "<a href=".$url[0].">".$url[0]."</a> ", $text);
	$questionText = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $questionText);
}

$userProfileLink = $userProfile.$main_message['displayname'];
$displayName = (!empty($main_message['lastname']))?$main_message['firstname'].' '.$main_message['lastname'] : $main_message['firstname'];

$url_parts = parse_url("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
$constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
$urlAnA = urlencode($constructed_url);

?>

<input id="exclude_question_id" type="hidden" value="<?php echo $main_message['msgId'];?>">
<script>
  overlayViewsArray.push(new Array('common/userCommonOverlay','userCommonOverlayForVCard'));
  overlayViewsArray.push(new Array('network/mailOverlay','mailOverlay'));
</script>

<!-- Start of best answer overlay -->
<div style="padding: 50px; display: none;" id="chooseBestAnswerOverLay">
	<div>
		<?php
		$urlForBestAns = site_url("messageBoard/MsgBoard/chooseBestAns");
		echo $this->ajax->form_remote_tag( array('url'=>$urlForBestAns ,'before' => 'beforeChooseBestAnswer();','success' => 'javascript:afterChooseBestAnswer(request.responseText);'));
		?>
		<div class="errorMsg mar_full_10p" id="confirmMsgForBestAnsOverLay"></div>
		<div style="padding: 0 10px">
			<div class="lineSpace_10p">&nbsp;</div>
			<div>
				<input type="radio" name="close" id="closeDiscussion1" value="1"
					checked /> Yes, I have found what I was looking for. Close this
				question
			</div>
			<div class="lineSpace_10p">&nbsp;</div>
			<div>
				<input type="radio" name="close" id="closeDiscussion2" value="0" />
				No, keep the question open for 3 more days. I want to receive more
				answers
			</div>
			<div class="lineSpace_10p">&nbsp;</div>
			<input type="hidden" id="msgIdForBestAnswer" name="msgId" value="-1" />
			<input type="hidden" id="threadIdForBestAnswer" name="threadId"
				value="-1" /> <input type="hidden" id="commentUserIdForBestAnswer"
				name="commentUserId" value="-1" />

			<div align="center">
				<input type="Submit" value="Submit" id="chooseBestAnswerSubmit"
					class="orange-button" /> &nbsp; <a
					onclick="javascript:hideOverlay();" href="javascript:void(0);">Cancel</a>
			</div>
			<div class="lineSpace_10p">&nbsp;</div>
		</div>
		</form>
	</div>
</div>
<!-- End of best answer overlay -->


<!-- Start of best answer with digUp overlay -->
<div style="padding: 50px; display: none;"
	id="chooseBestAnswerWithDigUpOverLay">
	<div>
		<?php
		$urlForBestAns = site_url("messageBoard/MsgBoard/chooseBestAns");
		echo $this->ajax->form_remote_tag( array('url'=>$urlForBestAns ,'before' => 'beforeChooseBestAnswer();','success' => 'javascript:afterChooseBestAnswer(request.responseText);'));
		?>
		<div class="errorMsg mar_full_10p"
			id="confirmMsgForBestAnsWithDigUpOverLay"></div>
		<div style="padding: 0 10px">
			<div class="lineSpace_10p">&nbsp;</div>
			<h3>Do you also want to select this answer as The Best Answer?</h3>
			<div class="lineSpace_10p">&nbsp;</div>
			<input type="hidden" id="msgIdForBestAnswerWithDigUp" name="msgId"
				value="-1" /> <input type="hidden"
				id="threadIdForBestAnswerWithDigUp" name="threadId" value="-1" /> <input
				type="hidden" id="commentUserIdForBestAnswerWithDigUp"
				name="commentUserId" value="-1" /> <input type="hidden"
				id="closeDiscussion1" name="close" value="1" />
			<div align="center">
				<button type="Submit" value="Yes" id="chooseBestAnswerSubmit"
					style="color: #FFFFF" class="orange-button">Yes</button>
				&nbsp; <a href="#" onClick="javascript:hideOverlay();">No</a>
			</div>
			<div class="lineSpace_10p">&nbsp;</div>
		</div>
		</form>
	</div>
</div>
<!-- End of best answer with digUp overlay -->

<!-- Start of Dig up and YOU image tooltip overlay -->
<div id="digUpDownTooltip" class="blur"
	style="position: absolute; left: 0px; z-index: 1000; display: none; top: 0px;">
	<div class="shadow">
		<div class="content" id="digTooltipContent"></div>
	</div>
</div>
<div id="youTooltip" class="blur"
	style="width: 192px; position: absolute; left: 0px; z-index: 1000; display: none; top: 0px;">
	<div class="shadow">
		<div class="content">&nbsp;click to change your display name</div>
	</div>
</div>
<!-- End of Dig up and YOU image tooltip overlay -->

<!-- Start of Report abuse confirmation overlay -->
<div style="display: none;" id="reportAbuseConfirmationDiv">
	<div>
		<div style="padding: 10px 10px 10px 10px">
			<div class="lineSpace_5p">&nbsp;</div>
			<div align="center">
				<span id="reportAbuseConfirmation" style="font-size: 14px;"></span>
			</div>
			<div class="lineSpace_5p">&nbsp;</div>
			<div align="center">
				<input type="button" value="OK" class="spirit_header RegisterBtn"
					onClick="javascript:hideOverlay();" />
			</div>
			<div class="lineSpace_5p">&nbsp;</div>
		</div>
	</div>
</div>
<!-- End of Report abuse confirmation overlay -->
<input
	type="hidden" name="questionDetailPage" id="questionDetailPage"
	value="" />

<div
	style="margin-bottom: 10px; display: none" id="postingWidget"></div>

<!-- Main Question div starts from here -->

<div
	id="ques-details-wrap">

	<!--Course Content starts here-->
	<div id="ques-details-content">

		<?php $style = ($course_page_required_category)?"margin: 6px 0px 10px 0px;":"margin: 6px 0px 0px 0px;"?>
		<div class="breadcrumb clear-width" style="<?php echo $style;?>">
		 
			<?php $this->load->view('listing/national/widgets/breadcrumb');?>

		</div>
		
		<?php if($course_page_required_category):?>
		<div class="clear-width">
			<?php $this->load->view('messageBoard/headerPanelForAnA',array('questionDetailPage' => true));?>
		</div>
		<?php endif;?>


		<?php $this->load->view('listing/national/widgets/listingsOverlay'); ?>
 		
		<div id="responseFormNew" style="display:none">
			<?php //$this->load->view('listing/national/responseFormContactDetailsTop',array('validateUser' => $validateuser , 'courses' => $courses,'institute' => $insObj ,'source_page' => 'questionDetailPage'));  ?>
		</div>
		
		<div id="contactLayerTop" style="display:none"></div>
		
		<!--Course Left Col Starts here-->
		<div id="ques-details-leftCol">

		<div class="ques-details-title">
			<?php if($courseQuestion):?>
			<h1 style="color:#000000;">
				<?php echo $courseObj->getName();?>
			</h1>
			<?php endif;?>

			
			<a href="<?php echo $insObj->getURL()?>" style="display:inline-block;margin-bottom:8px;"><?php echo $instituteDisplayName;?>
			
			
			</a>
			<br/> 
	<!---		<a href="#" class="send-contact-btn flLt" 
				uniqueattr="LISTING_INSTITUTE_PAGES/BOTTOM_SEND_CONTACT_DETAILS_UPDATED" onclick="showResponseForm('responseFormNew',
					 'Top_course', '<?=$courseObj->getId();?>', 'listingPageTopLinks'); activatecustomplaceholder(); return false;">
				<i class="sms-icon"></i>
				Send Contact Details to Email/SMS
			</a>
		-->	
			<input type="hidden" value="<?php echo $courseObj->getURL();?>" id="question_detail_course_url">
			<div class="clearFix"></div>
			<?php if($courseQuestion):?>
			<a class="back-link" href="<?php echo $courseObj->getURL()."#ca_aqf";?>">Back
				to course page</a>
			<?php endif;?>
		</div>			
		<div class="clearFix"></div>
			<div class="qna-content-box">
				<div class="qna-box">
					<div class="qna-title">
						<?php if(($isQuestionUser) && ($closeDiscussion == 0)){ ?>
						<h4>YOUR OPEN QUESTION</h4>
						<?php } else if(($isQuestionUser) && ($closeDiscussion == 1)){ ?>
						<h4>YOUR RESOLVED QUESTION</h4>
						<?php } else if((!($isQuestionUser)) && ($closeDiscussion == 1)){ ?>
						<h4>QUESTION <span style="font-weight:normal; color:#999">(RESOLVED)</span></h4>
						<?php } else { ?>
						<h4>QUESTION</h4>
						<?php } ?>

						<div class="share-section">
							<span style="float: left;">Share this&nbsp; </span>
							<div class="flLt">
								<iframe
									src="https://www.facebook.com/plugins/like.php?href=<?php echo $urlAnA; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>"
									colorscheme=light " scrolling="no" frameborder="0"
									allowTransparency="true"
									style="border: none; overflow: hidden; width: 75px; height: 20px"></iframe>
							</div>
							<div class="flLt"><iframe id="twitterFrame" allowtransparency="true" frameborder="0" scrolling="no"  src="about:blank"  style="width:82px; height:20px;"></iframe>
							</div>							
							<div class="flLt">
								<g:plusone size="medium"></g:plusone>
								<script type="text/javascript">
							(function() {
							var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
							po.src = 'https://apis.google.com/js/plusone.js';
							var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
							})();
					</script>
							</div>
						</div>
					</div>


					<a href="<?php echo $questionList[$i]['url'];?>"
						style='color: #000000'> <span class=''
						id='completeRelatedQuesDiv<?php echo $main_message['msgId'] ;?>'
						style='display: none;'> <?php echo $main_message['msgTxt']; ?>
					</span>
					</a>


					<p class="ques-text" id="questionId">
						<?php echo formatQNAforQuestionDetailPage($questionText,32);?>
					</p>

					<div class="user-details clear-width">
						<p class="flLt">
							Posted by: 
							<span><a target="_blank"
								href="<?php echo $userProfileLink;?>"> <?php echo $displayName;?>
							</a> </span>
								<span><?php echo $main_message['creationDate'];?></span>							
							 <span style="color: #000"><?php echo $viewsCountForQuestion;?>
							</span> Views
						</p>
						<?php if(!$isQuestionUser):?>
						<?php if($main_message['reportedAbuse'] == 0):?>
						<?php if(!(($isCmsUser == 1)&&($main_message['status']=='abused'))):?>
						<a href="javascript:void(0);"
							onclick="report_abuse_overlay('<?php echo $threadId;?>','<?php echo $main_message['userId'];?>','<?php echo $main_message['parentId'];?>','<?php echo $threadId;?>','Question',0,0,'<?php echo $raqtrackingPageKeyId;?>');"
							class="flRt">Report Abuse</a>
						
						<?php endif;?>
						<?php else:?>
						<span id="abuseLine<?php echo $threadId;?>" class="flRt">Reported
							as inappropriate</span>
						<?php endif;?>
						<?php endif?>

					</div>
					<?php if((!$isQuestionUser) &&($closeDiscussion == 0) && $alreadyAnswer == 0):?>
						<?php if(!$doNoShowAnswerForm){ ?><a onclick="showAnswerFormNewWithCPGSHeader('<?php echo $threadId?>','campusRepDetailPage','<?php echo $anstrackingPageKeyId?>');" href="javascript:void(0);" class="ans-ques-btn">Answer this Question</a><?php } ?>
					<?php endif;?>
					<div class="clearFix"></div>
				</div>

				<?php $this->load->helper('course_page_helper');?>
				
				
				<?php if((!$isQuestionUser) &&($closeDiscussion == 0 ) ): ?>
				<div class="ans-ques-link">
					<?php if($ACLStatus['LinkQuestion']=='live' && count($linkQuestionViewCount->link)<10){?>

					<a style="margin-left:5px;" href="javascript:void(0);"
						onclick="openSearchQuestionOverlay(document.getElementById('questionId').innerHTML,<?php echo $main_message['msgId']; ?>,<?php echo $userId; ?>,<?php echo $main_message['userId'];?>,'Question');trackEventByGA('SEARCH_RESULTS','SHIKSHA_LINK_QUESTIONS_SOURCE_<?php echo $typeOfSearch ;?>_SEARCH');">Link
						Question</a>

					<?php } ?>
					<!--////QnA Rehash Phase-2 end code for showing edit title overlay-->
				</div>
				<?php endif;?>




				<div class="ans-ques-link">

					<!--QnA Rehash Phase-2 Start code for showing edit title overlay-->
					<?php if((($reputationPoints>25 && $reputationPoints!=9999999 && $showEditLink=='true')  || ($userGroup=='cms' && $showEditLink=='true')) || ( $main_message['userId'] ==$userId && $showEditLink=='true' )&& $userId >0){?>
					<div class="float_L">
						<a href="javascript:void(0);"
							onclick="showTitleDiv('completeRelatedQuesDiv<?php echo $main_message['msgId'];?>',<?php echo $main_message['msgId']; ?>,<?php echo $userId; ?>,<?php echo $main_message['userId'];?>)">Add
							Title</a>
					</div>
					<?php }?>

					<!-- Option block start for Delete/Edit/Close Question -->

					<?php
					if(($isQuestionUser && ($answerCountForQuestion <= 0) && ($closeDiscussion == 0)) ||($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3)){
						if($main_message['status']!='deleted' && $main_message['status']!='abused'){
							?>
					<a class="flRt" href="javascript:void(0);"
						onClick="javascript:deleteCafeEntity('<?php echo $topicId; ?>',<?php echo $topicId; ?>,<?php echo $main_message['userId'];?>,'question');"
						style="padding-right: 15px"><span class="cssSprite_Icons "
						style="background-position: 2px -2px; padding-right: 15px">&nbsp;</span>Delete</a>
					<?php }else{ ?>
					<span class="flRt" style="background-position: 0 -2px; padding-right: 15px;">Question
						Deleted</span>
					<?php }
					}

					if(($isQuestionUser) ||($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3) || $caFlag=='true'){
						if((count($topic_messages) == 0) && ($closeDiscussion == 0) || ($isCmsUser == 1) || ($caFlag=='true') ){
							?>
					<a class="flRt" href="javascript:void(0);"
						onClick="showEditForm('<?php echo $threadId; ?>','question');"
						style="padding-right: 15px"><span class="cssSprite_Icons "
						style="background-position: -96px -2px; padding-right: 15px">&nbsp;</span>Edit</a>
					<?php
						}
					}

					if(($isQuestionUser) ||($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3)){
						if($closeDiscussion != 1){
							?>
					<a class="flRt" href="javascript:void(0);"
						onClick="javascript:operateOnDiscussion('<?php echo $topicId; ?>','closeDiscussionTopic');"
						style="padding-right: 15px"><span class="cssSprite_Icons"
						style="background-position: -18px -2px; padding-right: 15px">&nbsp;</span>Close</a>
					<?php
					}else{ ?>
					<span class="flRt" style="margin-right:5px;">Closed Question</span>
					<?php 	}
					}
					?>


					<!-- Option block End for Edit/Close Question -->



				</div>

				<!-- Block Starts for Bottom navigation including Share, Report abuse -->

				<div class="spacer5 clearFix"></div>
				<div class="showMessages" style="margin-top: 10px; display: none;"
					id="confirmMsg<?php  echo $threadId; ?>"></div>

				<!--Start_AbuseForm-->
				<div style="display: none;" class="formResponseBrder"
					id="abuseForm<?php echo $threadId;?>"></div>
				<!--End_AbuseForm-->

				<?php if(isset($topicDetailEdit)): ?>
				<?php $data['isCmsUser'] = $isCmsUser; ?>
				<div id="editCatCounOverlay" style="display: none;">
					<?php $this->load->view('messageBoard/questionEditPostCategoryForm',$data); ?>
				</div>
				<?php endif;?>
				<span id="answerCountHolderForQuestion" style="display:none;"><?php echo $totalComments; ?></span>
				<div class="ans-title">
					<h5>
						ANSWERS <span id="answerCountHolderForQuestion_span">(<?php echo $totalComments; ?>)</span>
					</h5>
					<script>var filterSelected = '<?php echo $filterSel;?>'; var numberOfAnswers = <?php echo $totalComments; ?>;</script>


					<?php if($totalComments>1):?>
					<div class="sorting-section">
						Sort by: <a id="reputationFilter" href="javascript:void(0);"
							onClick="filterAnswers('<?php echo $threadId;?>', 'reputation');"
							<?php if($filterSel=='reputation') echo "class='linkActive'";?>>Reputation</a>
						<span>|</span> <a id="freshnessFilter" href="javascript:void(0);"
							onClick="filterAnswers('<?php echo $threadId;?>', 'freshness');"
							<?php if($filterSel=='freshness') echo "class='linkActive'";?>>Freshness</a>
						<span>|</span> <a id="ratingFilter" href="javascript:void(0);"
							onClick="filterAnswers('<?php echo $threadId;?>', 'rating');"
							<?php if($filterSel=='rating') echo "class='linkActive'";?>>Highest
							rated</a>
					</div>
					<?php endif;?>
					<div class="ans-pinter"></div>
					<div class="clearFix"></div>
				</div>
				
				<?php if($paginationHTML!=''){ ?>
					<div style="margin: 20px 0 0 80px; text-align: right; width:100%;"
						class="pagingID mar_full_10p lineSpace_22 float_R"
						id="paginataionPlace1">
						<?php echo preg_replace('/\/askHome\/default\/-1\/0\/10|-all-askHome-all-all-0-10/','',$paginationHTML); ?>
					</div>
					
					<?php } ?>				

				<?php
				$commentData['url'] = $url;
				$commentData['threadId'] = $threadId;
				$commentData['isCmsUser'] = $isCmsUser;
				$commentData['fromOthers'] = 'user';
				$commentData['maximumCommentAllowed'] = 4;
				$commentData['pageKeySuffixForDetail'] = 'ASK_ASKDETAIL_MIDDLEPANEL_';
				$commentData['subCatID']=$subCatID;
				$this->load->view('messageBoard/topicPage_quesDetail_course',$commentData);
				?>
	
			</div>
						
                <div id="answerFormDetailPage" >
                <?php if($alreadyAnswer != 0): ?>
                    <div class="showMessages" style="margin:20px 0 10px 0">You have already answered this question. We request you to rate the other answers received for this question.</div>
                <?php elseif((!$isQuestionUser) &&($closeDiscussion == 1)): ?>
                    <div class="showMessages" style="margin:20px 0 10px 0">This question is closed for Answering.</div>
                <?php elseif((!$isQuestionUser) &&($closeDiscussion == 0)): ?>
                <?php
                
                    $dataArray = array('quesDetailPage' => true,  'userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => $mainAnsCount,'loggedUserImageURL' => $userImageURL,'userId' => $userId,'detailPageUrl' =>-1,'callBackFunction' => 'try{ addMainCommentForQues('.$threadId.',request.responseText,\'-1\',false,true,\'\',\'\',false,\''.$userImageURL.'\',0,false,true); } catch (e) {}');
                    $this->load->view('messageBoard/replyBox',$dataArray);
                ?>

                <!--<div class="lineSpace_10">&nbsp;</div>-->
                <?php /*echo "<div style='width:100%'><div class='Fnt66612 lineSpace_18 float_R ana_q' style='margin-bottom:5px'><a id='postingWidgetLink' href='javascript:void(0);' class='bld' onClick='showPostingWidget(\"question\");'>Ask a Question</a><span id='postingWidgetNonLink' class='bld' style='display:none;color: #000'>Ask a Question</span></div><div class='clear_B'>&nbsp;</div></div>"; */?>
                <?php endif; ?>
                </div>						
			
			<?php if(!empty($qna)):?>			
			<div  class="other-ques-section clear-width">
				<h3>Other Question about this Course</h3>
			 	<ul class="comment-block" id="otherQues_div">
				<?php $this->load->view('messageBoard/topicPage_quesDetail_otherQues',$main_message['msgId']); ?>
				 </ul>
			</div>
			<?php endif;?>
			<?php if($totalQuesCount > 5):?>
			<a class="load-more" id="other_ques_load" href="javascript:void(0)" onclick="loadMoreQuesDetail()" style="margin-bottom:20px;">Load More Question</a>
			<span class="load-more clear-width" id="load_more_span" style="display:none;margin-bottom:20px;">No more questions to show</span >
			<?php endif;?>
			
			<div style="margin-top:20px;" class="clear-width">
				<?php echo Modules::run('CA/CADiscussions/getQuestionForm',$courseObj->getId(),$insObj->getId(),true,false,'',$qtrackingPageKeyId); ?>
			</div>
			
		</div>
		<!--Course Left Col Ends here-->

		<!--Course Right Col Starts here-->
		<div id="ques-details-rightCol">

		<?php if($insObj->getLogo()): ?>
		<?php 
		if(strrpos($insObj->getLogo(), '_m.') !== false || strrpos($insObj->getLogo(), '_s.') !== false) {
			$logoImage = $insObj->getLogo();
		} else {
			$pos = strrpos($insObj->getLogo(), '.');
			if($pos !== false) {
				$extStr = substr($insObj->getLogo(), $pos);
				$logoImage = str_replace($extStr, "_m".$extStr, $insObj->getLogo());
			}
		}		
		?>
		<div class="col-logo">
			<i class="col-left-arrow"></i> <img align="left" alt="<?=html_escape($insObj->getName())?>, <?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName().' Logo'?>"  <?=$widthClause?>  title="<?=html_escape($insObj->getName())?>, <?=(($currentLocation->getLocality() && $currentLocation->getLocality()->getName()) ? $currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName().' Logo'?>" src="<?=$logoImage?>">
		</div>
		<?php endif;?>	
			<div class="clearFix"></div>
			<div>
			<?php 
				$this->load->view('listing/national/widgets/courseCampusRepRight');
			?>
			</div>
			<div>
				<?php 	
					  $rightPanelArray = array();
					  $rightPanelArray['topicId'] = $topicId;
					  $rightPanelArray['topicName'] = $topicName;
					  $rightPanelArray['alertNameValue'] = $alertNameValue;
					  $rightPanelArray['alertId'] = $alertId;
					  $rightPanelArray['categoryId'] = $categoryId;
					  $rightPanelArray['userId'] = $userId;
					  $this->load->view('messageBoard/topicDetails_right_new',$rightPanelArray);
				?>
			</div>	
		</div>
		<!--Course Right Col Ends here-->
	</div>
	<!--Course content ends here-->
</div>

