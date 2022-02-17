 
<div class="clg-blok dflt__card">
      <h2><?php echo $sectionNameMapping[$section]?></h2>
  <div class="imp__dates">
  <ul>
    <?php 
    $wikiObj = $examContent['importantdates']['wiki'][0];
    if(!empty($wikiObj)){
    $impDatesWiki = $wikiObj->getEntityValue();
    $wikiLabel = new tidy ();
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
    <p class="f14__clr3"><?php echo $wikiLabel; ?></p>
  <?php } ?>
  </div>
</div>