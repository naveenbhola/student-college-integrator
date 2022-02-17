 <!--questions link block-->
 <?php if($data['entityDetails']['queryType'] == 'Q'){
      $GA_currentPage = 'QUESTION DETAIL PAGE';
      $GA_Tap_On_Tag = 'TAG';
      $GA_Tap_On_Desc_View_More = 'VIEWMORE_DESCRIPTION';
      $GA_Tap_On_Write = 'WRITEANSWER';
      $GA_Tap_On_parentId_Owner = 'PROFILE_QUEST';
      $GA_Tap_On_share = 'SHARE';
      $GA_Tap_On_AnsCom_Later = 'ANSWERLATER_QUEST';
      $GA_Tap_On_Edit_Ques = 'EDITQUEST_QUEST';
      $GA_Tap_On_Edit_Tags = 'EDITTAGS_QUEST';
      $GA_Tap_On_Close ='CLOSE_QUEST';
      $GA_Tap_On_Delete = 'DELETE_QUEST';
      $GA_Tap_On_abuseQuestion = 'REPORTABUSE_QUEST';
      $GA_Tap_On_Follow = 'FOLLOW';
      $GA_Tap_On_Overflow = 'OVERFLOW_QUEST';
      $GA_Tap_On_Follower_List = 'FOLLOWERLIST';
      $GA_Tap_On_LinkedParent = 'LINKING_QUEST';
      $GA_Tap_On_Move_To_Listing = 'MOVTOLISTING_QUEST';
      $GA_Tap_On_Move_To_Cafe     = 'MOVTOCAFE_QUEST';
      $GA_Tap_On_Post_Child       = 'POSTANSWER';
 }
 elseif($data['entityDetails']['queryType'] == 'D'){
    $GA_currentPage = 'DISCUSSION DETAIL PAGE';
    $GA_Tap_On_Tag            = 'TAG';
    $GA_Tap_On_Desc_View_More = 'VIEWMORE_DESCRIPTION';
    $GA_Tap_On_Write          = 'WRITECOMMENT';
    $GA_Tap_On_parentId_Owner = 'PROFILE_DISC';
    $GA_Tap_On_Follow         = 'FOLLOW';
    $GA_Tap_On_share          = 'SHARE';
    $GA_Tap_On_Overflow       = 'OVERFLOW_DISC';
    $GA_Tap_On_abuseQuestion  = 'REPORTABUSE_DISC';
    $GA_Tap_On_AnsCom_Later   = 'COMMENTLATER_DISC';
    $GA_Tap_On_Edit_Ques      = 'EDITDISC_DISC';
    $GA_Tap_On_Edit_Tags      = 'EDITTAGS_DISC';
    $GA_Tap_On_Close          = 'CLOSE_DISC';
    $GA_Tap_On_Delete         = 'DELETE_DISC';
    $GA_Tap_On_Follower_List  = 'FOLLOWERLIST';
    $GA_Tap_On_LinkedParent   = 'LINKING_DISC';
    $GA_Tap_On_Post_Child       = 'POSTCOMMENT';
}
 ?>
 <?php $this->load->view('desktopNew/listOfUserLayer');?>
 <?php if($data['entityDetails']['queryType'] == 'D'){?>
 <i class="disc-ico"></i>
 <?php } ?>
  <div class="qdp-qstn-block" >
 <?php if(!empty($data['entityDetails']['tagsDetail'])){?>

  <div id="lessTags_<?=$data['entityDetails']['msgId'];?>">
    <div class="qstn-row <?php if($data['entityDetails']['queryType'] == 'Q'){?> fullwdth <?php }?> " id="lessTags">
          <?php foreach($data['entityDetails']['tagsDetail'] as $key=>$value){ 
            //$tagUrl = getSeoUrl($value['tagId'], 'tag', $value['tagName']);
          ?>
           <a href="<?=$value['url']?>" <?php if($value['type']!="ne") {?> class="ent-tgClr" <?php } ?> ga-attr="<?php echo $GA_Tap_On_Tag;?>"><?=$value['tagName'];?></a> 

       <?php } ?>

    </div>
     <a href="javascript:void(0);" id="viewAllTags" data-msgId="<?=$data['entityDetails']['msgId'];?>" class="link" style="display:none">View all tags</a>

 </div>
 <div class="qdp-tag-div" id="allTagsDiv_<?=$data['entityDetails']['msgId'];?>" style="display:none">
    <div class="qstn-row <?php if($data['entityDetails']['queryType'] == 'Q'){?> fullwdth <?php }?> ">
          <?php foreach($data['entityDetails']['tagsDetail'] as $key=>$value){ 
            //$tagUrl = getSeoUrl($value['tagId'], 'tag', $value['tagName']);
          ?>
           <a href="<?=$value['url']?>" <?php if($value['type']!="ne") {?> class="ent-tgClr" <?php } ?> ga-attr="<?php echo $GA_Tap_On_Tag;?>"><?=$value['tagName'];?></a> 

       <?php } ?>
    </div>
    </div>

<?php } ?>

