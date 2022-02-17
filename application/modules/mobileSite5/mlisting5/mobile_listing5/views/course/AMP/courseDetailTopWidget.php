<?php
    $extraInfo = getCourseTupleExtraData($courseObj,'mobileCoursePageAmp',false,'i-block color-6 f12 font-w6');
    $Answered_Test = ' Answered Questions';

    if($anaWidget['count'] == 1){
      $Answered_Test = ' Answered Question'; 
    }
?>
 <div class="data-card m-5btm">
             <div class="card-cmn color-w">
                 <p><a class="block l-16 f12 color-b m-5btm font-w4" href="<?php echo $instituteURL; ?>" ><?=($instituteName);?></a>
                    <?php
                      /*if($validateuser != 'false') {
                         if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
                          if(!empty($courseIsPaid)){
                              echo '<span class="upcoming-tap">PAID</span>';
                          }else{
                              echo '<span class="upcoming-tap-greentag">FREE</span>';
                          }
                         }
                      }                */
                  ?>
                   <?php if($isMultilocation){ ?>
                      <a class="color-b f12 i-block v-arr font-w6" on="tap:change-location" role="button" tabindex="0">Change branch<i class="arw-icn"></i></a>
                  <?php } ?>
              </p>
                 <h1 class="color-3 f16 font-w6 word-break"><?= ($courseName);?></h1>
                 <ul class="cl-ul">
                     <?=$extraInfo;?>
                 </ul>
                   <?php
                    if(!empty($aggregateReviewsData)){
                      $url = $instituteObj->getAllContentPageUrl('reviews').'?course='.$courseId;

                      ?>
                      <div class="new_rating">
                        <?php $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidgetAmp", array('allReviewsUrl' => $url, 'aggregateReviewsData' => $aggregateReviewsData, 'reviewsCount' => $reviewWidget['count'],'isPaid' => $instiuteIsPaid,'forAnsweredQuestions'=>1,'id' =>'course'.$courseObj->getId() )); ?>
                      <?php
                      if($anaWidget['count'] > 0){ 
                      echo "| ";
                      }
                    }
                    if($anaWidget['count'] > 0){ 
                    ?>
                        <a class="view_rvws qstn ga-analytic" data-vars-event-name="Header_AnsweredQuestions" href="<?php echo $anaWidget['allQuestionURL']?>" >
                          <i class="qstns_ico"></i><?php echo formatNumber($anaWidget['count'])?><?php echo $Answered_Test?>
                        </a>
                    <?php if(!empty($aggregateReviewsData)){ ?>
                    </div>
                    <?php  }
                    }
                   ?>
                 <div class="">
                     <ul class="m-15top cli-i">
                        <?php $elementsToShow = array();

                            foreach($recognitionData['approvals'] as $rec){
                                $str = "<span class=\"font-w6 f12 color-3\">".$rec['name']." Approved</span>";
                                if(count($recognitionData['approvals']) > 1){
                                    $str .= "<a class=\"block color-b f12 font-w6 ga-analytic\" data-vars-event-name='APPROVED_MORE' on=\"tap:approval-more-data\" role=\"button\" tabindex=\"0\">+".(count($recognitionData['approvals'])-1)." more</a>";
                                }else{
                                    $str .= "<a class=\"color-b f12 font-w6\" on=\"tap:approval-more-data\" role=\"button\" tabindex=\"0\"><i class=\"cmn-sprite clg-info i-block v-mdl\"></i></a>";
                                }
                                $elementsToShow[] = $str;
                                break;
                            }
                            foreach($recognitionData['institute'] as $rec){
                                $str = "<span class=\"font-w6 f12 color-3\">".$rec['name']." Accredited</span>";
                                if(count($recognitionData['institute']) > 1){
                                    $str .= "<a class=\"block color-b f12 font-w6 ga-analytic\" data-vars-event-name='ACCREDITED_MORE' on=\"tap:institute-more-data\" role=\"button\" tabindex=\"0\">+".(count($recognitionData['institute'])-1)." more</a>";
                                }else{
                                    $str .= "<a class=\"color-b f12 font-w6\" on=\"tap:institute-more-data\" role=\"button\" tabindex=\"0\"><i class=\"cmn-sprite clg-info i-block v-mdl\"></i></a>";
                                }
                                $elementsToShow[] = $str;
                                break;
                            }
                            if(!empty($fees)) {
                                $str = "<p class=\"pos-rl color-9 f12 \">Total Fee ";
                                if(!empty($fees['feesTooltipBasicInfo'])){
                                     $str .= "<a class=\"pos-rl\" on=\"tap:fee-more-data\" role=\"button\" tabindex=\"0\"><i class=\"cmn-sprite clg-info i-block v-mdl\"></i></a>";
                                }
                                $str .= "</p><span class=\"block color-3 f13 font-w6\">".$fees['feesInfo']['general']['totalFeesBasicSection']."</span>";
                                $elementsToShow[] = $str;
                            }

                            $medium = $courseObj->getMediumOfInstruction();
                            $showCount = false;
                            if(count($medium) > 1){
                                $showCount = true;
                            }
                            $fmedium = $medium[0];
                            if($fmedium && $fmedium->getName() != "English" || $showCount){
                                if(!empty($fmedium)){
                                    $str = "<p class=\"color-9 pos-rl f12\">Medium <span class=\"block color-3 f12 font-w6\">".$fmedium->getName();
                                    $str .= "</span></p>";
                                    if($showCount){
                                        $str .= "<span> <a id='mediumShowMore' data-vars-event-name='MEDIUM_MORE' class='block color-b f12 font-w6 ga-analytic' on='tap:medium-more-data'>+".(count($medium)-1)." More</a></span>";
                                    }

                                    $elementsToShow[] = $str;
                                }
                            }
                            if($difficulty = $courseObj->getDifficultyLevel()->getName()){
                                $str = "<p class=\"color-9 pos-rl f12\">Difficulty Level <span class=\"block color-3 f12 font-w6\">".$difficulty."</span></p>";
                                $elementsToShow[] = $str;
                            }

                            echo "<li class='v-top'>";
                            foreach($elementsToShow as $element){
                                echo '<div class="tab-cell-b t-width-b">'.$element.'</div>';
                                $counter+=1;
                                if($counter %2 == 0){
                                    echo '</li><li class="v-top">';
                                }
                            }
                            echo "</li>";
                        ?>
                     </ul>

                     <?php
                     //Now one liner details start
                        $elementsToShow = array();
                        $courseRankData = $courseRankTopWidget['courseRankData'];
                        $rank = $courseRankData[0];
                        if($rank){
                            $rankingAnchorTag = '<a href="'.$rank['url'].'" >'.$rank['source_name'].' '.$rank['source_year'].'</a>';
                            $rankString = "Ranked ".$rank['rank']." by ".$rankingAnchorTag;
                        }
                        if(count($courseRankData)>1){
                            $rankString .= " <a class=\"block color-b f12 font-w6 ga-analytic\" data-vars-event-name='RANKED_MORE' on=\"tap:rank-more-data\" role=\"button\" tabindex=\"0\">+".(count($courseRankData)-1)." more</a>";
                        }
                        $elementsToShow[] = $rankString;
                        if(!empty($affiliatedUniversityName)){
                            $targetAttr = '';
                            if($affiliatedUniversityScope == 'abroad') {
                                $targetAttr = 'target="_blank"';
                            }
                            $affiliatedHref = 'href="'.$affiliatedUniversityUrl.'"';

                            $str = "Affiliated To ";
                            if(!empty($affiliatedUniversityUrl)) {
                                $elementsToShow[] = $str.'<a class="color-b f12 font-w6" '.$targetAttr.'  '.$affiliatedHref.'>'.$affiliatedUniversityName.'</a>';
                            }
                            else {
                                $elementsToShow[] = $str.$affiliatedUniversityName;
                            }
                        }
                        $counter = 0;?>

                        <div class="div-border">
                        <?php foreach($elementsToShow as $element){
                          if(!empty($element)){
                            echo "<div class='f12 color-3 font-w6 m-5top'>".$element."</div>";
                          }
                        }
                     ?>
                   </div>
                      </div>
                      
                     <?php if(!empty($courseDates)) {?>
                        <div class="dot-div m-top">
                            <?php
                                if($courseDates['type'] == 'onlineForm') {
                                    $ctaName = 'Start Application';
                                    $ctaLink = "emailResults('".$courseObj->getId()."', '".base64_encode($courseObj->getInstituteName())."' , '".$courseDates['internalFlag']."' , '". MOBILE_NL_COURSE_PAGE_TOP_APPLY_OF."');";
                                    $ctaGA = "APPLYNOW";
                                    $ctaId = "startApp".$courseObj->getId();
                                    $dateText = $courseDates['eventName'];
                                }
                                else if($courseDates['type'] == 'importantDates') {
                                    //$ctaName = 'View all dates';
                                    $ctaLink = "animateTodiv('#admissions')";
                                    $ctaGA   = "VIEWALLDATES";
                                    $dateText = $courseDates['eventName'];
                                }

                            ?>
                            <h2 class="f13 color-6 font-w6 pad8 word-break"><?php echo $dateText;?><span class="f14 color-3 font-w6 pad3"><?=$courseDates['date']?></span>
                              <?php if($courseDates['type'] == 'onlineForm') {?>
                                     <section class="wd50 block" amp-access="NOT validuser" amp-access-hide>
                                          <a class="oafcta-btn" data-vars-event-name="<?php echo $ctaGA;?>"
                                            href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getRegistrationAmpPage?listingId=<?=$courseId;?>&actionType=applynow&fromwhere=coursepage&pos=top"  ><i class="d-arrow"></i><?=$ctaName;?>
                                          </a>

                                      </section>
                                      <section class="wd50 block" amp-access="validuser" amp-access-hide tabindex="0">
                                        <a class="oafcta-btn"
                                            href="<?=$courseObj->getURL();?>?actionType=applynow&pos=top"><i class="d-arrow"></i><?=$ctaName;?>
                                        </a>

                                      </section>
                              <?php }elseif(!empty($ctaName)) { ?>
                                <a class="color-b f14 font-w6 ga-analytic" data-vars-event-name="<?php echo $ctaGA;?>"><?=$ctaName;?></a>
                                <?php } ?>
                            </h2>
                        </div>
                    <?php } ?>

                    <section class="p1" amp-access="shortlisted" amp-access-hide>
                      <a class="ga-analytic" href="<?=$courseObj->getURL();?>?actionType=shortlist&course=<?=$courseId;?>" data-vars-event-name="SHORTLIST">
                          <p class="btn btn-primary color-o color-f f14 font-w7 m-15top" tabindex="0" role="button"><i class="shortl-list active"></i>Shortlisted</p>
                      </a>
                    </section>

                    <section class="p1" amp-access="NOT validuser AND NOT shortlisted" amp-access-hide>
                      <a class="ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingId=<?=$courseId?>&actionType=shortlist&fromwhere=coursepage" data-vars-event-name="SHORTLIST">
                          <p class="btn btn-primary color-o color-f f14 font-w7 m-15top" tabindex="0" role="button"><i class="shrt-list"></i>Shortlist</p>
                      </a>
                    </section>
                    <section class="p1" amp-access="validuser AND NOT shortlisted" amp-access-hide tabindex="0">
                      <a class="ga-analytic" href="<?=$courseObj->getURL();?>?actionType=shortlist" data-vars-event-name="SHORTLIST">
                          <p class="btn btn-primary color-o color-f f14 font-w7 m-15top" tabindex="0" role="button"><i class="shrt-list"></i>Shortlist</p>
                      </a>
                    </section>
                     <!-- <a class="btn btn-primary color-o color-f f14 font-w7 m-15top"><i class="shrt-list"></i>Shortlist</a> -->
                 </div>
             </div>
         </div>

    <amp-lightbox class="" id="rank-more-data" layout="nodisplay" scrollable>
     <div class="lightbox">
       <a class="cls-lightbox f25 color-f font-w6" on="tap:rank-more-data.close" role="button" tabindex="0">&times;</a>
       <div class="m-layer">
           <div class="min-div color-w">
             <p class="f14 color-3 font-w6 pad10">Course Rankings</p>
              <ul class="pad10 pt0 rnk-br">
                <?php foreach($courseRankData as $rank){ ?>
                        <li class="f12 color-6 m-5btm">Ranked <?=$rank['rank'];?> by <a href="<?=$rank['url']?>"><?=($rank['source_name']." ".$rank['source_year']);?></a></li>
                <?php } ?>
              </ul>
              <ul class="pad10 pt0">
                <?php
                    $courseRankInterlinkData = $courseRankTopWidget['courseRankInterlinkData'];
                    if(!empty($courseRankInterlinkData)) {
                        foreach ($courseRankInterlinkData as $courseRankInterlinkValue) { ?>
                          <li class="f12 color-6 m-5btm"><?=$courseRankInterlinkValue['anchorText'];?> <a href="<?=$courseRankInterlinkValue['url'];?>"><?=$courseRankInterlinkValue['locationName'];?></a></li>
                    <?php }
                    }
                ?>
              </ul>
          </div>
       </div>
     </div>
   </amp-lightbox>
   <amp-lightbox class="" id="medium-more-data" layout="nodisplay" scrollable>
     <div class="lightbox">
       <a class="cls-lightbox f25 color-f font-w6" on="tap:medium-more-data.close" role="button" tabindex="0">&times;</a>
       <div class="m-layer">
           <div class="min-div color-w catg-lt">
             <p class="f14 color-3 bck1 pad10 font-w6">Mediums of Education</p>
              <ul class="color-6">
                <?php foreach($medium as $tmed){ ?>
                        <li class="f12 color-6 m-5btm"><?php echo $tmed->getName(); ?></li>
                <?php } ?>
              </ul>
          </div>
       </div>
     </div>
   </amp-lightbox>
   <amp-lightbox class="" id="approval-more-data" layout="nodisplay" scrollable>
     <div class="lightbox">
       <a class="cls-lightbox f25 color-f font-w6" on="tap:approval-more-data.close" role="button" tabindex="0">&times;</a>
       <div class="m-layer">
           <div class="min-div color-w pad10">
              <ul>
                <?php foreach($recognitionData['approvals'] as $rec){ ?>
                        <li class="f12 color-6 m-5btm"><?=$rec['name']?> : <?=$rec['tooltip']?> </li>
                <?php } ?>
              </ul>
          </div>
       </div>
     </div>
   </amp-lightbox>
   <amp-lightbox class="" id="institute-more-data" layout="nodisplay" scrollable>
     <div class="lightbox">
       <a class="cls-lightbox f25 color-f font-w6" on="tap:institute-more-data.close" role="button" tabindex="0">&times;</a>
       <div class="m-layer">
           <div class="min-div color-w pad10">
              <ul>
                <?php foreach($recognitionData['institute'] as $rec){ ?>
                        <li class="f12 color-6 m-5btm"><?=$rec['name']?> : <?=$rec['tooltip']?> </li>
                <?php } ?>
              </ul>
          </div>
       </div>
     </div>
   </amp-lightbox>
     <amp-lightbox class="" id="fee-more-data" layout="nodisplay" scrollable>
     <div class="lightbox">
       <a class="cls-lightbox f25 color-f font-w6" on="tap:fee-more-data.close" role="button" tabindex="0">&times;</a>
       <div class="m-layer">
           <div class="min-div color-w pad10">
           <p class="m-btm f14 color-3 font-w6">Total Fee</p>
              <ul>
                    <li class="f12 color-6 m-5btm"><?=$fees['feesTooltipBasicInfo'];?></li>
              </ul>
          </div>
       </div>
     </div>
   </amp-lightbox>
     <amp-lightbox class="" id="duration-more-data" layout="nodisplay" scrollable>
     <div class="lightbox">
       <a class="cls-lightbox f25 color-f font-w6" on="tap:duration-more-data.close" role="button" tabindex="0">&times;</a>
       <div class="m-layer">
           <div class="min-div color-w pad10">
           <p class="m-btm f14 color-3 font-w6">Duration</p>
              <ul>
                    <li class="f12 color-6 m-5btm"><?php echo DURATION_TOOLTIP; ?></li>
              </ul>
          </div>
       </div>
     </div>
   </amp-lightbox>
