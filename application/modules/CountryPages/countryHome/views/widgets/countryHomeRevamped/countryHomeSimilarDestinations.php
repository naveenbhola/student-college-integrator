<?php $countryRecommendations = $coursesData[$mainCourseId]['countryRecommendations']; ?>
<div style="text-align:center; margin:10px 0 6px;" class="download-widget clearwidth countryReco<?php echo $mainCourseId;?>">
    <i class="abroad-shadow abroad-shadow-top"></i>
    <p class="mba-count-hd">
       Study destinations similar to <?php echo $countryObj->getName(); ?> 
    </p>
    <ul class="mba-count-list">
        <?php foreach($countryRecommendations as $country){?>
        <li>
            <i style="vertical-align:top;" class="flags <?=str_replace(' ','',strtolower($country['countryObj']->getName()))?> flLt"></i>
            <div class="mba-count-txt">
                <p class="mba-count-name" style="padding-top:8px"><a href="<?php echo $country['canonicalUrl']; ?>" target="_blank"><?php $headingArr = explode(" - ",$country['seoTitle']); echo $headingArr[0];?></a></p>
            </div>
        </li>
        <?php } ?>
    </ul>
    <i class="abroad-shadow abroad-shadow-bottom"></i>
</div>