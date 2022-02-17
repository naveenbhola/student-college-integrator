<?php 
    $GA_Tap_On_Link = 'RECO_LINKS_';
    if(!empty($coursesWidgetData['rankingPageLinks'])){
        ?>
        <div class="crs-widget listingTuple">
            <h2 class="head-L2 intrstd-head">View colleges by ranking</h2>
            <div class="intrstd-clgWdgt">
                <ul>
                    <?php 
                        foreach ($coursesWidgetData['rankingPageLinks'] as $rankingPageRow) {
                            ?>
                            <li><a href="<?php echo $rankingPageRow['url'];?>" class="link-blue-small" ga-attr="<?php echo $GA_Tap_On_Link.'RANKING'; ?>"><?php echo $rankingPageRow['title'];?></a></li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
        </div>
        <?php
    }
    if(!empty($coursesWidgetData['categoryPageLinks'])){
        ?>
        <div class="crs-widget listingTuple">
            <h2 class="head-L2 intrstd-head">View colleges by location</h2>
            <div class="intrstd-clgWdgt">
                <ul>
                    <?php 
                        foreach ($coursesWidgetData['categoryPageLinks'] as $categoryPageRow) {
                            ?>
                            <li><a href="<?php echo $categoryPageRow['url'];?>" class="link-blue-small" ga-attr="<?php echo $GA_Tap_On_Link.'CATEGORY'; ?>"><?php echo $categoryPageRow['title'];?></a></li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
        </div>
        <?php
    }
?>
