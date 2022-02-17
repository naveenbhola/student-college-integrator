<div class="apply_col">
  <section class="page_width">
    <h2 class="txt_cntr fnt_28">Meet some of our counselors</h2>
    <p class="txt_cntr sub_txt">Get access to India's leading counselors from the comfort of your home</p>
    <div class="counselors_dtls clearfix">
      <div class="clearfix">
        <ul class="slider-list">
          <li class="clearfix active">
          <?php
          $liHtml2 = '<li class="clearfix inactiveSlide">';
          foreach ($counselorWidgetData as $key => $value) {
            $studentName = 'Anonymous';
            if($counselorReviewData[$value['counselorId']]['anonymousFlag'] != 1){
              $studentName = ucwords(strtolower($studentInfo['userBasicInfo'][$counselorReviewData[$value['counselorId']]['userId']]['firstname'].' '.$studentInfo['userBasicInfo'][$counselorReviewData[$value['counselorId']]['userId']]['lastname']));
            }
            $studentName = trim($studentName);
            if(empty($studentName)){
              $studentName = ucwords(strtolower($counselorReviewData[$value['counselorId']]['StudentName']));
            }
            if($key>1 && $key%2==0){
              echo '</li>'.$liHtml2;
            }
          ?>
          <a cname="<?=$value['counsellor_name']?>" class="random_box flt__lft slide fade slide--up" href="<?php echo SHIKSHA_STUDYABROAD_HOME.$value['seoUrl'] ?>">
                <div class="cnslr_prf clearfix">
                   <div class="cnslr_img flt__lft">
                      <img src="<?php echo MEDIAHOSTURL.resizeImage($value['counsellor_image'],'160x160') ?>" title="<?php echo $value['counsellor_name']; ?>" alt="<?php echo $value['counsellor_name']; ?>">
                   </div>
                   <div class="cnlsr_rating clearfix">
                     <div class="clearfix">
                       <div class="cnslr_name">
                         <p class="fnt_16"><?php echo explode(' ',$value['counsellor_name'])[0]; ?></p>
                         <p class="fnt_14"><?php echo $value['counsellor_expertise']; ?></p>
                       </div>
                       <div class="score flt__right">
                          <p class="fnt_12">Overall Rating</p>
                          <p class="fnt_36"><?php echo $value['counselorRatings']['overAllRating'] ?></p>
                       </div>
                     </div>
                      <div class="clearfix">
                        <div class="cns-list">

                          <div class="cnslr-list clearfix">
                              <label>Responsiveness
                                <div class="rel-inline">
                                  <span class="info-ico"></span>
                                  <span class="input-helper">
                                    <span class="up-arrow"></span>
                                    <span class="helper-text">Indicates counselor getting back to students as per requirements.</span>
                                  </span>
                                </div>
                              </label>
                              <section class="prgs-dv">
                                  <div class="myProgress">
                                      <p class="myBar" style="width: <?php echo $value['counselorRatings']['responseRating']*10 ?>px;"></p>
                                  </div>
                                  <strong><?php echo $value['counselorRatings']['responseRating'] ?>/10</strong>
                              </section>
                          </div>
                          <div class="cnslr-list clearfix">
                              <label>Knowledge
                                <div class="rel-inline">
                                  <span class="info-ico"></span>
                                  <span class="input-helper">
                                    <span class="up-arrow"></span>
                                    <span class="helper-text">Indicates counselor's understanding on country, courses, applications & visa procedure.</span>
                                  </span>
                                </div>
                              </label>
                              <section class="prgs-dv">
                                  <div class="myProgress">
                                      <p class="myBar" style="width: <?php echo $value['counselorRatings']['knowledgeRating']*10 ?>px;"></p>
                                  </div>
                                  <strong><?php echo $value['counselorRatings']['knowledgeRating'] ?>/10</strong>
                              </section>
                          </div>
                          <div class="cnslr-list clearfix">
                              <label>Guidance
                                <div class="rel-inline">
                                  <span class="info-ico"></span>
                                  <span class="input-helper">
                                    <span class="up-arrow"></span>
                                    <span class="helper-text">Indicates how well the counselor guided students.</span>
                                  </span>
                                </div>
                              </label>
                              <section class="prgs-dv">
                                  <div class="myProgress">
                                      <p class="myBar" style="width: <?php echo $value['counselorRatings']['guidanceRating']*10 ?>px;"></p>
                                  </div>
                                  <strong><?php echo $value['counselorRatings']['guidanceRating'] ?>/10</strong>
                              </section>
                          </div>
                      </div>
                      </div>
                   </div>

                </div>
                <!--student reviews-->
                <div class="stu_rvws clearfix">
                  <p class="fnt_16">Student Reviews <span>(<?php echo $counselorReviewData[$value['counselorId']]['reviewCount']; ?> Reviews)</span></p>
                  <div class="clearfix main_tuple">
                     <div class="pic_block">
                       <div class="pic_circle">
                       <?php
                        if($counselorReviewData[$value['counselorId']]['anonymousFlag']==1){
                            $imageUrl = $studentInfo['userBasicInfo']['anonymous']['avtarimageurl'];
                        }
                        else if(empty($studentInfo['userBasicInfo'][$counselorReviewData[$value['counselorId']]['userId']]['avtarimageurl'])){
                            $imageUrl= $studentInfo['userBasicInfo'][0]['avtarimageurl'];
                        }
                        else {
                            $imageUrl= $studentInfo['userBasicInfo'][$counselorReviewData[$value['counselorId']]['userId']]['avtarimageurl'];
                        }
                        ?>
                        <p class="pic-wrap" style="background-image: url('<?php echo getImageUrlBySize($imageUrl,'medium');?>')"></p>
                       </div>
                     </div>
                     <div class="content_wrap">
                        <p class="fnt_14"><?php echo $studentName; ?></p>
                        <p class="fnt_14">
                          <?php
                          if($counselorReviewData[$value['counselorId']]['anonymousFlag']!=1){
                            if(!empty($studentInfo['userExams'][$counselorReviewData[$value['counselorId']]['userId']]['examName'])){
                                echo $studentInfo['userExams'][$counselorReviewData[$value['counselorId']]['userId']]['examName'].' Score: '.$studentInfo['userExams'][$counselorReviewData[$value['counselorId']]['userId']]['marks'];
                            }
                            if(!empty($studentInfo['userExams'][$counselorReviewData[$value['counselorId']]['userId']]['examName'])
                                && !empty($studentInfo['userAdmittedUniversities'][$counselorReviewData[$value['counselorId']]['userId']])){
                                echo ', ';
                            }
                            if(!empty($studentInfo['userAdmittedUniversities'][$counselorReviewData[$value['counselorId']]['userId']])){
                                echo 'Admitted to '.  htmlentities($studentInfo['userAdmittedUniversities'][$counselorReviewData[$value['counselorId']]['userId']]);
                            }
                          }
                          ?>
                        </p>
                        <p class="brief_rvw fnt_14"><?php echo ucfirst(formatArticleTitle($counselorReviewData[$value['counselorId']]['reviewText'],130)); ?></p>
                     </div>
                  </div>
                </div>
                <!--view profile-->
                <div class="txt_cntr view_prf">
                   <span>View Counselor Profile <i></i> </span>
                </div>
            </a>
          <?php
          }
          ?>
          </li>
        </ul>
      </div>
      <div>
          <ul class="cntr-lst">
          <?php
          for($i=1; $i<=(count($counselorWidgetData)/2);$i++){
          ?>
            <li class="<?php echo ($i==1)?'active':''?>"><em class="p_bar"></em></li>
            <?php 
          }
            ?>
          </ul>
      </div>
    </div>
  </section>
</div>