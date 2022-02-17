<?php 
if(!empty($interLinkingLinks['rankingPageLinks'])){
    ?>
    <div class="crs-widget listingTuple">
        <h2 class="head-L2 intrstd-head">View colleges by ranking</h2>
        <div class="intrstd-clgWdgt">
            <ul>
            <?php
                foreach ($interLinkingLinks['rankingPageLinks'] as $rankingPageRow) {
                    ?>
                    <li>
                        <a onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','RANKING_LINKS_COURSEDETAIL_MOBILE','<?php echo $GA_userLevel;?>');" class="link-blue-small" href="<?php echo $rankingPageRow['url'];?>"><?php echo $rankingPageRow['title'];?></a>
                    </li>
                    <?php
                }
            ?>
            </ul>
        </div>
    </div>
    <?php
}
if(!empty($interLinkingLinks['categoryPageLinks'])){
    ?>
    <div class="crs-widget listingTuple">
        <h2 class="head-L2 intrstd-head">View colleges by location</h2>
        <div class="intrstd-clgWdgt">
            <ul>
            <?php
                foreach ($interLinkingLinks['categoryPageLinks'] as $categoryPageRow) {
                    ?>
                    <li>
                        <a target="_blank" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','CAT_LINKS_COURSEDETAIL_MOBILE','<?php echo $GA_userLevel;?>');" class="link-blue-small" href="<?php echo $categoryPageRow['url'];?>"><?php echo $categoryPageRow['title'];?></a>
                    </li>
                    <?php
                }
            ?>
            </ul>
        </div>
    </div>
    <?php
}
?>