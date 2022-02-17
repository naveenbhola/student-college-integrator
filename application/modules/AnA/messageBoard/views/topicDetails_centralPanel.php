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
	$totalNumOfRows = $totalComments;

	$paginationHTML = doPagination($totalNumOfRows,$paginationURL,$start,$count,10);

?>
<script>
  overlayViewsArray.push(new Array('common/userCommonOverlay','userCommonOverlayForVCard'));
  overlayViewsArray.push(new Array('network/mailOverlay','mailOverlay'));
  var isShowAppBanner = 1; // using onload
</script>

<!-- Start of best answer overlay -->
<div style="padding:50px;display:none;" id="chooseBestAnswerOverLay">
	<div>
		<?php
			$urlForBestAns = site_url("messageBoard/MsgBoard/chooseBestAns");
			echo $this->ajax->form_remote_tag( array('url'=>$urlForBestAns ,'before' => 'beforeChooseBestAnswer();','success' => 'javascript:afterChooseBestAnswer(request.responseText);'));
		?>
		<div class="errorMsg mar_full_10p" id="confirmMsgForBestAnsOverLay"></div>
		<div style="padding:0 10px">
		<div class="lineSpace_10p">&nbsp;</div>
		<div><input type="radio" name="close" id="closeDiscussion1" value="1" checked /> Yes, I have found what I was looking for. Close this question</div>
		<div class="lineSpace_10p">&nbsp;</div>
		<div><input type="radio" name="close" id="closeDiscussion2" value="0" /> No, keep the question open for 3 more days. I want to receive more answers</div>
		<div class="lineSpace_10p">&nbsp;</div>
		<input type="hidden" id="msgIdForBestAnswer" name="msgId" value="-1" />
		<input type="hidden" id="threadIdForBestAnswer" name="threadId" value="-1" />
		<input type="hidden" id="commentUserIdForBestAnswer" name="commentUserId" value="-1" />

		<div align="center"><input type="Submit" value="Submit" id="chooseBestAnswerSubmit" class="orange-button" /> &nbsp;
	 	<a onclick="javascript:hideOverlay();" href="javascript:void(0);">Cancel</a></div>
		<div class="lineSpace_10p">&nbsp;</div>
		</div>
		</form>
	</div>
</div>
<!-- End of best answer overlay -->


<!-- Start of best answer with digUp overlay -->
<div style="padding:50px;display:none;" id="chooseBestAnswerWithDigUpOverLay">
	<div>
		<?php
			$urlForBestAns = site_url("messageBoard/MsgBoard/chooseBestAns");
			echo $this->ajax->form_remote_tag( array('url'=>$urlForBestAns ,'before' => 'beforeChooseBestAnswer();','success' => 'javascript:afterChooseBestAnswer(request.responseText);'));
		?>
		<div class="errorMsg mar_full_10p" id="confirmMsgForBestAnsWithDigUpOverLay"></div>
		<div style="padding:0 10px">
		<div class="lineSpace_10p">&nbsp;</div>
		<h3>Do you also want to select this answer as The Best Answer?</h3>
		<div class="lineSpace_10p">&nbsp;</div>
		<input type="hidden" id="msgIdForBestAnswerWithDigUp" name="msgId" value="-1" />
		<input type="hidden" id="threadIdForBestAnswerWithDigUp" name="threadId" value="-1" />
		<input type="hidden" id="commentUserIdForBestAnswerWithDigUp" name="commentUserId" value="-1" />
		<input type="hidden" id="closeDiscussion1" name="close" value="1"/>
		<div align="center"><button type="Submit" value="Yes" id="chooseBestAnswerSubmit" style="color:#FFFFF" class="orange-button">Yes</button> &nbsp;
                    <a href="#" onClick="javascript:hideOverlay();">No</a></div>
		<div class="lineSpace_10p">&nbsp;</div>
		</div>
		</form>
	</div>
</div>
<!-- End of best answer with digUp overlay -->

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

