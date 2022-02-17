<form action="/applyHome/CounselorHomePage/saveCounselorReview" method="post" id="counselorReviewForm">
<div class="review_wrap">
 <div class="review_block clear_max">
   <h2 class="review_sectn">COUNSELOR REVIEW</h2>
     <?php 
        if(count($userRelatedCounselors)>1){
          $this->load->view('applyHome/counselorReviewPosting/counselorList'); 
        }
        if(count($userRelatedCounselors)==1){
          $singleCss = 1;
        }
        if(!$userCounselorId){
          $userCounselorId = array_keys($userRelatedCounselors)[0];
        }
     ?>
   <div class="counsel_layout">
     <!--counsel profile-->
     <?php if(!$userCounselorId && count($userRelatedCounselors)>1){ ?>
     <div class="c-pp-sec counsel-rvw <?php if($singleCss) echo 'single'?>" hidden>
     <?php }else{ ?>
     <div class="c-pp-sec counsel-rvw <?php if($singleCss) echo 'single'?>">
     <?php } ?>
       <div class="counsel_pic">
          <a class="avtarimageurl" style="background-image: url('<?php echo getImageUrlBySize($userRelatedCounselors[$userCounselorId]['counselorImageUrl'],'64x64'); ?>')"></a>
       </div>
       <div class="about_counsel rvw-arww">
          <h3 class="fnt_14_b clr1 counselor_name"><?php echo $userRelatedCounselors[$userCounselorId]['counsellor_name']; ?></h3>
          <p class="exprt_in">Expertise: <?php echo $userRelatedCounselors[$userCounselorId]['counsellor_expertise']; ?></p>
       </div>
     </div>
     <div class="rvw-err" id="counselorDropDown_err">
      <p>Please select counselor to proceed</p>
     </div>
       <input type="hidden" class="userCounselorId" name="userCounselorId" value="<?php echo $userCounselorId; ?>">
       <input type="hidden" class="trackingKeyId" name="trackingKeyId" value="<?php echo $trackingKeyId; ?>">
       <input type="hidden" class="showErrorPopupFlag" name="showErrorPopupFlag" value="<?php echo $showErrorPopupFlag; ?>">
      
      <!--feedback form-->
      <div class="feedback">
        <!--fields-->
        <?php 
        $quesNumber = 0;
        foreach ($counselorRelatedQuestions as $key => $value) {
          $quesNumber++;
        ?>
        <div class="field_container">
          <div class="feedback_col">
            <p class="fnt_16_sb clr3"><?php echo $quesNumber.'. '.$value['question']; ?></p>
            <?php 
            if(isset($value['subHead'])){
              echo '<span>'.$value['subHead'].'</span>';
            }
            ?>
          </div>
          <div class="fields_group  ponts_table clear_max">
              <div class="fields_block">
                <table class="">
                   <tr>
                    <?php 
                    for ($i=1; $i<=10; $i++) {
                    ?>
                    <td class="radio-button-container">
                       <div class="field_wrap">
                            <input type="radio" class="userRating" name="userRating<?php echo $quesNumber?>" id="up-<?php echo $key.$i?>" value="<?php echo $i?>">
                            <label for="up-<?php echo $key.$i?>" class="answer_label"><?php echo $i?></label>
                       </div>
                     </td>
                    <?php 
                    }
                    ?>
                   </tr>
                </table>
              </div>
              <div class="col-txt-container clear_max">
                 <div class="col-txt-left"><?php echo $value['minScale'] ?></div>
                 <div class="col-txt-right"><?php echo $value['maxScale'] ?></div>
              </div>
              <div class="rvw-err" id="userRating<?php echo $quesNumber?>_err">
                <p>This question requires an answer</p>
               </div>
           </div>
        </div>
        <?php 
        }
        ?>
        <div class="field_container lst">
            <div class="feedback_col">
                 <p class="fnt_16_sb clr3"> 4. Please write a detailed review on your counselor for the benefit of other students.  </p>
                 <span>This Review will be published on Shiksha Study Abroad website.</span>
             </div>
           <div class="fields_group clear_max">

              <div class="fields_block">
                <table>
                   <tbody><tr>
                     <td class="radio-button-container">
                       <div class="field_wrap">
                            <textarea id="counselorReviewText" name="counselorReviewText" rows="5" cols="80" placeholder="Enter Comments" class="txt_area"></textarea>
                       </div>
                     </td>

                   </tr>
                </tbody>
              </table>
              </div>
              <div class="rvw-err" id="counselorReviewText_err">
                <p>This question requires an answer</p>
               </div>
           </div>
         </div>
         <!--fileds form-->
      </div>
   </div>
 </div>
 <!--Service Review-->
 <div class="review_block clear_max">
   <h2 class="review_sectn">Shiksha Counseling Service Review</h2>
   <div class="counsel_layout">
     <div class="feedback fd-sp">
       <!--fields-->
       <?php 
        $quesNumber = 3;
        foreach ($counsellingServiceQuestions as $key => $value) {
          $quesNumber++;
        ?>
        <div class="field_container">
          <div class="feedback_col">
            <p class="fnt_16_sb clr3"><?php echo ($quesNumber+1).'. '.$value['question']; ?></p>
            <?php 
            if(isset($value['subHead'])){
              echo '<span>'.$value['subHead'].'</span>';
            }
            ?>
          </div>
          <div class="fields_group  ponts_table clear_max">
              <div class="fields_block">
                <table class="">
                   <tr>
                    <?php 
                    for ($i=1; $i<=10; $i++) {
                    ?>
                    <td class="radio-button-container">
                       <div class="field_wrap">
                            <input type="radio" class="userRating" name="userRating<?php echo $quesNumber?>" id="dwn-<?php echo $key.$i?>" value="<?php echo $i?>">
                            <label for="dwn-<?php echo $key.$i?>" class="answer_label"><?php echo $i?></label>
                       </div>
                     </td>
                    <?php 
                    }
                    ?>
                   </tr>
                </table>
              </div>
              <div class="col-txt-container clear_max">
                 <div class="col-txt-left"><?php echo $value['minScale'] ?></div>
                 <div class="col-txt-right"><?php echo $value['maxScale'] ?></div>
              </div>
              <div class="rvw-err" id="userRating<?php echo $quesNumber?>_err">
                <p>This question requires an answer</p>
               </div>
           </div>
        </div>
        <?php 
        }
        ?>
        <div class="field_container">
           <div class="feedback_col">
                <p class="fnt_16_sb clr3">7. Please write a detailed review on Shiksha Counseling Service for the benefit of other students. </p>
                <span>This is not a counselor specific question.</span>
            </div>

          <div class="fields_group clear_max">

             <div class="fields_block">
               <table>
                  <tbody><tr>
                    <td class="radio-button-container">
                      <div class="field_wrap">
                           <textarea id="counselingServiceReviewText" name="counselingServiceReviewText" rows="5" cols="80" placeholder="Enter Comments" class="txt_area"></textarea>
                      </div>
                    </td>

                  </tr>
               </tbody>
             </table>
             </div>
             <div class="rvw-err" id="counselingServiceReviewText_err">
                <p>This question requires an answer</p>
               </div>
          </div>
        </div>
        <!--fileds form-->
     </div>
   </div>
   <!--feedback form-->
 </div>
 <div class="review_block clear_max">
   <div class="submit-col">
     <div class="anynomus_txt"> 
         <span class="fnt_14_sb clr3 studentName">Your review will be posted as <?php echo ucwords($validateuser[0]['firstname'].' '.$validateuser[0]['lastname'])?></span>
         <span hidden class="fnt_14_sb clr3 anonymous">Your review will be posted as Anonymous</span>
        <div class="dis-inblk">
            <a class="chek__box">
              <input type="checkbox" name="anonymousFlag" id="anonymousFlag" class="inputChk" value="yes">
               <label class="f12-clr3 check__opt" for="anonymousFlag">
                     <i></i>Post as Anonymous</label>
            </a>
          </div>
    </div>
      <div class="rvw-err generic-er" id="generic_err">
      <p>You have one or more errors, please scroll up to top to check.</p>
      </div>
     <div class="tac sbmt-btn"><a href="javascript:;" id="submitBtn" status="ready" class="btn-prime sub_btm">Submit</a></div>
   </div>
 </div>
</div>
</form>
<script>
document.getElementById("counselorReviewForm").reset();
</script>