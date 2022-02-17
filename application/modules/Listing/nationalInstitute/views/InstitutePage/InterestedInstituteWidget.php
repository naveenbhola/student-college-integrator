<?php 
$GA_Tap_On_Link = 'RECO_LINKS_';
if(!empty($coursesWidgetData['categoryPageLinks']) || !empty($coursesWidgetData['rankingPageLinks'])){
    ?>
    <div class="recomend-col">
        <?php 
        if(!empty($coursesWidgetData['rankingPageLinks'])){
            ?>
            <div class="group-card bg-none gap listingTuple">
                <h2 class="head-1">View colleges by ranking</h2>
                <ul class="flex-ul clg-intrst">
                    <?php 
                        foreach ($coursesWidgetData['rankingPageLinks'] as $rankingPageRow) {
                            ?>
                            <li><a href="<?php echo $rankingPageRow['url'];?>" ga-attr="<?php echo $GA_Tap_On_Link.'RANKING'; ?>"><?php echo $rankingPageRow['title'];?></a></li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
            <?php
        }
        if(!empty($coursesWidgetData['categoryPageLinks'])){
            ?>
            <div class="group-card bg-none gap listingTuple">
                <h2 class="head-1">View colleges by location</h2>
                <ul class="flex-ul clg-intrst">
                    <?php 
                        foreach ($coursesWidgetData['categoryPageLinks'] as $categoryPageRow) {
                            ?>
                            <li><a target="_blank" href="<?php echo $categoryPageRow['url'];?>" ga-attr="<?php echo $GA_Tap_On_Link.'CATEGORY'; ?>"><?php echo $categoryPageRow['title'];?></a></li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>
