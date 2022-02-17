	
	<?php	foreach ($data['childDetails'] as $key => $childData){
				$userProfileURL = getSeoUrl($childData['userId'],'userprofile');
				if($entityType == 'question'){
					$childType = 'Comment';
					$reportAbuseEntityType = 'comment';
					$GA_currentPage 		= 'ANSWERCOMMENT';
					$GA_Tap_on_Owner 		= 'PROFILE_ANSCOMMENTPAGE_DESKAnA';
					$GA_Tap_On_Add			= 'ADD_ANSCOMMENTPAGE_DESKAnA';
					$GA_Tap_On_Post			= 'POST_ANSCOMMENTPAGE_DESKAnA';
					$GA_Tap_On_Abuse		= 'REPORTABUSE_ANSCOMMENTPAGE_DESKAnA';
					$GA_Tap_On_Edit			= 'EDIT_ANSCOMMENTPAGE_DESKAnA';
					$GA_Tap_On_DEL			= 'DELETE_ANSCOMMENTPAGE_DESKAnA';
				}else{
					$childType = 'Reply';
					$reportAbuseEntityType = 'discussionReply';
					$GA_currentPage 		= 'COMMENTREPLY';
					$GA_Tap_on_Owner 		= 'PROFILE_COMMENTREPLYPAGE_DESKAnA';
					$GA_Tap_On_Add			= 'ADD_COMMENTREPLYPAGE_DESKAnA';
					$GA_Tap_On_Post			= 'POST_COMMENTREPLYPAGE_DESKAnA';
					$GA_Tap_On_Abuse		= 'REPORTABUSE_COMMENTREPLYPAGE_DESKAnA';
					$GA_Tap_On_Edit			= 'EDIT_COMMENTREPLYPAGE_DESKAnA';
					$GA_Tap_On_DEL			= 'DELETE_COMMENTREPLYPAGE_DESKAnA';
				}
	?>
				<div class="c-block" style="display: none;">
					<?php	if($startIndex == 0 && $key == 0){?>
								<p class="classCommentReplyCountShown" id="CommentReplyCount_<?=$childData['parentId']?>" style="margin:0px 0 20px;color: #5a595c;font-size: 12px;display: none;"></p>
					<?php	}?>
					<a class="new-avatar" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_on_Owner;?>','<?php echo $GA_userLevel;?>');window.open('<?= $userProfileURL?>','_top')">
					<?php	if($childData['picUrl'] != ''){?>
								<img src="<?php echo $childData['picUrl'];?>">
					<?php 	}else {
								echo ucfirst(substr($childData['firstname'], 0,1));
							}
					?>
				    </a>
				    <div class="c-inf">
					    <span class="time"><?php echo $childData['formattedTime']?></span>
					    <a href="<?=$userProfileURL?>" class="avatar-name" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_on_Owner;?>','<?php echo $GA_userLevel;?>');"><?php echo ucfirst($childData['firstname']).' '.ucfirst($childData['lastname'])?><span class=""><?php echo $childData['aboutMe'];?></span></a>
					    <p class="g-l"><a href="<?php echo SHIKSHA_HOME;?>/shikshaHelp/ShikshaHelp/upsInfo" class="lvl-name"><?=$childData['levelName']?></a></p>
					    <div class="r-txt">
					    	<?php	$textLength = strlen(strip_tags($childData['msgTxt']));
					    			if($textLength > 650){
					    				$con = substr(strip_tags($childData['msgTxt']),0,650).'...';
					    	?>
					    			<div id="lessAnswer_<?=$childData['msgId']?>"><?=$con;?>
					    				<a href="javascript:void(0);" class="link" id="viewMoreBtn_<?=$childData['msgId']?>" onclick="viewFullAnswerText('<?=$childData['msgId']?>')">view more</a>
					    	<?php	}else{
					    	?>
					    				<div id="entityTxt_<?=$childData['msgId']?>" class="trfrm-lnk"><?php echo $childData['msgTxt'];?></div>
					    	<?php	}
					    	  $formattedMsg = preg_replace('#<br\s*/?>#i', "\n", $childData['msgTxt']);
					    	?>

					    			<p id="answerMsgTxt_<?=$childData['msgId'];?>" style = "display:none;"><?=$formattedMsg;?></p>
									</div>

							<?php if($textLength > 650){ ?>
 	                                 <div id="answerfullTxt_<?=$childData['msgId']?>" style = "display:none;"><?=$childData['msgTxt'];?></div> 
	                            <?php } ?>
							<!-- Comment Box Starts -->
		                     <form id="postEntityAnA_<?=$childData['msgId']?>" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="postAnswerAnA">
		                       <div class="ans-block  new__ans__block" id="entityPostingCol_<?=$childData['msgId']?>" style="display:none;">
		                          <p class="txt-count" style="display:block;" id="entityTxtCounter<?=$childData['msgId']?>">Characters <span id="entity_text_<?=$childData['msgId']?>_counter">0</span>/2500</p>
		                          <textarea placeholder="Write your <?=$childType;?>. Feel free to share your opinion and experience, the community will appreciate it." onkeypress="handleCharacterInTextField(event,true);" onkeyup="autoGrowField(this,300);textKey(this)" validate="validateStr" minlength=15 maxlength=2500 caption="Answer" id="entity_text_<?=$childData['msgId']?>" required="true" onpaste="handlePastedTextInTextField('entity_text_<?=$childData['msgId']?>',true);"></textarea>
		                          <div class="btns-col">
		                             <span class="right-box">
		                                  <a class="exit-btn" id="cancelBtn<?=$childData['msgId']?>" href="javascript:void(0);" onclick = "hideCommentReplyBox(this,'<?=$childData['msgId']?>')">Cancel</a>
		                                  <a class="prime-btn" id= "postBtn<?=$childData['msgId']?>" href="javascript:void(0);" id="entityPostingButton<?=$childData['msgId']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Post;?>','<?php echo $GA_userLevel;?>');if(!validateCommentAnswerPostingField('entity_text_<?=$childData['msgId']?>','postEntityAnA_<?=$childData['msgId']?>')){return false;}else{initializeCommentPostingAnA('<?=$childData['parentId']?>','<?=$childData['msgId']?>');}">Post</a>
		                              </span>
		                              <p class="clr"></p>
		                          </div>
		                          <input type="hidden" id="threadId_<?=$childData['msgId']?>" value="<?=$childData['threadId']?>" />
		                          <input type="hidden" id="editEntityId<?=$childData['msgId']?>" value="0" />
		                          <input type="hidden" id="parentType<?=$childData['msgId']?>" value="<?=$entityType?>" />
		                          <input type="hidden" id="parentId_<?=$childData['msgId']?>" value="<?=$childData['parentId']?>" />
		                          <input type="hidden" id="entityType<?=$childData['msgId']?>" value="<?=$childType?>" />
		                          <input type="hidden" id="actionOnAns_<?=$childData['msgId']?>" value="add" />
		                       </div>
		                       <div>
		                           <p class="err0r-msg"  id="entity_text_<?=$childData['msgId']?>_error"></p>
		                        </div>
		                   </form>
					         <!-- Comment Box Ends -->
							<div class="opinion-col qdp-ul" id="overflowOptions_<?=$childData['msgId']?>">
								<span>
									<?php	foreach ($childData['overflowTabs'] as $id => $value){?>
												<?php if($value['label'] == 'Delete'){?>
													<a id="closeDeleteEntity" href="javascript:void(0);" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_DEL;?>','<?php echo $GA_userLevel;?>');showConfirmMessage('<?=$childData['msgId'];?>','<?=$childData['threadId'];?>','<?=$childData['parentId']?>','<?=$childType;?>','<?=$value['label'];?>','layer')" class="up-txt" overFlowActionValue="<?php echo $value['id'];?>"><?php echo $value['label'];?></a>
													<?php } elseif($value['label'] == 'Report Abuse'){?>
														<a id="closeDeleteEntity" href="javascript:void(0);" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Abuse;?>','<?php echo $GA_userLevel ;?>');fetchReportAbuseLayer('<?php echo $childData['msgId'];?>','<?php echo $childData['threadId']?>','<?php echo $reportAbuseEntityType;?>','<?php echo $raTrackingPageKeyId;?>')" class="up-txt" overFlowActionValue="<?php echo $value['id'];?>"><?php echo $value['label'];?></a>
												<?php } elseif($value['label'] == 'Edit'){?>
														<a id="closeDeleteEntity" href="javascript:void(0);" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Edit;?>','<?php echo $GA_userLevel ;?>');makeCommentPostingLayer('<?=$childData['parentId']?>','<?=$childData['msgId']?>')" class="up-txt" overFlowActionValue="<?php echo $value['id'];?>"><?php echo $value['label'];?></a>
												<?php }else{?>
													<a class="up-txt" overFlowActionValue="<?php echo $value['id'];?>"><?php echo $value['label'];?></a>
												<?php } ?>
									<?php	}?>
									<!-- <a class="up-txt">Edit</a>
									<a class="up-txt">Delete</a>
									<a class="up-txt">Report Abuse</a> -->
								</span>
							</div>
						</div>
					</div>
				</div>
	<?php 	}?>
<?php	//if($data['showViewMore'] == 1){?>
			<div class="v-cmnts commentDiv" style="display: none;" onclick="loadCommentReply(this,<?=$parentId?>,'<?= $entityType?>',<?= $startIndex+count($data['childDetails'])?>,5,true)">View More <?= ($entityType == "question")?"Comments":"Replies"?></div>
			<img class="loaderIcon" style="display: none;" border="0" src="<?php echo SHIKSHA_HOME;?>/public/images/ShikshaMobileLoader.gif">
<?php	//}?>
