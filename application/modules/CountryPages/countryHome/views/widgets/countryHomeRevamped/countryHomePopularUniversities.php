<?php $widgetData = $coursesData[$mainCourseId]['widgetData'];?>
<?php $countryHomePopularUniversities = $widgetData['countryHomePopularUniversities']; ?>
<div class="rightCol-widgetSec">
    <h2 class="mostPopular-title">Most popular <?php echo htmlentities($courseNames[$mainCourseId]); ?> colleges</h2>
    <div class="popular-univ-content">
        <div class="vertical-tabs">
            
            <?php $count = 0;
                foreach($countryHomePopularUniversities['universityData'] as $univId=>$univData){ ?>
            <div class="vertical-tabContent <?php echo 'univBlock'.$univId." ".($count===0?'':'hiddenTabContent');?>">
                <a class="content-title" href="<?php echo $univData['url']; ?>" target="_blank"><?php echo htmlentities(limitTextLength($univData['university_name'],37));?></a>
                <a href="<?php echo $univData['courseLink']; ?>" target="_blank">
                    <img width="300" height="200" alt="<?php echo htmlentities($univData['university_name']);?>" <?php echo ($count===0?'src':'data-original');?>="<?php echo htmlentities($univData['photos']);?>" class="lazy">
                </a>
                <div class="tabContent-info">
                    <a href="<?php echo $univData['courseLink'];?>" title="<?php echo $univData['courseName']; ?>" target="_blank"><?php echo limitTextLength($univData['courseName'],37);?></a>
                    <div class="tab-fee-detail">
                        <div style="width:45%;" class="flLt">
                            <p>1st year total fees</p>
                            <strong><?php echo $univData['courseFee']; ?></strong>
                        </div>
                        <div style="width:52%;" class="flRt">
                            <p>Eligibility</p>
                            <div class="elgblity-height">
                            <?php foreach($univData['courseExams'] as $courseExam){ ?>
                            <strong class="wightNrml"><?php echo $courseExam; ?></strong><br/>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $count++;} ?>
            
            <div class="vertical-tablist">
                <ul>
                    <?php $activeClass = "active";
                    foreach($countryHomePopularUniversities['universityData'] as $univId=>$univData){ ?>
                    <li class="<?php echo $activeClass; ?>">
                        <a href="Javascript:void(0);" customTarget="<?php echo $univId?>">
                            <i class="study-sprite tab-arrw"></i>
                            <div class="tb-detail">
                                <strong class="tablist-head" title="<?php echo htmlentities($univData['university_name']);?>"><?php echo htmlentities(limitTextLength($univData['university_name'],42));?></strong> 
                                <span><?php echo $univData['cityName'].", ".$univData['country']; ?></span>
                            </div>
                        </a>
                    </li>
                    <?php $activeClass = ""; } ?>
                </ul>
            </div>
            <a class="viewAll-link" href="<?php echo $countryHomePopularUniversities['viewAllUniversityPageUrl']; ?>" target="_blank">View <?php echo ($countryHomePopularUniversities['totalCount']>1?"all ".$countryHomePopularUniversities['totalCount']." universities":" university"); ?> in <?php echo $countryObj->getName(); ?> &gt;</a>
        </div>
    </div>
</div>

