<?php 
$stateFilterCount = count($filters['state']);
$cityFilterCount  = count($filters['city']);
$examFilterCount  = count($filters['exam']);
?>
<h2 class="f-20 fnt__sb">Related Rankings</h2>
<div class="rltd_wdgt">
<?php 
    $floatClass = 'lft-rnkng';
    $locationStyle = "style='width:60%'";
    if(count($filters['exam']) > 1) { 
        $floatClass = 'fRight';
        $locationStyle = '';
        ?>
    <div class="rlt_blk color-6">
        <h3 class="f-16 fnt__sb"><?=$interlinkingWidgetHeading['exam']?></h3>
        <ul>
            <?php 
                $count = 0;
                foreach($filters['exam'] as $examFilter) { 
                    $count++;
                    if($count == 1) continue;
                    if($count > 10) break;
                ?>
                <li><a href="<?=$examFilter->getUrl();?>" ga-attr="RELATEDRANKING"><?=$examFilter->getName();?></a></li>
            <?php } ?>
            <?php if($examFilterCount > 9) { ?>
                <li class="no_mrgn"><a href="javascript:void(0);" class="f-12 vw-al fnt__sb viewLayerRankingPage" id="vwAll" layerType='examLayer'>View All</a></li>
            <?php } ?> 
        </ul>
    </div>
<?php } 
if($stateFilterCount > 0 || $cityFilterCount > 0)
?>
    <div class="rlt_blk color-6 <?=$floatClass?>" <?=$locationStyle?>>
        <h3 class="f-16 fnt__sb m-lft"><?=$interlinkingWidgetHeading['location']?></h3>
        <?php if($cityFilterCount > 0) { ?>
        <?php } ?>
        <ul>
            <li><h4 class="f-14 color-1 fnt__sb ct-wdt">Cities</h4></li>
        <?php 
            $count = 0;
            foreach ($filters['city'] as $locationFilter) { 
                $count++;
                if($count > 4) break; ?>
                <li><a href="<?=$locationFilter->getUrl();?>" ga-attr="RELATEDRANKING"><?=$locationFilter->getName();?></a></li>
        <?php } 
              
              if($cityFilterCount > 4) { ?>
                <li class="no_mrgn"><a href="javascript:void(0);" class="f-12 vw-al fnt__sb viewLayerRankingPage" layerType='cityLayer'>View All</a></li>
            <?php } ?> 
        </ul>
    <?php if($stateFilterCount > 0) { 
        ?>
        <ul>
            <li><h4 class="f-14 color-1 fnt__sb ct-wdt">States</h4></li>
        <?php 
            $count = 0;
            foreach ($filters['state'] as $count => $locationFilter) { 
                $count++;
                if($count > 4) break;
            ?>
            <li><a href="<?=$locationFilter->getUrl()?>" ga-attr="RELATEDRANKING"><?=$locationFilter->getName()?></a></li>
        <?php } 
              if($stateFilterCount > 4) { ?>
                <li class="no_mrgn"><a href="javascript:void(0);" class="f-12 vw-al fnt__sb viewLayerRankingPage" layerType='stateLayer'>View All</a></li>
        <?php }
        ?>
        </ul>
    <?php } ?>
    </div>
    <div class="clr"></div>
</div>