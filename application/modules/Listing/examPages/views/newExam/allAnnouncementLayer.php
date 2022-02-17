<!--announcement list layer starts-->
<?php 
  if($updates['totalUpdates']>1){
    $heading = "Updates";
  }else{
    $heading = "Update";
  }
?>
<div class="layer-common" id="announcementlayer" style="display:none;">
   <div class="group-card pop-div">
      <a class="cls-head heplyr" data-layer="announcementlayer">&times;</a>
      <div>
          <div class="card__right mt__15 ext__div ps__rl">
            <div class="pd_btm_10 anmtLayerdiv"><h3 class="f16__clr3 fix__sec fnt__n">Latest <?=$heading?> about <?=$examName?></h3></div>
              <div class="" id="all-updates"></div>

          </div>
      </div>
   </div>
</div>
<!--announcement list layer ends-->