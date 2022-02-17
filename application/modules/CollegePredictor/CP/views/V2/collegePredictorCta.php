<?php 
if($currentCourseId > 0){
    ?>
    <div class="tpl-rgtBx cta-btm-cont">
        <div class="rslt-cnt">
            <!-- <p>
                <?php 
                    if(!empty($courseData[$currentCourseId]['reviewCount'])){
                        ?>
                        <a href="<?php echo $courseData[$currentCourseId]['reviewsUrl']; ?>"><?php echo formatAmountToNationalFormat($courseData[$currentCourseId]['reviewCount'],1,array('k','l', 'c'),'count') ?> Reviews</a> 
                        <?php
                    }
                    if(!empty($courseData[$currentCourseId]['questionCount'])){
                        ?>
                        <a href="<?php echo $courseData[$currentCourseId]['questionsUrl']; ?>"><?php echo formatAmountToNationalFormat($courseData[$currentCourseId]['questionCount'],1,array('k','l', 'c'),'count') ?> <span>Question<?php echo $courseData[$currentCourseId]['questionCount']>1?"s":""; ?></span> </a> 
                        <?php
                    }
                    if(!empty($courseData[$currentCourseId]['articleCount'])){
                        ?>
                        <a href="<?php echo $courseData[$currentCourseId]['articlesUrl']; ?>"><?php echo formatAmountToNationalFormat($courseData[$currentCourseId]['articleCount'],1,array('k','l', 'c'),'count') ?> <span>Article<?php echo $courseData[$currentCourseId]['articleCount']>1?"s":""; ?></span> </a>
                        <?php
                    }
                ?>
            </p> -->
            <div class="txt-left">
                <a href="javascript:void(0);" id='shortlist_<?php echo $currentCourseId; ?>' shortlistCourseId="<?php echo $currentCourseId; ?>" trackingKeyId="<?php echo $shortlisttrackingPageKeyId; ?>" class="btn-star shortlist <?php echo isset($shortlistedCoursesOfUser[$currentCourseId]) ? 'shortlisted' : ''; ?>"></a>
                <a href="javascript:void(0);" id='compare_<?php echo $currentCourseId; ?>' compareCourseId="<?php echo $currentCourseId; ?>" trackingKeyId="<?php echo $comparetrackingPageKeyId; ?>" class="btn-blue addToCmp">
                    <span>Add To Compare</span>
                </a>
                <a href="javascript:void(0);" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" hideRecoLayer=1 <?php echo $defaultView && $validateuser === 'false' ? "reloadAfter=1":""; ?> courseName="<?php echo $courseData[$currentCourseId]['courseName']; ?>" clientCourseId="<?php echo $currentCourseId; ?>" trackingKeyId="<?php echo $brochuretrackingPageKeyId; ?>" class="btn-org <?php echo (isset($_COOKIE['applied_'.$currentCourseId]) && $_COOKIE['applied_'.$currentCourseId] == 1)? "disable-btn":"dnldBrchr";?>">Apply Now</a>

                <p style="display:none !important;" class="success-msg-listing <?php echo (isset($_COOKIE['applied_'.$currentCourseId]) && $_COOKIE['applied_'.$currentCourseId] == 1)? "":" hid";?>">Brochure successfully mailed</p>
            </div>
        </div>
    </div>
    <?php
}
?>