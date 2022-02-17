<div class="recomend-col">
    <?php 
    if(!empty($interLinkingLinks['rankingPageLinks'])){
        ?>
        <div class="group-card bg-none gap">
            <h2 class="head-1">View colleges by ranking</h2>
            <ul class="flex-ul clg-intrst">
                <?php 
                    foreach ($interLinkingLinks['rankingPageLinks'] as $rankingPageRow) {
                        ?>
                        <li><a onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','RANKING_LINKS_COURSEDETAIL_DESKTOP','<?php echo $GA_userLevel;?>');" href="<?php echo $rankingPageRow['url'];?>"><?php echo $rankingPageRow['title'];?></a></li>
                        <?php
                    }
                ?>
            </ul>
        </div>
        <?php
    }
    if(!empty($interLinkingLinks['categoryPageLinks'])){
        ?>
        <div class="group-card bg-none gap">
            <h2 class="head-1">View colleges by location</h2>
            <ul class="flex-ul clg-intrst">
                <?php 
                    foreach ($interLinkingLinks['categoryPageLinks'] as $categoryPageRow) {
                        ?>
                        <li><a target="_blank" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','CAT_LINKS_COURSEDETAIL_DESKTOP','<?php echo $GA_userLevel;?>');" href="<?php echo $categoryPageRow['url'];?>"><?php echo $categoryPageRow['title'];?></a></li>
                        <?php
                    }
                ?>
            </ul>
        </div>
        <?php
    }
    ?>
</div>