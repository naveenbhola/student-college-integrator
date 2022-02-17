<?php
    $instituteId = $pageData->getInstituteId();
    $instituteObject = $instituteInfo[$instituteId];
    $courseId = $pageData->getCourseId();
    if(!empty($instituteObject)){

        if(!empty($courseLocationInfo[$courseId]) ) {

            $courseMainCity =$courseLocationInfo[$courseId]['cityId'];
            $instituteDisplayLocationParams = NULL; //check if multilocation
            $instituteMainLocation = $instituteObject->getMainLocation();
            if(!empty($instituteMainLocation) ) {
                if($courseMainCity!=$instituteMainLocation->getCityId()){
                    $instituteDisplayLocationParams = array('city' => $courseMainCity);
                }
       

?>
    <tr pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?>" instId="<?php echo $instituteId; ?>">
        <td width="7%" class="tac"><p class="f-26 color-1 fnt__sb">
            <?php 
            $rank = $pageData->getSourceWiseRank(); 
            if($rank[$currentSourceId]['rank']>0){echo $rank[$currentSourceId]['rank'];} else{ echo "-";}?>
        </p></td>
        
        <?php if($previousRankFlag){ ?>
        <td width="7%" class="tac"><p class="f-26 color-6">
            <?php if($rank[$previousSourceId]['rank']>0){echo $rank[$previousSourceId]['rank'];} else{ echo "-";}?>
        </p></td>
        <?php } ?>
        
        <td width="31%">
            <div class="clg_dv">
                <div class="ps__rl clg_dis">
                       <div class="clg_img">
                       <?php $instituteUrl=add_query_params($instituteObject->getURL(), $instituteDisplayLocationParams); ?>
                        <a ga-attr="INSTITUTEIMAGE" class="<?php if($instituteMediaInfo[$instituteId]['instituteThumbURL']!='') echo "clg_img_lnk"; ?>" href="<?php echo $instituteUrl;?>" target="_blank">
                        <img <?php echo $instituteMediaInfo[$instituteId]['instituteThumbURL']!=''? "data-original='".$instituteMediaInfo[$instituteId]['instituteThumbURL']."'":'' ?> src="//<?=IMGURL?>/public/images/ranking_default_desktop.png"  alt='<?php echo html_escape($instituteObject->getName()); ?>' class="lazy" />
                        </a>
                        <?php if($instituteMediaInfo[$instituteId]['totalMediaCount']>0) { ?> 
                        <a class="selfi__img ico-pht"  href="<?php echo $instituteUrl;?>#gallery" target="_blank"><?php  echo $instituteMediaInfo[$instituteId]['totalMediaCount']; ?></a>
                        <?php } ?>
                    </div>
                </div>
            
                <div class="clg_info">
                    <p class="color-1 fnt__sb"><a class="color-1 fnt__sb tdn" target="_blank" href="<?php echo $instituteUrl;?>" title='<?php echo html_escape($instituteObject->getName()); ?>'><?php echo htmlentities(substr($instituteObject->getName(),0,75));?><?php if(strlen($instituteObject->getName())>75){echo '...'; }?></a></p>
                    <span class="f-13 color-6 spn_loc">
                    <?php
                        if(!empty($courseLocationInfo[$courseId])){
                            echo $courseLocationInfo[$courseId]['cityName'];
                        } 
                    ?>
                    </span>
                    <?php
                    if($tupleType=='course'){
                        ?>
                        <div class="crs_det">
                            <a target="_blank" href="<?php echo $courseInfo[$courseId]->getURL();?>" title="<?php echo htmlentities($courseInfo[$courseId]->getName());?>" class="link_blue f-14"> <?php echo htmlentities(substr($courseInfo[$courseId]->getName(),0,35));?><?php if(strlen($courseInfo[$courseId]->getName())>35){echo '...'; }?></a>
                            <ul class="f-12">
                            <?php if(!empty($courseFees[$courseId])){  ?>
                                <li><label class="color-9 block">Fees <?php echo empty($courseFees[$courseId])?"":"(".$courseFees[$courseId]['unit'].")";?></label><p class="color-1"><?php echo empty($courseFees[$courseId])?"":$courseFees[$courseId]['value'];?></p></li>
                                <?php
                                }
                                   if($examNames[$courseId]['count']>0){
                                ?>
                                <li><label class="color-9 block">Exam</label>
                                            <div class="ps__rl">
                                                <?php echo $examNames[$courseId]['examString'];?>
                                                <?php if($examNames[$courseId]['count']>1){ ?>
                                                <a href="javascript:void(0);" class="link_blue f-12 block more_exams_handle">+<?=$examNames[$courseId]['count']-1?> more</a>
                                        
                                        <div class="more_exams hid">
                                            <?=$examNames[$courseId]['examMoreString']?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </li>
                                <?php 
                                    }
                                    
                                    $courseDuration=$courseInfo[$courseId]->getDuration();
                                    $courseDuration=$courseDuration['value']." ".$courseDuration['unit']; 
                                ?>    
                                <li><label class="color-9 block">Duration</label><p><?=$courseDuration?></p></li>
                            </ul>
                        </div>
                        <?php  } ?>

                </div>
            </div>
        </td>


        <td width="22%">
            <div class="RQC_blk">
                <ul>
                    <?php
                        $instituteId = $pageData->getInstituteId();
                        if($reviewCounts[$instituteId]>0){ 
                            $reviewsUrl =  $instituteInfo[$instituteId]->getAllContentPageUrl('reviews');
                            if($tupleType=='course'){
                            $reviewsUrl = add_query_params($reviewsUrl,array("course"=>$courseId));
                            }
                            ?>
                            <li><a target="_blank" href="<?=$reviewsUrl?>"  class='rank-rev' ga-attr="ALLREVIEWS">
                                <label><?=formatAmountToNationalFormat($reviewCounts[$instituteId],1,array('k','l', 'c'),'count')?></label>
                                <p class="ib__blck">Reviews <i class="ico-sprt ico-arw"></i></p>
                                <span class="srpHoverCntnt rank-hover"><p><?php echo $websiteTourContentMapping['Reviews'] ?></p></span>
                            </a></li>
                            <?php 
                        } 
                        if($questionCounts[$instituteId] > 0){
                            $questionsUrl =  $instituteInfo[$instituteId]->getAllContentPageUrl('questions');
                            if($tupleType=='course'){
                            $questionsUrl = add_query_params($questionsUrl,array("course"=>$courseId));
                            }
                            ?>
                        <li><a target="_blank" href="<?=$questionsUrl?>" class="rank-questions" ga-attr="ALLQUESTIONS">
                            <label><?=formatAmountToNationalFormat($questionCounts[$instituteId],1,array('k','l', 'c'),'count')?></label>
                            <p class="ib__blck">Question<?php echo $questionCounts[$instituteId]>1?"s":""; ?> <i class="ico-sprt ico-arw rt-arw"></i></p>
                            <span class="srpHoverCntnt rank-hover"><p><?php echo $websiteTourContentMapping['Questions'] ?></p></span>
                        </a></li>
                        <?php }
                            if($courseCounts[$instituteId]>0){
                                $courseUrl =  $instituteInfo[$instituteId]->getAllContentPageUrl('courses');
                                if(!empty($allCoursePageUrlData)) {
                                    $allCoursePageFilterUrl = $allCoursesPageLib->getUrlForAppliedFilters($allCoursePageUrlData, $instituteInfo[$instituteId]);
                                    $courseUrl = $allCoursePageFilterUrl;
                                }
                        ?>
                            <li><a target="_blank" href="<?=$courseUrl?>" class="rank-course" ga-attr="ALLCOURSES">
                                <label><?=formatAmountToNationalFormat($courseCounts[$instituteId],1,array('k','l', 'c'),'count')?></label>
                                <p class="ib__blck">Course<?php echo $courseCounts[$instituteId]>1?"s":""; ?> <i class="ico-sprt ico-arw"></i></p>
                                <span class="srpHoverCntnt rank-hover"><p><?php echo $websiteTourContentMapping['Course'] ?></p></span>
                            </a></li>
                            <?php 
                        } 
                    ?>
                </ul>
            </div>
        </td>
        <td width="33%">
            <div class="txt-right">
                <?php if(isset($shortlistedCoursesOfUser[$courseId])) {
                    $shortlistClass = 'shortlisted';
                } else {
                    $shortlistClass = '';
                } ?>
                <a href="javascript:void(0);" trackingKeyId='235' id='shortlist_<?php echo $courseId ?>' customCallBack='listingShortlistCallback' cta-type="shortlist" customactiontype="ND_Ranking" class="btn-star <?php echo $shortlistClass ?> shortlist-site-tour" <?php echo $tupleType=='course'?"track='on'":"ga-attr='SHORTLIST'";?>>
                <span class="srpHoverCntnt rank-hover"><p><?php echo $websiteTourContentMapping['Shortlist'] ?></p></span>    
                </a>
                <a href="javascript:void(0);" trackingKeyId='509' id='compare_<?php echo $courseId ?>' class="btn-blue addToCmp compare-site-tour" <?php echo $tupleType=='course'?"track='on'":"ga-attr='COMPARE'";?>><span>Add To Compare</span>
                <span class="srpHoverCntnt rank-hover"><p><?php echo $websiteTourContentMapping['Compare']; ?></p></span>
                </a>

                <a href="javascript:void(0);" trackingKeyId='234' cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" customactiontype="ND_Ranking" class="btn-org deb-site-tour <?php echo ($tupleType=='course' && isset($_COOKIE['applied_'.$courseInfo[$courseId]->getId()]) && $_COOKIE['applied_'.$courseInfo[$courseId]->getId()] == 1)? "disable-btn":"dnldBrchr";?>" <?php echo $tupleType=='course'?"track='on'":"ga-attr='DEB'";?>>Download Brochure
                    <span class="srpHoverCntnt rank-hover"><p><?php echo $websiteTourContentMapping['DEB']; ?></p></span>                
                </a>
                <?php if($tupleType=='course'){ ?>
                    <p class="success-msg-listing <?php echo ($tupleType=='course' && isset($_COOKIE['applied_'.$courseInfo[$courseId]->getId()]) && $_COOKIE['applied_'.$courseInfo[$courseId]->getId()] == 1)? "":" hid";?>">Brochure successfully mailed</p>
                <?php }?>
            </div>

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

        <?php /*if($tupleType == 'institute') {
            $this->load->view('nationalInstitute/AllContentPage/widgets/allContentCourseLayer', array('instituteCourses' => $rankingCriteriaCourses[$instituteId]));
        }*/ ?>
        </td>
    </tr>

<?php
            }
            else {
                error_log('RANKING_LOCATION_DATA_CORRUPT_FOR_INSTITUTE');
                error_log(print_r(array('instituteId'=>$instituteId,'courseId'=>$courseId),true));
            }
        }
        else{
            error_log('RANKING_LOCATION_DATA_CORRUPT_FOR_COURSE');
            error_log(print_r(array('instituteId'=>$instituteId,'courseId'=>$courseId),true));
        }
    }
?>