<?php 

             if($data['entityDetails']['queryType'] == 'Q'){
                    $childType = 'Answer';
                    $threadType = 'question';
                    $postingAction = "initializeAnswerPostingAnA('".$data['entityDetails']['threadId']."')";
                    $followTrackingPageKeyId = $qfollowTrackingPageKeyId;
                    $markLaterTrackingPageKeyId = $alTrackingPageKeyId;
                    $childPostTrackingPageKeyId = $atrackingPageKeyId;
                    $nameTxt = "Asked by";
              }else{
                    $childType = 'Comment';
                    $threadType = 'discussion';
                    $postingAction = "initializeCommentPostingAnA('".$data['entityDetails']['threadId']."')"; 
                    $followTrackingPageKeyId = $dfollowTrackingPageKeyId;
                    $markLaterTrackingPageKeyId = $dclTrackingPageKeyId;
                    $childPostTrackingPageKeyId = $dctrackingPageKeyId;
                    $nameTxt = "Started by";
              }
          
              if($data['entityDetails']['followerCount']==0){   
                  $displayClass = "";
                  $curClass = 'zeroFollowers';
                  $revClass = 'follower';
                  $displayClass = 'style="display:none"';
              }else{
                  $label = ($data['entityDetails']['followerCount']>1)? 'Followers':'Follower';
                  $displayClass = "";
                  $curClass = 'follower';
                  $revClass = 'zeroFollowers';
                  $displayClass = '';
              } 

              if($data['entityDetails']['followerCount'] >= 1000){
                 $totalFollowCount =  round(($data['entityDetails']['followerCount']/1000),1).'k';
                
              }else{
                 $totalFollowCount = $data['entityDetails']['followerCount'];
              }

              if($data['entityDetails']['viewCount'] >= 1000){
                 $totalViewCount =  round(($data['entityDetails']['viewCount']/1000),1).'k';
                
              }else{
                 $totalViewCount = $data['entityDetails']['viewCount'];
              }

              $viewCountTxt = ($data['entityDetails']['viewCount']==1)?'View':'Views';

          ?>

