<div class="rnk_popup hid"  id="location_layer">
    <div class="rnk_hed">
      <div class="rnk_tl"><p class="rnk_titl">Top Ranked Colleges in</p></div>
      <div class="rnk_clos"><a href="javascript:void(0);" class='closeRankingLayer'>×</a></div>
   </div>
    <div class="rnk_Pdiv color-6">
        <strong id="cityLayer" class="block f-16 color-1 tp-blk">Cities</strong>
         <ul>
        <?php foreach ($filters['city'] as $city) { 
                   ?>
                   <li><a href="<?=$city->getUrl()?>" ga-attr="RELATEDRANKING"><?=$city->getName()?></a></li>
               <?php } ?>   
         </ul>

        <strong id="stateLayer" class="block f-16 color-1">States</strong>
        <ul>
       <?php foreach ($filters['state'] as $states) { 
                  ?>
                  <li><a href="<?=$states->getUrl()?>" ga-attr="RELATEDRANKING"><?=$states->getName()?></a></li>
              <?php } ?>   
        </ul>
        
    </div>
</div>
<div class="rnk_popup hid"  id="exam_layer">
    <div class="rnk_hed">
      <div class="rnk_tl"><p class="rnk_titl"><?=$interlinkingWidgetHeading['exam']?></p></div>
      <div class="rnk_clos"><a href="javascript:void(0);" class='closeRankingLayer'>×</a></div>
   </div>
    <div class="rnk_Pdiv color-6">
        <strong class="block f-16 color-1">Exams</strong>
        <ul>
       <?php foreach ($filters['exam'] as $exam) { 
                  ?>
                  <li><a href="<?=$exam->getUrl()?>" ga-attr="RELATEDRANKING"><?=$exam->getName()?></a></li>
              <?php } ?>   
        </ul>
    </div>
</div>