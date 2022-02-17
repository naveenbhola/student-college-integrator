<div class="_card">
    <div class="counceller_profile">
       <div class="clear_max lft_fix">
          <p class="pic_col" style="background:url('<?php echo getImageUrlBySize($counsellorInfo['counselorImageUrl'],'160x160'); ?>')"> </p>
          <div class="counselr_expert">
              <h1 class="main_h1"><?php echo htmlentities(ucfirst($counsellorInfo['counsellor_name']));?></h1>
              <p class="expert_txt"><span>Expertise:<span> <?php echo htmlentities($counsellorInfo['counsellor_expertise']);?></p>
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
                <li class="">
                   <div class="vrfy_txt">
                       <p>Responsiveness<i class="info_tip"></i>
                            <span class="input-helper">
                            <span class="up-arrow"></span>
                            <span class="helper-text">Indicates counselor getting back to students as per requirements.</span>
                            </span>
                       </p>
                   </div>
                   <div class="bar_col">
                      <section class="myProgress">
                          <p class="myBar" style="width: <?php echo $counselorRatings['responseRating']*10?>%"></p>
                      </section>
                      <span class="rating-val"><?php echo ($counselorRatings['responseRating']-intval($counselorRatings['responseRating'])==0?intval($counselorRatings['responseRating']):$counselorRatings['responseRating']); ?>/10</span>
                   </div>
                </li>
             <?php }
             if($counselorRatings['knowledgeRating']>0){?>
                <li class="">
                   <div class="vrfy_txt">
                       <p>Knowledge<i class="info_tip"></i>
                           <span class="input-helper">
                            <span class="up-arrow"></span>
                            <span class="helper-text">Indicates counselors understanding on country, courses, applications & visa procedure.</span>
                           </span>
                       </p>
                   </div>
                   <div class="bar_col">
                      <section class="myProgress">
                         <p class="myBar" style="width: <?php echo $counselorRatings['knowledgeRating']*10?>%"></p>
                      </section>
                      <span class="rating-val"><?php echo ($counselorRatings['knowledgeRating']-intval($counselorRatings['knowledgeRating'])==0?intval($counselorRatings['knowledgeRating']):$counselorRatings['knowledgeRating']);?>/10</span>
                   </div>
                </li>
                <?php }
                if($counselorRatings['guidanceRating']>0){?>
                <li class="">
                   <div class="vrfy_txt">
                      <p>
                        Guidance <i class="info_tip"></i>
                        <span class="input-helper">
                           <span class="up-arrow"></span>
                          <span class="helper-text">Indicates how well the counselor guided students.</span>
                        </span>
                      </p>

                    </div>
                   <div class="bar_col">
                      <section class="myProgress">
                         <p class="myBar" style="width: <?php echo $counselorRatings['guidanceRating']*10?>%"></p>
                      </section>
                      <span class="rating-val"><?php echo ($counselorRatings['guidanceRating']-intval($counselorRatings['guidanceRating'])==0?intval($counselorRatings['guidanceRating']):$counselorRatings['guidanceRating']);?>/10</span>
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