</div>

 <!---->
 <div class="dtl-qstn" tracking="false" questionid="<?=$data['entityDetails']['threadId']?>" answerid="0" type="<?php echo $data['entityDetails']['queryType'];?>">
       <h1 id="quesTitle_<?=$data['entityDetails']['threadId']?>"><?=$data['entityDetails']['title']?></h1>

       <?php 
        if($data['entityDetails']['queryType'] == 'Q'){ ?>
                  <p class="ask-q-txt"><?=$data['entityDetails']['referrenceName']?></p>
        <?php }

        if ($data['entityDetails']['description'] != ''){ ?>
           <p class="multi-txt" id="entityDesc_<?=$data['entityDetails']['threadId']?>">
              <?php 

                $entityDescLen = strlen(strip_tags($data['entityDetails']['description']));
                if($entityDescLen > 300){
                    echo substr(strip_tags($data['entityDetails']['description']),0,300).'...'; 
                ?>
                    <a href="javascript:void(0)" ga-attr="<?php echo $GA_Tap_On_Desc_View_More;?>" class="link qVFED" id="viewMoreBtn_<?=$data['entityDetails']['threadId']?>" data-threadId="<?=$data['entityDetails']['threadId']?>">View More</a>   
                <?php }else{
                    echo trim($data['entityDetails']['description']); 
                }
              ?>
            </p>
       <?php } ?>
       <?php if($entityDescLen > 300){?>
                  <p class="multi-txt" id="entityFullDesc_<?=$data['entityDetails']['threadId']?>" style ="display:none;"><?php echo $data['entityDetails']['description']; ?></p>
                  
        <?php } ?>
      
        <?php $userProfileURL = getSeoUrl($data['entityDetails']['userId'],'userprofile'); ?>            
        <!-- <div class="n-s"> 
            <a class="l-div" href="<?=$userProfileURL;?>" ga-attr="<?php echo $GA_Tap_On_parentId_Owner;?>">
                      <?php if($data['entityDetails']['picUrl'] != '' && strpos($data['entityDetails']['picUrl'],'/public/images/photoNotAvailable.gif') === false){?>
                              <img src=<?php echo getSmallImage($data['entityDetails']['picUrl']);?> alt="Shiksha Ask & Answer" style="width: 60px;height: 60px;">

                      <?php }else{
                          echo ucwords(substr($data['entityDetails']['firstname'],0,1));
                      }?>
            </a>
            
            <div class="r-div">
              <a class="r-div-n" href="<?=$userProfileURL;?>" ga-attr="<?php echo $GA_Tap_On_parentId_Owner;?>"><?php echo $data['entityDetails']['firstname'].' '.$data['entityDetails']['lastname']?>
              <?php if(isset($data['entityDetails']['aboutMe']) && $data['entityDetails']['aboutMe'] != ''){?>
                <span><?=$data['entityDetails']['aboutMe'];?></span>
              <?php } ?>
              </a>
              <p class="r-div-l"><?=$data['entityDetails']['levelName']?></p>
            </div>
        </div>   -->
 </div>
 
 <span class="time time1">
  <span><a class="<?php echo $curClass;?> followersCountTextArea qupTextClk" curClass="<?php echo $curClass;?>" revClass="<?php echo $revClass;?>" href="javascript:void(0)" style = '<?php echo $displayClass;?>' ga-attr="<?php echo $GA_Tap_On_Follower_List;?>" data-msgId="<?php echo $data['entityDetails']['threadId'];?>" data-type="<?php echo $entityType;?>" data-param="follow" data-trackingKey="<?php echo $flistfollowTrackingPageKeyId;?>" listElement="list_<?php echo $data['entityDetails']['threadId'];?>" valueCount="<?php echo $data['entityDetails']['followerCount'];?>"><?=$totalFollowCount?><?php echo ' '?><?=$label?></a>
  </span>
  <b <?=$displayClass?> id="followerSpan">|</b>
  <span class="viewers-span"><?php echo $totalViewCount.' '.$viewCountTxt?></span>
  <b>|</b>
  <span>Posted <?=$data['entityDetails']['creationDate']?></span>
