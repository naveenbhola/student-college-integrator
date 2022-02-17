<div class="_card counceller_profile">
    <div class="">
       <div class="clear_max u_col">
           <p class="pic_col" style="background:url('<?php echo getImageUrlBySize($counsellorInfo['counselorImageUrl'],'medium'); ?>')"> </p>
           <div class="counselr_expert">
             <h1 class="main_h1"><?php echo htmlentities(ucfirst($counsellorInfo['counsellor_name']));?></h1>
             <p class="expert_txt">Expertise: <?php echo htmlentities($counsellorInfo['counsellor_expertise']);?></p>
           </div>
       </div>
       <div class="counselr_col clear_max overall_rating">
           <?php if($counselorRatings['overAllRating']>0){?>
           <div class="score">
              <p class="rating_titl">Overall Rating</p>
              <p class="avg_rating"><?php echo ($counselorRatings['overAllRating']-intval($counselorRatings['overAllRating'])==0?intval($counselorRatings['overAllRating']):$counselorRatings['overAllRating']); ?></p>
           </div>
           <?php } ?>
           <div class="counselr_expert">
              <ul class="rating_bar">
                <?php if($counselorRatings['responseRating']>0){ ?>
                <li  class="">
                    <div class="vrfy_txt"><p>Responsiveness<i class="helpText"></i></p>
                        <span class="input-helper">
                            <span class="up-arrow"></span>
                            <span class="helper-text">Indicates counselor getting back to students as per requirements.</span>
                        </span>
                    </div>
                   <div class="bar_col">
                    <?php echo ($counselorRatings['responseRating']-intval($counselorRatings['responseRating'])==0?intval($counselorRatings['responseRating']):$counselorRatings['responseRating']); ?>/10
                  </div>
                </li>
                <?php }
             if($counselorRatings['knowledgeRating']>0){?>
                <li  class="">
                   <div class="vrfy_txt"><p>Knowledge<i class="helpText"></i></p>
                       <span class="input-helper">
                            <span class="up-arrow"></span>
                            <span class="helper-text">Indicates counselors understanding on country, courses, applications & visa procedure.</span>
                        </span>
                   </div>
                   <div class="bar_col">
                   <?php echo ($counselorRatings['knowledgeRating']-intval($counselorRatings['knowledgeRating'])==0?intval($counselorRatings['knowledgeRating']):$counselorRatings['knowledgeRating']);?>/10
                  </div>
                </li>
                 <?php } 
                if($counselorRatings['guidanceRating']>0){?>
                <li  class="">
                   <div class="vrfy_txt"><p>Guidance<i class="helpText"></i></p>
                       <span class="input-helper">
                            <span class="up-arrow"></span>
                            <span class="helper-text">Indicates how well the counselor guided students.</span>
                        </span>
                   </div>
                    
                   <div class="bar_col">
                    <?php echo ($counselorRatings['guidanceRating']-intval($counselorRatings['guidanceRating'])==0?intval($counselorRatings['guidanceRating']):$counselorRatings['guidanceRating']);?>/10
                  </div>
                </li>
                <?php }?>
              </ul>
           </div>
       </div>
    </div>
    <div class="about_col">
       <p><?php echo htmlentities($counsellorInfo['counsellor_bio']);?></p>
    </div>
</div>