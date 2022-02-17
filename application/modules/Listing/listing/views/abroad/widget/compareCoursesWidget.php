<div class="institute-head clearwidth">
    <h3 class="font-14">Compare this course with similar courses</h3>
</div>
<div class="compr-wdgt">
    <p class="cm-crse">THIS COURSE</p>
    <ul>
        <li>
            <strong><?= cutString($courseObj->getUniversityName(),50).", " ?><span><?= $universityObj->getLocation()->getCountry()->getName(); ?></span></strong>
            <div class="crs-det">
                <img src="<?=$imgURL?>" width="172" height="114">
                <p class="crs-tl"><?= cutString($courseObj->getName(),40);?></p>
                <div class="inst-info clearfix">
                    <p>
                        <label class="flLt">Course Duration</label>
                        <span class="flLt"><?=$courseObj->getDuration()?></span>
                    </p>
                    <p>
                        <label class="flLt">1st Year Total Fees</label>
                        <span class="flLt"><?=str_replace("Lakhs","L",str_replace("Thousand","K",$courseFeesDisplableAmount));?></span>
                    </p>
                </div>
            </div>
        </li>
        <?php
            foreach ($compareData['recommendedCompareCourseData'] as $key => $course) {?>
                <li>
                    <strong><?= cutString($course['universityName'],50).", "?><span><?= $course['countryName'];?></span></strong>
                    <div class="crs-det">
                        <img src="<?=$course['universityImageURL']?>" width="172" height="114">
                        <p class="crs-tl"><?= cutString($course['courseName'],40);?></p>
                        <div class="inst-info clearfix">
                            <p>
                                <label class="flLt">Course Duration</label>
                                <span class="flLt"><?=$course['courseDuration']?></span>
                            </p>
                            <p>
                                <label class="flLt">1st Year Total Fees</label>
                                <span class="flLt"><?=str_replace("Lakhs","L",str_replace("Thousand","K",$course['courseFees']));?></span>
                            </p>
                        </div>
                    </div>
                </li>
        <?php }?>
    </ul>
</div>
<div class="cmp-btnAra">
    <a href="javascript:void(0);" class="cm-btn" compareWidgetTrackingId=1248 entityIds='<?=$compareData["compareCourseIds"]?>'>Compare with similar courses</a>
</div>