<?php
$catPageTitleFix = str_replace("in All Countries","Abroad",$catPageTitle);
?>
<div class="header-unfixed">
    <?php //if(!$isZeroResultPage){?>
    <section class="filter-tabs">
        <a href="#filterContainer" class="first-tab" data-transition="slide" data-inline="true" data-rel="dialog" onclick="selectFilterTab('examTab');">
            <div>
                <i class="sprite filter-icn flLt"></i>
                <p>Filters<br /><small id="appliedFilterCount"><?= ($appliedFilterCount >0)?$appliedFilterCount." applied":"None applied"?></small></p>
            </div>
        </a>
        <a href="#sorterContainer" data-transition="slide" data-inline="true" data-rel="dialog">
            <div>
                <i class="sprite sort-icn flLt"></i>
                <p class="sort-text">Sort<br /><small><?=$sortByText?></small></p>
            </div>
        </a>
    </section>
    <?php //}?>
    <div class="_topHeadingCont">
        <h1 class="_topHeading"><?= $catPageTitleFix?><?='&nbsp;('.$noOfUniversities.')'?></h1>
    </div>
</div>
