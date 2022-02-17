<?php 
    $GA_Tap_On_Link = 'RECO_LINKS_';
    if(!empty($coursesWidgetData['rankingPageLinks'])){
        ?>
        <div class="data-card pad10">
            <h2 class="color-3 f15 font-w6">View colleges by ranking</h2>
            <ul class="in-ul">
                <?php 
                    foreach ($coursesWidgetData['rankingPageLinks'] as $rankingPageRow) {
                        ?>
                        <li class="color-9"><a href="<?php echo $rankingPageRow['url'];?>" class="color-b f14 l-14 block ga-analytic" data-vars-event-name="<?php echo $GA_Tap_On_Link.'RANKING'; ?>"><?php echo $rankingPageRow['title'];?></a></li>
                        <?php
                    }
                ?>
            </ul>
        </div>
        <?php
    }
    if(!empty($coursesWidgetData['categoryPageLinks'])){
        ?>
        <div class="data-card pad10">
            <h2 class="color-3 f15 font-w6">View colleges by location</h2>
            <ul class="in-ul">
                <?php 
                    foreach ($coursesWidgetData['categoryPageLinks'] as $categoryPageRow) {
                        ?>
                        <li class="color-9"><a href="<?php echo $categoryPageRow['url'];?>" class="color-b f14 l-14 block ga-analytic" data-vars-event-name="<?php echo $GA_Tap_On_Link.'CATEGORY'; ?>"><?php echo $categoryPageRow['title'];?></a></li>
                        <?php
                    }
                ?>
            </ul>
        </div>
        <?php
    }
?>
