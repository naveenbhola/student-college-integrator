<?php if(!empty($data['linkedEntities']) && $entityType == 'question'){ ?>
  <div class="qst-l">
   <h2>Linked <?php echo ucfirst($entityType).'s';?></h2>
       <div class="related-que">
        
          <ul>
              <?php foreach($data['linkedEntities'] as $key=>$linkedEntity){
                $viewCountText = ($linkedEntity['viewCount']== 1) ? 'View' : 'Views';
                $childCountText = ($linkedEntity['childCount']== 1) ? 'Answer' : 'Answers';
                if($linkedEntity['viewCount'] >= 1000){
                 $totalViewCount =  round(($linkedEntity['viewCount']/1000),1).'k';
                  
                }else{
                   $totalViewCount = $linkedEntity['viewCount'];
                }
              ?>
                <li>
                   <a href="<?=$linkedEntity['url']?>" onclick="gaTrackEventCustom('QUESTION DETAIL PAGE','LINKED_QUEST_QUESTIONDETAIL_DESKAnA','<?php echo $GA_userLevel;?>');"><?=$linkedEntity['title']?></a>
                   <span><?php echo $linkedEntity['childCount'].' '.$childCountText;?></span>
                   <?php //if($linkedEntity['viewCount']>0){?>
                        <!-- <span><?php echo $totalViewCount.' '.$viewCountText; ?></span> -->
                   <?php //} ?>
               </li>
              <?php } ?>                                
          </ul>
      </div>
  </div>
<?php } ?>
  
  <?php 
  $relatedAlgoType = "RELATED_QUE_ALGO_1";
  if(isset($data['algoType']) && $data['algoType'] == 2){
      $relatedAlgoType = "RELATED_QUE_ALGO_2";
  }
  ?>
  <input type="hidden" id="relatedAlgoType" value="<?php  echo $relatedAlgoType; ?>"/>
  <?php if(!empty($data['related'])){  ?>
     <div class="qst-l">
       <h2>Related <?php echo ucfirst($entityType).'s';?></h2>
         <div class="related-que">
       
             <ul>
                <?php foreach($data['related'] as $key=>$relatedEntity){
                  $viewCountText = ($relatedEntity['viewCount']== 1) ? 'View' : 'Views';
                  if($entityType =='question' ){
                      $childCountText = ($relatedEntity['childCount']== 1) ? 'Answer' : 'Answers';
                      $GA_currentPage = 'QUESTION DETAIL PAGE';
                      $GA_Tap_Related = 'RELATED_QUEST_QUESTIONDETAIL_DESKAnA';
                  }else{
                      $childCountText = ($relatedEntity['childCount']== 1) ? 'Comment' : 'Comments';
                      $GA_currentPage = 'DISCUSSION DETAIL PAGE';
                      $GA_Tap_Related = 'RELATED_DISC_DISCUSSIONDETAIL_DESKAnA';
                  }

                  if($relatedEntity['viewCount'] >= 1000){
                    $totalViewCount =  round(($relatedEntity['viewCount']/1000),1).'k';       
                  }else{
                     $totalViewCount = $relatedEntity['viewCount'];
                  }

                ?>
                <li>
                   <a href="<?=$relatedEntity['url']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_Related;?>','<?php echo $GA_userLevel;?>');"><?=$relatedEntity['title']?></a>
                   <span><?php echo $relatedEntity['childCount'].' '.$childCountText;?></span>
                   <?php if($relatedEntity['viewCount'] > 0){?>
                        <span><?php echo $totalViewCount.' '.$viewCountText; ?></span>
                   <?php } ?>
               </li>
               <?php }?>
                                            
            </ul>
  </div>
     </div>
<?php } ?>
  <!--related col ends-->