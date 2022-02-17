<?php 
 if($isHomePage)
 {
    $className = 'setHeight';
    $styleSet = ' style="height:360px;" ';
 }
?>
<section>
<div class="data-card wdg-card m15">
  <?php 
    if($groupYear)
        $groupYear = ' '.$groupYear;
     if($isHomePage)
     { ?>
      <h2 class="color-3 f16 heading-gap font-w6"><a href ="<?php echo $snippetUrl[$section]; ?>" class="color-3" ga-attr="IMPORTANT_DATES_VIEW_ALL"><?php echo $examName.$groupYear.' '.'Dates' ?></a></h2>
  <?php }else{
      ?>
      <!--<h1 class="color-3 f16 heading-gap font-w6"><?//=$h1?></h1>-->
  <?php } ?>
<div class="ss bar-line-col lcard color-w f14" style="position: relative;"> 
  <div class="<?=$className;?>" id="<?php echo $section.'det';?>" <?=$styleSet?>>
          <?php if(count($groupList)>1 && !$isHomePage){?>
        <p class="f14 color-3 change-brdr">Showing details for <strong class="font-w6"><?php echo $groupName;?> </strong>   
           <a href="javascript:void(0);" class="font-w4" id="changeCourse" ga-attr="CHANGE_COURSE">Change Course<i class="chnge-brnchico"></i></a>
        </p>
      <?php }?>    
    <p style="margin-bottom:10px;"><?=$datesString?></p>
    <div class="bar-line">
    <?php 
    $wikiObj = $examContent['importantdates']['wiki'][0];
    if(!empty($wikiObj)){
    $impDatesWiki = $wikiObj->getEntityValue();
    }
      foreach ($importantDatesData['dates'] as $key => $value) {
        foreach ($value as $key1 => $val1) { 
            $backgroundColor = '';
            if($val1['isUpcoming']){
              $backgroundColor = 'style="background:#ef5552;"';
            } ?>
            <?php
            if($val1['isOngoing']){
              $backgroundColor = 'style="background:#00cc66;"';
            } ?>           
            <div class="circle-block">
                <div <?=$backgroundColor;?> class="circ"></div>
                <div class="l-cnt">
                <p>
                <?php if($val1['startDate'] != '0000-00-00'){
                  echo $val1['startDate'];
                }
              if(($val1['startDate'] != $val1['endDate']) && ($val1['endDate'] != '0000-00-00')){
                echo " - ";
                echo $val1['endDate'];
              }?>
                </p>
                </div>
                <div class="r-cnt">
                    <p><?=$val1['event'];?></p>
                    <?php 
                    if($val1['isUpcoming']){ ?>   
                        <span class="upcoming-tap">UPCOMING</span>
                    <?php } 
                    if($val1['isOngoing']){ ?>   
                        <span class="ongoing-tap">ONGOING</span>
                    <?php } ?>
                </div>
            </div>
    <?php }}   ?>
  </div>
  <?php
   if(!empty($impDatesWiki)){ ?>
    <div class="f14__clr3 mbtm_sp"><?php echo html_entity_decode($impDatesWiki); ?></div>
  <?php } ?>
  </div>
     <?php if($isHomePage) { ?>
        <div class="btn-sec" id="<?php echo $section.'detRm';?>"> <a href ="<?php echo $snippetUrl[$section]; ?>" class="btn btn-secondary color-w color-b f14 font-w6 m-15top m-5btm " ga-attr="IMPORTANT_DATES_VIEW_ALL">View Details</a> </div>
      <?php } ?>
</div> 
</div>
</section>
<?php if(!$isHomePage){
    $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow')); 
}?>
