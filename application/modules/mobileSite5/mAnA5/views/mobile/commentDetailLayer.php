     <div class="comment-layer-1" style="display:none;">
                <div class="cmnts-layer" style="display:none" id="confirmLayer_layer">
                  <a href="javascript:void(0);" class="layer-cls" onclick="hideConfirmationMessage('layer')">&times</a>
                      <div class="cmnts-msg">
                            <h2 id="confirmHeading_layer"></h2>
                            <p id="alertHeading_layer"></p>
                      </div>
                      <div class="usr-choice">
                        <a href="javascript:void(0);" onclick="hideConfirmationMessage('layer')">CANCEL</a>
                        <a href="javascript:void(0);" id="performAction_layer">YES</a>
                      </div>
              </div>
        </div>
      <!--comment section card view--> 
      <?php 
           if($entityType == 'question'){
                  $GA_currentPage = 'ANSWERCOMMENT';
                  $GA_tapUserProfile = 'PROFILE_ANSCOMMENTPAGE_WEBAnA'; 
                  $GA_tapReportAbuse = 'REPORTABUSE_ANSCOMMENTPAGE_WEBAnA';
                  $GA_tapEdit = 'EDIT_ANSCOMMENTPAGE_WEBAnA';
          }else{
                  $GA_currentPage = 'COMMENTREPLY';
                  $GA_tapUserProfile = 'PROFILE_COMMENTREPLYPAGE_WEBAnA'; 
                  $GA_tapReportAbuse = 'REPORTABUSE_COMMENTREPLYPAGE_WEBAnA';
                  $GA_tapEdit = 'EDIT_COMMENTREPLYPAGE_WEBAnA';
          } 

      ?>
         <?php foreach($data['childDetails'] as $key=>$commentDetail){ ?>
           <div class="new-cards">
	    <?php $userProfileURL = getSeoUrl($commentDetail['userId'],'userprofile'); ?>
            <a class="cmnt-mt-card" href="<?=$userProfileURL?>" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapUserProfile?>','<?=$GA_userLevel?>');">
                    <div class="cmnt-i-card">
                    <?php 

                      if($commentDetail['picUrl'] != '' && strpos($commentDetail['picUrl'],'/public/images/photoNotAvailable.gif') === false){?>
                          <img src=<?php echo getSmallImage($commentDetail['picUrl']);?> alt="Shiksha Ask & Answer" style="width: 55px;height: 55px;">

                      <?php }else{
                          echo ucwords(substr($commentDetail['firstname'],0,1));
                    }?>
                    </div>
                    <div class="cmnt-inf-card">
                      <p class="cmnt-name"><?php echo $commentDetail['firstname'].' '.$commentDetail['lastname'];?></p>
                      <p class="cmnt-level"><?php echo $commentDetail['levelName'];?></p>
                      <?php if(isset($commentDetail['aboutMe']) && $commentDetail['aboutMe'] != ''){?>
                          <p class="cmnt-level"><?php echo $commentDetail['aboutMe'];?></p>
                      <?php } ?>
                      <span class="show-time"><i class="clock-ico"></i><?php if($commentDetail['formattedTime'] == ''){echo 'Just now';}else {echo $commentDetail['formattedTime'];}?></span>
                    </div>
                    <p class="clr"></p>
            </a>

           <!--review box--> 
            <div class="cmnt-box-r">
              <p class="cmnt-review" id="commentFullTxt_<?=$commentDetail['msgId']?>"><?php echo $commentDetail['msgTxt'];?></p>
            </div> 
            <!--cmnts-options-->
            <?php if(!empty($commentDetail['overflowTabs'])){?>
            <div class="cmnts-options">
              <?php foreach($commentDetail['overflowTabs'] as $key=>$tabs){
                if($childType == 'Comment')
                {
                    $reportAbuseEntityType = 'comment';

                    if($tabs['label'] == 'Close'){
                        $GA_tapCloseDelete='CLOSE_ANSCOMMENTPAGE_WEBAnA';
                    }else if($tabs['label'] == 'Delete'){
                        $GA_tapCloseDelete='DELETE_ANSCOMMENTPAGE_WEBAnA';
                    }
                }
                else if($childType == 'Reply')
                {
                    $reportAbuseEntityType = 'discussionReply';

                    if($tabs['label'] == 'Close'){
                        $GA_tapCloseDelete='CLOSE_COMMENTREPLYPAGE_WEBAnA';
                    }else if($tabs['label'] == 'Delete'){
                        $GA_tapCloseDelete='DELETE_COMMENTREPLYPAGE_WEBAnA';
                    }
                }
                  

                ?>

              <?php if($tabs['label'] == 'Report Abuse') {?>
                  <a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapReportAbuse?>','<?=$GA_userLevel?>');fetchReportAbuseLayer('<?php echo $commentDetail['msgId'];?>','<?php echo $commentDetail['threadId']?>','<?php echo $reportAbuseEntityType;?>','<?php echo $raTrackingPageKeyId;?>')" id="reportAbuse" class="cmnts-edit" data-inline="true" data-rel="dialog" data-transition="fade"><?=$tabs['label']?></a></li>
			
              <?php } else if($tabs['label'] == 'Edit') {?>
                    <a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapEdit?>','<?=$GA_userLevel?>');makeCommentPostingLayer('<?php echo $commentDetail['threadId']?>','<?=$parentId?>','<?=$entityType?>','<?=$childType?>','<?=$commentDetail['msgId']?>')" data-inline="true" data-rel="dialog" data-transition="fade" class="cmnts-edit editComment" ><?=$tabs['label']?></a>

			<?php } elseif($tabs['label'] == 'Close' || $tabs['label'] == 'Delete')
              { ?>
              <a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapCloseDelete?>','<?=$GA_userLevel?>');showConfirmMessage('<?php echo $commentDetail['msgId'];?>','<?php echo $commentDetail['threadId'];?>','<?php echo $childType;?>','<?php echo $tabs['label'];?>','layer')" id="closeEntity" data-inline="true" data-rel="dialog" class="cmnts-edit" data-transition="fade"><?=$tabs['label']?></a>
              <?php }else {?>
          
               <a href="javascript:void(0);" class="cmnts-edit"><?=$tabs['label'];?></a>
              <?php }} ?>
            </div>
            <?php } ?>
       </div>
       <?php } ?>
     
     <div style="text-align: center; margin-top: 7px; margin-bottom: 10px; display: none;" id="loader-id">
        <img border="0" alt="" id="loadingImage1" class="small-loader" style="border-radius:50%" src="/public/mobile5/images/ShikshaMobileLoader.gif">
      </div>

      <div data-enhance="false">
            <input type="hidden" name="startIndex" id="startIndex" value="<?php echo $startIndex;?>" />
            <input type="hidden" name="countIndex" id="countIndex" value="<?php echo $countIndex;?>" />
            <input type="hidden" name="parentId" id="parentId" value="<?php echo $parentId;?>"/>
            
  </div>
</div>
  

  <script>
  var pageNo = <?php echo $pageNo;?>;
  /*$(document).ready(function()
    {
        closeOverFlowtabOnLayer();
    });
  
  */</script>

  
