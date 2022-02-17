<?php 
              $instituteId = $pageData->getInstituteId();
              $instituteObject = $instituteInfo[$instituteId];
              $courseId = $pageData->getCourseId();
              if(!empty($instituteObject)){
                if(!empty($courseLocationInfo[$courseId]) ) {
                  $courseMainCity =$courseLocationInfo[$courseId]['cityId'];
                  $instituteDisplayLocationParams = NULL; //check if multilocation
                  $instituteMainLocation = $instituteObject->getMainLocation();
                  if(!empty($instituteMainLocation)) {
                      if($courseMainCity!=$instituteMainLocation->getCityId()){
                          $instituteDisplayLocationParams = array('city' => $courseMainCity);
                      }
                  }
                  else {
                    continue;
                  }

          ?>
            <section pn="<?php echo $pageNumber+1; ?>" instId="<?php echo $instituteId; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?> ranking__touple clear__float">
                <article class="fit__in__block flt__lft rank__section">
                  <p class="fit__to__cntr f24__bold clr__1">
                  <?php 
                  $rank = $pageData->getSourceWiseRank();
                  if($rank[$currentSourceId]['rank']>0){echo $rank[$currentSourceId]['rank'];} else{ echo "-";}?></p>
                  <?php if($previousRankFlag){ ?>
                  <p class="fit__to__cntr f11__normal clr__6">
                  <?php if($rank[$previousSourceId]['rank']>0){echo $rank[$previousSourceId]['rank']." in ".$previousYear;}?>
                  </p>
                <?php } ?>
                </article>
                <div class="flt__right rank__dtl">
                  <a class="fit__in__block rank__clg" href="<?php echo add_query_params($instituteObject->getURL(), $instituteDisplayLocationParams);?>" title='<?php echo html_escape($instituteObject->getName()); ?>'>
                    <h4 class="f14__semi clr__1 lh__18"><?php echo htmlentities(substr($instituteObject->getName(),0,73));?><?php if(strlen($instituteObject->getName())>73){echo '...'; }?></h4>
                  </a>
                      <span class="fit__block f11__normal clr__6 btm__3 lh__17">
                      <i class="msprite clg-loc"></i>
                      <?php
                          if(!empty($courseLocationInfo[$courseId])){
                            echo $courseLocationInfo[$courseId]['cityName'];
                        } 
                      ?>
                      </span> 
                      <?php
                      if(isset($shortlistedCoursesOfUser[$courseId])){
                        $class = 'act';
                      }else{
                        $class = '';
                      } ?>
                      <i class="ranking__sprite star__img <?php echo $class; ?> shortlist-site-tour btn-star" id='<?php echo "shortlist_{$courseId}";?>' trackingKeyId=<?php echo $shortlistTrackingKeyId; ?> customCallBack='listingShortlistCallback' cta-type="shortlist" pageType ='mRankingPage' <?php echo $tupleType=='course'?"track='on'":"ga-attr='SHORTLIST'";?>></i>
                      <?php
                        if($tupleType=='course'){
                      ?>
                  <p class="f12__normal clr__b btm__5"><a class="fit__in__block" title="<?php echo htmlentities($courseInfo[$courseId]->getName());?>" href="<?php echo $courseInfo[$courseId]->getURL();?>"> <?php echo htmlentities(substr($courseInfo[$courseId]->getName(),0,34));?><?php if(strlen($courseInfo[$courseId]->getName())>34){echo '...'; }?></a> </p>
                  <?php } ?>
                  <p class="f12__normal clr__1 btm__10 total__tags">
                  <?php
                    $instituteId = $pageData->getInstituteId();
                    if($reviewCounts[$instituteId]>0){ 
                        $reviewsUrl =  $instituteInfo[$instituteId]->getAllContentPageUrl('reviews');
                        if($tupleType=='course'){
                          $reviewsUrl = add_query_params($reviewsUrl,array("course"=>$courseId));
                        }
                        ?>
                    <span class="fit__in__block"> <a class="fit__in__block" href="<?=$reviewsUrl?>" ga-attr="ALLREVIEWS"><span><?=formatAmountToNationalFormat($reviewCounts[$instituteId],1,array('k','l', 'c'),'count')?></span><span class="blak">Reviews</span></a></span>
                  <?php 
                    } 
                    if($questionCounts[$instituteId] > 0){
                        $questionsUrl =  $instituteInfo[$instituteId]->getAllContentPageUrl('questions');
                        if($tupleType=='course'){
                          $questionsUrl = add_query_params($questionsUrl,array("course"=>$courseId));
                        }
                        ?>
                    <span class="fit__in__block"> <a class="fit__in__block" href="<?=$questionsUrl?>" ga-attr="ALLQUESTIONS"><span><?=formatAmountToNationalFormat($questionCounts[$instituteId],1,array('k','l', 'c'),'count')?></span><span class="blak">Question<?php echo $questionCounts[$instituteId]>1?"s":""; ?></span></a></span>
                    <?php }
                        if($courseCounts[$instituteId]>0){
                            $courseUrl =  $instituteInfo[$instituteId]->getAllContentPageUrl('courses');
                            if(!empty($allCoursePageUrlData)) {
                                $allCoursePageFilterUrl = $allCoursesPageLib->getUrlForAppliedFilters($allCoursePageUrlData, $instituteInfo[$instituteId]);
                                $courseUrl = $allCoursePageFilterUrl;
                            }
                    ?>
                    <span class="fit__in__block"> <a class="fit__in__block" href="<?=$courseUrl?>" ga-attr="ALLCOURSES"><span><?=formatAmountToNationalFormat($courseCounts[$instituteId],1,array('k','l', 'c'),'count')?></span><span class="blak">Course<?php echo $courseCounts[$instituteId]>1?"s":""; ?></span></a></span>
                  <?php 
                    } 
                ?>
                  </p>
                  <?php
                    $brochureButtonDisable=False;
                    if($tupleType=='course'){
                     if(in_array($courseId, $brochuresMailed)){
                      $brochureButtonDisable=True;
                     }
                    }
                  ?>
                  <div class="clear__float tuple__btns">
                    <a href="javascript:void(0);" trackingKeyId='510' id='compare_<?php echo $courseId ?>' class="ranking__btns __transbtn compare-site-tour addToCmp" <?php echo $tupleType=='course'?"track='on'":"ga-attr='COMPARE'";?>>Compare</a>
                    <a href="javascript:void(0);" trackingKeyId='343' cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" customactiontype="ND_Ranking" class=" ranking__btns __prime deb-site-tour <?php echo $brochureButtonDisable? "btn-mb-dis":"dnldBrchr";?>" <?php echo $tupleType=='course'?"track='on'":"ga-attr='DEB'";?>><?php echo $brochureButtonDisable? "Brochure Mailed":"Download Brochure";?></a>
                <?php if($tupleType == 'course') {
                    $listingType = 'course';
                    $listingId = $courseId;
                    $listingName = $courseInfo[$courseId]->getName();
                } else {
                    $listingType = 'institute';
                    $listingId = $instituteId;
                    $listingName = html_escape($instituteObject->getName());
                } ?>

                <input type="hidden" name="tupleInfo"
                    listingId='<?php echo $listingId ?>' 
                    listingType='<?php echo $listingType ?>' 
                    listingName='<?php echo $listingName ?>'
                >  
              </div>
            </div>
            </section>
<?php }
}
 ?>