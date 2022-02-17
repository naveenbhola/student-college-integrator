<div class="review_tuple clear_max">
    <div class="set_height">
      <div class="layout_wrap">
        <?php 
        $linkOpen = $linkClose = '';
          if($value['anonymousFlag']==1){
              $imageUrl = $studentInfo['userBasicInfo']['anonymous']['avtarimageurl'];
          }
          else if(empty($studentInfo['userBasicInfo'][$value['userId']]['avtarimageurl'])){
              $linkOpen = '<a href="'.$studentInfo['userBasicInfo'][$value['userId']]['url'].'">';            
              $imageUrl = $studentInfo['userBasicInfo'][0]['avtarimageurl'];   
              $linkClose= '</a>';
          }
          else {
              $linkOpen = '<a href="'.$studentInfo['userBasicInfo'][$value['userId']]['url'].'">';
              $imageUrl = $studentInfo['userBasicInfo'][$value['userId']]['avtarimageurl'];
              $linkClose= '</a>';
          }
          ?> 
          <?php echo $linkOpen; ?>
            <p class="pic-wrap"style="background-image: url('<?php echo getImageUrlBySize($imageUrl,'small');?>')"></p>
          <?php echo $linkClose; ?>
      </div>
      <div class="text_wraper">
        <div class="user_t clear_max">
            <p class="fnt1_4_3">
              <span class="stu-cmt"><?php 
                if($value['anonymousFlag']==1){
                    echo 'Anonymous';
                }
                else if($value['userId']==0){
                    echo htmlentities($value['StudentName']);
                }else{
                    echo $linkOpen;
                    echo htmlentities($studentInfo['userBasicInfo'][$value['userId']]['firstname'].' '.$studentInfo['userBasicInfo'][$value['userId']]['lastname']); 
                    echo $linkClose;
                }?>
              </span>
                <span class="rating_block"><?php echo $value['overallRating'];?>/10</span>
            </p> 
        </div>
        <div><p class="fnt1_4_6"><?php
                        if($value['anonymousFlag']!=1){
                          if(!empty($studentInfo['userExams'][$value['userId']]['examName'])){
                              echo $studentInfo['userExams'][$value['userId']]['examName'].' Score: '.$studentInfo['userExams'][$value['userId']]['marks'];
                          }
                          if(!empty($studentInfo['userExams'][$value['userId']]['examName'])
                              && !empty($studentInfo['userAdmittedUniversities'][$value['userId']])){
                              echo ', ';
                          }
                          if(!empty($studentInfo['userAdmittedUniversities'][$value['userId']])){
                              echo 'Admitted to '.  htmlentities($studentInfo['userAdmittedUniversities'][$value['userId']]);
                          }
                        }
                          ?>
            </p>
        </div>
      </div>

    </div>
    <div class="text_desc"><p class="fnt_1_4_7"><?php echo htmlentities($value['reviewText']);?></p></div>
    <?php if($userEligibleForReviewDeletion[$value['id']]){?>
    <a href="javascript:void(0)" class="new-left deleteReview" reviewId="<?php echo $value['id'];?>">Delete Review</a>
    <?php }?>
    <span class="r_date">Reviewed <?php echo date('d M y',  strtotime($value['addedAt']));?></span>
</div>
