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
	if($fromOthersTopic == 'discussion'){
		$totalNumOfRows = $topic_messages[0][0]['childCount'];
	}else{
		$totalNumOfRows = $topic_messages[0][0]['msgCount'];
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
	echo "</script> ";

	$paginationHTML = doPaginationCafe($totalNumOfRows,$paginationURL,$start,$count,10);
	$displayArr = array('announcement'=>'Announcements', 'discussion'=>'Topic','eventAnA'=>'Announcements','review'=>'Review');
	$newPostArr = array('announcement'=>'Make a New Announcement', 'discussion'=>'Start a new discussion','eventAnA'=>'Post an Event','review'=>'Review a College');
	if($fromOthersTopic == 'eventAnA' ||$fromOthersTopic == 'review' ||$fromOthersTopic == 'announcement')
	  $levelString = 'levelP';
	else
	  $levelString = 'level';
	if($fromOthersTopic == 'discussion') 
	{
	    $displayClass = "ana_blog_detail";
	    $abuseTrackingPageKeyId=$radtrackingPageKeyId;
	    $commenttrackingPageKeyId = $dctrackingPageKeyId;
	}
	else if($fromOthersTopic == 'announcement') 
	{
	    $displayClass = "ana_miked_detail";
	    $abuseTrackingPageKeyId=$raatrackingPageKeyId;
	    $commenttrackingPageKeyId = $actrackingPageKeyId;
	}
	if(is_array($catCountArray)){
	    if($fromOthersTopic == 'discussion'){
	      $postNameDisplay = "<a href='/messageBoard/MsgBoard/discussionHome/1/6/1' style='color:#707070;'>Discussions</a>";
	      $catCounDisplayString = "<a href='/messageBoard/MsgBoard/discussionHome/".$catCountArray[0]['categoryId']."/6/".$catCountArray[0]['countryId']."' style='color:#707070;'>".$questionCatCountry."</a>";
	    }
	    else if($fromOthersTopic == 'announcement'){
	      $postNameDisplay = "<a href='/messageBoard/MsgBoard/discussionHome/1/7/1' style='color:#707070;'>Announcements</a>";
	      $catCounDisplayString = "<a href='/messageBoard/MsgBoard/discussionHome/".$catCountArray[0]['categoryId']."/7/".$catCountArray[0]['countryId']."' style='color:#707070;'>".$questionCatCountry."</a>";
	    }
	}
?>
<script>
  overlayViewsArray.push(new Array('common/userCommonOverlay','userCommonOverlayForVCard'));
  overlayViewsArray.push(new Array('network/mailOverlay','mailOverlay'));
</script>

<!-- Start of Dig up and YOU image tooltip overlay -->
<div id = "digUpDownTooltip" class="blur" style="position:absolute;left:0px;z-index:1000;display:none;top:0px;">
  <div class="shadow">
    <div class="content" id="digTooltipContent"></div>
  </div>
</div>
<div id = "youTooltip" class="blur" style="width:192px;position:absolute;left:0px;z-index:1000;display:none;top:0px;">
  <div class="shadow">
    <div class="content">&nbsp;click to change your display name</div>
  </div>
</div>
<!-- End of Dig up and YOU image tooltip overlay -->

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
<input type="hidden" name="questionDetailPage" id="questionDetailPage" value="" />

<div class="wdh100" id="questionMainDiv">
    <!--Start_MainQuestion_Box-->
		<div >
			<?php
				if(!(isset($_COOKIE['entitytype']) && isset($_COOKIE['posttitle']))){
				  echo "<div style='width:100%'><div><div class='$displayClass h23 float_R' style='margin-bottom:5px'><a id='postingWidgetLink'  href='javascript:void(0);' onClick='showPostingWidget(\"$fromOthersTopic\",$qtrackingPageKeyId,$dtrackingPageKeyId,$atrackingPageKeyId);'>".$newPostArr[$fromOthersTopic]."</a><span id='postingWidgetNonLink' style='display:none;'>".$newPostArr[$fromOthersTopic]."</span></div></div></div>";
				  echo "<div class='clear_B'></div>";
				}
				//echo "<div class=' Fnt16 bld float_L' style='margin-bottom:5px'><span class='fcdGya'>".$displayArr[$fromOthersTopic].": </span>".formatQNAforQuestionDetailPage($topic_messages[0][0]['msgTxt'],32)."</div>";
				echo "<div style='margin-bottom:10px;display:none' id='postingWidget'></div>";
				
				//In case the Cookies are set, open the Post entity widget
				if(isset($_COOKIE['entitytype']) && isset($_COOKIE['posttitle'])){
					//echo "<div class='ln' style='margin-top:0'>&nbsp;</div>";
					$entityTypeCleaned = $this->security->xss_clean($_COOKIE['entitytype']);
					$entity = (isset($_COOKIE['entitytype']))?$entityTypeCleaned:'question';
					$displayData['entity'] = $entity;
					$displayData['displayHeading'] = "false";
					//conversion tracking purpose 
					$displayData['dtrackingPageKeyId']=$dtrackingPageKeyId;
					$this->load->view('common/askCafeForm',$displayData);
				}
				else
				{
					//echo "<div class='ln' style='margin-top:0'>&nbsp;</div>";
				}
			?>
			<!-- Code Start to show the username, category, country -->
			<?php
				  if($topic_messages[0][0]['listingTypeId'] > 0){
					$commentData['showCurrentStudentStatus'] = true;
					$commentData['ownerUserId'] = $topic_messages[0][0]['userId'];
				  }
				  $userProfileLink = $userProfile.$topic_messages[0][0]['displayname'];	
				  $displayName = $topic_messages[0][0]['firstname'].' '.$topic_messages[0][0]['lastname'];
				  $questionUserId = $topic_messages[0][0]['userId'];
				  $displayNameLink = '<span onmouseover="showUserCommonOverlayForCard(this,'.$questionUserId.');" ><a href="'.$userProfileLink.'" >'.$displayName.'</a></span>, '.$levelVCard[$questionUserId][$levelString];
				  $commentDisplay = ($totalNumOfRows>1)?'Comments':'Comment';
				  if($userId == $topic_messages[0][0]['userId'])
					  $displayNameLink .= '&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>&nbsp;';
				  else if($levelVCard[$questionUserId]['vcardStatus']=="1")
					  $displayNameLink = '<span onmouseover="showUserCommonOverlayForCard(this,'.$questionUserId.');" ><a href="'.$userProfileLink.'" >'.$displayName.'</a></span>, '.$levelVCard[$questionUserId][$levelString].'<br>';
			?>
			
			
			
			
			<div class="discuss-cloud-box">
	<div class="discuss-cloud-cont">
	<?php $discussionTopicText = html_entity_decode(html_entity_decode($topic_messages[0][0]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
				echo "<div class=' Fnt16 bld float_L' style='margin-bottom:5px'>".formatQNAforQuestionDetailPage($discussionTopicText,32)."</div>";
				echo "<div class='clear_B'></div>";?>
			<div style="margin-bottom:1px;">
			    <div class="Fnt11" style="margin-bottom:8px; float: left;">
				<span class="fcdGya"><?php echo $postNameDisplay;?></span> &#187; <span class="fcdGya"><?php echo $catCounDisplayString;?></span>
				<span class="ana_sprt ana_sblg ml10">&nbsp;</span><span><?php echo $totalNumOfRows." ".$commentDisplay;?></span>
			    </div>
			   
			<!-- Code End to show the username, category, country -->

			<?php //if($start==0){ ?>
			<?php if(isset($topic_messages[0][0]['creationDate'])): echo "<div class='tar fcdGya Fnt11' >Posted: ".$topic_messages[0][0]['creationDate']."</div><div class='clearFix'></div>"; endif;   ?> 
			<!--Start_MainQuestion-->
			<div class="aqAns">
			    <div class="lineSpace_10">&nbsp;</div>
			    <div class="wdh100">
				<div class="imgBx">
				      <img id="userProfileImageForComment" align='left' valign='top' src="<?php echo ($topic_messages[0][0]['userImage'] != '')?getSmallImage($topic_messages[0][0]['userImage']):getTinyImage('/public/images/photoNotAvailable.gif');?>" />
				      <?php if($commentData['showCurrentStudentStatus']){ ?><div class="current-student-patch" style="clear: both;float: left;margin-top: 5px;">Current Student</div><?php } ?>
				</div>
				<div class="cntBx">
				    <div class="wdh100 float_L">                                            
					<div class="lineSpace_20 <?php echo $displayClass; ?>">
					    <?php echo $displayNameLink; ?><br>
					    <!--<span class="forA">
						<a href="/shikshaHelp/ShikshaHelp/upsInfo"><?php echo getTheRatingStar($levelVCard[$questionUserId][$levelString]);?></a>
					    </span>-->
					    <?php
						$discussionText = html_entity_decode(html_entity_decode($topic_messages[0][0]['description'],ENT_NOQUOTES,'UTF-8'));
						echo formatQNAforQuestionDetailPage($discussionText,600);
					    ?>
					</div>
					<!--<div class="wdh100 lineSpace_20">
					    <span style="padding-left:25px">
						under <span class="fcdGya"><?php echo $postNameDisplay;?></span>
						in <span class="fcdGya"><?php echo $catCounDisplayString;?></span> <?php echo $viewsText; ?> 
						<span id="commentholder"></span>
					    </span>
					</div>                                            
					<div class="lineSpace_10">&nbsp;</div>-->
					
					<!-- Block to show the user answers -->
					<div style="display:none;margin-top:10px;margin-bottom:5px;" id="yourAnswer<?php  echo $threadId; ?>">&nbsp;
					</div>
					<!-- Block End to show the user answers -->

					<!-- Block Start for Digup, dig down -->
					<?php if($fromOthersTopic == 'review' && (!$isQuestionUser)): ?>
					<div class="wdh100" style="margin-top:10px;">
						<div class="float_L">
								<table cellspacing='0' cellpadding='0' border='0'>
								<tr>
								  <td colspan='2'>
									<span >
									  <a href="javascript:void(0);" onMouseOver = "showLikeDislike(0,'<?php echo $topic_messages[0][0]['msgId']; ?>','review');" onMouseOut = "hideLikeDislike(0,'<?php echo $topic_messages[0][0]['msgId']; ?>');" onClick="updateDig(this,'<?php echo $topic_messages[0][0]['msgId']; ?>',1,'review');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="aqIcn rUp Fnt11" style="color:#000;text-decoration:none"><?php echo $topic_messages[0][0]['digUp']; ?></a>
									  <a href="javascript:void(0);" onMouseOver = "showLikeDislike(1,'<?php echo $topic_messages[0][0]['msgId']; ?>','review');" onMouseOut = "hideLikeDislike(1,'<?php echo $topic_messages[0][0]['msgId']; ?>');" onClick="updateDig(this,'<?php echo $topic_messages[0][0]['msgId']; ?>',0,'review');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="aqIcn rDn Fnt11" style="color:#000;text-decoration:none"><?php echo $topic_messages[0][0]['digDown']; ?></a>
									</span>
								  </td>
								</tr>
								<tr>
								  <td width='50%'>
									<div id="likeDiv<?php echo $topic_messages[0][0]['msgId']; ?>" style="display:block;visibility:hidden;"></div></td><td><div id="dislikeDiv<?php echo $topic_messages[0][0]['msgId']; ?>" style="display:block;visibility:hidden;"></div>
								  </td>
								</tr>
								</table>
						</div>
						<div class="clear_B">&nbsp;</div>
					</div> 
					<div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?php  echo $topic_messages[0][0]['msgId']; ?>">&nbsp;</div>
					<?php endif; ?>
					<!-- Block End for Dig up and Dig down -->

					<div class="spacer10 clearFix"></div>

					<!-- Block Starts for Bottom navigation including Share, Report abuse -->
					<div class="fcGya flRt" style="padding-top:5px; width: 250px">
					      <!--<div class="float_L" style="width:300px">
						      
						      <div class="float_L">
						      <b class="fcblk">Share:</b>
						      <a href="javascript:void(0);" onClick="javascript:showShareThisQuestionOverlay(document.body,'<?php echo $fromOthersTopic;?>');" style="padding-right:5px"><span class="cssSprite_Icons" style="background-position:-288px -3px;padding-left:20px">&nbsp;</span>Email</a>|
						      </div>
						      <div class="addthis_toolbox addthis_default_style float_L" >
						      <a href="http://www.addthis.com/bookmark.php?v=250&amp;username=techierajan" class="addthis_button_compact">Share</a>
						      <span class="addthis_separator">|</span>
						      <a class="addthis_button_facebook"></a>
						      <a class="addthis_button_myspace"></a>
						      <a class="addthis_button_google"></a>
						      <a class="addthis_button_twitter"></a>
						      </div>
						      <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=techierajan"></script>
						      
					      </div>-->

					      <!-- Option block start for Report abuse link -->
					      <?php 
					      if(!($isQuestionUser)){
						if($topic_messages[0][0]['reportedAbuse']==0){ 
						      if(!(($isCmsUser == 1)&&($main_message['status']=='abused'))){ 
					      ?>
							<span id="abuseLink<?php echo $topic_messages[0][0]['msgId'];?>" class="float_R Fnt11"><a href="javascript:void(0);" onclick="report_abuse_overlay('<?php echo $topic_messages[0][0]['msgId'];?>','<?php echo $main_message['userId'];?>','<?php echo $threadId;?>','<?php echo $threadId;?>','<?php echo $fromOthersTopic;?>',0,0,<?=$abuseTrackingPageKeyId?>);"  style="color:#0066DD; font-size:11px">Report Abuse</a></span>
							<div class="clear_B">&nbsp;</div>					      
					      <?php }}else{ ?>
						      <span id="abuseLink<?php echo $topic_messages[0][0]['msgId'];?>" class="float_R Fnt11">Reported as inappropriate</span>
						      <div class="clear_B">&nbsp;</div>
					      <?php } 
						} ?>
						
					      <!-- Option block end for Report abuse link -->


					      <!-- Option block start for Delete/Edit/Close Question -->
					      <div class="txt_align_r">
					      
						<!--<?php if(($userGroup == 'cms') && ($editorPickFlag > 0)){ ?>
						      <div style="float:right;width:162px;cursor:pointer;margin-left:10px;">
						      <div class="dcms_remove_editorial" onClick="updateEditorialBin('<?php echo  $threadId; ?>','delete','reload');">Remove From Editorial</div>
						      </div>
						<?php } elseif(($userGroup == 'cms') && ($editorPickFlag == 0)) { ?>
						      <div style="float:right;width:135px;cursor:pointer;margin-left:10px;">
						      <div class="dcms_editorial" onClick="updateEditorialBin('<?php echo  $threadId; ?>','add','reload');">Mark As Editorial</div>
						      </div> 
						<?php } ?>-->

					      <?php 
						if(($isQuestionUser && (count($topic_messages[0]) <= 1) && ($topic_messages[0][0]['digUp']==0) && ($topic_messages[0][0]['digDown']==0) ) || ($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3) ){ 
						    $deleteEntityDisplay = array('discussion'=>'discussion post','announcement'=>'announcement','review'=>'College review','eventAnA'=>'Event');
					     ?>
						    <a  href="javascript:void(0);" onClick="showEditForm('<?php echo $topicId; ?>','<?php echo $topic_messages[0][0]['fromOthers'];?>');" style="padding-left:15px"><span class="cssSprite_Icons" style="background-position:-96px -2px;padding-left:15px">&nbsp;</span>Edit</a>
					      <?php
						    if($main_message['status']!='deleted' && $main_message['status']!='abused'){
					      ?>
						    <a href="javascript:void(0);" onClick="javascript:deleteCafeEntity('<?php echo $topic_messages[0][0]['msgId'];?>','<?php echo $topic_messages[0][0]['threadId'];?>','<?php echo $topic_messages[0][0]['userId'];?>','<?php echo $deleteEntityDisplay[$topic_messages[0][0]['fromOthers']];?>');" style="padding-left:15px"><span class="cssSprite_Icons" style="background-position:0 -2px;padding-left:15px">&nbsp;</span>Delete</a> 
					      <?php }else{ ?>
						    <span style="background-position:0 -2px;padding-left:15px;">Question Deleted</span>
					      <?php }

						}
					      ?>						
					      </div>
					      <!-- Option block End for Edit/Close Question -->

					</div>
					
					
					
					<div class="float_L" style="padding-left:30px;">
				<div class="float_L" style="margin-top:4px;margin-right:10px;"><a href="javascript:void(0);" onClick="showAnswerCommentForm('<?php echo $topic_messages[0][0]['msgId'];?>',<?=$commenttrackingPageKeyId?>);" style="font-size:14px;">Post Reply</a></div>
					
				<div class="float_L" style="margin-top:3px;margin-right:10px;">
				    <span class="st_sharethis" displayText="ShareThis"></span>
				    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
				    <script type="text/javascript">stLight.options({publisher:'f56a7074-7c1a-4390-8bdc-5f0f2a7c89f4'});</script>
				</div>
				<div class="float_L" style="width:74px; overflow:hidden;">
				    <script src="https://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like layout="button_count"></fb:like>
				</div>
				<?php if($ACLStatus['LinkDiscussion']=='live' && $fromOthersTopic=='discussion' && ($checkForDiscussionStatus['result']=='Blank')):?>
				<div class="float_L">	
				   <span>
					<a style="text-decoration:none;" href="javascript:void(0);" onclick="openSearchDiscussionOverlay('<?php echo formatQNAforQuestionDetailPage(base64_encode($discussionTopicText),32);?>','<?php echo $topicId; ?>','<?php echo $userId;?>','<?php echo $questionUserId;?>','discussion');"><img border="0" src="/public/images/link_disc.gif" />&nbsp;Link Discussion</a>	
				   <span>
				</div>
				<?php endif;?>
				<!--<div class="clear_B">&nbsp;</div>-->
			    </div>
			    <!-- AddThis Button END -->
					
					
					<!-- Block Starts for Bottom navigation including Share, Report abuse -->

					<div class="lineSpace_5">&nbsp;</div>
					<div class="lineSpace_2">&nbsp;</div>
					<div class="showMessages" style="margin-top:10px;display:none;" id="confirmMsg<?php  echo $threadId; ?>"></div>

					<!--Start_AbuseForm-->
					<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $topic_messages[0][0]['msgId'];?>">
					</div>
					<!--End_AbuseForm-->
					
 			    

				    </div>
				</div>
				<s></s>
			    </div>
			    
			    
			    
			</div>
			<!--End_MainQuestion-->
			<?php //} ?>
			  
			    <div class="clearFix"></div>
			</div>
			
			<div class="spacer10 clearFix"></div>
			</div>
			<div class="discuss-cloud-pointer"></div>

			</div>
			<div class="clearFix spacer15"></div>
			<div class="float_R Fnt11" id="topIdPage" style="margin-top:5px;">
				<span class="Fnt11" id="answerCountHolderForQuestion"><?php if($totalNumOfRows!=0) echo ($start+1)." - ".(count($topic_messages[0])-1+$start);else echo $totalNumOfRows; ?> of <?php echo $totalNumOfRows; ?></span>
				&nbsp;&nbsp;
				<span class="Fnt12"><?php echo $paginationHTML; ?></span>
			    </div>
			
			
			
			
			<div class="spacer20 clearFix"></div>

			<script>
			var userVCardObject = new Array();
			</script>

    </div>
    <!--End_MainQuestion_Box-->                                                
</div>

<!-- Code to load the Edit form in case the logged in user is the owner and no activity ahs been performed -->
<?php if(isset($topicDetailEdit)) { ?>
<div id="editCatCounOverlay" style="display:none;">
    <?php $this->load->view('messageBoard/questionEditPostCategoryForm'); ?>
</div>
<?php } ?>
<!-- Code End to load the Edit form in case the logged in user is the owner and no activity ahs been performed -->
		
<?php 
	if(!$isAskQuestion): //The condition for the Posted question started here
?>
		<!--Start_Answer_count and Pagination 1 block-->
		<?php //if($start==0){ ?>
		<div>
				<script type="text/javascript">
				var allAnswers = [];
				</script>
				<!-- If it is a single page result set then execute to pass array to the js for sorting -->
			<?php
			if($totalNumOfRows <= 20){
				$j =0;
				foreach ($topic_messages[0] as $key => $value) {
					// _p($value);
					if($key == 0)
					{
						continue;
					}
					$allAnswers[$key-1]['msgId'] = $value['msgId'];
					$allAnswers[$key-1]['upvotes'] = $value['digUp'];
?>
		<script type="text/javascript">
			allAnswers[<?=$j?>] = [<?=$value['digUp']?>,<?=$value['msgId']?>];
		</script>
<?php
				$j++;
				}
			}
			?>
			<!-- end -->
				<script>
				var filterSelected = '<?php echo $filterSel;?>'; 
				var numberOfAnswers = <?php echo $totalNumOfRows; ?>;
				var filterURL = '<?php echo $filterURL;?>';
				</script>


				<?php if($totalNumOfRows>2){?>
				<div id="ansFilterId" class="float_R"><strong>Sort by:&nbsp;&nbsp;</strong>
				      <a id="latestFilter" href="javascript:void(0);" onClick="filterDiscussion('<?php echo $threadId;?>', 'latest');" <?php if($filterSel=='latest' && $referenceEntityId=="") echo "class='linkActive'";?> >latest</a> <span>|</span>
				      <a id="oldestFilter" href="javascript:void(0);" onClick="filterDiscussion('<?php echo $threadId;?>', 'oldest');" <?php if($filterSel=='oldest' && $referenceEntityId=="") echo "class='linkActive'";?> >Oldest</a> <span>|</span>
				      <a id="upvotesFilter" href="javascript:void(0);" onClick="filterDiscussion('<?php echo $threadId;?>', 'upvotes');" <?php if($filterSel=='upvotes' && $referenceEntityId=="") echo "class='linkActive'";?> >Upvotes</a>
				</div>
				<?php } ?>


			<div style="clear: both;margin-bottom:1px;" class="dottedLineMsg">&nbsp;</div>
		</div>
		<?php //} ?>
		<!--End_Answer_count and Pagination 1 block-->

		<!--Start_Answer_Block-->
		<div>
			<div id="topicContainer">
				<div id="threadedCommentId">	
					<?php	
						$commentData['url'] = $url;
						$commentData['threadId'] = $threadId;	
						$commentData['isCmsUser'] = $isCmsUser;	
						$commentData['fromOthers'] = 'user';
						$commentData['maximumCommentAllowed'] = 4;
						$commentData['pageKeySuffixForDetail'] = 'ASK_ASKDETAIL_MIDDLEPANEL_';
						$this->load->view('messageBoard/topicPage_quesDetail_others',$commentData);  
					?>
				</div>	
			</div>
		</div>
		<!--End_Answer_Block-->

		<div class="lineSpace_10p">&nbsp;</div>

		<!--Pagination Place start here -->
		<div class="txt_align_r" id="topIdPage">
			<?php echo $paginationHTML; ?>
			<?php if(strlen($paginationHTML)>10){ ?>

			<span class="sepClr">|</span>&nbsp;&nbsp;
			<input type="text" id="pageNumber2" value="" class="inptBxSty" maxlength="3" style="width:25px;" onKeyPress="if (event.keyCode == 13){ $('pageNumber2Button').click();} return numbersonly(this, event);" ></input>&nbsp;
			<input id="pageNumber2Button" type="button" value="&nbsp;" class="ana_btn1" onClick="showEnteredPage('<?php echo ceil($totalNumOfRows/20);?>','<?php echo $paginationURL;?>','pageNumber2');"></input>
			<?php } ?>
		</div>
		<!-- Pagination Place ends here -->	

<?php if($checkForDiscussionStatus['result']=='accepted'):?>
<div class="showMessages">This discussion has been closed by the moderator because a similar discussion exist.Click on the following link to view the original discussion.</div>
<div class="lineSpace_10">&nbsp;</div>
<a href="<?php echo $getLinkedDiscussion['url']?>" class="Fnt16" title="<?php echo $getLinkedDiscussion['discussionText']?>"><img border="0" src="/public/images/grn_arrow_lr.gif" /> <b><?php echo $getLinkedDiscussion['discussionText']?></b></a>
<?php endif;?>
<!-- Block Start to show the comment form for the entity -->
<?php if($closeDiscussion == 0): ?>
<?php if($checkForDiscussionStatus['result']=='accepted'):?>

<?php else:?>

<div id="answerFormDetailPage1" style="margin-top:10px;">
    <div class="bld" style="color: #757575" >Reply to Topic</div>
    <div class="ln" style="margin-top:0">&nbsp;</div>

    <div class="aqAns" id="answerFormDetailPage">
	<div class="lineSpace_10">&nbsp;</div>
	<div class="wdh100">
	    <div class="imgBx"><img src="/public/images/ana_comnt.gif" /></div>
	    <div class="cntBx">
		<div class="wdh100 float_L">
		    <div class="pl33" style='padding-left:58px;'><img src="/public/images/upArw.png" /></div>
		    <?php
		      if(isset($topic_messages) && is_array($topic_messages)){
			  $displayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
			  $tempType = $questionList[$i]['type'];
			  $functionToCall = isset($functionToCall)?$functionToCall:'-1';
			  $showMention = ($fromOthersTopic == 'discussion')?true:false;
			  $dataArrayMain = array('showMention'=>$showMention,'type'=> $fromOthersTopic,'userId'=>$userId,'userImageURL'=>$userImageURL,'userGroup' =>$userGroup, 'threadId' =>$threadId,'ansCount' => $mainAnsCount,'detailPageUrl' =>-1,'functionToCall' => $functionToCall, 'fromOthers' => $fromOthersTopic, 'msgId' => $topic_messages[0][0]['msgId'], 'mainAnsId' => $topic_messages[0][0]['msgId'], 'dotCount'=>2 , 'displayname'=> $displayName, 'sortFlag'=>2, 'messageToShow'=>'Write a comment...', 'commenterDisplayName'=>'', 'commentParentId'=>'');
			  $inlineFormHtmlMain = $this->load->view('messageBoard/InlineForm_Homepage_Comment',$dataArrayMain,true);
			  echo "<div style='padding-left:25px;'>".$inlineFormHtmlMain."</div>";
		      }
		    ?>
		</div>
	    </div>
	    <s></s>
	</div>                                
    </div>

</div>
<?php endif; ?>			
<?php endif; ?>	
<!-- Block End to show the comment form for the entity -->


<div class="lineSpace_10">&nbsp;</div>
<?php 
	elseif($isQuestionUser && $isAskQuestion):
		$this->load->view('messageBoard/questionPosted');
	endif; //The condition for the Posted question ends here
	
 ?>


<!-- Code Start to show the Category-Country overlay when the user has logged in -->
<?php
      if(isset($_COOKIE['entitytype']))
      {
?>
	  <script>
	    var entityType = "<?php echo $entity;?>";
	    if(entityType=="discussion"){
		var questionTextValue = $('questionTextD').value;
		showCatCounOverlay(questionTextValue,'discussion'); 
	    }
	    else if(entityType=="announcement"){
		var questionTextValue = $('questionTextA').value; 
		showCatCounOverlay(questionTextValue,'announcement'); 
	    }
	    //else if(entityType=="question"){
		//var questionTextValue = $('questionText').value; 
		//showCatCounOverlay(questionTextValue,'question'); 
	    //}
	  </script>
<?php }
      setcookie  ('posttitle','',time()-3600,'/',COOKIEDOMAIN);
      setcookie  ('postdescription','',time()-3600,'/',COOKIEDOMAIN);
      setcookie  ('entitytype','',time()-3600,'/',COOKIEDOMAIN);
?>
<!-- Code End to show the Category-Country overlay when the user has logged in -->

<!-- Code to showEdit form if the user is CMSAdmin user and the hash tag for opening layer is set -->
<?php if($isCmsUser == 1){ ?>
	<script>
	if(window.location.hash.indexOf("openeditlayer")>=0){
		showEditForm('<?php echo $topicId; ?>','<?php echo $topic_messages[0][0]['fromOthers'];?>');
	}
	</script>
<?php } ?>
<!-- Code end for Showing Edit layer -->
