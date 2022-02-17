<?php $widgetData = $coursesData[$mainCourseId]['widgetData']; ?>
<div class="clearfix"></div>
<h2 class="popular-title">Fees and Affordability for <?php echo $courseNames['mainCourseId']; ?> in <?php echo $countryObj->getName(); ?> </h2>
<?php
    $graphData = $widgetData['countryHomeFeeAffordability']['graphData'];
    if($graphData['total'] != ""){ ?>
        <div class="fee-expense-widget clear">
            <h3 class="avrgCost">Average 1st year cost for <?php echo $courseNames['mainCourseId']; ?> in <?php echo $countryObj->getName(); ?></h3>
            <p>Total 1st year cost = <?php echo $graphData['total']; ?></p>
        
            <div class="study-graph">
                <?php if($graphData['firstYear']['average'] != ""){ ?>
                <div class="big-bar"  style="height:<?php echo (integer)(90*$graphData['firstYear']['ratio']); ?>px;"><div class="bar-tooltip"><i class="study-sprite bar-arrw"></i><?php echo $graphData['firstYear']['average']; ?></div><strong class="graphsubtitle">Avg tution fees</strong></div>
                <?php } ?>
                <?php if($graphData['livingExpense']['average'] != ""){ ?>
                <div class="small-bar"  style="height:<?php echo (integer)(90*$graphData['livingExpense']['ratio']); ?>px;"><div class="bar-tooltip"><i class="study-sprite bar-arrw"></i><?php echo $graphData['livingExpense']['average']; ?></div><strong class="graphsubtitle">Avg living expenses</strong></div>
                <?php } ?>
            </div>
        
            <p style="font-weight:500; font-size:11px;">
                <?php if($graphData['firstYear']['average'] !=""){ ?>
                    Avg 1st year tuition fees is based on <?php echo $graphData['firstYear']['count'];?> <?php echo $courseNames['mainCourseId']; ?> course<?php echo $graphData['firstYear']['count']==1?"":"s";?> in <?php echo ucwords($countryObj->getName()); ?>
                    <br/>
                <?php } ?>
                <?php if($graphData['livingExpense']['average'] != ""){ ?>
                    Avg living expense is based on <?php echo $graphData['livingExpense']['count']; ?> college<?php echo $graphData['livingExpense']['count']==1?"":"s";?> in <?php echo ucwords($countryObj->getName()); ?>
                <?php } ?>
            </p>
        </div>
<?php } ?>

<div class="countryPage-widget">
    <?php if($widgetData['countryHomeFeeAffordability']['cheapest']['count']>0){ ?>
    <h3 class="widget-title">Cheapest colleges</h3>
    <ul>
        <li>
            <a href="<?php echo $widgetData['countryHomeFeeAffordability']['cheapest']['url']; ?>"><strong>Cheapest <?php echo $courseNames['mainCourseId']; ?> colleges in <?php echo $countryObj->getName(); ?></strong></a>
            <span><?php echo $widgetData['countryHomeFeeAffordability']['cheapest']['count']; ?> College<?php echo ($widgetData['countryHomeFeeAffordability']['cheapest']['count']>1?'s':''); ?> (by low to high fees)</span>
        </li>
    </ul>
    <?php } ?>
    <?php if($widgetData['countryHomeFeeAffordability']['scholarship']['count']>0){ ?>
    <h3 class="widget-title">With Scholarship</h3>
    <ul>
        <li>
            <a href="<?php echo $widgetData['countryHomeFeeAffordability']['scholarship']['url']; ?>"><strong><?php echo $courseNames['mainCourseId']; ?> colleges offering scholarships in <?php echo $countryObj->getName(); ?></strong></a>
            <span><?php echo $widgetData['countryHomeFeeAffordability']['scholarship']['count']; ?> College<?php echo ($widgetData['countryHomeFeeAffordability']['scholarship']['count']>1?'s':''); ?></span>
        </li>
    </ul>
    <?php } ?>
    <?php if($widgetData['countryHomeFeeAffordability']['public']['count']>0){ ?>
    <h3 class="widget-title">Publicly Funded</h3>
    <ul>
        <li>
            <a href="<?php echo $widgetData['countryHomeFeeAffordability']['public']['url']; ?>"><strong>Publicly Funded <?php echo $courseNames['mainCourseId']; ?> colleges in <?php echo $countryObj->getName(); ?></strong></a>
            <span><?php echo $widgetData['countryHomeFeeAffordability']['public']['count']; ?> College<?php echo ($widgetData['countryHomeFeeAffordability']['public']['count']>1?'s':''); ?></span>
        </li>
    </ul>
    <?php } ?>
</div>