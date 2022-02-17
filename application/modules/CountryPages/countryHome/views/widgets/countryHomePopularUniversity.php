<?php
//_p($widgetsData);
$popularUniversitiesDetail = $widgetData['countryHomePopularUniversity'];
$totalUnivCount = $popularUniversitiesDetail['totalCount'];
?>
<div class="<?=($floatClass)?> popular-widget" style="height:345px;">
    <div class="popular-widget-title"><?=($widgetConfigData['countryHomePopularUniversity']['title'])?></div>
    <div class="popular-widget-detail">
        <?php if($totalUnivCount >1){?>
        <strong>Most viewed universities in last 21 days</strong>
        <?php }?>
        <ul class="pop-widget-list">
            <?php
            $count=0;
            foreach($popularUniversitiesDetail['universityData'] as $univArry){ $count++;?>
            <li class="<?= ($count==$totalUnivCount || $count==3)?"last":''?>">
                <div class="univ-img">
                    <a href="<?= $univArry['url'];?>" target="_blank">
                        <img src="<?= $univArry['photos'];?>" width="75" height="50" alt="<?= htmlentities($univArry['university_name'])?>" title="<?= htmlentities($univArry['university_name']);?>" />
                    </a>
                </div>
                <div class="univ-info">
                   <a href="<?= $univArry['url'];?>" target="_blank"><?= htmlentities(formatArticleTitle($univArry['university_name'],47));?></a>
                   <p>
                   <?php
                   $universityInfoText = '';
                    if($univArry['university_type'] != 'private' && $univArry['university_type'] != 'public'){
                                    $universityInfoText = 'Non-profit university';
                    }else{
                                    $universityInfoText = ucfirst($univArry['university_type'])." university";
                    }
                    if($univArry['establishment_year']){
                                    $universityInfoText .= ', Estd '.$univArry['establishment_year'];
                    }
                    
                    $location = $univArry['cityName'];
                    if($univArry['stateName']){
                                    if($univArry['cityName']){
                                            $location .= ', ';	
                                    }
                            $location .= $univArry['stateName'];	
                    }
                    
                   ?>
                   <?= $universityInfoText;?></p>
                   <p><?= $location;?></p>
                </div>
            </li>
            <?php }?>
        </ul>
        <?php if($totalUnivCount >3){?>
        <a href="<?= $popularUniversitiesDetail['viewAllUniversityPageUrl'];?>" class="flRt" >View all <?= $totalUnivCount;?> universities ></a>
        <?php }?>
        <div class="clearfix"></div>
    </div>
</div>
