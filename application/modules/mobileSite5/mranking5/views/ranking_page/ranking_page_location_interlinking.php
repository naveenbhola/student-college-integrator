<?php
$stateFilterCount = count($filters['state']);
$cityFilterCount  = count($filters['city']);

if($stateFilterCount > 0 || $cityFilterCount > 0) { ?>
    <section class="clear__float">
        <article class="top__5 top__clgs">
            <h3 class="f14__semi clr__1"><?=$interlinkingWidgetHeading['location']?></h3>
            <?php if($cityFilterCount > 0) { ?>
                <div class="rl__cities clear__float">
                    <ul class="rl__ces clear__float top__5">
                        <li>
                            <h4>Cities</h4>
                        </li>
                        
                        <?php $count = 1;
                        foreach ($filters['city'] as $key => $locationFilter) {
                            if($count > 4) break; ?>
                            <li>
                                <a href="<?=$locationFilter->getUrl();?>"  ga-attr="RELATEDRANKING"><?=$locationFilter->getName();?></a>
                            </li>
                        <?php $count++;
                        } ?>
                    </ul>
                    <?php if($cityFilterCount > 4) { ?>
                        <a href="javascript:void();" class="ranking__btns __transbtn top__5 locationViewAll" layerType='city' >View All</a>
                    <?php } ?>
                </div>
            <?php } ?>
            
            <?php if($stateFilterCount > 0) { ?>
                <div class="rl__cities clear__float">
                    <ul class="clear__float top__5">
                        <li>
                            <h4>States</h4>
                        </li>
                        
                        <?php $count = 1;
                        foreach ($filters['state'] as $key => $locationFilter) {
                            if($count > 4) break; ?>
                            <li>
                                <a href="<?=$locationFilter->getUrl();?>"  ga-attr="RELATEDRANKING"><?=$locationFilter->getName();?></a>
                            </li>
                        <?php $count++;
                        } ?>
                    </ul>
                    <?php if($stateFilterCount > 4) { ?>
                        <a href="javascript:void();" class="ranking__btns __transbtn top__5 locationViewAll" layerType='state'>View All</a>
                    <?php } ?>
                </div>
            <?php } ?>
        </article>
    </section>
<?php } ?>