<?php
	//echo "<div class='Fnt14 bld lineSpace_18 float_L' style='margin-bottom:5px'><span class='fcGya'>".$displayArr[$fromOthersTopic].": </span>".formatQNAforQuestionDetailPage($topic_messages[0][0]['msgTxt'],32)."</div>";
	//In case the Cookies are set, open the Post entity widget
	/*if(isset($_COOKIE['entitytype']) && isset($_COOKIE['posttitle'])){
		//echo "<div class='ln' style='margin-top:0'>&nbsp;</div>";
		$entity = 'question';
		$displayData['entity'] = $entity;
		$displayData['displayHeading'] = "false";
		$this->load->view('common/askCafeForm',$displayData);
	}
	else
	{
		//echo "<div style='width:100%'><div class='Fnt12 lineSpace_18 float_R ana_q' style='margin-bottom:5px'><a id='postingWidgetLink' href='javascript:void(0);' class='bld' onClick='showPostingWidget(\"question\");'>Ask a Question</a><span id='postingWidgetNonLink' class='bld' style='display:none;color: #000'>Ask a Question</span></div><div class='clear_B'>&nbsp;</div></div>";
		echo "
                        <div style=\"height:42px;overflow:hidden;background:#6e9ad2;padding:0 10px\">
	                        <div class=\"float_L\"><div class=\"anaCup\">Interact with experts or simply hang out with fellow students!</div></div>
                            <div class=\"float_R\"><img src=\"/public/images/anaQ.gif\" onClick='showPostingWidget(\"question\");' class='pointer'/></div>

                            
                        </div>
";
                //echo "<div class='ln' style='margin-top:0'>&nbsp;</div>";
	}*/
  ?>
