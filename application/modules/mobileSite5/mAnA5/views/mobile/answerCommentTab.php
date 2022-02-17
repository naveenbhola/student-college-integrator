
  <div class="cmnts-box">
  <?php if($data['entityDetails']['queryType'] == 'Q'){

                $GA_currentPage ='QUESTION DETAIL PAGE';
                $GA_sortLatest = 'SORT_LATEST_QUEST_QUESTIONDETAIL_WEBAnA';
                $GA_sortOldest ='SORT_OLDEST_QUEST_QUESTIONDETAIL_WEBAnA';
                $GA_sortUpvote = 'SORT_UPVOTE_QUEST_QUESTIONDETAIL_WEBAnA';
                if($data['entityDetails']['childCount']>1)
                    $childType = 'ANSWERS';
                else
                    $childType = 'ANSWER';
        }else{
                $GA_currentPage ='DISCUSSION DETAIL PAGE';
                $GA_sortLatest = 'SORT_LATEST_DISC_DISCUSSIONDETAIL_WEBAnA';
                $GA_sortOldest ='SORT_OLDEST_DISC_DISCUSSIONDETAIL_WEBAnA';
                $GA_sortUpvote = 'SORT_UPVOTE_DISC_DISCUSSIONDETAIL_WEBAnA';
                if($data['entityDetails']['childCount']>1)
                  $childType = 'COMMENTS';
                else
                  $childType = 'COMMENT';
          }
    ?>
      <h2 class=""><?php echo $childType.' ('.$data['entityDetails']['childCount'].')'?></h2>
     <?php if($data['entityDetails']['childCount']>3){?>
      <span class="right-span">Sort by: 
          <div class="qa-dropdown">
             <a href="javascript:void(0);" onclick="$('#sortOptionTab').toggle();" id="sortingDiv">
                 <div class="arrow"><i class="caret"></i></div>
                 <span class="display-area" id="selectedSortOrder"><?php if($referenceAnswerId > 0){

                  echo 'Select';}else{ echo $sortOrder;}?></span>
             </a>
              <div class="drop-layer" id="sortOptionTab" style="display:none;">
                <ul>
                    <li><a href="javascript:void(0);" id="sort_Upvotes" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_sortUpvote?>','<?=$GA_userLevel?>');sortEntityData('<?=$entityId?>','<?=$start?>','<?=$count?>','Upvotes')">Upvotes</a></li>
                    <li><a href="javascript:void(0);" id="sort_latest" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_sortLatest?>','<?=$GA_userLevel?>');sortEntityData('<?=$entityId?>','<?=$start?>','<?=$count?>','Latest')">Latest</a></li>
                    <li><a href="javascript:void(0);" id="sort_oldest" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_sortOldest?>','<?=$GA_userLevel?>');sortEntityData('<?=$entityId?>','<?=$start?>','<?=$count?>','Oldest')">Oldest</a></li>
                     <?php if($referenceAnswerId > 0){?>
                     <li><a href="javascript:void(0);" id="selectTab" onclick="$('#selectedSortOrder').text('Select');$('#sortOptionTab').hide();">Select</a></li>
                     <?php } ?>
                </ul>
              </div>
          </div>
         
       </span>
       <?php }?>
  </div>
            
  