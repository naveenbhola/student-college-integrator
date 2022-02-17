 <?php 
 if($isHomePage)
 {
    $className = 'setHeight';
    $styleSet = ' style="height:270px;" ';
 }
 ?>
<div class="dflt__card mt__15 examTuple no__pad" id="<?php echo $section;?>">
  <?php 
    if($groupYear)
      $groupYear = ' '.$groupYear;
    if($isHomePage)
     { ?>
      <h2 class="mt__10 f20__clr3"><a href ="<?php echo $snippetUrl['importantdates']; ?>" style="color: inherit;" ga-attr="<?php echo str_replace(' ', '_', strtoupper('Important Dates'))?>_VIEW_ALL"><?php echo $examName.$groupYear.' '.'Dates'?></a></h2>
  <?php }else{ ?>
 <!--     <h1 class="mt__10 f20__clr3"><?//=$h1?></h1>-->
  <?php } ?>

  <div class="imp__dates <?=$className;?> pad__16" id="<?php echo $section.'det';?>" <?=$styleSet?>>
  

  <div class="data_change">
    <?php if(count($groupList)>1 && !$isHomePage){?>
      <p class="f14__clr3 chngCrs"> Showing details for <strong><?php echo $groupName;?> </strong>            
          <a class="fnt__n" style="cursor:pointer" id="changeCourse">Change Course<i class="chnge-brnchico"></i></a>
      </p>
    <?php } ?>
    </div>


  <p class="f14__clr3"><?=$datesString?></p>
  <ul>
    <?php 
    $wikiObj = $examContent['importantdates']['wiki'][0];
    if(!empty($wikiObj)){
    $impDatesWiki = $wikiObj->getEntityValue();
    $wikiLabel = new tidy ();
    $impDatesWiki = addTargetBlankInWikiData($impDatesWiki);
    $wikiLabel->parseString (htmlspecialchars_decode($impDatesWiki) , array ('show-body-only' => true ), 'utf8' );
    $wikiLabel->cleanRepair();
    }
    foreach ($importantDatesData['dates'] as $key => $value) { ?>
      <label class="label__table f14__clr6"><?php echo $key;?></label>
      <?php foreach ($value as $key1 => $val1) { ?>
      <li>
        <div class="col__result">
        <strong class="f14__clr6 fnt__sb ib__block">
          <?php 
          if($val1['startDate'] != '0000-00-00'){
            echo $val1['startDate'];
          }
          if(($val1['startDate'] != $val1['endDate']) && ($val1['endDate'] != '0000-00-00')){
            echo " - ";
            echo $val1['endDate'];
          }?>
        </strong>
        <p class="label__data ib__block f14__clr3 lb_dat"><?=$val1['event']?>
        <?php if($val1['isUpcoming']){?>
           <span class="label__chip">UPCOMING</span></p> 
        <?php } ?>
        <?php if($val1['isOngoing']){?>
           <span class="label__chip_up">ONGOING</span></p> 
        <?php } ?>

        </div>
      </li>
    <?php } }?>
  </ul>
  <?php
  if(!empty($wikiLabel)){ ?>
    <div class="f14__clr3 mbtm_sp"><?php echo $wikiLabel; ?></div>
  <?php } ?>
  </div>
  <?php if($isHomePage) { ?>
    <div class="pd__top__10 txt__cntr mtop__10 pad__16" id="<?php echo $section.'detRm';?>">
      <a href ="<?php echo $snippetUrl['importantdates']; ?>" class="blue__brdr__btn arrow_after" ga-attr="<?php echo str_replace(' ', '_', strtoupper('Important Dates'))?>_VIEW_ALL">View Details</a>
    </div>
  <?php } ?>
</div>
