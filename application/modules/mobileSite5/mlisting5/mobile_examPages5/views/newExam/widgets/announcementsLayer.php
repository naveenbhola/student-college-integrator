<?php 
  if($updates['totalUpdates']>1){
    $heading = "Updates";
  }else{
    $heading = "Update";
  }
?>
<div class="que-contn" id="announcementlayer" style="display:none;">
      <!--comment secion heading-->
       <div class="ex-hed">
          <div class="ex-tl"><p class="ex-titl">Latest <?=$heading?> about <?=$examName?></p></div>
          <div class="ex-clos"><a href="javascript:void(0);" data-rel="back">&times;</a></div>
       </div>
       <div class="lcard color-w f14 upd-list" id="all-updates">
            
        </div>
</div>