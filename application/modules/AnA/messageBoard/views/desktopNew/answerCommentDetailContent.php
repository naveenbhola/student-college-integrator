<?php 
  $childCount_=0;
  foreach($data['childDetails'] as $key=>$childDetail){
    if ($data['entityDetails']['childCount']>5) {
      $childCount_++;
      if ($childCount_==4) {
        $this->load->view('dfp/dfpCommonHtmlBanner.php',array('bannerType' => 'content','bannerPlace' => 'C_AON'));
      }
    }

    if($data['entityDetails']['queryType'] == 'Q'){
              $entityType = 'Answer';
              $childType = 'Comment';
              $type = 'question';
              $reportAbuseEntityType = 'answer';
              $actionType = 'answer';
              $tdowntrackingPageKeyId = $tdownatrackingPageKeyId;
              $tuptrackingPageKeyId = $tupatrackingPageKeyId;
              $leafPostTrackingPageKeyId = $ctrackingPageKeyId;
              $editAction = "makeAnswerPostingLayer('".$childDetail['threadId']."','".$childDetail['msgId']."',"."'edit')";
              $GA_currentPage = 'QUESTION DETAIL PAGE';
              $GA_Tap_On_View_More_Childs   = 'VIEWMOREANSWERS_QUEST';
              $GA_Tap_On_View_More_Child    = 'VIEWMORE_ANSWER';
              $GA_Tap_On_Owner              =  'PROFILE_ANSWER';
              $GA_Tap_On_Leaf_Entity        =  'COMMENT_ANSWER';
              $GA_Tap_On_Share_Child        = 'SHARE_ANSWER';
              $GA_Tap_On_Report_Abuse_Child = 'REPORTABUSE_ANSWER';
              $GA_Tap_On_Edit_Child         = 'EDIT_ANSWER';
              $GA_Tap_On_Del_Child          = 'DELETE_ANSWER';
              $GA_Tap_On_Upvote             = 'UPVOTE_ANSWER';
              $GA_Tap_On_Downvote           = 'DOWNVOTE_ANSWER';
              $GA_Tap_On_Upvote_List        = 'UPVOTERS_ANSWER';
              $GA_Tap_On_OverFlow           = 'OVERFLOW_QUEST';
              $GA_Tap_On_Leaf_Entity_CTA    = 'COMMENTCTA_ANSWER';
              $GA_Tap_On_Post               = 'POST_ANSCOMMENTPAGE_DESKAnA';
              $GA_Tap_On_Post_Page          = 'ANSWERCOMMENT';
    }else{
              $entityType = 'Comment';
              $childType = 'Reply';
              $type = 'discussion';
              $reportAbuseEntityType = 'discussionComment';
              $actionType = 'comment';
              $tdowntrackingPageKeyId       = $tdowndctrackingPageKeyId;
              $tuptrackingPageKeyId         = $tupdctrackingPageKeyId;
              $leafPostTrackingPageKeyId    = $drtrackingPageKeyId;
              $editAction = "makeCommentPostingLayer('".$childDetail['threadId']."','".$childDetail['msgId']."')";
              $GA_currentPage               = 'DISCUSSION DETAIL PAGE';
              $GA_Tap_On_View_More_Childs   = 'VIEWMORECOMMENTS_DISC';
              $GA_Tap_On_View_More_Child    = 'VIEWMORE_COMMENT';
              $GA_Tap_On_Owner              = 'PROFILE_ANSWER';
              $GA_Tap_On_Leaf_Entity        = 'REPLY_COMMENT';
              $GA_Tap_On_Share_Child        = 'SHARE_COMMENT';
              $GA_Tap_On_Report_Abuse_Child = 'REPORTABUSE_COMMENT';
              $GA_Tap_On_Edit_Child         = 'EDIT_COMMENT';
              $GA_Tap_On_Del_Child          = 'DELETE_COMMENT';
              $GA_Tap_On_Upvote             = 'UPVOTE_COMMENT';
              $GA_Tap_On_Downvote           = 'DOWNVOTE_COMMENT';
              $GA_Tap_On_Upvote_List        = 'UPVOTERS_COMMENT';
              $GA_Tap_On_OverFlow           = 'OVERFLOW_DISC';
              $GA_Tap_On_Leaf_Entity_CTA    = 'REPLYCTA_COMMENT';
              $GA_Tap_On_Post               = 'POST_COMMENTREPLYPAGE_DESKAnA';
              $GA_Tap_On_Post_Page          = 'COMMENTREPLY';
    }
  ?>
      <li class="module" tracking="true" questionid="0" answerid="<?php echo $childDetail['msgId'];?>" type="<?php echo $data['entityDetails']['queryType'];?>">

      <!--answer-posting-col-->
      <div class="avatar-col">
       <div class="" >
          <?php $userProfileURL = getSeoUrl($childDetail['userId'],'userprofile'); ?>          
          <a class="new-avatar" href="<?=$userProfileURL;?>" ga-attr="<?php echo $GA_Tap_On_Owner;?>">
           <?php if($childDetail['picUrl'] != '' && strpos($childDetail['picUrl'],'/public/images/photoNotAvailable.gif') === false){?>
                <img src=<?php echo getSmallImage($childDetail['picUrl']);?> alt="Shiksha Ask & Answer" style="width: 60px;height: 60px;">
            <?php } else{
                echo ucwords(substr($childDetail['firstname'],0,1));
            }?>
          </a>
          <div class="data-new-col">
             <p class="ans-by"><?php echo $entityType.'ed by'; ?></p>
             <p class="user-state">
              <strong> <a class="avatar-name" data-uid="<?=$childDetail['userId'];?>" href="<?=$userProfileURL;?>" ga-attr="<?php echo $GA_Tap_On_Owner;?>" ><?php echo $childDetail['firstname'].' '.$childDetail['lastname'];?>
              </a>
            </strong>
              <span>
              <?php 

                /*if($childDetail['CA_instituteId']>0){
                        echo 'Current Student'.', '.$childDetail['CA_instName'];
                }else{*/
                  
                    if(isset($childDetail['aboutMe']) && $childDetail['aboutMe'] != ''){
                        echo $childDetail['aboutMe']; 
                    } ?>
              </span>

               
                  
                <?php $level = explode('-',$childDetail['levelName']);                                                                                                     
                if(substr($level[1],6,2)>=6){ ?>
                   | <a class="lvl-name" href="<?php echo SHIKSHA_HOME;?>/shikshaHelp/ShikshaHelp/upsInfo" ><?php echo $childDetail['levelName'];?></a>
                  <?php } ?>
              </p>
              <span class="time"><?php if($childDetail['formattedTime'] == ''){echo 'Just now';}else {echo $childDetail['formattedTime'];}?></span>
           </div>
        </div>

          <div class="inf-block">
                   <div class="rp-txt" id="lessAnswer_<?=$childDetail['msgId'];?>">
                       <?php 
                        $fullAnwerWithInstSuggestions = $childDetail['msgTxt'].$childDetail['suggestionText'];
                        $ansTextlen = strlen(strip_tags($fullAnwerWithInstSuggestions));
                        
                        if($ansTextlen > 650){
                            echo substr(strip_tags($fullAnwerWithInstSuggestions),0,650).'...';    
                        }else{
                            echo $fullAnwerWithInstSuggestions; 
                        }
                      ?>
                      <?php if($ansTextlen > 650){?>
                          <a class="link qnaVMore" id="viewMoreBtn_<?=$childDetail['msgId'];?>" ga-attr="<?php echo $GA_Tap_On_View_More_Child;?>" data-msgId="<?=$childDetail['msgId'];?>">view more</a> 
                      <?php } ?>                
                   </div>
                    <div class="rp-txt" id="entityTxt_<?=$childDetail['msgId'];?>" style = "display:none;"><?php echo $fullAnwerWithInstSuggestions; ?></div>

                    <?php $formattedMsg = preg_replace('#<br\s*/?>#i', "\n", $childDetail['msgTxt']); ?>
                    <p id="answerMsgTxt_<?=$childDetail['msgId'];?>" style = "display:none;"><?=$formattedMsg;?></p>
                    <p id="instSuggestion_<?=$childDetail['msgId'];?>" style = "display:none;"><?=$childDetail['suggestionText'];?></p>

                   <div class="opinion-col" id="optionCol_<?=$childDetail['msgId'];?>">
                       <?php 
                      if($childDetail['digUp']>0){ 
                          $displayUpClass = 'display:none';
                          $displayStyle = '';
                          
                      }else{
                         $displayUpClass = 'cursor:default;text-decoration: none';
                          $displayStyle = 'display:none';
                      }
        
                      if($childDetail['digUp']>=1000){
                        $digUpCount = round(($childDetail['digUp']/1000),1).'k'; 
                      }else{
                        $digUpCount = $childDetail['digUp'];
                      }

                      if($childDetail['digDown']>=1000){
                        $digDownCount = round(($childDetail['digDown']/1000),1).'k'; 
                      }else{
                        $digDownCount = $childDetail['digDown'];
                      }

                     $digUplabel = ($childDetail['digUp']==1)? 'Upvote':'Upvotes';
                     $digDownlabel = ($childDetail['digDown']==1)? 'Downvote':'Downvotes'; 

                  ?>

                   <?php if($childDetail['hasUserVotedUp'] == 'true'){
                          $upvotedClass = 'b-like';
                          $downvotedClass = 'g1-dislike';
                          $upvotedReverseClass = 'g-like';
                          $downvotedReverseClass = 'b-dislike';
                          $upvotedDisableClass = 'g1-like';
                          $downvotedDisableClass = 'g-dislike';
                          $upvotedAction = 'cancelupvote';
                          $downvotedAction = 'noaction';
                        }else if($childDetail['hasUserVotedDown'] == 'true'){
                          $upvotedClass = 'g1-like';
                          $downvotedClass = 'b-dislike';
                          $upvotedReverseClass = 'b-like';
                          $downvotedReverseClass = 'g-dislike';
                          $upvotedDisableClass = 'g-like';
                          $downvotedDisableClass = 'g1-dislike';
                          $upvotedAction = 'noaction';
                          $downvotedAction = 'canceldownvote';
                        }else{
                          $upvotedClass = 'g-like';
                          $downvotedClass = 'g-dislike';
                          $upvotedReverseClass = 'b-like';
                          $downvotedReverseClass = 'b-dislike';
                          $upvotedDisableClass = 'g1-like';
                          $downvotedDisableClass = 'g1-dislike';
                          $upvotedAction = 'upvote';
                          $downvotedAction = 'downvote';
                        }
                  ?>
                       <span>

                           <a class="up-thumb like-a qthumbClk" href="javascript:void(0)" ga-attr="<?php echo $GA_Tap_On_Upvote;?>" data-msgId="<?php echo $childDetail['msgId']?>" data-actionType="<?php echo $actionType;?>" data-trackingKey="<?php echo $tuptrackingPageKeyId;?>" callforaction="<?php echo $upvotedAction;?>"><i class="<?php echo $upvotedClass;?>" reverseclass="<?php echo $upvotedReverseClass;?>" disabledclass="<?php echo $upvotedDisableClass;?>"></i></a>
                           <a class="up-thumb like-d qthumbClk" data-msgId="<?php echo $childDetail['msgId']?>" data-actionType="<?php echo $actionType;?>" data-trackingKey="<?php echo $tdowntrackingPageKeyId;?>" href="javascript:void(0)" ga-attr="<?php echo $GA_Tap_On_Downvote;?>" callforaction="<?php echo $downvotedAction;?>"><i class="<?php echo $downvotedClass;?>" reverseclass="<?php echo $downvotedReverseClass;?>" disabledclass="<?php echo $downvotedDisableClass;?>"></i></a>
                           <a class="up-txt qupTextClk" data-msgId="<?php echo $childDetail['msgId'];?>" data-type="<?php echo $type;?>" data-trackingKey="<?php echo $uplistfollowTrackingPageKeyId;?>" id="upvoteLinkEnable_<?=$childDetail['msgId']?>" style="<?=$displayStyle?>" ga-attr="<?php echo $GA_Tap_On_Upvote_List;?>"  listElement="list_<?php echo $childDetail['msgId'];?>" valueCount="<?php echo $childDetail['digUp'];?>"><span class="digupCount"><?php echo $digUpCount; ?></span><?php echo ' ';?><span class="digupText"><?=$digUplabel?></span></a>
                           <a class="dwn-txt" id="upvoteLinkDisable_<?=$childDetail['msgId']?>" style="<?=$displayUpClass?>"><span class="digupCount"><?php echo $digUpCount; ?></span><?php echo ' ';?><span class="digupText"><?=$digUplabel?></span></a>
                           <a class="dwn-txt" style="cursor:default;text-decoration: none"><span class="digdownCount"><?=$digDownCount?></span><?php echo ' ';?><span class="digdownText"><?=$digDownlabel ?></span></a> 
                           <input type="hidden" name="userCountUpvote_<?=$childDetail['msgId']?>" id="userCountUpvote_<?=$childDetail['msgId']?>" value="<?=$childDetail['digUp']?>">
                          <input type="hidden" name="userCountDownvote_<?=$childDetail['msgId']?>" id="userCountDownvote_<?=$childDetail['msgId']?>" value="<?=$childDetail['digDown']?>">

                           <?php 
                                $childLabel = '';
                                if($childType == 'Comment'){
                                  if($childDetail['childCount'] == 0){
                                     $childLabel == '';
                                  }else{
                                     $childLabel = ($childDetail['childCount'] == 1)?'Comment':'Comments';
                                  }
                                  
                                }
                                else{
                                  if($childDetail['childCount'] == 0){
                                     $childLabel == '';
                                  }else{
                                     $childLabel = ($childDetail['childCount'] == 1)?'Reply':'Replies';
                                  }
                                  
                                }

                                $childCount = ($childDetail['childCount'] > 0)?$childDetail['childCount']:'';
                                $commentCountClass = ($childDetail['childCount'] > 0)?'up-txt':'dwn-txt';
								if($childDetail['childCount'] == 0)
                                  {
                                    $childTypeClass = 'cursor:default;text-decoration: none';
                                    $onclickCommentReplyCount = "";
                                  }
                                  else{
                                    $childTypeClass = '';
                                    $onclickCommentReplyCount = 'loadCommentReply(this,'.$childDetail['msgId'].',\''.$type.'\',0,3);';
                                  }
                            ?>    
                                <a href="javascript:void(0);" childCount="<?= $childDetail['childCount'];?>" id="loadCommentReply_<?=$childDetail['msgId']?>" class="<?=$commentCountClass;?> commentAnchor" style="<?php echo $childTypeClass;?>" showHideCommentReply = "true" ga-attr="<?php echo $GA_Tap_On_Leaf_Entity;?>" onclick="<?=$onclickCommentReplyCount;?>"><span id="entityChildCount_<?=$childDetail['msgId']?>"><?=$childCount;?></span><?php echo ' ';?><span id='entityChildText_<?=$childDetail['msgId']?>'><?php echo $childLabel; ?></span></a>
                                <input type="hidden" class="classCommentReplyCountHidden" value="<?= $childDetail['childCount'];?>">
                                 
                       </span>   
                       <div class="left-cl">
                           <ul class="nav-discussion">
                              <?php if(!empty($childDetail['overflowTabs'])){?>
                                  <li class="nav-item"><a class="nav-lnk overflowDiv showDotQLyr" ga-attr="<?php echo $GA_Tap_On_OverFlow;?>" data-msgId="<?=$childDetail['msgId']?>"><i class="dot" ></i></a>
                                    <span class="opt-ul overflowOptions" id="overflowOptions_<?=$childDetail['msgId']?>">
                                     <ul class="qdp-ul">
                                        <?php foreach($childDetail['overflowTabs'] as $key=>$actions){ ?>
                                            <?php if($actions['label'] == 'Delete'){?>
                                                  <li><a id="closeDeleteEntity" href="javascript:void(0);" ga-attr="<?php echo $GA_Tap_On_Del_Child;?>" data-msgId="<?=$childDetail['msgId'];?>" data-threadId="<?=$childDetail['threadId'];?>" data-parentId="<?=$childDetail['parentId']?>" data-entityType="<?=$entityType;?>" data-label="<?=$actions['label'];?>" class="q qCDE"><?=$actions['label']?></a></li>
                                            <?php }elseif($actions['label'] == 'Report Abuse'){?>
                                                  <li><a id="raLayer" href="javascript:void(0);" ga-attr="<?php echo $GA_Tap_On_Report_Abuse_Child;?>" data-msgId="<?php echo $childDetail['msgId'];?>" data-threadId="<?php echo $childDetail['threadId']?>" data-raEntityType="<?php echo $reportAbuseEntityType;?>" data-trackingKey="<?php echo $raCTrackingPageKeyId;?>" class="q raLayerClk"><?=$actions['label']?></a></li> 
                                            <?php }elseif($actions['label'] == 'Edit'){?>
                                                  <li><a id="raLayer" href="javascript:void(0);" ga-attr="<?php echo $GA_Tap_On_Edit_Child;?>" onclick="<?=$editAction?>"  class="q edit_<?=$childDetail['msgId']?>"><?=$actions['label']?></a></li>
                                            <?php }else{?>
                                                  <li><a href="javascript:void(0);" class="q"><?=$actions['label']?></a></li>
                                            <?php } ?>
                                        <?php } ?>
                                     </ul>
                                   </span>  
                               </li>
                              <?php } ?>
                             <li class="nav-item"><a class="nav-lnk qSLayer" href="javascript:void(0);" ga-attr="<?php echo $GA_Tap_On_Share_Child;?>" data-threadId="<?=$childDetail['msgId']?>" data-shareUrl="<?=$childDetail['shareUrl']?>" data-param="<?php if($childDetail['queryType'] == 'D'){echo 'discussionComment';} else {echo 'answer';} ?>"><i class="share"></i></a></li>
                             <li class="nav-item"><a class="ana-btns c-btn ansBtnClk" id="childLink<?=$childDetail['msgId']?>" href="javascript:void(0);" ga-attr="<?php echo $GA_Tap_On_Leaf_Entity_CTA;?>" data-msgId="<?=$childDetail['msgId']?>"><?=$childType;?></a></li>
                           </ul>
                        </div>
                        <img class="loaderIcon" style="display: none;" border="0" src="<?php echo SHIKSHA_HOME;?>/public/mobile5/images/ajax-loader.gif">
                    </div>
                    <input type="hidden" name="showViewMore" id="showViewMore" value="<?php echo $data['entityDetails']['showViewMore'];?>" />
                    
                    <!-- Comment Box Starts -->
                     <form id="postEntityAnA_<?=$childDetail['msgId']?>" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="postAnswerAnA">
                       <div class="ans-block new__ans__block" id="entityPostingCol_<?=$childDetail['msgId']?>" style="display:none;">
                          <p class="txt-count" style="display:block;" id="entityTxtCounter<?=$childDetail['msgId']?>">Characters <span id="entity_text_<?=$childDetail['msgId']?>_counter">0</span>/2500</p>
                          <textarea placeholder="Write your <?=$childType;?>. Feel free to share your opinion and experience, the community will appreciate it." onkeypress="handleCharacterInTextField(event,true);" onkeyup="autoGrowField(this,300);textKey(this)" validate="validateStr" minlength=15 maxlength=2500 caption="Answer" id="entity_text_<?=$childDetail['msgId']?>" required="true" onpaste="handlePastedTextInTextField('entity_text_<?=$childDetail['msgId']?>',true);"></textarea>
                          <div class="btns-col">
                             <span class="right-box">
                                  <a class="exit-btn qtbCancel" href="javascript:void(0);" data-msgId="<?=$childDetail['msgId']?>">Cancel</a>
                                  <a class="prime-btn qtbPost" href="javascript:void(0);" id="entityPostingButton<?=$childDetail['msgId']?>" ga-attr="<?php echo $GA_Tap_On_Post;?>" data-threadId="<?=$childDetail['threadId']?>" data-msgId="<?=$childDetail['msgId'];?>" actionPerformed ='commentPosting'>Post</a>
                              </span>
                              <p class="clr"></p>
                          </div>
                          <input type="hidden" id="threadId_<?=$childDetail['msgId']?>" value="<?=$childDetail['threadId']?>" />
                          <input type="hidden" id="editEntityId<?=$childDetail['msgId']?>" value="0" />
                          <input type="hidden" id="parentType<?=$childDetail['msgId']?>" value="<?=$type?>" />
                          <input type="hidden" id="entityType<?=$childDetail['msgId']?>" value="<?=ucfirst($childType)?>" />
                          <input type="hidden" id="parentId_<?=$childDetail['msgId']?>" value="<?=$childDetail['parentId']?>" />
                          <input type="hidden" id="actionOnAns_<?=$childDetail['msgId']?>" value="add" />
                          <input type="hidden" id="tracking_keyid_<?php echo $childDetail['msgId'];?>" value="<?php echo $leafPostTrackingPageKeyId;?>">

                          <div class="errorQDP">
                            <p class="err0r-msg "  id="entity_text_<?=$childDetail['msgId']?>_error"></p>
                         </div>
                       </div>
                        
                   </form>
					         <!-- Comment Box Ends -->
          </div>
     </div>
    </li>
<?php } ?>
<script>
paginationHtml = `<?php echo $paginationHtml?>`;
</script>
