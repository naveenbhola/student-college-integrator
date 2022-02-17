<?php
//echo "<pre>";var_dump($topic_replies); echo "</pre>";

$userImageURLDisplay = isset($validateuser[0]['avtarurl'])?$validateuser[0]['avtarurl']:'/public/images/photoNotAvailable.gif';
$displayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
$userGroup = isset($validateuser[0]['usergroup'])?$validateuser[0]['usergroup']:'normal';
if($userGroup=='cms'){ $userGroup = 'normal';}
if(($topic_messages)&&(count($topic_messages)>0)){ 
$userProfile = site_url('getUserProfile').'/';

?>
<div>

	<?php if(!(isset($ajaxCall))){ ?>
	<!-- Start: Display the Top bar for Discussion board with View all link, Write comment link -->
	<div class="show-option">
					<div class="flLt">
						  Showing 1-<span id="commentCount_total"><?php echo count($topic_messages);?></span> of total <?php echo "<span id='commentCountHolder4'>".$commentCountForTopic."</span> ";if($commentCountForTopic>1) echo "comments";else echo "comment";?>
					</div>
					<div class="flRt">
						<?php if($commentCountForTopic>10){ ?>
						<div class="flLt" id="viewAllDiv<?php echo $topic_messages[0][0]['threadId'];?>">
							  <span style="display:none" id="allImage<?php echo $topic_messages[0][0]['threadId'];?>"><img src="/public/images/working.gif" align="absmiddle"/></span>&nbsp;<a href="javascript:void(0);"  style="font-size:14px" onClick="showMyArticleComments('<?php echo $topic_messages[0][0]['threadId'];?>','<?php echo $commentCountForTopic;?>','<?php echo $articleId; ?>','<?php echo $entityTypeShown; ?>','<?php echo $commentCountForTopic;?>')">View all <?php echo "<span id='commentCountHolder1'>".$commentCountForTopic."</span> comments";?></a>
							  <?php if($closeDiscussion==0 && $userGroup!='cms'){ echo "<span style='color:#c5c5c5;font-size:12px;'>&nbsp;|&nbsp;</span>"; } ?>
						</div>
                                        	<?php } ?>
					        <?php if($closeDiscussion==0 && $userGroup!='cms'){ ?>
					        <div class="flRt">
						          <a href="javascript:void(0);" onclick="try{ showEntityCommentForm('<?php echo $topic_messages[0][0]['threadId']; ?>','<?=$trackingPageKeyId?>'); }catch (e){}">Write a Comment</a>
				        	</div>
				        	<?php } ?>
					</div>
		
			<s>&nbsp;</s>
	</div>
	<div id="showMoreComments"></div>
	<input type="hidden" name="start" id="start" value="10"/>
	<!-- End: Display the Top bar for Discussion board with View all link, Write comment link -->
	<?php } ?>

	<div id="commentDiv<?php echo $articleId;?>">
	<?php  for($x=(count($topic_messages)-1);$x>=0;$x--){
			$answerId = $topic_messages[$x][0]['msgId'];
			//echo $answerId;
	?>
    <div class="comment-wrapp">
    <div class="comment-details">
		<div class="fbkBx w6">
			<div>
				<div class="flLt wdh100">
					<?php if($topic_messages[$x][0]['status']!='abused'){ ?>
						<div class="imgBx">
							<img src="<?php if($topic_messages[$x][0]['userImage']=='') echo getSmallImage("/public/images/photoNotAvailable.gif"); else echo getSmallImage($topic_messages[$x][0]['userImage']); ?>" style="cursor:pointer;" onClick="window.location=('<?php echo $userProfile.$topic_messages[$x][0]['displayname']; ?>');"/>
						</div>
						<div class="cntBx">
							<div class="wdh100 flLt">
								<div>
                                <div class='cmttxt'>
								  
                                  	
									  <span><strong><a href="<?php echo $userProfile.$topic_messages[$x][0]['displayname']; ?>">
									  <?php echo $topic_messages[$x][0]['displayname']; ?></a></strong></span>
									  <?php if($topic_messages[$x][0]['userId']==$userId){ ?>
										<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"><img src="/public/images/fU.png" /></span>
									  <?php } ?>
                                      <span class="fcdGya" style="font-size:12px">
											<?php echo $topic_messages[$x][0]['creationDate'];?>
										</span>
									  
								  	<div class="clearFix"></div>
									<p>
                                        <?php
                                            $text = html_entity_decode(html_entity_decode($topic_messages[$x][0]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
                                            echo formatQNAforQuestionDetailPage($text,500);
                                        ?>
                                    </p>
								</div>
                                    
                                </div>
                                <div class="clearFix"></div>
								<div class="flRt" >
									<?php if($topic_messages[$x][0]['userId']!=$userId) { if($topic_messages[$x][0]['reportedAbuse']==0){ 
									  if(!(($isCmsUser == 1)&&($topic_messages[$x][0]['status']=='abused'))){ 
									?>
									<span style="font-size:12px" id="abuseLink<?php echo $topic_messages[$x][0]['msgId'];?>"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $topic_messages[$x][0]['msgId']; ?>','<?php echo $topic_messages[$x][0]['userId'];?>','<?php echo $topic_messages[$x][0]['parentId'];?>','<?php echo $comments[$x][0]['threadId']; ?>','<?php echo $entityTypeShown; ?>','<?php echo $eventId; ?>','<?php echo $articleId; ?>','<?php echo $ratrackingPageKeyId;?>');return false;">Report Abuse</a></span>
									<?php }}else{ ?>
									<span id="abuseLink<?php echo $topic_messages[$x][0]['msgId'];?>">Reported as inappropriate</span>
									<?php }} ?>
								</div>
								<div style="line-height:1px;clear:both">&nbsp;</div>
							</div>
						</div>
						<!--Start_AbuseForm-->
						<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $topic_messages[$x][0]['msgId'];?>">
						</div>
						<!--End_AbuseForm-->
					<?php }else{ ?>
						<div class="wdh100 flLt">
							<div style="padding-top:2px;">
								This entity has been removed on account of report abuse.
							</div>
						</div>
					<?php } ?>
				</div>
				<s>&nbsp;</s>
			</div>
		</div>
		<?php if(!($userGroup=='cms' && count($topic_replies[$answerId])==0)){ ?>
		<?php } ?>

		<!-- View All Comment Div Start -->
		<?php if(count($topic_replies[$answerId])>10){ ?>
		<div class="fbkBx w49" id="viewComments<?php echo $answerId; ?>">
		  <a href="javascript:void(0)" onClick="javascript:showRepliesDiv(this,'<?php echo $answerId; ?>');return false;" class="fbxVw">View All <span id="replyAnswerCount<?php echo $answerId;?>"><?php echo count($topic_replies[$answerId]);?></span> Replies</a>
		</div>
		<?php } ?>
		<!-- View All Comment Div End -->
		<div id="<?php echo 'repliesContainer'.$answerId; ?>" style="display:block;">
		  <?php  for($y=(count($topic_replies[$answerId])-1);$y>=0;$y--){ 
			  $commentId = $topic_replies[$answerId][$y][0]['msgId'];
		  ?>
			  <div id="completeMsgContent<?php echo $commentId;?>" class="fbkBx" style="margin-left:96px;width:490px;display:<?php if($y>=10)echo 'none';else echo '';?>;">
				  <div>
					  <div class="flLt wdh100">
						  <?php if($topic_replies[$answerId][$y][0]['status']!='abused'){ ?>
							  <div class="imgBx">
								  <img src="<?php if($topic_replies[$answerId][$y][0]['userImage']=='') echo getTinyImage("/public/images/photoNotAvailable.gif"); else echo getTinyImage($topic_replies[$answerId][$y][0]['userImage']); ?>" style="cursor:pointer;" onClick="window.location=('<?php echo $userProfile.$topic_replies[$answerId][$y][0]['displayname']; ?>');"/>
							  </div>
							  <div class="cntBx">
								  <div class="wdh100 flLt">
									  <div class="">
										<span>
											<span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $topic_replies[$answerId][$y][0]['userId']; ?>');" ><strong><a href="<?php echo $userProfile.$topic_replies[$answerId][$y][0]['displayname']; ?>">
											<?php echo $topic_replies[$answerId][$y][0]['displayname']; ?></a></strong></span>
											<?php if($topic_replies[$answerId][$y][0]['userId']==$userId){ ?>
											  <span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"><img src="/public/images/fU.png" /></span>
											<?php } ?>
                                            <?php
                                                  $text = html_entity_decode(html_entity_decode($topic_replies[$answerId][$y][0]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
                                                  echo formatQNAforQuestionDetailPage($text,500);
                                            ?>
										</span>
									  </div>
									  <div class="fcdGya flLt" style="font-size:12px">
										  <?php echo $topic_replies[$answerId][$y][0]['creationDate'];?>
									  </div>
									  <div class="flRt" >
										  <?php if($topic_replies[$answerId][$y][0]['userId']!=$userId) { if($topic_replies[$answerId][$y][0]['reportedAbuse']==0){ 
											if(!(($isCmsUser == 1)&&($topic_replies[$answerId][$y][0]['status']=='abused'))){
										  ?>
										  <span style="font-size:12px" id="abuseLink<?php echo $topic_replies[$answerId][$y][0]['msgId'];?>"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $topic_replies[$answerId][$y][0]['msgId']; ?>','<?php echo $topic_replies[$answerId][$y][0]['userId'];?>','<?php echo $topic_replies[$answerId][$y][0]['parentId'];?>','<?php echo $comments[$x][0]['threadId']; ?>','<?php echo $entityTypeShown; ?>','<?php echo $eventId; ?>','<?php echo $articleId; ?>','<?php echo $ratrackingPageKeyId;?>');return false;">Report Abuse</a></span>
										  <?php }}else{ ?>
										  <span id="abuseLink<?php echo $topic_replies[$answerId][$y][0]['msgId'];?>">Reported as inappropriate</span>
										  <?php }} ?>
									  </div>
									  <div style="line-height:1px;clear:both">&nbsp;</div>
								  </div>
							  </div>
							  <!--Start_AbuseForm-->
							  <div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $topic_replies[$answerId][$y][0]['msgId'];?>">
							  </div>
							  <!--End_AbuseForm-->
						  <?php }else{ ?>
							  <div class="wdh100 flLt">
								  <div style="padding-top:2px;">
									  This entity has been removed on account of report abuse.
								  </div>
							  </div>
						  <?php } ?>
					  </div>
					  <s>&nbsp;</s>
				  </div>
			  </div>
			  
		  <?php } ?>
		</div>
	<?php
		$functionToCall = isset($functionToCall)?$functionToCall:'-1';
		$dataArray = array('userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$topic_messages[0][0]['threadId'],'ansCount' => $commentCountForTopic,'detailPageUrl' =>'','functionToCall' => '-1', 'fromOthers' => 'blog', 'msgId' => $answerId, 'mainAnsId' => $answerId, 'dotCount'=>2 , 'displayname'=> $displayName, 'sortFlag'=>2, 'messageToShow'=>'Reply...','rtrackingPageKeyId'=>$rtrackingPageKeyId);
		$inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage_Comment',$dataArray,true);
		
	?>
	<div id="replyPlace<?php echo $answerId;  ?>" style="margin-left:96px;"></div>
	<div style="width:490px;margin-left:96px;">
	  <?php echo $inlineFormHtml; ?>
	</div>
	<div class="clearFix"></div>
    </div>
    <div class="comment-pointer">&nbsp;</div>
	</div>
	<?php } ?>
	</div>

	<!-- Div Start to display newly added comments -->
	<div id="addCommentDiv"></div>
	<!-- Div End to display newly added comments -->
	<input type="hidden" id="pageKeyForSubmitComment" value="ARTICLE_COMMENT_MIDDLEPANEL_SUBMITANSWER" />

	<?php if(!(isset($ajaxCall))){ ?>
	<div class="show-option">
					<?php if($commentCountForTopic>10){ ?>
					<div class="flRt" id="viewAllDiv1<?php echo $topic_messages[0][0]['threadId'];?>">
					  <span style="display:none" id="allImage1<?php echo $topic_messages[0][0]['threadId'];?>"><img src="/public/images/working.gif" align="absmiddle"/></span>&nbsp;<?php if($commentCountForTopic>20){ ?><a href="javascript:void(0);" style="font-size:14px" onClick="showMyArticleComments('<?php echo $topic_messages[0][0]['threadId'];?>','<?php echo $commentCountForTopic;?>','<?php echo $articleId; ?>','<?php echo $entityTypeShown; ?>','<?php echo $commentCountForTopic;?>')">View all <?php echo "<span id='commentCountHolder2'>".$commentCountForTopic."</span> comments";?></a><?php } ?>
					</div>
					<?php } ?>
			<s>&nbsp;</s>
	</div>
	<?php } ?>

</div>
<?php }else{ ?>
	<!-- Div Start to display newly added comments -->
	<div id="addCommentDiv"></div>
	<!-- Div End to display newly added comments -->
<?php
  }
 ?>
<?php 	if(!(isset($ajaxCall))){ ?>
<input type="hidden" id="pageKeyForReportAbuse" value="ARTICLE_COMMENT_MIDDLEPANEL_REPORTABUSE" />
<input type="hidden" id="pageKeyForSubmitComment" value="ARTICLE_COMMENT_MIDDLEPANEL_SUBMITANSWER" />
<input type="hidden" id="showUpdateUserNameImage" value="1"/>
<input type="hidden" id="questionDetailPage" value="1"/>
<?php } ?>

<!-- Div Start to show the Add comment form -->
<?php 	if((!(isset($ajaxCall)))&&($closeDiscussion==0)&&($userGroup!='cms')){
		  $dataArray = array('userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => $commentCountForTopic,'detailPageUrl' =>'','callBackFunction' => 'addMainCommentForQues('.$threadId.',request.responseText,\'-1\',true,true,\'\',\'\',false,\''.$userImageURLDisplay.'\',\'0\',true);');
		  $inlineFormHtml = $this->load->view('messageBoard/InlineForm_Entity_Comment',$dataArray,true);
?>
	<div style="margin-top:25px; font-size:16px;"><strong>Write a Comment</strong></div>
	<div class="fbkBx" style="width:622px;margin-bottom:10px;">
		<div>
			<div class="flLt wdh100">
				<?php echo $inlineFormHtml; ?>
			</div>
			<s>&nbsp;</s>
		</div>
	</div>
<?php
		}
?>
<!-- Div End to show the Add comment form -->

