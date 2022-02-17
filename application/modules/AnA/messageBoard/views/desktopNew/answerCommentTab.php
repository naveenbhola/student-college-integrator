<!-- answer Tab -->

<?php if($data['entityDetails']['queryType'] == 'Q'){
                if($data['entityDetails']['childCount']>1)
                    $childType = 'Answers';
                else
                    $childType = 'Answer';
          $GA_currentPage       = 'QUESTION DETAIL PAGE';
          $GA_Tap_On_Old_Sort   = 'SORT_OLDEST_QUEST';
          $GA_Tap_On_Latest_Sort  = 'SORT_LATEST_QUEST';
          $GA_Tap_On_Upvote_Sort  = 'SORT_UPVOTE_QUEST';
        }else{
                if($data['entityDetails']['childCount']>1)
                  $childType = 'Comments';
                else
                  $childType = 'Comment';
          $GA_currentPage         = 'DISCUSSION DETAIL PAGE';
          $GA_Tap_On_Old_Sort     = 'SORT_OLDEST_DISC';
          $GA_Tap_On_Latest_Sort  = 'SORT_LATEST_DISC';
          $GA_Tap_On_Upvote_Sort  = 'SORT_UPVOTE_DISC';
          }
          
    ?>
  <li class="module">
   <div class="drp-col">
      <h2 class="l-span"><?php echo $data['entityDetails']['childCount'].' '.$childType;?></h2>
      <?php if($data['entityDetails']['childCount']>3){?>
      <div class="r-span">
         Sort by
             <span class="opt-span" id="selectedSortOrder" onclick="$j('#sortOptionTab').toggle();">
              <?php if($referenceAnswerId > 0){
                  echo 'Select';}else{ echo $sortOrder;}?>
             </span>
             <span class="opt-ul" id="sortOptionTab">
               <ul>
                   <li><a href="javascript:void(0);" class="qSOT" id="sort_Upvotes" data-entityId="<?=$entityId?>" data-param="Upvotes" data-count="<?=$count?>" ga-attr="<?php echo $GA_Tap_On_Upvote_Sort;?>">Upvotes</a></li>
                   <li><a href="javascript:void(0);" class="qSOT" data-entityId="<?=$entityId?>" data-param="Latest" data-count="<?=$count?>" id="sort_latest" ga-attr="<?php echo $GA_Tap_On_Latest_Sort;?>">Latest</a></li>
                   <li><a href="javascript:void(0);" class="qSOT" data-entityId="<?=$entityId?>" data-param="Oldest" data-count="<?=$count?>" id="sort_oldest" ga-attr="<?php echo $GA_Tap_On_Old_Sort;?>">Oldest</a></li>
                   <?php if($referenceAnswerId > 0){?>
                   <li id="selectTab"><a href="javascript:void(0);" onclick="$j('#selectedSortOrder').text('Select');$j('#sortOptionTab').hide();" class="">Select</a></li>
                   <?php } ?>
               </ul>
             </span>
      </div>
      <?php } ?>
   </div>
 </li>