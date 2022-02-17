<?php 
  if($updates['totalUpdates']>1){
    $heading = "Updates";
  }else{
    $heading = "Update";
  }
?>
<div class="card__right mt__15 ext__div ps__rl global-box-shadow">
  <div class="anmtdiv"><h3 class="f16__clr3 fix__sec fnt__sb">Latest <?=$heading?> about <?=$examName?></h3> 
    <?php if($updates['totalUpdates']>5){?>
    <a class="ps__abs sm__btn" id="view-announcement" ga-attr="VIEW_ALL_UPDATES">View All</a>
    <?php } ?>
  </div>
    <div class="">
      <?php $this->load->view('/examPages/newExam/widgets/announcementList');?>

      <!--<div class="subscribe__div f16__clr3 fnt__sb">
        Get updates in your email <a class="sub__btn <?php if(isset($isSubscribe) && $isSubscribe){?> disable-btn <?php }?>" id="subEBtn" data-trackingKey="<?php echo $trackingKeyList['subscribe_to_latest_updates'];?>" ga-attr="SUBSCRIBE">Subscribe</a>
      </div>-->
  </div>
</div>