<div style="margin-bottom:10px;display:none" id="postingWidget"></div>
<div class="qna-widget" id="questionMainDiv">
	<div class="qna-header">
    	<div class="anaCup" style="font-size:15px;padding-top:0px;">
            Interact with experts or simply hang out with fellow students!&nbsp;&nbsp;
            <?php if($tab_required_course_page):?>
            <input type="button" class="orange-button" value="Ask a question" style="font-size:15px;vertical-align:middle;" onClick="window.location.hash = '#cp_ans_header'; $('questionTextQnADetails').focus();" ></input>
            <?php else:?>
            <input type="button" class="orange-button" value="Ask a question" style="font-size:15px;vertical-align:middle;" onClick="_getTabFocus($j(this),'atab1_t'); $('questionTextQnADetails').focus();" ></input>
            <?php endif;?>
        </div>
    </div>
	<!--Start_MainQuestion_Box-->
    <div class="qna-contents">
				<?php if(($isQuestionUser) && ($closeDiscussion == 0)){ ?>
				  <h4>YOUR OPEN QUESTION</h4>
				<?php } else if(($isQuestionUser) && ($closeDiscussion == 1)){ ?>
				  <h4>YOUR RESOLVED QUESTION</h4>
				<?php } else if((!($isQuestionUser)) && ($closeDiscussion == 1)){ ?>
				  <h4>QUESTION</h4> (RESOLVED)
				<?php } else { ?>
				  <h4>QUESTION</h4>
				<?php } ?>

			  <?php if($editorPickFlag > 0){ ?>
				  <div style="padding-bottom:14px">
					  <div style="width:125px;">
						  <div class="dcms_editorial_pick">Editor's Pick</div>
					  </div>
				  </div>
			  <?php } ?>
              <div class="wdh100 flLt" style="margin-bottom:5px;">
                 

                       	<div class="ques-cont">
			  <?php
                                 //////QnA Rehash Phase-2 Start code for showing edit title overlay
                                 echo "<a href=".$questionList[$i]['url']." style='color:#000000'><span class='' id='completeRelatedQuesDiv".$main_message['msgId']."' style='display:none;'>".$main_message['msgTxt']."</span></a>";
                                 //////QnA Rehash Phase-2 End code for showing eedit title overlay
                                 $questionText = html_entity_decode(html_entity_decode($main_message['msgTxt'],ENT_NOQUOTES,'UTF-8'));
								//QnA Rehash Phase-2 part-2 start    
								$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
								//$text = "The text you want to filter goes here. http://google.com";
								if(preg_match($reg_exUrl, $questionText, $url)) {
								//echo preg_replace($reg_exUrl, "<a href=".$url[0].">".$url[0]."</a> ", $text);
								$questionText = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $questionText);
								}
								echo "<h1><div id='questionId'>".formatQNAforQuestionDetailPage($questionText,32)."</div></h1>";
								//QnA Rehash Phase-2 part-2 end                                 
								  if(!empty($questionDescription)){
                                  $questionDescription = html_entity_decode(html_entity_decode($questionDescription,ENT_NOQUOTES,'UTF-8'));
                                  echo "<div class='quest-details' >".formatQNAforQuestionDetailPage($questionDescription,32)."</div>";
                                  }		
				  if($main_message['listingTitle'] != ""){
					  echo '<div style="color:#FD8103;padding-top:10px;">This question is about <a href="'.$main_message['instituteurl'].'">'.$main_message['listingTitle'].'</a></div>';
				  }
				  $userProfileLink = $userProfile.$main_message['displayname'];
				  $displayName = $main_message['firstname'].' '.$main_message['lastname'];
				  $questionUserId = $main_message['userId'];
				  $displayNameLink = '<span onmouseover="showUserCommonOverlayForCard(this,'.$questionUserId.');" ><a href="'.$userProfileLink.'" >'.$displayName.'</a></span><span class="blackFont">, '.$levelVCard[$questionUserId]['level'].'</span>';
				  if($userId == $main_message['userId'])
					  $displayNameLink .= '&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
				  else if($levelVCard[$questionUserId]['vcardStatus']=="1")
					  $displayNameLink = '<span onmouseover="showUserCommonOverlayForCard(this,'.$questionUserId.');" ><a href="'.$userProfileLink.'" >'.$displayName.'</a></span>';
			  ?>
			  <script>
			  var userVCardObject = new Array();
			  </script>
				</div>
                <?php $questionCatCountry = preg_replace('/Visual Effects/','VFX',$questionCatCountry);?>
                <div class="user-info">
                	<div class="figure"><img src="<?php echo ($main_message['userImage'] != '')?getTinyImage($main_message['userImage']):getTinyImage('/public/images/photoNotAvailable.gif');?>" onClick="window.location=('<?php echo $userProfile.$main_message['displayname']; ?>');" /></div>
              		<div class="details">By <?php echo $displayNameLink;?>
              		<!--span class="forA"><a href="/shikshaHelp/ShikshaHelp/upsInfo"><?php //echo getTheRatingStar($levelVCard[$questionUserId]['level']);?></a></span-->&nbsp; <?php if(isset($main_message['creationDate'])): echo $main_message['creationDate']; endif;   ?> in <?php echo $questionCatCountry;?> <?php echo $viewsText; ?> <span id="commentholder"></span>
					</div>
                    
                   	<!-- Option block start for Report abuse link -->
				<?php
				if(!($isQuestionUser)){
				  if($main_message['reportedAbuse']==0){
					if(!(($isCmsUser == 1)&&($main_message['status']=='abused'))){
				?>
					  <span id="abuseLink<?php echo $threadId;?>" class="report-abuse"><a href="javascript:void(0);" onclick="report_abuse_overlay('<?php echo $threadId;?>','<?php echo $main_message['userId'];?>','<?php echo $main_message['parentId'];?>','<?php echo $threadId;?>','Question',0,0,<?=$raqtrackingPageKeyId?>);"  style="color:#0066DD; font-size:12px">Report Abuse</a></span>
					  <div class="clear_B">&nbsp;</div>
				<?php }}else{ ?>
					<span id="abuseLink<?php echo $threadId;?>" class="float_R">Reported as inappropriate</span>
					<div class="clear_B">&nbsp;</div>
				<?php }
				  } ?>
				<!-- Option block end for Report abuse link -->
				</div>
       </div>
       <?php $this->load->helper('course_page_helper');?>
       <?php if((!$isQuestionUser) &&($closeDiscussion == 0)): ?>
           <div class="spacer15 clearFix">&nbsp;</div>
           <div class="ans-ques-link">
           		<a href="javascript:void(0);" onClick="<?php if(checkIfCourseTabRequired($subCatID)===TRUE) { ?>showAnswerFormNewWithCPGSHeader<?php } else { ?>showAnswerFormNew<?php }?>('<?php if($alreadyAnswer==0){echo $threadId; }else {echo 0;}?>',<?=$anstrackingPageKeyId?>);">Answer this question</a>
                
                <!--QnA Rehash Phase-2 part-2 start -->
                                        <?php if($ACLStatus['LinkQuestion']=='live' && count($linkQuestionViewCount->link)<10){?>
                                        
                                                 | <a href="javascript:void(0);" onclick="openSearchQuestionOverlay(document.getElementById('questionId').innerHTML,<?php echo $main_message['msgId']; ?>,<?php echo $userId; ?>,<?php echo $main_message['userId'];?>,'Question');trackEventByGA('SEARCH_RESULTS','SHIKSHA_LINK_QUESTIONS_SOURCE_<?php echo $typeOfSearch ;?>_SEARCH');getQuestionDataFromGoogleAjax(document.getElementById('questionId').innerHTML, document.getElementById('mainQuestionIdPU').innerHTML);">Link Question</a>
                                           
                                        <?php } ?>
                                       <!--////QnA Rehash Phase-2 end code for showing edit title overlay-->
					<!--<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=techierajan"></script>-->
					<!-- AddThis Button END -->
           </div>
           <?php endif;?>  
           <div class="spacer5 clearFix"></div>
           <div class="flRt">
           	
					<b class="fcblk flLt" style="padding-top:2px">Share: &nbsp;</b>
					<!--<a href="javascript:void(0);" onClick="javascript:showShareThisQuestionOverlay();" style="padding-right:5px"><span class="cssSprite_Icons" style="background-position:-288px -3px;padding-left:20px">&nbsp;</span>Email</a>|-->
					<!--<fb:like send="false" layout="button_count" width="30" show_faces="false"></fb:like>&nbsp;&nbsp; 
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
					
					</div>-->