</span>
  
 <!---->
 <div class="new-column cta-block">
    <div class="right-cl">
      <span class="viewers-span"><?=$nameTxt;?> <a class="r-div-n" href="<?=$userProfileURL;?>" ga-attr="<?php echo $GA_Tap_On_parentId_Owner;?>"><?php echo $data['entityDetails']['firstname'].' '.$data['entityDetails']['lastname']?></span>
        <!---->
     </div>
    <div class="left-cl">
       <ul class="nav-discussion">
        <?php if( is_array($data['entityDetails']['overflowTabs']) && ((count($data['entityDetails']['overflowTabs']) > 0 && $data['entityDetails']['status'] == 'live') || (count($data['entityDetails']['overflowTabs']) > 1 && $data['entityDetails']['status'] == 'closed') )) {?>

         <li class="nav-item">
          
           <a class="nav-lnk overflowDiv nvLnk" ga-attr="<?php echo $GA_Tap_On_Overflow;?>" data-msgId="<?=$data['entityDetails']['msgId']?>" id="overflowDiv_<?=$data['entityDetails']['msgId']?>"><i class="dot"></i></a>

              <span class="opt-ul overflowOptions" id="overflowOptions_<?=$data['entityDetails']['msgId']?>">
                 <ul class="qdp-ul">
                    <?php	foreach($data['entityDetails']['overflowTabs'] as $key=>$actions){ ?>
                		<?php	if($actions['label'] == 'Report Abuse') {?>
                            		<li><a href="javascript:void(0);" id="raLayer" class="q raLayerClk" ga-attr="<?php echo $GA_Tap_On_abuseQuestion;?>" data-msgId="<?php echo $data['entityDetails']['msgId'];?>" data-threadId="<?php echo $data['entityDetails']['threadId']?>" data-raEntityType="<?php echo $entityType;?>" data-trackingKey="<?php echo $raPTrackingPageKeyId;?>"><?=$actions['label']?></a></li>
                		<?php	}elseif($actions['label'] == 'Close' || $actions['label'] == 'Delete'){
	                                if($actions['label'] == 'Close')
	                                    $GA_CLOSEDELQUE = $GA_Tap_On_Close;
	                                else
	                                    $GA_CLOSEDELQUE = $GA_Tap_On_Delete;
                        ?>
                        			<li><a href="javascript:void(0);" id="closeDeleteEntity" class="q qCDE" ga-attr="<?php echo $GA_CLOSEDELQUE;?>" data-threadId="<?=$data['entityDetails']['threadId'];?>" data-parentId="<?=$data['entityDetails']['parentId'];?>" data-entityType="<?=ucfirst($entityType);?>" data-label="<?=$actions['label'];?>"><?=$actions['label']?></a></li>
			  			<?php	}elseif($actions['label'] == 'Answer Later' || $actions['label'] == 'Comment Later'){
		                        	if(count($data['entityDetails']['overflowTabs']) == 1){
		                                	?><script>var hideOverflowIcon = true;</script><?php
		                        	}
                 	  	?>
                 	  				<li id="markLaterActionLink"><a href="javascript:void(0);" class="q qMarkLtr" data-entityId="<?php echo $entityId;?>" data-type="<?php echo $threadType;?>" data-trackingKey="<?php echo $markLaterTrackingPageKeyId;?>" data-msgId="<?=$data['entityDetails']['msgId']?>" ga-attr="<?php echo $GA_Tap_On_AnsCom_Later;?>"><?=$actions['label']?></a></li>
						<?php	}elseif($actions['label'] == "Edit Question"){?>
									<li><a href="javascript:void(0);" class="q qEDQ" data-param="question" ga-attr="<?php echo $GA_Tap_On_Edit_Ques;?>"><?=$actions['label']?></a></li>
						<?php	}elseif($actions['label'] == "Edit Discussion"){?>
									<li><a href="javascript:void(0);" class="q qEDQ" data-param="discussion" ga-attr="<?php echo $GA_Tap_On_Edit_Ques;?>"><?=$actions['label']?></a></li>
						<?php	}elseif($actions['label'] == "Edit Tags") {
									if($data['entityDetails']['queryType'] == "Q"){
						?>
										<li><a href="javascript:void(0);" class="q qeditag" data-param="question" ga-attr="<?php echo $GA_Tap_On_Edit_Tags;?>"><?=$actions['label']?></a></li>
						<?php		}else{?>
										<li><a href="javascript:void(0);" class="q qeditag" data-param="discussion" ga-attr="<?php echo $GA_Tap_On_Edit_Tags;?>"><?=$actions['label']?></a></li>
						<?php		}
								}elseif (in_array($actions['label'], array('Link Question', 'Link Discussion'))){
						?>
                           			<li><a href="javascript:void(0);" class="q oLTO" data-entityId="<?= $entityId?>" data-entityType="<?= $entityType?>" ga-attr="<?php echo $GA_Tap_On_LinkedParent;?>"><?= $actions['label']?></a></li>
                        <?php	}elseif($actions['label'] != 'Share'){?>
                        			<li><a href="javascript:void(0);" class="q"><?=$actions['label']?></a></li>
						<?php	} ?>
                    <?php	} ?> 
					  <?php if(isset($isModerator) && $isModerator == true){
                              if(isset($isCollegeTagRemoved) && $isCollegeTagRemoved){?>
                                  <li><a href="javascript:void(0);" class="q isModClk" ga-attr="<?php echo $GA_Tap_On_Move_To_Cafe;?>" data-param="off" data-msgId="<?=$data['entityDetails']['msgId']?>">Remove College</a></li>
                              <?php }else{ ?>
                                  <li><a href="javascript:void(0);" class="q isModClk" ga-attr="<?php echo $GA_Tap_On_Move_To_Listing;?>" data-param="on">Move to listings</a></li>
                              <?php } ?>
                              <input type="hidden" id="questionMoveToIns" value="off"/>
                    <?php } ?>
                 </ul>
               </span>  
         </li>
         <?php } ?>

            <li class="nav-item">       
            <a class="nav-lnk qSLayer" data-threadId="<?=$data['entityDetails']['threadId']?>" data-shareUrl="<?=$data['entityDetails']['shareUrl']?>" data-param="<?php if($data['entityDetails']['queryType']=='D'){echo 'discussion';} else{ echo 'question';} ?>" ga-attr="<?php echo $GA_Tap_On_share;?>" href="javascript:void(0);">
              <i class="share" id="share"></i>
             </a>
              </li>

         <?php if(($entityType == 'question' && $data['entityDetails']['status'] !='closed') || ($entityType == 'discussion' && empty($linkedData['data']['linkedEntities']))){ ?>
              <li class="nav-item">
                <?php if($data['entityDetails']['hasUserFollowed'] == 'true'){ ?>
                    <a class="ana-btns un-btn qflEntity" curclass="un-btn" reverseclass="f-btn" href="javascript:void(0);" callforaction="unfollow" ga-attr="<?php echo $GA_Tap_On_Follow;?>" data-entityId="<?php echo $entityId;?>"  data-enType="<?php echo $entityType;?>" data-trackingKey="<?php echo $followTrackingPageKeyId;?>">Unfollow</a>
                <?php }else{ ?>
                    <a class="ana-btns f-btn qflEntity" curclass="f-btn" reverseclass="un-btn" callforaction="follow" href="javascript:void(0);" ga-attr="<?php echo $GA_Tap_On_Follow;?>" data-entityId="<?php echo $entityId;?>"  data-enType="<?php echo $entityType;?>" data-trackingKey="<?php echo $followTrackingPageKeyId;?>">Follow</a>
                <?php } ?>
              </li> 
         <?php } ?>

         <?php if(($data['entityDetails']['isEntityOwner']== false && $data['entityDetails']['hasUserAnswered']== false && $entityType == 'question' && $data['entityDetails']['status']!='closed' ) || ($entityType == 'discussion' && empty($linkedData['data']['linkedEntities']))) { ?>
            <li class="nav-item">
                <?php if($data['entityDetails']['queryType'] == 'Q'){?>
                    <a class="ana-btns a-btn qMAPL" data-threadId="<?=$data['entityDetails']['threadId']?>" id="postAnswer"><?=$childType;?></a>
                <?php }else{?>
                    <a class="ana-btns a-btn mcplClk" ga-attr="<?php echo $GA_Tap_On_Write;?>" id="postAnswer" data-threadId="<?=$data['entityDetails']['threadId']?>"><?=$childType;?></a>
                <?php } ?>
            </li>
          <?php }?>
       </ul>

    </div>

    <p class="clr"></p>

     <!--answer-posting-col-->
	 <div id="entityPostingCol_<?=$data['entityDetails']['threadId']?>" style="display:none;">
     <form id="postEntityAnA_<?=$data['entityDetails']['threadId']?>" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="postAnswerAnA">
       <div class="ans-block new__ans__block" >
          <p class="txt-count" style="display:block;" id="entityTxtCounter<?=$data['entityDetails']['threadId']?>">Characters <span id="entity_text_<?=$data['entityDetails']['threadId']?>_counter">0</span>/2500</p>
          <textarea placeholder="Write your <?=$childType;?>. Feel free to share your opinion and experience, the community will appreciate it." onkeypress="handleCharacterInTextField(event,true);" onkeyup="autoGrowField(this,300);textKey(this)" validate="validateStr" minlength=15 maxlength=2500 caption="Answer" id="entity_text_<?=$data['entityDetails']['threadId']?>" required="true" onpaste="handlePastedTextInTextField('entity_text_<?=$data['entityDetails']['threadId']?>',true);" style="height:120px"></textarea>
          
          <div class="btns-col">
             <span class="right-box">
                  <a class="exit-btn qtbCncl" href="javascript:void(0);" data-threadId="<?=$data['entityDetails']['threadId']?>">Cancel</a>
                  <a class="prime-btn" href="javascript:void(0);" ga-attr="<?php echo $GA_Tap_On_Post_Child;?>" id="entityPostingButton<?=$data['entityDetails']['threadId']?>" onclick="if(!validateCommentAnswerPostingField('entity_text_<?=$data['entityDetails']['threadId']?>','postEntityAnA_<?=$data['entityDetails']['threadId']?>')){return false;}else{<?=$postingAction?>}">Post</a>
              </span>
              <p class="clr"></p>
          </div>
          <input type="hidden" id="threadId_<?=$data['entityDetails']['threadId']?>" value="<?=$data['entityDetails']['threadId']?>" />
          <input type="hidden" id="actionOnAns_<?=$data['entityDetails']['threadId']?>" value="add" />
          <input type="hidden" id="parentType<?=$data['entityDetails']['threadId']?>" value="<?=$entityType?>" />
          <input type="hidden" id="entityType<?=$data['entityDetails']['threadId']?>" value="<?=$childType?>" />
          <input type="hidden" id="tracking_keyid_<?php echo $data['entityDetails']['threadId'];?>" value="<?php echo $childPostTrackingPageKeyId;?>">
       </div>
       <div>
        <p class="err0r-msg"  id="entity_text_<?=$data['entityDetails']['threadId']?>_error"></p>
      </div>
   </form>
   </div>

     <?php if($data['entityDetails']['status']=='closed' && $data['entityDetails']['queryType'] == 'Q'){?>
     <div class="unmsg-col">
      <p>The question is closed for answering</p>
    </div>
    <?php } else if($data['entityDetails']['queryType'] == 'D' && !empty($linkedData['data']['linkedEntities'])) {?>
      <div class="unmsg-col">
        <p>This discussion has been closed by the moderator because a similar discussion exist.</p>
         <p class="chckout">Checkout the linked discussion below :</p>
         <?php foreach($linkedData['data']['linkedEntities'] as $key=>$linkedEntities){?>
         <a href="<?=$linkedEntities['url']?>" ga-attr="LINKED_DISC"><?=$linkedEntities['title']?></a>
         <?php } ?>
      </div>
    <?php } ?>

    <?php if($data['entityDetails']['childCount']== 0){
            if($userIdOfLoginUser == $data['entityDetails']['userId'] && $data['entityDetails']['queryType'] != 'D' && $data['entityDetails']['status'] != 'closed'){
          ?>  
            <div class="blue-box">
               <h3 class="bluebox__titl">Your question is posted successfully, please note:</h3>
               <ul class="ul__lists">
                 <li class="time">You may expect to receive answers from our community within the next 48 hours.</li>
                 <li class="msg">We will send you an email the moment you receive an answer.</li>

                 <li class="tag">A question with relevant tags, gets better answers. We suggest you 
                  <?php if($data['entityDetails']['queryType'] == 'Q' || 1){?>
                      <a href="javascript:void(0);" class="qeditag" data-param="question">review the tags</a> 
                  <?php }else{ ?>
                      <a href="javascript:void(0);" class="qeditag" data-param="discussion">review the tags</a> 
                  <?php } ?>

                  </li>
               </ul>
          </div>

          <?php }else{ ?>
               <div class="unmsg-col">
                <p class="img__no"></p>
                <p>No <?php echo lcfirst($childType).'s';?> yet.</p>
                <?php if($data['entityDetails']['queryType'] == 'Q' && $data['entityDetails']['status'] != 'closed'){?>
                    <a href="javascript:void(0);" class="qMAPL" data-threadId="<?=$data['entityDetails']['threadId']?>">Can you answer this <?=$entityType;?>?</a>
                <?php }elseif($data['entityDetails']['queryType'] == 'D'){?>
                    <a href="javascript:void(0);" class="mcplClk" data-threadId="<?=$data['entityDetails']['threadId']?>">Can you comment on this <?=$entityType;?>?</a>
                <?php } ?>
                
              </div>  
    <?php }
    } ?>
 </div>
