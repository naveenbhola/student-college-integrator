<?php $widgetData = $coursesData[$mainCourseId]['widgetData'];?>
<div class="rightCol-widgetSec">
    <h2 class="mostPopular-title">Fees and Affordability for <?php echo $courseNames[$mainCourseId]; ?> in <?php echo ucwords($countryObj->getName()); ?></h2>
    <div style="padding:10px 25px;" class="popular-univ-content">
        <div class="fee-twoCol flLt" style="border-right: 1px solid #ccc;position: relative;left: 0px;">
            <?php if ($widgetData['countryHomeFeeAffordability']['cheapest']['count'] > 0) { ?>
            <h3>Cheapest colleges</h3>
            <ul>
                <li>
                    <a href="<?php echo $widgetData['countryHomeFeeAffordability']['cheapest']['url'];?>" target="_blank"><i class="study-sprite blueStudy-arrw"></i>
                    <div class="tb-detail">
                        Cheapest <?php echo $courseNames[$mainCourseId]; ?> colleges in <?php echo ucwords($countryObj->getName()); ?><br>
                    </div>
                    </a>
                    <div class="tb-detail">
                        <span><?php echo $widgetData['countryHomeFeeAffordability']['cheapest']['count'];?> College<?php if($widgetData['countryHomeFeeAffordability']['cheapest']['count']!=1){ echo 's';}?> (by low to high fees)</span>
                    </div>
                </li>
            </ul>
            <?php } ?>
            <?php if ($widgetData['countryHomeFeeAffordability']['scholarship']['count'] > 0){ ?>
            <h3>With Scholarship</h3>
            <ul>
                <li>
                    <a href="<?php echo $widgetData['countryHomeFeeAffordability']['scholarship']['url'];?>" target="_blank"><i class="study-sprite blueStudy-arrw"></i>
                    <div class="tb-detail">
                        <?php echo $courseNames[$mainCourseId]; ?> colleges offering Scholarship in <?php echo ucwords($countryObj->getName()); ?><br>
                    </div>
                    </a>
                    <div class="tb-detail">
                        <span><?php echo $widgetData['countryHomeFeeAffordability']['scholarship']['count'];?> College<?php if($widgetData['countryHomeFeeAffordability']['scholarship']['count']!=1){ echo 's';}?></span>
                    </div>
                </li>
            </ul>
            <?php } ?>
            <?php if ($widgetData['countryHomeFeeAffordability']['public']['count'] > 0){ ?>
            <h3>Publicly Funded</h3>
            <ul>
                <li>
                    <a href="<?php echo $widgetData['countryHomeFeeAffordability']['public']['url'];?>" target="_blank"><i class="study-sprite blueStudy-arrw"></i>
                    <div class="tb-detail">
                        Publicly funded <?php echo $courseNames[$mainCourseId]; ?> colleges in <?php echo ucwords($countryObj->getName()); ?><br>
                    </div>
                    </a>
                    <div class="tb-detail">
                        <span><?php echo $widgetData['countryHomeFeeAffordability']['public']['count'];?> College<?php if($widgetData['countryHomeFeeAffordability']['public']['count']!=1){ echo 's';}?></span>
                    </div>
                </li>
            </ul>
            <?php } ?>
        </div>
        <?php $graphData = $widgetData['countryHomeFeeAffordability']['graphData']; ?>
        <?php if($graphData['total'] != ""){ ?>
            <div style="border-left: 1px solid #ccc;padding-left: 10px; right: 1px;position: relative;" class="fee-twoCol flRt">
                <h3>Average 1st year cost for <?php echo $courseNames[$mainCourseId]; ?> in <?php echo ucwords($countryObj->getName()); ?></h3>
                <p>Total 1st year cost = <?php echo $graphData['total']; ?></p>
                <div class="study-graph">
                    <?php if($graphData['firstYear']['average'] != ""){ ?>
                        <div class="big-bar" style="height:<?php echo (integer)(90*$graphData['firstYear']['ratio']); ?>px !important;">
                            <div class="bar-tooltip">
                                <span><?php echo $graphData['firstYear']['average']; ?></span>
                                <i class="study-sprite bar-arrw topPos"></i>
                            </div>
                            <strong class="graphsubtitle">Avg tution fees</strong>
                        </div>
                    <?php } ?>
                    <?php if($graphData['livingExpense']['average'] != ""){ ?>
                        <div class="small-bar" style="height:<?php echo (integer)(90*$graphData['livingExpense']['ratio']); ?>px !important">
                            <div class="bar-tooltip">
                                <span><?php echo $graphData['livingExpense']['average']; ?></span>
                                <i class="study-sprite bar-arrw"></i>
                            </div>
                            <strong class="graphsubtitle">Avg living expenses</strong>
                        </div>
                    <?php } ?>
                </div>
                <p style="font-size:10px;">
                    <?php if($graphData['firstYear']['average'] !=""){ ?>
                        Avg 1st year tuition fees is based on <?php echo $graphData['firstYear']['count'];?> <?php echo $courseNames[$mainCourseId]; ?> course<?php echo $graphData['firstYear']['count']==1?"":"s";?> in <?php echo ucwords($countryObj->getName()); ?>
                        <br/>
                    <?php } ?>
                    <?php if($graphData['livingExpense']['average'] != ""){ ?>
                        Avg living expense is based on <?php echo $graphData['livingExpense']['count']; ?> college<?php echo $graphData['livingExpense']['count']==1?"":"s";?> in <?php echo ucwords($countryObj->getName()); ?>
                    <?php } ?>
                </p>
            </div>
        <?php } ?>
    </div>
</div>