<?php
    $url_parts = parse_url("https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
    $constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
    $urlAnA = urlencode($constructed_url);
?>

					<div class="flLt"><iframe src="https://www.facebook.com/plugins/like.php?href=<?php echo $urlAnA; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>" colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:75px; height:20px"></iframe>
                    </div>
					<!-- AddThis Button BEGIN -->
					<!--<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=techierajan" class="addthis_button_compact">Share</a>-->
					<!--<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo urlencode("https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]); ?>" data-via="#">Tweet</a>			
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>-->
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
					<!--<span class="addthis_separator">|</span>
					<a class="addthis_button_facebook"></a>
					<a class="addthis_button_myspace"></a>
					<a class="addthis_button_google"></a>
					<a class="addthis_button_twitter"></a>-->
					</div>
           
           </div>
           
			  
			  <!-- Block to show the user answers -->
			  <div style="display:none;margin-top:10px;margin-bottom:5px; clear:both" id="yourAnswer<?php  echo $threadId; ?>">&nbsp;
			  </div>
			  <!-- Block End to show the user answers -->


			  <!--<div class="lineSpace_10">&nbsp;</div>-->


			  <!-- Block Starts for Bottom navigation including Share, Report abuse -->

				<div class="ans-ques-link">
                <div class="spacer10 clearFix"></div>
					<!--QnA Rehash Phase-2 Start code for showing edit title overlay-->
                                        <?php if((($reputationPoints>25 && $reputationPoints!=9999999 && $showEditLink=='true')  || ($userGroup=='cms' && $showEditLink=='true')) || ( $main_message['userId'] ==$userId && $showEditLink=='true' )&& $userId >0){?>
                                        <div class="float_L"><a href="javascript:void(0);" onclick="showTitleDiv('completeRelatedQuesDiv<?php echo $main_message['msgId'];?>',<?php echo $main_message['msgId']; ?>,<?php echo $userId; ?>,<?php echo $main_message['userId'];?>)">Add Title</a>
                                        </div>
                                        <?php }?>
                                        
                                        <!-- Option block start for Delete/Edit/Close Question -->


				  <?php if(($userGroup == 'cms') && ($editorPickFlag > 0)){ ?>
					<div style="float:right;width:162px;cursor:pointer;margin-left:10px;">
					<div class="dcms_remove_editorial" onClick="updateEditorialBin('<?php echo  $threadId; ?>','delete','reload');">Remove From Editorial</div>
					</div>
				  <?php } else if(($userGroup == 'cms') && ($editorPickFlag == 0)) { ?>
					<div style="float:right;width:135px;cursor:pointer;margin-left:10px;">
					<div class="dcms_editorial" onClick="updateEditorialBin('<?php echo  $threadId; ?>','add','reload');">Mark As Editorial</div>
					</div>
				  <?php } ?>

				<?php
				  if(($isQuestionUser && ($answerCountForQuestion <= 0) && ($closeDiscussion == 0)) ||($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3)){
					if($main_message['status']!='deleted' && $main_message['status']!='abused'){
				?>
					<a href="javascript:void(0);" onClick="javascript:deleteCafeEntity('<?php echo $topicId; ?>',<?php echo $topicId; ?>,<?php echo $main_message['userId'];?>,'question');" style="padding-left:15px"><span class="cssSprite_Icons" style="background-position:2px -2px;padding-left:15px">&nbsp;</span>Delete</a>
				<?php }else{ ?>
					<span style="background-position:0 -2px;padding-left:15px;">Question Deleted</span>
				<?php }}

				  if(($isQuestionUser) ||($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3) || $caFlag=='true'){
					if((count($topic_messages) == 0) && ($closeDiscussion == 0) || ($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3) || ($caFlag=='true') ){
				?>
					  <a href="javascript:void(0);" onClick="showEditForm('<?php echo $threadId; ?>','question');" style="padding-left:15px"><span class="cssSprite_Icons" style="background-position:-96px -2px;padding-left:15px">&nbsp;</span>Edit</a>
				<?php
					}
				  }

				  if(($isQuestionUser) ||($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3)){
					if($closeDiscussion != 1){
				?>
					  <a href="javascript:void(0);" onClick="javascript:operateOnDiscussion('<?php echo $topicId; ?>','closeDiscussionTopic');" style="padding-left:15px"><span class="cssSprite_Icons" style="background-position:-18px -2px;padding-left:15px">&nbsp;</span>Close</a>
				<?php
					}else{ ?>
					  <span class="cssSprite_Icons" style="background-position:-18px -2px;padding-left:20px">&nbsp;</span>Closed Question
				<?php 	}}
				?>


				<!-- Option block End for Edit/Close Question -->
                                        
                                        
										
				</div>

				


				


			
			<!-- Block Starts for Bottom navigation including Share, Report abuse -->

			<div class="spacer5 clearFix"></div>
			<div class="showMessages" style="margin-top:10px;display:none;" id="confirmMsg<?php  echo $threadId; ?>"></div>

			<!--Start_AbuseForm-->
			<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $threadId;?>">
			</div>
			<!--End_AbuseForm-->

		</div>
	<!--End_MainQuestion_Box-->
</div>
<div class="spacer20 clearFix" id="cp_ans_header"></div>
<!-- Code to load the Edit form in case the logged in user is the owner and no activity ahs been performed -->
<?php if(isset($topicDetailEdit)) { 
$data['isCmsUser'] = $isCmsUser;

?>
<div id="editCatCounOverlay" style="display:none;">
    <?php $this->load->view('messageBoard/questionEditPostCategoryForm',$data); ?>
</div>
<?php } ?>
<!-- Code End to load the Edit form in case the logged in user is the owner and no activity ahs been performed -->

<?php
	if(!$isAskQuestion): //The condition for the Posted question started here
?>
		<!--Start_Answer_count and Pagination 1 block-->
			
            	<div class="ans-header">
				<h4>ANSWERS <strong>(<span id="answerCountHolderForQuestion"><?php echo $totalComments; ?></span>)</strong></h4>
				<script>var filterSelected = '<?php echo $filterSel;?>'; var numberOfAnswers = <?php echo $totalComments; ?>;</script>

				<?php if($paginationHTML!=''){ ?>
				<div style="margin-left:80px;" class="pagingID mar_full_10p lineSpace_22 float_R" id="paginataionPlace1"><?php echo $paginationHTML;  ?></div>

				<?php } ?>
				<?php if($totalComments>1){?>
				<div id="ansFilterId" class="float_R"><strong>Sort by:&nbsp;&nbsp;</strong>
				      <a id="upvotesFilter" href="javascript:void(0);" onClick="filterAnswers('<?php echo $threadId;?>', 'upvotes');" <?php if($filterSel=='upvotes' && $referenceEntityId =='') echo "class='linkActive'";?> >Upvotes</a><span>|</span>
				      <a id="latestFilter" href="javascript:void(0);" onClick="filterAnswers('<?php echo $threadId;?>', 'latest');" <?php if($filterSel=='latest' && $referenceEntityId =='') echo "class='linkActive'";?> >Latest</a><span>|</span>
				      <a id="oldestFilter" href="javascript:void(0);" onClick="filterAnswers('<?php echo $threadId;?>', 'oldest');" <?php if($filterSel=='oldest' && $referenceEntityId =='') echo "class='linkActive'";?> >Oldest</a> 
				</div>
				<?php } ?>

				<div class="arrow-cloud">&nbsp;</div>
                <div class="clearFix"></div>
			</div>
            
		<!--End_Answer_count and Pagination 1 block-->
		<!--Start_Answer_Block-->
			<div id="topicContainer">
					<div id="threadedCommentId">
						<?php
							$commentData['url'] = $url;
							$commentData['threadId'] = $threadId;
							$commentData['isCmsUser'] = $isCmsUser;
							$commentData['fromOthers'] = 'user';
							$commentData['maximumCommentAllowed'] = 4;
							$commentData['pageKeySuffixForDetail'] = 'ASK_ASKDETAIL_MIDDLEPANEL_';
							$commentData['subCatID']=$subCatID;
							$this->load->view('messageBoard/topicPage_quesDetail',$commentData);
						?>
				</div>
			</div>
		<!--End_Answer_Block-->

			<div class="spacer10 clearFix">&nbsp;</div>

		<!--Pagination Place start here -->
			
			<div class="pagingID mar_full_10p lineSpace_22 txt_align_r" id="paginataionPlace2"><?php echo $paginationHTML; ?></div>
		<!-- Pagination Place ends here -->

<?php 
	elseif($isQuestionUser && $isAskQuestion):
		$this->load->view('messageBoard/questionPosted');
	endif; //The condition for the Posted question ends here

 ?>

  <!--Start_ReplyBox-->
                <div id="answerFormDetailPage">
                <?php if($alreadyAnswer != 0): ?>
                    <div class="showMessages" style="margin-top:5px;margin-bottom:5px;">&nbsp;You have already answered this question. We request you to rate the other answers received for this question.</div>
                <?php elseif((!$isQuestionUser) &&($closeDiscussion == 1)): ?>
                    <div class="showMessages" style="margin-top:5px;margin-bottom:5px;">&nbsp;This question is closed for Answering.</div>
                <?php elseif((!$isQuestionUser) &&($closeDiscussion == 0)): ?>

                <?php
                    $dataArray = array('userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => $mainAnsCount,'loggedUserImageURL' => $userImageURL,'userId' => $userId,'detailPageUrl' =>-1,'callBackFunction' => 'try{ addMainCommentForQues('.$threadId.',request.responseText,\'-1\',false,true,\'\',\'\',false,\''.$userImageURL.'\',0,false,true); } catch (e) {}');
                    $this->load->view('messageBoard/InlineForm_Answer',$dataArray);
                ?>

                <!--<div class="lineSpace_10">&nbsp;</div>-->
                <?php /*echo "<div style='width:100%'><div class='Fnt12 lineSpace_18 float_R ana_q' style='margin-bottom:5px'><a id='postingWidgetLink' href='javascript:void(0);' class='bld' onClick='showPostingWidget(\"question\");'>Ask a Question</a><span id='postingWidgetNonLink' class='bld' style='display:none;color: #000'>Ask a Question</span></div><div class='clear_B'>&nbsp;</div></div>"; */?>
                <?php endif; ?>
                </div>
                <!--End_ReplyBox-->
                <div class="spacer10 clearFix"></div>
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
		showEditForm('<?php echo $threadId; ?>','question');
	}
	</script>
<?php } ?>
<!-- Code end for Showing Edit layer -->
